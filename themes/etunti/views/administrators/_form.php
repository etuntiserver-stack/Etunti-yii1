<?php
/* @var $this AdministratorsController */
/* @var $model Administrators */
/* @var $form CActiveForm */
?>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'administrators-form',
	'enableAjaxValidation'=>true,
)); ?>


	<?php echo $form->errorSummary($model); ?>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'adm_login'); ?>
		<?php echo $form->textField($model,'adm_login',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'adm_login'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'adm_email'); ?>
		<?php echo $form->textField($model,'adm_email',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'adm_email'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'adm_nimi'); ?>
		<?php echo $form->textField($model,'adm_nimi',array('size'=>60,'maxlength'=>100,'class'=>'form-control', 'AUTOCOMPLETE'=>'off')); ?>
		<?php echo $form->error($model,'adm_nimi'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'status'); ?>

		<?php 
		$as = Yii::app()->createController('Asetukset');
		$val = $as[0]->oikeudenOtsikot();
		$arr = [];
		foreach($val as $id => $value)
			$arr[$id] = $value['nimike'];

		// <-- Oikeudet
		   $checkOikeus = "ryhmat_2_".Yii::app()->user->adminStatus;
		   $site = Yii::app()->createController('Site');
		   $vastaus = $site[0]->checkOikeusFields($checkOikeus);
		//  Oikeudet -->

		if($vastaus == 0)
		echo '<br><span class="small">Sinulla ei ole oikeuksia</span>'; 
		else
		echo $form->dropDownList($model,'status', $arr, 
			array('class'=>'form-control')); 

		?>

		<?php echo $form->error($model,'status'); ?>
	</div>

	<?php if(isset($model->id)) : ?>
	<br>
	<p><span class="btn btn-primary myBgColors" data-toggle="collapse" data-target="#salasanaVaihdo"><?php echo Yii::t('main', 'Salasana'); ?> <i class="caret"></i></span></p>


	<div id="salasanaVaihdo" class="collapse">
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'adm_salasana'); ?>
		<?php echo $form->passwordField($model,'adm_salasana',array('value'=>'','size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'adm_salasana'); ?>
	</div>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'adm_salasana_repeat'); ?>
		<?php echo $form->passwordField($model,'adm_salasana_repeat',array('value'=>'','size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'adm_salasana_repeat'); ?>
	</div>
	</div>
<?php /*
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'adm_salasana'); ?>
		<?php echo $form->passwordField($model,'adm_salasana',array('size'=>60,'maxlength'=>100,'class'=>'form-control', 'AUTOCOMPLETE'=>'off')); ?>
		<?php echo $form->error($model,'adm_salasana'); ?>
	</div>
	<div id="adm_salasana_error"></div>
*/ ?>
	<?php endif; ?>

<br>

	<div class="section fill mb5">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>


<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>


<div id="showres" class="modal fade" tabindex="-1" role="dialog">

  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Neuvot oikeudesta</h4>
      </div>
      <div class="modal-body">

	<label>Malli:</label>  <br><br>
		<p>Ryhmän nimetys/arvo(ei saa muuttaa)</p>
		<p><b>1:</b> Saa poista.</p>
		<p><b>2:</b> Saa muokata. Ei saa poista.</p>
		<p><b>3:</b> Ei saa poista, muokata, luoda.</p>
		<p><b>4:</b> Ei käytettävissä.</p>
		<p><b>5:</b> Ei käytettävissä.</p>
		<p><b>6:</b> Ei käytettävissä.</p>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>
        <button type="button" class="btn btn-primary tallenna">Päivitä sivua</button>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->

</div>


<script type="text/javascript">
$(document).ready(function(){

/*
$("#Administrators_adm_salasana").blur(function() {
	validatePassword( $(this).val() );
});

function validatePassword(p) {

        errors = [];
    if (p.length < 8) {
        errors.push("Salasanan pitää olla vähintään 8 merkkiä pitkä."); 
    }
    if (p.search(/[a-z]/i) < 0) {
        errors.push("Salasanan pitää sisältää vähintään yksi kirjain.");
    }
    if (p.search(/[0-9]/) < 0) {
        errors.push("Salasanan pitää sisältää vähintään yksi numero."); 
    }
    if (p.search(/[a-z]/) < 0) { 
	errors.push("Your password must contain at least one lowercase letter.") 
    } 
    if (p.search(/[A-Z]/) < 0) { 
	errors.push("Your password must contain at least one uppercase letter.") 
    }
    if (errors.length > 0) {
        $('#adm_salasana_error').html('<div class="alert bg-danger">' + errors.join("<br>") + '</div>' ).show();
        return false;
    }
        $('#adm_salasana_error').html('').hide();
    return true;
}
*/

/* valikot */
$(".muokaValiko").click(function() {

	$('#showres').modal().html();

});
/* valikot */


});
</script>

