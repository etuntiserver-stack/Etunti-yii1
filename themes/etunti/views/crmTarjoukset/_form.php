<?php
/* @var $this CrmTarjouksetController */
/* @var $model CrmTarjoukset */
/* @var $form CActiveForm */
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
  <div class="col-sm-4">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kohde_id'); ?>
		<?php
		$list = array();
		$criteria=new CDbCriteria;
		//$criteria->condition=" ";
      		$l = Tyonkuvaus::model()->findAll($criteria);
		if( $l != null )
		{
		    foreach($l as $v)
		    {
			$k = Kohteet::model()->findByPk($v->kohde_id);
			if(isset($k->id))
			$list[$v->id] = date("d.m.Y", strtotime($v->time)).' - '.$k->osoite;
		    }
		}

		if(count($list) > 0)
		{
        		echo $form->dropDownList($model, 'kohde_id', $list,
			array('empty'=>'Valitse','class'=>'form-control required'));
		}		
        	?>
		<?php echo $form->error($model,'kohde_id'); ?>
	</div>


	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tarjous'); ?>
		<?php echo $form->textArea($model,'tarjous',array('class'=>'form-control', 'rows'=>7)); ?>
		<?php echo $form->error($model,'kohteen_osoite'); ?>
	</div>

 </div><div class="col-sm-4">

	<div class="section fill mb5 kohdeHide">
		<?php echo $form->labelEx($model,'kohteen_osoite'); ?>
		<?php echo $form->textField($model,'kohteen_osoite',array('maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'kohteen_osoite'); ?>
	</div>

	<div class="section fill mb5 kohdeHide">
		<?php echo $form->labelEx($model,'kohteen_postinumero'); ?>
		<?php echo $form->textField($model,'kohteen_postinumero',array('maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'kohteen_postinumero'); ?>
	</div>

	<div class="section fill mb5 kohdeHide">
		<?php echo $form->labelEx($model,'kohteen_postitoimipaikka'); ?>
		<?php echo $form->textField($model,'kohteen_postitoimipaikka',array('maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'kohteen_postitoimipaikka'); ?>
	</div>

	<div class="section fill mb5 kohdeHide">
		<?php echo $form->labelEx($model,'asiakkaan_sahkoposti'); ?>
		<?php echo $form->textField($model,'asiakkaan_sahkoposti',array('maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'asiakkaan_sahkoposti'); ?>
	</div>

 </div>
</div><!-- form -->




		<?php //echo $form->textArea($model,'tyonkuvaus',array('rows'=>6, 'cols'=>50, 'class'=>'form-control', 'style'=>'display:none')); ?>
		<?php //echo $form->textArea($model,'tarjouslaskenta',array('rows'=>6, 'cols'=>50, 'class'=>'form-control', 'style'=>'display:none')); ?>


	<div class="section fill mb5">
		<div id="tyonkuvaus"></div>
	</div>

		<?php echo $form->hiddenField($model,'tyonkuvaus_id'); ?>
<br>
	<div class="section">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary myBgColors submitButton')); ?>
	</div>

<?php $this->endWidget(); ?>



<?php if(isset($model->tyonkuvaus_id) and !empty($model->tyonkuvaus_id)) : ?>
<script type="text/javascript">
$(document).ready(function(){

        $.ajax({
           url: 'view_tyonkuvaus?id=<?php echo $model->tyonkuvaus_id; ?>',
           //type: "POST",
           //data: { },
           success: function(data){
		var data = JSON.parse(data);
		//console.log(data);
		if(data)
		{
			$('#tyonkuvaus').html('<br>' + data);
		}
           }
        });

});
</script>
<?php endif; ?>

<script type="text/javascript">
$(document).ready(function(){



 $('#crm-tarjoukset-form').on("submit", function(e){

    $(this).submit();
    e.preventDefault();

 });


 $('#CrmTarjoukset_kohde_id').change(function(){

	var thisVal = $(this).val();
        $.ajax({
           url: 'get_kohteentiedot?id=' + thisVal,
           //type: "POST",
           //data: { },
           success: function(data){
		var data = JSON.parse(data);
		console.log(data);
		if(data['id'])
		{
			$('.kohdeHide').show();
			$('#CrmTarjoukset_kohteen_osoite').val(data['osoite']).attr('readonly', 'yes');
			$('#CrmTarjoukset_kohteen_postinumero').val(data['pnumero']).attr('readonly', 'yes');
			$('#CrmTarjoukset_kohteen_postitoimipaikka').val(data['kaupunki']).attr('readonly', 'yes');
			$('#CrmTarjoukset_asiakkaan_sahkoposti').val(data['email']).attr('readonly', 'yes');
			//$('#CrmTarjoukset_tyonkuvaus').val(data['tyonkuvaus']);
			$('#CrmTarjoukset_tyonkuvaus_id').val(data['tyonkuvaus_id']);
			$('#tyonkuvaus').html('<br>' + data['tyonkuvaus']);
		}
           }
        });

 });


});
</script>

