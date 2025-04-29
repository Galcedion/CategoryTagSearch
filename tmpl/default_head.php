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
?>
<?php if($g_cts_config['module_head']): ?>
	<h3><?=$g_cts_config['pageheader'];?></h3>
	<?php if(strlen($g_cts_config['pageinfo']) >= 1): ?>
		<p><?=$g_cts_config['pageinfo'];?></p>
	<?php endif; ?>
<?php endif; ?>
<div class="g-tagsearch-headline mb-1"><?=$filter_display;?>
<?php foreach($tag_list as $t): ?>
	<strong id="g-cts-tag-<?=$t['id'];?>" class="btn btn-sm btn-info" title="<?=$t['description'];?>" onclick="gCTSSwitchtag(this.id)"><?=$t['title'];?></strong>
<?php endforeach; ?>
</div>