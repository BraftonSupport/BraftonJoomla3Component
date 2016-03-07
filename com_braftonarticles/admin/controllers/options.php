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
class BraftonArticlesControllerOptions extends JControllerAdmin {
    function __construct($config = array()){
        parent::__construct($config);
        $input = JFactory::getApplication()->input;
        $method = $input->get('task');
        if($method){
            $this->$method();
        }
    }
	function apply() {
		$model = $this->getModel('options');
		$model->setOptions();
        $msg = 'Your options have successfully been saved.  Please note that your articles will not import until you have activated the <a href="index.php?option=com_plugins">Brafton Cron and Brafton Content Plugins</a>.';
		$this->setRedirect('index.php?option=com_braftonarticles', $msg);
	}
	
	function cancel() {
		$this->setRedirect('index.php');
	}
    function clear_log(){
        $config = new JConfig();
		$logPath = rtrim($config->log_path, '/') . '/com_braftonarticles.log.php';
        unlink($logPath);
        $msg = 'Your Log has now been deleted';
        $this->setRedirect('index.php?option=com_braftonarticles&view=log', $msg, 'message');
        
    }
    function download_log(){
        $config = new JConfig();
		$logPath = rtrim($config->log_path, '/') . '/com_braftonarticles.log.php';
        copy($logPath, rtrim($config->log_path, '/') . '/com_braftonarticles.log.txt');    
        $txt = fopen(rtrim($config->log_path, '/') . '/com_braftonarticles.log.txt', 'r');
        $txt_content = fread($txt, filesize(rtrim($config->log_path, '/') . '/com_braftonarticles.log.txt'));
        header("Content-type: text/plain");
        header("Content-Disposition: attachment; filename=Brafton_Errors_".date('Y-M-d-(h.m.s)')."-".$_SERVER['HTTP_HOST'].".txt");
        echo '<pre>';
        var_dump($txt_content);
        echo '</pre>';
        exit();
        
    }
    function sync_categories(){
        $model = $this->getModel('categories');
        $model->getCategories();
        $msg = 'You have successfully imported your categories';
        $this->setRedirect('index.php?option=com_braftonarticles', $msg);
    }
}