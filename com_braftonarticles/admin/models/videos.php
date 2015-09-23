<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_ADMINISTRATOR.'/components'.'/com_braftonarticles'.'/models'.'/parent.php');

class BraftonArticlesModelVideos extends BraftonArticlesModelParent
{
	//takes source URL and destination URL, uses cURL or fOpen, depending on the loading mechanism (chosen by Parent)
	protected function saveImage($source, $dest)
	{
		if ($this->loadingMechanism == "cURL")
		{
			$ch = curl_init($source);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			
			$image = curl_exec($ch);
			curl_close($ch);
			
			$result = file_put_contents($dest, $image);
			return $result !== false;
		}
		else if ($this->loadingMechanism == "allow_url_fopen")
		{
			return @copy($source, $dest);
		}
		
		return false;
	}

	//takes an "article" object (as dictated by Adfero libraries)
	function loadVideo($article)
	{
		//assign the XML data specific to this article to $thisArticle, for handling
		$thisArticle = $this->client->Articles()->Get( $article->id );
        if($this->debug){
		  JLog::add(sprintf('Loading article "%s" (%d).', trim($thisArticle->fields['title']), $article->id), JLog::DEBUG, 'com_braftonarticles');
        }
		
		$content = $this->getTable('content');
		$data = $this->convertToContent($article);
		
		if (!$content->save($data))
			return $content->getError();
		
		$brafContent = $this->getTable('braftoncontent');
		$listing = array('content_id' => $content->id, 'brafton_content_id' => $article->id, 'id' => null);
		
		if (!$brafContent->save($listing))
			return $brafContent->getError();
		
		return true;
	}

	//loop through all article items in feed, check they haven't been imported, and attempts to save them
	public function loadVideos()
	{
		$this->options->load('feed-number');
		$feedNum = $this->options->value;

		$feeds = $this->client->Feeds();
		$feedList = $feeds->ListFeeds(0, 10);

		$articles = $this->client->Articles();

		//array of news article items from XML (note: contains no information beyond ID)
		$articleList = $articles->ListForFeed( $feedList->items[$feedNum]->id, 'live', 0, 100);

		$addedCount = 0;
		$errored = false;
		
		foreach ($articleList->items as $article)
		{
			$contentId = $this->getContentId($article);
			
			if (!$this->articleExists($article))
			{
				$result = $this->loadVideo($article);
				if ($result !== true)
				{
					JLog::add(sprintf('Error: Failed to update article: %s', $result), JLog::ERROR, 'com_braftonarticles');
					$errored = true;
				}
				else
					$addedCount++;
			}
		}
		
		if ($addedCount > 0)
			JLog::add(sprintf('Loaded %d article%s.', $addedCount, ($addedCount == 1 ? '' : 's')), JLog::DEBUG, 'com_braftonarticles');
	}

	private function articleExists($article)
	{
		if (!$article)
			return false;
		
		//if any ID is returned by getContentId, return "true", otherwise "false"
		return !!$this->getContentId($article);
	}

	// content id = article's "Joomla ID" (its ID in the content table)
	private function getContentId($article)
	{
		$brafArticleId = $article->id;
		
		$db = JFactory::getDbo();
		$q = $db->getQuery(true);
		
		$q->select('content_id')->from('#__brafton_content')->where('brafton_content_id = ' . $q->q($brafArticleId));
		$db->setQuery($q);
		
		return $db->loadResult();
	}

