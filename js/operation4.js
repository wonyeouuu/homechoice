//variables
var state = 1;
var offState = 1;
var goState = 1;
var nextState = 1;
var lastState = 1;
var restartState = 1;
var downState = 1;

$(document).ready(function(){
  mainTransfer();

  // $('#btnPower').click(function() {
  //   if (state == 1) {
  //     clearInterval(intervalOff);
  //     $('#btnImgPower').attr("src", "../resources/images/operatorhelp/PowerOn.imageset/0518_Baxter app_操作介紹及練習_1處方設定練習-11.png");
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
    $('#btnImgNextStep').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/content_menu_next.imageset/a.png");
    state = 1;
    mainTransfer();
  });  

  $('#btnGo').click(function() {
    $('#beep').trigger('play');
    if (state == 1) {
      clearInterval(intervalGo);
      $('#btnImgGo').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelGo.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }
    else if (state == 2) {
      clearInterval(intervalGo);
      $('#btnImgGo').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelGo.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }
    else if(state == 8) {
      clearInterval(intervalGo);
      $('#btnImgGo').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelGo.imageset/a.png");
      $('#introImg').show();//introImg modify
      state = state + 1;
      mainTransfer();
    }
  });

  $('#btnDown').click(function() {
    $('#beep').trigger('play');
    if (state == 9) {
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

    else if (state == 11) {
      clearInterval(intervalDown);
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if (state == 12) {
      clearInterval(intervalDown);
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }
    else if (state == 13) {
      clearInterval(intervalDown);
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }
  });
  
  $('#btnNextStep').click(function() {
    if (state == 3) {
      clearInterval(intervalNext);
      $('#btnImgNextStep').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/content_menu_next.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }
    else if(state == 4) {
      clearInterval(intervalNext);
      $('#btnImgNextStep').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/content_menu_next.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }
    else if(state == 5) {
      clearInterval(intervalNext);
      $('#btnImgNextStep').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/content_menu_next.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }
    else if(state == 6) {
      clearInterval(intervalNext);
      $('#btnImgNextStep').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/content_menu_next.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }
    else if(state == 7) {
      clearInterval(intervalNext);
      $('#btnImgNextStep').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/content_menu_next.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }
  });
  $('#btnLastStep').click(function() {
    for (var i = 1; i < 999; i++) {
      window.clearInterval(i);
    }
    $('#btnImgGo').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelGo.imageset/a.png");
    $('#btnImgNextStep').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/content_menu_next.imageset/a.png");
    state = Math.max(1, state - 1);
    mainTransfer();
  });

});

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

var downTransfer2 = function(stateF) {
  if (stateF%2 == 1) {
    $('#btnImgDown').attr('src', '../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png');//restart modify   
  }
  else {
    $('#btnImgDown').attr('src', '../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down_select.imageset/a.png');//restart modify   
  }

  downState = downState + 1;
}

