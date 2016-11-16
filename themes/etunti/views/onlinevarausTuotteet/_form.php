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
 <div class="col-sm-4">
	<legend><?php echo Yii::t('main', 'Perustiedot'); ?></legend>
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

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'hinta'); ?>
		<?php echo $form->numberField($model,'hinta',array('size'=>20,'maxlength'=>20, 'class'=>'form-control', 'step'=>'any')); ?>
		<?php echo $form->error($model,'hinta'); ?>

	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'alv'); ?>
		<?php
		$list = array();
		for ($i = 1; $i <= 24; $i++) {
		    $list[$i] = $i;
		}

		if(isset($model->id) and $model->alv != 0)
			$alv = $model->alv;
		else
			$alv = 24;

        	echo $form->dropDownList($model,'alv',$list, 
		array('class'=>'form-control','options' => array($alv=>array('selected'=>true))));
	
        	?>
		<?php echo $form->error($model,'alv'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kesto'); ?>
		<?php echo $form->numberField($model,'kesto',array('maxlength'=>20, 'class'=>'form-control', 'placeholder'=>'Esimerkiksi.. 0.5', 'step'=>'any')); ?>
		<?php echo $form->error($model,'kesto'); ?>
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

 </div><div class="col-sm-8">
	<legend><?php echo Yii::t('main', 'Toinen alasvetovalikko rakenne'); ?></legend>
	<div class="section fill mb5">
		<?php
			$rakenne = json_decode($model->toinen_valikko_rakenne, true);
		?>

		<label><?php echo Yii::t('main', 'Alasvetovalikon nimike'); ?></label>
		<input type="text" class="form-control" name="toinen_valiko[otsikko]" placeholder="<?php echo Yii::t('main', 'Esim.: Huoneisten koko m²'); ?>" value="<?php if( is_array($rakenne) and isset($rakenne['otsikko'])) echo $rakenne['otsikko']; ?>">

		<br>
		<div id="toinenRakenne">
		<?php if( !is_array($rakenne) or !isset($rakenne['values']) ): ?>
		 <div class="row" id="rivi_1">
		  <div class="col-sm-3">
			<label><?php echo Yii::t('main', 'Nimike'); ?></label>
			<input type="text" class="form-control" name="toinen_valiko[values][nimike][]">
		  </div>
		  <div class="col-sm-2">
			<label><?php echo Yii::t('main', 'Hinta (ALV 0)'); ?></label>
			<input type="number" class="form-control hinta_veroton" for="rivi_1" name="toinen_valiko[values][hinta_veroton][]" step="any">
		  </div>
		  <div class="col-sm-3">
			<label><?php echo Yii::t('main', 'Hinta (ALV '.$alv.'%)'); ?></label>
			<input type="number" class="form-control hinta" for="rivi_1" name="toinen_valiko[values][hinta][]" step="any">
		  </div>
		  <div class="col-sm-3">
			<label><?php echo Yii::t('main', 'Kesto'); ?></label>
			<input type="number" class="form-control" name="toinen_valiko[values][kesto][]" step="any" placeholder="h">
		  </div>
		  <div class="col-sm-1">
			<label></label><br>
			<span class="btn btn-danger poistaRivi pull-right" for="rivi_1"><i class="fa fa-trash-o" aria-hidden="true"></i></span>
		  </div>
		 </div>
		<input type="hidden" id="lastRivi" value=1>
		<?php else: ?>
		 <?php 


			   $nimike = array();
			   foreach($rakenne['values']['nimike'] as $key=>$item)
				$nimike[] = $item;

			   $hinta_veroton = array();
			   foreach($rakenne['values']['hinta_veroton'] as $key=>$item)
				$hinta_veroton[] = $item;

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
				  <div class="col-sm-3">
					<label>'.Yii::t('main', 'Nimike').'</label>
					<input type="text" class="form-control" name="toinen_valiko[values][nimike][]" value="'.$nimike[$key].'">
				  </div>
				  <div class="col-sm-2">
					<label>'.Yii::t('main', 'Hinta (ALV 0)').'</label>
					<input type="number" class="form-control hinta_veroton" for="rivi_'.$i.'" name="toinen_valiko[values][hinta_veroton][]" value="'.$hinta_veroton[$key].'" step="any">
				  </div>
				  <div class="col-sm-3">
					<label>'.Yii::t('main', 'Hinta (ALV '.$alv.'%)').'</label>
					<input type="number" class="form-control hinta" for="rivi_'.$i.'" name="toinen_valiko[values][hinta][]" value="'.$hinta[$key].'" step="any">
				  </div>
				  <div class="col-sm-3">
					<label>'.Yii::t('main', 'Kesto').'</label>
					<input type="number" class="form-control" name="toinen_valiko[values][kesto][]" value="'.$kesto[$key].'" step="any" placeholder="h">
				  </div>
				  <div class="col-sm-1">
					<label></label><br>
					<span class="btn btn-danger poistaRivi pull-right" for="rivi_'.$i.'"><i class="fa fa-trash-o" aria-hidden="true"></i></span>
				  </div>
				 </div>
				';
			   }

				echo '<input type="hidden" id="lastRivi" value='.$i.'>';
		 ?>
		<?php endif; ?>
		</div><!--toinenRakenne-->


		<br>
		<p><span class="btn btn-success btn-sm uusiRivi"><i class="fa fa-plus" aria-hidden="true"></i></span></p>

	</div>

 </div>
</div>
<hr>

