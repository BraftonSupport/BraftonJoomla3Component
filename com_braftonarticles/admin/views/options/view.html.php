<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.view');
jimport('joomla.application.categories');
/**
 * Options View
 */
class BraftonArticlesViewOptions extends JViewLegacy
{
	protected $api_key;
	protected $base_url;
	protected $importOrder;
	protected $publishedState;
	protected $updateArticles;
	protected $parentCategory;
	
	protected $categoryList;

	//video related variables
	protected $public_key;
	protected $secret_key;
	protected $feed_number;
	protected $import_assets;
    
    protected $stopImporter;
    
    protected $pauseText;
    protected $pauseLink;
    protected $pauseAssetId;
	
	function display($tpl = null)
	{
		$toolbar = JToolBar::getInstance();
		JHtml::stylesheet('com_braftonarticles/css/admin/style.css', array('media/'), true);
		$document = JFactory::getDocument();
        $document->addstylesheet(JUri::root(true).'/media/com_braftonarticles/css/admin/style.css');
		JToolBarHelper::title('Brafton Article Importer','logo');
		JToolBarHelper::apply('options.apply');
		JToolBarHelper::cancel('options.cancel');
		JToolBarHelper::divider();
		$toolbar->appendButton('Confirm', 'This will build the importing category structure.', 'refresh', 'Sync Categories', 'options.sync_categories', false);
		//$toolbar->appendButton('Confirm', 'This will attempt to rebuild the listing of loaded Brafton content. This may have severe consequences and is irreversible! Are you sure you want to do this?', 'purge', 'Rebuild Content Listing', 'devtools.rebuild_content_listing', false);
        $toolbar->appendButton('Confirm', 'This will run your article importer', 'refresh', 'Run Article Importer', 'cron.loadArticles', false);		
		$this->api_key = $this->get('APIKey');
		$this->base_url = $this->get('BaseURL');
		$this->author = $this->get('Author');
		$this->authorList = $this->get('AuthorList');
		$this->importOrder = $this->get('ImportOrder');
		$this->publishedState = $this->get('PublishedState');
		$this->updateArticles = $this->get('UpdateArticles');
		$this->parentCategory = $this->get('ParentCategory');
		
		$this->categoryList = array();
		$cats = JCategories::getInstance('Content');
		$this->populateCategoryList($cats->get('root'), 0);

		//load video options
		$this->public_key = $this->get('PublicKey');
		$this->secret_key = $this->get('SecretKey');
		$this->feed_number = $this->get('FeedNumber');
        $this->pauseText = $this->get('PauseText');
        $this->pauseLink = $this->get('PauseLink');
        $this->pauseAssetId = $this->get('PauseAssetId');
        $this->endTitle = $this->get('EndTitle');
        $this->endSubtitle = $this->get('endSubtitle');
        $this->endText = $this->get('EndButtonText');
        $this->endLink = $this->get('EndButtonLink');
        $this->endAssetId = $this->get('EndAssetId');
        $this->endBackground = $this->get('EndBackground');
        
		$this->import_assets = $this->get('ImportAssets');
        $this->stopImporter = $this->get('StopImporter');
        if($this->stopImporter == 'On'){
            $app = JFactory::getApplication();        
            $app->enqueueMessage(JText::_('There was a vital failure when running your importer.  Please check the Log for errors.  Once you have solved the issue Turn Importer Error to Off under Advanced Options'), 'error');
        }
		parent::display($tpl);
	}
	
	private function populateCategoryList($catTree, $level)
	{
		if (empty($catTree))
			return;
		
		// special case for the root
		if ($level == 0)
			$this->categoryList[1] = 'None (Root)';
		else
			$this->categoryList[$catTree->id] = str_repeat('- ', $level) . ' ' . $catTree->title;
		
		foreach ($catTree->getChildren() as $c)
			$this->populateCategoryList($c, $level + 1);
	}
}
?>
