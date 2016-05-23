<?php
/* @var $this AsetuksetController */
/* @var $model Asetukset */
/* @var $form CActiveForm */


     $tas = array();
   if(isset(Yii::app()->user->adminPaketti)) 
     $tas = explode(",",Yii::app()->user->adminPaketti);
?>

<div class="row">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'asetukset-form',
	'enableAjaxValidation'=>false,
)); ?>



	<?php echo $form->errorSummary($model); ?>


		<?php echo $form->hiddenField($model,'id'); ?>
		<?php echo $form->error($model,'id'); ?>

  <div class="col-sm-4">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'logon_polkku'); ?>
		<?php echo $form->textField($model,'logon_polkku',array('size'=>60,'maxlength'=>500,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'logon_polkku'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'logon_korkeus'); ?>
		<?php echo $form->textField($model,'logon_korkeus',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'logon_korkeus'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'johtaja'); ?>
		<?php echo $form->textField($model,'johtaja',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'johtaja'); ?>
	</div>

  </div><div class="col-sm-4">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'pyhapaivat'); ?>
		<?php echo $form->textarea($model,'pyhapaivat',array('rows'=>8,'maxlength'=>3000,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'pyhapaivat'); ?>
	</div>

  </div><div class="col-sm-4">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'erikoislauantai'); ?>
		<?php echo $form->textarea($model,'erikoislauantai',array('rows'=>8,'maxlength'=>3000,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'erikoislauantai'); ?>
	</div>

  </div>
</div><!-- form -->


<?php if(in_array('3',$tas)) : ?>
<hr>
  <div class="row form">
    <div class="col-sm-4">
    <legend><h2><?php echo Yii::t('main','Laskutuksen asetukset'); ?></h2></legend>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tilinumero'); ?>
		<?php echo $form->textField($model,'tilinumero',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tilinumero'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'iban'); ?>
		<?php echo $form->textField($model,'iban',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'iban'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'bic'); ?>
		<?php echo $form->textField($model,'bic',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'bic'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'viivastyskorko'); ?>
		<?php echo $form->textField($model,'viivastyskorko',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'viivastyskorko'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'palvelu_tyyppi'); ?>
		<?php 
        	$tal = array(1=>'POSTITA',2=>'TRUST',3=>'MANUAL');
		echo $form->dropDownList($model,'palvelu_tyyppi', $tal, 
		array('empty'=>'Valitse palvelu','class'=>'form-control','id'=>'osoite')) ?>
		<?php echo $form->error($model,'palvelu_tyyppi'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'lasku_asiakasnumero'); ?>
		<?php 
        	$tal = array(0=>'Automaatiseesti',1=>'Itse');
		echo $form->dropDownList($model,'lasku_asiakasnumero', $tal, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'lasku_asiakasnumero'); ?>
	</div>

    </div><div class="col-sm-4">
    <legend><h2><?php echo Yii::t('main','POSTITA.FI tunnukset'); ?></h2></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'postita_username'); ?>
		<?php echo $form->textField($model,'postita_username',array('size'=>20,'maxlength'=>100,'class'=>'form-control')); ?>

		<?php echo $form->error($model,'postita_username'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'postita_password'); ?>
		<?php echo $form->textField($model,'postita_password',array('size'=>20,'maxlength'=>100,'class'=>'form-control')); ?>

		<?php echo $form->error($model,'postita_password'); ?>
	</div>

    </div><div class="col-sm-4">
    <legend><h2><?php echo Yii::t('main','TRUST.FI tunnukset'); ?></h2></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'trust_url'); ?>
		<?php echo $form->textField($model,'trust_url',array('size'=>20,'maxlength'=>255,'class'=>'form-control')); ?>

		<?php echo $form->error($model,'trust_url'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'trust_cid'); ?>
		<?php echo $form->textField($model,'trust_cid',array('size'=>20,'maxlength'=>100,'class'=>'form-control')); ?>

		<?php echo $form->error($model,'trust_cid'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'trust_api'); ?>
		<?php echo $form->textField($model,'trust_api',array('size'=>20,'maxlength'=>100,'class'=>'form-control')); ?>

		<?php echo $form->error($model,'trust_api'); ?>
	</div>

    </div>
  </div>
<?php endif; ?>



<?php if(in_array('4',$tas)) : ?>
<hr>
  <div class="row form">
    <div class="col-sm-4">
    <legend><h2><?php echo Yii::t('main','Onlinevaraus'); ?></h2></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'checkout_id'); ?>
		<?php echo $form->textField($model,'checkout_id',array('size'=>20,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'checkout_id'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'checkout_salasana'); ?>
		<?php echo $form->textField($model,'checkout_salasana',array('size'=>20,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'checkout_salasana'); ?>
	</div>

	<br>
	<div class="section fill mb5">

		<a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/asetukset/rekisteriseloste"><?php echo Yii::t('main','Onlinevaraus tietosuoja- ja rekisteriseloste'); ?> </a>

	</div>


    </div><div class="col-sm-3">
    <legend><h2><?php echo Yii::t('main','Viikonloppulisät'); ?></h2></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'viikonloppulisa_la'); ?>
		<?php echo $form->numberField($model,'viikonloppulisa_la',array('size'=>20,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'viikonloppulisa_la'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'viikonloppulisa_su'); ?>
		<?php echo $form->numberField($model,'viikonloppulisa_su',array('size'=>20,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'viikonloppulisa_su'); ?>
	</div>

    </div><div class="col-sm-5">

    <legend><h2><?php echo Yii::t('main','Muut'); ?></h2></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tilausvahvistus'); ?>
		<?php echo $form->textarea($model,'tilausvahvistus',array('rows'=>4,'maxlength'=>5000,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tilausvahvistus'); ?>
	</div>

    </div>
  </div>
<?php endif; ?>

<hr>
  <div class="row form">
   <div class="col-sm-4">
    <legend><h2><?php echo Yii::t('main','SOVELLUS'); ?></h2></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'sovellus_tyovuorot'); ?>
		<?php 
        	$tal = array(
			1=>'Vain tämä viikko suunnuntai asti',
			2=>'Tästä päivä alkaen +7pv',
			3=>'Tästä päivä alkaen +14pv'
		);
		echo $form->dropDownList($model,'sovellus_tyovuorot', $tal, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'sovellus_tyovuorot'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'show_name'); ?>
		<?php 
        	$tal = array(
			0=>'Ei',
			1=>'Kyllä'
		);
		echo $form->dropDownList($model,'show_name', $tal, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'show_name'); ?>
	</div>

   </div>
  </div>


<br>
<br>
	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>


<?php
/*
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'syntyrin_emails'); ?>
		<?php echo $form->textArea($model,'syntyrin_emails',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'syntyrin_emails'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'paivan_uutinen'); ?>
		<?php echo $form->textField($model,'paivan_uutinen',array('size'=>60,'maxlength'=>500,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'paivan_uutinen'); ?>
	</div>
*/
?>
