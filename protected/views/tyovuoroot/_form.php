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
if(isset($model->id))
{

	$m = Kohteet::model()->findbypk($model->kohde);
		
	if(isset($m->id))
	{

		$k = explode("//",$m->kenella_on_avain);

		  $ohje = '';
		if(isset($k[1]))
		  $ohje .= Yii::t('main', 'Avain on: ')." ".$k[1]."\n";
		if(!empty($m->avain))
		  $ohje .= Yii::t('main', 'Avain: ')." ".$m->avain."\n\n";
		if(!empty($m->toimenpiteet))
		  $ohje .= "\nToimenpiteet:\n".$m->toimenpiteet;
		if(!empty($m->tietoja))
		  $ohje .= "\nTietoja:\n".$m->tietoja;
		if(!empty($m->muut))
		  $ohje .= "\nMuut:\n".$m->muut;

	}

}
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
	<?php echo $form->hiddenField($model,'osoiteOnline'); ?>
	<?php echo $form->hiddenField($model,'time'); ?>
	<?php echo $form->error($model,'tid'); ?>

<div class="row">
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'pvm'); ?>
		<?php echo $form->textField($model,'pvm',array('size'=>20,'maxlength'=>20,'class'=>'form-control datepicker'));//,'readonly'=>'yes' ?>
		<?php echo $form->error($model,'pvm'); ?>
  </div>
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'alku'); ?>
		<input type="text" name="Tyovuoroot[alku]" class="form-control laske timepicker" id="alku" value="<?php echo $model->alku; ?>">
  </div>
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'loppu'); ?>
		<input type="text" name="Tyovuoroot[loppu]" class="form-control laske timepicker" id="loppu" value="<?php echo $model->loppu; ?>">
  </div>
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'pituus'); ?>
		<input type="text" name="Tyovuoroot[pituus]" id="pituus" class="form-control timepicker" value="<?php echo $model->pituus; ?>">
  </div>
</div>

<div class="row">
<!--
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'tyoajanlaatu'); ?>
		<?php
        	$tal = Valikkoot::model()->findAll(" select_type='tyoajanlaatu' ", array('order' => 'select_type'));
		echo '<select name="Tyovuoroot[tyoajanlaatu]" class="form-control">';

		 if(!empty($model->tyoajanlaatu)){
		   $expl1 = explode("/",$model->tyoajanlaatu);
		   $value1 = (isset($expl1[0])) ? $expl1[0] : '';
		   echo '<option value="'.$model->tyoajanlaatu.'">'.$value1.'</option>';
		 }

		 foreach($tal as $v)
		 {
		   $expl1 = explode("/",$v->value);
		   $color = (isset($expl1[1])) ? $expl1[1] : '';
		   $value1 = (isset($expl1[0])) ? $expl1[0] : '';
		   echo '<option style="color:'.$color.'" value="'.$v->value.'">'.$value1.'</option>';
		 }
		echo '</select>';
        	?>
  </div>
-->
  <div class="col-sm-6">
		<?php echo $form->labelEx($model,'tyoajanmerkinta'); ?>
		<?php
        	$tal = Valikkoot::model()->findAll(" select_type='tyoajanmerkinta' ", array('order' => 'select_type'));
		echo '<select name="Tyovuoroot[tyoajanmerkinta]" class="form-control">';

		 if(!empty($model->tyoajanmerkinta)){
		   $expl = explode("/",$model->tyoajanmerkinta);
		   $value = (isset($expl[0])) ? $expl[0] : '';
		   echo '<option value="'.$model->tyoajanmerkinta.'">'.$value.'</option>';
		 }

		 foreach($tal as $v)
		 {
		   $expl = explode("/",$v->value);
		   $color = (isset($expl[1])) ? $expl[1] : '';
		   $value = (isset($expl[0])) ? $expl[0] : '';
		   echo '<option style="color:'.$color.'" value="'.$v->value.'">'.$value.'</option>';
		 }
		echo '</select>';
        	?>

  </div>
  <div class="col-sm-6">
		<?php echo $form->labelEx($model,'kohde'); ?>
		<?php
        	$list = CHtml::listData(Kohteet::model()->findAll(array('order' => 'osoite')), 'id', 'osoite');
        	echo $form->dropDownList($model, 'kohde', $list,array('empty'=>'Valitse','class'=>'form-control kohde'));
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

<?php 
$t = Tyontekijat::model()->findbypk($model->tid);
if(!empty($t->gcm_reg_id)) :
?>
<div class="row">
  <div class="col-sm-12">
    <div class="pull-right">
		<?php echo Yii::t('main','Ilmoita työntekijä viestinä'); ?> 
			<input type="checkbox" name="Tyovuoroot[PushNotify]" class="sw" id="Tyovuoroot_PushNotify">
    </div>
  </div>
</div>
<?php endif; ?>

</div>
	<div class="row modal-footer">
		<?php echo CHtml::Button('Sulje',array('class'=>'btn btn-default btn-sm','data-dismiss'=>'modal')); ?>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-sm btn-primary submitThis')); ?>
	</div>		
		</div> <!-- end modal-content -->
	</div> <!-- end modal-dialog -->



<?php $this->endWidget(); ?>


<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/asetukset.js"></script>

<script type="text/javascript">
$(document).ready(function(){

  $(".sw").bootstrapSwitch({
	size: "mini",
	onColor: "success",
	offColor: "danger",
	onText: "Kyllä",
	offText: "Ei"
  });

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
			data: { "pvm" : "<?php echo $model->pvm; ?>", "tid" : "<?php echo $model->tid; ?>", "from" : "ajax" },
			  success:function(data){
			  //console.log(data);
			  $('#<?php echo date("Ymd",strtotime($model->pvm))."_".$model->tid; ?>').html(data);
			  return false;
			  },
			  error:function(data){
			  console.log(data);
			  }
	 	});

	  	$.ajax({
			url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/viikko',
			type:'GET',
			data: { "tid" : "<?php echo $model->tid; ?>", "viikko" : "<?php echo date('W',strtotime($model->pvm)); ?>" },
			  success:function(data){
			  //console.log(data);
			  $('#vk_<?php echo date("W",strtotime($model->pvm))."_".$model->tid; ?>').html(data);
			  return false;
			  },
			  error:function(data){
			  console.log(data);
			  }
	 	});

	  	$.ajax({
			url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/fromto',
			type:'GET',
			data: { "tid" : "<?php echo $model->tid; ?>" },
			  success:function(data){
			  //console.log(data);
			  $('.fromto_<?php echo $model->tid; ?>').html(data);
			  return false;
			  },
			  error:function(data){
			  console.log(data);
			  }
	 	});

		$('#showres').modal('hide');
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
			type:'GET',
			data: { "pvm" : "<?php echo $model->pvm; ?>", "tid" : "<?php echo $model->tid; ?>", "from" : "ajax" },
			  success:function(data){
			  //console.log(data);
			  $('#showres').modal('hide');
			  $('#<?php echo date("Ymd",strtotime($model->pvm))."_".$model->tid; ?>').html(data);
			  //return false;
			  },
			  error:function(data){
			  console.log(data);
			  }
	 	});

		//return false;
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


  $('#alku').keyup(function(){
	laskePituus();
  });

  $('#loppu').keyup(function(){
	laskePituus();
  });

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
			//console.log(data);
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
