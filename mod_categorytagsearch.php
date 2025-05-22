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
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Uri\Uri;
require_once dirname(__FILE__) . '/helper.php';

/* types of config parameters (only these will be used) */
$param_list_bool = [
	'search_mode', 'module_head', 'result_thumb', 'result_title', 'result_newtab',
	'use_article_lang', 'use_tag_lang', 'enable_fontawesome', 'sort_direction', 'tag_transparency'
];
$param_list_int = [
	'category', 'show_columns', 'show_rows', 'result_direction', 'sort_by'
];
$param_list_str = [
	'g_class', 'striptag', 'pageheader', 'pageinfo', 'custom_css',
	'color_button', 'color_tag'
];
/* building main config */
$g_cts_config = [];
foreach($param_list_bool as $plb) { // turn params to bool
	$g_cts_config[$plb] = $params->get($plb) == 1 ? TRUE : FALSE;
}
foreach($param_list_int as $pli) { // sanitize int params
	$g_cts_config[$pli] = intval($params->get($pli));
}
foreach($param_list_str as $pls) { // clean up str params
	$g_cts_config[$pls] = $params->get($pls) === NULL ? '' : trim($params->get($pls));
}
$g_cts_config['rpp'] = intval($g_cts_config['show_columns']) * intval($g_cts_config['show_rows']);
$g_cts_config['enable_paging'] = NULL; // the actual value will be set in get_article_list
if($g_cts_config['search_mode']) { // get data from HTTP GET
	$g_cts_config['GET'] = [];
	$get_contents = Uri::getInstance();
	if(empty($get_contents->getVar('tags')))
		$g_cts_config['GET']['tags'] = [];
	else {
		$g_cts_config['GET']['tags'] = explode(',', preg_replace('/[^\d,]/', '', $get_contents->getVar('tags')));
		$g_cts_config['GET']['tags'] = array_filter(array_unique($g_cts_config['GET']['tags']));
	}
	$g_cts_config['GET']['p'] = filter_var($get_contents->getVar('p'), FILTER_SANITIZE_NUMBER_INT);
	if(empty($g_cts_config['GET']['p']))
		$g_cts_config['GET']['p'] = 1;
	// add parameters set by Joomla when search engine friendly URLs are disabled
	$g_cts_config['GET']['joomla'] = [];
	$j_GET_string = ['option', 'view', 'layout', 'format', 'type'];
	$j_GET_int = ['id', 'Itemid', 'catid'];
	foreach($j_GET_string as $jgs) {
		if(!empty($get_contents->getVar($jgs)))
			$g_cts_config['GET']['joomla'][$jgs] = filter_var($get_contents->getVar($jgs), FILTER_SANITIZE_URL);
	}
	foreach($j_GET_int as $jgi) {
		if(!empty($get_contents->getVar($jgi)))
			$g_cts_config['GET']['joomla'][$jgi] = filter_var($get_contents->getVar($jgi), FILTER_SANITIZE_NUMBER_INT);
	}
}
$g_cts_config['current_lang'] = Factory::getLanguage()->getTag(); // the language the user is currently using
foreach($params->get('pagealt') as $pagealt) { // setting up alternative language
	if($pagealt->pagealt_lang == $g_cts_config['current_lang']) {
		$g_cts_config['pageheader'] = $pagealt->pagealt_header;
		$g_cts_config['pageinfo'] = $pagealt->pagealt_info;
	}
}
/* config ready */

$tag_list = ModCategoryTagSearch::get_tag_list($g_cts_config);
if(count($tag_list) == 0)
	return;
$article_list = ModCategoryTagSearch::get_article_list($g_cts_config, $tag_list);
$g_cts_config['enable_paging'] = (count($article_list) > $g_cts_config['rpp']) ? TRUE : FALSE;
$g_cts_config['cur_page'] = empty($g_cts_config['GET']['p']) ? 1 : $g_cts_config['GET']['p'];
$g_cts_config['max_page'] = ceil(count($article_list)/$g_cts_config['rpp']);
if($g_cts_config['search_mode'])
	$article_list = ModCategoryTagSearch::get_article_list_page($article_list, $g_cts_config);

require ModuleHelper::getLayoutPath('mod_categorytagsearch'); // this loads the tmpl/default.php

?>
