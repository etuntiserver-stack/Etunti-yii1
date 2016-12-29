<?php
/* @var $this ViestintaController */
/* @var $model Viestinta */
/* @var $form CActiveForm */

  $admin = '';
$ad = Administrators::model()->findbypk(Yii::app()->user->adminID);
if(isset($ad->adm_nimi))
  $admin = $ad->id.",".$ad->adm_nimi;


?>

<div class="row form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'viestinta-form',
	'enableAjaxValidation'=>false,
)); ?>


	<?php 
	if(empty($ad->adm_email)){
	echo CHtml::link('Sähköposti puutuu vastauksen varten','/index.php/administrators/update?id='.$ad->id,array('class'=>'btn btn-danger'));
	} else {
	?>

	<?php echo $form->errorSummary($model); ?>

		<?php echo $form->hiddenField($model,'pvm',array('value'=>date("Y-m-d H:i:s"))); ?>
		<?php echo $form->hiddenField($model,'admin',array('value'=>$admin,'class'=>'form-control','readonly'=>'yes')); ?>

  <div class="col-sm-4">
	<div class="section fill mb5">
		<?php
		$site = Yii::app()->createController('Site');

		if(isset($_GET['tid']))
		{

		  $tt = Tyontekijat::model()->findbypk($_GET['tid']);
		  if(isset($tt->tekijan_nimi))
		  echo '<label>'.Yii::t('main','Saaja: ').' '.$this->etuSukunimi($tt->id).'</label>';

		  echo $form->hiddenField($model,'tekija',array('value'=>$_GET['tid'],'class'=>'form-control','readonly'=>'yes'));
		} else {

		  if(isset($model->id))
		  {


		  preg_match('/tt_(.*?),/', $model->admin, $matches);
		  if(isset($matches[1])) $model->tekija = $matches[1];

		  echo $form->labelEx($model,'tekija').'<br>';

		  $list = $site[0]->tyontekiatArrayList(1);

        	  echo $form->dropDownList($model, 'tekija', $list,
			array('class'=>'form-control','readonly'=>'yes'));


		  } else {


   // Toimialue
   $list = array();
   $criteria = new CDbCriteria();
   $criteria->order = " select_type ";
   $criteria->condition = " select_type='tyo_toimialue' ";
   $l = Valikkoot::model()->findAll($criteria);
   foreach($l as $v)
   $list[$v->value] = $v->value;

   echo CHtml::dropDownList('siivous', 'siivous', $list,
   array('empty'=>Yii::t('main', 'Toimialue'),'class'=>'form-control form-group','id'=>'tekijanToimialue'));



		  echo $form->labelEx($model,'tekija').'<br>';
		  $list = $site[0]->tyontekiatArrayList(1);
        	  echo $form->dropDownList($model, 'tekija', $list,array('class'=>'form-control', 'multiple'=>'yes'));

?>
<script>
$(document).ready(function(){


$(document).delegate("#tekijanToimialue","change",function(){

	var tekijanToimialue = $(this).val();


        $.ajax({
           url: 'toimialue',
           type: "POST",
           data: { "tekijanToimialue" : tekijanToimialue },
           success: function(data){
		var d = JSON.parse(data);
		console.log(d);

		$("#Viestinta_tekija").val(d);
		// Then refresh
		$("#Viestinta_tekija").multiselect("refresh");

           }
        });

});



$('#Viestinta_tekija').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: 'Tyhjä',
	selectAllText: 'Valitse kaikki',
	allSelectedText: 'Kaikki',
	nSelectedText: 'valittu',
	numberDisplayed: 0,
	buttonWidth: '100%',
        maxHeight: 300,
});

});
</script>
<?php


		  }
		}
        	?>
		<?php echo $form->error($model,'tekija'); ?>
	</div>

  </div>
</div>



	<?php if(isset($model->id)) : ?>

<div class="row form">
  <div class="col-sm-6">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'viesti'); ?>
		<?php echo $form->textArea($model,'viesti',array('value'=>'','rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'viesti'); ?>
	</div>

  </div><div class="col-sm-6">

	<div class="section fill mb5">
		<?php  echo $form->hiddenField($model,'edellinen_viesti',array('value'=>$model->viesti,'rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->labelEx($model,'edellinen_viesti'); ?><br><br>
		<p><?php echo str_replace("\n","<br>",$model->viesti); ?></p>
		<?php echo $form->error($model,'edellinen_viesti'); ?>
	</div>

  </div>
</div>
	<?php else : ?>

<div class="row form">
  <div class="col-sm-4">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'viesti'); ?>
		<?php echo $form->textArea($model,'viesti',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'viesti'); ?>
	</div>

  </div>
</div>
	<?php endif; ?>

<br>

<div class="row form">
  <div class="col-sm-3">
	<div class="section fill mb5">
	<?php if(!empty($ad->adm_email)): ?>
	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'LÄHETÄ' : 'LÄHETÄ',array('class'=>'btn btn-sm btn-primary myBgColors')); ?>
	</div>
	<?php endif; 
	} 
	?>
	</div>
  </div>
</div>

<?php $this->endWidget(); ?>

