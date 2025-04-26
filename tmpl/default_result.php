<div id="g-cts-article-<?=$a['id'];?>" class="g-cts-article card <?=$bootstrap_col;?><?=($onpage_count > $g_cts_config['rpp'] ? ' hidden' : '');?>">
	<div class="card-title">
		<a href="<?=$a['path'];?>">
			<?php if($g_cts_config['result_icon']): ?>
				<img src="<?=$a['images'];?>"/>
			<?php endif; ?>
			<?php if($g_cts_config['result_title']): ?>
				<strong><?=$a['label'];?></strong>
			<?php endif; ?>
		</a>
	</div>
	<div class="card-body">
		<?php foreach($tag_list as $t): ?>
			<?php if(in_array($t['id'], $a['tags'])): ?>
				<strong class="bg-info text-white rounded px-1 g-cts-tag-<?=$t['id'];?>" title="<?=$t['description'];?>"><?=$t['title'];?></strong>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
</div>