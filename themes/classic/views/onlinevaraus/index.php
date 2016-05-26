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
   </div><div class="form-group col-sm-offset-4">
	<h2>Online-Varaus</h2>
   </div>
 </div>
</div>


<!--
<div class="">
<ul id="green_and_orange_step_menu">
<li class="first aktiivinen"><?php echo CHtml::link('PALVELU','index'); ?></li>
<li class="passivinen"><?php echo CHtml::link('AIKA','aika'); ?><span class="akt"></span></li>
<li class="passivinen"><?php echo CHtml::link('OSOITE','osoite'); ?><span class="pas"></span></li>
<li class="passivinen"><?php echo CHtml::link('MAKSU','maksu'); ?><span class="pas"></span></li>
</ul>
</div>
-->


<ul class="steps expanded even-4">
    <li class="active"><?php echo CHtml::link('PALVELU','index'); ?></li>
    <li class="disabled"><?php echo CHtml::link('AIKA','aika'); ?></li>
    <li class="disabled"><?php echo CHtml::link('OSOITE','osoite'); ?></li>
    <li class="disabled"><?php echo CHtml::link('MAKSU','maksu'); ?></li>
</ul>

<br><br>




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

	<div class="row">
	  <div class="col-sm-4 col-sm-offset-4">

		<h4>Valitse palvelu</h4>
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
	</div>
<br>
	<div class="row" id="huoneistonkoko">
	  <div class="col-sm-4 col-sm-offset-4">
	   <h4>Valitse huoneiston koko</h4>
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


<br>
	<div class="row" id="lispalvimg">
	  <div class="col-sm-4 col-sm-offset-4">
		<h4>Haluaisitko lisäpalveluita?</h4>
	   	<img src="<?php echo Yii::app()->request->baseUrl; ?>/ylapalkki/lisapalv.png">
	  </div>
	</div>
<br>



	<div id="lisapalvelulista">
	<p><h4>Valitse lisäpalvelu</h4></p>
	<?php
       	$criteria = new CDbCriteria();
       	$criteria->condition = " palvelu=1 ";
	$onlineTuotteet = OnlinevarausTuotteet::model()->findAll($criteria);

	echo '<div class="row">';
	foreach($onlineTuotteet as $data)
	{
	  $checked = '';
	  if(isset($_SESSION['onlinevaraus']['lisapalvelut']) and in_array($data->id, $_SESSION['onlinevaraus']['lisapalvelut'], true))
	  $checked = 'checked';
	  echo 
	  '
	  <div class="col-sm-6">
	   <table class="tblisat">
	    <tr>
	     <td width=1>
		<input class="checkbox" for="'.$data->id.'" type="checkbox" '.$checked.'>
	     </td><td>
	        <a href="#" data-toggle="modal" data-target="#myModal_'.$data->id.'">'.$data->nimike.'</a>


<!-- Modal -->
<div id="myModal_'.$data->id.'" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">'.Yii::t('main', 'Selitysteksti').'</h4>
      </div>
      <div class="modal-body">
        <p>'.$data->selitysteksti.'</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


	     </td>
	    </tr>
	   </table>
	  </div>
	  ';
	}
	echo '</div>';
	?>
	</div>
   </center>
  </div>
 </div>
 <div class="col-sm-4">
 
	      <div id="panGetContent"></div>

	      <div id="alennuskoodi">
		<div class="boxes-info">
		  <center><h4><?php echo Yii::t('main', 'Alennuskoodi'); ?></h4>
			<form class="input-group">
			<input type="text" class="form-control form-group input-lg">
			<span class="input-group-btn">
			  <input type="submit" class="btn btn-lg btn-group btn-warning" value="<?php echo Yii::t('main', 'Aktivoi'); ?>">
			</span>	
			</form>
		  </center>
		</div>
	      </div>

	      <div>
		<div class="boxes-info sininen">
		  <center><h4><?php echo Yii::t('main', 'Laatu ja luotettavuus'); ?></h4>

		  </center>
		</div>
	      </div>

	      <div>
		<div class="boxes-info sininen">
		  <center><h4><?php echo Yii::t('main', 'Takuu ja turvallisuus'); ?></h4>

		  </center>
		</div>
	      </div>

	      <div>
		<div class="boxes-info sininen">
		  <center><h4><?php echo Yii::t('main', 'Asiakaspalvelu'); ?></h4>

		  </center>
		</div>
	      </div>

 </div>
</div>

<br>

<div class="">
  <div class="boxes-info sininen">
	<center><h4><?php echo Yii::t('main', 'Arvio siivouksesta'); ?></h4>

	</center>
  </div>
</div>

<a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/onlinevaraus/rekisteriseloste" target="_blank"><?php echo Yii::t('main','Onlinevaraus tietosuoja- ja rekisteriseloste'); ?> </a>

</div>



<script type="text/javascript">
$(document).ready(function(){


$( "#lispalvimg" ).hover(
  function() {
	$('#lispalvimg img').replaceWith('<img src="<?php echo Yii::app()->request->baseUrl; ?>/ylapalkki/haluan.png">');
  }, function() {
	$('#lispalvimg img').replaceWith('<img src="<?php echo Yii::app()->request->baseUrl; ?>/ylapalkki/lisapalv.png">');
  }
);

$("#lispalvimg").click(function(){
	$('#lisapalvelulista').show('slow');
});

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
			$('#huoneistonkoko').show('slow');
			$('#lispalvimg').show('slow');
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



