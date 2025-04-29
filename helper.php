<?php
/**
 * @package    Joomla.Site
 * @subpackage mod_categorytagsearch
 *
 * @author     Galcedion https://galcedion.com
 * @copyright  Copyright (c) 2025 Galcedion
 * @license    GNU/GPL: https://gnu.org/licenses/gpl.html
 */
use Joomla\CMS\Uri\Uri;
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
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)->select('t.id, t.title, t.description')->from($db->quoteName('#__tags', 't'));
		$query->join('LEFT', $db->quoteName('#__contentitem_tag_map', 'ctm') . ' ON ' . $db->quoteName('t.id') . '=' .  $db->quoteName('ctm.tag_id'));
		$query->join('LEFT', $db->quoteName('#__content', 'c') . ' ON ' . $db->quoteName('ctm.content_item_id') . '=' .  $db->quoteName('c.id'));
		$query->where('c.catid = ' . $db->quote($g_cts_config['category']));
		$query->where('t.published = 1');
		$query->where('c.state = 1');
		if($g_cts_config['use_tag_lang']) // if active, only get tags from the user's language
			$query->where('t.language = ' . $db->quote($g_cts_config['current_lang']));
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
	private static function strip_tag_list($g_cts_config, $tag_list) { // TODO: better handling
		foreach($tag_list as &$t) {
			if($g_cts_config['use_tag_lang']) // if active, strip the lang tag of the user's language
				$t['title'] = str_replace('-' . $g_cts_config['current_lang'], '', $t['title']);
			if($g_cts_config['striptag'] != "") // only strip if something has been set to strip
				$t['title'] = str_replace($g_cts_config['striptag'], $t['title']);
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
		$tagids = ModCategoryTagSearch::get_tag_ids($tags);
		/* get related menu structure */
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)->select('m.title, m.path, m.link')->from($db->quoteName('#__menu', 'm'));
		$query->where('m.published = 1');
		$query->where('m.link LIKE "%view=article%"');
		if($g_cts_config['use_article_lang']) // if active, only get menu items from the user's language
			$query->where('m.language = ' . $db->quote($g_cts_config['current_lang']));
		$query->order('m.id ASC');
		$db->setQuery($query);
		$menu = $db->loadAssocList();
		// TODO when menu is empty, there are errors displayed since path and label are never set
		foreach($menu as &$m) { // prepare menu item URLs (item id)
			$start = strpos($m['link'], '&id=') + 4;
			if(strpos($m['link'], '&', $start) === FALSE)
				$m['id'] = substr($m['link'], $start);
			else
				$m['id'] = substr($m['link'], $start, strpos($m['link'], '&', $start) - $start);
		}
		unset($m);
		/* get required articles */
		$query = $db->getQuery(true)->select('c.id, c.title, c.images')->from($db->quoteName('#__content', 'c'));
		$query->join('LEFT', $db->quoteName('#__contentitem_tag_map', 'ctm') . ' ON ' . $db->quoteName('c.id') . '=' .  $db->quoteName('ctm.content_item_id'));
		$query->join('LEFT', $db->quoteName('#__categories', 'cat') . ' ON ' . $db->quoteName('c.catid') . '=' .  $db->quoteName('cat.id'));
		$query->where('c.catid = ' . $db->quote($g_cts_config['category']));
		$query->where('c.state = 1');
		if($g_cts_config['use_article_lang']) // if active, only get articles from the user's language
			$query->where('c.language = ' . $db->quote($g_cts_config['current_lang']));
		$query->where('ctm.tag_id IN (' . implode(',', $tagids) . ')');
		$query->group('c.id');
		$query->order('c.title ASC');
		$db->setQuery($query);
		$articles = $db->loadAssocList();
		foreach($articles as &$a) { // build article data for display
			$a['images'] = json_decode($a['images'], true)['image_intro'];
			foreach($menu as $m) { // map menu to article if available
				if($a['id'] == $m['id']) {
					$a['path'] = $m['path'];
					$a['label'] = $m['title'];
					break;
				}
			}
			if(!isset($a['path'])) { // build alternative URL and title when no menu available
				$a['path'] = Route::_('index.php?option=com_content&view=article&id=' . $a['id'] .'&catid=' . $g_cts_config['category']);
				$a['label'] = $a['title'];
			}
			/* get tags of this article */
			// TODO can be a lot of SQL calls
			$query = $db->getQuery(true)->select('t.id')->from($db->quoteName('#__tags', 't'));
			$query->join('LEFT', $db->quoteName('#__contentitem_tag_map', 'ctm') . ' ON ' . $db->quoteName('t.id') . '=' .  $db->quoteName('ctm.tag_id'));
			$query->where('ctm.content_item_id = ' . $a['id']);
			$query->order('t.title ASC');
			$db->setQuery($query);
			$articletags = $db->loadAssocList();
			$s['tags'] = array();
			foreach($articletags as $at) {
				$a['tags'][] = $at['id'];
			}
		}
		unset($a);
		return $articles;
	}

	/**
	 * static function to get the ids from the tags
	 *
	 * @param array of tags (assoc arrays)
	 *
	 * @return array of tag ids
	 */
	private static function get_tag_ids($tag_list) {
		$tagids = [];
		foreach($tag_list as $t)
			$tagids[] = $t['id'];
		return $tagids;
	}
}


?>
