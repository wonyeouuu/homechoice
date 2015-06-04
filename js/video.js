//variables
var nurseCurrentState = 1;
var introState = 1;
var play_video = true;

$(document).ready(function(){
	$("#home_video").bind("ended", function() {
	   alert("此網頁僅供作醫護人員與接受APD治療病患的衛教資訊使用，無法取代專業的醫療診察。如有任何透析或治療方面的問題，請您與專業的醫護人員當面討論。");
	   window.location.href = "home.html";
	});
});