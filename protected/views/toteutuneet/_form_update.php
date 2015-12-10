<?php
/* @var $this ToteutuneetController */
/* @var $model Toteutuneet */
/* @var $form CActiveForm */

	  $model->loppui = date("d.m.Y H:i",strtotime($model->loppui));
	  $model->aloitan = date("d.m.Y H:i",strtotime($model->aloitan));

$kesto = '<h1>'.$this->sprint(strtotime($model->loppui)-strtotime($model->aloitan)).'</h1>';
$forPVM = date('d.m.Y',strtotime($model->aloitan));
//$model->aloitan = date("H:i",strtotime($model->aloitan));
//$model->loppui = date("H:i",strtotime($model->loppui));


?>

	<input type="hidden" id="forDatepickerAlkuPVM" value="<?php echo date('Y-m-d H:i',strtotime($model->aloitan)); ?>">
	<input type="hidden" id="forDatepickerLoppuPVM" value="<?php echo date('Y-m-d H:i',strtotime($model->loppui)); ?>">

	<div class="modal-dialog modal-lg">
	    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		<h2 class="modal-title"><?php echo Yii::t('main', 'Toteuman muutos'); ?></h2>
	
		</div>
		<div class="modal-body">

	<div class="dialogTable clearfix modal-osio">


<div class="row form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'toteutuneet-form-upd',
	'enableAjaxValidation'=>false,
)); ?>


		<?php echo $form->hiddenField($model,'id'); ?>
		<?php echo $form->hiddenField($model,'kid'); ?>
		<?php echo $form->hiddenField($model,'tid'); ?>

  <div class="col-sm-12">
	<div class="row">
		<?php echo $form->labelEx($model,'tietoja'); ?>
		<?php echo $form->textarea($model,'tietoja',array('rows'=>4,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'tietoja'); ?>
	</div>
  </div>

  <div class="col-sm-4">

	<div class="row">
		<?php echo $form->labelEx($model,'kohde_kannasta'); ?>
		<?php echo $form->dropDownList($model,'kohde_kannasta', 
			CHtml::listData(Kohteet::model()->findAll(array('order' => 'osoite ASC')), 'id', 'osoite'), 
			    array(
                		'class'=>'form-control input-sm',
		                'options' => array($model->kohdenID=>array('selected'=>true)),
			    )
			);
		?>
		<?php echo $form->error($model,'kohde_kannasta'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'osoite'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php 
		      $list = array(3=>Yii::t('main','TYÖ'),2=>Yii::t('main','MATKA'),10=>Yii::t('main','LOUNASTAUKO'));
		      //array_unshift($list, $list[$s->status]);
		      echo $form->dropDownList($model,'status', 
			 	$list, 
				array('options' => array($model->status=>array('selected'=>true)),'class'=>'form-control input-sm'));
		?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'aloitan'); ?>
		<?php echo $form->textField($model,'aloitan',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm al')); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'loppui'); ?>
		<?php echo $form->textField($model,'loppui',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm lp')); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sairaus'); ?>
		<?php 
        	$tal = array(1=>'Palkaton',2=>'Palkallinen',3=>'Lapsi sairaus');
		echo $form->dropDownList($model,'sairaus', $tal, 
		array('empty'=>'Valitse','class'=>'form-control input-sm')) ?>
		<?php echo $form->error($model,'sairaus'); ?>
	</div>

		<input type="hidden" name="forPVM" value="<?php echo $forPVM; ?>">


  </div><div class="col-sm-8">

	<div class="row">
	<label><?php echo Yii::t('main','Kohteen tiedot'); ?></label>
	<?php
	$m = Kohteet::model()->findbypk($model->kohdenID);
	$ohje = '';
	if(isset($m->id))
	{

		$k = explode("//",$m->kenella_on_avain);

		if(isset($k[1]))
		  $ohje .= Yii::t('main', 'Avain on: ')." ".$k[1]."\n";
		if(!empty($m->avain))
		  $ohje .= Yii::t('main', 'Avain: ')." ".$m->avain."\n\n";
		if(!empty($m->aikataulu))
		  $ohje .= "\nAikataulu: ".$m->aikataulu;
		if(!empty($m->toimenpiteet))
		  $ohje .= "\nToimenpiteet: ".$m->toimenpiteet;
		if(!empty($m->tietoja))
		  $ohje .= "\nTietoja: ".$m->tietoja;
		if(!empty($m->muut))
		  $ohje .= "\nMuut: ".$m->muut;

	}
	?>
	<textarea class="form-control input-sm" rows="12" ><?php echo $ohje; ?></textarea>
	</div>

	<div class="row">
		<div id="kesto"><?php echo $kesto; ?></div>
	</div>

  </div>
</div><!-- form -->

<?php $this->endWidget(); ?>



	<div class="modal-footer">
		<span class="btn btn-default" data-dismiss="modal">Sulje</span>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary updTot')); ?>
	</div>		
		</div> <!-- end modal-content -->
	</div> <!-- end modal-dialog -->


	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/toteuma.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.mask.js"></script>


<script type="text/javascript">
$(document).ready(function(){


  $('.al').mask('00.00.0000 00:00',{
        placeholder: "__.__.____ __:__"
  });

  $('.lp').mask('00.00.0000 00:00',{
        placeholder: "__.__.____ __:__"
  });


});
</script>
