<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');
jimport('joomla.html.parameter');
jimport('joomla.log.log');
jimport('joomla.database.table');

class plgContentBraftoncontent extends JPlugin
{
    protected $autoloadLanguage = true;
    
    function plgContentBraftoncontent(&$subject, $params){
        parent::__construct( $subject, $params );
    }
    
    public function onContentChangeState($context, $pks, $value){
        if($value != -2 && $context != 'com_content.articles'){
            return;
        }
        for($i=0;$i<count($pks);$i++){
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->delete($db->quoteName('#__brafton_content'))->where('content_id = '.$pks[$i] );
            $db->setQuery($query);
            $result = $db->execute();
        }
    }
    
    public function onContentPrepareForm($form, $data){
        /*
        $app = JFactory::getApplication();
        $delId = 'prepare';
        $app->enqueueMessage(JText::_(JURI::root(true)));
        */
        return true;
    }

}

?>