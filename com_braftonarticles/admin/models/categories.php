<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_ADMINISTRATOR.'/components'.'/com_braftonarticles'.'/models'.'/parent.php');
require_once (JPATH_ADMINISTRATOR.'/components'.'/com_braftonarticles'.'/tables'.'/braftonoptions.php');
require_once (JPATH_ADMINISTRATOR.'/components'.'/com_braftonarticles'.'/tables'.'/braftoncategories.php');
jimport('joomla.database.table');
jimport('joomla.log.log');

class BraftonArticlesModelCategories extends BraftonArticlesModelParent
{
	public function getCategories()
	{
        $this->ArticleCategories();
        if($this->importAssets != 'noVideos'){
            $this->VideoCategories();
        }
        $table = JTable::getInstance('Category');
        $table->rebuild();
        
	}
	/*
	*	$category - a NewsCategory item
	*	$row - connection to brafton_categories table
	*/
	private function category_exists($category, $brCategoryRow)
	{
		$jcatid = 0;
		$db = $brCategoryRow->getDbo();
		
		$q = $db->getQuery(true);
		$q->select('cat_id')->from('#__brafton_categories')->where('brafton_cat_id=' . $db->quote($category->getId()));
		
		$db->setQuery($q);
		
		if (!$db->loadRow())
			return false;
		return true;
	}
    private function video_category_exists($category, $brCategoryRow){
        $jcatid = 0;
		$db = $brCategoryRow->getDbo();
		
		$q = $db->getQuery(true);
		$q->select('cat_id')->from('#__brafton_categories')->where('brafton_cat_id=' . $db->quote($category[0]));
		
		$db->setQuery($q);
		
		if (!$db->loadRow())
			return false;
		return true;
    }
    private function ArticleCategories(){
        $categoryList = $this->feed->getCategoryDefinitions();
	
		foreach ($categoryList as $category)
		{
			$categoryRow = JTable::getInstance('Category');
			$brCategoryRow = JTable::getInstance('BraftonCategories', 'Table');
JLog::add('category names in getCategories function :'.trim($category->getName()), JLog::INFO, 'com_braftonarticles');

			//check to see if category already exists
			if (!$this->category_exists($category, $brCategoryRow))
			{
				//start checking for parent category
				$this->options->load('parent-category');
				$parentId = $this->options->value;
				
				// it's a little awkward using the same model for loading and saving in the same pass.
				// todo: use the dbo for loads/checks as part of the model rewrite
				if (!$categoryRow->load($parentId))
				{
					// if we can't insert under the parent, keep the tree intact. insert under root.
					JLog::add(sprintf('Warning: No parent category match for id %d.', $parentId), JLog::WARNING, 'com_braftonarticles');
					//$parentId = 1;
				}
				//end checking for parent category 

                if($this->debug){
				    JLog::add(sprintf('Warning:parent category is %d.', $parentId), JLog::WARNING, 'com_braftonarticles');
                }
                
				$categoryRow =& JTable::getInstance('Category');
				//try saving as objects instead
                
                $categoryRow->title = trim($category->getName());
                $categoryRow->alias = str_replace(' ','-',strtolower(trim($category->getName())));
                $categoryRow->extension = 'com_content';
                $categoryRow->published = 1;
                $categoryRow->language = '*';
                $categoryRow->params = '{"category_layout":"","image":""}';
                $categoryRow->metadata = '{"author":"","robots":"noindex, follow"}';
                $categoryRow->access = 1;

                $categoryRow->store();
                if($this->debug){
                    JLog::add(sprintf('parent from database table  %d.', $categoryRow->parent_id), JLog::WARNING, 'com_braftonarticles');
                }
                $oldParent = $categoryRow->set('parent_id', $parentId);
                $oldLevel = $categoryRow->set('level', 2);
                $categoryRow->store();
                if($this->debug){
                    JLog::add(sprintf('parent from database table after saving the reset  %d.', $categoryRow->parent_id), JLog::WARNING, 'com_braftonarticles');
                }
				$brCategoryData['id'] = null;
				$brCategoryData['cat_id'] = $categoryRow->id;
				$brCategoryData['brafton_cat_id'] = (int) $category->getId();
				$brCategoryRow->save($brCategoryData);
			}
		}
    }
    
    private function VideoCategories(){
        $ClientCategory = $this->client->Categories();
        $cNum = $ClientCategory->ListCategoriesForFeed($this->feedId, 0, 100,'','')->totalCount;
        $categoryList = array();
        for($i=0;$i<$cNum;$i++){
            $catId = $ClientCategory->ListCategoriesForFeed($this->feedId,0,100,'','')->items[$i]->id;
            $catNew = $ClientCategory->Get($catId);
            $categoryList[] = array($catId, $catNew->name);
            if($this->debug){
                JLog::add(sprintf('video cat to check is %s with id %d', $catNew->name, $catId), JLog::WARNING, 'com_braftonarticles');
            }
        }
		foreach ($categoryList as $category)
		{
			$categoryRow = JTable::getInstance('Category');
			$brCategoryRow = JTable::getInstance('BraftonCategories', 'Table');

			//check to see if category already exists
			if (!$this->video_category_exists($category, $brCategoryRow))
			{
				//start checking for parent category
				$this->options->load('parent-category');
				$parentId = $this->options->value;
				
				// it's a little awkward using the same model for loading and saving in the same pass.
				// todo: use the dbo for loads/checks as part of the model rewrite
				if (!$categoryRow->load($parentId))
				{
					// if we can't insert under the parent, keep the tree intact. insert under root.
					JLog::add(sprintf('Warning: No parent category match for id %d.', $parentId), JLog::WARNING, 'com_braftonarticles');
					//$parentId = 1;
				}
				//end checking for parent category 
                
				$categoryRow =& JTable::getInstance('Category');
				//try saving as objects instead
                
                $categoryRow->title = trim($category[1]);
                $categoryRow->alias = str_replace(' ','-',strtolower(trim($category[1])));
                $categoryRow->extension = 'com_content';
                $categoryRow->published = 1;
                $categoryRow->language = '*';
                $categoryRow->params = '{"category_layout":"","image":""}';
                $categoryRow->metadata = '{"author":"","robots":"noindex, follow"}';
                $categoryRow->access = 1;
                $categoryRow->store();
                if($this->debug){
                    JLog::add(sprintf('parent from database table  %d.', $categoryRow->parent_id), JLog::WARNING, 'com_braftonarticles');
                }
                $oldParent = $categoryRow->set('parent_id', $parentId);
                $oldLevel = $categoryRow->set('level', 2);
                if($this->debug){
                    JLog::add(sprintf('parent from database table after reseting %d.', $oldParent), JLog::WARNING, 'com_braftonarticles');
                }
                $categoryRow->store();
				$brCategoryData['id'] = null;
				$brCategoryData['cat_id'] = $categoryRow->id;
				$brCategoryData['brafton_cat_id'] = $category[0];
				$brCategoryRow->save($brCategoryData);
			}
		}
    }
}
?>