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
	
	function display($tpl = null)
	{
		$toolbar = JToolBar::getInstance();
		JHtml::stylesheet('com_braftonarticles/css/admin/style.css', 'media/');
		
		JToolBarHelper::title('Brafton Article Importer','logo');
		JToolBarHelper::apply('options.apply');
		JToolBarHelper::cancel('options.cancel');
		JToolBarHelper::divider();
		$toolbar->appendButton('Confirm', 'This will build the importing category structure from scratch! Are you sure you want to do this?', 'refresh', 'Sync Categories', 'devtools.sync_categories', false);
		//$toolbar->appendButton('Confirm', 'This will attempt to rebuild the listing of loaded Brafton content. This may have severe consequences and is irreversible! Are you sure you want to do this?', 'purge', 'Rebuild Content Listing', 'devtools.rebuild_content_listing', false);
		
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
		$this->import_assets = $this->get('ImportAssets');
        $this->stopImporter = $this->get('StopImporter');
		
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
