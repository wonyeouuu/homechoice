//variables
var nurseCurrentState = 1;
var introState = 1;
var play_video = true;
var jsonBuffer = {};
var opt = 4;//non select
var input = {};
var closeAbility = false;

$(document).ready(function(){

	$('#step2').hide();
	$('#step3').hide();
	$('.btn_closePop').hide();

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
	$('#popContainer').show();
	$('.btn_openPop').click(function(){
		var _popItem = $(this).find('img'),
			_popSrc = _popItem.attr('src'),
			_popImg = $('.popContent').find('img');
		_popImg.attr('src',_popSrc);
		$('#popContainer').fadeIn();
	});
	$('.btn_closePop').click(function() {
			$('#popContainer').fadeOut();	
			//nurse speaking (anonymous function!!!)
		   	setInterval(function() {nurseTransfer(nurseCurrentState)}, 500);
			//intro Transfer
		   	introTransfer(introState);
			setInterval(function() {introTransfer(introState)}, 14000);
	});

	$("#btnSubmit").click(function() {
		if ($('#inputEmail').val().length === 0) {
			alert('註冊或登入信箱不得留白！');
		}
		else {
			input = {};
			input.email = $('#inputEmail').val();
			$.post("http://atc-event.com/homechoice/api/member/getmemberbyemail", input,
	        function(data,status){
	        	if (status == "success") {
	        		jsonBuffer = JSON.parse(data);
	        		if (jsonBuffer['success'] == 1) {//already exist
	        			alert('登入成功！如果有空幫忙填個問卷吧！');
	        			$('#step1').hide();
	        			$('#step3').show();
	        			$('.btn_closePop').show();
	        		}	
	        		else {//new user
	        			console.log('哥是新加入低～～～');
	        			$('#eMail').html(input.email);
	        			$('#step1').hide();
	        			$('#step2').show();
	        		}
	        	}
	        	else {
	        		console.log("API BOMB!!");
	        	}
	            
	        });
		}
		
    });

    $("#btnRegister").click(function() {
    	if (opt == 4) {
    		alert("請先選擇您的身份再進行註冊這個動作！");
    	}
    	else {
    		var inputR = {};
			inputR.email = input.email;
			inputR.capacity = opt;
			$.post("http://atc-event.com/homechoice/api/member/addmember", inputR,
	        function(data,status) {
	        	if (status == "success") {
	        		alert("恭喜您註冊成功！如果有空的話，幫忙填個問卷吧！");
	        		$('#step2').hide();
	        		$('#step3').show();
	        		$('.btn_closePop').show();
	        	}
	        	else {
	        		alert("API BOMB!!");
	        	}
	            
	        });
    	}
    });

	$(".qList").click(function() {
    	window.open("https://zh.surveymonkey.com", '_blank');
    });    

    $("#opt1").click(function() {
    	$(this).addClass("selected");
    	$("#opt2").removeClass("selected");
    	$("#opt3").removeClass("selected");
    	opt = 1;
    });
    $("#opt2").click(function() {
    	$(this).addClass("selected");
    	$("#opt1").removeClass("selected");
    	$("#opt3").removeClass("selected");
    	opt = 2;
    });
    $("#opt3").click(function() {
    	$(this).addClass("selected");
    	$("#opt1").removeClass("selected");
    	$("#opt2").removeClass("selected");
    	opt = 3;
    });

    $("#niao").click(function() {
        $.post("http://atc-event.com/homechoice/api/member/addmember",
        {
          email: "ws6604@hotmail.com",
          capacity: "1"
        },
        function(data,status){
            console.log(data);
            json = JSON.parse(data);
            console.log(json);
        });
    });
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
