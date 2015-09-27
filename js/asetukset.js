  $(function() {
    $( ".datepicker" ).datetimepicker({
	timepicker:false,
	//mask:true,
	format:'d.m.Y'
    });

    $( ".timepicker" ).datetimepicker({
	datepicker:false,
	mask:true,
	format:'H:i',
    });

    $( ".datetimepicker" ).datetimepicker({
	mask:true,
	format:'d.m.Y H:i'
    });


  });


$(document).ready(function(){



});
