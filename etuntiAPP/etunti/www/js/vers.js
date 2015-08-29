$(document).ready(function(){

function ku (){
    var ua = navigator.userAgent;
    var checker = {
      iphone: ua.match(/(iPhone|iPod|iPad)/),
      blackberry: ua.match(/BlackBerry/),
      android: ua.match(/Android/)
    };
    if (checker.android){
        $("#result2").html(checker.android);
    }
    else if (checker.iphone){
        $("#result2").html(checker.iphone);
    }
    else if (checker.blackberry){
        $("#result2").html(checker.blackberry);
    }
    else {
        $("#result2").html('Unknow type');
    }
}

ku();

/*
  var type = $("#result2").html();

	$("#result").append("Device type: "+type+"\n");

  if(type == 'Android')
  {
	$("#result").append('ok');
  }
*/
















});
