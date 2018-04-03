<?php
/* @var $this TietosuojaController */
/* @var $model Tietosuoja */
/* @var $form CActiveForm */
?>



<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tietosuoja-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

<div class="row">
 <div class="col-sm-3">
	<legend><h3><?php echo Yii::t('main', 'Asiakkaan tiedot'); ?></h3></legend>
	<h4><?= Yii::t('main', 'Asiakkaan henkilötietojen säilyttämisen'); ?></h4>

	<?php echo $form->errorSummary($model); ?>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'asiakas_oikeusperuste'); ?>
		<?php echo $form->textField($model,'asiakas_oikeusperuste', array('class' => 'form-control')); ?>
		<?php echo $form->error($model,'asiakas_oikeusperuste'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'asiakas_kayttotarkoitus'); ?>
		<?php echo $form->textarea($model,'asiakas_kayttotarkoitus', array('class' => 'form-control')); ?>
		<?php echo $form->error($model,'asiakas_kayttotarkoitus'); ?>
	</div>

	<div class="section fill mb5 row">
	   <div class="col-sm-6">
		<?php echo $form->labelEx($model,'asiakas_sailytysajan_tyyppi'); ?>
		<?php
		$list = array(0=>'Päivä',1=>'kk',2=>'Vuosi');
        	echo $form->dropDownList($model, 'asiakas_sailytysajan_tyyppi', $list,
		array('class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'asiakas_sailytysajan_tyyppi'); ?>
	   </div>

	   <div class="col-sm-6">
		<?php echo $form->labelEx($model,'asiakas_sailytysaika_lukumaara'); ?>
		<?php echo $form->numberField($model,'asiakas_sailytysaika_lukumaara', array('class' => 'form-control')); ?>
		<?php echo $form->error($model,'asiakas_sailytysaika_lukumaara'); ?>
	   </div>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'asiakas_viesti'); ?>
		<?php echo $form->textarea($model,'asiakas_viesti', array('class' => 'form-control')); ?>
		<?php echo $form->error($model,'asiakas_viesti'); ?>
	</div>

 </div>

 <?php if( !empty($this->AsiakasMobileLaskin()[0]) and count($this->AsiakasMobileLaskin()[1]) > 0 ) : ?>
 <div class="col-sm-6">
	<legend><h3><?php echo Yii::t('main', 'Taulu'); ?></h3></legend>
	<h4><?= Yii::t('main', 'Asiakkaat jotka vanhentunut'); ?></h4>

	<div class="section fill mb5" style="height:280px;overflow: auto">
	<label>Mobile taulussa rivit ennen kun <?= date("d.m.Y", strtotime($this->AsiakasMobileLaskin()[0]))?> </label>
	<table class="table table-bordered table-striped">
	<tr>
	<th>Viimeinen käynti</th>
	<th>Asiakas</th>
	<th>Puhelin</th>
	<th>Kohde</th>
	</tr>
	<?php foreach($this->AsiakasMobileLaskin()[1] as $t) : ?>
	<?php 
		if( strtotime($t->time) > strtotime($this->AsiakasMobileLaskin()[0]) ){ continue; }

		$nimi = '';
		$puhelin = '';
		$osoite = '';
		$k = Kohteet::model()->findByPk($t->kohdenID);
		if(isset($k->asiakkaat) and $k->asiakkaat->tyyppi == 'yritys'){$nimi = $k->asiakkaat->yrityksen_nimi;}
		if(isset($k->asiakkaat) and $k->asiakkaat->tyyppi == 'henkilo'){$nimi = $k->asiakkaat->yhteyshenkilo;}
		if(isset($k->asiakkaat)){$puhelin = $k->asiakkaat->puhelin;}
		if(isset($k->id)){$osoite = '#'.$k->id.' '.$k->osoite;}
	?>
	<tr>
	 <td><?=date("d.m.Y", strtotime($t->time))?></td>
	 <td><?=$nimi?></td>
	 <td><?=$puhelin?></td>
	 <td><?=$osoite?></td>
	</tr>
	<?php endforeach; ?>
	</table>
	</div>
 </div>
 <?php endif; ?>

</div>
<div class="row">
 <div class="col-sm-3">

	<hr>
	<h4><?= Yii::t('main', 'Työntekijän henkilötietojen säilyttämisen'); ?></h4>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyontekija_oikeusperuste'); ?>
		<?php echo $form->textField($model,'tyontekija_oikeusperuste', array('class' => 'form-control')); ?>
		<?php echo $form->error($model,'tyontekija_oikeusperuste'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyontekija_kayttotarkoitus'); ?>
		<?php echo $form->textarea($model,'tyontekija_kayttotarkoitus', array('class' => 'form-control')); ?>
		<?php echo $form->error($model,'tyontekija_kayttotarkoitus'); ?>
	</div>

	<div class="section fill mb5 row">
	   <div class="col-sm-6">
		<?php echo $form->labelEx($model,'tyontekija_sailytysajan_tyyppi'); ?>
		<?php
		$list = array(0=>'Päivä',1=>'kk',2=>'Vuosi');
        	echo $form->dropDownList($model, 'tyontekija_sailytysajan_tyyppi', $list,
		array('class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'tyontekija_sailytysajan_tyyppi'); ?>
	   </div>

	   <div class="col-sm-6">
		<?php echo $form->labelEx($model,'tyontekija_sailytysaika_lukumaara'); ?>
		<?php echo $form->numberField($model,'tyontekija_sailytysaika_lukumaara', array('class' => 'form-control')); ?>
		<?php echo $form->error($model,'tyontekija_sailytysaika_lukumaara'); ?>
	   </div>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyontekija_viesti'); ?>
		<?php echo $form->textarea($model,'tyontekija_viesti', array('class' => 'form-control')); ?>
		<?php echo $form->error($model,'tyontekija_viesti'); ?>
	</div>

 </div>

 <?php if( !empty($this->TyontekijaTyosuhdetLaskin()[0]) and count($this->TyontekijaTyosuhdetLaskin()[1]) > 0 ) : ?>
 <div class="col-sm-6">
	<hr>
	<legend><h3><?php echo Yii::t('main', 'Taulu'); ?></h3></legend>
	<h4><?= Yii::t('main', 'Työntekijät jotka vanhentunut'); ?></h4>

	<div class="section fill mb5" style="height:280px;overflow: auto">
	<table class="table table-bordered table-striped">
	<tr>
	<th>Työsyhteet loppu pvm.</th>
	<th>Nimi</th>
	<th>Puhelin</th>
	</tr>
	<?php foreach($this->TyontekijaTyosuhdetLaskin()[1] as $t) : ?>
	<?php 
		$puhelin = '';
		$loppu_pvm = '';
		if(isset($t->tyosuhteet->id)){
		   $loppu_pvm = $t->tyosuhteet->loppu;
		   if( strtotime($t->tyosuhteet->loppu) > strtotime($this->TyontekijaTyosuhdetLaskin()[0]) ){ continue; }
		}
	?>
	<tr>
	 <td><?=$loppu_pvm?></td>
	 <td><?=$this->etuSukunimi($t->id);?></td>
	 <td><?=$puhelin?></td>
	</tr>
	<?php endforeach; ?>
	</table>
	</div>

 </div>
 <?php endif; ?>

</div>

<div class="row">
 <div class="col-sm-3">

<?php /*
	<hr>
	<h4><?= Yii::t('main', 'Onlinevarauksen henkilötietojen säilyttämisen'); ?></h4>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'onlinevaraus_oikeusperuste'); ?>
		<?php echo $form->textField($model,'onlinevaraus_oikeusperuste', array('class' => 'form-control')); ?>
		<?php echo $form->error($model,'onlinevaraus_oikeusperuste'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'onlinevaraus_kayttotarkoitus'); ?>
		<?php echo $form->textarea($model,'onlinevaraus_kayttotarkoitus', array('class' => 'form-control')); ?>
		<?php echo $form->error($model,'onlinevaraus_kayttotarkoitus'); ?>
	</div>

	<div class="section fill mb5 row">
	   <div class="col-sm-6">
		<?php echo $form->labelEx($model,'onlinevaraus_sailytysajan_tyyppi'); ?>
		<?php
		$list = array(0=>'Päivä',1=>'kk',2=>'Vuosi');
        	echo $form->dropDownList($model, 'onlinevaraus_sailytysajan_tyyppi', $list,
		array('class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'onlinevaraus_sailytysajan_tyyppi'); ?>
	   </div>

	   <div class="col-sm-6">
		<?php echo $form->labelEx($model,'onlinevaraus_sailytysaika_lukumaara'); ?>
		<?php echo $form->numberField($model,'onlinevaraus_sailytysaika_lukumaara', array('class' => 'form-control')); ?>
		<?php echo $form->error($model,'onlinevaraus_sailytysaika_lukumaara'); ?>
	   </div>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'onlinevaraus_viesti'); ?>
		<?php echo $form->textarea($model,'onlinevaraus_viesti', array('class' => 'form-control')); ?>
		<?php echo $form->error($model,'onlinevaraus_viesti'); ?>
	</div>
*/ ?>

	<hr>
	<h4><?= Yii::t('main', 'eDico vinkki henkilötietojen säilyttämisen'); ?></h4>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'edico_vinkki_oikeusperuste'); ?>
		<?php echo $form->textField($model,'edico_vinkki_oikeusperuste', array('class' => 'form-control')); ?>
		<?php echo $form->error($model,'edico_vinkki_oikeusperuste'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'edico_vinkki_kayttotarkoitus'); ?>
		<?php echo $form->textarea($model,'edico_vinkki_kayttotarkoitus', array('class' => 'form-control')); ?>
		<?php echo $form->error($model,'edico_vinkki_kayttotarkoitus'); ?>
	</div>

	<div class="section fill mb5 row">
	   <div class="col-sm-6">
		<?php echo $form->labelEx($model,'edico_vinkki_sailytysajan_tyyppi'); ?>
		<?php
		$list = array(0=>'Päivä',1=>'kk',2=>'Vuosi');
        	echo $form->dropDownList($model, 'edico_vinkki_sailytysajan_tyyppi', $list,
		array('class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'edico_vinkki_sailytysajan_tyyppi'); ?>
	   </div>

	   <div class="col-sm-6">
		<?php echo $form->labelEx($model,'edico_vinkki_sailytysaika_lukumaara'); ?>
		<?php echo $form->numberField($model,'edico_vinkki_sailytysaika_lukumaara', array('class' => 'form-control')); ?>
		<?php echo $form->error($model,'edico_vinkki_sailytysaika_lukumaara'); ?>
	   </div>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'edico_vinkki_viesti'); ?>
		<?php echo $form->textarea($model,'edico_vinkki_viesti', array('class' => 'form-control')); ?>
		<?php echo $form->error($model,'edico_vinkki_viesti'); ?>
	</div>

	<div class="section">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('main', 'Luo') : Yii::t('main', 'Tallenna'),array('class'=>'btn btn-primary myBgColors')); ?>
	</div>
 </div>
</div>


<?php $this->endWidget(); ?>


