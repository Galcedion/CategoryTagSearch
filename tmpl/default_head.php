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
$filter_display = $g_cts_config['enable_fontawesome'] ? '<i class="fa-solid fa-magnifying-glass"></i>' : '';
$btn_class = 'btn btn-sm';
if(in_array($g_cts_config['color_button'], $bootstrap_colors))
	$btn_class .= ' btn-' . $g_cts_config['color_button'];
?>
<?php if($g_cts_config['module_head']): ?>
	<h3><?=$g_cts_config['pageheader'];?></h3>
	<?php if(!empty($g_cts_config['pageinfo'])): ?>
		<p><?=$g_cts_config['pageinfo'];?></p>
	<?php endif; ?>
<?php endif; ?>
<div class="g-tagsearch-headline mb-1"><?=$filter_display;?>
<?php if($g_cts_config['search_mode']): ?>
	<form method="GET">
		<?php foreach($g_cts_config['GET']['joomla'] as $k => $v): ?>
			<input type="hidden" name="<?=$k;?>" value="<?=$v;?>">
		<?php endforeach; ?>
		<?php foreach($tag_list as $t): ?>
			<?php $existing_pos = array_search($t['id'], $g_cts_config['GET']['tags']); ?>
			<?php if($existing_pos !== FALSE): ?>
				<?php $selected_tags = array_diff($g_cts_config['GET']['tags'], [$t['id']]); ?>
			<?php else: ?>
				<?php $selected_tags = array_merge($g_cts_config['GET']['tags'], [$t['id']]); ?>
			<?php endif; ?>
			<button type="submit" id="g-cts-tag-<?=$t['id'];?>" name="tags" value="<?=implode(',', $selected_tags);?>" class="<?=$btn_class;?>" title="<?=$t['description'];?>"><?=$t['title'];?></button>
		<?php endforeach; ?>
	</form>
<?php else: ?>
	<?php foreach($tag_list as $t): ?>
		<strong id="g-cts-tag-<?=$t['id'];?>" class="<?=$btn_class;?>" title="<?=$t['description'];?>" onclick="gCTSSwitchtag(this.id)"><?=$t['title'];?></strong>
	<?php endforeach; ?>
<?php endif; ?>
</div>