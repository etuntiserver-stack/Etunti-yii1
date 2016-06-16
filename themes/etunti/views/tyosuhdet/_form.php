<?php
/* @var $this TyosuhdetController */
/* @var $model Tyosuhdet */
/* @var $form CActiveForm */



$d1 = date("Y-m-d");
$d2 = date("Y-m-d", strtotime($model->alku));
$result = (int)abs((strtotime($d1) - strtotime($d2))/(60*60*24*30));

if($result < 12)
  $l = 2;
else
  $l = 2.5;

  if((int)date("n") > 3)
	$c = (int)date("n")-3;
  elseif((int)date("n") == 3)
	$c = 0;
  elseif((int)date("n") == 2)
	$c = 11;
  elseif((int)date("n") == 1)
	$c = 10;

  echo 'L: '.$l.'<br>';
  echo 'C: '.$c.'<br>';
  $pv = $c*$l;
  echo '<br><br>';

  $m = Yii::app()->createController('Mobile');
  $tulos = "true";	

  echo '<div class="row">
	 <div class="col-sm-6">';
  echo '<h3>'.Yii::t('main', 'Vuosiloma laskenta').'</h3>';
  echo '<table class="table table-bordered table-hover">
	<tr>
	<th>'.Yii::t('main', 'Kuukausi').'</th>
	<th>'.Yii::t('main', 'Työpäiviä').'</th>
	<th>'.Yii::t('main', 'Tunnit').'</th>
	</tr>';
  while($c) {
	$from = date("Y-m-01", strtotime("-".$c--." month"));
	$to = date("Y-m-d", strtotime($from." last day +1 month"));
	$tp = $m[0]->TP($model->tid,$from,$to);
	$toteutu = $m[0]->toteutu($model->tid,'palkkataulukko',$from,$to);

	echo '<tr>';
	echo '<td>'.date("d.m.Y",strtotime($from)).' - '.date("d.m.Y",strtotime($to)).'</td>';

	// TP
	echo '<td>';
	   if($tp <= 14)
	   {
		echo '<span class="btn btn-danger btn-block btn-sm">'.$tp.'</span>';
		$tulos = "false";
  	   } else {
		echo '<span class="btn btn-success btn-block btn-sm">'.$tp.'</span>';
	   }
	echo '</td>';


	// Toteutu
	echo '<td>';
	   if((int)$m[0]->num($toteutu[0]) < 35)
	   {
		echo '<span class="btn btn-danger btn-block btn-sm">'.(float)$m[0]->num($toteutu[0]).'</span>';
		$tulos = "false";
  	   } else {
		echo '<span class="btn btn-success btn-block btn-sm">'.(float)$m[0]->num($toteutu[0]).'</span>';
	   }
	echo '</td>';

	echo '</tr>';

	if($tp <= 14 or (int)$m[0]->num($toteutu[0]) < 35)
	{
		$tulos = "false";
		//break;
	}
  }
  echo '</table>';

  if($tulos == 'true')
  echo '<div class="alert alert-success">'.Yii::t('main', 'Vuosiloma päiviä').': '.$pv.'</div>';
  else
  echo '<div class="alert alert-danger">'.Yii::t('main', 'Vuosiloma päiviä ei saa olla').'</div>';

  echo ' </div>
	</div>


	<hr>';


?>



<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tyosuhdet-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

		<?php echo $form->hiddenField($model,'tid'); ?>

<div class="row">
  <div class="col-sm-3">
  <legend>
    <h2><?php echo Yii::t('main', 'TYÖSUHTEET'); ?></h2>
  </legend>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'alku'); ?>
		<?php echo $form->textField($model,'alku',array('size'=>20,'maxlength'=>20,'class'=>'form-control datepicker')); ?>
		<?php echo $form->error($model,'alku'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'loppu'); ?>
		<?php echo $form->textField($model,'loppu',array('size'=>20,'maxlength'=>20,'class'=>'form-control datepicker')); ?>
		<?php echo $form->error($model,'loppu'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyopvm_kk'); ?>
		<?php echo $form->numberField($model,'tyopvm_kk',array('maxlength'=>2,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tyopvm_kk'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'vktyoaika'); ?>
		<?php echo $form->textField($model,'vktyoaika',array('size'=>10,'maxlength'=>10,'class'=>'form-control timepicker_false')); ?>
		<?php echo $form->error($model,'vktyoaika'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'nimike'); ?>
		<?php echo $form->textField($model,'nimike',array('size'=>40,'maxlength'=>40,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'nimike'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'palkkausmuoto'); ?>
		<?php echo $form->textField($model,'palkkausmuoto',array('size'=>30,'maxlength'=>30,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'palkkausmuoto'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tuntihinta'); ?>
		<?php echo $form->textField($model,'tuntihinta',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tuntihinta'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'matka_thinta'); ?>
		<?php echo $form->textField($model,'matka_thinta',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'matka_thinta'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'lippu_kuumaks'); ?>
		<?php echo $form->textField($model,'lippu_kuumaks',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'lippu_kuumaks'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'koe_loppu'); ?>
		<?php echo $form->numberField($model,'koe_loppu',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'koe_loppu'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'koe_hinta'); ?>
		<?php echo $form->textField($model,'koe_hinta',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'koe_hinta'); ?>
	</div>

  </div><div class="col-sm-3">
  <legend>
    <h2><?php echo Yii::t('main', 'VEROPROSENTTI'); ?></h2>
  </legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tuloraja_ajalle'); ?>
		<?php echo $form->textField($model,'tuloraja_ajalle',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tuloraja_ajalle'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'perusprosentti'); ?>
		<?php echo $form->textField($model,'perusprosentti',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'perusprosentti'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'lisaprosentti'); ?>
		<?php echo $form->textField($model,'lisaprosentti',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'lisaprosentti'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kuukaudessa'); ?>
		<?php echo $form->textField($model,'kuukaudessa',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'kuukaudessa'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kahdessa_viikossa'); ?>
		<?php echo $form->textField($model,'kahdessa_viikossa',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'kahdessa_viikossa'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'viikossa'); ?>
		<?php echo $form->textField($model,'viikossa',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'viikossa'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'paivassa'); ?>
		<?php echo $form->textField($model,'paivassa',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'paivassa'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'atk_varten'); ?>
		<?php echo $form->textField($model,'atk_varten',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'atk_varten'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'yksi_tuloraja'); ?>
		<?php echo $form->textField($model,'yksi_tuloraja',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'yksi_tuloraja'); ?>
	</div>

  </div>
</div><!-- form -->

	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary myBgColors')); ?>
	</div>

<?php $this->endWidget(); ?>




<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.mask.js"></script>


<script type="text/javascript">
$(document).ready(function(){

  $('#Tyosuhdet_vktyoaika').mask('00:00',{
        placeholder: "__:__"
  });

});
</script>


