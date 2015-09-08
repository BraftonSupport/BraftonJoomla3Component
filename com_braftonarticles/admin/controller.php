<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
/*
jimport('joomla.application.component.controller');
jimport('joomla.version');
*/

/**
 * General Controller of BraftonArticles component
 */
class BraftonArticlesController extends JControllerLegacy
{
	function display($cachable = false, $urlparams = false) 
	{

			$jinput = JFactory::getApplication()->input;
			$view = $jinput->get('view', 'Options');
			$jinput->set('view', $view);
	
		
		parent::display($cachable);
	}
}
?>