<?php
/* @var $this OnlinevarausController */
/* @var $dataProvider CActiveDataProvider */
$asetukset = Asetukset::model()->findbypk(1);
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/onlinevaraus_2.css">

<div class="container-fluid">
<br>

<div class="row">
 <div class="form-inline col-sm-12">
   <div class="form-group">
	<img src="<?php echo $asetukset->logon_polkku; ?>" height="<?php echo $asetukset->logon_korkeus; ?>">
   </div><div class="form-group col-sm-offset-3">
	<h2>Online-Varaus</h2>
   </div>
 </div>
</div>



<div class="">
<ul id="green_and_orange_step_menu">
<li class="first aktiivinen"><?php echo CHtml::link('PALVELU','index'); ?></li>
<li class="passivinen"><?php echo CHtml::link('AIKA','aika'); ?><span class="akt"></span></li>
<li class="passivinen"><?php echo CHtml::link('OSOITE','osoite'); ?><span class="pas"></span></li>
<li class="passivinen"><?php echo CHtml::link('MAKSU','maksu'); ?><span class="pas"></span></li>
</ul>
</div>

<br>


<style>
.checkbox {
   width: 80px;
   height: 30px;
   margin: auto;
   position: relative;
   background: #fff;
   border: none;
   border-radius: 2px;
   -webkit-border-radius: 2px;
   -moz-border-radius: 2px;
}
</style>

<?php 
$asetukset = Asetukset::model()->findbypk(1);
if(isset($asetukset->checkout_id) and !empty($asetukset->checkout_id) and !empty($asetukset->checkout_salasana))
{

} else {
		echo '<h3 class="alert alert-danger"><center>Checkout tunnukset puuttuu!</center></h3>';
}
?>

<div class="row">
 <div class="col-sm-8">
  <div class="boxes-info">
   <center>
	<p>
		<h4>Valitse palvelu ja huoneiston koko</h4>
	</p>

	<div class="row">
	  <div class="col-sm-4 col-sm-offset-1">
		<select class="form-control input-lg" id="palvelu">
		<?php
		if(isset($_SESSION['onlinevaraus']['paapalvelu']))
		{
		  $onlineTuotteet = OnlinevarausTuotteet::model()->findbypk($_SESSION['onlinevaraus']['paapalvelu']);
		    if(isset($onlineTuotteet->id))
		  	echo '<option value="'.$onlineTuotteet->nimike.'">'.$onlineTuotteet->nimike.'</option>';
		}
		?>

		<option value="">Valitse palvelu</option>

		<?php
	       	$criteria = new CDbCriteria();
	       	$criteria->condition = " palvelu=0 ";
	       	$criteria->group = " nimike ";
		$onlineTuotteet = OnlinevarausTuotteet::model()->findAll($criteria);
		foreach($onlineTuotteet as $data)
		{
		  echo 
		  '
			<option value="'.$data->nimike.'">'.$data->nimike.'</option>
		  ';
		}
		?>
		</select>
	  </div>

	  <div class="col-sm-4 col-sm-offset-1">
	    <div id="nelioValikko">
		<select class="form-control input-lg" id="nelio">
		<?php
		if(isset($_SESSION['onlinevaraus']['paapalvelu']))
		{
		  $onlineTuotteet = OnlinevarausTuotteet::model()->findbypk($_SESSION['onlinevaraus']['paapalvelu']);
		    if(isset($onlineTuotteet->id))
		  	echo '<option value="'.$onlineTuotteet->nelio.'">'.$onlineTuotteet->nelio.'</option>';
		}
		?>
		<option value="">Huoneisten koko m²</option>
		<option value="">Palvelu puuttuu</option>
		</select>
	    </div>
	  </div>
	</div>


	<p>
		<h4>Valitse lisäpalvelu</h4>
	</p>
	<div id="lisapalvelulista">
	<?php
       	$criteria = new CDbCriteria();
       	$criteria->condition = " palvelu=1 ";
	$onlineTuotteet = OnlinevarausTuotteet::model()->findAll($criteria);
	foreach($onlineTuotteet as $data)
	{
	  $checked = '';
	  if(isset($_SESSION['onlinevaraus']['lisapalvelut']) and in_array($data->id, $_SESSION['onlinevaraus']['lisapalvelut'], true))
	  $checked = 'checked';
	  echo 
	  '
		<div class="row">
		    <div class="col-md-4">
			<input class="checkbox pull-right" for="'.$data->id.'" type="checkbox" '.$checked.'>
		    </div><div class="col-md-8 text-left">
				<div class="row">'.$data->nimike.'</div>
				<div class="row small">'.$data->selitysteksti.'</div>
		    </div>
		</div>
		<br>
	  ';
	}
	?>
	</div>
   </center>
  </div>
 </div>
 <div class="col-sm-4">
 
	      <div id="panGetContent"></div>
 </div>
</div>

<br>


<a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/onlinevaraus/rekisteriseloste" target="_blank"><?php echo Yii::t('main','Onlinevaraus tietosuoja- ja rekisteriseloste'); ?> </a>
</div>



<script type="text/javascript">
$(document).ready(function(){

$("#palvelu").change(function(){

   clearAll();

   var palvelu = $(this).val();
   if(palvelu)
   {
   $.ajax({
	url: 'palvelu_ajax',
	data:{ "word" : palvelu },
	type:'POST',
	success:function(data){
		//console.log(data);
		if(data)
		{
			$('#nelioValikko').html(JSON.parse(data));
			checker();

			$("#nelio").change(function(){
			checker();
			});

		}
   	},
	error:function(data){
		console.log(data);
    	}
    });
    } else {
			checker();
    }

});


function clearAll(){

   $.ajax({
	url: 'palvelu_ajax',
	data:{ "clear" : "all" },
	type:'POST',
	success:function(data){
		//console.log(data);
   		$('.checkbox').removeAttr('checked');
		$('#panGetContent').html('');
   	},
	error:function(data){
		console.log(data);
    	}
    });

}



checker();
function checker(){

	var palvelu = $("#palvelu").val();
	var nelio = $("#nelio").val();

	if(palvelu && nelio)
	{
	console.log(palvelu+ " " + nelio);

   $.ajax({
	url: 'palvelu_save_ajax',
	data:{ "palvelu" : palvelu, "nelio" : nelio },
	type:'POST',
	success:function(data){
		//console.log(data);
		if(data)
		{
			$('#panGetContent').html(JSON.parse(data));
			lisat();
		}
   	},
	error:function(data){
		console.log(data);
    	}
    });


	}
}


function lisat()
{
$(".checkbox").click(function(){

   var lisapalveluID = $(this).attr("for");
   var checked = '';
   if ($(this).is(':checked')) {
	checked = 1;
   } else {
	checked = 0;
   }

   if(lisapalveluID)
   {
   $.ajax({
	url: 'lisat_ajax',
	data:{ "id" : lisapalveluID, "checked" : checked },
	type:'POST',
	success:function(data){
		console.log(data);
		if(data)
		    checker();

   	},
	error:function(data){
		console.log(data);
    	}
    });
    }

});
}



});
</script>



