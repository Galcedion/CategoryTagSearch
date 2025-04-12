<?php
defined('_JEXEC') or die;
require_once dirname(__FILE__) . '/helper.php';

$category = $params->get('g_category');
$striptag = $params->get('g_striptag');
$lang = JFactory::getLanguage()->getTag();
 
$tag_list = ModCategoryTagSearch::get_tag_list($category, $lang);
if(count($tag_list) == 0)
	return;
$tagids = array();

foreach($tag_list as &$t) {
	$tagids[] = $t['id'];
	$t['title'] = str_replace('-' . $lang, '', $t['title']);
	if($striptag != "")
		$t['title'] = str_replace($striptag, $t['title']);
	$t['description'] = str_replace('<p>', '', str_replace('</p>', '', $t['description']));
}
unset($t);

$article_list = ModCategoryTagSearch::get_article_list($category, $tagids, $lang);

require JModuleHelper::getLayoutPath('mod_categorytagsearch');

?>
