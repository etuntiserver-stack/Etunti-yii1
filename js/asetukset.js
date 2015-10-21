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

    $( ".timepicker_false" ).datetimepicker({
	datepicker:false,
	timepicker:false,
	mask:'99:99',
	format:'H:i',
    });

    $( ".datetimepicker" ).datetimepicker({
	mask:true,
	format:'d.m.Y H:i',

    });

    var myDateA = new Date($("#forDatepickerAlkuPVM").val());
    var myDateL = new Date($("#forDatepickerLoppuPVM").val());

    if(myDateA)
    {
    $( ".datetimepickerToteumaAlkuPVM" ).datetimepicker({
	mask:true,
	format:'d.m.Y H:i:s',
	defaultDate:myDateA,
    });
    }

    if(myDateL)
    {
    $( ".datetimepickerToteumaLoppuPVM" ).datetimepicker({
	mask:true,
	format:'d.m.Y H:i:s',
	defaultDate:myDateL,
    });
    }


  });


$(document).ready(function(){



});
