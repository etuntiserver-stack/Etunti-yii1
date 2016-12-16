<?php
/* @var $this LaskutusTuotteetController */
/* @var $model LaskutusTuotteet */
/* @var $form CActiveForm */
?>

<div class="section fill mb5">
  <div class="col-sm-4">
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
		<?php echo $form->numberField($model,'hinta_alv_0',array('size'=>20,'maxlength'=>20,'class'=>'form-control', 'step'=>'0.01')); ?>
		<?php echo $form->error($model,'hinta_alv_0'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'hinta_alv_sis'); ?>
		<?php echo $form->numberField($model,'hinta_alv_sis',array('size'=>20,'maxlength'=>20,'class'=>'form-control', 'step'=>'0.01')); ?>
		<?php echo $form->error($model,'hinta_alv_sis'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'alv'); ?>
		<?php 
        	$l = array();
		for ($i = 1; $i <= 50; $i++) {
		    $l[$i] = $i;
		}

		echo $form->dropDownList($model,'alv',$l, 
			array('class'=>'form-control','options' => array('24'=>array('selected'=>true)))
		);

		?>

		<?php echo $form->error($model,'alv'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'yksikko'); ?>

	   <div class="input-group">
		<?php
		$list = array();
      		$l = Valikkoot::model()->findAll(" select_type='laskutus_yksikko' ",array('order' => "select_type"));
		foreach($l as $v)
		$list[$v->id] = $v->value;

		if(count($list) > 0)
		{
        	echo $form->dropDownList($model, 'yksikko', $list,
		array('empty'=>'Valitse Laskutusyksikkö','class'=>'form-control'));
		} else {
		echo 'Tyhjä';
		}		
        	?>
		<span class="input-group-btn">
			<span class="btn btn-primary myBgColors muokaValiko" for="laskutus_yksikko"><i class="fa fa-pencil-square-o"></i></span>
		</span>
	   </div>

		<?php echo $form->error($model,'yksikko'); ?>
	</div>


	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'ryhma'); ?>

	   <div class="input-group">
		<?php
		$list = array();
		$v = Valikkoot::model()->find(" select_type='laskutus_tuotteet_ryhma' ");
		if(!isset($v->id))
		{
			$vnew = new Valikkoot;
			$vnew->value = 'test';
			$vnew->select_type = 'laskutus_tuotteet_ryhma';
			$vnew->save();		
		}

      		$l = Valikkoot::model()->findAll(" select_type='laskutus_tuotteet_ryhma' ",array('order' => "select_type"));
		foreach($l as $v)
		$list[$v->id] = $v->value;

		if(count($list) > 0)
		{
        	echo $form->dropDownList($model, 'ryhma', $list,
		array('empty'=>'Valitse ryhmä','class'=>'form-control'));
		} 		
        	?>
		<span class="input-group-btn">
			<span class="btn btn-primary myBgColors muokaValiko" for="laskutus_tuotteet_ryhma"><i class="fa fa-pencil-square-o"></i></span>
		</span>
	   </div>

		<?php echo $form->error($model,'ryhma'); ?>
	</div>


	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'is_active'); ?>
		<?php 
        	$l = array(0=>Yii::t('main', 'Ei'),1=>Yii::t('main', 'Kyllä'));
		echo $form->dropDownList($model,'is_active',$l, 
			array('class'=>'form-control','options' => array('24'=>array('selected'=>true)))
		);

		?>

		<?php echo $form->error($model,'is_active'); ?>
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



 $('#LaskutusTuotteet_hinta_alv_0').keyup(function(){
	lasketa();
 });

 $('#LaskutusTuotteet_alv').change(function(){
	lasketa();
 });

 function lasketa(){
	var hinta_alv_0 = parseFloat($('#LaskutusTuotteet_hinta_alv_0').val());
	var alv = parseFloat($('#LaskutusTuotteet_alv').val());

	var result = ((hinta_alv_0*alv)/100)+hinta_alv_0;
	var result = Math.round(result * 100) / 100;
	$('#LaskutusTuotteet_hinta_alv_sis').val(result);
 }


});
</script>
