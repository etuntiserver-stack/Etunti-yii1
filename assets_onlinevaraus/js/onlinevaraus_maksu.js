$(document).ready(function(){
var step = 41;
var count = step;
function counter(){
    count += -1;


	var time = count*15;
	var minutes = "0" + Math.floor(time / 60);
	var seconds = "0" + (time - minutes * 60);
	jaljella =  minutes.substr(-2) + ":" + seconds.substr(-2);
	$('#countTimer').text('Aikajäljellä: '+jaljella);

    if(count < 1)
    window.location.href="index?keskeyta=true";
}
setInterval(counter, "15000");

});
