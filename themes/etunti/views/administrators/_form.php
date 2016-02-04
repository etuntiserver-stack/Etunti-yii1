<?php
/* @var $this AdministratorsController */
/* @var $model Administrators */
/* @var $form CActiveForm */
?>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'administrators-form',
	'enableAjaxValidation'=>true,
)); ?>


	<?php echo $form->errorSummary($model); ?>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'adm_login'); ?>
		<?php echo $form->textField($model,'adm_login',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'adm_login'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'adm_salasana'); ?>
		<?php echo $form->passwordField($model,'adm_salasana',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'adm_salasana'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'adm_email'); ?>
		<?php echo $form->textField($model,'adm_email',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'adm_email'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'adm_nimi'); ?>
		<?php echo $form->textField($model,'adm_nimi',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'adm_nimi'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'status'); ?>

	   <div class="form-inline">
		<?php 
		$a = Valikkoot::model()->findAll(" select_type='admin status' ");
        	$tal = array();
		foreach($a as $v){
		$exV = explode("/",$v->value);
		   if(isset($exV[1]))
		   $tal[$exV[1]] = $exV[0];
		}

		if(isset($a[0])){
			echo $form->dropDownList($model,'status', $tal, 
			array('class'=>'form-control')); 
		} else {
			echo $form->textField($model,'status',array('size'=>60,'maxlength'=>1,'class'=>'form-control'));
		}
		?>
		<span class="btn btn-primary myBgColors muokaValiko" for="admin status"><i class="fa fa-pencil-square-o"></i></span>
	   </div>
		<?php echo $form->error($model,'status'); ?>
	</div>

<br>

	<div class="section fill mb5">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>



	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>


<script type="text/javascript">
$(document).ready(function(){

/* valikot */
$(".muokaValiko").click(function() {
    var thisFor = $(this).attr("for");
        $.ajax({
           url: location.protocol + "//" + location.host + "/index.php/site/valiko",
	   type:'POST',
	   data: { "select_type" : thisFor },
           success: function(data){
		//console.log(data);
		$('#showres').modal().html(JSON.parse(data));
           }
        });
});
/* valikot */


});
</script>