var mainTransfer = function() {
  console.log('mainTransfer' + state);
  if (state == 1) {
    intervalGo = setInterval(function() {goTransfer2(goState)}, 400);//shining
    $('#contentWord').text('請按綠色鍵');//text modify
    $('#screenContentWord').text('治療完成');//screen text modify
    $('#introImg').hide();//introImg modify
    $('.ps').css('color', 'black');
    $('#p1').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/greenbutton.mp3');
    $('#nurseAudio').trigger('play');

  }

  else if (state == 2) {
    intervalGo = setInterval(function() {goTransfer2(goState)}, 400);//shining
    $('#contentWord').text('關閉迷你輸液開關與所有管夾。');//text modify
    $('#screenContentWord').text('關閉所有管夾');//text modify
    $('#introImg').show();//introImg modify
    $('#introImg').attr('src', '../resources/images/operatorhelp/treatover/TreatOver1.imageset/a.png');//introImg modify
    $('.ps').css('color', 'black');
    $('#p2').css('color', 'red');    

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/minicloseall.mp3');
    $('#nurseAudio').trigger('play');   
  }

  else if (state == 3) {
    intervalNext = setInterval(function() {nextTransfer2(nextState)}, 400);//shining
    $('#contentWord').text('戴上口罩及徹底清理雙手。');//text modify
    $('#screenContentWord').text('分離管組與自己');//text modify
    $('#introImg').hide();//introImg modify
    $('.ps').css('color', 'black');
    $('#p3').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/clean.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 4) {
    intervalNext = setInterval(function() {nextTransfer2(nextState)}, 400);//shining
    $('#contentWord').text('準備一包新的小白帽，檢查有效期限，打開後再小心檢查小白帽內之優碘藥水是否濕潤。');//text modify
    $('#screenContentWord').text('分離管組與自己');//text modify
    $('#introImg').show();//introImg modify
    $('#introImg').attr('src', '../resources/images/operatorhelp/treatover/TreatOver2.imageset/a.png');//introImg modify
    $('.ps').css('color', 'black');
    $('#p3').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/preparecheck.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 5) {
    intervalNext = setInterval(function() {nextTransfer2(nextState)}, 400);//shining
    $('#contentWord').text('分離迷你輸液管及病人端管路，並立即以無菌技術將小白帽安裝到迷你輸液管上。');//text modify
    $('#screenContentWord').text('分離管組與自己');//text modify
    $('#introImg').show();//introImg modify
    $('#introImg').attr('src', '../resources/images/operatorhelp/treatover/TreatOver3.imageset/a.png');//introImg modify
    $('.ps').css('color', 'black');
    $('#p3').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/separate.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 6) {
    intervalNext = setInterval(function() {nextTransfer2(nextState)}, 400);//shining
    $('#contentWord').text('分離迷你輸液管及病人端管路，並立即以無菌技術將小白帽安裝到迷你輸液管上。');//text modify
    $('#screenContentWord').text('分離管組與自己');//text modify
    $('#introImg').show();//introImg modify
    $('#introImg').attr('src', '../resources/images/operatorhelp/treatover/TreatOver4.imageset/a.png');//introImg modify
    $('.ps').css('color', 'black');
    $('#p3').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/separate.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 7) {
    intervalNext = setInterval(function() {nextTransfer2(nextState)}, 400);//shining
    $('#contentWord').text('將管組門打開取出管組，丟棄剩餘之透析液以及管組。');//text modify
    $('#screenContentWord').text('分離管組與自己');//text modify
    $('#introImg').show();//introImg modify
    $('#introImg').attr('src', '../resources/images/operatorhelp/treatover/TreatOver5.imageset/a.png');//introImg modify
    $('.ps').css('color', 'black');
    $('#p3').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/abandon.mp3');
    $('#nurseAudio').trigger('play');
  }
  else if (state == 8) {
    intervalGo = setInterval(function() {goTransfer2(goState)}, 400);//shining
    $('#contentWord').text('將管組門打開取出管組，丟棄剩餘之透析液以及管組。');//text modify
    $('#screenContentWord').text('分離管組與自己');//text modify
    $('#introImg').show();//introImg modify
    $('#introImg').attr('src', '../resources/images/operatorhelp/treatover/TreatOver6.imageset/a.png');//introImg modify
    $('.ps').css('color', 'black');
    $('#p3').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/abandon.mp3');
    $('#nurseAudio').trigger('play');
  }
  else if (state == 9) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('此時可將電源關閉。');//text modify
    $('#screenContentWord').text('關機');//text modify
    $('#introImg').hide();//introImg modify
    $('.ps').css('color', 'black');
    $('#p4').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/turnoff.mp3');
    $('#nurseAudio').trigger('play');
  }
  else if (state == 10) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('再按向下鍵紀錄總脫水量');//text modify
    $('#screenContentWord').text('0週期引流                                     2115ML');//text modify
    $('#introImg').hide();//introImg modify
    $('.ps').css('color', 'black');
    $('#p5').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/recordwater.mp3');
    $('#nurseAudio').trigger('play');
  }
  else if (state == 11) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('再按向下鍵紀錄平均留置時間');//text modify
    $('#screenContentWord').text('總脫水量                                     894ML');//text modify
    $('#introImg').hide();//introImg modify
    $('.ps').css('color', 'black');
    $('#p6').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/recordtime.mp3');
    $('#nurseAudio').trigger('play');
  }
  else if (state == 12) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('再按向下鍵紀錄平均留置時間');//text modify
    $('#screenContentWord').text('平均留置時間                                     1:17');//text modify
    $('#introImg').hide();//introImg modify
    $('.ps').css('color', 'black');
    $('#p7').css('color', 'red');

  }
  else if (state == 13) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('再按向下鍵紀錄平均留置時間');//text modify
    $('#screenContentWord').text('留置時間減少                                     0:00');//text modify
    $('#introImg').hide();//introImg modify
    $('.ps').css('color', 'black');
    $('#p7').css('color', 'red');
  }
  else if (state == 14) {
    $('#contentWord').text('此時可將電源關閉');//text modify
    $('#screenContentWord').text('警訊紀錄');//text modify
    $('#introImg').show();//introImg modify
    $('#introImg').attr('src', '../resources/images/operatorhelp/treatbeforeprepare/TBP_Snap_Home.imageset/a.png');//introImg modify
    $('.ps').css('color', 'black');
    $('#p7').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/turnoff.mp3');
    $('#nurseAudio').trigger('play');
  }
}

$.fn.multiline = function(text){
    this.text(text);
    this.html(this.html().replace(/\n/g,'<br/>'));
    return this;
}