$(document).ready(function(){

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


    $(".datepickerMY").datepicker({
    	format: "yyyy-mm",
    	viewMode: "months", 
    	minViewMode: "months",
	language: 'fi',
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

    $(".orientation-start-picker").datepicker({
        locale: "fi",
        format: "dd.mm.yyyy",
        altField: "#Tyontekijat_orientation_start",
        altFormat: "yyyy-mm-dd"
    });

    $(".orientation-end-picker").datepicker({
        locale: "fi",
        format: "dd.mm.yyyy",
        altField: "#Tyontekijat_orientation_end",
        altFormat: "yyyy-mm-dd"
    });

});
