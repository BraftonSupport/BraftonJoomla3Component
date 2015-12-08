<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

class BraftonArticlesControllerCron extends JControllerLegacy
{
	function __construct( $config = array())
	{
		parent::__construct( $config );
        $input = JFactory::getApplication()->input;
        $method = $input->get('task');
        if($method){
            $this->$method();
        }
	}

	function display($cachable = false, $urlparams = false) 
	{
		// set default view if not set
		JRequest::setVar('view', JRequest::getCmd('view','Options'));
		parent::display($cachable);
	}
	
	function loadCategories()
	{

		JLog::add('loaded categories started', JLog::INFO, 'com_braftonarticles');
		$model = $this->getModel('categories');
		if(!$model->getCategories()) {
			return false;
		} else {
			return true;
		}
	}
	
	function loadArticles()
	{

		JLog::add('loaded articles started', JLog::INFO, 'com_braftonarticles');
		$model = $this->getModel('articles');
		if(!$model->loadArticles()) {
            $app = JFactory::getApplication();
            if($app->isAdmin()){
                $msg = 'You have Run the importer.  Check the Log for a list of imported Items.';
                $this->setRedirect('index.php?option=com_braftonarticles', $msg, 'message');
            }
			return false;
		} else {
			return true;
		}
	}
	function loadVideos()
    {
        JLog::add('loaded videos started', JLog::INFO, 'com_braftonarticles');
        $model = $this->getModel('videos');
        if(!$model->loadVideos()) {
            $app = JFactory::getApplication();
            if($app->isAdmin()){
                $msg = 'You have Run the importer.  Check the Log for a list of imported Items.';
                $this->setRedirect('index.php?option=com_braftonarticles', $msg, 'message');
            }
            return false;
        } else {
            return true;
        }
    }
	function updateArticles()
	{
		$model = $this->getModel('articles');
		if(!$model->updateArticles()) {
			return false;
		} else {
			return true;
		}
	}
}