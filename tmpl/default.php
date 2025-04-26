<?php
defined('_JEXEC') or die;
$col_step = 12 / intval($g_cts_config['show_columns']);
$col_val = 0;
$row_open = FALSE;
$bootstrap_col = 'col-' . $col_step;
$onpage_count = 0;
?>
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
<div class="container">
<?php foreach($article_list as $a): ?>
	<?php $onpage_count++; ?>
	<?php if($col_val == 0): ?>
		<div class="row">
	<?php $row_open = TRUE; endif; ?>
		<div id="g-cts-article-<?=$a['id'];?>" class="g-cts-article card <?=$bootstrap_col;?><?=($onpage_count > $g_cts_config['rpp'] ? ' hidden' : '');?>">
			<div class="card-title">
				<a href="<?=$a['path'];?>">
					<strong><?=$a['label'];?></strong>
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
<div class="text-center">
	<input type="button" value="<" onclick="gCTSFlipPaging(-1)">
	<strong id="g-cts-paging-cur">1</strong>
	<strong>/</strong>
	<strong id="g-cts-paging-total"><?=ceil($onpage_count/$g_cts_config['rpp']);?></strong>
	<input type="button" value=">" onclick="gCTSFlipPaging(1)">
</div>
<script>
var gSelectedTags = [];
var gRPP = <?=$g_cts_config['rpp'];?>;
var curPage = 1;
var maxPage = 0;
function gCTSSwitchtag(origin) {
	curPage = 1;
	document.getElementById(origin).classList.toggle('active');
	if(gSelectedTags.indexOf(origin) != -1)
		gSelectedTags.splice(gSelectedTags.indexOf(origin),1);
	else
		gSelectedTags.push(origin);
	var articleList = document.getElementsByClassName('g-cts-article');
	var onpageCount = 0;
	for(var i = 0; i < articleList.length; ++i) {
		var id = articleList[i].id;
		var hasTag = true;
		for(var j = 0; j < gSelectedTags.length; ++j) {
			if(articleList[i].querySelector('.' + gSelectedTags[j]) == null) {
				hasTag = false;
				break;
			}
			++onpageCount;
		}
		if(hasTag && onpageCount <= gRPP)
			document.getElementById(id).classList.remove('hidden');
		else
			document.getElementById(id).classList.add('hidden');
		maxPage = Math.ceil(onpageCount / gRPP);
		gCTSSetPaging();
	}
}
function gCTSFlipPaging(step) {
	if((step == -1 && curPage == 1) || (step == 1 && curPage == maxPage))
		return;
	curPage += step;
	var articleList = document.getElementsByClassName('g-cts-article');
	var onpageCount = 0;
	for(var i = 0; i < articleList.length; ++i) {
		var id = articleList[i].id;
		var hasTag = true;
		for(var j = 0; j < gSelectedTags.length; ++j) {
			if(articleList[i].querySelector('.' + gSelectedTags[j]) == null) {
				hasTag = false;
				break;
			}
			++onpageCount;
		}
		if(hasTag && Math.ceil(onpageCount / gRPP) == curPage)
			document.getElementById(id).classList.remove('hidden');
		else
			document.getElementById(id).classList.add('hidden');
	}
	gCTSSetPaging();
}
function gCTSSetPaging() {
	document.getElementById('g-cts-paging-cur').textContent = curPage;
	document.getElementById('g-cts-paging-total').textContent = maxPage;
}
</script>
<?php endif; ?>