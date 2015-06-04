//variables
var camState = 1;

$(document).ready(function(){
  camTransfer(camState);
	setInterval(function() {camTransfer(camState)}, 500);

  $('#video1Btn').click(function() {
    $('.videos').show();
    $('#video1').show();
    $('#video1')[0].play();
    $('#mainView').hide();
  });

  $('#video2Btn').click(function() {
    $('.videos').show();
    $('#video2').show();
    $('#video2').css('margin-top', '6%');
    $('#previous').hide();
    $('#mainView').hide();
  });

  $('#video3Btn').click(function() {
    $('.videos').show();
    $('#video3').show();
    $('#video3').css('margin-top', '6%');
    $('#previous').hide();
    $('#mainView').hide();
  });

  $('#video4Btn').click(function() {
    $('.videos').show();
    $('#video4').show();
    $('#video4').css('margin-top', '6%');
    $('#previous').hide();
    $('#mainView').hide();
  });

  $('#video5Btn').click(function() {
    $('.videos').show();
    $('#video5').show();
    $('#video5').css('margin-top', '6%');
    $('#previous').hide();
    $('#mainView').hide();
  });



  //multiply here!
});

var camTransfer = function(camStateF) {
  if (camStateF%2 == 1) { //normal
    $('.cams').attr("src", "../resources/images/treat_intro/apdmovie/movie_normal.imageset/a.png");
  }
  else { //cam up
    $('.cams').attr("src", "../resources/images/treat_intro/apdmovie/movie_up.imageset/a.png"); 
  }

  camState = camState + 1;
}
