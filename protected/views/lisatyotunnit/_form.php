<?php
/* @var $this LisatyotunnitController */
/* @var $model Lisatyotunnit */
/* @var $form CActiveForm */
?>


        <div class="col-md-4 col-md-offset-1">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h4 class="text-center"><?php echo Yii::t('main', '+ LISÄTYÖTUNNIT'); ?></h4>
                </div>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'lisatyotunnit-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

                <ul class="list-group list-group-flush text-center">
                    <li class="list-group-item">
	<div class="row">
		<?php echo $form->labelEx($model,'tid'); ?><br>
		<?php echo $form->dropDownList($model,'tid', 
			CHtml::listData(Tyontekijat::model()->findAll(array('order' => 'tekijan_nimi')), 'id', 'tekijan_nimi'), 
			array('empty'=>'Valitse työntekijä','class'=>'form-control')) ?>
		<?php echo $form->error($model,'tid'); ?>
	</div>
                    </li>
                    <li class="list-group-item">
	<div class="row">
		<?php echo $form->labelEx($model,'pvm'); ?><br>
		<?php echo $form->textField($model,'pvm',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'pvm'); ?>
	</div>
                    </li>
		    <li class="list-group-item">
	<div class="row">
		<?php echo $form->labelEx($model,'syy'); ?><br>
		<?php echo $form->textField($model,'syy',array('size'=>20,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'syy'); ?>
	</div>
                    </li>
                    <li class="list-group-item">
	<div class="row">
		<?php echo $form->labelEx($model,'prosentti'); ?><br>
		<?php echo $form->textField($model,'prosentti',array('size'=>20,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'prosentti'); ?>
	</div>
                    </li>
                    <li class="list-group-item">
	<div class="row">
		<?php echo $form->labelEx($model,'tunnimaara'); ?><br>
		<?php echo $form->textField($model,'tunnimaara',array('size'=>20,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tunnimaara'); ?>
	</div>
                    </li>

                </ul>

                <div class="panel-footer">
                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-lg btn-block btn-success')); ?>
                </div>

<?php $this->endWidget(); ?>


            </div>
        </div>





