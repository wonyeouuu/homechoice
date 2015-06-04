//variables
var state = 1;
var offState = 1;
var goState = 1;
var nextState = 1;
var lastState = 1;
var restartState = 1;
var stopState = 1;
var downState = 1;
var enterState = 1;
var screenState = 1;
var screenState3 = 1;
var screenState4 = 1;
var go = 0;


$(document).ready(function(){
  mainTransfer();

  $('#btnPower').click(function() {
    if (state == 1) {
      clearInterval(intervalOff);
      $('#btnImgPower').attr("src", "../resources/images/operatorhelp/PowerOn.imageset/a.png");
      state = 1.5;
      mainTransfer();
    }
    else if ((state == 1.5) && (go == 1)) {
      clearInterval(intervalOff);
      $('#btnImgPower').attr("src", "../resources/images/operatorhelp/PowerOn.imageset/a.png");
      state = 3;
      mainTransfer();
    }

    else if (state == 2) {
      clearInterval(intervalOff);
      $('#btnImgPower').attr("src", "../resources/images/operatorhelp/PowerOn.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }    
  });

  $('#btnRestart').click(function() {
    location.reload();
  });  

  // $('#btnGo').click(function() {
  //   if (state == 9) {
  //     clearInterval(intervalGo);
  //     $('#btnImgGo').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelGo.imageset/a.png");
  //     state = state + 1;
  //     mainTransfer();
  //   }    
  // });
  
  $('#btnStop').click(function() {
    $('#beep').trigger('play');
    if (state == 4) {
      $('#APDWarning').trigger('pause');
      clearInterval(intervalStop);
      $('#btnImgStop').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelStop.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }
  });

  $('#btnEnter').click(function() {
    $('#beep').trigger('play');
    if (state == 9) {
      clearInterval(intervalEnter);
      $('#btnImgEnter').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelEnter.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }
  });

  $('#btnDown').click(function() {
    $('#beep').trigger('play');
    if (state == 5) {
      clearInterval(intervalDown);
      clearInterval(intervalScreen3);
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if (state == 6) {
      clearInterval(intervalDown);
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if (state == 7) {
      clearInterval(intervalDown);
      clearInterval(intervalScreen4);
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if (state == 8) {
      clearInterval(intervalDown);
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

  });

  $('#btnLastStep').click(function() {
    for (var i = 1; i < 999; i++) {
      window.clearInterval(i);
    }
    $('#btnImgRestart').attr('src', '../resources/images/operatorhelp/MENU.imageset/a.png');//restart modify   
    $('#btnImgGo').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelGo.imageset/a.png");
    $('#btnImgEnter').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelEnter.imageset/a.png");
    $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
    $('#btnImgStop').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelStop.imageset/a.png");
    $('.bars').hide();
    $('#APDWarning').trigger('pause');
    state = Math.max(1, state - 1);
    mainTransfer();
  });

});


var mainTransfer = function() {
  console.log('mainTransfer' + state);
  if (state == 1) {
    intervalOff = setInterval(function() {offTransfer2(offState)}, 400);//shining
    $('#screenContentWord').text('3/4留置');//screen text modify
    $('.bars').hide();
  }

  else if (state == 1.5) {
    intervalOff = setInterval(function() {offTransfer2(offState)}, 400);//shining
    $('#screenContentWord').text('');//screen text modify
    $('#contentWord').text('關閉電源等候10秒鐘');//text modify
    setTimeout(function() {
      clearInterval(intervalOff);
      $('#contentWord').text('開啟電源');//text modify
      $('#btnImgPower').attr("src", "../resources/images/operatorhelp/PowerOn.imageset/a.png");
      $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/turnon_en.mp3');
      $('.bars').hide();
      barControl(2);
      $('#nurseAudio').trigger('play');
      setTimeout(function() {
        go = 1;
      }, 1000);
    }, 4000);
    $('.bars').hide();
    barControl(1);

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/shutdown10_en.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 2) {
    $('#contentWord').text('開啟電源');//text modify
    $('#screenContentWord').text('');//screen text modify
    $('.bars').hide();
    barControl(2);

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/turnon_en.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 3) {
    $('#contentWord').text('重新開啟電源，隨即會發出聲音警訊。');//text modify
    $('#screenContentWord').text('請稍候...');//screen text modify
    $('.bars').hide();
    barControl(3);

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/turnon_warring_en.mp3');
    $('#nurseAudio').trigger('play');

    setTimeout(function() {
      state = state + 1;
      mainTransfer();
    }, 5000);
  }

  else if (state == 4) {
    intervalStop = setInterval(function() {stopTransfer2(stopState)}, 400);//shining
    $('#contentWord').text('按紅色鍵將警訊靜音。[電力恢復]會與您目前所在的階段週期交替顯示。');//text modify
    $('#screenContentWord').text('電力恢復');//screen text modify
    $('.bars').hide();
    barControl(4);

    $('#APDWarning').trigger('play');
    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/redmute_power_en.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 5) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('按向下鍵');//text modify
    intervalScreen3 = setInterval(function() {screenTransfer3(screenState3)}, 1000);
    $('.bars').hide();
    barControl(5);

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/downbutton_en.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 6) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('按向下鍵，目前治療的[0週期引流量]隨即顯示');//text modify
    $('#screenContentWord').html('<ul>0週期引流量：</ul><ul>65ML</ul>');//screen text modify
    $('.bars').hide();
    barControl(6);

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/downbutton_delete_en.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 7) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('按向下鍵');//text modify
    intervalScreen4 = setInterval(function() {screenTransfer4(screenState4)}, 1000);
    $('.bars').hide();
    barControl(7);

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/downbutton_en.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 8) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('按向下鍵顯示治療的平均實際留置時間');//text modify
    $('#screenContentWord').html('<ul>平均留置時間：</ul><ul>1:32</ul>');//text modify
    $('.bars').hide();
    barControl(8);

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/downbutton_time_en.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 9) {
    intervalEnter = setInterval(function() {enterTransfer2(enterState)}, 400);//shining
    $('#contentWord').text('按向下鍵');//text modify
    $('#screenContentWord').text('結束治療');//screen text modify
    $('.bars').hide();
    barControl(9);

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/downbutton_en.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 10) {
    $('#contentWord').text('按藍色鍵。繼續進行[結束治療]程序');//text modify
    $('#screenContentWord').text('關閉所有管夾');//screen text modify
    $('.bars').hide();
    barControl(10);

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/bluebutton_end_en.mp3');
    $('#nurseAudio').trigger('play');
  }
}

$.fn.multiline = function(text){
    this.text(text);
    this.html(this.html().replace(/\n/g,'<br/>'));
    return this;
}

var screenTransfer3 = function(stateF) {
  if (stateF%2 == 1) {
    $('#screenContentWord').text('3/4留置');//screen text modify
  }
  else {
    $('#screenContentWord').text('電力恢復');//screen text modify
  }

  screenState3 = screenState3 + 1;
}

var screenTransfer4 = function(stateF) {
  if (stateF%2 == 1) {
    $('#screenContentWord').html('<ul>總脫水量：</ul><ul>150ML</ul>');//screen text modify
  }
  else {
    $('#screenContentWord').html('<ul>當前超濾量：</ul><ul>50ML</ul>');//screen text modify
  }

  screenState4 = screenState4 + 1;
}

var offTransfer2 = function(stateF) {
  if (stateF%2 == 1) {
    $('#btnImgPower').attr("src", "../resources/images/operatorhelp/PowerOff.imageset/a.png");
  }
  else {
    $('#btnImgPower').attr("src", "../resources/images/operatorhelp/PowerOff_Select.imageset/a.png");
  }

  offState = offState + 1;
}

var goTransfer2 = function(stateF) {
  if (stateF%2 == 1) {
    $('#btnImgGo').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelGo.imageset/a.png");
  }
  else {
    $('#btnImgGo').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelGoSelect.imageset/a.png");
  }

  goState = goState + 1;
}

var lastTransfer2 = function(stateF) {
  if (stateF%2 == 1) {
    $('#btnImgLastStep').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/content_menu_back.imageset/a.png");
  }
  else {
    $('#btnImgLastStep').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/content_menu_back_select.imageset/a.png");
  }

  lastState = lastState + 1;
}

var nextTransfer2 = function(stateF) {
  if (stateF%2 == 1) {
    $('#btnImgNextStep').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/content_menu_next.imageset/a.png");
  }
  else {
    $('#btnImgNextStep').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/content_menu_next_select.imageset/a.png");
  }

  nextState = nextState + 1;
}

var restartTransfer2 = function(stateF) {
  if (stateF%2 == 1) {
    $('#btnImgRestart').attr('src', '../resources/images/operatorhelp/MENU.imageset/a.png');//restart modify   
  }
  else {
    $('#btnImgRestart').attr('src', '../resources/images/operatorhelp/MENU_Select.imageset/a.png');//restart modify   
  }

  restartState = restartState + 1;
}

var stopTransfer2 = function(stateF) {
  if (stateF%2 == 1) {
    $('#btnImgStop').attr('src', '../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelStop.imageset/a.png');//restart modify   
  }
  else {
    $('#btnImgStop').attr('src', '../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelStopSelect.imageset/a.png');//restart modify   
  }

  stopState = stopState + 1;
}

var downTransfer2 = function(stateF) {
  if (stateF%2 == 1) {
    $('#btnImgDown').attr('src', '../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png');//restart modify   
  }
  else {
    $('#btnImgDown').attr('src', '../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down_select.imageset/a.png');//restart modify   
  }

  downState = downState + 1;
}

var enterTransfer2 = function(stateF) {
  if (stateF%2 == 1) {
    $('#btnImgEnter').attr('src', '../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelEnter.imageset/a.png');//restart modify   
  }
  else {
    $('#btnImgEnter').attr('src', '../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelEnterSelect.imageset/a.png');//restart modify   
  }

  enterState = enterState + 1;
}

var screenTransfer2 = function(stateF) {
  if (stateF%2 == 1) {
    $('#screenContentWord').text('2/5引流');//text modify
  }
  else {
    $('#screenContentWord').text('引流量不足');//text modify
  }

  screenState = screenState + 1;
}

var barControl = function(numOfDot) {
  for (i = 1; i <= numOfDot; i++) {
    $('#bar' + i).show();
  }
}