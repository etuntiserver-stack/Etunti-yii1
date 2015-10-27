<?php
/* @var $this ToteutuneetController */
/* @var $model Toteutuneet */
/* @var $form CActiveForm */

if(isset($_POST['forid'])){
  $s= Mobile::model()->findbypk($_POST['forid']);


  $model->kohde_kannasta = $s->kohde_kannasta;
}

$kesto = '<h1>'.$this->sprint(strtotime($s->loppui)-strtotime($s->aloitan)).'</h1>';
$forPVM = date('d.m.Y',strtotime($s->aloitan));
//$aloitan = date("H:i",strtotime($s->aloitan));
//$loppui = date("H:i",strtotime($s->loppui));
?>
	<input type="hidden" id="forDatepickerAlkuPVM" value="<?php echo date('Y-m-d H:i',strtotime($s->aloitan)); ?>">
	<input type="hidden" id="forDatepickerLoppuPVM" value="<?php echo date('Y-m-d H:i',strtotime($s->loppui)); ?>">


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
  <div class="col-sm-3">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'toteutuneet-form',
	'enableAjaxValidation'=>false,
)); ?>


		<input type="hidden" name="forPVM" value="<?php echo $forPVM; ?>">
		<?php echo $form->hiddenField($model,'kid',array('value'=>$s->id)); ?>
		<?php echo $form->hiddenField($model,'tid',array('value'=>$s->tid)); ?>
		<?php echo $form->hiddenField($model,'tekijan_nimi',array('value'=>$s->tekijan_nimi)); ?>


	<div class="row">
		<?php echo $form->labelEx($model,'kohde_kannasta'); ?>
		<?php echo $form->dropDownList($model,'kohde_kannasta', 
			CHtml::listData(Kohteet::model()->findAll(array('order' => 'osoite ASC')), 'id', 'osoite'), 
			    array(
                		'class'=>'form-control',
		                'options' => array($s->kohdenID=>array('selected'=>true)),
			    )
			);
		?>
		<?php echo $form->error($model,'kohde_kannasta'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php 
		      $list = array(3=>Yii::t('main','TYÖ'),2=>Yii::t('main','MATKA'),10=>Yii::t('main','LOUNASTAUKO'));
		      //array_unshift($list, $list[$s->status]);
		      echo $form->dropDownList($model,'status', 
			 	$list, 
				array('options' => array($s->status=>array('selected'=>true)),'class'=>'form-control'));
		?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'aloitan'); ?>
		<?php echo $form->textField($model,'aloitan',array('value'=>$s->aloitan,'size'=>60,'maxlength'=>100,'class'=>'form-control al')); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'loppui'); ?>
		<?php echo $form->textField($model,'loppui',array('value'=>$s->loppui,'size'=>60,'maxlength'=>100,'class'=>'form-control lp')); ?>
	</div>

  </div><div class="col-sm-2">

	<div class="row">
		<div id="kesto"><?php echo $kesto; ?></div>
	</div>

  </div><div class="col-sm-6">

	<div class="row">
	<br>
	<?php
		$m = Kohteet::model()->findbypk($s->kohdenID);
		if(isset($m->kenella_on_avain))
		{
		  $k = explode("//",$m->kenella_on_avain);

		  $ohje = '';
		  if(!empty($m->toimenpiteet))
		    $ohje .= "\n\nToimenpiteet:\n".$m->toimenpiteet;
		  if(!empty($m->tietoja))
		    $ohje .= "\n\nTietoja:\n".$m->tietoja;
		  if(!empty($m->muut))
		    $ohje .= "\n\nMuut:\n".$m->muut;
		  echo '<textarea class="form-control" rows="12" >'.$ohje.'</textarea>';
		}
	?>
	</div>

<?php $this->endWidget(); ?>


  </div>
</div><!-- form -->

	<div class="modal-footer">
		<span class="btn btn-default" data-dismiss="modal">Sulje</span>
		<?php echo CHtml::Button('Tallenna',array('class'=>'btn btn-primary uusiTot')); ?>
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

<?php
/*
<!--
	<div class="row">
		<?php echo $form->labelEx($model,'tyoajanlaatu'); ?>
		<?php echo $form->textField($model,'tyoajanlaatu',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tyoajanlaatu'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tyoajanmerkinta'); ?>
		<?php echo $form->textField($model,'tyoajanmerkinta',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tyoajanmerkinta'); ?>
	</div>


	<div class="row">
		<?php echo $form->labelEx($model,'tietoja'); ?>
		<?php echo $form->textArea($model,'tietoja',array('rows'=>6, 'cols'=>50,'value'=>$s->tietoja,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tietoja'); ?>
	</div>
-->
*/
?>
