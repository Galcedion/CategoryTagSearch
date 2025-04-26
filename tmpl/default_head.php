<?php if($g_cts_config['module_head']): ?>
	<h3><?=$g_cts_config['pageheader'];?></h3>
	<?php if(strlen($g_cts_config['pageinfo']) >= 1): ?>
		<p><?=$g_cts_config['pageinfo'];?></p>
	<?php endif; ?>
<?php endif; ?>
<div class="g-tagsearch-headline">Filter:
<?php foreach($tag_list as $t): ?>
	<strong id="g-cts-tag-<?=$t['id'];?>" class="btn btn-sm btn-info" title="<?=$t['description'];?>" onclick="gCTSSwitchtag(this.id)"><?=$t['title'];?></strong>
<?php endforeach; ?>
</div>