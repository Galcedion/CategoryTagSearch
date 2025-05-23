<?php
/**
 * @package    Joomla.Site
 * @subpackage mod_categorytagsearch
 *
 * @author     Galcedion https://galcedion.com
 * @copyright  Copyright (c) 2025 Galcedion
 * @license    GNU/GPL: https://gnu.org/licenses/gpl.html
 */
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;

/**
 * Helper class for the Module CategoryTagSearch
 */
class ModCategoryTagSearch
{
	/**
	 * static function to get the tags from the database
	 *
	 * @param associative array containing the module config
	 *
	 * @return array of finished tags (assoc arrays)
	 */
	public static function get_tag_list($g_cts_config) {
		$db = Factory::getDbo();
		$query = $db->getQuery(true)->select('t.id, t.title, t.description')->from($db->quoteName('#__tags', 't'));
		$query->join('LEFT', $db->quoteName('#__contentitem_tag_map', 'ctm') . ' ON ' . $db->quoteName('t.id') . '=' .  $db->quoteName('ctm.tag_id'));
		$query->join('LEFT', $db->quoteName('#__content', 'c') . ' ON ' . $db->quoteName('ctm.content_item_id') . '=' .  $db->quoteName('c.id'));
		$query->where('c.catid = ' . $db->quote($g_cts_config['category']));
		$query->where('t.published = 1');
		$query->where('c.state = 1');
		if($g_cts_config['use_tag_lang']) // if active, only get tags from the user's language
			$query->where('t.language = ' . $db->quote($g_cts_config['current_lang']));
		if($g_cts_config['use_article_lang']) // if active, only get articles from the user's language
			$query->where('c.language = ' . $db->quote($g_cts_config['current_lang']));
		$query->group('t.id');
		$query->order('t.title ASC');
		$db->setQuery($query);
		$results = $db->loadAssocList();
		return ModCategoryTagSearch::strip_tag_list($g_cts_config, $results);
	}

	/**
	 * static function to remove unwanted text from tags
	 *
	 * @param associative array containing the module config
	 * @param array of tags (assoc arrays)
	 *
	 * @return array of stripped tags (assoc arrays)
	 */
	private static function strip_tag_list($g_cts_config, $tag_list) {
		$g_cts_config['striptag'] = array_map('trim', explode(PHP_EOL, $g_cts_config['striptag']));
		$g_cts_config['striptag'] = array_filter(array_unique($g_cts_config['striptag']));
		foreach($tag_list as &$t) {
			if($g_cts_config['use_tag_lang']) // if active, strip the lang tag of the user's language
				$t['title'] = str_replace('-' . $g_cts_config['current_lang'], '', $t['title']);
			if(count($g_cts_config['striptag']) > 0) { // only strip if something has been set to strip
				foreach($g_cts_config['striptag'] as $st)
					$t['title'] = str_replace($st, '', $t['title']);
			}
			$t['description'] = str_replace('<p>', '', str_replace('</p>', '', $t['description']));
		}
		unset($t);
		return $tag_list;
	}

	/**
	 * static function to get the articles from the database
	 *
	 * @param associative array containing the module config
	 * @param array of tags (assoc arrays)
	 *
	 * @return array of articles (assoc arrays)
	 */
	public static function get_article_list($g_cts_config, $tags) {
		switch($g_cts_config['sort_by']) {
			case 1:	$sorting = 'created';
					break;
			case 0:
			default:
					$sorting = 'title';
					break;
		}
		if($g_cts_config['sort_direction'])
			$sorting .= ' ASC';
		else
			$sorting .= ' DESC';
		$tagids = ModCategoryTagSearch::get_db_item_ids($tags);
		/* get required articles */
		$db = Factory::getDbo();
		$query = $db->getQuery(true)->select('c.id, c.title, c.images')->from($db->quoteName('#__content', 'c'));
		$query->join('LEFT', $db->quoteName('#__contentitem_tag_map', 'ctm') . ' ON ' . $db->quoteName('c.id') . '=' .  $db->quoteName('ctm.content_item_id'));
		$query->where('c.catid = ' . $db->quote($g_cts_config['category']));
		$query->where('c.state = 1');
		if($g_cts_config['use_article_lang']) // if active, only get articles from the user's language
			$query->where('c.language = ' . $db->quote($g_cts_config['current_lang']));
		$query->where('ctm.tag_id IN (' . implode(',', $tagids) . ')');
		$query->group('c.id');
		$query->order('c.' . $sorting);
		$db->setQuery($query);
		$articles = $db->loadAssocList();
		$article_ids = ModCategoryTagSearch::get_db_item_ids($articles);
		/* get tags of this article */
		$query = $db->getQuery(true)->select('t.id AS tid, ctm.content_item_id AS aid')->from($db->quoteName('#__tags', 't'));
		$query->join('LEFT', $db->quoteName('#__contentitem_tag_map', 'ctm') . ' ON ' . $db->quoteName('t.id') . '=' .  $db->quoteName('ctm.tag_id'));
		$query->where('ctm.content_item_id IN (' . implode(',', $article_ids) . ')');
		$query->order('t.title ASC');
		$db->setQuery($query);
		$articletags = $db->loadAssocList();
		foreach($articles as $k => &$a) { // build article data for display
			$a['images'] = json_decode($a['images'], true)['image_intro'];
			if(!isset($a['path'])) { // build URL and title
				$a['path'] = Route::_('index.php?option=com_content&view=article&id=' . $a['id'] .'&catid=' . $g_cts_config['category']);
				$a['label'] = $a['title'];
			}
			$a['tags'] = array();
			foreach($articletags as $at) { // add tags to article
				if($a['id'] == $at['aid'])
					$a['tags'][] = $at['tid'];
			}
			if(!empty($g_cts_config['GET']['tags'])) { // if tags are given and not matching, remove result
				if(array_intersect($g_cts_config['GET']['tags'], $a['tags']) != $g_cts_config['GET']['tags'])
					unset($articles[$k]);
			}
		}
		unset($a);
		return $articles;
	}

	/**
	 * static function to get the ids from database results
	 *
	 * @param array of tags (assoc arrays)
	 *
	 * @return array of tag ids
	 */
	private static function get_db_item_ids($item_list) {
		$item_ids = [];
		foreach($item_list as $t)
			$item_ids[] = $t['id'];
		return $item_ids;
	}

	/**
	 * static function to get one single page of articles (intended for PHP Paging)
	 *
	 * @param array of articles (assoc arrays)
	 * @param associative array containing the module config
	 *
	 * @return array of articles (assoc arrays)
	 */
	public static function get_article_list_page($article_list, $g_cts_config) {
		$range_start = $g_cts_config['rpp'] * ($g_cts_config['GET']['p'] - 1);
		$range_end = $g_cts_config['rpp'] * ($g_cts_config['GET']['p']);
		$article_list = array_slice($article_list, $range_start, $range_end);
		return $article_list;
	}
}


?>
