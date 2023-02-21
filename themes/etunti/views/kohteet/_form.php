<?php
/* @var $this KohteetController */
/* @var $model Kohteet */
/* @var $form CActiveForm */

     $tas = array();
   if(isset(Yii::app()->user->adminPaketti)) 
     $tas = explode(",",Yii::app()->user->adminPaketti);


$site = Yii::app()->createController('Site');

if(!isset($model->id)){ $model->alv = 24; }
$asetukset = Asetukset::model()->findbypk(1);
$tietoja = $asetukset->tyovuoro_tietoja_mobiilisovellukseen;
if(empty($model->tietoja))
	$model->tietoja = $tietoja;
?>
<div class="row">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'kohteet-form',
	'enableAjaxValidation'=>false,
)); ?>



	<?php echo $form->errorSummary($model); ?>

  <div class="col-sm-3">

		<?php echo $form->hiddenField($model,'gps_sijainti',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'asiakas_id'); ?>
    		<?php 
       		$criteria = new CDbCriteria();
		$criteria->order = " etunimi ";

        	$a = Asiakkaat::model()->findAll($criteria);
		echo '<select name="Kohteet[asiakas_id]" class="form-control" id="Kohteet_asiakas_id">';

		if(isset($model->asiakas_id) and !empty($model->asiakas_id))
		{
        	  $aon = Asiakkaat::model()->findbypk($model->asiakas_id);
		  if(isset($aon->id))
		  {
		    echo '<option value="'.$aon->id.'">'.$aon->Fullname.'</option>';
		  }

		} else {
	        echo '<option></option>';
		}

		foreach($a as $aa)
		{
		    echo '<option value="'.$aa->id.'">'.$aa->Fullname.'</option>';
		}
		echo '</select>';
		?>
	</div>


	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'etu_suku_nimet'); ?>
		<?php echo $form->textField($model,'etu_suku_nimet',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'etu_suku_nimet'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'ensisijainen'); ?>
		<?php 
			$check = Kohteet::model()->find("asiakas_id='".$model->asiakas_id."' AND ensisijainen=1");
			$lista = [0 => 'Ei', 1 => 'Kyllä'];
			if(isset($check->id) and $check->id != $model->id)
				echo '<p><b>'.$check->osoite.'</b></p>';
			else
				echo $form->dropDownList($model, 'ensisijainen', $lista, array('class'=>'form-control')); 
		?> 
		<?php echo $form->error($model,'ensisijainen'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'osoite'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'pnumero'); ?>
		<?php echo $form->textField($model,'pnumero',array('size'=>7,'maxlength'=>7,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'pnumero'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kaupunki'); ?>
		<?php echo $form->textField($model,'kaupunki',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'kaupunki'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>72,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'puh_nro'); ?> <?php if(!empty($model->puh_nro)): ?><a href="tel:<?php echo $model->puh_nro; ?>">***soita***</a><?php endif; ?>
		<?php echo $form->textField($model,'puh_nro',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'puh_nro'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'toimipaikka'); ?>
		<?php echo $form->textField($model,'toimipaikka',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'toimipaikka'); ?>
	</div>

	<legend><h3>Lasku hinnasto.</h3></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'laskurivi_tyyppi'); ?>
		<?php 
			$lista = ['tunti' => 'Kirjaus muoto - h', 'kk' => 'Kuukausi muoto - kk', 'kpl' => 'Kertakäynti muoto - kpl'];
			echo $form->dropDownList($model, 'laskurivi_tyyppi', $lista, array('class'=>'form-control')); 
		?> 
		<?php echo $form->error($model,'laskurivi_tyyppi'); ?>
	</div>
	
	<div class="section fill mb5">
		<?php
	    $criteria = new CDbCriteria();
		$criteria->order = "hinnaston_otsikko";
		?>
		<?php echo $form->labelEx($model,'hinnasto_id'); ?> <b class="fa fa-info-circle text-danger" data-toggle="tooltip" title="Huomio! Jos valitset hinnaston, silloin hinnasto ajaa yli tuotteet ja palvelut."></b>
		<?php echo $form->dropDownList($model, 'hinnasto_id', CHtml::listData(Hinnastot::model()->findAll($criteria), 'id', 'hinnaston_otsikko'), 
		array('empty'=>'Valitse hinnasto', 'class'=>'form-control')); ?> 
		<?php echo $form->error($model,'hinnasto_id'); ?>
	</div>

	<?php
	    $criteria = new CDbCriteria();
		$criteria->order 		= "nimike";
		$criteria->condition 	= "aktiivinen=1 AND yksikko='h'";
		$h_all 					= TuotteetPalvelut::model()->findAll($criteria);

	    $criteria = new CDbCriteria();
		$criteria->order 		= "nimike";
		$criteria->condition 	= "aktiivinen=1 AND yksikko='kk'";
		$kk_all 				= TuotteetPalvelut::model()->findAll($criteria);

	    $criteria = new CDbCriteria();
		$criteria->order 		= "nimike";
		$criteria->condition 	= "aktiivinen=1 AND yksikko='kpl'";
		$kpl_all 				= TuotteetPalvelut::model()->findAll($criteria);

 		if(count($h_all) == 0)
 		{
 			echo '
 			<div class="well">
	 			Luo ainakin yksi "h" tuote '.CHtml::link('tästä', ['/tuotteetPalvelut/create']).'. <br><br>
	 			Suosittelemme myös luomaan <b>"kk"</b> ja <b>"kpl"</b> tuotteet. <br>
	 			Tuote on pakollinen kenttä kohteen kortilla.<br>
	 		</div>
 			';
 		}
	?>

	<div class="section fill mb5" id="h_valinta" style="display:none">
		<?php echo $form->labelEx($model,'tuote_h'); ?>
		<?php echo $form->dropDownList($model, 'tuote_h', CHtml::listData($h_all, 'id', 'nimike'), 
		array('empty'=>'Valitse tuote', 'class'=>'form-control')); ?> 
		<?php echo $form->error($model,'tuote_h'); ?>
	</div>

	<div class="section fill mb5" id="kk_valinta" style="display:none">
		<?php echo $form->labelEx($model,'tuote_kk'); ?>
		<?php echo $form->dropDownList($model, 'tuote_kk', CHtml::listData($kk_all, 'id', 'nimike'), 
		array('empty'=>'Valitse tuote', 'class'=>'form-control')); ?> 
		<?php echo $form->error($model,'tuote_kk'); ?>
	</div>

	<div class="section fill mb5" id="kpl_valinta" style="display:none">
		<?php echo $form->labelEx($model,'tuote_kpl'); ?>
		<?php echo $form->dropDownList($model, 'tuote_kpl', CHtml::listData($kpl_all, 'id', 'nimike'), 
		array('empty'=>'Valitse tuote', 'class'=>'form-control')); ?> 
		<?php echo $form->error($model,'tuote_kpl'); ?>
	</div>
	
	<script type="text/javascript">
	$(document).ready(function(){

		laskurivityyppi();
		$(document).delegate("#Kohteet_laskurivi_tyyppi","change",function(){
			laskurivityyppi();
			if( $(this, 'option:selected').val() == 'kk' )
				alert('Tämä valinta luo vain yksi rivi laskutuksen luomisessa.\n\nTyövuorojen lisäpalvelut näytetään vain "Laskurivien tyyppi - tunti" tilassa kun TYÖ mobiilissa on tehty työvuoro listan mukaisesti.');
		});

		laskurivityyppi();

	 	function laskurivityyppi()
	 	{
			if( $('#Kohteet_laskurivi_tyyppi option:selected').val() == 'kk' )
			{
				$('#kk_valinta').show(375);
				$('#Kohteet_tuote_kk').attr('required', 'yes');
				$('#h_valinta').hide(375);
				$('#Kohteet_tuote_h').val(0).removeAttr('required');
				$('#kpl_valinta').hide(375);
				$('#Kohteet_tuote_kpl').val(0).removeAttr('required');
			} else if ( $('#Kohteet_laskurivi_tyyppi option:selected').val() == 'tunti' ){
				$('#h_valinta').show(375);
				$('#Kohteet_tuote_h').attr('required', 'yes');
				$('#kk_valinta').hide(375);
				$('#Kohteet_tuote_kk').val('').removeAttr('required');
				$('#kpl_valinta').hide(375);
				$('#Kohteet_tuote_kpl').val(0).removeAttr('required');
			} else if ( $('#Kohteet_laskurivi_tyyppi option:selected').val() == 'kpl' ){
				$('#kpl_valinta').show(375);
				$('#Kohteet_tuote_kpl').attr('required', 'yes');
				$('#h_valinta').hide(375);
				$('#Kohteet_tuote_h').val(0).removeAttr('required');
				$('#kk_valinta').hide(375);
				$('#Kohteet_tuote_kk').val(0).removeAttr('required');
			}
	 	}
	 	
		var TuotteetBefore = $('#Kohteet_tuote_h').html();

		$('#Kohteet_hinnasto_id').on('change', function(){
			tuotteetbyhinnasto();
		});

		function tuotteetbyhinnasto()
		{
			var thisVal = $('#Kohteet_hinnasto_id option:selected').val();
			$('#Kohteet_tuote_h').html('');
			if(thisVal)
			{
				$.ajax({
					url: 'tuotteetbyhinnasto?id=' + thisVal,
					success: function(data){
						console.log(data);
						if(data !== '')
						{
							data = JSON.parse(data);
							$('#Kohteet_tuote_h').html(data);
						}
					}
				});
				
			} else {
				$('#Kohteet_tuote_h').html(TuotteetBefore);
			}
		}
	});
	</script>

  </div><div class="col-sm-3">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tag_id'); ?>
		<?php echo $form->textField($model,'tag_id',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tag_id'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kustannuspaikka_nro'); ?>
		<?php echo $form->textField($model,'kustannuspaikka_nro',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'kustannuspaikka_nro'); ?>
	</div>

	<?php if( $asetukset->netvisor_kaytto == 1 ): ?>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'netvisor_dimension_name'); ?>
		<?php 
		echo '<select class="form-control" name="Kohteet[netvisor_dimension_name]">';
	 	echo '<option>Valitse</option>';
		$l_controller = Yii::app()->createController('Lasku');
		foreach($l_controller[0]->netvisorLaskentaKohteetLista() as $k => $v){
		 foreach($v->DimensionName as $k1 => $v1){
		 	echo '<optgroup label="'.$v1->Name.'">';
			foreach($v1->DimensionDetails->DimensionDetail as $k2 => $v2){
			 	echo '<option value="'.$v1->Name.'//'.$v2->Name.'" '.(( isset($model->netvisor_dimension_name) and !empty($model->netvisor_dimension_name) and isset($model->netvisor_dimension_item) and !empty($model->netvisor_dimension_item) and $model->netvisor_dimension_name.'//'.$model->netvisor_dimension_item == $v1->Name.'//'.$v2->Name )? 'selected':'').'>'.$v2->Name.'</option>';
			}
		 }
		}
		echo '</select>';
		?>
	</div>
	<?php endif; ?>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'siivous'); ?>

	   <div class="input-group">
		<?php
		$list = array();
      		$l = Valikkoot::model()->findAll(" select_type='siivous' ",array('order' => "select_type"));
		foreach($l as $v)
		$list[$v->id."//".$v->value] = $v->value;

        	echo $form->dropDownList($model, 'siivous', $list,
		array('empty'=>'','class'=>'form-control form-group'));
        	?>
		<span class="input-group-btn">
			<span class="btn btn-primary myBgColors muokaValiko" for="siivous"><i class="fa fa-pencil-square-o"></i></span>
		</span>
	   </div>

		<?php echo $form->error($model,'siivous'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'aktiivinen'); ?>
		<?php
		if(!isset($model->id)) $model->aktiivinen = 1;
		if(empty($model->aktiivinen)) $model->aktiivinen = 0;

		$list = array(1=>'Kyllä',0=>'Ei');
        	echo $form->dropDownList($model, 'aktiivinen', $list,
		array('class'=>'form-control'));
        	?>
		<?php echo $form->error($model,'aktiivinen'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyoryhma'); ?>
		<?php
		$checkOikeus = "tyoryhmat_4_".Yii::app()->user->adminStatus;
	    $criteria = new CDbCriteria();
		$criteria->order = " value ";
		$criteria->condition = "select_type='tyoryhma'";
		if( $site[0]->checkOikeusFields($checkOikeus) == 0 ){
		$criteria->addCondition ("value2 LIKE '%\"".Yii::app()->user->adminID."\"%'");
		}

		$listData = Valikkoot::model()->findAll($criteria);
		?>
		<?php echo $form->dropDownList($model, 'tyoryhma', CHtml::listData($listData, 'id', 'value'), 
		array('empty'=>'Valitse', 'class'=>'form-control')); 
		?>
		<?php echo $form->error($model,'tyoryhma'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'ryhma'); ?>
	   <div class="input-group">

		<?php

		$list = array();
      		$l = Valikkoot::model()->findAll(" select_type='asiakas_ryhma' ",array('order' => "select_type"));
		foreach($l as $v)
		$list[$v->id] = $v->value;

		if(count($list) > 0)
		{
        	echo $form->dropDownList($model, 'ryhma', $list,
		array('empty'=>Yii::t('main', 'Valitse'),'class'=>'form-control'));
		} else {
		echo 'Luo Valikko tietokannassa "Select Type = asiakas_ryhma"';
		}		
        	?>
		<span class="input-group-btn">
			<span class="btn btn-primary myBgColors muokaValiko" for="asiakas_ryhma"><i class="fa fa-pencil-square-o"></i></span>
		</span>
	   </div>

		<?php echo $form->error($model,'ryhma'); ?>
	</div>


	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tarvittavien_tyontekijoiden_maara'); ?>
		<?php echo $form->numberField($model,'tarvittavien_tyontekijoiden_maara',array('maxlength'=>3,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tarvittavien_tyontekijoiden_maara'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'arvioitu_kesto'); ?>
		<?php echo $form->textField($model,'arvioitu_kesto',array('maxlength'=>5,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'arvioitu_kesto'); ?>
	</div>

	<div class="section fill mb5">
	  <div class="row">
	   <div class="col-sm-6">
		<?php echo $form->labelEx($model,'arvioitu_kello_alku'); ?>
		<?php echo $form->textField($model,'arvioitu_kello_alku',array('maxlength'=>5,'class'=>'form-control automask')); ?>
		<?php echo $form->error($model,'arvioitu_kello_alku'); ?>
	   </div>
	   <div class="col-sm-6">
		<?php echo $form->labelEx($model,'arvioitu_kello_loppu'); ?>
		<?php echo $form->textField($model,'arvioitu_kello_loppu',array('maxlength'=>5,'class'=>'form-control automask')); ?>
		<?php echo $form->error($model,'arvioitu_kello_loppu'); ?>
	   </div>
	  </div>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kohteen_neliot'); ?>
		<?php echo $form->numberField($model,'kohteen_neliot',array('maxlength'=>5,'class'=>'form-control', 'step' => "any")); ?>
		<?php echo $form->error($model,'kohteen_neliot'); ?>
	</div>

	<div class="section fill mb5">
		<?= $form->labelEx($model, "window_count"); ?>
		<?= $form->numberField($model, "window_count", ["class" => "form-control", "step" => "any"]) ?>
		<?= $form->error($model, "window_count"); ?>
	</div>

	<div class="section fill mb5">
		<?= $form->labelEx($model, "window_est_time"); ?>
		<?= $form->textField($model, "window_est_time", ["class" => "form-control automask"]) ?>
		<?= $form->error($model, "window_est_time"); ?>
	</div>

	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.mask.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
	  $('#Kohteet_arvioitu_kesto, #Kohteet_arvioitu_kello_alku, #Kohteet_arvioitu_kello_loppu, #Kohteet_window_est_time').mask('00:00',{
	        placeholder: "__:__"
	  });
	  $('.automask').blur(function(){
		var thisval = $(this).val().split(':');
		if(!thisval[1] & $(this).val() !== '')
		{
			var h = $(this).val() ^ 0 ;
			var m = 0 ^ 0 ;
			$(this).val((h<10?"0"+h:h)+":"+(m<10?"0"+m:m));
		}
	  });
	});
	</script>

	<div class="section fill mb5 bg-info p5">
	<?php 
	if(isset($model->id) and isset($model->avaimet) and count($model->avaimet) > 0){
		echo '<table class="table">';
		echo '<tr><th>Avainnumero</th><th>Sijainti</th></tr>';
		foreach($model->avaimet as $avain){
			echo '<tr><td>'.$avain->avainnumero.'</td><td>'.$avain->sijainti.'</td></tr>';
		}
		echo '</table>';
		echo CHtml::link('Avaimet sivulle', array('/avaimet/index', 'osoite' => $model->osoite), array('class'=>'btn btn-default btn-block')); 
	}
	if(isset($model->id))
		echo CHtml::link('Luo avain', array('/avaimet/create', 'asiakas_id' => $model->asiakas_id, 'kohde_id' => $model->id), array('class'=>'btn btn-default btn-block')); 
	?>
	</div>

  <?php
  if (!empty(Yii::app()->user->kp) && isset($model->id)) {
    echo '<br>';
    $this->renderPartial('omasiistijat', ['kohde_id' => $model->id]);
  }
  ?>

  </div><div class="col-sm-6">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'aikataulu'); ?>
		<?php echo $form->textArea($model,'aikataulu',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'aikataulu'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'hinnoittelu'); ?>
		<?php echo $form->textArea($model,'hinnoittelu',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<p>(tämä tieto kuvaillaan laskutusnäkymässä)</p>
		<?php echo $form->error($model,'hinnoittelu'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'muut'); ?>
		<?php echo $form->textArea($model,'muut',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'muut'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'toimenpiteet'); ?>
		<?php echo $form->textArea($model,'toimenpiteet',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'toimenpiteet'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyonkuvaus_tiedostot_mobiilissa'); ?>
		<?php
		if(!isset($model->id)) $model->aktiivinen = 1;
		if(empty($model->aktiivinen)) $model->aktiivinen = 0;

		$list = array(0=>'Ei', 1=>'Kyllä');
        	echo $form->dropDownList($model, 'tyonkuvaus_tiedostot_mobiilissa', $list,
		array('class'=>'form-control'));
        	?>
		<?php echo $form->error($model,'tyonkuvaus_tiedostot_mobiilissa'); ?>
	</div>

	<div class="section fill mb5">
        <?php
	$i = 0;
	foreach(array_reverse(glob('tiedostot/kohteet/'.Yii::app()->user->domain.'/tyonkuvaukset/'.$model->id.'_*.*')) as $file) {
	$i++;
	$ext = pathinfo(basename($file), PATHINFO_EXTENSION);
 	echo '
	<div class="row">
 	  <div class="col-sm-2">
	  <label>'. Yii::t('main', 'Työnkuvaus').': </label>
	  </div>
	  <div class="col-sm-8" id="t_'.$model->id.$i.'">
	  <div class="btn btn-xs btn-danger poistaTyonkuvaus" this="'.$file.'" model="'.$model->id.'" for="t_'.$model->id.$i.'">X </div> ';
				// <-- file_safe_opener
				$filepath = Yii::getPathOfAlias('application').'/../'.$file;
				echo CHtml::link(basename($file),
					array('/site/file_safe_opener', 'filepath' => $filepath, 'ext' => $ext),
					array(
						'target'=>'_blank',
						'class'=>'link'
				));
				//     file_safe_opener// -->
	echo '
	 </div>
	</div>
	<br>
	';
	$kuvat[$i] = $file;
	}
        ?>
	</div>


	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tietoja'); ?>
		<?php echo $form->textArea($model,'tietoja',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tietoja'); ?>
	</div>

	<div class="section">
		<?php echo $form->labelEx($model,'tyo_erittelyt'); ?>:&nbsp;<span class="btn btn-success fa fa-plus uusierittely"></span>
		<p>
		<div id="erittelynlista">
		 <?php if(is_array(json_decode($model->tyo_erittelyt, true))): ?>
		 <?php foreach(json_decode($model->tyo_erittelyt, true) as $k => $v): ?>
		 <div class="row">
		  <div class="col-sm-8 col-sm-offset-3">
		   <input type="text" name="Kohteet[tyo_erittelyt][]" class="form-control" value="<?=$v?>">
		  </div>
		  <div class="col-sm-1 text-right">
		   <span class="btn btn-danger fa fa-trash poislistasta"></span>
		  </div>
 		 </div>
		 <?php endforeach; ?>
		 <?php endif; ?>
 		</div>
		</p>
		<?php echo $form->error($model,'tyo_erittelyt'); ?>
	</div>
	<script type="text/javascript">
	$(document).ready(function(){
	 $(".uusierittely").click(function(){
	  $("#erittelynlista").append('' +
			 '<div class="row">' +
			  '<div class="col-sm-8 col-sm-offset-3">' +
			   '<input type="text" name="Kohteet[tyo_erittelyt][]" class="form-control">' +
			  '</div>' +
			  '<div class="col-sm-1 text-right">' +
			   '<span class="btn btn-danger fa fa-trash poislistasta"></span>' +
			  '</div>' +
	 		 '</div>'
	  );
	 });
	 $(document).delegate(".poislistasta","click",function(){
	  $(this).closest(".row").remove();
	 });
	});
	</script>
	<div class="section">
		<?php echo $form->labelEx($model,'url_linkkit'); ?>:&nbsp;&nbsp;&nbsp;&nbsp;<span class="btn btn-success fa fa-plus uusilinkki"></span>
		<p>
		<div id="linkkilista">
		<?php
		$urls = $this->getKohdeUrls($model->url_linkkit);
		if( count($urls) > 0 )
		{
	 		foreach($urls as $k => $v){
			echo '
			 <div class="row">
			  <div class="col-sm-4">
			   <input type="text" name="Kohteet[url_linkkit][nimike][]" class="form-control" value="'.$k.'" placeholder="URL nimike">
			  </div>
			  <div class="col-sm-4">
			   <input type="text" name="Kohteet[url_linkkit][url][]" class="form-control" value="'.$v.'" placeholder="http osoite">
			  </div>
			  <div class="col-sm-1 text-right">
			   <span class="btn btn-danger fa fa-trash poislistasta"></span>
			  </div>
	 		 </div>';
			}
		}
		?>
 		</div>
		</p>
		<?php echo $form->error($model,'url_linkkit'); ?>
	</div>
	<script type="text/javascript">
	$(document).ready(function(){
	 $(".uusilinkki").click(function(){
	  $("#linkkilista").append('' +
			 '<div class="row">' +
			  '<div class="col-sm-4">' +
			   '<input type="text" name="Kohteet[url_linkkit][nimike][]" class="form-control" placeholder="URL nimike">' +
			  '</div>' +
			  '<div class="col-sm-4">' +
			   '<input type="text" name="Kohteet[url_linkkit][url][]" class="form-control" placeholder="http osoite">' +
			  '</div>' +
			  '<div class="col-sm-1 text-right">' +
			   '<span class="btn btn-danger fa fa-trash poislistasta"></span>' +
			  '</div>' +
	 		 '</div>'
	  );
	 });
	 $(document).delegate(".poislistasta","click",function(){
	  $(this).closest(".row").remove();
	 });
	});
	</script>

  </div>
</div><!-- form -->

<br>

	<div class="section">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary myBgColors luoTallennaKohde')); ?>
	</div>

<?php $this->endWidget(); ?>

<br>


<div class="row">
  <div class="col-sm-6 pull-right">
  <form id="uploadimage" action="#" class="form-input" method="post" enctype="multipart/form-data">
     <div class="section input-group">
       <label class="field prepend-icon append-button file">
         <span class="button"><?php echo Yii::t('main', 'Työnkuvaus'); ?></span>
         <input type="file" class="gui-file" name="file" id="t_file" onChange="document.getElementById('tiedostoUP').value = this.value;">
         <input type="text" class="gui-input" name="uploaded_tyonkuvaus" id="tiedostoUP" placeholder="Valitse tiedosto..">
         <label class="field-icon">
          <i class="fa fa-upload"></i>
         </label>
       </label>
	<span class="input-group-btn">
          <input type="submit" value="Lataa" class="btn btn-primary myBgColors" />
	</span>
    </div>
  </form>
 </div>
</div>



<hr>

<?php


   echo '<div class="section fill mb5">';

	$criteria = new CDbCriteria();
	$criteria->condition = " kohde_id='".$model->id."'  ";
	$kuvk = KuviaKohteesta::model()->findAll($criteria);

	$i = 0;
	Yii::app()->user->domain = strtolower(Yii::app()->user->domain);
	foreach($kuvk as $data) {
	$i++;

	 //if(file_exists(Yii::app()->request->baseUrl.'img/uploadedfromphone/'.Yii::app()->user->domain.'/'.$data->tiedosto))
	 //{
 	 echo '
	 <div class="col-sm-3">
	  <div class="link poistaKuva" this="'.Yii::app()->request->baseUrl.'img/uploadedfromphone/'.Yii::app()->user->domain.'/'.$data->tiedosto.'" kuva_id="'.$data->id.'">'.Yii::t('main','poista').'</div>
	  <label>'.$data->tekijan_nimi.'<br><b>'.date("d.m.Y H:i", strtotime($data->time)).'</b></label><br>';

		// <-- file_safe_opener
		$filepath = dirname(Yii::app()->getBasePath()).'/img/uploadedfromphone/'.Yii::app()->user->domain.'/'.$data->tiedosto;

		if( file_exists($filepath) ){
		$imageData = base64_encode(file_get_contents($filepath));
		$src = 'data: '.mime_content_type($filepath).';base64,'.$imageData;

		echo CHtml::link('<img src="'.$src.'" class="img-responsive thumbnail" style="height:200px">',
			array('/site/file_safe_opener', 'filepath' => $filepath, 'ext' => 'pdf'),
			array('target'=>'_blank','class'=>'text-danger'
		));
		}
		//     file_safe_opener -->

	   if(!empty($data->kuvaus))
	   {
	    echo '
	      <label>'.Yii::t('main','Kuvaus').'</label><br>
	      '.$data->kuvaus;
	   }

	 echo '</div>';
	 //}
	}
   echo '</div>';
?>

</div></div>

<script type="text/javascript">
$(document).ready(function(){


// Send form by ajax
$('#Kohteet_asiakas_id').change(function(){

   var thisVal = $(this).val();

   $.ajax({
      url: 'autotaytaminen?id='+thisVal,
      //type: "POST",
      //data: { index_ajax : "true" },
      success: function(data){
	  console.log(data);
	  var sp = data.split("//");
	  $("#Kohteet_etu_suku_nimet").val(sp[0]);
	  //$("#Kohteet_kaupunki").val(sp[2]);
	  //$("#Kohteet_pnumero").val(sp[3]);
	  $("#Kohteet_email").val(sp[3]);
	  $("#Kohteet_puh_nro").val(sp[4]);

	  if(sp[6] != 0)
	  {
	  var ryhma = sp[5].split("-");
	  $("#Kohteet_ryhma option:selected").val(ryhma[0]);
	  $("#Kohteet_ryhma option:selected").text(ryhma[1]);
	  }
      }
   });

});



$(".poistaKuva").click(function(){
	var forThis = $(this).attr("this");
	var kuva_id = $(this).attr("kuva_id");
        $.ajax({
           url: "update?id=<?php echo $model->id; ?>",
	   type:'POST',
	   data: { "poistaTamaKuva" : forThis, kuva_id : kuva_id },
           success: function(data){
		//console.log(data)
	    	window.location.reload();
           }
        });
});



});
</script>


<?php
    $lat = '';
    $lng = '';

    if(!empty($model->gps_sijainti))
    {
	$ex = explode(",", $model->gps_sijainti);
	if(isset($ex[1]))
	{
	        $lat = $ex[0];
	        $lng = $ex[1];
	}

    }

?>


<?php if(isset($model->id)) : ?>
    <input type="hidden" id="lat" value="<?php echo $lat; ?>">
    <input type="hidden" id="lng" value="<?php echo $lng; ?>">

<!DOCTYPE html>
<html>
  <head>
    <style>
      #map-canvas {
        width: 100%;
        height: 400px;
      }
    </style>
    <?php
	$asetuksetForAll = AsetuksetForAll::model()->findByPk(1);
    ?>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $asetuksetForAll->googlemaps_apikey; ?>"></script>
    <script>

window.initialize = function() {
    var lat = parseFloat(document.getElementById('lat').value);
    var lng = parseFloat(document.getElementById('lng').value);
    if(!lat){
	return false;
    }
    if(!lng){
	return false;
    }
    var myLatlng = new google.maps.LatLng(lat, lng);
    var mapCanvas = document.getElementById('map-canvas');
    var mapOptions = {
        center: myLatlng,          


        zoom: 14,
    }
    var map = new google.maps.Map(mapCanvas, mapOptions);
    var marker = new google.maps.Marker({
      position: myLatlng,
      map: map,
      title:"123"
      });
    var latLng = marker.getPosition(); 
    map.setCenter(latLng);

  }

  google.maps.event.addDomListener(window, 'load', initialize);

    </script>
  </head>
  <body>
    <div id="map-canvas"></div>
  </body>
</html>
<?php endif; ?>

	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/select_valiko.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>


<script type="text/javascript">
$(document).ready(function(){

});
</script>



