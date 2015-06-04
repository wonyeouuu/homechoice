//variables
var nurseCurrentState = 1;
var introState = 1;
var play_video = true;
var jsonBuffer = {};
var opt = 4;//non select
var input = {};

$(document).ready(function(){
	//nurse speaking (anonymous function!!!)
	setInterval(function() {nurseTransfer(nurseCurrentState)}, 500);
	//intro Transfer
	introTransfer(introState);
	setInterval(function() {introTransfer(introState)}, 14000);
	
	var languageState = 1;
	$('#language').click(function() {
		if (languageState % 2 == 1) {
			$('#languageChooser').show();
		}
		else {
			$('#languageChooser').hide();
		}
		languageState = languageState + 1;
	});
	
	
	
	// LightBox
	
	/*
	$('#popContainer').click(function(){
		$('#popContainer').fadeOut();
		//_popImg.attr('src','');
	});
	*/
	// $('#treatMethod').hover(
	// 	function() {
	// 		$('#treatMethod').attr("src", "../resources/images/home/TreatIntroSelect.imageset/0518_Baxter app_2主畫面-08.png");
	// 	}, function() {
	// 		$('#treatMethod').attr("src", "../resources/images/home/TreatIntro.imageset/0518_Baxter app_2主畫面-04.png");
	// 	}
	// );

	// $('#machine').hover(
	// 	function() {
	// 		$('#machine').attr("src", "../resources/images/home/MachineIntroSelect.imageset/0518_Baxter app_2主畫面-10.png");
	// 	}, function() {
	// 		$('#machine').attr("src", "../resources/images/home/MachineIntro.imageset/0518_Baxter app_2主畫面-06.png");
	// 	}
	// );

	// $('#operation').hover(
	// 	function() {
	// 		$('#operation').attr("src", "../resources/images/home/OperationHelpSelect.imageset/0518_Baxter app_2主畫面-11.png");
	// 	}, function() {
	// 		$('#operation').attr("src", "../resources/images/home/OperationHelp.imageset/0518_Baxter app_2主畫面-07.png");
	// 	}
	// );

	// $('#TV').hover(
	// 	function() {
	// 		$('#TV').attr("src", "../resources/images/home/MovieSelect.imageset/0518_Baxter app_2主畫面-14.png");
	// 	}, function() {
	// 		$('#TV').attr("src", "../resources/images/home/Movie.imageset/0518_Baxter app_2主畫面-13.png");
	// 	}
	// );
		
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

	if (introStateF%4 == 1) { //treatMethod
		$('#TV').attr("src", "../resources/images/home/Movie.imageset/a.png");
		$('#treatMethod').attr("src", "../resources/images/home/TreatIntroSelect.imageset/a.png");
		//audio here
		$('#nurseAudio').attr("src", '../resources/audio/nurse/ch/treat_tw.mp3');
		$('#nurseAudio').trigger('play');
		//speaking content
		$('#contentWord').text('想知道什麼是全自動腹膜透析(APD)治療嗎？這裡有詳盡的介紹！');
	}
	else if (introStateF%4 == 2) { //machine
		$('#treatMethod').attr("src", "../resources/images/home/TreatIntro.imageset/a.png");
		$('#machine').attr("src", "../resources/images/home/MachineIntroSelect.imageset/a.png");
		//audio here
		$('#nurseAudio').attr("src", '../resources/audio/nurse/ch/exterior_tw.mp3');
		$('#nurseAudio').trigger('play');
		//speaking content
		$('#contentWord').text('我們一起來看一下全自動腹膜透析(APD)機的外觀吧！');
	}
	else if (introStateF%4 == 3) { //operation
		$('#machine').attr("src", "../resources/images/home/MachineIntro.imageset/a.png");
		$('#operation').attr("src", "../resources/images/home/OperationHelpSelect.imageset/a.png");
		//audio here
		$('#nurseAudio').attr("src", '../resources/audio/nurse/ch/operate_tw.mp3');
		$('#nurseAudio').trigger('play');
		//speaking content
		$('#contentWord').text('該怎麼操作全自動腹膜透析(APD)治療呢？讓我們一起來了解與練習吧！');
	}
	else { //TV
		$('#operation').attr("src", "../resources/images/home/OperationHelp.imageset/a.png");
		$('#TV').attr("src", "../resources/images/home/MovieSelect.imageset/a.png");
		//audio here
		$('#nurseAudio').attr("src", '../resources/audio/nurse/ch/vedio_tw.mp3');
		$('#nurseAudio').trigger('play');
		//speaking content
		$('#contentWord').text('影片欣賞！');
	}

	introState = introState + 1;
}
