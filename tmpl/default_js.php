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
?>
<script>
var gSelectedTags = [];
var gRPP = <?=$g_cts_config['rpp'];?>;
var curCols = 0;
var numCols = <?=$g_cts_config['show_columns'];?>;
var curRows = 0;
var numRows = <?=$g_cts_config['show_rows'];?>;
var curPage = 1;
var maxPage = <?=ceil($onpage_count/$g_cts_config['rpp']);?>;
window.onload = gCTSInit();
function gCTSInit() {
	var onpageCount = 0;
	var articleList = document.getElementsByClassName('g-cts-article-base');
	var currentRow = null;
	for(var i = 0; i < articleList.length; ++i) {
		var id = articleList[i].id;
		++onpageCount;
		if(onpageCount <= gRPP)
			currentRow = gCTSAddResult(id, currentRow);
		maxPage = Math.ceil(onpageCount / gRPP);
	}
	gCTSAddResult(null, currentRow);
}
function gCTSSwitchtag(origin) {
	curPage = 1;
	document.getElementById(origin).classList.toggle('active');
	if(gSelectedTags.indexOf(origin) != -1)
		gSelectedTags.splice(gSelectedTags.indexOf(origin),1);
	else
		gSelectedTags.push(origin);
	var articleList = document.getElementsByClassName('g-cts-article-base');
	var onpageCount = 0;
	gCTSRebuildResults();
	var currentRow = null;
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
			currentRow = gCTSAddResult(id, currentRow);
		maxPage = Math.ceil(onpageCount / gRPP);
	}
	gCTSAddResult(null, currentRow);
	<?php if($g_cts_config['enable_paging']): ?>
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
	var articleList = document.getElementsByClassName('g-cts-article-base');
	var onpageCount = 0;
	gCTSRebuildResults();
	var currentRow = null;
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
			currentRow = gCTSAddResult(id, currentRow);
	}
	gCTSAddResult(null, currentRow);
	<?php if($g_cts_config['enable_paging']): ?>
	gCTSSetPaging();
	<?php endif; ?>
}
function gCTSRebuildResults() {
	curCols = 0;
	curRows = 0;
	document.getElementById('g-cts-list').innerHTML = '';
}
function gCTSAddResult(resultId, currentRow = null) {
	if(curRows >= numRows || (resultId == null && currentRow == null))
		return null;
	if(resultId == null) {
		for(var r = 0; r < (numCols - curCols); ++r) {
			let filler = document.createElement('div');
			filler.classList.add('col');
			currentRow.appendChild(filler);
		}
		document.getElementById('g-cts-list').appendChild(currentRow);
		return;
	}
	let resultClone = document.getElementById(resultId).cloneNode(true);
	resultClone.classList.remove('g-cts-article-base');
	resultClone.classList.add('g-cts-article');
	if(currentRow == null) {
		currentRow = document.createElement('div');
		currentRow.classList.add('row');
		currentRow.classList.add('mb-1');
	}
	currentRow.appendChild(resultClone);
	curCols++;
	if(curCols >= numCols) {
		curCols = 0;
		document.getElementById('g-cts-list').appendChild(currentRow);
		currentRow = null;
		curRows++;
	}
	return currentRow;
}
<?php if($g_cts_config['enable_paging']): ?>
function gCTSSetPaging() {
	document.getElementById('g-cts-paging-cur').textContent = curPage;
	document.getElementById('g-cts-paging-total').textContent = maxPage;
}
<?php endif; ?>
</script>