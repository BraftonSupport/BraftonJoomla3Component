<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * Hello Table class
 */
class TableBraftonOptions extends JTable
{
	

        var $option = null;

        var $value = null;
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db) 
	{
		parent::__construct('#__brafton_options', 'option', $db);
	}
}
