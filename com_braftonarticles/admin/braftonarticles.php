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

	$jinput = JFactory::getApplication()->input;
	$view = strtolower($jinput->get('view', 'options'));
	$task = $jinput->get('task');

JSubMenuHelper::addEntry('General Settings', 'index.php?option=com_braftonarticles', $view == 'options');
JSubmenuHelper::addEntry('Video Settings', 'index.php?option=com_braftonarticles&view=options_video', $view == 'options_video');
JSubMenuHelper::addEntry('Import Log', 'index.php?option=com_braftonarticles&view=log', $view == 'log');

$controller = JControllerLegacy::getInstance('BraftonArticles');
$controller->execute(JRequest::getCmd('task'));
 
// Redirect if set by the controller
$controller->redirect();
?>