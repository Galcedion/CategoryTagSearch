<?php
class ModCategoryTagSearch
{
	public static function get_tag_list($category, $lang) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)->select('t.id, t.title, t.description')->from($db->quoteName('#__tags', 't'));
		$query->join('LEFT', $db->quoteName('#__contentitem_tag_map', 'ctm') . ' ON ' . $db->quoteName('t.id') . '=' .  $db->quoteName('ctm.tag_id'));
		$query->join('LEFT', $db->quoteName('#__content', 'c') . ' ON ' . $db->quoteName('ctm.content_item_id') . '=' .  $db->quoteName('c.id'));
		$query->where('c.catid = ' . $db->quote($category));
		$query->where('t.published = 1');
		$query->where('c.state = 1');
		$query->where('t.language = ' . $db->quote($lang));
		$query->order('t.title ASC');
		$query->group('t.id');
		$db->setQuery($query);
		return $db->loadAssocList();
	}
	
	public static function get_article_list($category, $tags, $lang) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)->select('m.title, m.path, m.link')->from($db->quoteName('#__menu', 'm'));
		$query->where('m.published = 1');
		$query->where('m.link LIKE "%view=article%"');
		$query->where('m.language = ' . $db->quote($lang));
		$query->order('m.id ASC');
		$db->setQuery($query);
		$menu = $db->loadAssocList();
		foreach($menu as &$m) {
			$start = strpos($m['link'], '&id=') + 4;
			if(strpos($m['link'], '&', $start) === FALSE)
				$m['id'] = substr($m['link'], $start);
			else
				$m['id'] = substr($m['link'], $start, strpos($m['link'], '&', $start) - $start);
		}
		unset($m);
		$query = $db->getQuery(true)->select('c.id, c.title')->from($db->quoteName('#__content', 'c'));
		$query->join('LEFT', $db->quoteName('#__contentitem_tag_map', 'ctm') . ' ON ' . $db->quoteName('c.id') . '=' .  $db->quoteName('ctm.content_item_id'));
		$query->where('c.catid = ' . $db->quote($category));
		$query->where('c.state = 1');
		$query->where('c.language = ' . $db->quote($lang));
		$query->where('ctm.tag_id IN (' . implode(',', $tags) . ')');
		$query->order('c.title ASC');
		$query->group('c.id');
		$db->setQuery($query);
		$articles = $db->loadAssocList();
		foreach($articles as &$a) {
			foreach($menu as $m) {
				if($a['id'] == $m['id']) {
					$a['path'] = $m['path'];
					$a['label'] = $m['title'];
					break;
				}
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
}


?>
