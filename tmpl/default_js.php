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
		if(gSelectedTags.length == 0)
			++onpageCount;
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
		if(gSelectedTags.length == 0)
			++onpageCount;
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