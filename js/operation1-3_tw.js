//variables
var state = 1;
var offState = 1;
var goState = 1;
var mode = "CCPD/IPD"
var nextState = 1;
var lastState = 1;
var restartState = 1;
var stopState = 1;
var downState = 1;
var enterState = 1;
var upState = 1;
var screenState = 1;

var treatValue = 7000;
var time1 = 5;
var time2 = 50;
var insertValue = 1500;
var lastValue = 300;
var lastGrape = "不同";
var weight = "磅";
var weightValue = 65;

var mod1v = "CCPD/IPD";
var mod2v = 8000;
var mod3v = 2000;
var mod4v = 0;
var mod5v = 66;
var mod61v = 6;
var mod62v = 0;

$(document).ready(function(){
  mainTransfer();

  $('#moderation').hide();

  $('#btnModeration').click(function() {
    if (state <= 3) {
      $('#moderation').show();
    }
    else {
      alert('小護士的溫馨提醒：不建議於機器運行中修改處方喔！');
    }

  });


  $('#enter').click(function() {
    $('#moderation').hide();
    $('#ta1').html(mod1v);
    $('#ta2').html(mod2v);
    $('#ta3').html(mod3v);
    $('#ta4').html(mod4v);
    $('#ta5').html(mod5v);
    $('#ta61').html(mod61v);
    $('#ta62').html(mod62v);
    if (mod62v == 0) {
      $('#ta62').html('00');
    }
  });


  $('#mod1m').click(function() {
    if (mod1v == "CCPD/IPD") {
      mod1v = "CCPD/IPD";
      $('#mod1v').html(mod1v);
    }
    else if (mod1v == "高劑量CCPD") {
      mod1v = "CCPD/IPD";
      $('#mod1v').html(mod1v);
    }
    else if (mod1v == "潮式TPD") {
      mod1v = "高劑量CCPD";
      $('#mod1v').html(mod1v);
    }
    else if (mod1v == "高劑量潮式") {
      mod1v = "潮式TPD";
      $('#mod1v').html(mod1v);
    }
  });
  $('#mod1p').click(function() {
    if (mod1v == "CCPD/IPD") {
      console.log("gangan");
      mod1v = "高劑量CCPD";
      $('#mod1v').html(mod1v);
    }
    else if (mod1v == "高劑量CCPD") {
      mod1v = "潮式TPD";
      $('#mod1v').html(mod1v);
    }
    else if (mod1v == "潮式TPD") {
      mod1v = "高劑量潮式";
      $('#mod1v').html(mod1v);
    }
    else if (mod1v == "高劑量潮式") {
      mod1v = "高劑量潮式";
      $('#mod1v').html(mod1v);
    }
  });
  $('#mod2m').click(function() {
    if (mod2v >= 4500) {
        mod2v = mod2v - 500;
        $('#mod2v').text(mod2v + 'ML');//text modify  
      }
      else if ((mod2v >= 2000) && (mod2v < 4500)) {
        mod2v = mod2v - 100;
        $('#mod2v').text(mod2v + 'ML');//text modify  
      } 
      else if ((mod2v < 2000) && (mod2v >= 250)) {
        mod2v = mod2v - 50;
        $('#mod2v').text(mod2v + 'ML');//text modify  
      }
  });
  $('#mod2p').click(function() {
    if ((mod2v >= 4500) && (mod2v <= 80000)) {
        mod2v = mod2v + 500;
        $('#mod2v').text(mod2v + 'ML');//text modify  
      }
      else if ((mod2v >= 2000) && (mod2v < 4500)) {
        mod2v = mod2v + 100;
        $('#mod2v').text(mod2v + 'ML');//text modify  
      } 
      else if ((mod2v < 2000) && (mod2v >= 200)) {
        mod2v = mod2v + 50;
        $('#mod2v').text(mod2v + 'ML');//text modify  
      }
  });
  $('#mod3m').click(function() {
    if (mod3v > 1000) {
        mod3v = mod3v - 100;
        $('#mod3v').text(mod3v + 'ML');//text modify  
      }
      else if ((mod3v > 500) && (mod3v <= 1000)) {
        mod3v = mod3v - 50;
        $('#mod3v').text(mod3v + 'ML');//text modify  
      } 
      else if ((mod3v <= 500) && (mod3v > 100)) {
        mod3v = mod3v - 10;
        $('#mod3v').text(mod3v + 'ML');//text modify  
      }
  });
  $('#mod3p').click(function() {
    if (mod3v >= 1000) {
        mod3v = mod3v + 100;
        $('#mod3v').text(mod3v + 'ML');//text modify  
      }
      else if ((mod3v >= 500) && (mod3v < 1000)) {
        mod3v = mod3v + 50;
        $('#mod3v').text(mod3v + 'ML');//text modify  
      } 
      else if ((mod3v < 500) && (mod3v >= 100)) {
        mod3v = mod3v + 10;
        $('#mod3v').text(mod3v + 'ML');//text modify  
      }

    if (mod3v >= 3000) {
      mod3v = 3000;
      $('#mod3v').text(mod3v + 'ML');//text modify  
    }
  });
  $('#mod4m').click(function() {
    if (mod4v > 1000) {
        mod4v = mod4v - 100;
        $('#mod4v').text(mod4v + 'ML');//text modify  
      }
      else if ((mod4v > 500) && (mod4v <= 1000)) {
        mod4v = mod4v - 50;
        $('#mod4v').text(mod4v + 'ML');//text modify  
      } 
      else if ((mod4v <= 500) && (mod4v > 100)) {
        mod4v = mod4v - 10;
        $('#mod4v').text(mod4v + 'ML');//text modify  
      }
  });
  $('#mod4p').click(function() {
    if (mod4v >= 1000) {
        mod4v = mod4v + 100;
        $('#mod4v').text(mod4v + 'ML');//text modify  
      }
      else if ((mod4v >= 500) && (mod4v < 1000)) {
        mod4v = mod4v + 50;
        $('#mod4v').text(mod4v + 'ML');//text modify  
      } 
      else if ((mod4v < 500) && (mod4v >= 0)) {
        mod4v = mod4v + 10;
        $('#mod4v').text(mod4v + 'ML');//text modify  
      }

    if (mod4v >= 3000) {
      mod4v = 3000;
      $('#mod4v').text(mod4v + 'ML');//text modify  
    }
  });
  $('#mod5m').click(function() {
    mod5v = Math.max(mod5v - 1, 30);
    $('#mod5v').text(mod5v + '公斤');//text modify  
  });
  $('#mod5p').click(function() {
    mod5v = Math.min(mod5v + 1, 200);
    $('#mod5v').text(mod5v + '公斤');//text modify  
  });
  $('#mod6m').click(function() {
    mod62v = mod62v - 10;
    $('#mod62v').html(mod62v);
    if (mod62v == -10) {
      mod61v = mod61v - 1;
      if (mod61v == -1) {
        mod61v = 23;
      }
      mod62v = 50;
      $('#mod61v').html(mod61v);
      $('#mod62v').html(mod62v);
    }
    if (mod62v == 0) {
      console.log("fjdsakjf;");
      $('#mod62v').html('00'); 
    }
  });
  $('#mod6p').click(function() {
    mod62v = mod62v + 10;
    $('#mod62v').html(mod62v);
    if (mod62v == 60) {
      mod61v = mod61v + 1;
      $('#mod61v').html(mod61v);
      mod62v = 0;
      $('#mod62v').html(mod62v);
    }
    if (mod61v == 24) {
        mod61v = 0;
        $('#mod61v').html(mod61v);
      };
    if (mod62v == 0) {
      $('#mod62v').html('00'); 
    } 
  });

  $('#btnStop').click(function() {
    $('#beep').trigger('play');
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
    location.reload();
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

  $('#btnEnter').click(function() {
    $('#beep').trigger('play');
    if (state == 3) {
      clearInterval(intervalEnter);
      $('#btnImgEnter').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelEnter.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if (state == 4) {
      clearInterval(intervalDown);
      state = state + 0.5;
      mainTransfer();
    }
    else if ((state == 4.5) && (mode == mod1v)) {
      clearInterval(intervalDown);
      clearInterval(intervalUp);
      clearInterval(intervalScreen);
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      $('#btnImgUp').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_up.imageset/a.png");
      state = state + 0.3;
      $('#screenSpan').css('opacity', '1');
      mainTransfer();
    }

    else if (state == 5) {
      clearInterval(intervalEnter);
      $('#btnImgEnter').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelEnter.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if ((state == 6) && (treatValue == mod2v)) {
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

    else if ((state == 9) && (time1 == mod61v) && (time2 == mod62v)) {
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

    else if ((state == 12) && (insertValue == mod3v)) {
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

    else if ((state == 15) && (lastValue == mod4v)) {
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

    else if ((state == 18) && (lastGrape == "相同")) {
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

    else if ((state == 24) && (weightValue == mod5v)) {
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
    $('#beep').trigger('play');
    if (state == 4.5) {   
      if (mode == "CCPD/IPD") {
        mode = "CCPD/IPD";
      }
      else if (mode == "TPD") {
        mode = "CCPD/IPD";
      }
      else if (mode == "高劑量CCPD") {
        mode = "TPD";
      }
      else if (mode == "高劑量TPD") {
        mode = "高劑量CCPD";
      }
          $('#screenContentValue1').html(mode);//text modify
    }

    else if (state == 6) {
      if (treatValue >= 4500) {
        treatValue = treatValue + 500;
        $('#screenContentValue1').text(treatValue + 'ML');//text modify  
      }
      else if ((treatValue >= 2000) && (treatValue < 4500)) {
        treatValue = treatValue + 100;
        $('#screenContentValue1').text(treatValue + 'ML');//text modify  
      } 
      else if ((treatValue < 2000) && (treatValue >= 150)) {
        treatValue = treatValue + 50;
        $('#screenContentValue1').text(treatValue + 'ML');//text modify  
      } 

      
    }

    else if (state == 9) { //modifying
      time2 = time2 + 10;
      if (time2 >= 60) {
        time1 = time1 + 1;
        time2 = 0;
        if (time1 == 24) {
          time1 = 0;
          time2 = 0;
        }
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
      if (insertValue >= 1000) {
        insertValue = insertValue + 100;
        $('#screenContentValue1').text(insertValue + 'ML');//text modify  
      }
      else if ((insertValue >= 500) && (insertValue < 1000)) {
        insertValue = insertValue + 50;
        $('#screenContentValue1').text(insertValue + 'ML');//text modify  
      } 
      else if ((insertValue < 500) && (insertValue >= 0)) {
        insertValue = insertValue + 10;
        $('#screenContentValue1').text(insertValue + 'ML');//text modify  
      }

      if (insertValue >= 3000) {
        insertValue = 3000;
        $('#screenContentValue1').text(insertValue + 'ML');//text modify  
      }
    }  

    else if (state == 15) { //modifying
      if (lastValue >= 1000) {
        lastValue = lastValue + 100;
        $('#screenContentValue1').text(lastValue + 'ML');//text modify  
      }
      else if ((lastValue >= 500) && (lastValue < 1000)) {
        lastValue = lastValue + 50;
        $('#screenContentValue1').text(lastValue + 'ML');//text modify  
      } 
      else if ((lastValue < 500) && (lastValue >= 0)) {
        lastValue = lastValue + 10;
        $('#screenContentValue1').text(lastValue + 'ML');//text modify  
      }

      if (lastValue >= 3000) {
        lastValue = 3000;
        $('#screenContentValue1').text(lastValue + 'ML');//text modify  
      }
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
        weight = "公斤";
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
  });

  $('#btnDown').click(function() {
    $('#beep').trigger('play');
    if (state == 2) {
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

    else if (state == 4.5) {    
      if (mode == "CCPD/IPD") {
       mode = "TPD";
      }
      else if (mode == "TPD") {
        mode = "高劑量CCPD";
      }
      else if (mode == "高劑量CCPD") {
        mode = "高劑量TPD";
      }
      else if (mode == "高劑量TPD") {
        mode = "高劑量TPD";
      }
        $('#screenContentValue1').html(mode);//text modify
    }

    else if (state == 4.8) {
      clearInterval(intervalDown);
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      state = state + 0.2;
      mainTransfer();
    }

    else if (state == 6) { //modifying
      if (treatValue >= 4500) {
        treatValue = treatValue - 500;
        $('#screenContentValue1').text(treatValue + 'ML');//text modify  
      }
      else if ((treatValue >= 2000) && (treatValue < 4500)) {
        treatValue = treatValue - 100;
        $('#screenContentValue1').text(treatValue + 'ML');//text modify  
      } 
      else if ((treatValue < 2000) && (treatValue > 150)) {
        treatValue = treatValue - 50;
        $('#screenContentValue1').text(treatValue + 'ML');//text modify  
      }
    }

    else if (state == 7) {
      clearInterval(intervalDown);
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if (state == 9) { //modifying
      time2 = time2 - 10;
      if (time2 < 0) {
        time1 = time1 - 1;
        time2 = 50;
      }
      if (time1 < 0) {
        time1 = 23;
        time2 = 50;
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
      if (insertValue > 1000) {
        insertValue = insertValue - 100;
        $('#screenContentValue1').text(insertValue + 'ML');//text modify  
      }
      else if ((insertValue > 500) && (insertValue <= 1000)) {
        insertValue = insertValue - 50;
        $('#screenContentValue1').text(insertValue + 'ML');//text modify  
      } 
      else if ((insertValue <= 500) && (insertValue > 0)) {
        insertValue = insertValue - 10;
        $('#screenContentValue1').text(insertValue + 'ML');//text modify  
      }
    }

    else if (state == 13) {
      clearInterval(intervalDown);
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }

    else if (state == 15) { //modifying
      if (lastValue > 1000) {
        lastValue = lastValue - 100;
        $('#screenContentValue1').text(lastValue + 'ML');//text modify  
      }
      else if ((lastValue > 500) && (lastValue <= 1000)) {
        lastValue = lastValue - 50;
        $('#screenContentValue1').text(lastValue + 'ML');//text modify  
      } 
      else if ((lastValue <= 500) && (lastValue > 0)) {
        lastValue = lastValue - 10;
        $('#screenContentValue1').text(lastValue + 'ML');//text modify  
      }
    } 

    else if (state == 16) {
      clearInterval(intervalDown);
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      state = state + 4;
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
      state = state + 1;
      mainTransfer();
    }

    else if (state == 21) { //modifying
      if (weight == "公斤") {
        weight = "磅";
        $('#screenContentValue1').text(weight);//text modify
      }
      else {
        weight = "磅";
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
      if (weightValue > 0) {
        weightValue = weightValue - 1;
        $('#screenContentValue1').text(weightValue + '公斤');//text modify  
      }
    }

    else if (state == 25) {
      clearInterval(intervalDown);
      $('#btnImgDown').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/Panel_menu_down.imageset/a.png");
      state = state + 1;
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
    console.log('1');
    $('#screenSpan').css('opacity', '1');
  }
  else {
    console.log('2');
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

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/turnon_tw.mp3');
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

      $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/downbutton_tw.mp3');
      $('#nurseAudio').trigger('play');   
    }, 2000);
    
  }

  else if (state == 3) {
    intervalEnter = setInterval(function() {enterTransfer2(enterState)}, 400);//shining
    $('#contentWord').text('按向下鍵');//text modify
    $('#screenContentWord').text('更改程式');//text modify
    $('.ps').css('color', 'black');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/downbutton_tw.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 4) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('治療方式為'+mod1v+'請按向下鍵');//text modify
    $('#screenContentWord').html('<ul>治療方式:</ul>');//text modify
    $('#screenContentValue1').html(mode);//text modify
    $('#screenContentValue1').show();
    $('.ps').css('color', 'black');
    $('#p1').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/ccpd_tw.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 4.5) {
    intervalUp = setInterval(function() {upTransfer2(upState)}, 400);//shining
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    intervalScreen = setInterval(function() {screenTransfer2(screenState)}, 400);
    $('#screenContentValue1').html(mode);//text modify
  }

  else if (state == 4.8) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#screenContentValue1').html(mode);//text modify
  }

  else if (state == 5) {
    intervalEnter = setInterval(function() {enterTransfer2(enterState)}, 400);//shining
    $('#contentWord').text('依據設定處方，總治療量為'+mod2v+'ML請按ENTER鍵後，按上下鍵調整數值至'+mod2v+'ML再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').html('<ul>總治療量：</ul>');//text modify
    $('#screenContentValue1').text(treatValue + 'ML');//text modify
    $('.ps').css('color', 'black');
    $('#p2').css('color', 'red');

    $('#screenContentValue1').show();

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/8000ML_tw.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 6) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    intervalUp = setInterval(function() {upTransfer2(upState)}, 400);//shining
    $('#contentWord').text('依據設定處方，總治療量為'+mod2v+'ML請按ENTER鍵後，按上下鍵調整數值至'+mod2v+'ML再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').html('<ul>總治療量：</ul>');//text modify
    $('#screenContentValue1').text(treatValue + 'ML');//text modify
    intervalScreen = setInterval(function() {screenTransfer2(screenState)}, 400);
    $('.ps').css('color', 'black');
    $('#p2').css('color', 'red');

  }

  else if (state == 7) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('依據設定處方，總治療量為'+mod2v+'ML請按ENTER鍵後，按上下鍵調整數值至'+mod2v+'ML再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').html('<ul>總治療量：</ul>');//text modify
    $('#screenContentValue1').text(treatValue + 'ML');//text modify
    $('.ps').css('color', 'black');
    $('#p2').css('color', 'red');
  }

  else if (state == 8) {
    intervalEnter = setInterval(function() {enterTransfer2(enterState)}, 400);//shining
    if (mod62v == 0) {
      $('#contentWord').html('接下來是治療時間的設定，依據處方，治療時間為'+mod61v+':00，請按ENTER鍵後，按上下鍵調整數值至'+mod61v+':00再按ENTER鍵完成設定。');//text modify
    }
    else {
      $('#contentWord').html('接下來是治療時間的設定，依據處方，治療時間為'+mod61v+':'+mod62v+'，請按ENTER鍵後，按上下鍵調整數值至'+mod61v+':'+mod62v+'再按ENTER鍵完成設定。');//text modify
    } 
    $('#screenContentWord').html('<ul>治療時間：</ul>');//text modify
    $('#screenContentValue1').text(time1);//text modify
    $('#screenContentValue2').text(time2);//text modify
    $('#comma').css('display', 'inline-block');
    $('.ps').css('color', 'black');
    $('#p3').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/6time_tw.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 9) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    intervalUp = setInterval(function() {upTransfer2(upState)}, 400);//shining
    if (mod62v == 0) {
      $('#contentWord').html('接下來是治療時間的設定，依據處方，治療時間為'+mod61v+':00，請按ENTER鍵後，按上下鍵調整數值至'+mod61v+':00再按ENTER鍵完成設定。');//text modify
    }
    else {
      $('#contentWord').html('接下來是治療時間的設定，依據處方，治療時間為'+mod61v+':'+mod62v+'，請按ENTER鍵後，按上下鍵調整數值至'+mod61v+':'+mod62v+'再按ENTER鍵完成設定。');//text modify
    } 
    $('#screenContentWord').html('<ul>治療時間：</ul>');//text modify
    $('#screenContentValue1').text(time1);//text modify
    $('#screenContentValue2').text(time2);//text modify
    intervalScreen = setInterval(function() {screenTransfer2(screenState)}, 400);
    $('.ps').css('color', 'black');
    $('#p3').css('color', 'red');
  }

  else if (state == 10) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    if (mod62v == 0) {
      $('#contentWord').html('接下來是治療時間的設定，依據處方，治療時間為'+mod61v+':00，請按ENTER鍵後，按上下鍵調整數值至'+mod61v+':00再按ENTER鍵完成設定。');//text modify
    }
    else {
      $('#contentWord').html('接下來是治療時間的設定，依據處方，治療時間為'+mod61v+':'+mod62v+'，請按ENTER鍵後，按上下鍵調整數值至'+mod61v+':'+mod62v+'再按ENTER鍵完成設定。');//text modify
    } 
    $('#screenContentWord').html('<ul>治療時間：</ul>');//text modify
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
    $('#contentWord').text('依據處方，注入量為'+mod3v+'ML，請按ENTER鍵後，按上下鍵調整數值至'+mod3v+'ML再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').html('<ul>注入量：</ul>');//text modify
    $('#screenContentValue1').text(insertValue + 'ML');
    $('#screenContentValue2').text('');
    $('#comma').css('display', 'none');
    $('.ps').css('color', 'black');
    $('#p4').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/2000ml_tw.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 12) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    intervalUp = setInterval(function() {upTransfer2(upState)}, 400);//shining
    $('#contentWord').text('依據處方，注入量為'+mod3v+'ML，請按ENTER鍵後，按上下鍵調整數值至'+mod3v+'ML再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').html('<ul>注入量：</ul>');//text modify
    $('#screenContentValue1').text(insertValue + 'ML');//text modify
    intervalScreen = setInterval(function() {screenTransfer2(screenState)}, 400);
    $('.ps').css('color', 'black');
    $('#p4').css('color', 'red');
  }

  else if (state == 13) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('依據處方，注入量為'+mod3v+'ML，請按ENTER鍵後，按上下鍵調整數值至'+mod3v+'ML再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').html('<ul>注入量：</ul>');//text modify
    $('#screenContentValue1').text(insertValue + 'ML');//text modify
    $('.ps').css('color', 'black');
    $('#p4').css('color', 'red');
  }

  else if (state == 14) {
    intervalEnter = setInterval(function() {enterTransfer2(enterState)}, 400);//shining
    $('#contentWord').text('依據處方，最末袋注入量為'+mod4v+'ML，請按ENTER鍵後，按上下鍵調整數值至'+mod4v+'ML再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').html('<ul>最末袋注入量：</ul>');//text modify
    $('#screenContentValue1').text(lastValue + 'ML');
    $('#screenContentValue2').text('');
    $('#comma').css('display', 'none');
    $('.ps').css('color', 'black');
    $('#p5').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/0mllast_tw.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 15) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    intervalUp = setInterval(function() {upTransfer2(upState)}, 400);//shining
    $('#contentWord').text('依據處方，最末袋注入量為'+mod4v+'ML，請按ENTER鍵後，按上下鍵調整數值至'+mod4v+'ML再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').html('<ul>最末袋注入量：</ul>');//text modify
    $('#screenContentValue1').text(lastValue + 'ML');
    intervalScreen = setInterval(function() {screenTransfer2(screenState)}, 400);
    $('.ps').css('color', 'black');
    $('#p5').css('color', 'red');
  }

  else if (state == 16) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('依據處方，最末袋注入量為'+mod4v+'ML，請按ENTER鍵後，按上下鍵調整數值至'+mod4v+'ML再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').html('<ul>最末袋注入量：</ul>');//text modify
    $('#screenContentValue1').text(lastValue + 'ML');
    $('.ps').css('color', 'black');
    $('#p5').css('color', 'red');
  }

  else if (state == 17) {
    intervalEnter = setInterval(function() {enterTransfer2(enterState)}, 400);//shining
    $('#contentWord').text('依據處方，最末袋葡萄糖濃度應設定為為"相同"，請按ENTER鍵後，按上下鍵調整數值至"相同"再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').html('<ul>最末袋葡萄糖濃度：</ul>');//text modify
    $('#screenContentValue1').text(lastGrape);
    $('#screenContentValue2').text('');
    $('#comma').css('display', 'none');
    $('.ps').css('color', 'black');
    $('#p6').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/same_tw.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 18) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    intervalUp = setInterval(function() {upTransfer2(upState)}, 400);//shining
    $('#contentWord').text('依據處方，最末袋葡萄糖濃度應設定為為"相同"，請按ENTER鍵後，按上下鍵調整數值至"相同"再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').html('<ul>最末袋葡萄糖濃度：</ul>');//text modify
    $('#screenContentValue1').text(lastGrape);
    intervalScreen = setInterval(function() {screenTransfer2(screenState)}, 400);
    $('.ps').css('color', 'black');
    $('#p6').css('color', 'red');
  }

  else if (state == 19) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('依據處方，最末袋葡萄糖濃度應設定為為"相同"，請按ENTER鍵後，按上下鍵調整數值至"相同"再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').html('<ul>最末袋葡萄糖濃度：</ul>');//text modify
    $('#screenContentValue1').text(lastGrape);
    $('.ps').css('color', 'black');
    $('#p6').css('color', 'red');
  }

  else if (state == 20) {
    intervalEnter = setInterval(function() {enterTransfer2(enterState)}, 400);//shining
    $('#contentWord').text('依據處方，體重單位應設定為為"公斤"，請按ENTER鍵後，按上下鍵調整數值至"公斤"再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').html('<ul>體重單位：</ul>');//text modify
    $('#screenContentValue1').text(weight);
    $('#screenContentValue2').text('');
    $('#comma').css('display', 'none');
    $('.ps').css('color', 'black');
    $('#p7').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/kilo_tw.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 21) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    intervalUp = setInterval(function() {upTransfer2(upState)}, 400);//shining
    $('#contentWord').text('依據處方，體重單位應設定為為"公斤"，請按ENTER鍵後，按上下鍵調整數值至"公斤"再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').html('<ul>體重單位：</ul>');//text modify
    $('#screenContentValue1').text(weight);
    intervalScreen = setInterval(function() {screenTransfer2(screenState)}, 400);
    $('.ps').css('color', 'black');
    $('#p7').css('color', 'red');
  }

  else if (state == 22) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('依據處方，體重單位應設定為為"公斤"，請按ENTER鍵後，按上下鍵調整數值至"公斤"再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').html('<ul>體重單位：</ul>');//text modify
    $('#screenContentValue1').text(weight);
    $('.ps').css('color', 'black');
    $('#p7').css('color', 'red');
  }

  else if (state == 23) {
    intervalEnter = setInterval(function() {enterTransfer2(enterState)}, 400);//shining
    $('#contentWord').text('依據處方，患者體重為'+mod5v+'公斤，請按ENTER鍵後，按上下鍵調整數值至'+mod5v+'公斤再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').html('<ul>患者體重：</ul>');//text modify
    $('#screenContentValue1').text(weightValue + '公斤');
    $('#screenContentValue2').text('');
    $('#comma').css('display', 'none');
    $('.ps').css('color', 'black');
    $('#p8').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/66kilo_tw.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 24) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    intervalUp = setInterval(function() {upTransfer2(upState)}, 400);//shining
    $('#contentWord').text('依據處方，患者體重為'+mod5v+'公斤，請按ENTER鍵後，按上下鍵調整數值至'+mod5v+'公斤再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').html('<ul>患者體重：</ul>');//text modify
    $('#screenContentValue1').text(weightValue + '公斤');
    intervalScreen = setInterval(function() {screenTransfer2(screenState)}, 400);
    $('.ps').css('color', 'black');
    $('#p8').css('color', 'red');
  }

  else if (state == 25) {
    intervalDown = setInterval(function() {downTransfer2(downState)}, 400);//shining
    $('#contentWord').text('依據處方，患者體重為'+mod5v+'公斤，請按ENTER鍵後，按上下鍵調整數值至'+mod5v+'公斤再按ENTER鍵完成設定。');//text modify
    $('#screenContentWord').html('<ul>患者體重：</ul>');//text modify
    $('#screenContentValue1').text(weightValue + '公斤');
    $('.ps').css('color', 'black');
    $('#p8').css('color', 'red');
  }

  else if (state == 26) {
    intervalStop = setInterval(function() {stopTransfer2(stopState)}, 400);//shining
    $('#contentWord').text('按紅色鍵');//text modify
    $('#screenContentWord').html('<ul>患者體重：</ul>');//text modify
    $('#screenContentValue1').text(weightValue + '公斤');
    $('.ps').css('color', 'black');
    $('#p8').css('color', 'red');
  }

  else if (state == 27) {
    $('#contentWord').text('更改程式已完成，機器已準備就緒，可以開始治療');//text modify
    $('#screenContentWord').html('<ul>週期數：</ul><ul>4</ul>');//text modify
    setTimeout(function() {
      $('#screenContentWord').html('<ul>留置時間：</ul><ul>1:04</ul>');//text modify
      setTimeout(function() {
      $('#screenContentWord').text('按綠色鍵開始執行');//text modify
    }, 2000);  
    }, 2000);
    $('#screenContentValue1').text('');
    $('.ps').css('color', 'black');
    $('#p8').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/treatready_tw.mp3');
    $('#nurseAudio').trigger('play');
  }

}

$.fn.multiline = function(text){
    this.text(text);
    this.html(this.html().replace(/\n/g,'<br/>'));
    return this;
}