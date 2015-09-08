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


				
				$categoryData = array(
					'title' =>			trim($category->getName()),
					'alias' =>			strtolower(trim($category->getName())), /* check() handles slugification */
					'extension' =>		'com_content',
					'published' =>		1,
					'language' =>		'*',
					'params' =>			'{"category_layout":"","image":""}',
					'metadata' =>		'{"author":"","robots":"noindex, follow"}',
					'access' =>			1,
                    'parent_id' =>      $parentId
				);
				
				$categoryRow = JTable::getInstance('Category');
				
				if (!$categoryRow->save($categoryData))
				{
					// if all our failsafes have failed then this category is no good.
					// don't save; we'll get downstream notices for support/debug.
					JLog::add(sprintf('Error: Unable to add category %s - %s', $category->getName(), $categoryRow->getError()), JLog::ERROR, 'com_braftonarticles');
					continue;
				}
				
				$brCategoryData['id'] = null;
				$brCategoryData['cat_id'] = $categoryRow->id;
				$brCategoryData['brafton_cat_id'] = (int) $category->getId();
				$brCategoryRow->save($brCategoryData);
			}
		}
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
}
?>