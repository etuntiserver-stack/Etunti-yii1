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
 <div class="col-sm-9">
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
		$asiakas_id = '';
		$k = Kohteet::model()->findByPk($t->kohdenID);
		if(isset($k->asiakkaat) and $k->asiakkaat->tyyppi == 'yritys'){$nimi = $k->asiakkaat->yrityksen_nimi;}
		if(isset($k->asiakkaat) and $k->asiakkaat->tyyppi == 'henkilo'){$nimi = $k->asiakkaat->yhteyshenkilo;}
		if(isset($k->asiakkaat)){
			$puhelin = $k->asiakkaat->puhelin;
			$asiakas_id = $k->asiakkaat->id;
		}
		if(isset($k->id)){$osoite = '#'.$k->id.' '.$k->osoite;}
	?>
	<tr>
	 <td><?=date("d.m.Y", strtotime($t->time))?></td>
	 <td>
		<i class="pull-right link text-danger asiakas_pois fa fa-trash" asiakas_id="<?=$asiakas_id?>" nimi="<?=$nimi?>"></i>
		<?=$nimi?>
	 </td>
	 <td><?=$puhelin?></td>
	 <td>
		<i class="pull-right link text-danger kohde_pois fa fa-trash"></i>
		<?=$osoite?>
	 </td>
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
 <div class="col-sm-9">
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

<div class="row">
 <div class="col-sm-3">
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
 </div>
 <?php if( !empty($this->VinkkiLaskin()[0]) and count($this->VinkkiLaskin()[1]) > 0 ) : ?>
 <div class="col-sm-9">
	<hr>
	<legend><h3><?php echo Yii::t('main', 'Taulu'); ?></h3></legend>
	<h4><?= Yii::t('main', 'Vinkit vanhentunut'); ?></h4>

	<div class="section fill mb5" style="height:280px;overflow: auto">
	<table class="table table-bordered table-striped">
	<tr>
	<th>Vinkin pvm.</th>
	<th>Nimi</th>
	<th>Puhelin</th>
	<th>Sähköposti</th>
	</tr>
	<?php foreach($this->VinkkiLaskin()[1] as $t) : ?>
	<?php 
		$puhelin = '';
		$loppu_pvm = '';
		if( strtotime($t->time) > strtotime($this->VinkkiLaskin()[0]) ){ continue; }
	?>
	<tr>
	 <td><?=date("d.m.Y", strtotime($t->time))?></td>
	 <td><?=$t->nimi;?></td>
	 <td><?=$t->puhelin?></td>
	 <td><?=$t->sahkoposti?></td>
	</tr>
	<?php endforeach; ?>
	</table>
	</div>
 </div>
 <?php endif; ?>
</div>
	<div class="section">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('main', 'Luo') : Yii::t('main', 'Tallenna'),array('class'=>'btn btn-primary myBgColors')); ?>
	</div>




<?php $this->endWidget(); ?>








     <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>

     <div id="showres" class="modal fade" tabindex="-1" role="dialog">
        <!-- Admin Form Popup -->
        <div id="modal-form" class=" popup-basic popup-lg admin-form mfp-with-anim mfp-hide">
          <div class="panel">
            <div class="panel-heading">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size:170%">
				<span aria-hidden="true">&times;</span>
			</button>
              <span class="panel-title"></span>
            </div>
            <!-- end .panel-heading section -->

            <form method="post" action="/" id="comment">
              <div class="panel-body p25">

              </div>
              <!-- end .form-body section -->

              <div class="panel-footer">
		<button type="button" class="button btn-default" data-dismiss="modal" aria-label="Close">Sulje</button>
                <button type="button" class="button btn-danger">Poista</button>
              </div>
              <!-- end .form-footer section -->
            </form>
          </div>
          <!-- end: .panel -->
        </div>
        <!-- end: .admin-form -->
     </div>




<script>
$(document).ready(function(){
  $(".asiakas_pois").click(function(){

	var nimi = $(this).attr('nimi');
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tietosuoja/asiakas_poisto?asiakas_id='+$(this).attr('asiakas_id'),
           type: "GET",
           //data: {"tarjousPainike" : "true"},
           success: function(html){
		$('#showres').modal();
		$('#showres .panel-title').html( '<b>' + nimi + '</b> poistaminen');
		$('#showres .panel-body').html(html);
           },
	   error:function(data){
		alert('Kohdetta ei löydy! Päivitä sivu!');
	   }
        });
  });
});
</script>


