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
        1. _NOTE: The default time setting for the cron is 60 minutes._
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
Under "Prohibited Elements" ensure script, iframe, and video are not listed.
If they are listed delete them.

##Configure the Importer##

Once the Above steps are completed you can find your new Importer under Components -> Brafton Article Importer.

###Quick Setup###
1. API Key: Enter the API Key provided to you by your Account Manager.
2. API Domain: Select your Domain Brand.
3. Post Author: Select a user to attribute articles to.
4. Parent Category: Select the Category you Created in the _"Configure Joomla 3"_ Section.
5. Save your settings.
6. Click _"Sync Categories"_ to import your category list from the feed.
7. Click _"Run Article Importer"_ to import your first articles.

###General Settings###
1. API Key: your unique XML Feed key provided to you by your Account Manager.
2. API Domain: The brand of your XML Feed. We provide content for the following brands _required_
    1. Brafton - http://api.brafton.com/
    2. ContentLEAD - http://api.contentlead.com/
    3. Castleford = http://api.castelford.com.au/
3. Post Author: You can select the user you would like to attribute the imported content. _required_
4. Import Content: Select what type of content you are importing. _required_
    1. Articles: If this option is selected you only need to configure the General Settings Page.
    2. Videos: If this option is selected you will need to configure all _*REQUIRED*_ fields in the General Settings Section as well as all settings on the "Video Settings" Tab.
    3. Both: If this option is selected you will need to ensure all options are configured.
5. Apply Article Updates: If this section is "ON" all content currently in your XML feed will be updated.  _NOTE: this will result in images being redownloaded as well_
6. Article Date: Sets the date for the imported content.
    1. Published Date: Sets the Content Date to the Published Date in your XML Feed. (recommended)
    2. Last Modified Date: Sets the Content Date to the date your content was last modified by our writers.
    3. Created Date: Sets the Content Date to the date your content was created by the writers.
7. Published Status: Sets the status of the imported content.
    1. Published: Content will be publically avaialbe immediately.
    2. Unpublished: Content will require you to manually publish before it can be viewed on your blog.
8. Parent Category: Sets the parent category for your content. _required_
    1. _Cateogry Selected Here will need to have a menu item to list all blog posts_. Ensure you have followed the *"Configure Joomla 3"* instructions correctly.
9. Debug Mode: Turning this "ON" will log all debugging messages and errors to your _"Import Log"_ during operation.
    1. _This option will turn on automatically if the importer fails_
10. Importer Error: This option will cause the importer to stop running.
    1. Do to normal operation of the Joomla 3 CMS should the importer result in a fatal error this option will turn "ON" and notify your Account Manager of the error.
    
###Video Settings###
1. Public Key: Your Public Key provided to you by your Account Manager to access your Video XML Feed.
2. Secret Key: Your Unique key provided to you by your Account Manager to identify your Video XML Feed.
3. Feed Number: Your Feed Number for your Video feed. _Normally 0 unless you have more than 1 video feed_

#### Video Call To Actions ####
1. Pause Text: The text shown at the top of the video screen when the Pause is pressed.
2. Pause Link: The url to send users to when they click your "Pause Text"
    1. Must be a complete URL with "HTTP://" or "HTTPS://"

###Importer Log###

##Brafton Content###

##Brafton Cron###


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