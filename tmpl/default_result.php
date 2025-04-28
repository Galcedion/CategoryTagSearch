<?php
defined('_JEXEC') or die;
if($g_cts_config['result_direction'] == 0)
	$tag_classes = 'bg-info text-white rounded px-1 small w-auto mx-1';
else
	$tag_classes = 'bg-info text-white rounded px-1 small';
?>
<div id="g-cts-article-<?=$a['id'];?>" class="mb-1 g-cts-article <?=$bootstrap_col;?><?=($onpage_count > $g_cts_config['rpp'] ? ' hidden' : '');?>">
	<div class="container">
		<?php if($g_cts_config['result_direction'] == 1): ?>
			<div class="row">
		<?php endif; ?>
		<?php if($g_cts_config['result_thumb']): ?>
			<div class="<?=$g_cts_config['result_direction'] == 0 ? 'row text-center' : 'col';?>">
				<a href="<?=$a['path'];?>">
					<img src="<?=$a['images'];?>"/>
				</a>
			</div>
		<?php endif; ?>
		<?php if($g_cts_config['result_title']): ?>
			<div class="<?=$g_cts_config['result_direction'] == 0 ? 'row text-center' : 'col';?>">
				<a href="<?=$a['path'];?>">
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