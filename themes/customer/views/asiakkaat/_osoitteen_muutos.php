<?php

?>


<div class="row">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'asiakkaat-form',
	'enableAjaxValidation'=>true,
)); ?>


	<?php echo $form->errorSummary($model); ?>

  <div class="col-sm-3">
	<legend><h3><?php echo Yii::t('main', 'Asiakkaan tiedot'); ?></h3></legend>


	<div class="section fill mb5 yritys ashidd">
		<?php echo $form->labelEx($model,'yrityksen_nimi'); ?>
		<?php echo $form->textField($model,'yrityksen_nimi',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'yrityksen_nimi'); ?>
	</div>

	<div class="section fill mb5 y_tunnus ashidd">
		<?php echo $form->labelEx($model,'y_tunnus'); ?>
		<?php echo $form->textField($model,'y_tunnus',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'y_tunnus'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'etunimi'); ?>
		<?php echo $form->textField($model,'etunimi',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'etunimi'); ?>
	</div>
	
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'sukunimi'); ?>
		<?php echo $form->textField($model,'sukunimi',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'sukunimi'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'osoite'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'postinumero'); ?>
		<?php echo $form->textField($model,'postinumero',array('class'=>'form-control','maxlength'=>5)); ?>
		<?php echo $form->error($model,'postinumero'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'kaupunki'); ?>
		<?php echo $form->textField($model,'kaupunki',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'kaupunki'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'puhelin'); ?>
		<?php echo $form->textField($model,'puhelin',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'puhelin'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'sahkoposti'); ?>
		<?php echo $form->textField($model,'sahkoposti',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'sahkoposti'); ?>
	</div>

  </div>

</div><!-- form -->
<br>

	<div class="section">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('main', 'Luo') : Yii::t('main', 'Lähetä'),array('class'=>'btn btn-primary myBgColors luoTallennaAsiakas')); ?>
	</div>


<?php $this->endWidget(); ?>



