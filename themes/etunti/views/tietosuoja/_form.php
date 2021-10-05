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
	<legend><h3><?php echo Yii::t('main', 'Asiakkuus'); ?></h3></legend>
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
<?php /*
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'asiakas_viesti'); ?>
		<?php echo $form->textarea($model,'asiakas_viesti', array('class' => 'form-control')); ?>
		<?php echo $form->error($model,'asiakas_viesti'); ?>
	</div>
*/ 
	$mobileLaskin = $this->AsiakasMobileLaskin();
	if(isset($mobileLaskin[1])) {
		echo count($this->AsiakasMobileLaskin()[1]);
	} else {
		echo 0;
	}
  
  //exit;
?>

 </div>
 <?php $a_arr = $this->AsiakasMobileLaskin(); ?>
 <?php if( !empty($a_arr[0]) and count($a_arr[1]) > 0 ) : ?>
 <div class="col-sm-9">
	<legend><h3><?php echo Yii::t('main', 'Taulu'); ?></h3></legend>
	<h4><?= Yii::t('main', 'Asiakkaat joille ei ole kirjattu töitä säilytysaika huomioiden'); ?></h4>

	<div class="section fill mb5" style="height:280px;overflow: auto">
	<table class="table table-bordered table-striped">
	<tr>
	<th>Asiakas</th>
	<th>Kohde</th>
	<th>Puhelin</th>
	</tr>
	<?php foreach($a_arr[1] as $k) : ?>
	<?php 
		$last_time = '';
		$nimi = '';
		$puhelin = '';
		$osoite = '#'.$k->id.' '.$k->osoite;
		$asiakas_id = '';
		$kohde_id = $k->id;

		if(isset($k->asiakkaat)){$nimi = $k->asiakkaat->Fullname;}
		if(isset($k->asiakkaat)){
			$puhelin = $k->asiakkaat->puhelin;
			$asiakas_id = $k->asiakkaat->id;
		}
		if(empty($k->asiakas_id)){ continue; }
	       	$criteria = new CDbCriteria();
	       	$criteria->order = " osoite ";
		// HUOMIO - kohde sarake on VARCHAR niin pakko suodata vain numerona  - kohde REGEXP '^[[:digit:]]+$'
	       	$criteria->condition = " 
			id!='".$k->id."' AND asiakas_id='".$k->asiakas_id."' 
			AND ( id IN ( SELECT kohdenID FROM sivexkuitti WHERE DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN ".$a_arr[0]." AND CURDATE() )
				OR id IN ( SELECT kohde REGEXP '^[[:digit:]]+$' FROM sivex_tvuoro WHERE kohde REGEXP '^[[:digit:]]+$' AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') > ".date('Y-m-d')." )
			)
		";
		$k_all = Kohteet::model()->findAll($criteria);
	?>
	<tr>
	 <td>
		<?php if( count($k_all) == 0): ?>
		<i class="pull-right link text-danger asiakas_pois fa fa-trash" asiakas_id="<?=$asiakas_id?>" nimi="<?=$nimi?>"></i>
		<?php endif; ?>
		 <?=$nimi?>
	 </td>
	 <td>
		<i class="pull-right link text-danger kohde_pois fa fa-trash" kohde_id="<?=$kohde_id?>" nimi="<?=$osoite?>"></i>
		<?=$osoite?>
	 </td>
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

	<legend><h3><?php echo Yii::t('main', 'Työntekijät'); ?></h3></legend>
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
<?php /*
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyontekija_viesti'); ?>
		<?php echo $form->textarea($model,'tyontekija_viesti', array('class' => 'form-control')); ?>
		<?php echo $form->error($model,'tyontekija_viesti'); ?>
	</div>
*/ ?>
 </div>

 <?php if( !empty($this->TyontekijaTyosuhdetLaskin()[0]) and count($this->TyontekijaTyosuhdetLaskin()[1]) > 0 ) : ?>
 <div class="col-sm-9">
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
		   //if( strtotime($t->tyosuhteet->loppu) > strtotime($this->TyontekijaTyosuhdetLaskin()[0]) ){ continue; }
		}
	?>
	<tr>
	 <td><?=$loppu_pvm?></td>
	 <td>
		<i class="pull-right link text-danger tid_pois fa fa-trash" tid="<?=$t->id?>" nimi="<?=$this->etuSukunimi($t->id);?>"></i>
		<?=$this->etuSukunimi($t->id);?>
	 </td>
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

	<legend><h3><?php echo Yii::t('main', 'eDico'); ?></h3></legend>
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
<?php /*
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'edico_vinkki_viesti'); ?>
		<?php echo $form->textarea($model,'edico_vinkki_viesti', array('class' => 'form-control')); ?>
		<?php echo $form->error($model,'edico_vinkki_viesti'); ?>
	</div>
*/ ?>
 </div>
 <?php if( !empty($this->VinkkiLaskin(null)[0]) and count($this->VinkkiLaskin(null)[1]) > 0 ) : ?>
 <div class="col-sm-9">

	<legend><h3><?php echo Yii::t('main', 'Taulu'); ?> <!--<i class="link text-danger vinkit_pois fa fa-trash"></i>--> </h3></legend>
	<h4><?= Yii::t('main', 'Vinkit vanhentunut'); ?> <?=date("d.m.Y", strtotime($this->VinkkiLaskin(null)[0]))?></h4>

	<div class="section fill mb5" style="height:280px;overflow: auto">
	<table class="table table-bordered table-striped">
	<tr>
	<th>Vinkin pvm.</th>
	<th>Nimi</th>
	<th>Puhelin</th>
	<th>Sähköposti</th>
	</tr>
	<?php foreach($this->VinkkiLaskin(null)[1] as $t) : ?>
	<?php 
		$puhelin = '';
		$loppu_pvm = '';
		//if( strtotime($t->time) > strtotime($this->VinkkiLaskin(null)[0]) ){ continue; }
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
                <button type="button" class="button btn-danger poisto-painike">Poista</button>
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
	var asiakas_id = $(this).attr('asiakas_id');

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tietosuoja/asiakas_poisto?asiakas_id='+ asiakas_id,
           type: "GET",
           //data: {"tarjousPainike" : "true"},
           success: function(html){
		$('#showres').modal();
		$('#showres .panel-title').html( '<b>' + nimi + '</b> poistaminen');
		$('#showres .poisto-painike').attr('asiakas_id', asiakas_id).addClass('asiakas');
		$('#showres .panel-body').html(html);
           },
	   error:function(data){
		//alert('Kohdetta ei löydy! Päivitä sivu!');
	   }
        });
  });

  $(document).delegate(".poisto-painike.asiakas","click",function(){
	var asiakas_id = $(this).attr('asiakas_id');
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tietosuoja/asiakas_poisto?asiakas_id='+ asiakas_id,
           type: "POST",
           data: {"action" : "delete"},
           success: function(html){
		//console.log(html);
		window.location.href="index?id=1";
           },
	   error:function(data){
		//alert('Kohdetta ei löydy! Päivitä sivu!');
	   }
        });
  });

  $(".kohde_pois").click(function(){

	var nimi = $(this).attr('nimi');
	var kohde_id = $(this).attr('kohde_id');

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tietosuoja/kohde_poisto?kohde_id='+ kohde_id,
           type: "GET",
           //data: {"tarjousPainike" : "true"},
           success: function(html){
		$('#showres').modal();
		$('#showres .panel-title').html( '<b>' + nimi + '</b> poistaminen');
		$('#showres .poisto-painike').attr('kohde_id', kohde_id).addClass('kohde');
		$('#showres .panel-body').html(html);
           },
	   error:function(data){
		//alert('Kohdetta ei löydy! Päivitä sivu!');
	   }
        });
  });

  $(document).delegate(".poisto-painike.kohde","click",function(){
	var kohde_id = $(this).attr('kohde_id');
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tietosuoja/kohde_poisto?kohde_id='+ kohde_id,
           type: "POST",
           data: {"action" : "delete"},
           success: function(html){
		//console.log(html);
		window.location.href="index?id=1";
           },
	   error:function(data){
		alert('Kohdetta ei löydy! Päivitä sivu!');
	   }
        });
  });

  $(".tid_pois").click(function(){

	var nimi = $(this).attr('nimi');
	var tid = $(this).attr('tid');

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tietosuoja/tid_poisto?tid='+ tid,
           type: "GET",
           //data: {"tarjousPainike" : "true"},
           success: function(html){
		$('#showres').modal();
		$('#showres .panel-title').html( '<b>' + nimi + '</b> poistaminen');
		$('#showres .poisto-painike').attr('tid', tid).addClass('tid');
		$('#showres .panel-body').html(html);
           },
	   error:function(data){
		alert('Kohdetta ei löydy! Päivitä sivu!');
	   }
        });
  });

  $(document).delegate(".poisto-painike.tid","click",function(){
	var tid = $(this).attr('tid');
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tietosuoja/tid_poisto?tid='+ tid,
           type: "POST",
           data: {"action" : "delete"},
           success: function(html){
		//console.log(html);
		window.location.href="index?id=1";
           },
	   error:function(data){
		alert('Kohdetta ei löydy! Päivitä sivu!');
	   }
        });
  });

  $(document).delegate(".vinkit_pois","click",function(){
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tietosuoja/vinkit_poisto',
           success: function(html){
		//console.log(html);
		window.location.href="index?id=1";
           },
	   error:function(data){
		alert('Kohdetta ei löydy! Päivitä sivu!');
	   }
        });
  });

});
</script>


