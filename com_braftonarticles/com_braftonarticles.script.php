<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.installer.installer');
jimport('joomla.filesystem.file');
jimport('joomla.log.log');

class com_braftonarticlesInstallerScript
{
	public function __construct()
	{
		JLog::addLogger(array('text_file' => 'com_braftonarticles.log.php'), JLog::ALL, 'com_braftonarticles');
	}
	
	public function preflight($type, $parent)
	{
		$v = new JVersion();
		$joomlaVersion = $v->getShortVersion();
		
		if (version_compare($joomlaVersion, '1.7', 'lt') || version_compare($joomlaVersion, '3.0', 'gte'))
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage(sprintf('%s failed: Incompatible version of Joomla.', ucfirst($type)), 'error');
			return false;
		}
	}
	
	/**
	 * Called after any type of action
	 *
	 * @param   string  $route  Which action is happening (install|uninstall|discover_install)
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function postflight($type, $parent){ 
		$installer = new JInstaller;
		$src = $parent->getParent()->getPath('source');
		$installer->install($src.'/plg_braftoncron');
        $installer_2 = new JInstaller;
        $src_2 = $parent->getParent()->getPath('source');
        $installer_2->install($src_2.'/plg_braftoncontent');
	}

	/**
	 * Called on uninstallation
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 */

}