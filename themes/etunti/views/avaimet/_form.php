<?php
/* @var $this AvaimetController */
/* @var $model Avaimet */
/* @var $form CActiveForm */

if( !isset($model->id) and isset($_GET['asiakas_id']) and isset($_GET['kohde_id']) and !empty($_GET['asiakas_id']) and !empty($_GET['kohde_id']) ){
	$model->asiakas_id = $_GET['asiakas_id'];
	$model->kohde = $_GET['kohde_id'];
}
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'avaimet-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>
<div class="row form">
 <div class="col-sm-4">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'avainnumero'); ?>
		<?php echo $form->textField($model,'avainnumero',array('size'=>60,'maxlength'=>255, 'class' => 'form-control')); ?>
		<?php echo $form->error($model,'avainnumero'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'asiakas_id'); ?>
		<?php
			$a_controller = Yii::app()->createController('Asiakkaat');
			$list = $a_controller[0]->asiakkaatArrHelper(true);

        		echo $form->dropDownList($model, 'asiakas_id', $list,
			array('empty'=>'Valitse','class'=>'form-control'));
		
        	?>
		<?php echo $form->error($model,'asiakas_id'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kohde'); ?>
		<?php
	       	$criteria = new CDbCriteria();
	       	$criteria->condition = " aktiivinen=1 ";

		// <-- Tyoryhmat
		$site = Yii::app()->createController('Site');
		$arr = $site[0]->TyoryhmatHelper();
		$ids = implode(",", $arr);
		if( count($arr) > 0 ){
			$criteria->condition = " tyoryhma IN ($ids) ";
		}
		//    Tyoryhmat -->

	       	$criteria->order = " osoite ";
		$kohteet = Kohteet::model()->findAll($criteria);
        	echo $form->dropDownList($model, 'kohde', CHtml::listData($kohteet, 'id', 'osoite'),
		array('empty' => 'Valitse', 'class'=>'form-control'
		));
		?>
		<?php echo $form->error($model,'kohde'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tid'); ?>

		<?php 
			$site = Yii::app()->createController('Site');
			$tyontekiatLista = $site[0]->workerListSelect2(
				'Avaimet[tid]', // name
				'form-control', //class
				'Avaimet_tid', // id
				$model->tid, // selected
				1, // active workers or not,
				false, // multiple select or not
				[0 => "Valitse"] // placeholder element, id and value
			);
			echo $tyontekiatLista;
		?>
		<?php echo $form->error($model,'tid'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'sijainti_omatekstti'); ?>
		<?php
			$list = [0 => 'Valikon mukaan', 1 => 'Kirjoittamalla oma sijainti'];
        		echo $form->dropDownList($model, 'sijainti_omatekstti', $list,
			array('class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'asiakas_id'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'sijainti'); ?>
		<div id="sijainti_rakenne"></div>
		<?php echo $form->error($model,'sijainti'); ?>
	</div>

	<div class="section fill mb5 palautetu_asiakkaalle_pvm" style="display:none">
		<?php echo $form->labelEx($model,'palautetu_asiakkaalle_pvm'); ?>
		<?php echo $form->textField($model,'palautetu_asiakkaalle_pvm',array('size'=>60,'maxlength'=>50, 'class' => 'form-control datepickerFI')); ?>
		<?php echo $form->error($model,'palautetu_asiakkaalle_pvm'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'ovikoodi'); ?>
		<?php echo $form->textField($model,'ovikoodi',array('size'=>60,'maxlength'=>255, 'class' => 'form-control')); ?>
		<?php echo $form->error($model,'ovikoodi'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'lisatiedot'); ?>
		<?php echo $form->textArea($model,'lisatiedot',array('rows'=>6, 'cols'=>50, 'class' => 'form-control')); ?>
		<?php echo $form->error($model,'lisatiedot'); ?>
	</div>
<?php /*
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php
        	$l = array(0=>0, 1=>1, 2=>2, 3=>3);

        	echo $form->dropDownList($model, 'status', $l,
		array('class'=>'form-control'
		));
        	?>
		<?php echo $form->error($model,'status'); ?>
	</div>
*/ ?>
 </div>
</div><!-- form -->

<br>

<div class="row form">
  <div class="col-sm-3">
	<div class="section fill mb5">
	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-sm btn-primary myBgColors')); ?>
	</div>
	</div>
  </div>
</div>

<?php $this->endWidget(); ?>


<script type="text/javascript">
$(document).ready(function(){
	// init select2 elements
	$(".select2").select2({
		placeholder: {
			id: 0,
			text: "Valitse",
		},
		allowClear: true,
	});

 function sijainti(){
	if( $('#Avaimet_sijainti_omatekstti').val() == '0' ){
		$('#sijainti_rakenne').html('' +
			'<select class="form-control" name="Avaimet[sijainti]" id="Avaimet_sijainti">' +
			'<option value="1">Toimistolla</option>' +
			'<option value="2">Palautettu asiakkaalle</option>' +
			'<option value="3">Työntekijällä</option>' +
			'</select>' 
		);
		$('#Avaimet_sijainti').val('<?=$model->sijainti?>');
	}
	if( $('#Avaimet_sijainti_omatekstti').val() == '1' ){
		$('#sijainti_rakenne').html('' +
			'<input size="60" maxlength="255" class="form-control" name="Avaimet[sijainti]" id="Avaimet_sijainti" type="text" value="<?=$model->sijainti?>" />' 
		);
	}
	if( $("#Avaimet_sijainti").val() == '2' ){
		$('.palautetu_asiakkaalle_pvm').show(370);
	} else {
		$('.palautetu_asiakkaalle_pvm').hide(370);
	}
 }
 sijainti();
 $('#Avaimet_sijainti_omatekstti').change(function(){
	sijainti();
	$('#Avaimet_sijainti').val('');
 });
 $(document).delegate("#Avaimet_sijainti","change",function(){
	if( $(this).val() == '2' ){
		$('.palautetu_asiakkaalle_pvm').show(370);
	} else {
		$('.palautetu_asiakkaalle_pvm').hide(370);
	}
 });
 $('#Avaimet_asiakas_id').change(function(){

	var thisVal = $(this).val();
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/crmSopimukset/get_kohde?id=' + thisVal,
           //type: "POST",
           //data: { },
           success: function(data){
		var data = JSON.parse(data);
		console.log(data);
		if(data['error']){
	    		window.location.href=location.protocol + "//" + location.host + '/index.php/user/logout';
		}
		if(data['options']){
			$('#Avaimet_kohde').html(data['options']);
		}
		if(data['asiakas_sahkoposti']){
			$('#Avaimet_asiakkaan_sahkoposti').val(data['asiakas_sahkoposti']);
		}
		if(data['asiakas_tiedot']){
			$('#Avaimet_tiedot').html(data['asiakas_tiedot']);
		}

           },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	window.location.href=location.protocol + "//" + location.host + '/index.php/user/logout';
 	   }
        });

 });


});
</script>

