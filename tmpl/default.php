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
use Joomla\CMS\Helper\ModuleHelper;
/* set HTML params from config and initial */
$col_step = 12 / intval($g_cts_config['show_columns']);
$col_val = 0;
$row_open = FALSE;
$bootstrap_col = 'col-' . $col_step;
if($g_cts_config['search_mode'])
	$onpage_count = 0;
else
	$onpage_count = count($article_list);
?>
<?php require ModuleHelper::getLayoutPath('mod_categorytagsearch', $params->get('layout', 'default') . '_head'); ?>
	<?php if($g_cts_config['search_mode']): ?>
	<div class="container">
	<?php foreach($article_list as $a): ?>
		<?php $onpage_count++; ?>
		<?php if($col_val == 0): ?>
			<div class="row">
		<?php $row_open = TRUE; endif; ?>
		<?php if(!empty($a['tags'])): ?>
			<?php require ModuleHelper::getLayoutPath('mod_categorytagsearch', $params->get('layout', 'default') . '_result'); ?>
		<?php endif; ?>
		<?php $col_val += $col_step; ?>
		<?php if($col_val >= 12): ?>
			</div>
		<?php $col_val = 0; $row_open = FALSE; endif; ?>
	<?php endforeach; ?>
	<?php if($row_open): ?>
		</div>
	<?php endif; ?>
	</div>
<?php else: ?>
	<div id="g-cts-list" class="container"></div>
	<div class="hidden">
	<?php foreach($article_list as $a): ?>
			<?php require ModuleHelper::getLayoutPath('mod_categorytagsearch', $params->get('layout', 'default') . '_result'); ?>
	<?php endforeach; ?>
	</div>
<?php endif; ?>
<?php if($g_cts_config['enable_paging']): ?>
	<?php require ModuleHelper::getLayoutPath('mod_categorytagsearch', $params->get('layout', 'default') . '_paging'); ?>
<?php endif; ?>
<?php if(!$g_cts_config['search_mode']): ?>
	<?php require ModuleHelper::getLayoutPath('mod_categorytagsearch', $params->get('layout', 'default') . '_js'); ?>
<?php endif; ?>