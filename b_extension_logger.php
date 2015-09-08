<?php
jimport('joomla.log.log');

function logMsg($message)
	{

		$log_file_location = JURI::base() . "/logs/brafton_extension.errors.php";

		if( ! file_exists($log_file_location))
		{
			JLog::addLogger(
       			array(
            		//Sets file name
            		'text_file' => 'brafton_extension.errors.php'
      				),
       			//Sets all JLog messages to be set to the file
      			 JLog::ALL,
       			//Chooses a category name
       			'brafton_extension'
 	  		 );	
		}	
		JLog::add(JText::_($message), JLog::WARNING, 'brafton_extension');
	}

?>
