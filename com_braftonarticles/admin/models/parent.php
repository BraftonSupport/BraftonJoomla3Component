<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');
ini_set('max_execution_time', 300);
include_once 'ApiClientLibrary/ApiHandler.php';

// Load Brafton Video Libraries
require_once 'RCClientLibrary/AdferoArticlesVideoExtensions/AdferoVideoClient.php';
require_once 'RCClientLibrary/AdferoArticles/AdferoClient.php';
require_once 'RCClientLibrary/AdferoPhotos/AdferoPhotoClient.php';
require_once 'BraftonError.php';

class BraftonArticlesModelParent extends JModelList
{
	protected $feed;
	protected $options;
	protected $loadingMechanism;
    public   $importAssets;
    public $feedId;
	//video library classes
	protected $videoClient;
	protected $client;
	protected $photoClient;
	
	function __construct()
	{
		parent::__construct();
		$error = new BraftonErrorReport();
        $this->importAssets = 'articles';
		JLog::addLogger(array('text_file' => 'com_braftonarticles.log.php'), JLog::ALL, 'com_braftonarticles');
        //JLog::addLogger(array('logger' => 'database', 'db_table' => '#__brafton_error'), JLog::ALL, 'com_braftonarticles');
		
		$allowUrlFopenAvailable = ini_get('allow_url_fopen') == "1" || ini_get('allow_url_fopen') == "On";
		$cUrlAvailable = function_exists('curl_version');
		
		if (!$allowUrlFopenAvailable && !$cUrlAvailable)
		{
			$report = implode(", ", array(sprintf("allow_url_fopen is %s", ($allowUrlFopenAvailable ? "On" : "Off")), sprintf("cURL is %s", ($cUrlAvailable ? "enabled" : "disabled"))));
			throw new Exception(sprintf("No feed loading mechanism available - PHP reported %s", $report), "");
		}
		
		// prioritize cURL over allow_url_fopen
		if ($cUrlAvailable)
			$this->loadingMechanism = "cURL";
		else if ($allowUrlFopenAvailable)
			$this->loadingMechanism = "allow_url_fopen";
		
		JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components'.'/com_braftonarticles'.'/tables');
		$this->options = $this->getTable('braftonoptions');

		JLog::add('load parent.', JLog::INFO, 'com_braftonarticles');

		$this->options->load('api-key');
		$API_Key = $this->options->value;
		$error->set_api($API_Key);
        
		$this->options->load('base-url');
		$API_BaseURL = $this->options->value;
		$error->set_brand($API_BaseURL);
        
        $this->options->load('debug');
        $debug = $this->options->value;
        $error->set_debug($debug);
        
		$this->feed = new ApiHandler($API_Key, $API_BaseURL);

		//load video options
		$this->options->load('secret-key');
		$secret_key = $this->options->value;

		$this->options->load('public-key');
		$public_key = $this->options->value;

		$this->options->load('feed-number');
		$feed_number = $this->options->value;
        

		//determine appropriate base client and photo URLs
		switch ($API_BaseURL) {
			case 'http://api.brafton.com/':
				$baseURL = 'http://livevideo.api.brafton.com/v2/';
				$photoURI = "http://pictures.brafton.com/v2/";
				break;
			case 'http://api.contentlead.com/':
				$baseURL = 'http://livevideo.api.contentlead.com/v2/';
				$photoURI = "http://pictures.contentlead.com/v2/";
				break;
			case 'http://api.castleford.com.au/':
				$baseURL = 'http://livevideo.api.castleford.com.au/v2/';
				$photoURI = "http://pictures.castleford.com.au/v2/";
				break;
			default:
				$baseURL = 'http://livevideo.api.brafton.com/v2/';
				$photoURI = "http://pictures.brafton.com/v2/";
				break;
		}
        
		//Check that public and secret key are set, instantiate video classes if so
		if( ($secret_key == '') || ($public_key == '') )
		{
            $this->importAssets = 'noVideos';
		} else {
			$this->videoClient = new AdferoVideoClient ($baseURL, $public_key, $secret_key);
			$this->client = new AdferoClient ($baseURL, $public_key, $secret_key);
            $feeds = $this->client->Feeds();
            $feedList = $feeds->ListFeeds(1,10);
            $this->feedId = $feedList->items[$feed_number]->id;
			$this->photoClient = new AdferoPhotoClient($photoURI);
		}
	}
}
?>