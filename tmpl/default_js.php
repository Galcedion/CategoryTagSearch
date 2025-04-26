<script>
var gSelectedTags = [];
var gRPP = <?=$g_cts_config['rpp'];?>;
var curPage = 1;
var maxPage = <?=ceil($onpage_count/$g_cts_config['rpp']);?>;
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
		}
		if(hasTag || gSelectedTags.length == 0)
			++onpageCount;
		if(hasTag && onpageCount <= gRPP)
			document.getElementById(id).classList.remove('hidden');
		else
			document.getElementById(id).classList.add('hidden');
		maxPage = Math.ceil(onpageCount / gRPP);
	}
	<?php if($enable_paging): ?>
	if(onpageCount > gRPP)
		document.getElementById('g-cts-paging').classList.remove('hidden');
	else
		document.getElementById('g-cts-paging').classList.add('hidden');
	gCTSSetPaging();
	<?php endif; ?>
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
		}
		if(hasTag || gSelectedTags.length == 0)
			++onpageCount;
		if(hasTag && Math.ceil(onpageCount / gRPP) == curPage)
			document.getElementById(id).classList.remove('hidden');
		else
			document.getElementById(id).classList.add('hidden');
	}
	<?php if($enable_paging): ?>
	gCTSSetPaging();
	<?php endif; ?>
}
<?php if($enable_paging): ?>
function gCTSSetPaging() {
	document.getElementById('g-cts-paging-cur').textContent = curPage;
	document.getElementById('g-cts-paging-total').textContent = maxPage;
}
<?php endif; ?>
</script>