	//Consumes XML feed, converts to a format usable by Joomla's "save" function
	protected function convertToContent($article, $contentId = null, $ignore = array())
	{
		//assign the XML data specific to this article to $thisArticle, for handling
		$thisArticle = $this->client->Articles()->Get( $article->id );

		//array containing article data, structured for Joomla's "save" function
		$data = array();
		
		$data['id'] = $contentId;
		$data['modified'] = $thisArticle->fields['lastModifiedDate'];
		
		if (!in_array('title', $ignore))
			$data['title'] = trim($thisArticle->fields['title']);
		
		if (!in_array('alias', $ignore))
			$data['alias'] = preg_replace(array('/[^a-zA-Z0-9]+/', '/^-+/', '/-+$/'), array('-', '', ''), strtolower($thisArticle->fields['title']));
		
		$introText = $thisArticle->fields['extract'];
		$fullText = $thisArticle->fields['content'];
        
        //load the video embeed codepress_supported_langs
        $embeedCode = $this->generate_embeed_code($article, $thisArticle);
        $fullText = $embeedCode.$fullText;
		
		//set up photos related variables
		$photos = $this->client->ArticlePhotos();
		$scale_axis = 150;
		$scale = 150;

		$thisPhotos = $photos->ListforArticle( $article->id, 0, 100 );

		// photos are optional; having none is a valid state.
		if ( isset($thisPhotos->items[0]->id) )
		{
			$photoId = $photos->Get( $thisPhotos->items[0]->id )->sourcePhotoId;

			//if I'm not mistaken, "scale_axis" and "scale" are dictating the size of the generated image
			//only pull thumbnail sized image for excerpt, push embed code into main body, in lieu of image
			//mess with $scale_axis and $scale to change thumbnail size
			$photoURL = $this->photoClient->Photos()->GetScaleLocationUrl( $photoId, $scale_axis, $scale )->locationUri;
			$photoURL = strtok($photoURL, '?');

			//grab Joomla image location information
			$imagesFolder = JPATH_ROOT . '/images';
			//$imagesUrl = JURI::base(true) . '/images';
            $imagesUrl = JURI::root(true). '/images';
			//modify article title to make appropriate Image filename
			$filename = preg_replace(array('/[^a-zA-Z0-9]+/', '/^-+/', '/-+$/'), array('-', '', ''), strtolower($thisArticle->fields['title']));
			
			$imageFilename = null;
			$imageSaved = false;
			
			$imageFilename = $filename . '.' . pathinfo($photoURL, PATHINFO_EXTENSION);
			$imagePath = $imagesFolder . "/$imageFilename";
			
			//attempt to save image, if successful, push into main body content, with markup
			if ($this->saveImage($photoURL, $imagePath))
			{
				$imageSaved = true;
				$localUrl = $imagesUrl . "/$imageFilename";
				$imageMarkup = sprintf('<div class="figure figure-thumbnail"><img src="%s" alt="%s" title="%s" class="article-image" /></div>', $localUrl, $thisArticle->fields['title'], $thisArticle->fields['title']);
				$introText = $imageMarkup . $introText;
			}
			else
				JLog::add(sprintf('Notice: Failed to save image %s (attached to article %s (%d)).', $photoURL, trim($thisArticle->fields['title']), $thisArticle->fields['id']), JLog::NOTICE, 'com_braftonarticles');
		}
		
		if (!in_array('introtext', $ignore))
			$data['introtext'] = $introText;
		
		if (!in_array('fulltext', $ignore))
			$data['fulltext'] = $fullText;
		
		if (!in_array('state', $ignore))
		{
			$this->options->load('published-state');
			$publishedState = $this->options->value;
			
			if ($publishedState == 'Unpublished')
				$data['state'] = 0;
			else
				$data['state'] = 1;
		}
		
		$this->options->load('import-order');
		$importOrder = $this->options->value;

		//Video feed only supports created and last modified dates
		if (!in_array('created', $ignore))
		{
			if ($importOrder == 'Last Modified Date')
				$data['created'] = $thisArticle->fields['lastModifiedDate'];
			else
				$data['created'] = $thisArticle->fields['date'];
			
			if (!in_array('publish_up', $ignore))
				$data['publish_up'] = $data['created'];
		}
		
		if (!in_array('created_by', $ignore))
		{
			$this->options->load('author');
			$data['created_by'] = $this->options->value;
		}
        
		if (!in_array('catid', $ignore))
		{     
            $a_categories = $this->client->Categories();
            $cat_id = $a_categories->ListForArticle($article->id, 0, 100)->items[0]->id;
            if($this->debug){
			 JLog::add(sprintf('the category id is %s', $cat_id ), JLog::WARNING, 'com_braftonarticles');
            }
            
            $category = $cat_id;
            $catId = $this->getCategoryId($category);

            if (!$catId)
                JLog::add(sprintf('the category id is %s', $cat_id ), JLog::WARNING, 'com_braftonarticles');
            else
                $data['catid'] = $catId;
			
			
		}
		
		if (!in_array('language', $ignore))
			$data['language'] = '*';
		
		
		if (!in_array('metadesc', $ignore))
			$data['metadesc'] = trim($thisArticle->fields['extract']);
		
		if (!in_array('attribs', $ignore))
			$data['attribs'] = '{"show_title":"","link_titles":"","show_intro":"","show_category":"","link_category":"","show_parent_category":"","link_parent_category":"","show_author":"","link_author":"","show_create_date":"","show_modify_date":"","show_publish_date":"","show_item_navigation":"","show_icons":"","show_print_icon":"","show_email_icon":"","show_vote":"","show_hits":"","show_noauth":"","urls_position":"","alternative_readmore":"","article_layout":"","show_publishing_options":"","show_article_options":"","show_urls_images_backend":"","show_urls_images_frontend":""}';
		
		if (!in_array('access', $ignore))
			$data['access'] = '1';
		
		if (!in_array('metadata', $ignore))
			$data['metadata'] = '{"robots":"","author":"","rights":"","xreference":""}';
		
		return $data;
	}

