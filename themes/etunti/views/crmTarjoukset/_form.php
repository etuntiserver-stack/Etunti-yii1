<?php
/* @var $this CrmTarjouksetController */
/* @var $model CrmTarjoukset */
/* @var $form CActiveForm */

 $asiakas_selected = array();
 $yhteystiedot_selected = array();
 $tyonkuvaus = '';
 if(isset($_GET['asiakas_id']) and !empty($_GET['asiakas_id']))
 {
	$asiakas_selected[$_GET['asiakas_id']] = array('selected' => 'selected');
	$tyonkuvaus = $this->get_tyonkuvaus($_GET['asiakas_id'], null, $_GET['asiakas_id']);
 }

 if(isset($_GET['yhteystiedot_id']) and !empty($_GET['yhteystiedot_id']))
 {
	$yhteystiedot_selected[$_GET['yhteystiedot_id']] = array('selected' => 'selected');
	$tyonkuvaus = $this->get_tyonkuvaus(null, $_GET['yhteystiedot_id'], $_GET['yhteystiedot_id']);
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


<div class="row">
  <div class="col-sm-5">

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

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


		<?php echo $form->textArea($model,'tyonkuvaus',array('rows'=>6, 'cols'=>50, 'class'=>'form-control')); ?>


	<div class="section fill mb5">
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


 $('#crm-tarjoukset-form').on("submit", function(e){

    if ($("#CrmTarjoukset_tyonkuvaus").val() === '') {
       	alert('Valitse työnkuvaus.');
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

