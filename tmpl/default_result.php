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
/* set HTML params from config and initial */
if($g_cts_config['search_mode'])
	$article_classes = 'mb-1 g-cts-article ' . $bootstrap_col . ($onpage_count > $g_cts_config['rpp'] ? ' hidden' : '');
else
	$article_classes = 'mb-1 g-cts-article-base ' . $bootstrap_col;
$tag_classes = 'rounded px-1 small m-1';
if(!$g_cts_config['overwrite_colors'] && in_array($g_cts_config['color_tag'], $bootstrap_colors))
	$tag_classes .= ' bg-' . $g_cts_config['color_tag'];
if($g_cts_config['result_direction'] == 0)
	$tag_classes .= ' w-auto';
$tag_style = '';
if($g_cts_config['tag_transparency'])
	$tag_style = 'opacity:0.7;';
if($g_cts_config['overwrite_colors'])
	$tag_style .= ' background-color:' . $g_cts_config['overwrite_color_tag_bg'] . '; color:' . $g_cts_config['overwrite_color_tag_text'] . ';';
else
	$tag_classes .= ' text-white';
if($tag_style != '')
	$tag_style = ' style="' . ltrim($tag_style) . '"';
?>
<div id="g-cts-article-<?=$a['id'];?>" class="<?=$article_classes;?>">
	<div class="container">
		<?php if($g_cts_config['result_direction'] == 1): ?>
			<div class="row">
		<?php endif; ?>
		<?php if($g_cts_config['result_thumb'] && !empty($a['images'])): ?>
			<div class="<?=$g_cts_config['result_direction'] == 0 ? 'row text-center' : 'col';?>">
				<a href="<?=$a['path'];?>"<?=$g_cts_config['result_newtab'] ? ' target="_blank"' : '';?>>
					<img src="<?=$a['images'];?>"/>
				</a>
			</div>
		<?php endif; ?>
		<?php if($g_cts_config['result_title']): ?>
			<div class="<?=$g_cts_config['result_direction'] == 0 ? 'row text-center' : 'col';?>">
				<a href="<?=$a['path'];?>"<?=$g_cts_config['result_newtab'] ? ' target="_blank"' : '';?>>
					<strong><?=$a['label'];?></strong>
				</a>
			</div>
		<?php endif; ?>
		<div class="<?=$g_cts_config['result_direction'] == 0 ? 'row justify-content-center' : 'col';?>">
			<?php foreach($tag_list as $t): ?>
				<?php if(in_array($t['id'], $a['tags'])): ?>
					<strong class="<?=$tag_classes;?> g-cts-tag-<?=$t['id'];?>" title="<?=$t['description'];?>"<?=$tag_style;?>><?=$t['title'];?></strong>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
		<?php if($g_cts_config['result_direction'] == 1): ?>
			</div>
		<?php endif; ?>
	</div>
</div>