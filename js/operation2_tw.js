//variables
var state = 1;
var offState = 1;
var goState = 1;
var nextState = 1;
var lastState = 1;
var restartState = 1;
var connectState = 1;
var connect2State = 1;

$(document).ready(function(){
  mainTransfer();

  $('#btnPower').click(function() {
    if (state == 1) {
      clearInterval(intervalOff);
      $('#btnImgPower').attr("src", "../resources/images/operatorhelp/PowerOn.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }
  });

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

    if (state == 2) {
      clearInterval(intervalGo);
      $('#btnImgGo').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelGo.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }
    else if(state == 7) {
      clearInterval(intervalGo);
      $('#btnImgGo').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelGo.imageset/a.png");
      $('#introImg').show();//introImg modify
      state = state + 1;
      mainTransfer();
    }
    else if(state == 11) {
      clearInterval(intervalGo);
      clearInterval(intervalConnect);
      $('#btnImgGo').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelGo.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }
    else if(state == 15) {
      clearInterval(intervalGo);
      clearInterval(intervalConnect2);
      $('#btnImgGo').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/PSPanelGo.imageset/a.png");
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
    else if(state == 8) {
      clearInterval(intervalNext);
      $('#btnImgNextStep').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/content_menu_next.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }
    else if(state == 9) {
      clearInterval(intervalConnect);
      clearInterval(intervalNext);
      $('#btnImgNextStep').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/content_menu_next.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }
    else if(state == 10) {
      clearInterval(intervalConnect);
      clearInterval(intervalNext);
      $('#btnImgNextStep').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/content_menu_next.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }
    else if(state == 12) {
      clearInterval(intervalNext);
      $('#btnImgNextStep').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/content_menu_next.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }
    else if(state == 13) {
      clearInterval(intervalNext);
      clearInterval(intervalConnect2);
      $('#btnImgNextStep').attr("src", "../resources/images/operatorhelp/prescriptsetmenu/prescriptset/content_menu_next.imageset/a.png");
      state = state + 1;
      mainTransfer();
    }
    else if(state == 14) {
      clearInterval(intervalConnect2);
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

var connectTransfer2 = function(stateF) {
  if (stateF%2 == 1) {
    $('#screenContentWord').text('連接透析液袋');//text modify
  }
  else {
    $('#screenContentWord').text('打開管夾');//text modify
  }

  connectState = connectState + 1;
}

var connect2Transfer2 = function(stateF) {
  if (stateF%2 == 1) {
    $('#screenContentWord').text('檢查病人端管路');//text modify
  }
  else {
    $('#screenContentWord').text('連接你自己');//text modify
  }

  connect2State = connect2State + 1;
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

var mainTransfer = function() {
  console.log('mainTransfer' + state);
  if (state == 1) {
    intervalOff = setInterval(function() {offTransfer2(offState)}, 400);//shining
    $('#contentWord').text('連接電源，開啟開關');//text modify
    $('#screenContentWord').text('');//screen text modify
    $('#introImg').show();//introImg modify
    $('#introImg').attr('src', '../resources/images/operatorhelp/treatbeforeprepare/TBP_Snap_Home.imageset/a.png');//introImg modify
    $('.ps').css('color', 'black');
    $('.ps').css('opacity', '0.3');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/poweron_tw.mp3');
    $('#nurseAudio').trigger('play');

  }

  else if (state == 2) {
    intervalGo = setInterval(function() {goTransfer2(goState)}, 400);//shining
    $('#contentWord').text('');//text modify
    $('#screenContentWord').text('啟動標準模式');//text modify
    setTimeout(function() {
      $('#screenContentWord').text('按綠色鍵開始執行');//text modify
    }, 1000);
    $('#introImg').show();//introImg modify
    $('#introImg').attr('src', '../resources/images/operatorhelp/treatbeforeprepare/TBP_Snap_Home.imageset/a.png');//introImg modify
    $('.ps').css('opacity', '1');   
  }

  else if (state == 3) {
    intervalNext = setInterval(function() {nextTransfer2(nextState)}, 400);//shining
    $('#contentWord').text('將全自動腹膜透析管組從包裝袋取出，關上所有管夾(4頭管路應有6個管夾)');//text modify
    $('#screenContentWord').text('裝置管組');//text modify
    $('#introImg').show();//introImg modify
    $('#introImg').attr('src', '../resources/images/operatorhelp/treatbeforeprepare/TPB_Snap_PrepareLine1.imageset/a.png');//introImg modify
    $('.ps').css('opacity', '1');  
    $('.ps').css('color', 'black');
    $('#p1').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/closeall_tw.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 4) {
    intervalNext = setInterval(function() {nextTransfer2(nextState)}, 400);//shining
    $('#contentWord').text('打開管組門(將把手向上拉)，置入透析卡匣，再將管組門關上並拉下把手。');//text modify
    $('#screenContentWord').text('裝置管組');//text modify
    $('#introImg').show();//introImg modify
    $('#introImg').attr('src', '../resources/images/operatorhelp/treatbeforeprepare/TPB_Snap_PrepareLine2.imageset/a.png');//introImg modify
    $('.ps').css('opacity', '1');  
    $('.ps').css('color', 'black');
    $('#p1').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/open_tw.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 5) {
    intervalNext = setInterval(function() {nextTransfer2(nextState)}, 400);//shining
    $('#contentWord').text('打開管組門(將把手向上拉)，置入透析卡匣，再將管組門關上並拉下把手。');//text modify
    $('#screenContentWord').text('裝置管組');//text modify
    $('#introImg').show();//introImg modify
    $('#introImg').attr('src', '../resources/images/operatorhelp/treatbeforeprepare/TPB_Snap_PrepareLine3.imageset/a.png');//introImg modify
    $('.ps').css('opacity', '1');  
    $('.ps').css('color', 'black');
    $('#p1').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/open_tw.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 6) {
    intervalNext = setInterval(function() {nextTransfer2(nextState)}, 400);//shining
    $('#contentWord').multiline('放置管組架。\na. 將管組架的長插槽掛到管組門上方的釣鉤上。\nb. 將管組架的下方插槽扣在管組門正面的支柱上。\nc. 確認病人端管路的末端已正確地置入管組架內。');//text modify
    $('#screenContentWord').text('裝置管組');//text modify
    $('#introImg').show();//introImg modify
    $('#introImg').attr('src', '../resources/images/operatorhelp/treatbeforeprepare/TPB_Snap_PrepareLine4.imageset/a.png');//introImg modify
    $('.ps').css('opacity', '1');  
    $('.ps').css('color', 'black');
    $('#p1').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/put_tw.mp3');
    $('#nurseAudio').trigger('play');
  }
  else if (state == 7) {
    intervalGo = setInterval(function() {goTransfer2(goState)}, 400);//shining
    $('#contentWord').text('取出引流管路及連接引流系統。');//text modify
    $('#screenContentWord').text('裝置管組');//text modify
    $('#introImg').hide();//introImg modify
    $('.ps').css('opacity', '1');  
    $('.ps').css('color', 'black');
    $('#p1').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/takeout_tw.mp3');
    $('#nurseAudio').trigger('play');
  }
  else if (state == 8) {
    intervalNext = setInterval(function() {nextTransfer2(nextState)}, 400);//shining
    $('#contentWord').text('約2分鐘後');//text modify
    $('#screenContentWord').text('機器自我測試');//text modify
    $('#introImg').hide();//introImg modify
    $('.ps').css('opacity', '1');  
    $('.ps').css('color', 'black');
    $('#p2').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/2min_tw.mp3');
    $('#nurseAudio').trigger('play');
  }
  else if (state == 9) {
    intervalNext = setInterval(function() {nextTransfer2(nextState)}, 400);//shining
    $('#contentWord').multiline('請按照腹膜透析護士教導的步驟將適當的管路連接至透析液袋。\n註：\na)紅色管夾之加溫袋管路應連接至加溫袋\nb)加溫袋須蓋住加溫板上的感應鈕\nc)如進行最末袋注入時使用不同濃度的透析液，則必須將藍色管夾之最末袋管路連接至該透析液袋中。');//text modify
    $('#screenContentWord').text('連接透析液袋');//text modify
    setTimeout(function() {
      intervalConnect = setInterval(function() {connectTransfer2(connectState)}, 1000);//shining
    }, 1000);
    $('#introImg').hide();//introImg modify
    $('.ps').css('opacity', '1');  
    $('.ps').css('color', 'black');
    $('#p3').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/followstep_tw.mp3');
    $('#nurseAudio').trigger('play');
  }
  else if (state == 10) {
    intervalNext = setInterval(function() {nextTransfer2(nextState)}, 400);//shining
    $('#contentWord').text('將有連接透析液之管夾打開。');//text modify
    intervalConnect = setInterval(function() {connectTransfer2(connectState)}, 1000);//shining
    $('#introImg').show();//introImg modify
    $('#introImg').attr('src', '../resources/images/operatorhelp/treatbeforeprepare/RoundLineConfirm_1.imageset/a.png');//introImg modify   
    $('.ps').css('opacity', '1');  
    $('.ps').css('color', 'black');
    $('#p3').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/openall_tw.mp3');
    $('#nurseAudio').trigger('play');
  }
  else if (state == 11) {
    intervalGo = setInterval(function() {goTransfer2(goState)}, 400);//shining
    $('#contentWord').text('將病人端管路上之管夾打開，確認病人端管路的末端已正確地置入管組架內。');//text modify
    $('#screenContentWord').text('打開管夾');//text modify
    $('#introImg').show();//introImg modify
    $('#introImg').attr('src', '../resources/images/operatorhelp/treatbeforeprepare/RoundLineConfirm_2.imageset/a.png');//introImg modify   
    $('.ps').css('opacity', '1');  
    $('.ps').css('color', 'black');
    $('#p3').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/confirm_tw.mp3');
    $('#nurseAudio').trigger('play');
  }
  else if (state == 12) {
    intervalNext = setInterval(function() {nextTransfer2(nextState)}, 400);//shining
    $('#contentWord').text('當排氣完畢後...');//text modify
    $('#screenContentWord').text('排氣');//text modify
    $('#introImg').hide();//introImg modify
    $('.ps').css('opacity', '1');  
    $('.ps').css('color', 'black');
    $('#p4').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/exhaust_tw.mp3');
    $('#nurseAudio').trigger('play');
  }
  else if (state == 13) {
    intervalNext = setInterval(function() {nextTransfer2(nextState)}, 400);//shining
    $('#contentWord').text('確認病人端管路已正確完成排氣。如透析液液位與病人端管路連接器末端仍有一段距離，請為病人端管路重新排氣一次。請參閱位於簡易操作手冊中第7-9頁的<病人端重新排氣>程序。');//text modify
    intervalConnect2 = setInterval(function() {connect2Transfer2(connect2State)}, 1000);//shining
    $('#introImg').hide();//introImg modify
    $('.ps').css('opacity', '1');  
    $('.ps').css('color', 'black');
    $('#p5').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/manual_tw.mp3');
    $('#nurseAudio').trigger('play');
  }
  else if (state == 14) {
    intervalNext = setInterval(function() {nextTransfer2(nextState)}, 400);//shining
    $('#contentWord').text('將迷你輸液管連接至病人端管路。請按照腹膜透析護士教導的步驟進行。');//text modify
    intervalConnect2 = setInterval(function() {connect2Transfer2(connect2State)}, 1000);//shining
    $('#introImg').show();//introImg modify
    $('#introImg').attr('src', '../resources/images/operatorhelp/treatbeforeprepare/OpenPatient1.imageset/a.png');//introImg modify   
    $('.ps').css('opacity', '1');  
    $('.ps').css('color', 'black');
    $('#p6').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/minifollowstep_tw.mp3');
    $('#nurseAudio').trigger('play');
  }
  else if (state == 15) {
    intervalGo = setInterval(function() {goTransfer2(goState)}, 400);//shining
    $('#contentWord').text('開啟迷你輸液管開關，並確定病人端管路上之管夾已打開。');//text modify
    intervalConnect2 = setInterval(function() {connect2Transfer2(connect2State)}, 1000);//shining
    $('#introImg').show();//introImg modify
    $('#introImg').attr('src', '../resources/images/operatorhelp/treatbeforeprepare/OpenPatient2.imageset/a.png');//introImg modify   
    $('.ps').css('opacity', '1');  
    $('.ps').css('color', 'black');
    $('#p6').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/miniopen_tw.mp3');
    $('#nurseAudio').trigger('play');
  }

  else if (state == 16) {
    intervalRestart = setInterval(function() {restartTransfer2(restartState)}, 400);//shining
    $('#contentWord').text('治療正式開始。');//text modify
    $('#screenContentWord').text('0週期引流');//text modify
    $('#introImg').hide();//introImg modify
    $('#btnImgRestart').attr('src', '../resources/images/operatorhelp/MENU_Select.imageset/a.png');//restart modify   
    $('.ps').css('opacity', '1');  
    $('.ps').css('color', 'black');
    $('#p7').css('color', 'red');

    $('#nurseAudio').attr('src', '../resources/audio/nurse/ch/treatstart_tw.mp3');
    $('#nurseAudio').trigger('play');
  }
}

$.fn.multiline = function(text){
    this.text(text);
    this.html(this.html().replace(/\n/g,'<br/>'));
    return this;
}