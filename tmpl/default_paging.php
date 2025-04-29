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
if($g_cts_config['enable_fontawesome']) {
	$prev_display = '<i class="fa-solid fa-chevron-left" onclick="gCTSFlipPaging(-1)"></i>';
	$next_display = '<i class="fa-solid fa-chevron-right" onclick="gCTSFlipPaging(1)"></i>';
} else {
	$prev_display = '<input type="button" value="<" onclick="gCTSFlipPaging(-1)">';
	$next_display = '<input type="button" value=">" onclick="gCTSFlipPaging(1)">';
}
?>
<div id="g-cts-paging" class="text-center">
	<?=$prev_display;?>
	<strong id="g-cts-paging-cur">1</strong>
	<strong>/</strong>
	<strong id="g-cts-paging-total"><?=ceil($onpage_count/$g_cts_config['rpp']);?></strong>
	<?=$next_display;?>
</div>