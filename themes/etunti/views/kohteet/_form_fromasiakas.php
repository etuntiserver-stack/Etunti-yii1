<?php
/* @var $this KohteetController */
/* @var $model Kohteet */
/* @var $form CActiveForm */

     $tas = array();
   if(isset(Yii::app()->user->adminPaketti)) 
     $tas = explode(",",Yii::app()->user->adminPaketti);

if(isset($model->id))
$model->hinta = str_replace(",",".",$model->hinta);

if(!isset($model->id)){ $model->alv = 24; }
?>

<div class="row">


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'kohteet-form',
	'enableAjaxValidation'=>false,
)); ?>



	<?php echo $form->errorSummary($model); ?>

  <div class="col-sm-3">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'asiakas_id'); ?>
    		<?php 
       		$criteria = new CDbCriteria();
        	$criteria->condition = " id='$asiakas->id' ";

        	$a = Asiakkaat::model()->findAll($criteria);
		echo '<select name="Kohteet[asiakas_id]" class="form-control" id="Kohteet_asiakas_id">';

		foreach($a as $aa)
		{
		    echo '<option value="'.$aa->id.'">'.$aa->Fullname.'</option>';
		}
		echo '</select>';
		?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'etu_suku_nimet'); ?>
		<?php echo $form->textField($model,'etu_suku_nimet',array('value'=>$asiakas->Etusukunimi,'size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'etu_suku_nimet'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('value'=>$asiakas->osoite, 'size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'osoite'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'pnumero'); ?>
		<?php echo $form->textField($model,'pnumero',array('value'=>$asiakas->postinumero, 'size'=>7,'maxlength'=>7,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'pnumero'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kaupunki'); ?>
		<?php echo $form->textField($model,'kaupunki',array('value'=>$asiakas->kaupunki, 'size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'kaupunki'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('value'=>$asiakas->sahkoposti,'size'=>60,'maxlength'=>72,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'puh_nro'); ?>
		<?php echo $form->textField($model,'puh_nro',array('value'=>$asiakas->puhelin,'size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'puh_nro'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'toimipaikka'); ?>
		<?php echo $form->textField($model,'toimipaikka',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'toimipaikka'); ?>
	</div>


	<?php if(in_array('3',$tas)) : ?>
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
		<?php echo $form->labelEx($model,'hinnasto_id'); ?> <b class="fa fa-info-circle text-danger" data-toggle="tooltip" title="Huomio! Jos valitset hinnaston, silloin hinnasto ajaa yli tuotteet ja palvelut."></b>
		<?php echo $form->dropDownList($model, 'hinnasto_id', CHtml::listData(Hinnastot::model()->findAll(), 'id', 'hinnaston_otsikko'), 
		array('empty'=>'Valitse hinnasto', 'class'=>'form-control')); ?> 
		<?php echo $form->error($model,'hinnasto_id'); ?>
	</div>
	
	<div class="section fill mb5" id="h_valinta" style="display:none">
		<?php echo $form->labelEx($model,'tuote_h'); ?>
		<?php echo $form->dropDownList($model, 'tuote_h', CHtml::listData(TuotteetPalvelut::model()->findAll("yksikko='h'"), 'id', 'nimike'), 
		array('empty'=>'Valitse tuote', 'class'=>'form-control')); ?> 
		<?php echo $form->error($model,'tuote_h'); ?>
	</div>

	<div class="section fill mb5" id="kk_valinta" style="display:none">
		<?php echo $form->labelEx($model,'tuote_kk'); ?>
		<?php echo $form->dropDownList($model, 'tuote_kk', CHtml::listData(TuotteetPalvelut::model()->findAll("yksikko='kk'"), 'id', 'nimike'), 
		array('empty'=>'Valitse tuote', 'class'=>'form-control')); ?> 
		<?php echo $form->error($model,'tuote_kk'); ?>
	</div>

	<div class="section fill mb5" id="kpl_valinta" style="display:none">
		<?php echo $form->labelEx($model,'tuote_kpl'); ?>
		<?php echo $form->dropDownList($model, 'tuote_kpl', CHtml::listData(TuotteetPalvelut::model()->findAll("yksikko='kpl'"), 'id', 'nimike'), 
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
	<?php endif; ?>

  </div><div class="col-sm-3">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tag_id'); ?>
		<?php echo $form->textField($model,'tag_id',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tag_id'); ?>
	</div>

		<?php echo $form->hiddenField($model,'gps_sijainti',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'siivous'); ?>

		<?php
      		$l = Valikkoot::model()->findAll(" select_type='siivous' ",array('order' => "select_type"));
		foreach($l as $v)
		$list[$v->id."//".$v->value] = $v->value;

        	echo $form->dropDownList($model, 'siivous', $list,
		array('empty'=>'','class'=>'form-control'));
        	?>

		<?php echo $form->error($model,'siivous'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'aktiivinen'); ?>
		<?php
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
		$site = Yii::app()->createController('Site');
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

	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.mask.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
	  $('#Kohteet_arvioitu_kesto').mask('00:00',{
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

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kohteen_neliot'); ?>
		<?php echo $form->numberField($model,'kohteen_neliot',array('maxlength'=>5,'class'=>'form-control', 'step' => "any")); ?>
		<?php echo $form->error($model,'kohteen_neliot'); ?>
	</div>

	<?php 
	if(isset($model->id) and isset($model->avaimet) and count($model->avaimet) > 0){
	echo CHtml::link('Avaimet', array('/avaimet/index', 'osoite' => $model->osoite), array('class'=>'btn btn-default btn-block')); 
	}
	if(isset($model->id) and isset($model->avaimet) and count($model->avaimet) == 0){
	echo CHtml::link('Luo avain', array('/avaimet/create', 'asiakas_id' => $model->asiakas_id, 'kohde_id' => $model->id), array('class'=>'btn btn-default btn-block')); 
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
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary myBgColors')); ?>
	</div>

<?php $this->endWidget(); ?>


	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/select_valiko.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>


<script type="text/javascript">
$(document).ready(function(){

	var avainOn = $( "#Kohteet_avaimen_sijainti option:selected" ).val();
	if(avainOn !== '3')
	$('.kenella_on_avain').hide();


$("#Kohteet_avaimen_sijainti").change(function() {
    var thisVal = $(this).val();
	if(thisVal !== '3')
	{
		$('.kenella_on_avain').hide('slow');
		$('#Kohteet_kenella_on_avain').val('');
	} else {
		$('.kenella_on_avain').show('slow');
	}
});


});
</script>


