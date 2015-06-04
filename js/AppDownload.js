$(document).ready(function() {
    var code = parseInt(getUrlParameter('code'));//
    var device = parseInt(getUrlParameter('device'));//1: ios, 2: android
    console.log(code + device);
    $.post("http://atc-event.com/homechoice/api/source/logsource",
    {
        system: device,
        sId: code
    },
    function(data,status){
        console.log(status);
        if (device == 1) {
            window.location.href = "https://itunes.apple.com/app/homechoice/id895524929";    
        }
        else if (device == 2) {
            window.location.href = "https://play.google.com/store";       
        }
        else {
            alert("別亂掰參數好嗎？");
        }
        
    });
    
});
	


function getUrlParameter(sParam)
{
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) 
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) 
        {
            return sParameterName[1];
        }
    }
}