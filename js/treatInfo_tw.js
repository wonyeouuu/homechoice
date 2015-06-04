//variables
var nurseCurrentState = 1;
var introState = 1;

$(document).ready(function(){
	   
	//nurse speaking (anonymous function!!!)
   	setInterval(function() {nurseTransfer(nurseCurrentState)}, 500);

   	//intro Transfer 
   	introTransfer(introState);
	setInterval(function() {introTransfer(introState)}, 13000);
		
});

var nurseTransfer = function(nurseCurrentStateF) {
	if (nurseCurrentStateF%3 == 1) {
		$('#nurse').attr("src", "../resources/images/home/Nurse.imageset/a.png");
	}
	else if (nurseCurrentStateF%3 == 2) {
		$('#nurse').attr("src", "../resources/images/home/NurseEye.imageset/a.png");
	}
	else {
		$('#nurse').attr("src", "../resources/images/home/NurseSpeak.imageset/a.png");
	}

	nurseCurrentState = nurseCurrentState + 1;
}

var introTransfer = function(introStateF) {

	if (introStateF%3 == 1) { //APD treat intro light1
		$('#light1').css("opacity", "1");
		$('#light2').css("opacity", "0.2");
		$('#light3').css("opacity", "0.2");
		//audio here
		$('#nurseAudio').attr("src", '../resources/audio/nurse/ch/APDtreat_tw.mp3');
		$('#nurseAudio').trigger('play');
		//speaking content
		$('#contentWord').text('APD治療介紹、優點及操作流程等資訊都在這裡。');
	}
	else if (introStateF%3 == 2) { //compare light2
		$('#light1').css("opacity", "0.2");
		$('#light2').css("opacity", "1");
		$('#light3').css("opacity", "0.2");
		//audio here
		$('#nurseAudio').attr("src", '../resources/audio/nurse/ch/compare_tw.mp3');
		$('#nurseAudio').trigger('play');
		//speaking content
		$('#contentWord').text('洗腎方法有三種！我們一起來看看有什麼不同吧！');
	}
	else { //Q$A light3
		$('#light1').css("opacity", "0.2");
		$('#light2').css("opacity", "0.2");
		$('#light3').css("opacity", "1");
		//audio here
		$('#nurseAudio').attr("src", '../resources/audio/nurse/ch/question_tw.mp3');
		$('#nurseAudio').trigger('play');
		//speaking content
		$('#contentWord').text('這邊有大家常問的問題以及解答喔～');
	}

	introState = introState + 1;
}