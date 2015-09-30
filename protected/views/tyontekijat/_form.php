<?php
/* @var $this TyontekijatController */
/* @var $model Tyontekijat */
/* @var $form CActiveForm */

?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tyontekijat-form',
	'enableAjaxValidation'=>false,
)); ?>

<div class="row form">
   <div class="col-sm-3">

	<?php echo $form->errorSummary($model); ?>


	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_nimi'); ?>
		<?php echo $form->textField($model,'tekijan_nimi',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_nimi'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_email'); ?>
		<?php echo $form->textField($model,'tekijan_email',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_katuosoite'); ?>
		<?php echo $form->textField($model,'tekijan_katuosoite',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_katuosoite'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_pnumero'); ?>
		<?php echo $form->textField($model,'tekijan_pnumero',array('size'=>7,'maxlength'=>7,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_pnumero'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_ptoimipaikka'); ?>
		<?php echo $form->textField($model,'tekijan_ptoimipaikka',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_ptoimipaikka'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'aktiivinen'); ?>
		<?php 
		$a = Valikkoot::model()->findAll(" select_type='aktiivinen' ");
        	$tal = '';
		foreach($a as $v){
		$exV = explode("/",$v->value);
		   $tal[$exV[1]] = $exV[0];
		}

		echo $form->dropDownList($model,'aktiivinen', $tal, 
		array('class'=>'form-control','id'=>'osoite')) ?>
		<?php echo $form->error($model,'aktiivinen'); ?>
	</div>


   </div>
   <div class="col-sm-3">

	<div class="row">
		<?php echo $form->labelEx($model,'imei'); ?>
		<?php echo $form->textField($model,'imei',array('size'=>30,'maxlength'=>30,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'imei'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'laiten_puh'); ?>
		<?php echo $form->textField($model,'laiten_puh',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'laiten_puh'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_henkilotunnus'); ?>
		<?php echo $form->textField($model,'tekijan_henkilotunnus',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_henkilotunnus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_puh'); ?>
		<?php echo $form->textField($model,'tekijan_puh',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_puh'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'online_varauksen_valmina'); ?>
		<?php 
        	$tal = array(0=>'Ei',1=>'Kyllä');
		echo $form->dropDownList($model,'online_varauksen_valmina', $tal, 
		array('class'=>'form-control','id'=>'osoite')) ?>
		<?php echo $form->error($model,'online_varauksen_valmina'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tyoryhma'); ?>
		<?php
      		$l = Valikkoot::model()->findAll(" select_type='tyoryhma' ",array('order' => "select_type"));
		foreach($l as $v)
		$list[$v->value] = $v->value;

        	echo $form->dropDownList($model, 'tyoryhma', $list,
		array('empty'=>'','class'=>'form-control'));
        	?>
		<?php echo $form->error($model,'tyoryhma'); ?>
	</div>

   </div>
   <div class="col-sm-3">

	<div class="row">
		<?php echo $form->labelEx($model,'tyoehtosopimus'); ?>
		<?php echo $form->textField($model,'tyoehtosopimus',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tyoehtosopimus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_pankkitili'); ?>
		<?php echo $form->textField($model,'tekijan_pankkitili',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_pankkitili'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_konttori'); ?>
		<?php echo $form->textField($model,'tekijan_konttori',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_konttori'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kortit'); ?>
		<?php
		$a = Valikkoot::model()->findAll(" select_type='kortit' ");
		$check = explode("##***",$model->kortit);

		echo '<select name="kortit[]" class="selectpicker form-control" multiple title="Valitse">';
		  foreach($a as $val){
		    	$on = false;
		   foreach($check as $c)
		   {
		     if(trim($c) == trim('kortti_'.$val->value))
		     {
		    	echo '<option value="kortti_'.$val->value.'" selected>'.$val->value.'</option>';
		    	$on = true;
		     }
		   }
		    if($on == false)
		    echo '<option value="kortti_'.$val->value.'">'.$val->value.'</option>';
		  }
		echo '</select>';
		?>
		<?php echo $form->error($model,'kortit'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ayjasenyys'); ?>
		<?php echo $form->checkbox($model,'ayjasenyys',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>

		<?php echo $form->error($model,'ayjasenyys'); ?>
	</div>

   </div>
   <div class="col-sm-2">
	<?php if(isset($model->id)): ?>
	<div class="row"><br>
		<?php
		$filename = "../../img/tekijat/".Yii::app()->user->domain."/".$model->id.".jpg";
		if (file_exists(Yii::app()->request->baseUrl."img/tekijat/".Yii::app()->user->domain."/".$model->id.".jpg"))
		   echo '<img src="'.$filename.'" class="img-thumbnail">';
		else
		   echo '<img src="../../img/tekijat/noname.jpg" class="img-thumbnail">';
		?>		
	</div>
	<?php endif; ?>
   </div>

</div><!-- form -->

<div class="row form">
   <div class="col-sm-6">

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_tietoja'); ?>
		<?php echo $form->textArea($model,'tekijan_tietoja',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_tietoja'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_muisti'); ?>
		<?php echo $form->textArea($model,'tekijan_muisti',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_muisti'); ?>
	</div>

   </div>
</div>


<br>

	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>



<?php
/*

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_lanka_puh'); ?>
		<?php echo $form->textField($model,'tekijan_lanka_puh',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_lanka_puh'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_kulunvalvonta'); ?>
		<?php echo $form->textField($model,'tekijan_kulunvalvonta',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_kulunvalvonta'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'salasana'); ?>
		<?php echo $form->textField($model,'salasana',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'salasana'); ?>
	</div>

*/

