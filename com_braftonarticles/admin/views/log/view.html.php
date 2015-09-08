<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
jimport('joomla.filesystem.file');

include_once JPATH_CONFIGURATION . '/configuration.php';

class BraftonArticlesViewLog extends JViewLegacy
{
	protected $logContents;
	
	function display($tpl = null)
	{
		$toolbar = JToolBar::getInstance();
		JHtml::stylesheet('com_braftonarticles/css/admin/style.css', 'media/');
		JToolBarHelper::title('Brafton Article Importer','logo');
        JToolBarHelper::apply('options.apply');
		JToolBarHelper::cancel('options.cancel');
		JToolBarHelper::divider();
		$toolbar->appendButton('Confirm', 'This will build the importing category structure from scratch! Are you sure you want to do this?', 'refresh', 'Clear Log', 'options.clear_log', false);
        
		$config = new JConfig();
		$logPath = rtrim($config->log_path, '/') . '/com_braftonarticles.log.php';
		if (JFile::exists($logPath))
			$this->logContents = JFile::read($logPath);
		else
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage('Empty log file.');
		}
		
		parent::display($tpl);
	}
}
?>