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
var upState = 1;
var screenState = 1;
var treatValue = 8000;
var time1 = 7;
var time2 = 50;
var insertValue = 1500;
var lastValue = 700;
var lastGrape = "相同";
var weight = "磅";
var weightValue = 55;
var TPD = 65;
var totalWater = 20;
var inliu = 2;

$(document).ready(function(){
  mainTransfer();

  $('#btnStop').click(function() {
    if (state == 26) {
      clearInterval(intervalStop);
      $('#btnImgStop').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelStop.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }
  });

  $('#btnPower').click(function() {
    if (state == 1) {
      clearInterval(intervalOff);
      $('#btnImgPower').attr("src", "../resources/images/operatorhelp/PowerOn.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }
  });

  $('#btnRestart').click(function() {
    console.log('niaoo');
    for (var i = 1; i < 999; i++) {
      window.clearInterval(i);
    }
    treatValue = 8000;
    time1 = 7;
    time2 = 50;
    insertValue = 1500;
    lastValue = 700;
    lastGrape = "相同";
    weight = "磅";
    weightValue = 55;
    TPD = 65;
    totalWater = 20;
    inliu = 2;
    $('#btnImgRestart').attr('src', '../resources/images/operatorhelp/MENU.imageset/a.png');//restart modify   
    $('#btnImgGo').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelGo.imageset/a.png");
    $('#btnImgNextStep').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/content_menu_next.imageset/a.png");
    $('#btnImgStop').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelStop.imageset/a.png");
    state = 1;
    mainTransfer();
  });  

  $('#btnGo').click(function() {
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

  $('#btnEnter').click(function() {
    if (state == 5) {
      clearInterval(intervalEnter);
      $('#btnImgEnter').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelEnter.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if ((state == 6) && (treatValue == 9000)) {
      clearInterval(intervalDown);
      clearInterval(intervalUp);
      clearInterval(intervalScreen);
      $('#screenSpan').css('opacity', '1');
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      $('#btnImgUp').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_up.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if (state == 8) {
      clearInterval(intervalEnter);
      $('#btnImgEnter').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelEnter.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if ((state == 9) && (time1 == 8) && (time2 == 0)) {
      clearInterval(intervalDown);
      clearInterval(intervalUp);
      clearInterval(intervalScreen);
      $('#screenSpan').css('opacity', '1');
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      $('#btnImgUp').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_up.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if (state == 11) {
      clearInterval(intervalEnter);
      $('#btnImgEnter').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelEnter.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if ((state == 12) && (insertValue == 2000)) {
      clearInterval(intervalDown);
      clearInterval(intervalUp);
      clearInterval(intervalScreen);
      $('#screenSpan').css('opacity', '1');
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      $('#btnImgUp').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_up.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if (state == 14) {
      clearInterval(intervalEnter);
      $('#btnImgEnter').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelEnter.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if ((state == 15) && (lastValue == 1000)) {
      clearInterval(intervalDown);
      clearInterval(intervalUp);
      clearInterval(intervalScreen);
      $('#screenSpan').css('opacity', '1');
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      $('#btnImgUp').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_up.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if (state == 17) {
      clearInterval(intervalEnter);
      $('#btnImgEnter').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelEnter.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if ((state == 18) && (lastGrape == "不同")) {
      clearInterval(intervalDown);
      clearInterval(intervalUp);
      clearInterval(intervalScreen);
      $('#screenSpan').css('opacity', '1');
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      $('#btnImgUp').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_up.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if (state == 20) {
      clearInterval(intervalEnter);
      $('#btnImgEnter').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelEnter.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if ((state == 21) && (weight == "公斤")) {
      clearInterval(intervalDown);
      clearInterval(intervalUp);
      clearInterval(intervalScreen);
      $('#screenSpan').css('opacity', '1');
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      $('#btnImgUp').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_up.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if (state == 23) {
      clearInterval(intervalEnter);
      $('#btnImgEnter').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelEnter.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if ((state == 24) && (weightValue == 50)) {
      clearInterval(intervalDown);
      clearInterval(intervalUp);
      clearInterval(intervalScreen);
      $('#screenSpan').css('opacity', '1');
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      $('#btnImgUp').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_up.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if (state == 28) {
      clearInterval(intervalEnter);
      $('#btnImgEnter').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelEnter.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if ((state == 29) && (TPD == 70)) {
      clearInterval(intervalDown);
      clearInterval(intervalUp);
      clearInterval(intervalScreen);
      $('#screenSpan').css('opacity', '1');
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      $('#btnImgUp').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_up.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if (state == 31) {
      clearInterval(intervalEnter);
      $('#btnImgEnter').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelEnter.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if ((state == 32) && (totalWater == 10)) {
      clearInterval(intervalDown);
      clearInterval(intervalUp);
      clearInterval(intervalScreen);
      $('#screenSpan').css('opacity', '1');
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      $('#btnImgUp').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_up.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if (state == 34) {
      clearInterval(intervalEnter);
      $('#btnImgEnter').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelEnter.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if ((state == 35) && (inliu == 3)) {
      clearInterval(intervalDown);
      clearInterval(intervalUp);
      clearInterval(intervalScreen);
      $('#screenSpan').css('opacity', '1');
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      $('#btnImgUp').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_up.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }
  });

  $('#btnUp').click(function() {
    if (state == 6) {
      treatValue = treatValue + 500;
      $('#screenContentValue1').text(treatValue + 'ML');//text modify
    }

    else if (state == 9) { //modifying
      time2 = time2 + 5;
      if (time2 >= 60) {
        time1 = time1 + 1;
        time2 = 0;
      }
      $('#screenContentValue1').text(time1);//text modify
      $('#screenContentValue2').text(time2);//text modify
      if (time2 == 0) {
        $('#screenContentValue2').text('00');//text modify
      }

      else if (time2 == 5) {
        $('#screenContentValue2').text('05');//text modify
      }
    }  

    else if (state == 12) { //modifying
      insertValue = insertValue + 100;
      $('#screenContentValue1').text(insertValue + 'ML');//text modify
    }  

    else if (state == 15) { //modifying
      lastValue = lastValue + 100;
      $('#screenContentValue1').text(lastValue + 'ML');//text modify
    }  

    else if (state == 18) { //modifying
      if (lastGrape == "不同") {
        lastGrape = "相同";
        $('#screenContentValue1').text(lastGrape);//text modify
      }
      else {
       lastGrape = "不同";
        $('#screenContentValue1').text(lastGrape);//text modify 
      }
    } 

    else if (state == 21) { //modifying
      if (weight == "公斤") {
        weight = "磅";
        $('#screenContentValue1').text(weight);//text modify
      }
      else {
        weight = "公斤";
        $('#screenContentValue1').text(weight);//text modify 
      }
    }

    else if (state == 24) { //modifying
      weightValue = weightValue + 1;
      $('#screenContentValue1').text(weightValue + '公斤');//text modify
    } 

    else if (state == 29) { //modifying
      TPD = TPD + 1;
      $('#screenContentValue1').text(TPD + '%');//text modify
    }

    else if (state == 32) { //modifying
      totalWater = totalWater + 5;
      $('#screenContentValue1').text(totalWater + 'ML');//text modify
    }

    else if (state == 35) { //modifying
      inliu = inliu + 1;
      $('#screenContentValue1').text(inliu + '週期');//text modify
    }
  });

  $('#btnDown').click(function() {
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

    else if (state == 6) { //modifying
      treatValue = treatValue - 500;
      $('#screenContentValue1').text(treatValue + 'ML');//text modify
    }

    else if (state == 7) {
      clearInterval(intervalDown);
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if (state == 9) { //modifying
      time2 = time2 - 5;
      if (time2 < 0) {
        time1 = time1 - 1;
        time2 = 55;
      }
      $('#screenContentValue1').text(time1);//text modify
      $('#screenContentValue2').text(time2);//text modify
      if (time2 == 0) {
        $('#screenContentValue2').text('00');//text modify
      }

      else if (time2 == 5) {
        $('#screenContentValue2').text('05');//text modify
      }
    }

    else if (state == 10) {
      clearInterval(intervalDown);
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if (state == 12) { //modifying
      insertValue = insertValue - 100;
      $('#screenContentValue1').text(insertValue + 'ML');//text modify
    }

    else if (state == 13) {
      clearInterval(intervalDown);
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      state = 28;
      mainTransfer();
    }

    else if (state == 15) { //modifying
      lastValue = lastValue - 100;
      $('#screenContentValue1').text(lastValue + 'ML');//text modify
    } 

    else if (state == 16) {
      clearInterval(intervalDown);
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if (state == 18) { //modifying
      if (lastGrape == "不同") {
        lastGrape = "相同";
        $('#screenContentValue1').text(lastGrape);//text modify
      }
      else {
       lastGrape = "不同";
        $('#screenContentValue1').text(lastGrape);//text modify 
      }
    } 
    else if (state == 19) {
      clearInterval(intervalDown);
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      state = 34;
      mainTransfer();
    }

    else if (state == 21) { //modifying
      if (weight == "公斤") {
        weight = "磅";
        $('#screenContentValue1').text(weight);//text modify
      }
      else {
        weight = "公斤";
        $('#screenContentValue1').text(weight);//text modify 
      }
    } 

    else if (state == 22) {
      clearInterval(intervalDown);
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if (state == 24) { //modifying
      weightValue = weightValue - 1;
      $('#screenContentValue1').text(weightValue + '公斤');//text modify
    }

    else if (state == 25) {
      clearInterval(intervalDown);
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if (state == 29) { //modifying
      TPD = TPD - 1;
      $('#screenContentValue1').text(TPD + '%');//text modify
    }

    else if (state == 30) {
      clearInterval(intervalDown);
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if (state == 32) { //modifying
      totalWater = totalWater - 5;
      $('#screenContentValue1').text(totalWater + 'ML');//text modify
    }

    else if (state == 33) {
      clearInterval(intervalDown);
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      state = 14;
      mainTransfer();
    }

    else if (state == 35) { //modifying
      inliu = inliu - 1;
      $('#screenContentValue1').text(inliu + '週期');//text modify
    }

    else if (state == 36) {
      clearInterval(intervalDown);
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      state = 20;
      mainTransfer();
    }

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

var stopTransfer2 = function(stateF) {
  if (stateF%2 == 1) {
    $('#btnImgStop').attr('src', '../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelStop.imageset/a.png');//restart modify   
  }
  else {
    $('#btnImgStop').attr('src', '../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelStopSelect.imageset/a.png');//restart modify   
  }

  stopState = stopState + 1;
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

var upTransfer2 = function(stateF) {
  if (stateF%2 == 1) {
    $('#btnImgUp').attr('src', '../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_up.imageset/a.png');//restart modify   
  }
  else {
    $('#btnImgUp').attr('src', '../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_up_select.imageset/a.png');//restart modify   
  }

  upState = upState + 1;
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
    $('#screenSpan').css('opacity', '1');
  }
  else {
    $('#screenSpan').css('opacity', '0');
  }

  screenState = screenState + 1;
}

var mainTransfer = function() {
  console.log('mainTransfer' + state);
  if (state == 1) {
    intervalOff = setInterval(function() {offTransfer2(offState)}, 400);//shining
    $('#contentWord').text('開啟電源');//text modify
    $('#screenContentWord').text('');//screen text modify
    $('.ps').css('color', 'black');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/turnon.mp3');
    $('#nurseAudio').trigger('play');

  }

  else if (state == 2) {
    $('#contentWord').text('');//text modify
    $('#screenContentWord').text('啟動標準模式');//text modify
    $('.ps').css('color', 'black');
    setTimeout(function() {
      intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
      $('#contentWord').text('按向下鍵');//text modify
      $('#screenContentWord').text('按綠色鍵開始執行');//text modify

      $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/downbutton.mp3');
      $('#nurseAudio').trigger('play');   
    }, 2000);
    
  }

  else if (state == 3) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('按向下鍵');//text modify
    $('#screenContentWord').text('更改程式');//text modify
    $('.ps').css('color', 'black');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/downbutton.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 4) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('治療方式為TPD請按向下鍵');//text modify
    $('#screenContentWord').text('治療方式：TPD');//text modify
    $('.ps').css('color', 'black');
    $('#p1').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/tpd.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 5) {
    intervalEnter = setInterval(function() {enterTransfer2(enterState)}, 400);//shining
    $('#contentWord').text('依據設定處方，總治療量為9000ML請按ENTER鍵後，按上下鍵調整數值至9000ML再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').text('總治療量：8000ML');//text modify
    $('.ps').css('color', 'black');
    $('#p2').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/9000ML.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 6) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    intervalUp = setInterval(function() {upTransfer2(upState)}, 400);//shining
    $('#contentWord').text('依據設定處方，總治療量為9000ML請按ENTER鍵後，按上下鍵調整數值至9000ML再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').text('總治療量：                              ');//text modify
    $('#screenContentValue1').text(treatValue + 'ML');//text modify
    intervalScreen = setInterval(function() {screenTransfer2(screenState)}, 400);
    $('.ps').css('color', 'black');
    $('#p2').css('color', 'red');

  }

  else if (state == 7) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('依據設定處方，總治療量為9000ML請按ENTER鍵後，按上下鍵調整數值至9000ML再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').text('總治療量：                              ');//text modify
    $('#screenContentValue1').text(treatValue + 'ML');//text modify
    $('.ps').css('color', 'black');
    $('#p2').css('color', 'red');
  }

  else if (state == 8) {
    intervalEnter = setInterval(function() {enterTransfer2(enterState)}, 400);//shining
    $('#contentWord').text('接下來是治療時間的設定，依據處方，治療時間為8:00，請按ENTER鍵後，按上下鍵調整數值至8:00再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').text('治療時間：                              ');//text modify
    $('#screenContentValue1').text(time1);//text modify
    $('#screenContentValue2').text(time2);//text modify
    $('#comma').css('display', 'inline-block');
    $('.ps').css('color', 'black');
    $('#p3').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/8time.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 9) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    intervalUp = setInterval(function() {upTransfer2(upState)}, 400);//shining
    $('#contentWord').text('接下來是治療時間的設定，依據處方，治療時間為8:00，請按ENTER鍵後，按上下鍵調整數值至8:00再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').text('治療時間：                              ');//text modify
    $('#screenContentValue1').text(time1);//text modify
    $('#screenContentValue2').text(time2);//text modify
    intervalScreen = setInterval(function() {screenTransfer2(screenState)}, 400);
    $('.ps').css('color', 'black');
    $('#p3').css('color', 'red');
  }

  else if (state == 10) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('接下來是治療時間的設定，依據處方，治療時間為8:00，請按ENTER鍵後，按上下鍵調整數值至8:00再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').text('治療時間：                              ');//text modify
    $('#screenContentValue1').text(time1);//text modify
    $('#screenContentValue2').text(time2);//text modify
    if (time2 == 0) {
      $('#screenContentValue2').text('00');//text modify
    }

    else if (time2 == 5) {
      $('#screenContentValue2').text('05');//text modify
    }
    $('.ps').css('color', 'black');
    $('#p3').css('color', 'red');
  }

  else if (state == 11) {
    intervalEnter = setInterval(function() {enterTransfer2(enterState)}, 400);//shining
    $('#contentWord').text('依據處方，注入量為2000ML，請按ENTER鍵後，按上下鍵調整數值至2000ML再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').text('注入量：                              ');//text modify
    $('#screenContentValue1').text(insertValue + 'ML');
    $('#screenContentValue2').text('');
    $('#comma').css('display', 'none');
    $('.ps').css('color', 'black');
    $('#p4').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/2000ml.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 12) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    intervalUp = setInterval(function() {upTransfer2(upState)}, 400);//shining
    $('#contentWord').text('依據處方，注入量為2000ML，請按ENTER鍵後，按上下鍵調整數值至2000ML再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').text('注入量：                              ');//text modify
    $('#screenContentValue1').text(insertValue + 'ML');//text modify
    intervalScreen = setInterval(function() {screenTransfer2(screenState)}, 400);
    $('.ps').css('color', 'black');
    $('#p4').css('color', 'red');
  }

  else if (state == 13) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('依據處方，注入量為2000ML，請按ENTER鍵後，按上下鍵調整數值至2000ML再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').text('注入量：                              ');//text modify
    $('#screenContentValue1').text(insertValue + 'ML');//text modify
    $('.ps').css('color', 'black');
    $('#p4').css('color', 'red');
  }

  else if (state == 14) {
    intervalEnter = setInterval(function() {enterTransfer2(enterState)}, 400);//shining
    $('#contentWord').text('依據處方，最末袋注入量為1000ML，請按ENTER鍵後，按上下鍵調整數值至1000ML再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').text('最末袋注入量：                           ');//text modify
    $('#screenContentValue1').text(lastValue + 'ML');
    $('#screenContentValue2').text('');
    $('#comma').css('display', 'none');
    $('.ps').css('color', 'black');
    $('#p5').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/1000ml.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 15) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    intervalUp = setInterval(function() {upTransfer2(upState)}, 400);//shining
    $('#contentWord').text('依據處方，最末袋注入量為1000ML，請按ENTER鍵後，按上下鍵調整數值至1000ML再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').text('最末袋注入量：                           ');//text modify
    $('#screenContentValue1').text(lastValue + 'ML');
    intervalScreen = setInterval(function() {screenTransfer2(screenState)}, 400);
    $('.ps').css('color', 'black');
    $('#p5').css('color', 'red');
  }

  else if (state == 16) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('依據處方，最末袋注入量為1000ML，請按ENTER鍵後，按上下鍵調整數值至1000ML再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').text('最末袋注入量：                           ');//text modify
    $('#screenContentValue1').text(lastValue + 'ML');
    $('.ps').css('color', 'black');
    $('#p5').css('color', 'red');
  }

  else if (state == 17) {
    intervalEnter = setInterval(function() {enterTransfer2(enterState)}, 400);//shining
    $('#contentWord').text('依據處方，最末袋葡萄糖濃度應設定為為"不同"，請按ENTER鍵後，按上下鍵調整數值至"不同"再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').text('最末袋葡萄糖濃度：                      ');//text modify
    $('#screenContentValue1').text(lastGrape);
    $('#screenContentValue2').text('');
    $('#comma').css('display', 'none');
    $('.ps').css('color', 'black');
    $('#p6').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/different.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 18) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    intervalUp = setInterval(function() {upTransfer2(upState)}, 400);//shining
    $('#contentWord').text('依據處方，最末袋葡萄糖濃度應設定為為"不同"，請按ENTER鍵後，按上下鍵調整數值至"不同"再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').text('最末袋葡萄糖濃度：                      ');//text modify
    $('#screenContentValue1').text(lastGrape);
    intervalScreen = setInterval(function() {screenTransfer2(screenState)}, 400);
    $('.ps').css('color', 'black');
    $('#p6').css('color', 'red');
  }

  else if (state == 19) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('依據處方，最末袋葡萄糖濃度應設定為為"不同"，請按ENTER鍵後，按上下鍵調整數值至"不同"再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').text('最末袋葡萄糖濃度：                      ');//text modify
    $('#screenContentValue1').text(lastGrape);
    $('.ps').css('color', 'black');
    $('#p6').css('color', 'red');
  }

  else if (state == 20) {
    intervalEnter = setInterval(function() {enterTransfer2(enterState)}, 400);//shining
    $('#contentWord').text('依據處方，體重單位應設定為為"公斤"，請按ENTER鍵後，按上下鍵調整數值至"公斤"再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').text('體重單位：                              ');//text modify
    $('#screenContentValue1').text(weight);
    $('#screenContentValue2').text('');
    $('#comma').css('display', 'none');
    $('.ps').css('color', 'black');
    $('#p7').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/kilo.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 21) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    intervalUp = setInterval(function() {upTransfer2(upState)}, 400);//shining
    $('#contentWord').text('依據處方，體重單位應設定為為"公斤"，請按ENTER鍵後，按上下鍵調整數值至"公斤"再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').text('體重單位：                              ');//text modify
    $('#screenContentValue1').text(weight);
    intervalScreen = setInterval(function() {screenTransfer2(screenState)}, 400);
    $('.ps').css('color', 'black');
    $('#p7').css('color', 'red');
  }

  else if (state == 22) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('依據處方，體重單位應設定為為"公斤"，請按ENTER鍵後，按上下鍵調整數值至"公斤"再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').text('體重單位：                              ');//text modify
    $('#screenContentValue1').text(weight);
    $('.ps').css('color', 'black');
    $('#p7').css('color', 'red');
  }

  else if (state == 23) {
    intervalEnter = setInterval(function() {enterTransfer2(enterState)}, 400);//shining
    $('#contentWord').text('依據處方，患者體重為50公斤，請按ENTER鍵後，按上下鍵調整數值至50公斤再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').text('患者體重：                              ');//text modify
    $('#screenContentValue1').text(weightValue + '公斤');
    $('#screenContentValue2').text('');
    $('#comma').css('display', 'none');
    $('.ps').css('color', 'black');
    $('#p8').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/50kilo.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 24) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    intervalUp = setInterval(function() {upTransfer2(upState)}, 400);//shining
    $('#contentWord').text('依據處方，患者體重為50公斤，請按ENTER鍵後，按上下鍵調整數值至50公斤再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').text('患者體重：                              ');//text modify
    $('#screenContentValue1').text(weightValue + '公斤');
    intervalScreen = setInterval(function() {screenTransfer2(screenState)}, 400);
    $('.ps').css('color', 'black');
    $('#p8').css('color', 'red');
  }

  else if (state == 25) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('依據處方，患者體重為50公斤，請按ENTER鍵後，按上下鍵調整數值至50公斤再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').text('患者體重：                              ');//text modify
    $('#screenContentValue1').text(weightValue + '公斤');
    $('.ps').css('color', 'black');
    $('#p8').css('color', 'red');
  }

  else if (state == 26) {
    intervalStop = setInterval(function() {stopTransfer2(stopState)}, 400);//shining
    $('#contentWord').text('按紅色鍵');//text modify
    $('#screenContentWord').text('患者體重：                              ');//text modify
    $('#screenContentValue1').text(weightValue + '公斤');
    $('.ps').css('color', 'black');
    $('#p8').css('color', 'red');
  }

  else if (state == 27) {
    $('#contentWord').text('更改程式已完成，機器已準備就緒，可以開始治療');//text modify
    $('#screenContentWord').text('按綠色鍵開始執行');//text modify
    $('#screenContentValue1').text('');
    $('.ps').css('color', 'black');
    $('#p8').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/treatready.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 28) {
    intervalEnter = setInterval(function() {enterTransfer2(enterState)}, 400);//shining
    $('#contentWord').text('依據處方範例，TPD容量百分比為70%，請按ENTER鍵後，按上下鍵將TPD容量百分比調整至70%再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').text('TPD容量百分比：');//text modify
    $('#screenContentValue1').text(TPD + '%');
    $('#screenContentValue2').text('');
    $('#comma').css('display', 'none');
    $('.ps').css('color', 'black');
    $('#p9').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/tpd70.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 29) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    intervalUp = setInterval(function() {upTransfer2(upState)}, 400);//shining
    $('#contentWord').text('依據處方範例，TPD容量百分比為70%，請按ENTER鍵後，按上下鍵將TPD容量百分比調整至70%再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').text('TPD容量百分比：');//text modify
    $('#screenContentValue1').text(TPD + '%');
    intervalScreen = setInterval(function() {screenTransfer2(screenState)}, 400);
    $('.ps').css('color', 'black');
    $('#p9').css('color', 'red');
  }

  else if (state == 30) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('依據處方範例，TPD容量百分比為70%，請按ENTER鍵後，按上下鍵將TPD容量百分比調整至70%再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').text('TPD容量百分比：');//text modify
    $('#screenContentValue1').text(TPD + '%');
    $('.ps').css('color', 'black');
    $('#p9').css('color', 'red');
  }

  else if (state == 31) {
    intervalEnter = setInterval(function() {enterTransfer2(enterState)}, 400);//shining
    $('#contentWord').text('依據處方範例，總脫水量為10ML，請按ENTER鍵後，按上下鍵將總脫水量調整至10ML再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').text('總脫水量：');//text modify
    $('#screenContentValue1').text(totalWater + 'ML');
    $('#screenContentValue2').text('');
    $('#comma').css('display', 'none');
    $('.ps').css('color', 'black');
    $('#p10').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/10ml.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 32) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    intervalUp = setInterval(function() {upTransfer2(upState)}, 400);//shining
    $('#contentWord').text('依據處方範例，總脫水量為10ML，請按ENTER鍵後，按上下鍵將總脫水量調整至10ML再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').text('總脫水量：');//text modify
    $('#screenContentValue1').text(totalWater + 'ML');
    intervalScreen = setInterval(function() {screenTransfer2(screenState)}, 400);
    $('.ps').css('color', 'black');
    $('#p10').css('color', 'red');
  }

  else if (state == 33) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('依據處方範例，總脫水量為10ML，請按ENTER鍵後，按上下鍵將總脫水量調整至10ML再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').text('總脫水量：');//text modify
    $('#screenContentValue1').text(totalWater + 'ML');
    $('.ps').css('color', 'black');
    $('#p10').css('color', 'red');
  }

  else if (state == 34) {
    intervalEnter = setInterval(function() {enterTransfer2(enterState)}, 400);//shining
    $('#contentWord').text('依據處方，完全引流應設定為3週期，請按ENTER鍵後，按上下鍵將完全引流調整至3週期再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').text('完全引流：');//text modify
    $('#screenContentValue1').text(inliu + '週期');
    $('#screenContentValue2').text('');
    $('#comma').css('display', 'none');
    $('.ps').css('color', 'black');
    $('#p11').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/3period.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 35) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    intervalUp = setInterval(function() {upTransfer2(upState)}, 400);//shining
    $('#contentWord').text('依據處方，完全引流應設定為3週期，請按ENTER鍵後，按上下鍵將完全引流調整至3週期再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').text('完全引流：');//text modify
    $('#screenContentValue1').text(inliu + '週期');
    intervalScreen = setInterval(function() {screenTransfer2(screenState)}, 400);
    $('.ps').css('color', 'black');
    $('#p11').css('color', 'red');
  }

  else if (state == 36) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('依據處方，完全引流應設定為3週期，請按ENTER鍵後，按上下鍵將完全引流調整至3週期再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').text('完全引流：');//text modify
    $('#screenContentValue1').text(inliu + '週期');
    $('.ps').css('color', 'black');
    $('#p11').css('color', 'red');
  }

}

$.fn.multiline = function(text){
    this.text(text);
    this.html(this.html().replace(/\n/g,'<br/>'));
    return this;
}