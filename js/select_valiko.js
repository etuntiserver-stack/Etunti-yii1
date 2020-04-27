$(document).ready(function(){

  $(".m3").multiselect({

	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Järjestelmänvalvojat"); ?>',
	selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
	allSelectedText: '<?php echo Yii::t("main", "Kaikki"); ?>',
	nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
	numberDisplayed: 0,

  });
  $(document).delegate(".color_valinta_vuosilomat","change",function(){
	var previnput = $(this).closest('.row').find('.m0').val();
	var previnput2 = $(this).closest('.row').find('.m0').val();
	var thiscolor = $(this).val();
	$(this).closest('.row').find('.color_set').val(previnput + '/' + previnput2 + '/#' + thiscolor);
  });
  $(document).delegate(".color_valinta_tyoajanmerkinta","change",function(){
	var previnput = $(this).closest('.row').find('.m0').val();
	var thiscolor = $(this).val();
	$(this).closest('.row').find('.color_set').val(previnput + '/#' + thiscolor);
  });
  $(document).delegate(".tallenna","click",function(){
	window.location.reload();
  });
  $(document).delegate(".paivita","click",function(){
	$(this).removeClass("btn-warning").addClass("btn-success");
	var select_type = $(this).attr("select_type");
        $.ajax({
           url: location.protocol + "//" + location.host + "/index.php/site/valiko_ajax",
           type: "POST",
           data: { "select_type" : select_type },
           success: function(data){
		//console.log(data)
		$("#result").html(data);
           }
        });
  });
  $(document).delegate(".muokkaSelectValikoja","click",function(){
	$(this).removeClass("btn-warning").addClass("btn-success");
	var forID 	= $(this).attr("for");
	var thisID 	= $(this).attr("id");
	var thisVal 	= $("#"+forID).val();
	var value2 = $('#m3_'+thisID+' :selected').map(function(){return $(this).val();}).get();
        $.ajax({
           url: location.protocol + "//" + location.host + "/index.php/site/valiko_ajax",
           type: "POST",
           data: {"muokkaSelects" : "true", "id" : thisID, "value" : thisVal, "value2" : value2, "select_type" : $("#select_type").val()},
           success: function(data){
		//console.log(data)
		$("#result").html(data);
           }
        });
  });
  $(document).delegate(".deleteFromSelect","click",function(){
	var thisID = $(this).attr("id").split("poista_");
	var select_type = $(this).attr("select_type");
	var variable = $(this).attr("variable");
        var r=confirm("Oletko varmaa?")
        if (r)
	{
        $.ajax({
           url: location.protocol + "//" + location.host + "/index.php/site/valiko_ajax",
           type: "POST",

           data: { "deleteFromSelect" : "true", "id" : thisID[1], "select_type" : select_type },
           success: function(html){
		$("#rivi_"+thisID[1]).remove();
           }

        });
	}
  });
  $(document).delegate(".uusi_valikkorivi","click",function(){
	var forID = $(this).attr("for");
	var select_type = $(this).attr("tyyppi");
	var thisVal = '';
	if(select_type == 'tyoajanmerkinta')
		thisVal = $("#"+forID).val() + '/';
	else if(select_type == 'vuosilomat')
		thisVal = $("#"+forID).val() + '/' + $("#"+forID).val() + '/black';
	else
		thisVal = $("#"+forID).val();
        $.ajax({
           url: location.protocol + "//" + location.host + "/index.php/site/valiko_ajax",
           type: "POST",
           data: {"uusiRiviSelects" : "true", "value" : thisVal, "select_type" : select_type},
           success: function(data){
		//console.log(data);
		$("#result").html(data);
		return false;
           }
        });
  });


});
