<?php
defined('_JEXEC') or die;
?>
<div id="g-cts-paging" class="text-center">
	<input type="button" value="<" onclick="gCTSFlipPaging(-1)">
	<strong id="g-cts-paging-cur">1</strong>
	<strong>/</strong>
	<strong id="g-cts-paging-total"><?=ceil($onpage_count/$g_cts_config['rpp']);?></strong>
	<input type="button" value=">" onclick="gCTSFlipPaging(1)">
</div>