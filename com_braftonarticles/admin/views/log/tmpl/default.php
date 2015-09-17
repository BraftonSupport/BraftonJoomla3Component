<?php 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
?>
<form action="index.php?option=com_braftonarticles&view=log" method="post" name="adminForm" id="adminForm">
    <input type="hidden" name="task" value="" />
    <?php if ($this->logContents){ ?>
    <pre><?php echo $this->logContents; ?></pre>
    <?php } ?>
</form>