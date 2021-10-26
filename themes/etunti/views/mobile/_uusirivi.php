<?php
/* @var $this MobileController */
/* @var $model Mobile */
/* @var $form CActiveForm */

$pvm = '';
if(!isset($_POST['forThis']))
{
	exit;
} else {
	$ex = explode("_",$_POST['forThis']);
	$pvm = $ex[0];
}
$t = Tyontekijat::model()->findbypk($ex[1]);
?>


	<div class="modal-dialog modal-lg">
	    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		<h2 class="modal-title"><?php echo Yii::t('main', 'Uuden rivin lisääminen'); ?></h2>
	
		</div>
		<div class="modal-body">

	<div class="dialogTable clearfix modal-osio">


<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'mobile-form',
	'enableAjaxValidation'=>false,
	'clientOptions' => array(
                    'validateOnSubmit' => false,
                ),
)); ?>

	<?php echo $form->errorSummary($model); ?>



  <input type="hidden" name="pvm" id="pvm" value="<?php echo $pvm; ?>">
  <div class="row">
    <div class="col-sm-12">
    <legend>
	<b><?php echo Yii::t('main','Päivämäärä').' '.date("d.m.Y",strtotime($pvm)); ?></b>
    </legend>
    </div>

    <div class="col-sm-3">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tid'); ?>
		<?php echo $form->textField($model,'tid',array('value'=>$t->id,'class'=>'form-control input-sm','readonly'=>'yes')); ?>
		<?php echo $form->error($model,'tid'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tekijan_nimi'); ?>
		<?php echo $form->textField($model,'tekijan_nimi',array('value'=>$this->etuSukunimi($t->id),'class'=>'form-control input-sm','readonly'=>'yes')); ?>
		<?php echo $form->error($model,'tekijan_nimi'); ?>
	</div>

	<div class="section">
		<?php echo $form->labelEx($model,'tuoteID'); ?>
		<?php
		$criteria = new CDbCriteria();
       		$criteria->condition = " aktiivinen=1 AND hinta_alv_0!=0 ";
		echo $form->dropDownList($model,'tuoteID', CHtml::listData(TuotteetPalvelut::model()->findAll($criteria), 'id', 'nimike'), 
		array('empty'=>'Valitse tuote/palvelu','class'=>'form-control'));
		?>
	</div>

    </div><div class="col-sm-3">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'aloitan'); ?>
		<?php echo $form->textField($model,'aloitan',array('value'=>date("d.m.Y",strtotime($pvm)).' 00:00', 'size'=>20,'maxlength'=>20,'class'=>'form-control input-sm al')); ?>
		<?php echo $form->error($model,'aloitan'); ?>
	</div>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'loppui'); ?>
		<?php echo $form->textField($model,'loppui',array('value'=>date("d.m.Y",strtotime($pvm)).' 00:00','size'=>20,'maxlength'=>20,'class'=>'form-control input-sm lp')); ?>
		<?php echo $form->error($model,'loppui'); ?>
	</div>

    </div><div class="col-sm-3">

	<div class="section fill mb5 select2-slim">
		<?php echo $form->labelEx($model,'kohde_kannasta'); ?>
		<?php echo $form->dropDownList($model,'kohde_kannasta', 
			CHtml::listData(Kohteet::model()->findAll(array('order' => 'osoite ASC')), 'id', 'osoite'), 
			array('empty'=>Yii::t('main','Valitse kohde'),'class'=>'form-control input-sm select2-bootstrap')); ?>
		<?php echo $form->error($model,'kohde_kannasta'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php 
		      $list = array(3=>Yii::t('main','TYÖ'),2=>Yii::t('main','MATKA'),10=>Yii::t('main','LOUNASTAUKO'));
		      //array_unshift($list, $list[$s->status]);
		      echo $form->dropDownList($model,'status', 
			 	$list, 
				array('class'=>'form-control input-sm'));
		?>
		<?php echo $form->error($model,'status'); ?>
	</div>

    </div><div class="col-sm-3">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kohdenID'); ?>
		<?php echo $form->textField($model,'kohdenID',array('size'=>20,'maxlength'=>20,'class'=>'form-control input-sm','readonly'=>'yes')); ?>
		<?php echo $form->error($model,'kohdenID'); ?>
	</div>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'laskutetaan'); ?>
		<?php 
        	$tal = array(1=>'Kyllä',0=>'Ei');

		echo $form->dropDownList($model,'laskutetaan', $tal, 
		array('class'=>'form-control input-sm')) ?>
		<?php echo $form->error($model,'laskutetaan'); ?>
	</div>
    </div>
  </div>


<?php $this->endWidget(); ?>

	<br>
	<div class="modal-footer">
		<span class="btn btn-default" data-dismiss="modal">Sulje</span>
		<?php echo CHtml::Button('Tallenna',array('class'=>'btn btn-primary uusiRiviSubmit')); ?>
	</div>		
		</div> <!-- end modal-content -->
	</div> <!-- end modal-dialog -->


	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/asetukset.js"></script>


<!--
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/toteuma.js"></script>

-->





</div><!-- form -->


	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.mask.js"></script>

<script type="text/javascript">
$(document).ready(function(){

$("#Mobile_kohde_kannasta").change(function(){
	var kohdenID = $(this).val();
	$("#Mobile_kohdenID").val(kohdenID);
});


  $('.al').mask('00.00.0000 00:00',{
        placeholder: "__.__.____ __:__"
  });

  $('.lp').mask('00.00.0000 00:00',{
        placeholder: "__.__.____ __:__"
  });


$('.al').blur(function(){
	checkMaxValues($(this).val());
});

$('.lp').blur(function(){
	checkMaxValues($(this).val());
});

// init select2
$("#Mobile_kohde_kannasta").select2();

function checkMaxValues(val){
	var check = val.split(" ");
	var dat = check[0].split(".");
	var time = check[1].split(":");
	
	if(dat[0] > 31)
	alert('Päivät eivät voi olla yli 31.')
	if(dat[1] > 12)
	alert('Kuukaudet eivät voi olla yli 12.')

	if(time[0] > 23)
	alert('Tunnit eivät voi olla yli 23.')
	if(time[1] > 59)
	alert('Minutit eivät voi olla yli 59.')
}

	// toteuma.js on toimimassa





});
</script>
