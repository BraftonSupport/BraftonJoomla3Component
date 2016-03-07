<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * @package     Joomla.Administrator
 * @subpackage  com_content
 * @since       1.6
 */
class BraftonArticlesControllerVideos extends JControllerAdmin {
    function __construct($config = array()){
        parent::__construct($config);
        $input = JFactory::getApplication()->input;
        $method = $input->get('task');
        if($method){
            $this->$method();
        }
    }
	function apply() {
		$model = $this->getModel('options_video');
		$model->setOptions();
		$this->setRedirect('index.php?option=com_braftonarticles&view=options_video');
	}
	 
	function cancel() {
		$this->setRedirect('index.php');
	}
}