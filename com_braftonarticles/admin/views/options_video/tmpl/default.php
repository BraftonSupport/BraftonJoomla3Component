<?php 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');

?>

<form action="index.php?option=com_braftonarticles" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<div id="brafton-standard-opts">
	<input type="hidden" name="task" value="" />
<!-- Add video settings -->
<div id="brafton-video-opts">
	<legend>
		<h2>Video Settings</h2>
	</legend>
	<div class="setting">
		<h3>
		<?php
			echo JHTML::tooltip(
				'Your Public Key.',
				'Public Key',
				'',
				'Public Key');
		?>
		</h3>
		<input type="text" name="public-key" value="<?php echo $this->public_key; ?>"/>
	</div>
	<div class="setting">
		<h3>
		<?php
			echo JHTML::tooltip(
				'Your Secret Key.',
				'Secret Key',
				'',
				'Secret Key');
		?>
		</h3>
		<input type="text" name="secret-key" value="<?php echo $this->secret_key; ?>"/>
	</div>
	<div class="setting">
		<h3>
		<?php
			echo JHTML::tooltip(
				'Your Video Feed number (typically 0).',
				'Feed Number',
				'',
				'Feed Number');
		?>
		</h3>
		<input type="text" name="feed-number" value="<?php echo $this->feed_number; ?>"/>
	</div>
</div>
    <fieldset>
    <legend><h2>Video Call To Actions</h2></legend>
    <div id="brafton-video-cta">
        <!-- template
        <div class="setting">
            <h3></h3>
            <p></p>
            <input type="text" name="" value=""/>
        </div>
        -->
        <div class="setting">
            <h3>Pause Text</h3>
            <p></p>
            <input type="text" name="pause-text" value="<?php echo $this->pauseText; ?>"/>
        </div>
        <div class="setting">
            <h3>Pause Link</h3>
            <p></p>
            <input type="text" name="pause-link" value="<?php echo $this->pauseLink; ?>"/>
        </div>
        <div class="setting">
            <h3>Pause Asset ID</h3>
            <p></p>
            <input type="text" name="pause-asset-id" value="<?php echo $this->pauseAssetId; ?>"/>
        </div>
        <div class="setting">
            <h3>Ending Title</h3>
            <p></p>
            <input type="text" name="end-title" value="<?php echo $this->endTitle; ?>"/>
        </div>
        <div class="setting">
            <h3>Ending Subtitle</h3>
            <p></p>
            <input type="text" name="end-subtitle" value="<?php echo $this->endSubtitle; ?>"/>
        </div>
        <div class="setting">
            <h3>Ending Button Text</h3>
            <p></p>
            <input type="text" name="end-text" value="<?php echo $this->endText; ?>"/>
        </div>
        <div class="setting">
            <h3>Ending Button Link</h3>
            <p></p>
            <input type="text" name="end-link" value="<?php echo $this->endLink; ?>"/>
        </div>
        <div class="setting">
            <h3>Ending Asset ID</h3>
            <p></p>
            <input type="text" name="end-asset-id" value="<?php echo $this->endAssetId; ?>"/>
        </div>
        <div class="setting">
            <h3>Ending Background</h3>
            <p></p>
            <input type="file" name="end-background" value=""/>
            <img src="<?php echo $this->endBackground; ?>" style="max-width:250px; height:auto"/>
        </div>
    </div>
</fieldset>
    </div>

</form>
