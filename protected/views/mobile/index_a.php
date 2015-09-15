  <table class="table table-striped" id="mobileTable">
  <thead>
  <tr>
  <th class="col-sm-1"><?php echo Yii::t('main', 'T'); ?></th>
  <th><?php echo Yii::t('main', 'ID'); ?></th>
  <th><?php echo Yii::t('main', 'Päivä'); ?></th>
  <th><?php echo Yii::t('main', 'Kartta'); ?></th>
  <th class="col-sm-4"><?php echo Yii::t('main', 'Työntekijä'); ?></th>

  <?php
  $tas = explode(",",Yii::app()->user->adminPaketti);
  if(in_array('2',$tas)) : 
  ?>
  <th><?php echo Yii::t('main', 'Työvuoroot'); ?></th>
  <?php endif; ?>

  <th><?php echo Yii::t('main', 'TAG'); ?></th>
  <th class="col-sm-3"><?php echo Yii::t('main', 'Osoite/Matka'); ?></th>
  <th><?php echo Yii::t('main', 'Aloitus'); ?></th>
  <th><?php echo Yii::t('main', 'Lopetus'); ?></th>
  <th><?php echo Yii::t('main', 'Kesto'); ?></th>
  <th><center><?php echo Yii::t('main', 'M'); ?></center></th>
  <th><center><?php echo Yii::t('main', 'P'); ?></center></th>
  </tr>
  </thead>
  <?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
  	'template'=>'{items}<table class="table table-striped table-condensed"></table><br/>{pager}',


	'pager' => array(
           'firstPageLabel'=>'<<',
           'prevPageLabel'=>'< Edellinen',
           'nextPageLabel'=>'Seuraava >',
           'lastPageLabel'=>'>>',
           //'maxButtonCount'=>'10',
           'header'=>'<h3>Siirry sivulle:</h3>',
           'cssFile'=>false,
       ), 

  )); ?>
  </table>

  <div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>
  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tvuoroot.js"></script>
  <?php //Yii::app()->clientScript->registerPackage('tyovuoroot'); ?>

<script type="text/javascript">
$(document).ready(function(){


$(".vietyovuoroon").click(function(){

	var thisVal = $(this).attr("pvmtid");
	window.open(location.protocol + "//" + location.host + '/index.php/tyovuoroot?pvmtid='+thisVal+'#'+thisVal);

});

$(".pvmupdate").blur(function(){

	var st = $(this).attr("status");
	var forTxt = $(this).attr("for");
	var thisID = $(this).attr("id").split("_");
	var request = $(this).attr("request");
	var thisVal = $(this).val().replace("T"," ");

	if((request == 'loppui') && (st == '1') && (thisVal != ''))
	status = 3;
	else
	status = st;

	if(thisVal == ''){
	alert("Error");
	return false;
	}
	
        $.ajax({
           url: 'updatetime',
           type: "POST",
           data: { "id" : thisID, "request" : request, "value" : thisVal, "status" : status },
           success: function(html){

		var newText = $('#'+thisID[0]+'_'+thisID[1]).val().split("T");
		$('#'+forTxt).html(newText[1]).addClass("text-success");
		$('#'+thisID[0]+'_'+thisID[1]).removeClass("btn-default").addClass("text-success");

		if(request == 'aloitan')
		  $('#alshow_'+thisID[1]).hide('slow');

		if(request == 'loppui')
		  $('#ltshow_'+thisID[1]).hide('slow');

        	$.ajax({
	           url: 'kesto?id='+thisID[1],
	           success: function(data){
		      	console.log(data);
			$('#kesto_'+thisID[1]).html("<strong>"+data+"</strong>").addClass("text-success");	
	           }
	        });



           }
        });

});

$(".openkohde").click(function(){

	var thisID = $(this).attr("id");
	var forid = $(this).attr("for");
	var riviid = $(this).attr("for").split("_");
	
        $.ajax({
           url: 'showkohteet/',
           type: "POST",
           data: { "id" : riviid[1], "thisID" : thisID },
           success: function(html){
		$('#'+forid).html(html);
           }
        });

});

$(".poistaKohde").click(function(){

	var forRivi = $(this).attr("for");
	var riviid = $(this).attr("for").split("_");
	if(confirm('Oletko varmaa?'))
	{
        $.ajax({
           url: 'poistaKohde',
           type: "POST",
           data: { "id" : riviid[1] },
           success: function(html){
		$("#"+forRivi).remove();
           }
        });
	}

});

$(".etsi_tekijan_nimi").change(function(){
	var thisVal = $(this).val();
        $.ajax({
           url: "index",
	   type:'POST',
	   data: { "etsi_tekijan_nimi" : thisVal },
           success: function(html){
		window.location.reload();
           }
        });
});

$(".etsi_kohteet").change(function(){
	var thisVal = $(this).val();
        $.ajax({
           url: "index",
	   type:'POST',
	   data: { "etsi_kohteet" : thisVal },
           success: function(html){
		window.location.reload();
           }
        });
});

$(".etsi_pvm").on('blur', function() {
	var thisVal = $(this).val();
	if(!thisVal)
	var thisVal = 'kaikki';

        $.ajax({
           url: "index",
	   type:'POST',
	   data: { "etsi_pvm" : thisVal },
           success: function(html){
		window.location.reload();
           }
        });
});

});
</script>
