Joomla-3.0-importer
===================

Component for Importing content from your Brafton/ContentLEAD/ or Castleford API Feed.

#First Steps#
The Brafton Importer is a Joomla Component and consists of both Modules and Plugins.  To Set Up you must follow the *Installation* and the *Configure Joomla 3* Sections as directed.  Without doing so will result in incorrect operation or a failure to operate.

##Installation##

1. Start by Logging into your _Administrator_ dashboard.
2. Navigate to the Extensions -> Manage -> Install Menu Item.
3. Select the "Upload Package File" Tab.
    1. Click "Choose File" and navigate to the location of the ZIP File you downloaded.
    2. Click "Upload & Install".
4. You must now "Turn ON" 2 plugins.
5. Navigate to the Extensions -> Plugins Menu Item.
6. Find _"Brafton Cron"_ in the list of plugins.
    1. Ensure It is enabled by looking for a Green Checkmark in the Status Column.  If it is not enabled Click the Red X to turn this plugin on.
        _1. NOTE: The default time setting for the cron is 60 minutes._
7. Find _"Brafton Content"_ in the list of plugins.
    1. Ensure It is enabled by looking for a Green Checkmark in the Status Column.  If it is not enabled Click the Red X to turn this plugin on.
    

##Configure Joomla 3##

1. Set up a Category to use for import. {recommend blog, news ect}
    _NOTE: This category will also be used as a fallback if no category can be attained form the xml feed_
2. Set up a Menu Item as a blog list.
	1. Under Details select the category you created in the previous step.
	2. Under Category change Subcategory levels to "none"
	3. Under blog layout change include subcategories to "all"
	4. Under blog Layout change Category Order to No Order.
	5. Under blog Layout change Article Order to "most recent first"
    6. Under Options change Show intro text to "Hide".

_For premium content and videos you must enable script and iframes in your wysiwyg editor._

Extensions -> Plugin Manager -> {your wysiwyg editor} most likely Editor - TinyMCE
Under "Prohibited Elements" ensure script and iframe are not listed.
If they are listed delete them.

##Configure the Importer##

###General Settings###

###Video Settings###

###Importer Log###

##Turn on included Modules##

###Brafton Content###

###Brafton Cron###

##Explination of Settings##

##Optional##

##Set up Category Widget##
Extensions->modules
Create new select Articles category
select the parent category we created from the setup.
add to appropriate position on the correct page.

##Set up Archive Widget##
@todo learn to create proper archives.

#To find appropriate widget positions#
go to template manager select templates.
select options top right corner
turn on preview modules positions.
view page in question with the parameter ?tp=1 added to the url

*NOTE: be sure to turn this off when youa re done*