<?php
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Router\Route;

class ModCategoryTagSearch
{
	public static function get_tag_list($g_cts_config) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)->select('t.id, t.title, t.description')->from($db->quoteName('#__tags', 't'));
		$query->join('LEFT', $db->quoteName('#__contentitem_tag_map', 'ctm') . ' ON ' . $db->quoteName('t.id') . '=' .  $db->quoteName('ctm.tag_id'));
		$query->join('LEFT', $db->quoteName('#__content', 'c') . ' ON ' . $db->quoteName('ctm.content_item_id') . '=' .  $db->quoteName('c.id'));
		$query->where('c.catid = ' . $db->quote($g_cts_config['category']));
		$query->where('t.published = 1');
		$query->where('c.state = 1');
		#$query->where('t.language = ' . $db->quote($g_cts_config['current_lang'])); // TODO lang should be optional!
		$query->group('t.id');
		$query->order('t.title ASC');
		$db->setQuery($query);
		$results = $db->loadAssocList();
		return ModCategoryTagSearch::strip_tag_list($g_cts_config, $results);
	}

	private static function strip_tag_list($g_cts_config, $tag_list) {
		foreach($tag_list as &$t) {
			#$t['title'] = str_replace('-' . $g_cts_config['current_lang'], '', $t['title']); // TODO lang should be optional!
			if($g_cts_config['striptag'] != "")
				$t['title'] = str_replace($g_cts_config['striptag'], $t['title']);
			$t['description'] = str_replace('<p>', '', str_replace('</p>', '', $t['description']));
		}
		unset($t);
		return $tag_list;
	}
	
	public static function get_article_list($g_cts_config, $tags) {
		$tagids = ModCategoryTagSearch::get_tag_ids($tags);
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)->select('m.title, m.path, m.link')->from($db->quoteName('#__menu', 'm'));
		$query->where('m.published = 1');
		$query->where('m.link LIKE "%view=article%"');
		#$query->where('m.language = ' . $db->quote($g_cts_config['current_lang'])); // TODO lang should be optional
		$query->order('m.id ASC');
		$db->setQuery($query);
		$menu = $db->loadAssocList();
		// TODO when menu is empty, there are errors displayed since path and label are never set
		foreach($menu as &$m) {
			$start = strpos($m['link'], '&id=') + 4;
			if(strpos($m['link'], '&', $start) === FALSE)
				$m['id'] = substr($m['link'], $start);
			else
				$m['id'] = substr($m['link'], $start, strpos($m['link'], '&', $start) - $start);
		}
		unset($m);
		$query = $db->getQuery(true)->select('c.id, c.title, c.alias, cat.alias')->from($db->quoteName('#__content', 'c'));
		$query->join('LEFT', $db->quoteName('#__contentitem_tag_map', 'ctm') . ' ON ' . $db->quoteName('c.id') . '=' .  $db->quoteName('ctm.content_item_id'));
		$query->join('LEFT', $db->quoteName('#__categories', 'cat') . ' ON ' . $db->quoteName('c.catid') . '=' .  $db->quoteName('cat.id'));
		$query->where('c.catid = ' . $db->quote($g_cts_config['category']));
		$query->where('c.state = 1');
		#$query->where('c.language = ' . $db->quote($g_cts_config['current_lang'])); // TODO lang should be optional!
		$query->where('ctm.tag_id IN (' . implode(',', $tagids) . ')');
		$query->group('c.id');
		$query->order('c.title ASC');
		$db->setQuery($query);
		$articles = $db->loadAssocList();
		foreach($articles as &$a) {$a['id'];
			foreach($menu as $m) {
				if($a['id'] == $m['id']) {
					$a['path'] = $m['path'];
					$a['label'] = $m['title'];
					break;
				}
			}
			if(!isset($a['path'])) {
				$a['path'] = Route::_('index.php?option=com_content&view=article&id=' . $a['id'] .'&catid=' . $g_cts_config['category']);
				$a['label'] = $a['title'];
			}
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

	private static function get_tag_ids($tag_list) {
		$tagids = [];
		foreach($tag_list as $t)
			$tagids[] = $t['id'];
		return $tagids;
	}
}


?>
