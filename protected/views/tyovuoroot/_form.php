<?php
/* @var $this TyovuorootController */
/* @var $model Tyovuoroot */
/* @var $form CActiveForm */

if(isset($_POST['pvm']))
  $model->pvm = date("Y-m-d",strtotime($_POST['pvm']));
else
  $model->pvm = date("Y-m-d",strtotime($model->pvm));


if(isset($_POST['tid']))
  $model->tid = $_POST['tid'];

  $ohje = '';
$m = Kohteet::model()->findbypk($model->kohde);
if(isset($m->toimenpiteet))
  $ohje = $m->toimenpiteet;
?>

<div class="row form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tyovuoroot-form',
	'enableAjaxValidation'=>false,

)); ?>


	<?php echo $form->errorSummary($model); ?>
	<?php echo $form->hiddenField($model,'id',array('id'=>$model->id)); ?>
	<?php echo $form->hiddenField($model,'tid'); ?>
	<?php echo $form->hiddenField($model,'ruokatauko'); ?>
	<?php echo $form->hiddenField($model,'alku_r'); ?>
	<?php echo $form->hiddenField($model,'kesto'); ?>
	<?php echo $form->error($model,'tid'); ?>

<div class="row">
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'pvm'); ?>
		<?php echo $form->dateField($model,'pvm',array('size'=>20,'maxlength'=>20,'class'=>'form-control'));//,'readonly'=>'yes' ?>
		<?php echo $form->error($model,'pvm'); ?>
  </div>
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'alku'); ?>
		<input type="time" name="Tyovuoroot[alku]" class="form-control laske" id="alku" value="<?php echo $model->alku; ?>">
  </div>
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'loppu'); ?>
		<input type="time" name="Tyovuoroot[loppu]" class="form-control laske" id="loppu" value="<?php echo $model->loppu; ?>">
  </div>
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'pituus'); ?>
		<input type="time" name="Tyovuoroot[pituus]" id="pituus" class="form-control" value="<?php echo $model->pituus; ?>">
  </div>
</div>

<div class="row">
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'tyoajanlaatu'); ?>
		<?php
        	$list = CHtml::listData(Tyontekijat::model()->findAll(array('order' => 'tekijan_nimi')), 'id', 'id');
        	echo $form->dropDownList($model, 'tyoajanlaatu', $list,array('class'=>'form-control'));
        	?>
  </div>
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'tyoajanmerkinta'); ?>
		<?php
        	$list = CHtml::listData(Tyontekijat::model()->findAll(array('order' => 'tekijan_nimi')), 'id', 'id');
        	echo $form->dropDownList($model, 'tyoajanmerkinta', $list,array('class'=>'form-control'));
        	?>
  </div>
  <div class="col-sm-3">

  </div>
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'kohde'); ?>
		<?php
        	$list = CHtml::listData(Kohteet::model()->findAll(array('order' => 'osoite')), 'id', 'osoite');
        	echo $form->dropDownList($model, 'kohde', $list,array('class'=>'form-control kohde'));
        	?>
  </div>
</div>

<div class="row">
  <div class="col-sm-6">
		<?php echo $form->labelEx($model,'tietoja'); ?>
		<?php echo $form->textarea($model,'tietoja',array('rows'=>8,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tietoja'); ?>
  </div>
  <div class="col-sm-6">
		<?php echo $form->labelEx($model,'ohje'); ?>
		<textarea class="form-control ohje" rows="8"><?php echo $ohje; ?></textarea>
  </div>
</div>

</div>
	<div class="modal-footer">
		<?php echo CHtml::Button('Sulje',array('class'=>'btn btn-default','data-dismiss'=>'modal')); ?>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary submitThis')); ?>
	</div>		
		</div> <!-- end modal-content -->
	</div> <!-- end modal-dialog -->



<?php $this->endWidget(); ?>



<script type="text/javascript">
$(document).ready(function(){

	$('.submitThis').click(function(){
		$('#tyovuoroot-form').submit();
	});

	$('#tyovuoroot-form').on('submit',function(e) {

	console.log( $( this ).serializeArray() );
	console.log( e.target[0].value );
	var str = '';
	if( e.target[0].value != '')
	{
	  $.ajax({
		  url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/update?id='+e.target[0].value,
		  data:$(this).serialize(),
		  type:'POST',
		  success:function(data){
			console.log(data);

	  		$.ajax({
				  url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/did',
				  type:'GET',
				  data: { id : e.target[0].value },
				  success:function(data){
					  console.log(data);
					  $('#showres').modal('hide');
					  $('#<?php echo date("Ymd",strtotime($model->pvm))."_".$model->tid; ?>').html(data);		
				return false;
			   	},
				error:function(data){
				console.log(data);
			    	}
			  });

		return false;
	   	},
		error:function(data){
		console.log(data);
	    	}
	  });

	} else {

	  $.ajax({
		  url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/create',
		  data:$(this).serialize(),
		  type:'POST',
		  success:function(data){
			//console.log(data);
	  		$.ajax({
				  url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/did',
				  success:function(data){
					  console.log(data);
					  $('#showres').modal('hide');
					  $('#<?php echo date("Ymd",strtotime($model->pvm))."_".$model->tid; ?>').html(data);		
				return false;
			   	},
				error:function(data){
				console.log(data);
			    	}
			  });

		return false;
	   	},
		error:function(data){
		console.log(data);
	    	}
	  });

	}

	e.preventDefault(); 
	});



  function laskePituus(){

	var alku = $("#alku").val().split(':');
	var loppu = $("#loppu").val().split(':');

	var d2 = new Date(2014, 0, 31, loppu[0], loppu[1]);
	var d1 = new Date(2014, 0, 31, alku[0], alku[1]);
	var seconds =  (d2- d1)/1000;
	var sec = seconds;
	var h = sec/3600 ^ 0 ;
	var m = (sec-h*3600)/60 ^ 0 ;

	$("#pituus").val((h<10?"0"+h:h)+":"+(m<10?"0"+m:m));
  }


  $('#alku').change(function(){
	laskePituus();
  });

  $('#loppu').change(function(){
	laskePituus();
  });

  $('#Tyovuoroot_kohde').change(function(){

	var thisID = $(this).val();

	  $.ajax({
		  url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/showohje?id='+thisID,
		  success:function(data){
			console.log(data);
			$('.ohje').text(data);
		return false;
	   	},
		error:function(data){
		console.log(data);
	    	}
	  });
  });



});
</script>


<!--

	<div class="row">
		<?php echo $form->labelEx($model,'ruokatauko'); ?>
		<?php echo $form->textField($model,'ruokatauko',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'ruokatauko'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'alku_r'); ?>
		<?php echo $form->textField($model,'alku_r',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'alku_r'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kesto'); ?>
		<?php echo $form->textField($model,'kesto',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'kesto'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'osoiteOnline'); ?>
		<?php echo $form->textField($model,'osoiteOnline',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'osoiteOnline'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kohde'); ?>
		<?php echo $form->textField($model,'kohde',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'kohde'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tid'); ?>
		<?php echo $form->textField($model,'tid'); ?>
		<?php echo $form->error($model,'tid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'time'); ?>
		<?php echo $form->textField($model,'time'); ?>
		<?php echo $form->error($model,'time'); ?>
	</div>

-->
