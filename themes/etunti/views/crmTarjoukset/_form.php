<?php
/* @var $this CrmTarjouksetController */
/* @var $model CrmTarjoukset */
/* @var $form CActiveForm */

 $asiakas_selected = array();
 $yhteystiedot_selected = array();
 $tyonkuvaus = '';
 $tarjouslaskenta = '';
 if(isset($_GET['asiakas_id']) and !empty($_GET['asiakas_id']))
 {
	$asiakas_selected[$_GET['asiakas_id']] = array('selected' => 'selected');
	$tyonkuvaus = $this->get_tyonkuvaus('asiakas_id', $_GET['asiakas_id']);
	$tarjouslaskenta = $this->get_tarjouslaskenta('asiakas_id', $_GET['asiakas_id']);
 }

 if(isset($_GET['yhteystiedot_id']) and !empty($_GET['yhteystiedot_id']))
 {
	$yhteystiedot_selected[$_GET['yhteystiedot_id']] = array('selected' => 'selected');
	$tyonkuvaus = $this->get_tyonkuvaus('yhteystiedot_id', $_GET['yhteystiedot_id']);
	$tarjouslaskenta = $this->get_tarjouslaskenta('yhteystiedot_id', $_GET['yhteystiedot_id']);
 }

 if(isset($model->id))
 {
	$model->tyonkuvaus = json_decode($model->tyonkuvaus);
	$model->tarjouslaskenta = json_decode($model->tarjouslaskenta);
 }
?>



<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'crm-tarjoukset-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

<?php if(!isset($model->id)) : ?>
<div class="row">
  <div class="col-sm-5">


	
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'yhteystiedot_id'); ?>
		<?php
		$list = array();
		$criteria=new CDbCriteria;
		//$criteria->condition="";
      		$l = Yhteystiedot::model()->findAll($criteria);
		foreach($l as $v)
		{
			if(!empty($v->yrityksen_nimi) and empty($v->yhteyshenkilo))
			$list[$v->id] = $v->yrityksen_nimi;
			elseif(empty($v->yrityksen_nimi) and !empty($v->yhteyshenkilo))
			$list[$v->id] = $v->yhteyshenkilo;
		}

        		echo $form->dropDownList($model, 'yhteystiedot_id', $list,
			array('empty'=>'Valitse','class'=>'form-control', 'options'=>$yhteystiedot_selected));
		
        	?>
		<?php echo $form->error($model,'yhteystiedot_id'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'asiakas_id'); ?>
		<?php
		$list = array();
		$criteria=new CDbCriteria;
		//$criteria->condition="";
      		$l = Asiakkaat::model()->findAll($criteria);
		foreach($l as $v)
		{
			if(!empty($v->yrityksen_nimi) and empty($v->yhteyshenkilo))
			$list[$v->id] = $v->yrityksen_nimi;
			elseif(empty($v->yrityksen_nimi) and !empty($v->yhteyshenkilo))
			$list[$v->id] = $v->yhteyshenkilo;
		}

		if(count($list) > 0)
		{
        		echo $form->dropDownList($model, 'asiakas_id', $list,
			array('empty'=>'Valitse','class'=>'form-control', 'options'=>$asiakas_selected));
		}		
        	?>
		<?php echo $form->error($model,'asiakas_id'); ?>
	</div>


 </div>
</div><!-- form -->
<?php endif; ?>


	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyonkuvaus'); ?>
		<?php echo $form->textArea($model,'tyonkuvaus',array('rows'=>6, 'cols'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tyonkuvaus'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tarjouslaskenta'); ?>
		<?php echo $form->textArea($model,'tarjouslaskenta',array('rows'=>6, 'cols'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tarjouslaskenta'); ?>
	</div>


	<div class="section fill mb5">
		<?php echo $tarjouslaskenta; ?>
		<?php echo $tyonkuvaus; ?>
	</div>


<!--
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tarjous'); ?>
		<?php echo $form->textArea($model,'tarjous',array('rows'=>6, 'cols'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tarjous'); ?>
	</div>
-->
<br>
	<div class="section">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary myBgColors')); ?>
	</div>

<?php $this->endWidget(); ?>



<?php if(!isset($model->id)) : ?>
<script type="text/javascript">
$(document).ready(function(){

 $('#CrmTarjoukset_asiakas_id').change(function(){
	window.location.href= "create?asiakas_id=" + $(this).val();
 });

 $('#CrmTarjoukset_yhteystiedot_id').change(function(){
	window.location.href= "create?yhteystiedot_id=" + $(this).val();
 });

 $('.tyokuvaus').change(function(){

    if ($("input[name='tyokuvaus']:checked").val()) {
	var thisFor = $(this).attr('for');
	var tablekuvaus = $('#'+thisFor).html();
	$('#CrmTarjoukset_tyonkuvaus').val(tablekuvaus);
    }

 });

 $('.tarjouslaskenta').change(function(){

    if ($("input[name='tarjouslaskenta']:checked").val()) {
	var thisFor = $(this).attr('for');
	var tablekuvaus = $('#'+thisFor).html();
	$('#CrmTarjoukset_tarjouslaskenta').val(tablekuvaus);
    }

 });

 $('#crm-tarjoukset-form').on("submit", function(e){

    if ($("#CrmTarjoukset_tyonkuvaus").val() === '') {
       	alert('Valitse työnkuvaus.');
        return false;
    }

    if ($("#CrmTarjoukset_tarjouslaskenta").val() === '') {
       	alert('Valitse tarjouslaskenta.');
        return false;
    }

    $(this).submit();
    e.preventDefault();

 });


/*
        $.ajax({
           url: 'get_tyonkuvaus_by_asiakas?id=' + $(this).val(),
           //type: "POST",

           //data: { },
           success: function(data){
		var d = JSON.parse(data);
		console.log(d);


           }
        });
*/


});
</script>
<?php endif; ?>
