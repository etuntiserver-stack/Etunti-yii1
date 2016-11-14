<?php
/* @var $this OnlinevarausTuotteetController */
/* @var $model OnlinevarausTuotteet */
/* @var $form CActiveForm */
?>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'onlinevaraus-tuotteet-form',
	'enableAjaxValidation'=>true,
)); ?>



	<?php echo $form->errorSummary($model); ?>

<div class="row">
 <div class="col-sm-3">
	<legend><?php echo Yii::t('main', 'Perus tiedot'); ?></legend>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'nimike'); ?>
		<?php echo $form->textField($model,'nimike',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'nimike'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'selitysteksti'); ?>
		<?php echo $form->textArea($model,'selitysteksti',array('rows'=>6, 'cols'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'selitysteksti'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kotitalousvahennys'); ?>
		<?php echo $form->numberField($model,'kotitalousvahennys',array('size'=>20,'maxlength'=>20, 'class'=>'form-control', 'placeholder'=>'Esimerkiksi 45')); ?>
		<?php echo $form->error($model,'kotitalousvahennys'); ?>
	</div>

<?php /*

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'palvelu'); ?>
		<?php
		$list = array(0=>'Pääpalvelu',1=>'Lisäpalvelu');
        	echo $form->dropDownList($model, 'palvelu', $list,
		array('class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'palvelu'); ?>
	</div>

	<div class="section fill mb5 paaPalveluValiko" style="display:none">
		<?php echo $form->labelEx($model,'paa_palvelu'); ?>
		<?php
		
		$criteria=new CDbCriteria;
		$criteria->order=" nimike ";
		$criteria->group=" nimike ";
		$criteria->condition=" palvelu=0 ";
		$ot = CHtml::listData(OnlinevarausTuotteet::model()->findAll($criteria), 'id', 'nimike');

        	echo $form->dropDownList($model, 'paa_palvelu', $ot,
		array('empty'=>Yii::t('main', 'Valitse'), 'class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'palvelu'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'hinta'); ?>
		<?php echo $form->numberField($model,'hinta',array('size'=>20,'maxlength'=>20, 'class'=>'form-control', 'step'=>'0.01')); ?>
		<?php echo $form->error($model,'hinta'); ?>
	</div>

	<div class="section fill mb5 nelio">
		<?php echo $form->labelEx($model,'nelio'); ?>
		<?php
		$list = array(
			'0-50'=>'0-50',
			'50-80'=>'50-80',
			'80-120'=>'80-120',
			'120-160'=>'120-160',
			'160-200'=>'160-200',
			'200-250'=>'200-250',
		);
        	echo $form->dropDownList($model, 'nelio', $list,
		array('empty'=>'Valitse neliö', 'class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'nelio'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kesto'); ?>
		<?php echo $form->numberField($model,'kesto',array('size'=>20,'maxlength'=>20, 'class'=>'form-control', 'placeholder'=>'Esimerkiksi.. 0.5', 'step'=>'0.5')); ?>
		<?php echo $form->error($model,'kesto'); ?>
	</div>
*/ ?>



	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'nayta_sivuilla'); ?>
		<?php
		$list = array(
			1=>'Kyllä',
			0=>'Ei',
		);
        	echo $form->dropDownList($model, 'nayta_sivuilla', $list,
		array('class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'nayta_sivuilla'); ?>
	</div>

 </div><div class="col-sm-4">
	<legend><?php echo Yii::t('main', 'Toinen alasvetovalikko rakenne'); ?></legend>
	<div class="section fill mb5">
		<?php
			$rakenne = json_decode($model->toinen_valikko_rakenne, true);
		?>

		<label><?php echo Yii::t('main', 'Alasvetovalikon nimike'); ?></label>
		<input type="text" class="form-control" name="toinen_valiko[otsikko]" placeholder="<?php echo Yii::t('main', 'Esim.: Huoneisten koko m²'); ?>" value="<?php if( is_array($rakenne) and isset($rakenne['otsikko'])) echo $rakenne['otsikko']; ?>">

		<br>
		<div id="toinenRakenne">
		<?php if( !is_array($rakenne) ): ?>
		 <div class="row" id="rivi_1">
		  <div class="col-sm-4">
			<label><?php echo Yii::t('main', 'Nimike'); ?></label>
			<input type="text" class="form-control" name="toinen_valiko[values][nimike][]">
		  </div>
		  <div class="col-sm-4">
			<label><?php echo Yii::t('main', 'Hinta'); ?></label>
			<input type="number" class="form-control" name="toinen_valiko[values][hinta][]">
		  </div>
		  <div class="col-sm-4">
			<label><?php echo Yii::t('main', 'Kesto'); ?></label>
			<input type="number" class="form-control" name="toinen_valiko[values][kesto][]">
		  </div>
		 </div>
		<input type="hidden" id="lastRivi" value=1>
		<?php else: ?>
		 <?php 


			   $nimike = array();
			   foreach($rakenne['values']['nimike'] as $key=>$item)
				$nimike[] = $item;

			   $hinta = array();
			   foreach($rakenne['values']['hinta'] as $key=>$item)
				$hinta[] = $item;

			   $kesto = array();
			   foreach($rakenne['values']['kesto'] as $key=>$item)
				$kesto[] = $item;


			   $i = 0;
			   foreach($nimike as $key=>$rivi)
			   {
			   $i++;
				echo '
				 <div class="row" id="rivi_'.$i.'">
				  <div class="col-sm-4">
					<label>'.Yii::t('main', 'Nimike').'</label>
					<input type="text" class="form-control" name="toinen_valiko[values][nimike][]" value="'.$nimike[$key].'">
				  </div>
				  <div class="col-sm-4">
					<label>'.Yii::t('main', 'Hinta').'</label>
					<input type="number" class="form-control" name="toinen_valiko[values][hinta][]" value="'.$hinta[$key].'">
				  </div>
				  <div class="col-sm-4">
					<label>'.Yii::t('main', 'Kesto').'</label>
					<input type="number" class="form-control" name="toinen_valiko[values][kesto][]" value="'.$kesto[$key].'">
				  </div>
				 </div>
				';
			   }

				echo '<input type="hidden" id="lastRivi" value='.$i.'>';
		 ?>
		<?php endif; ?>
		</div><!--toinenRakenne-->


		<br>
		<p><span class="btn btn-default btn-sm uusiRivi"><i class="fa fa-plus" aria-hidden="true"></i></span></p>

	</div>

 </div><div class="col-sm-5">
	<legend><?php echo Yii::t('main', 'Lisäpalvelut rakenne'); ?></legend>

	<div class="section fill mb5">
		<?php
			$lisapalvelut = json_decode($model->lisapalvelut, true);
		?>

		<div id="lisapalveluRakenne">
		<?php if(!is_array($lisapalvelut)): ?>
		 <div class="row" id="lisapalvelut_rivi_1">
		  <div class="col-sm-12">
			<label><?php echo Yii::t('main', 'Otsikko'); ?></label>
			<input type="text" class="form-control" name="lisapalvelut[values][otsikko][]">
		  </div>
		  <div class="col-sm-12">
			<label><?php echo Yii::t('main', 'Kuvaus'); ?></label>
			<textarea class="form-control" name="lisapalvelut[values][kuvaus][]"></textarea>
		  </div>
		  <div class="col-sm-4">
			<label><?php echo Yii::t('main', 'Hinta'); ?></label>
			<input type="number" class="form-control" name="lisapalvelut[values][hinta][]">
		  </div>
		  <div class="col-sm-4">
			<label><?php echo Yii::t('main', 'Kesto'); ?></label>
			<input type="number" class="form-control" name="lisapalvelut[values][kesto][]">
		  </div>
		 </div>
		<hr>
		<input type="hidden" id="lastLisapalveluRivi" value=1>
		<?php else: ?>
		 <?php 


			   $otsikko = array();
			   foreach($lisapalvelut['values']['otsikko'] as $key=>$item)
				$otsikko[] = $item;

			   $kuvaus = array();
			   foreach($lisapalvelut['values']['kuvaus'] as $key=>$item)
				$kuvaus[] = $item;

			   $hinta = array();
			   foreach($lisapalvelut['values']['hinta'] as $key=>$item)
				$hinta[] = $item;

			   $kesto = array();
			   foreach($lisapalvelut['values']['kesto'] as $key=>$item)
				$kesto[] = $item;


			   $i = 0;
			   foreach($otsikko as $key=>$rivi)
			   {
			   $i++;
				echo '
				 <div class="row" id="lisapalvelut_rivi_'.$i.'">
				  <div class="col-sm-12">
					<label>'.Yii::t('main', 'Otsikko').'</label>
					<input type="text" class="form-control" name="lisapalvelut[values][otsikko][]" value="'.$otsikko[$key].'">
				  </div>
				  <div class="col-sm-12">
					<label>'.Yii::t('main', 'Kuvaus').'</label>
					<textarea class="form-control" name="lisapalvelut[values][kuvaus][]">'.$kuvaus[$key].'</textarea>
				  </div>
				  <div class="col-sm-4">
					<label>'.Yii::t('main', 'Hinta').'</label>
					<input type="number" class="form-control" name="lisapalvelut[values][hinta][]" value="'.$hinta[$key].'">
				  </div>
				  <div class="col-sm-4">
					<label>'.Yii::t('main', 'Kesto').'</label>
					<input type="number" class="form-control" name="lisapalvelut[values][kesto][]" value="'.$kesto[$key].'">
				  </div>
				 </div>
				<hr>
				';
			   }

				echo '<input type="hidden" id="lastLisapalveluRivi" value='.$i.'>';

		 ?>
		<?php endif; ?>
		</div><!--toinenRakenne-->


		<br>
		<p><span class="btn btn-default btn-sm uusiLisapalvelutRivi"><i class="fa fa-plus" aria-hidden="true"></i></span></p>

	</div>

 </div>
</div><!-- form -->

	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna', array('class'=>'btn btn-primary myBgColors')); ?>
	</div>

<?php $this->endWidget(); ?>




<script type="text/javascript">
$(document).ready(function(){

/*
	checkPalvelu();
  $('#OnlinevarausTuotteet_palvelu').change(function(){
	checkPalvelu();
  });

  function checkPalvelu(){

	var thisVal = parseInt($($('#OnlinevarausTuotteet_palvelu option:selected')).val());

	if(thisVal == 1){
		$('.paaPalveluValiko').show(375);
	} else {
		$('.paaPalveluValiko').hide(375);
	}

  }
*/

  var rivi = parseInt($('#lastRivi').val());
  $('.uusiRivi').click(function(){
	
	rivi = rivi+1;

	$('#toinenRakenne').append(''+
		 '<div class="row" id="rivi_'+rivi+'">'+
		  '<div class="col-sm-4">'+
			'<label><?php echo Yii::t("main", "Nimike"); ?></label>'+
			'<input type="text" class="form-control" name="toinen_valiko[values][nimike][]">'+
		  '</div>'+
		  '<div class="col-sm-4">'+
			'<label><?php echo Yii::t("main", "Hinta"); ?></label>'+
			'<input type="number" class="form-control" name="toinen_valiko[values][hinta][]">'+
		  '</div>'+
		  '<div class="col-sm-4">'+
			'<label><?php echo Yii::t("main", "Kesto"); ?></label>'+
			'<input type="number" class="form-control" name="toinen_valiko[values][kesto][]">'+
		  '</div>'+
		 '</div>');
  });


  var Lisapalvelutrivi = parseInt($('#lastLisapalveluRivi').val());
  $('.uusiLisapalvelutRivi').click(function(){
	
	Lisapalvelutrivi = Lisapalvelutrivi+1;

	$('#lisapalveluRakenne').append(''+
		 '<div class="row" id="lisapalvelut_rivi_'+Lisapalvelutrivi+'">'+
		  '<div class="col-sm-12">'+
			'<label><?php echo Yii::t("main", "Otsikko"); ?></label>'+
			'<input type="text" class="form-control" name="lisapalvelut[values][otsikko][]">'+
		  '</div>'+
		  '<div class="col-sm-12">'+
			'<label><?php echo Yii::t("main", "Kuvaus"); ?></label>'+
			'<textarea class="form-control" name="lisapalvelut[values][kuvaus][]"></textarea>'+
		  '</div>'+
		  '<div class="col-sm-4">'+
			'<label><?php echo Yii::t("main", "Hinta"); ?></label>'+
			'<input type="number" class="form-control" name="lisapalvelut[values][hinta][]">'+
		  '</div>'+
		  '<div class="col-sm-4">'+
			'<label><?php echo Yii::t("main", "Kesto"); ?></label>'+
			'<input type="number" class="form-control" name="lisapalvelut[values][kesto][]">'+
		  '</div>'+
		 '</div><hr>');
  });



});
</script>
