//variables
var horizonalState = 0;
var verticalState = 0;

$(document).ready(function(){
 //  camTransfer(camState);
	// setInterval(function() {camTransfer(camState)}, 500);

  $('#machineBtn1').click(function() { //3D
    horizonalState = 0;
    verticalState = 0;
    $('#content').attr("src", "../resources/html/APD_3D/images/img_0_0_0.jpg");
    $('.spinBtns').show();
  });

  $('#machineBtn2').click(function() { //front
    $('#content').attr("src", "../resources/html/APD_3D/images/font.jpg");
    $('.spinBtns').hide();
  });

  $('#machineBtn3').click(function() { //back
    $('#content').attr("src", "../resources/html/APD_3D/images/back.jpg");
    $('.spinBtns').hide();
  });

  $('#machineBtn4').click(function() { //pipe
    $('#content').attr("src", "../resources/html/APD_3D/images/pipe.jpg");
    $('.spinBtns').hide();
  });

  $('#spinBtnLeft').click(function() {
    threeDimensionSpinLeft(horizonalState, verticalState);
  });

  $('#spinBtnRight').click(function() {
    threeDimensionSpinRight(horizonalState, verticalState);
  });

  $('#spinBtnUp').click(function() {
    threeDimensionSpinUp(horizonalState, verticalState);
  });

  $('#spinBtnDown').click(function() {
    threeDimensionSpinDown(horizonalState, verticalState);
  });

  //multiply here!
});

var threeDimensionSpinLeft = function(horizontalStateF, verticalStateF) {
  var p1 = mod(24, horizontalStateF + 1);
  var p2 = verticalStateF;
  var path = '../resources/html/APD_3D/images/img_0_' + p2 + '_' + p1 + '.jpg';
  $('#content').attr("src", path);
  horizonalState = horizonalState + 1;
}

var threeDimensionSpinRight = function(horizontalStateF, verticalStateF) {
  var p1 = mod(24, horizontalStateF - 1);
  var p2 = verticalStateF;
  var path = '../resources/html/APD_3D/images/img_0_' + p2 + '_' + p1 + '.jpg';
  $('#content').attr("src", path);
  horizonalState = horizonalState - 1;
}

var threeDimensionSpinUp = function(horizontalStateF, verticalStateF) {
  var p1 = mod(24, horizontalStateF);
  if (verticalStateF == 0) {
    var p2 = verticalStateF + 1;
    verticalState = verticalState + 1;
  }
  else { // 1
    var p2 = verticalStateF;
  }
  var path = '../resources/html/APD_3D/images/img_0_' + p2 + '_' + p1 + '.jpg';
  $('#content').attr("src", path);
}

var threeDimensionSpinDown = function(horizontalStateF, verticalStateF) {
  var p1 = mod(24, horizontalStateF);
  if (verticalStateF == 0) {
    var p2 = verticalStateF;
  }
  else { // 1
    var p2 = verticalStateF - 1;
    verticalState = verticalState - 1;
  }
  var path = '../resources/html/APD_3D/images/img_0_' + p2 + '_' + p1 + '.jpg';
  $('#content').attr("src", path);
}
//modify mod function
function mod(n, m) {
        return ((m % n) + n) % n;
}