<div class="row">
	<div class="col-sm-6">
	<legend><?php echo Yii::t('main', 'Lisäpalvelut rakenne'); ?></legend>

	<div class="section fill mb5">
		<?php
			$lisapalvelut = json_decode($model->lisapalvelut, true);
		?>

		<div id="lisapalveluRakenne">
		<?php if(!is_array($lisapalvelut) or !isset($lisapalvelut['values'])): ?>
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
			<input type="number" class="form-control" name="lisapalvelut[values][hinta][]" step="any">
		  </div>
		  <div class="col-sm-4">
			<label><?php echo Yii::t('main', 'Kesto'); ?></label>
			<input type="number" class="form-control" name="lisapalvelut[values][kesto][]" step="any">
		  </div>
		  <div class="col-sm-4">
			<label></label><br>
			<span class="btn btn-danger poistaLisapalvelutRivi pull-right" for="lisapalvelut_rivi_1"><i class="fa fa-trash-o" aria-hidden="true"></i></span>
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
					<input type="number" class="form-control" name="lisapalvelut[values][hinta][]" value="'.$hinta[$key].'" step="any">
				  </div>
				  <div class="col-sm-4">
					<label>'.Yii::t('main', 'Kesto').'</label>
					<input type="number" class="form-control" name="lisapalvelut[values][kesto][]" value="'.$kesto[$key].'" step="any">
				  </div>
				  <div class="col-sm-4">
					<label></label><br>
					<span class="btn btn-danger poistaLisapalvelutRivi pull-right" for="lisapalvelut_rivi_'.$i.'"><i class="fa fa-trash-o" aria-hidden="true"></i></span>
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
		<p><span class="btn btn-success btn-sm uusiLisapalvelutRivi"><i class="fa fa-plus" aria-hidden="true"></i></span></p>

	</div>

 </div>
</div><!-- form -->

	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna', array('class'=>'btn btn-primary myBgColors')); ?>
	</div>

<?php $this->endWidget(); ?>




<script type="text/javascript">
$(document).ready(function(){


  var rivi = parseInt($('#lastRivi').val());
  $('.uusiRivi').click(function(){
	
	rivi = rivi+1;

	$('#toinenRakenne').append(''+
		 '<div class="row" id="rivi_'+rivi+'">'+
		  '<div class="col-sm-3">'+
			'<label><?php echo Yii::t("main", "Nimike"); ?></label>'+
			'<input type="text" class="form-control" name="toinen_valiko[values][nimike][]">'+
		  '</div>'+
		  '<div class="col-sm-2">'+
			'<label><?php echo Yii::t("main", "Hinta (ALV 0)"); ?></label>'+
			'<input type="number" class="form-control hinta_veroton" for="rivi_'+rivi+'" name="toinen_valiko[values][hinta][]" step="any">'+
		  '</div>'+
		  '<div class="col-sm-3">'+
			'<label><?php echo Yii::t("main", "Hinta (ALV '+$('#OnlinevarausTuotteet_alv').val()+'%)"); ?></label>'+
			'<input type="number" class="form-control hinta" for="rivi_'+rivi+'" name="toinen_valiko[values][hinta][]" step="any">'+
		  '</div>'+
		  '<div class="col-sm-3">'+
			'<label><?php echo Yii::t("main", "Kesto"); ?></label>'+
			'<input type="number" class="form-control" name="toinen_valiko[values][kesto][]" step="any">'+
		  '</div>'+
		  '<div class="col-sm-1">'+
			'<label></label><br>'+
			'<span class="btn btn-danger poistaRivi pull-right" for="rivi_'+rivi+'"><i class="fa fa-trash-o" aria-hidden="true"></i></span>'+
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
			'<input type="number" class="form-control" name="lisapalvelut[values][hinta][]" step="any">'+
		  '</div>'+
		  '<div class="col-sm-4">'+
			'<label><?php echo Yii::t("main", "Kesto"); ?></label>'+
			'<input type="number" class="form-control" name="lisapalvelut[values][kesto][]" step="any">'+
		  '</div>'+
		  '<div class="col-sm-4">'+
			'<label></label><br>'+
			'<span class="btn btn-danger poistaLisapalvelutRivi pull-right" for="lisapalvelut_rivi_'+Lisapalvelutrivi+'"><i class="fa fa-trash-o" aria-hidden="true"></i></span>'+
		  '</div>'+
		 '</div><hr>');
  });



   $(document).delegate(".poistaRivi","click",function(){
	var thisID = $(this).attr("for");
	var r = confirm('Haluatko varmaasti poista?');
	if(r)
	$('#'+thisID).remove();
   });

   $(document).delegate(".poistaLisapalvelutRivi","click",function(){
	var thisID = $(this).attr("for");
	var r = confirm('Haluatko varmaasti poista?');
	if(r)
	$('#'+thisID).remove();
   });

   $(document).delegate(".hinta_veroton","keyup",function(){
	var forID = $(this).attr('for');
	laskuriPlus(forID);
   });

   $(document).delegate(".hinta","keyup",function(){
	var forID = $(this).attr('for');
	laskuriMiinus(forID);
   });

   function laskuriPlus(forID){
	var alv = parseFloat( $('#OnlinevarausTuotteet_alv').val() );
	var hinta_veroton = parseFloat( $('#'+forID).find('.hinta_veroton').val() );
	var hinta = $('#'+forID).find('.hinta');
	if(hinta_veroton > 0){
		result = (hinta_veroton*alv)/100;
		result = result+hinta_veroton;
		hinta.val(result);
	}
   }

   function laskuriMiinus(forID){
	var alv = parseFloat( $('#OnlinevarausTuotteet_alv').val() );
	var hinta = parseFloat( $('#'+forID).find('.hinta').val() );
	var hinta_veroton = $('#'+forID).find('.hinta_veroton');
	if(hinta > 0){
		result = (hinta*alv)/100;
		//result = hinta-result;
		hinta_veroton.val(result);
	}
   }

});
</script>
