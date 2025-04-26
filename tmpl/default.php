<?php
defined('_JEXEC') or die;
use Joomla\CMS\Helper\ModuleHelper;
$col_step = 12 / intval($g_cts_config['show_columns']);
$col_val = 0;
$row_open = FALSE;
$bootstrap_col = 'col-' . $col_step;
$onpage_count = 0;
$enable_paging = (count($article_list) > $g_cts_config['rpp']) ? TRUE : FALSE;
?>
<?php require ModuleHelper::getLayoutPath('mod_categorytagsearch', $params->get('layout', 'default') . '_head'); ?>
<div class="container">
<?php foreach($article_list as $a): ?>
	<?php $onpage_count++; ?>
	<?php if($col_val == 0): ?>
		<div class="row">
	<?php $row_open = TRUE; endif; ?>
		<?php require ModuleHelper::getLayoutPath('mod_categorytagsearch', $params->get('layout', 'default') . '_result'); ?>
	<?php $col_val += $col_step; ?>
	<?php if($col_val >= 12): ?>
		</div>
	<?php $col_val = 0; $row_open = FALSE; endif; ?>
<?php endforeach; ?>
<?php if($row_open): ?>
	</div>
<?php endif; ?>
</div>
<?php if($g_cts_config['search_mode']): ?>
	<?php if($enable_paging): ?>
		<?php require ModuleHelper::getLayoutPath('mod_categorytagsearch', $params->get('layout', 'default') . '_paging'); ?>
	<?php endif; ?>
	<?php require ModuleHelper::getLayoutPath('mod_categorytagsearch', $params->get('layout', 'default') . '_js'); ?>
<?php endif; ?>