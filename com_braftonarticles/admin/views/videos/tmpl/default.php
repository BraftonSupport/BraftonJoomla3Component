<?php 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');

?>

<form action="index.php?option=com_braftonarticles" method="post" name="adminForm" id="adminForm">
<div id="brafton-standard-opts">
	<h2>Settings</h2>
	<input type="hidden" name="task" value="" />
	<div class="setting">
		<h3>
		<?php
			echo JHTML::tooltip(
				'Your unique API key, in the format xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx.',
				'API Key',
				'',
				'API Key');
		?>
		</h3>
		<input type="text" name="api-key" value="<?php echo $this->api_key; ?>"/>
	</div>

	<div class="setting">
		<h3>
		<?php
			echo JHTML::tooltip(
				'The base URL of your news feed.',
				'API Domain',
				'',
				'API Domain');
		?>
		</h3>
		<select name="base-url">
			<?php
				$opts = array('http://api.brafton.com/', 'http://api.contentlead.com/', 'http://api.castleford.com.au/');
				foreach ($opts as $o) : ?>
				<option 
					<?php if ($this->base_url == $o) : ?>
						selected="selected"
					<?php endif; ?>
				value="<?php echo $o; ?>"><?php echo $o; ?>
			<?php endforeach; ?>
		</select>
	</div>

	<div class="setting">
		<h3>
		<?php
			echo JHTML::tooltip(
				'The author that articles will be attributed to.',
				'Post Author',
				'',
				'Post Author');
		?>
		</h3>
		<select name="author">
			<?php foreach($this->authorList as $author): ?>
			<option 
				<?php if(($this->author) == $author->id): ?>
					 selected="selected"
				<?php endif; ?>
					value="<?php echo $author->id; ?>"><?php echo $author->name; ?>
			</option>
			<?php endforeach; ?>
		</select>
	</div>
</div>

<!-- Add video settings -->
<div id="brafton-video-opts">
	<legend>
		<h2>Video Settings</h2>
		<h4>Note: Only modify these settings if you are receiving our video product</h4>
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
	<div class="setting">
		<h3>
		<?php
			echo JHTML::tooltip(
				'Import only articles, only videos, or both.',
				'Import Assets',
				'',
				'Import Assets');
		?>
		</h3>
		<select name="import-assets">
			<?php
				$opts = array('both', 'articles', 'videos');
				foreach ($opts as $o) : ?>
				<option 
					<?php if ($this->import_assets == $o) : ?>
						selected="selected"
					<?php endif; ?>
				value="<?php echo $o; ?>"><?php echo $o; ?>
			<?php endforeach; ?>
		</select>
	</div>
</div>
<fieldset>
    <legend><h2>Video Call To Actions</h2></legend>
    <div id="brafton-video-cta">
    
    </div>
</fieldset>
<fieldset>
	<legend><h2>Advanced</h2> (<a href="javascript:void(0)" onclick="$$('div#brafton-advanced-opts').toggle();">Show/Hide</a>)</legend>
	<div id="brafton-advanced-opts" style="display: none;">
		<div class="setting">
			<h3>
			<?php
				echo JHTML::tooltip(
					'If set to On, articles that are updated in the feed will be reflected on the site.',
					'Apply Article Updates',
					'',
					'Apply Article Updates');
			?>
			</h3>
			<select name="update-articles">
				<?php
					$opts = array('On', 'Off');
					foreach ($opts as $o) : ?>
					<option 
						<?php if ($this->updateArticles == $o) : ?>
							selected="selected"
						<?php endif; ?>
					value="<?php echo $o; ?>"><?php echo $o; ?>
				</option>
				<?php endforeach; ?>
			</select>
		</div>
		
		<div class="setting">
			<h3>
			<?php
				echo JHTML::tooltip(
					'The article\'s Create Date is set based on this setting.',
					'Article Date',
					'',
					'Article Date');
			?>
			</h3>
			<select name="import-order">
				<?php
					$opts = array('Published Date', 'Last Modified Date', 'Created Date');
					foreach ($opts as $o) : ?>
					<option 
						<?php if ($this->importOrder == $o) : ?>
							selected="selected"
						<?php endif; ?>
					value="<?php echo $o; ?>"><?php echo $o; ?>
				</option>
				<?php endforeach; ?>
			</select>
		</div>
		
		<div class="setting">
			<h3>
			<?php
				echo JHTML::tooltip(
					'The article\'s published status when imported.',
					'Published Status',
					'',
					'Published Status');
			?>
			</h3>
			<select name="published-state">
				<?php
					$opts = array('Published', 'Unpublished');
					foreach ($opts as $o) : ?>
					<option 
						<?php if ($this->publishedState == $o) : ?>
							selected="selected"
						<?php endif; ?>
					value="<?php echo $o; ?>"><?php echo $o; ?>
				<?php endforeach; ?>
			</select>
		</div>
		
		<div class="setting">
			<h3>
			<?php
				echo JHTML::tooltip(
					'Parent of imported categories.',
					'Parent Category',
					'',
					'Parent Category');
			?>
			</h3>
			<select name="parent-category">
				<?php
					foreach ($this->categoryList as $id => $o) : ?>
					<option 
						<?php if ($this->parentCategory == $id) : ?>
							selected="selected"
						<?php endif; ?>
					value="<?php echo $id; ?>"><?php echo $o; ?>
				<?php endforeach; ?>
			</select>
		</div>
        <div class="setting">
            <h3>Importer Error</h3>
            <p>this option stop the importer from running.  It is automatically turned on if the importer encounters a vital error during import.  That Error is reported to your Account Manager to aid in our ability to correct the issue and ensure smooth delivery.</p>
            <select name="stop-importer">
            <?php
					$opts = array('On', 'Off');
					foreach ($opts as $o) : ?>
					<option 
						<?php if ($this->stopImporter == $o) : ?>
							selected="selected"
						<?php endif; ?>
					value="<?php echo $o; ?>"><?php echo $o; ?>
				</option>
				<?php endforeach; ?>
            </select>
        </div>
	</div>
</fieldset>
</form>
