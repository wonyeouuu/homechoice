var logAPI = function(index) {
	console.log(index);
	$.post("http://atc-event.com/homechoice/api/click/addrecord", index,
        function(data,status){
        	console.log(data);
        });
}