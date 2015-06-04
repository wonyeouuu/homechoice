//variables
var nurseCurrentState = 1;
var introState = 1;
var play_video = true;

$(document).ready(function(){
  //nurse speaking (anonymous function!!!)
  setInterval(function() {nurseTransfer(nurseCurrentState)}, 500);
  //intro Transfer
  introTransfer(introState);
  setInterval(function() {introTransfer(introState)}, 17000);
    
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

  if (introStateF%4 == 1) { 
    $('#linkImg1').attr('src', '../resources/images/operatorhelp/prescriptsetmenu/bot01_select.imageset/a.png');
    $('#linkImg4').attr('src', '../resources/images/operatorhelp/prescriptsetmenu/bot04.imageset/a.png');
    //audio here
    $('#nurseAudio').attr("src", '../resources/audio/nurse/ch/Prescription_Chen.mp3');
    $('#nurseAudio').trigger('play');
    //speaking content
    $('#contentWord').text('如果晚上進行APD治療，且白天使用愛多尼爾，我們該如何設定處方呢？讓我們一起來了解與練習吧！');
  }
  else if (introStateF%4 == 2) { 
    $('#linkImg2').attr('src', '../resources/images/operatorhelp/prescriptsetmenu/bot02_select.imageset/a.png');
    $('#linkImg1').attr('src', '../resources/images/operatorhelp/prescriptsetmenu/bot01.imageset/a.png');
    //audio here
    $('#nurseAudio').attr("src", '../resources/audio/nurse/ch/Prescription_Lee.mp3');
    $('#nurseAudio').trigger('play');
    //speaking content
    $('#contentWord').text('如果晚上要進行APD治療，白天仍須使用一般的葡萄糖藥水，我們該如何設定處方呢？讓我們一起來了解與練習吧！');
  }
  else if (introStateF%4 == 3) { //operation
    $('#linkImg3').attr('src', '../resources/images/operatorhelp/prescriptsetmenu/bot03_select.imageset/a.png');
    $('#linkImg2').attr('src', '../resources/images/operatorhelp/prescriptsetmenu/bot02.imageset/a.png');
    //audio here
    $('#nurseAudio').attr("src", '../resources/audio/nurse/ch/Prescription_Wang.mp3');
    $('#nurseAudio').trigger('play');
    //speaking content
    $('#contentWord').text('如果晚上完成APD治療，但白天不須使用藥水，我們該如何設定處方呢？讓我們一起來了解與練習吧！');
  }
  else { //TV
    $('#linkImg4').attr('src', '../resources/images/operatorhelp/prescriptsetmenu/bot04_select.imageset/a.png');
    $('#linkImg3').attr('src', '../resources/images/operatorhelp/prescriptsetmenu/bot03.imageset/a.png');
    //audio here
    $('#nurseAudio').attr("src", '../resources/audio/nurse/ch/Prescription_Yeh.mp3');
    $('#nurseAudio').trigger('play');
    //speaking content
    $('#contentWord').text('如果需要使用TPD潮式治療，我們該如何設定處方呢？讓我們一起來了解與練習吧！');
  }

  introState = introState + 1;
}
