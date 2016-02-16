<?php
/* @var $this LaskutusTuotteetController */
/* @var $model LaskutusTuotteet */
/* @var $form CActiveForm */
?>

<div class="section fill mb5">
  <div class="col-sm-3">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'laskutus-tuotteet-form',
	'enableAjaxValidation'=>false,
)); ?>


	<?php echo $form->errorSummary($model); ?>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tuotenimi'); ?>
		<?php echo $form->textField($model,'tuotenimi',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tuotenimi'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'hinta_alv_0'); ?>
		<?php echo $form->textField($model,'hinta_alv_0',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'hinta_alv_0'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'alv'); ?>
		<?php echo $form->textField($model,'alv',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'alv'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'yksikko'); ?>

	   <div class="form-inline">
		<?php
		$list = array();
      		$l = Valikkoot::model()->findAll(" select_type='laskutus_yksikko' ",array('order' => "select_type"));
		foreach($l as $v)
		$list[$v->id] = $v->value;

		if(count($list) > 0)
		{
        	echo $form->dropDownList($model, 'yksikko', $list,
		array('empty'=>'Valitse Laskutusyksikkö','class'=>'form-control form-group'));
		} else {
		echo 'Tyhjä';
		}		
        	?>
		<span class="btn btn-primary myBgColors muokaValiko" for="laskutus_yksikko"><i class="fa fa-pencil-square-o"></i></span>
	   </div>

		<?php echo $form->error($model,'yksikko'); ?>
	</div>
<br>
	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>
  </div>
</div><!-- form -->


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
