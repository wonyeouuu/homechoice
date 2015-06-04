//variables

$(document).ready(function(){
	$('#btn1a').on("click",function() {
  		$('#btn1').attr("src", "../resources/images/treat_intro/apd/APDnbot02_select.imageset/a.png");
  		$('#btn2').attr("src", "../resources/images/treat_intro/apd/APDnbot01.imageset/a.png");
  		$('#btn3').attr("src", "../resources/images/treat_intro/apd/APDnbot03.imageset/a.png");

  		$('#content').attr("src", "../resources/html/APD/images/Baxte_01B.png");
	});

	$('#btn2a').on("click",function() {
  		$('#btn1').attr("src", "../resources/images/treat_intro/apd/APDnbot02.imageset/a.png");
  		$('#btn2').attr("src", "../resources/images/treat_intro/apd/APDnbot01_select.imageset/a.png");
  		$('#btn3').attr("src", "../resources/images/treat_intro/apd/APDnbot03.imageset/a.png");

  		$('#content').attr("src", "../resources/html/APD/images/Baxte_02B.png");
	});		

	$('#btn3a').on("click",function() {
  		$('#btn1').attr("src", "../resources/images/treat_intro/apd/APDnbot02.imageset/a.png");
  		$('#btn2').attr("src", "../resources/images/treat_intro/apd/APDnbot01.imageset/a.png");
  		$('#btn3').attr("src", "../resources/images/treat_intro/apd/APDnbot03_select.imageset/a.png");

  		$('#content').attr("src", "../resources/html/APD/images/Baxte_03B.png");
	});		
});
