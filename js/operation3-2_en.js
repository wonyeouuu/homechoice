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
      clearInterval(intervalStop);
      $('#btnImgStop').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelStop.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }
  });

  $('#btnEnter').click(function() {
    $('#beep').trigger('play');
    if (state == 8) {
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

    else if (state == 7) {
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
    $('.bars').hide();
    state = Math.max(1, state - 1);
    mainTransfer();
  });

});


var mainTransfer = function() {
  console.log('mainTransfer' + state);
  if (state == 1) {
    intervalStop = setInterval(function() {stopTransfer2(stopState)}, 400);//shining
    $('#contentWord').text('按紅色鍵');//text modify
    $('#screenContentWord').text('3/5注入');//screen text modify
    $('.bars').hide();

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/redbutton_en.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 2) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('按向下鍵');//text modify
    $('#screenContentWord').text('已停止：注入');//screen text modify
    $('.bars').hide();
    barControl(1);

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/downbutton_en.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 3) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('按向下鍵');//text modify
    $('#screenContentWord').html('<ul>注入量：</ul><ul>1250ML</ul>');//screen text modify
    $('.bars').hide();
    barControl(2);

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/downbutton_en.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 4) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('按向下鍵');//text modify
    $('#screenContentWord').html('<ul>總脫水量：</ul><ul>450ML</ul>');//screen text modify
    $('.bars').hide();
    barControl(3);

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/downbutton_en.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 5) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('按向下鍵');//text modify
    $('#screenContentWord').text('略過');//screen text modify
    $('.bars').hide();
    barControl(4);

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/downbutton_en.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 6) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('按向下鍵');//text modify
    $('#screenContentWord').text('更改程式');//screen text modify
    $('.bars').hide();
    barControl(5);

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/downbutton_en.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 7) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('按向下鍵');//text modify
    $('#screenContentWord').text('調整選項');//screen text modify
    $('.bars').hide();
    barControl(6);

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/downbutton_en.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 8) {
    intervalEnter = setInterval(function() {enterTransfer2(enterState)}, 400);//shining
    $('#contentWord').multiline('按藍色鍵\n[引流量]會顯示在顯示螢幕上。系統將會繼續引流，直到偵測不到流速為止。');//text modify
    $('#screenContentWord').text('手控引流');//text modify
    $('.bars').hide();
    barControl(7);

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/bluebutton_drainage_en.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 9) {
    intervalGo = setInterval(function() {goTransfer2(goState)}, 400);//shining
    $('#contentWord').text('按綠色鍵繼續治療');//text modify
    $('#screenContentWord').html('<ul>引流：</ul><ul>50ML</ul>');//text modify
    setTimeout(function() {
      $('#screenContentWord').html('<ul>引流：</ul><ul>100ML</ul>');//text modify
      setTimeout(function() {
        $('#screenContentWord').html('<ul>引流：</ul><ul>150ML</ul>');//text modify
        setTimeout(function() {
          $('#screenContentWord').html('<ul>引流：</ul><ul>200ML</ul>');//text modify
        }, 400)
      }, 400);
    }, 400);
    $('.bars').hide();
    barControl(8);

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/greencontinued_en.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 10) {
    $('#contentWord').multiline('引流的體液量會透過下列方式紀錄：\n-如果手控引流發生在病人接受最末袋注入後，引流量就會紀錄為[最末手控引流：ML]，並且會在您的下一個治療開始時在選單中顯示。\n-在其餘手控引流期間引流量會包括在治療總脫水量的一部分。');//text modify
    $('#screenContentWord').text('4/5注入');//text modify
    $('.bars').hide();
    barControl(8);

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/recordLiquid_en.mp3');
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

var barControl = function(numOfDot) {
  for (i = 1; i <= numOfDot; i++) {
    $('#bar' + i).show();
  }
}