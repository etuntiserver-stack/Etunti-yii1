<?php
/* @var $this IlmoitusKaikkilleController */
/* @var $model IlmoitusKaikkille */
/* @var $form CActiveForm */
$vastaanottajat = [];
if(is_array(json_decode($model->vastaanottajat, true))){
	$vastaanottajat = json_decode($model->vastaanottajat, true);
}
if( isset($model->id) ){
	$model->aloitus = date("d.m.Y H:i", strtotime($model->aloitus));
	$model->lopetus = date("d.m.Y H:i", strtotime($model->lopetus));
} else {
	$model->aloitus = date("d.m.Y H:i", strtotime("08:00"));
	$model->lopetus = date("d.m.Y H:i", strtotime("16:00"));
}
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ilmoitus-kaikkille-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

<div class="row">
     <div class="col-sm-6">
	<?php echo $form->errorSummary($model); ?>

	<div class="section fill mb5">
				<?php
		   		$domainit = $this->domainitMulti( 
						'vastaanottajat[]', // name
						'form-control', //class
						'domain', // id
						(count($vastaanottajat) > 0)?$vastaanottajat:'', //selected
						1 // aktiivinen
				);
				echo $domainit;
				?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'viesti'); ?>
		<?php echo $form->textArea($model,'viesti',array('class'=>'form-control', 'rows'=>6, 'cols'=>50, 'placeholder' => "<h3>Hei</h3>\n\nEsimerkki teksti")); ?>
		<?php echo $form->error($model,'viesti'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'aloitus'); ?>
		<?php echo $form->textField($model,'aloitus', array('class'=>'form-control datetimepicker')); ?>
		<?php echo $form->error($model,'aloitus'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'lopetus'); ?>
		<?php echo $form->textField($model,'lopetus', array('class'=>'form-control datetimepicker')); ?>
		<?php echo $form->error($model,'lopetus'); ?>
	</div>

	<div class="section buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Seuraava >>>' : 'Tallenna', array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->


<script type="text/javascript">
$(document).ready(function(){

  $('#domain').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: 'Valitse domainia',
	selectAllText: 'Valitse kaikki',
	allSelectedText: 'Kaikki',
	nSelectedText: 'valittu',
	numberDisplayed: 0,
	buttonWidth: '100%',
        maxHeight: 300,
  });

});
</script>
