<?php
defined('_JEXEC') or die; ?>
<?php if($params->get('g_module_head') == 1): ?>
	<h3><?=$params->get('g_pageheader');?></h3>
	<?php if(strlen($params->get('g_pageinfo')) >= 1): ?>
		<p><?=$params->get('g_pageinfo');?></p>
	<?php endif; ?>
<?php endif; ?>
<div class="g-tagsearch-headline">Filter:
<?php foreach($tag_list as $t): ?>
	<strong id="g-tag-<?=$t['id'];?>" class="g-tagsearch-tag g-tagsearch-inactive" title="<?=$t['description'];?>" onclick="g_switchtag(this.id)"><?=$t['title'];?></strong>
<?php endforeach; ?>
</div>
<?php foreach($article_list as $a): ?>
	<div id="g-article-<?=$a['id'];?>" class="g-article">
		<div class="g-article-title">
			<a href="<?=$a['path'];?>">
				<strong><?=$a['label'];?></strong>
			</a>
		</div>
		<div class="g-article-tags">
			<?php foreach($tag_list as $t): ?>
				<?php if(in_array($t['id'], $a['tags'])): ?>
					<strong class="g-tagsearch-tag g-tag-<?=$t['id'];?>" title="<?=$t['description'];?>"><?=$t['title'];?></strong>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
		
	</div>
<?php endforeach; ?>
</div>
<script>
g_selected_tags = [];
function g_switchtag(origin) {
	document.getElementById(origin).classList.toggle('g-tagsearch-inactive');
	if(g_selected_tags.indexOf(origin) != -1)
		g_selected_tags.splice(g_selected_tags.indexOf(origin),1);
	else
		g_selected_tags.push(origin);
	var article_list = document.getElementsByClassName('g-article');
	for(var i = 0; i < article_list.length; ++i) {
		var id = article_list[i].id;
		var has_tag = true;
		for(var j = 0; j < g_selected_tags.length; ++j) {
			if(article_list[i].querySelector('.' + g_selected_tags[j]) == null) {
				has_tag = false;
				break;
			}
		}
		if(has_tag)
			document.getElementById(id).classList.remove('g-hidden');
		else
			document.getElementById(id).classList.add('g-hidden');
	}
}
</script>
