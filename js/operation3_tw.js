//variables
var nurseCurrentState = 1;
var introState = 1;
var play_video = true;

$(document).ready(function(){
  //nurse speaking (anonymous function!!!)
  setInterval(function() {nurseTransfer(nurseCurrentState)}, 500);
  //intro Transfer
  introTransfer(introState);
  setInterval(function() {introTransfer(introState)}, 14000);
    
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
    $('#selectBall').css('top', '27%');
    //audio here
    $('#nurseAudio').attr("src", '../resources/audio/nurse/ch/warring_tw.mp3');
    $('#nurseAudio').trigger('play');
    //speaking content
    $('#contentWord').text('操作機器時若顯示機器警訊，應該如何處理呢？這裡有詳細的說明。');
  }
  else if (introStateF%4 == 2) { 
    $('#selectBall').css('top', '44%');
    //audio here
    $('#nurseAudio').attr("src", '../resources/audio/nurse/ch/ManualFlowMethod_tw.mp3');
    $('#nurseAudio').trigger('play');
    //speaking content
    $('#contentWord').text('手控引流可在注入期、留置期、引流期或結束治療時使用');
  }
  else if (introStateF%4 == 3) { //operation
    $('#selectBall').css('top', '61%');
    //audio here
    $('#nurseAudio').attr("src", '../resources/audio/nurse/ch/skip_tw.mp3');
    $('#nurseAudio').trigger('play');
    //speaking content
    $('#contentWord').text('略過引流階段的操作方式。請與您的透析中心確定執行略過的安全性。');
  }
  else { //TV
    $('#selectBall').css('top', '78%');
    //audio here
    $('#nurseAudio').attr("src", '../resources/audio/nurse/ch/ForceOverMethod_tw.mp3');
    $('#nurseAudio').trigger('play');
    //speaking content
    $('#contentWord').text('當停電(30min-2hr)或其他原因導致下機時間延誤，若您必須提前結束治療，可參考此執行方式，但請與您的透析中心確定“提前結束治療”的安全性。');
  }

  introState = introState + 1;
}
