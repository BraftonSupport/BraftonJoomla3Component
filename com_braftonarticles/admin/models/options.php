<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
jimport('joomla.error.error');
/**
 * BraftonArticlesOptions Model
 */
class BraftonArticlesModelOptions extends JModelList
{
	protected $optionsTable;
	protected $authorTable;
    protected $loadingMechanism;
	
	function __construct() {
		JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components'.'/com_braftonarticles'.'/tables');
		$this->optionsTable = $this->getTable('braftonoptions');
        
        $allowUrlFopenAvailable = ini_get('allow_url_fopen') == "1" || ini_get('allow_url_fopen') == "On";
		$cUrlAvailable = function_exists('curl_version');
        
        if ($cUrlAvailable)
			$this->loadingMechanism = "cURL";
		else if ($allowUrlFopenAvailable)
			$this->loadingMechanism = "allow_url_fopen";
		parent::__construct();
	}
	


	function setdatabase($value,$option){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
        $value = $query->escape($value);
// Fields to update.
$fieldsapi = array(
    $db->quoteName('value').'=\''.$value.'\'',
);
 
// Conditions for which records should be updated.
$conditionsapi = array(
    $db->quoteName('option').'=\''.$option.'\'',

);
$query->update($db->quoteName('#__brafton_options'))->set($fieldsapi)->where($conditionsapi);
$db->setQuery($query);
$result = $db->query();


	}



	// This sets the options in the DB
	// Called from the options sub-controller
	function setOptions() {
		
		$API_pattern = "[a-zA-Z0-9]{8}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{12}";
		$baseURL_pattern = "^(http:\/\/)?api\.[^.]*\.(com|com\.au|co\.uk)\/";
		$options = JRequest::get('post');
		
		if(!preg_match('/'.$API_pattern.'/', $options['api-key'], $apiKey)) {
			JError::raiseWarning(100, 'There was a problem registering your API key.  Please double check and try again.');
			return;
		}
		if(!preg_match('/'.$baseURL_pattern.'/', $options['base-url'], $baseURL)){
			JError::raiseWarning(100, 'There was a problem registering your base URL.  Please double check and try again.');
			return;
		}


		$this->setdatabase($options['api-key'],'api-key');
		$this->setdatabase($options['base-url'],'base-url');
		$this->setdatabase($options['author'],'author');
		$this->setdatabase($options['import-order'],'import-order');
		$this->setdatabase($options['published-state'],'published-state');
		$this->setdatabase($options['update-articles'],'update-articles');
		$this->setdatabase($options['parent-category'],'parent-category');

		//video related settings
        /*
		$this->setdatabase($options['secret-key'], 'secret-key');
		$this->setdatabase($options['public-key'], 'public-key');
		$this->setdatabase($options['feed-number'], 'feed-number');
        
        //video CTA's
        $this->setdatabase($options['pause-text'], 'pause-text');
        $this->setdatabase($options['pause-link'], 'puase-link');
        $this->setdatabase($options['pause-asset-id'], 'pause-asset-id');
        $this->setdatabase($options['end-title'], 'end-title');
        $this->setdatabase($options['end-subtitle'], 'end-subtitle');
        $this->setdatabase($options['end-text'], 'end-text');
        $this->setdatabase($options['end-link'], 'end-link');
        $this->setdatabase($options['end-asset-id'], 'end-asset-id');
        //section for uploading background image for video cta's
        if($_FILES['end-background']['name'] != ''){
            $fileUpload = $_FILES['end-background'];
            $fileOption = $this->saveImage($fileUpload);
            JFactory::getApplication()->enqueueMessage(sprintf('the fileoption is %s', $fileOption));
            $this->setdatabase($fileOption, 'end-background');
        }
        */
		//import articles, videos or both
		$this->setdatabase($options['import-assets'], 'import-assets');
        $this->setdatabase($options['stop-importer'], 'stop-importer');
        $this->setdatabase($options['debug'], 'debug');
		 
		JFactory::getApplication()->enqueueMessage('Your options have successfully been saved.  Please note that your articles will not import until you have activated the <a href="index.php?option=com_plugins">bundled cron plugin</a>.');
	}


	/* getAPIKey()
	 * Pre - N/A
	 * Post - returns API Key, string
	 */ 
	function getAPIKey() {
		$this->optionsTable->load('api-key');
		return $this->optionsTable->value;
	}
	
	/* getBaseURL()
	 * Pre - N/A
	 * Post - returns base URL, string
	 */
	function getBaseURL () {
		$this->optionsTable->load('base-url');
		return $this->optionsTable->value;
	}
	
	/* getAuthor()
	 * Pre - N/A
	 * Post - returns author, string
	 */
	function getAuthor() {
		$this->optionsTable->load('author');
		return $this->optionsTable->value;
	}
	
	function getAuthorList() {
		$db = JFactory::getDBO();
		$query = "SELECT name, id FROM #__users";
		$db->setQuery($query);
		$authors = $db->loadObjectList();
		return $authors;
	}
	
	function getImportOrder() {
		$this->optionsTable->load('import-order');
		return $this->optionsTable->value;
	}
	
	function getPublishedState() {
		$this->optionsTable->load('published-state');
		return $this->optionsTable->value;
	}
	
	function getUpdateArticles() {
		$this->optionsTable->load('update-articles');
		return $this->optionsTable->value;
	}
	
	function getParentCategory() {
		$this->optionsTable->load('parent-category');
		return $this->optionsTable->value;
	}

	function getPublicKey() {
		$this->optionsTable->load('public-key');
		return $this->optionsTable->value;
	}

	function getSecretKey() {
		$this->optionsTable->load('secret-key');
		return $this->optionsTable->value;
	}

	function getFeedNumber() {
		$this->optionsTable->load('feed-number');
		return $this->optionsTable->value;
	}

	function getImportAssets() {
		$this->optionsTable->load('import-assets');
		return $this->optionsTable->value;
	}
    function getStopImporter(){
        $this->optionsTable->load('stop-importer');
		return $this->optionsTable->value;
    }
    function getDebug(){
        $this->optionsTable->load('debug');
        return $this->optionsTable->value;
    }
    function getPauseText(){
        $this->optionsTable->load('pause-text');
        return $this->optionsTable->value;
    }
    function getPauseLink(){
        $this->optionsTable->load('pause-link');
        return $this->optionsTable->value;
    }
    function getPauseAssetId(){
        $this->optionsTable->load('pause-asset-id');
        return $this->optionsTable->value;
    }
    function getEndTitle(){
        $this->optionsTable->load('end-title');
        return $this->optionsTable->value;
    }
    function getEndSubtitle(){
        $this->optionsTable->load('end-subtitle');
        return $this->optionsTable->value;
    }
    function getEndButtonText(){
        $this->optionsTable->load('end-text');
        return $this->optionsTable->value;
    }
    function getEndButtonLink(){
        $this->optionsTable->load('end-link');
        return $this->optionsTable->value;
    }
    function getEndAssetId(){
        $this->optionsTable->load('end-asset-id');
        return $this->optionsTable->value;
    }
    function getEndBackground(){
        $this->optionsTable->load('end-background');
        return $this->optionsTable->value;
    }
    protected function saveImage($file)
	{
       
        $imagesFolder = JPATH_ROOT . '/images';
        $fullSizePath = $imagesFolder . "/".$file['name'];
        move_uploaded_file($file['tmp_name'], $fullSizePath);
        $imagesUrl = JURI::root(true) . '/images/'.$file['name'];
        return $imagesUrl;
	}
	
} // end class
?>