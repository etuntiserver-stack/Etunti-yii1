<?php


?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'asiakkaat-form',
	'enableAjaxValidation'=>false, // ala laita true, saat monta asiakaita update aikana netvisorissa
)); ?>


<div class="row">
  <div class="col-sm-3">
	<legend><h3><?php echo Yii::t('main', 'Muoka lähete'); ?></h3>
	<?php
	if(isset($model->asiakkaat->id)){
		echo $model->asiakkaat->Fullname;
	}
	?></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'laskutettu'); ?>
		<?php 
        	$tal = array(
			0=>'Ei',
			1=>'Kyllä'
		);
		echo $form->dropDownList($model,'laskutettu', $tal, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'laskutettu'); ?>
	</div>

	<div class="section">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('main', 'Luo') : Yii::t('main', 'Tallenna'),array('class'=>'btn btn-primary myBgColors luoTallennaAsiakas')); ?>
	</div>
  </div>
</div>

<?php $this->endWidget(); ?>

<br>
		<table class="table table-striped rivintaulu">
		<tr>
		<th class="th_tuote">Tuote</th>
		<th>Hinta</th>
		<th width="90">Yksikkö</th>
		<th>Määrä</th>
		<th>ALV</th>
		<th>Veroton</th>
		<th>Yhteensä</th>
		<th>Free text</th>
		</tr>
		<tbody>
		<?php 
		$key = 0; 
		$yhteensa_total = 0;
		?>
		<?php foreach(json_decode($model->tab_array, true) as $mob) : ?>
		<?php
			if( isset($mob['tp_id']) ){ $tp_id = $mob['tp_id']; }
			if( isset($mob['nimike']) ){ $nimike = $mob['nimike']; }
			if( isset($mob['kpl']) ){ $kpl = $mob['kpl']; }
			if( isset($mob['hinta']) ){ $hinta = $mob['hinta']; }
			if( isset($mob['alv']) ){ $alv = $mob['alv']; }
			if( isset($mob['yksikko']) ){ $yksikko = $mob['yksikko']; }
			if( isset($mob['freetext']) ){ $freetext = $mob['freetext']; }

			// <-- ALV laskin
			$veroton 	= 0;
			$yht 		= 0;
			if(!is_numeric($hinta) or !is_numeric($kpl) or !is_numeric($alv)){
				echo '<div class="alert bg-danger">
					Hinta:'.$hinta.' KPL:'.$kpl.' ALV:'.$alv.'<br>
					<h1>Hinta tai ALV ei saa olla teksti muodossa tai pilkulla.</h1>
				</div>';
				break;
			}
			if( $model->alvsis == 0 ){
				$laske = ($hinta*$kpl)/100*$alv;
				$veroton = round(($hinta*$kpl), 2);
				$yht = $laske+$veroton;
			}
			if( $model->alvsis == 1 ){
				$yht = $hinta*$kpl;
				$jakaa = '1.'.$alv;
				$l = $yht/$jakaa;
				$veroton = round($l, 2);
			}
			//     ALV laskin -->
			$yhteensa_total += $yht;
		?>
		<tr>
		<td class="input_tp_id"><?=$tp_id?></td>
		<td class="input_nimike"><?=$nimike?></td>
		<td class="input_hinta"><?=number_format($hinta, 2, ',', ' ')?></td>
		<td class="input_yksikko"><?=$yksikko?></td>
		<td class="input_kpl"><?=$kpl?></td>
		<td class="input_alv"><?=$alv?></td>
		<td class="input_veroton"><?=number_format($veroton, 2, ',', ' ')?></td>
		<td class="input_yhteensa"><?=number_format($yht, 2, ',', ' ')?></td>
		<td class="input_freetext"><?=$freetext?></td>
		</tr>
		<?php endforeach; ?>
		<tr>
		    <th><h3><?=Yii::t('main', 'Yhteensä')?>: <?=number_format($yhteensa_total, 2, ',', ' ')?>&euro;</h3></th>
		</tr>
		</table>
