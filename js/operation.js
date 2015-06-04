//variables
var introState = 1;
var nurseCurrentState = 1;

$(document).ready(function(){
  //nurse speaking (anonymous function!!!)
  setInterval(function() {nurseTransfer(nurseCurrentState)}, 500);
  //intro state transfer
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

  if (introStateF%4 == 1) { //1
    $('#btnImg1').attr("src", "../resources/images/operatorhelp/PrescriptionSettingSelect.imageset/a.png");
    $('#btnImg4').attr("src", "../resources/images/operatorhelp/TreatOver.imageset/a.png");
    //audio here
    $('#nurseAudio').attr("src", '../resources/audio/nurse/ch/setting.mp3');
    $('#nurseAudio').trigger('play');
    //speaking content
    $('#contentWord').text('關於APD治療的處方設定及練習讓我們一起來了解吧！');
  }
  else if (introStateF%4 == 2) { //2
    $('#btnImg2').attr("src", "../resources/images/operatorhelp/TreatPrepareSelect.imageset/a.png");
    $('#btnImg1').attr("src", "../resources/images/operatorhelp/PrescriptionSetting.imageset/a.png");
    //audio here
    $('#nurseAudio').attr("src", '../resources/audio/nurse/ch/prepare.mp3');
    $('#nurseAudio').trigger('play');
    //speaking content
    $('#contentWord').text('處方完成設定後，在進行治療之前，我們還要準備什麼呢？讓我們一起來了解吧！');
  }
  else if (introStateF%4 == 3) { //3
    $('#btnImg3').attr("src", "../resources/images/operatorhelp/TreatProblemSelect.imageset/a.png");
    $('#btnImg2').attr("src", "../resources/images/operatorhelp/TreatPrepare.imageset/a.png");
    //audio here
    $('#nurseAudio').attr("src", '../resources/audio/nurse/ch/solve.mp3');
    $('#nurseAudio').trigger('play');
    //speaking content
    $('#contentWord').text('治療中發生問題的時候，要如何操作“略過”、“強制排液”或是“提前結束治療”呢？讓我們一起來了解吧！');
  }
  else { //4
    $('#btnImg4').attr("src", "../resources/images/operatorhelp/TreatOverSelect.imageset/a.png");
    $('#btnImg3').attr("src", "../resources/images/operatorhelp/TreatProblem.imageset/a.png");
    //audio here
    $('#nurseAudio').attr("src", '../resources/audio/nurse/ch/treatover.mp3');
    $('#nurseAudio').trigger('play');
    //speaking content
    $('#contentWord').text('治療結束後，如何關閉APD電源完成下機呢？讓我們一起來了解吧！');
  }

  introState = introState + 1;
}
