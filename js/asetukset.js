
  $(function() {


/*
    $( ".datepicker" ).datepicker({
	format:'yyyy-mm-dd',
	language: 'fi'
    });
*/
    $( ".datepicker" ).datetimepicker({
         format : 'YYYY-MM-DD',
	 locale: 'fi',
    });

   $( ".datepickerFI" ).datetimepicker({
         format : 'DD.MM.YYYY',
	 locale: 'fi',
    });

    $( ".timepicker" ).datetimepicker({
	format:'hh:mm',
    });

    $( ".datetimepicker" ).datetimepicker({
         format : 'DD.MM.YYYY HH:mm',
	 locale: 'fi',
    });

    $( ".datetimepicker2" ).datetimepicker({
         format : 'YYYY.MM.DD HH:mm',
    });


  });



$(document).ready(function(){



});
