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
if($g_cts_config['search_mode']) { // static mode
	$lower_page = ($g_cts_config['GET']['p'] <= 2) ? 1 : $g_cts_config['GET']['p'] - 1;
	$upper_page = ($g_cts_config['GET']['p'] >= $g_cts_config['max_page'] - 1) ? $g_cts_config['max_page'] : $g_cts_config['GET']['p'] + 1;
	if($g_cts_config['enable_fontawesome']) { // FA
		$prev_display = '<button type="submit" name="p" class="btn" value="' . $lower_page . '"><i class="fa-solid fa-chevron-left"></i></button>';
		$next_display = '<button type="submit" name="p" class="btn" value="' . $upper_page . '"><i class="fa-solid fa-chevron-right"></i></button>';
	} else { // no FA
		$prev_display = '<button type="submit" name="p" class="btn" value="' . $lower_page . '"><</button>';
		$next_display = '<button type="submit" name="p" class="btn" value="' . $upper_page . '">></button>';
	}
} elseif(!$g_cts_config['search_mode'] && $g_cts_config['enable_fontawesome']) { // dynamic mode + FA
	$prev_display = '<i class="fa-solid fa-chevron-left" onclick="gCTSFlipPaging(-1)"></i>';
	$next_display = '<i class="fa-solid fa-chevron-right" onclick="gCTSFlipPaging(1)"></i>';
} elseif(!$g_cts_config['search_mode'] && !$g_cts_config['enable_fontawesome']) { // dynamic mode, no FA
	$prev_display = '<input type="button" class="btn" value="<" onclick="gCTSFlipPaging(-1)">';
	$next_display = '<input type="button" class="btn" value=">" onclick="gCTSFlipPaging(1)">';
}
?>
<div id="g-cts-paging" class="text-center">
	<?php if($g_cts_config['search_mode']): ?>
		<form method="GET">
			<?php foreach($g_cts_config['GET']['joomla'] as $k => $v): ?>
				<input type="hidden" name="<?=$k;?>" value="<?=$v;?>">
			<?php endforeach; ?>
			<input type="hidden" name="tags" value="<?=implode(',', $g_cts_config['GET']['tags']);?>">
	<?php endif; ?>
	<?=$prev_display;?>
	<strong id="g-cts-paging-cur"><?=$g_cts_config['cur_page'];?></strong>
	<strong>/</strong>
	<strong id="g-cts-paging-total"><?=$g_cts_config['max_page'];?></strong>
	<?=$next_display;?>
	<?php if($g_cts_config['search_mode']): ?>
		</form>
	<?php endif; ?>
</div>