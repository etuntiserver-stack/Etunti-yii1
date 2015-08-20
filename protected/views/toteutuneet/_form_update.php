<?php
/* @var $this ToteutuneetController */
/* @var $model Toteutuneet */
/* @var $form CActiveForm */

?>


	<div class="modal-dialog modal-lg">
	    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		<h2 class="modal-title"><?php echo Yii::t('main', 'Toteuman muutos'); ?></h2>
	
		</div>
		<div class="modal-body">

	<div class="dialogTable clearfix modal-osio">


<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'toteutuneet-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'kid'); ?>
		<?php echo $form->textField($model,'kid'); ?>
		<?php echo $form->error($model,'kid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kohde_kannasta'); ?>
		<?php echo $form->textField($model,'kohde_kannasta',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'kohde_kannasta'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kohdenID'); ?>
		<?php echo $form->textField($model,'kohdenID'); ?>
		<?php echo $form->error($model,'kohdenID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'aloitan'); ?>
		<?php echo $form->textField($model,'aloitan',array('size'=>20,'maxlength'=>20,'id'=>'aloitan')); ?>
		<?php echo $form->error($model,'aloitan'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'loppui'); ?>
		<?php echo $form->textField($model,'loppui',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'loppui'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_nimi'); ?>
		<?php echo $form->textField($model,'tekijan_nimi',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'tekijan_nimi'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tid'); ?>
		<?php echo $form->textField($model,'tid'); ?>
		<?php echo $form->error($model,'tid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tietoja'); ?>
		<?php echo $form->textArea($model,'tietoja',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'tietoja'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tyoajanlaatu'); ?>
		<?php echo $form->textField($model,'tyoajanlaatu',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'tyoajanlaatu'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tyoajanmerkinta'); ?>
		<?php echo $form->textField($model,'tyoajanmerkinta',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'tyoajanmerkinta'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->


	<div class="modal-footer">
		<?php echo CHtml::Button('Sulje',array('class'=>'btn btn-default','data-dismiss'=>'modal')); ?>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary submitThis')); ?>
	</div>		
		</div> <!-- end modal-content -->
	</div> <!-- end modal-dialog -->



<script type="text/javascript">
$(document).ready(function(){


	$('.submitThis').click(function(){
		$('#toteutuneet-form').submit();
	});

	$('#toteutuneet-form').on('submit',function(e) {

	console.log( $( this ).serializeArray() );
	console.log( e.target[0].value );
	var str = '';

	  $.ajax({
		  url: location.protocol + "//" + location.host + '/index.php/toteutuneet/update?id=<?php echo $model->id; ?>',
		  data:$(this).serialize(),
		  type:'POST',
		  success:function(data){
			console.log(data);

	  	$.ajax({
			url: location.protocol + "//" + location.host + '/index.php/toteutuneet/TotPvmTid',
			type:'GET',
			data: { "pvm" : "<?php echo $model->aloitan; ?>", "tid" : "<?php echo $model->tid; ?>", "from" : "update" },
			  success:function(data){
			  console.log(data);
			  $('#showres').modal('hide');
			  $('#<?php echo date("Ymd",strtotime($model->aloitan))."_".$model->tid; ?>').html(data);
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



	e.preventDefault(); 
	});






});
</script>
