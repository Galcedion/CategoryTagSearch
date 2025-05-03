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
if($g_cts_config['result_direction'] == 0)
	$tag_classes = 'bg-info text-white rounded px-1 small w-auto mx-1';
else
	$tag_classes = 'bg-info text-white rounded px-1 small';
?>
<div id="g-cts-article-<?=$a['id'];?>" class="<?=$article_classes;?>">
	<div class="container">
		<?php if($g_cts_config['result_direction'] == 1): ?>
			<div class="row">
		<?php endif; ?>
		<?php if($g_cts_config['result_thumb']): ?>
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
					<strong class="<?=$tag_classes;?> g-cts-tag-<?=$t['id'];?>" title="<?=$t['description'];?>"><?=$t['title'];?></strong>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
		<?php if($g_cts_config['result_direction'] == 1): ?>
			</div>
		<?php endif; ?>
	</div>
</div>