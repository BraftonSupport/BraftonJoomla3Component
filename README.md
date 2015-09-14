Joomla-3.0-importer
===================

Component for Importing content from your Brafton/ContentLEAD/ or Castleford API Feed.

#First Steps#

##Installation##

##Configure Joomla 3##

1. Set up a Category to use for import. {recommend blog, news ect}
2. Set up a Menu Item as a blog list.
	1. Under Details select the category you created in the previous step.
	2. Under Category change Subcategory levels to "none"
	3. Under blog layout change include subcategories to "all"
	4. Under blog Layout change Category Order to No Order.
	5. Under blog Layout change Article Order to "most recent first"

_For premium content and videos you must enable script and iframes in your wysiwyg editor._

Extensions -> Plugin Manager -> {your wysiwyg editor} most likely Editor - TinyMCE
Under "Prohibited Elements" ensure script and iframe are not listed.
If they are listed delete them.

##Configure the Importer##

###General Settings###

###Video Settings###

###Importer Log###

##Explination of Settings##