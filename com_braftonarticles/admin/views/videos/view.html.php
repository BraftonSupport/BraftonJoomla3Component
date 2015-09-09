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
		JHtml::stylesheet('com_braftonarticles/css/admin/style.css', array('media/'), true);
		$document = JFactory::getDocument();
        $document->addstylesheet(JUri::root(true).'/media/com_braftonarticles/css/admin/style.css');
		JToolBarHelper::title('Brafton Article Importer','logo');
		JToolBarHelper::apply('options.apply');
		JToolBarHelper::cancel('options.cancel');
		JToolBarHelper::divider();

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
		
		parent::display($tpl);
	}
	
}
?>
