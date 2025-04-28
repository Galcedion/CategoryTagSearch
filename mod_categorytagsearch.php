<?php
defined('_JEXEC') or die;
require_once dirname(__FILE__) . '/helper.php';

$param_list_bool = [
	'search_mode', 'module_head', 'result_thumb', 'result_title',
	'use_article_lang', 'use_tag_lang', 'enable_fontawesome'
];
$param_list_int = [
	'category', 'show_columns', 'show_rows', 'paging', 'result_direction'
];
$param_list_str = [
	'g_class', 'striptag', 'pageheader', 'pageinfo', 'custom_css'
];
$g_cts_config = [];
foreach($param_list_bool as $plb) {
	$g_cts_config[$plb] = $params->get($plb) == 1 ? TRUE : FALSE;
}
foreach($param_list_int as $pli) {
	$g_cts_config[$pli] = intval($params->get($pli));
}
foreach($param_list_str as $pls) {
	$g_cts_config[$pls] = $params->get($pls) === NULL ? '' : trim($params->get($pls));
}
$g_cts_config['rpp'] = intval($g_cts_config['show_columns']) * intval($g_cts_config['show_rows']);
if($g_cts_config['search_mode']) {
	$g_cts_config['GET'] = [];
	$g_cts_config['GET']['tags'] = isset($_GET['tags']) ? filter_var($_GET['tags'], FILTER_SANITIZE_URL) : NULL;
	$g_cts_config['GET']['p'] = isset($_GET['p']) ? filter_var($_GET['p'], FILTER_SANITIZE_NUMBER_INT) : NULL;
}
$g_cts_config['current_lang'] = JFactory::getLanguage()->getTag();

$tag_list = ModCategoryTagSearch::get_tag_list($g_cts_config);

if(count($tag_list) == 0)
	return;

$article_list = ModCategoryTagSearch::get_article_list($g_cts_config, $tag_list);

require JModuleHelper::getLayoutPath('mod_categorytagsearch');

?>
