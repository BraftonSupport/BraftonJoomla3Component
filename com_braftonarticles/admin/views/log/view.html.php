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
		JToolBarHelper::divider();
		$toolbar->appendButton('Confirm', 'This will clear your error log.  Be sure you have investigated all Errors first.', 'purge', 'Clear Log', 'options.clear_log', false);
        $toolbar->appendButton('Confirm', 'Download Importer Log', 'refresh', 'Download Log', 'options.download_log', false);
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