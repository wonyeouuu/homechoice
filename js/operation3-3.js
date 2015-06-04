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


$(document).ready(function(){
  mainTransfer();

  // $('#btnPower').click(function() {
  //   if (state == 1) {
  //     clearInterval(intervalOff);
  //     $('#btnImgPower').attr("src", "../resources/images/operatorhelp/PowerOn.imageset/a.png");
  //     state = state + 1;
  //     mainTransfer();
  //   }
  // });

  $('#btnRestart').click(function() {
    for (var i = 1; i < 999; i++) {
      window.clearInterval(i);
    }
    $('#btnImgRestart').attr('src', '../resources/images/operatorhelp/MENU.imageset/a.png');//restart modify   
    $('#btnImgGo').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelGo.imageset/a.png");
    $('#btnImgEnter').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelEnter.imageset/a.png");
    $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
    $('#btnImgStop').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelStop.imageset/a.png");
    $('.bars').hide();
    state = 1;
    mainTransfer();
  });  

  $('#btnGo').click(function() {
    $('#beep').trigger('play');
    if (state == 9) {
      clearInterval(intervalGo);
      $('#btnImgGo').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelGo.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }    
  });
  
  $('#btnStop').click(function() {
    $('#beep').trigger('play');
    if (state == 1) {
      $('#APDWarning').trigger('pause');
      clearInterval(intervalStop);
      clearInterval(intervalScreen);
      $('#btnImgStop').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelStop.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if (state == 8) {
      $('#APDWarning').trigger('pause');
      clearInterval(intervalStop);
      $('#btnImgStop').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelStop.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }
  });

  $('#btnEnter').click(function() {
    $('#beep').trigger('play');
    if (state == 7) {
      clearInterval(intervalEnter);
      $('#btnImgEnter').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelEnter.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if (state == 11) {
      clearInterval(intervalEnter);
      $('#btnImgEnter').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelEnter.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }
  });

  $('#btnDown').click(function() {
    $('#beep').trigger('play');
    if (state == 2) {
      clearInterval(intervalDown);
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if (state == 3) {
      clearInterval(intervalDown);
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if (state == 4) {
      clearInterval(intervalDown);
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if (state == 5) {
      clearInterval(intervalDown);
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

    else if (state == 9) {
      clearInterval(intervalDown);
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if (state == 10) {
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
    intervalStop = setInterval(function() {stopTransfer2(stopState)}, 400);//shining
    $('#screenContentWord').text('2/5引流');//screen text modify
    intervalScreen = setInterval(function() {screenTransfer2(screenState)}, 800);//shining
    $('#contentWord').text('按紅色鍵');//text modify
    $('.bars').hide();

    $('#APDWarning').trigger('play');
    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/redbutton.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 2) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('按向下鍵直到螢幕顯示[略過]');//text modify
    $('#screenContentWord').text('已停止：引流');//screen text modify
    $('.bars').hide();
    barControl(1);

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/downbutton_skip.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 3) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('按向下鍵直到螢幕顯示[略過]');//text modify
    $('#screenContentWord').html('<ul>引流量：</ul><ul>1650ML</ul>');//screen text modify
    $('.bars').hide();
    barControl(2);
  }

  else if (state == 4) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('按向下鍵直到螢幕顯示[略過]');//text modify
    $('#screenContentWord').html('<ul>最小引流量：</ul><ul>1700ML</ul>');//screen text modify
    $('.bars').hide();
    barControl(2);
  }

  else if (state == 5) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('按向下鍵直到螢幕顯示[略過]');//text modify
    $('#screenContentWord').html('<ul>當前超濾量：</ul><ul>-100ML</ul>');//screen text modify
    $('.bars').hide();
    barControl(2);
  }

  else if (state == 6) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('按向下鍵直到螢幕顯示[略過]');//text modify
    $('#screenContentWord').text('引流尚未完成');//screen text modify
    $('.bars').hide();
    barControl(2);
  }

  else if (state == 7) {
    intervalEnter = setInterval(function() {enterTransfer2(enterState)}, 400);//shining
    $('#contentWord').text('按藍色鍵隨即發出警訊');//text modify
    $('#screenContentWord').text('略過');//screen text modify
    $('.bars').hide();
    barControl(3);
    
    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/bluebutton_warring.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 8) {
    intervalStop = setInterval(function() {stopTransfer2(stopState)}, 400);//shining
    $('#contentWord').multiline('按紅色鍵將警訊靜音\n如果此時您不想執行[略過]，按綠色鍵繼續引流;如果您仍要執行[略過]，請參照下列步驟。');//text modify
    $('#screenContentWord').text('引流尚未完成');//text modify
    $('.bars').hide();
    barControl(4);

    $('#APDWarning').trigger('play');
    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/redmute_skip.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 9) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('按向下鍵直到螢幕顯示[略過]');//text modify
    $('#screenContentWord').html('<ul>引流量：</ul><ul>1800ML</ul>');//screen text modify
    $('.bars').hide();
    barControl(4);

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/downbutton_skip.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 10) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('按向下鍵直到螢幕顯示[略過]');//text modify
    $('#screenContentWord').html('<ul>總脫水量：</ul><ul>450ML</ul>');//screen text modify
    $('.bars').hide();
    barControl(4);
  }

  else if (state == 11) {
    intervalEnter = setInterval(function() {enterTransfer2(enterState)}, 400);//shining
    $('#contentWord').text('按藍色鍵下一個注入將會開始');//text modify
    $('#screenContentWord').text('略過');//screen text modify
    $('.bars').hide();
    barControl(5);

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/bluebutton_next.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 12) {
    $('#contentWord').multiline('下一個注入將會開始\n注意：在引流期時，如果引流尚未完成即執行略過，機器會在下個注入期自動減少注入量。\n(下次注入量 = 原本設定注入量 - 未被排出的引流液量)');//text modify
    $('#screenContentWord').text('3/5注入');//screen text modify
    $('.bars').hide();
    barControl(6);

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/next.mp3');
    $('#nurseAudio').trigger('play');
  }




}

$.fn.multiline = function(text){
    this.text(text);
    this.html(this.html().replace(/\n/g,'<br/>'));
    return this;
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