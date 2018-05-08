<?php
/* @var $this AvaimetController */
/* @var $model Avaimet */
/* @var $form CActiveForm */

if( !isset($model->id) and isset($_GET['asiakas_id']) and isset($_GET['kohde_id']) and !empty($_GET['asiakas_id']) and !empty($_GET['kohde_id']) ){
	$model->asiakas_id = $_GET['asiakas_id'];
	$model->kohde = $_GET['kohde_id'];
}
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'avaimet-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>
<div class="row form">
 <div class="col-sm-3">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'avainnumero'); ?>
		<?php echo $form->textField($model,'avainnumero',array('size'=>60,'maxlength'=>255, 'class' => 'form-control')); ?>
		<?php echo $form->error($model,'avainnumero'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'asiakas_id'); ?>
		<?php
			$a_controller = Yii::app()->createController('Asiakkaat');
			$list = $a_controller[0]->asiakkaatArrHelper(true);

        		echo $form->dropDownList($model, 'asiakas_id', $list,
			array('empty'=>'Valitse','class'=>'form-control'));
		
        	?>
		<?php echo $form->error($model,'asiakas_id'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kohde'); ?>
		<?php
	       	$criteria = new CDbCriteria();
	       	$criteria->condition = " aktiivinen=1 ";

		// <-- Tyoryhmat
		$site = Yii::app()->createController('Site');
		$arr = $site[0]->TyoryhmatHelper();
		$ids = implode(",", $arr);
		if( count($arr) > 0 ){
			$criteria->condition = " tyoryhma IN ($ids) ";
		}
		//    Tyoryhmat -->

	       	$criteria->order = " osoite ";
		$kohteet = Kohteet::model()->findAll($criteria);
        	echo $form->dropDownList($model, 'kohde', CHtml::listData($kohteet, 'id', 'osoite'),
		array('empty' => 'Valitse', 'class'=>'form-control'
		));
		?>
		<?php echo $form->error($model,'kohde'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tid'); ?>
		<?php
	       	$criteria = new CDbCriteria();
	       	$criteria->condition = " aktiivinen=1 ";

			// <-- Tyoryhmat
			$tt = Yii::app()->createController('Tyontekijat');
			$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper(null);
			$ids = implode(",", $tt_arr);
			if( count($tt_arr) > 0 ){
	        		$criteria->addCondition (' id IN ('.$ids.') ');
			}
			//    Tyoryhmat -->

		$tt = Tyontekijat::model()->findAll($criteria);
        	echo $form->dropDownList($model, 'tid', CHtml::listData($tt, 'id', 'FullName'),
		array('empty' => 'Valitse', 'class'=>'form-control'
		));
		?>
		<?php echo $form->error($model,'tid'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'sijainti'); ?>
		<?php echo $form->textField($model,'sijainti',array('size'=>60,'maxlength'=>255, 'class' => 'form-control')); ?>
		<?php echo $form->error($model,'sijainti'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'lisatiedot'); ?>
		<?php echo $form->textArea($model,'lisatiedot',array('rows'=>6, 'cols'=>50, 'class' => 'form-control')); ?>
		<?php echo $form->error($model,'lisatiedot'); ?>
	</div>
<?php /*
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php
        	$l = array(0=>0, 1=>1, 2=>2, 3=>3);

        	echo $form->dropDownList($model, 'status', $l,
		array('class'=>'form-control'
		));
        	?>
		<?php echo $form->error($model,'status'); ?>
	</div>
*/ ?>
 </div>
</div><!-- form -->

<br>

<div class="row form">
  <div class="col-sm-3">
	<div class="section fill mb5">
	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-sm btn-primary myBgColors')); ?>
	</div>
	</div>
  </div>
</div>

<?php $this->endWidget(); ?>


<script type="text/javascript">
$(document).ready(function(){


 $('#Avaimet_asiakas_id').change(function(){

	var thisVal = $(this).val();
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/crmSopimukset/get_kohde?id=' + thisVal,
           //type: "POST",
           //data: { },
           success: function(data){
		var data = JSON.parse(data);
		console.log(data);
		if(data['options'])
		{
			$('#Avaimet_kohde').html(data['options']);
		}
		if(data['asiakas_sahkoposti'])
		{
			$('#Avaimet_asiakkaan_sahkoposti').val(data['asiakas_sahkoposti']);
		}
		if(data['asiakas_tiedot'])
		{
			$('#Avaimet_tiedot').html(data['asiakas_tiedot']);
		}

           }
        });

 });


});
</script>

