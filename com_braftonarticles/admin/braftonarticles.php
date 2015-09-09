<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
/*
jimport('joomla.application.component.controller');
jimport('joomla.version');
*/

if (!JFactory::getUser()->authorise('core.manage', 'com_braftonarticles'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}
/*
$version = new JVersion();
$joomlaVersion = $version->getShortVersion();
$view = '';
$task = '';

if (version_compare($joomlaVersion, '2.5', '<'))
{
	$view = strtolower(JRequest::getVar('view', 'options'));
	$task = JRequest::getCmd('task');
}
else
{

	*/
	$jinput = JFactory::getApplication()->input;
	$view = strtolower($jinput->get('view', 'options'));
	$task = $jinput->get('task');

JSubMenuHelper::addEntry('Settings', 'index.php?option=com_braftonarticles', $view == 'options');
JSubmenuHelper::addEntry('Video CTAs', 'index.php?option=com_braftonarticles&view=videos', $view == 'videos');
JSubMenuHelper::addEntry('Log', 'index.php?option=com_braftonarticles&view=log', $view == 'log');

$controller = JControllerLegacy::getInstance('BraftonArticles');
$controller->execute(JRequest::getCmd('task'));
 
// Redirect if set by the controller
$controller->redirect();
?>