	private function getCategoryId($category)
	{
		$brafCatId = 9999999;
		
		$db = JFactory::getDbo();
		$q = $db->getQuery(true);
		$q->select('cat_id')->from('#__brafton_categories')->where('brafton_cat_id = ' . $q->q($category));
		$db->setQuery($q);
        if($return = $db->loadResult()){
            return $return;
        }
        else{
            //$db = JFactory::getDbo();
            //$q = $db->getQuery(true);
            JLog::add(sprintf('the category id %s did not exists.  Assigning master category.', $category ), JLog::WARNING, 'com_braftonarticles');
            //$q->select('value')->from('#__brafton_options')->where('option = ' . $q->quote('parent-category'));
            //$db->setQuery($q);
            //return $db->loadResult();
            $this->options->load('parent-category');
            return $this->options->value;
        }
	}
    private function generate_embeed_code($video, $article)
    {
        $videoOutClient = $this->videoClient->videoOutputs();
        $videoId = $video->id;
        $splash = array(
            'pre'   => $article->fields['preSplash'],
            'post'  => $article->fields['postSplash']
        );
        $videoList = $videoOutClient->ListForArticle($videoId,0,10);
        $list = $videoList->items;
        $embedCode = sprintf( "<video id='video-%s' class=\"ajs-default-skin atlantis-js\" controls preload=\"auto\" width='512' height='288' poster='%s' >", $videoId, $splash['pre'] );
        foreach($list as $listItem){
            $output=$videoOutClient->Get($listItem->id);
            $type = $output->type;
            $path = $output->path; 
            $resolution = $output->height; 
            $source = $this->generate_source_tag( $path, $resolution );
            $embedCode .= $source; 
        }
        $useCTA = true;
        $cta = '';
        if($useCTA){
            $this->options->load('pause-text');
            $pauseText = $this->options->value;
            $this->options->load('pause-link');
            $pauseLink = $this->options->value;
            $this->options->load('pause-asset-id');
            $pauseAsset = $this->options->value;
            $marpro = '';
            if($pauseAsset != '' || $pauseAsset != NULL){
                $marpro = "assetGateway: { id: '$pauseAsset' },";
            }
            $this->options->load('end-title');
            $endTitle = $this->options->value;
            $this->options->load('end-subtitle');
            $endSubtitle = $this->options->value;
            $this->options->load('end-text');
            $endButtonText = $this->options->value;
            $this->options->load('end-link');
            $endButtonLink = $this->options->value;
            $this->options->load('end-asset-id');
            $endAsset = $this->options->value;
            $endMarpro = '';
            if($endAsset != '' || $endAsset != NULL){
                $endMarpro = "assetGateway: { id: '$endAsset' },";
            }
            $this->options->load('end-background');
            $endBackground = $this->options->value;
            $videoBackground = '';
            if($endBackground != '' || $endBackground != NULL){
                $videoBackground = "background: '\'$endBackground\'',";
            }
            $cta = <<<CTA
                    ,
                    pauseCallToAction: {
                        $marpro
                        link: "$pauseLink",
                        text: "$pauseText"
                    },
                    endOfVideoOptions: {
                        $videoBackground
                        $endMarpro
                        callToAction: {
                            title: "$endTitle",
                            subtitle: "$endSubtitle",
                            button: {
                                link: "$endButtonLink",
                                text: "$endButtonText",
                            }
                        }
                    }
CTA;
        }
        $script = <<<SCRIPT
        <script type="text/javascript">
        var atlantisVideo = AtlantisJS.Init({
            videos: [{
                id: "video-{$videoId}"$cta
            }]
        });
        </script>
SCRIPT;
        $embedCode .= '</video>'.$script;
        return '<div id="post-single-video">'.$embedCode.'</div>';
    }
    private function generate_source_tag($src, $resolution)
    {
        $tag = ''; 
        $ext = pathinfo($src, PATHINFO_EXTENSION); 
        return sprintf('<source src="%s" type="video/%s" data-resolution="%s" />', $src, $ext, $resolution );
    }
}
?>