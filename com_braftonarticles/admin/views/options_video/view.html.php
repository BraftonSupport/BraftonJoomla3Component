<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.view');
jimport('joomla.application.categories');
 
/**
 * Options View
 */
class BraftonArticlesViewOptions_Video extends JViewLegacy
{
	protected $public_key;
	protected $secret_key;
	protected $feed_number;
    protected $pauseText;
    protected $pauseLink;
    protected $pauseAssetId;
    protected $endTitle;
    protected $endSubtitle;
    protected $endText;
    protected $endLink;
    protected $endAssetId;
    protected $endBackground;
	
	function display($tpl = null)
	{
		$toolbar = JToolBar::getInstance();
		JHtml::stylesheet('com_braftonarticles/css/admin/style.css', array('media/'), true);
		$document = JFactory::getDocument();
        $document->addstylesheet(JUri::root(true).'/media/com_braftonarticles/css/admin/style.css');
		JToolBarHelper::title('Brafton Article Importer','logo');
		JToolBarHelper::apply('videos.apply');
		JToolBarHelper::cancel('videos.cancel');
		$toolbar->appendButton('Confirm', 'This will build the importing category structure.', 'refresh', 'Sync Categories', 'options.sync_categories', false);
        $toolbar->appendButton('Confirm', 'This will run your Video Importer', 'refresh', 'Run Video Importer', 'cron.loadVideos', false);
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
