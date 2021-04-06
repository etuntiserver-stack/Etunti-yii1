<?php
/* @var $this ToteutuneetController */
/* @var $model Toteutuneet */
/* @var $form CActiveForm */

if(isset($_POST['forid'])){
  $s= Mobile::model()->findbypk($_POST['forid']);
  $model->kohde_kannasta = $s->kohde_kannasta;
  $model->tuoteID = $s->tuoteID;
}

	  $s->loppui = date("d.m.Y H:i",strtotime($s->loppui));
	  $s->aloitan = date("d.m.Y H:i",strtotime($s->aloitan));

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


<div class="row">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'toteutuneet-form',
	'enableAjaxValidation'=>false,
)); ?>


		<input type="hidden" name="forPVM" value="<?php echo $forPVM; ?>">
		<?php echo $form->hiddenField($model,'asiakas_num',array('value'=>$s->asiakas_num)); ?>
		<?php echo $form->hiddenField($model,'kid',array('value'=>$s->id)); ?>
		<?php echo $form->hiddenField($model,'tid',array('value'=>$s->tid)); ?>
		<?php echo $form->hiddenField($model,'tekijan_nimi',array('value'=>$s->tekijan_nimi)); ?>
		<?php //echo $form->hiddenField($model,'viesti',array('value'=>$s->viesti)); ?>
		<?php echo $form->hiddenField($model,'asiakas_hyvaksy',array('value'=>$s->asiakas_hyvaksy)); ?>
		<?php echo $form->hiddenField($model,'hyvaksytty',array('value'=>$s->hyvaksytty)); ?>
		<?php echo $form->hiddenField($model,'tv_id',array('value'=>$s->tv_id)); ?>
		<?php echo $form->hiddenField($model,'laskutettu',array('value'=>$s->laskutettu)); ?>


  <div class="col-sm-12">
	<div class="section">
		<?php echo $form->labelEx($model,'viesti'); ?>
		<?php echo $form->textarea($model,'viesti',array('value'=>$s->viesti, 'rows'=>4,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'viesti'); ?>
	</div>

	<div class="section">
		<?php echo $form->labelEx($model,'tietoja'); ?>
		<?php echo $form->textarea($model,'tietoja',array('rows'=>4,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tietoja'); ?>
	</div>
  <br>
  </div>

  <div class="col-sm-4">

	<div class="section">
		<?php echo $form->labelEx($model,'tuoteID'); ?>
		<?php
		$criteria = new CDbCriteria();
       		$criteria->condition = " aktiivinen=1 AND hinta_alv_0!=0 ";
		echo $form->dropDownList($model,'tuoteID', CHtml::listData(TuotteetPalvelut::model()->findAll($criteria), 'id', 'nimike'), 
		array('empty'=>'Valitse tuote/palvelu','class'=>'form-control'));
		?>
	</div>

	<div class="section">
		<?php echo $form->labelEx($model,'kohde_kannasta'); ?>
		<?php 

		         $opt = array($s->kohdenID=>array('selected'=>true));
/*
		      if($s->status == 2){
		         $k = Kohteet::model()->find("osoite='MATKA'");
		         $opt = array($k->id=>array('selected'=>true));
		      } elseif($s->status == 10){
		         $k = Kohteet::model()->find("osoite='LOUNASTAUKO'");
		         $opt = array($k->id=>array('selected'=>true));
		      } 
*/
		      echo $form->dropDownList($model,'kohde_kannasta', 
			CHtml::listData(Kohteet::model()->findAll(array('order' => 'osoite ASC')), 'id', 'osoite'), 
			    array(
                		'empty'=>'Valitse',
                		'class'=>'form-control input-sm',
		                'options' => $opt,
			    )
			);
		?>
		<?php echo $form->error($model,'kohde_kannasta'); ?>
	</div>

	<div class="section">
		<?php echo $form->labelEx($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'osoite'); ?>
	</div>

	<div class="section">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php 
		      $list = array(3=>Yii::t('main','TYÖ'),2=>Yii::t('main','MATKA'),10=>Yii::t('main','LOUNASTAUKO'));
		      //array_unshift($list, $list[$s->status]);
		      echo $form->dropDownList($model,'status', 
			 	$list, 
				array('options' => array($s->status=>array('selected'=>true)),'class'=>'form-control input-sm'));
		?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="section">
		<?php echo $form->labelEx($model,'aloitan'); ?>
		<?php echo $form->textField($model,'aloitan',array('value'=>$s->aloitan,'size'=>60,'maxlength'=>100,'class'=>'form-control input-sm al', 'autofocus'=>'yes')); ?>
	</div>
	<div class="section">
		<?php echo $form->labelEx($model,'loppui'); ?>
		<?php echo $form->textField($model,'loppui',array('value'=>$s->loppui,'size'=>60,'maxlength'=>100,'class'=>'form-control input-sm lp')); ?>
	</div>
	<div class="section">
		<?php echo $form->labelEx($model,'laskutetaan'); ?>
		<?php 
        	$tal = array(1=>'Kyllä',0=>'Ei');
		$optS = array($s->laskutetaan=>array('selected'=>true));
		echo $form->dropDownList($model,'laskutetaan', $tal, 
		array('class'=>'form-control input-sm', 'options'=>$optS)) ?>
		<?php echo $form->error($model,'laskutetaan'); ?>
	</div>
	<div class="section">
		<?php echo $form->labelEx($model,'palkanlaskentaan'); ?>
		<?php 
        	$tal = array(1=>'Kyllä',0=>'Ei');
		echo $form->dropDownList($model,'palkanlaskentaan', $tal, 
		array('class'=>'form-control input-sm')) ?>
		<?php echo $form->error($model,'palkanlaskentaan'); ?>
	</div>

  </div><div class="col-sm-8">

	<div class="section">
	<label><?php echo Yii::t('main','Tietoja mobiilisovellukseen'); ?></label>
	<p class="panel-footer"><?php if(isset($s->tyovuoroot->id)){ echo str_replace("\n", "<br>", $s->tyovuoroot->tietoja); } ?></p>
	</div>

	<div class="section">
		<div id="kesto"><?php echo $kesto; ?></div>

		<!-- Tyoerittelyt-->
		<div id="erittelynlista">
		 <?php if(isset($s->tyovuoroot->id) and is_array(json_decode($s->tyovuoroot->tyo_erittelyt, true))): ?>
		 <div class="erittelynlista_laatiko">
		 <legend><?php echo Yii::t('main','Työerittelyt'); ?></legend>
		 <?php foreach(json_decode($s->tyovuoroot->tyo_erittelyt, true) as $k => $v): ?>
		 <div class="row">
		  <div class="col-sm-11">
			<?=$v?>
		  </div>
		  <div class="col-sm-1">
			<?php if( is_array(json_decode($s->tyo_erittelyt, true)) and in_array($k, json_decode($s->tyo_erittelyt, true)) ): ?>
			<input class="tyo_erittelyt pull-right" name="Toteutuneet[tyo_erittelyt][]" type="checkbox" value="<?=$k?>" checked>
			<?php else: ?>
			<input class="tyo_erittelyt pull-right" name="Toteutuneet[tyo_erittelyt][]" type="checkbox" value="<?=$k?>">
			<?php endif; ?>
		  </div>
		 </div>
		 <?php endforeach; ?>
		 </div>
		 <?php endif; ?>
		</div>
		<!-- Tyoerittelyt //-->
	</div>

  </div>
</div><!-- form -->

<?php $this->endWidget(); ?>


		</div> <!-- end modal-content -->
	</div> <!-- end modal-dialog -->

<br>

	<div class="modal-footer">
		<span class="btn btn-danger poistaRivit" id="<?php echo $s->id; ?>" for="<?php echo date('Ymd', strtotime($s->aloitan)); ?>_<?php echo $s->tid; ?>"><?php echo Yii::t('main', 'Poista'); ?></span>
		<span class="btn btn-default" data-dismiss="modal">Sulje</span>
		<?php echo CHtml::Button('Tallenna',array('class'=>'btn btn-primary uusiTot')); ?>
	</div>		






	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.mask.js"></script>



<script type="text/javascript">
$(document).ready(function(){


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

/*
$('.poistaRivit').click(function(){
	var thisVal = $(this).attr('id');
	var r = confirm('Oletko varmaa?');
	if(r){
        $.ajax({
           url: 'poista_luetut_toteutuneet',
           type: "POST",
           data: { "id" : thisVal },
           success: function(data){
		var d = JSON.parse(data);
		console.log(d);

		window.location.reload();
		//$('#showres').modal('hide');

           }
        });
	}
});
*/


});
</script>

<?php
/*
<!--
	<div class="section">
		<?php echo $form->labelEx($model,'tyoajanlaatu'); ?>
		<?php echo $form->textField($model,'tyoajanlaatu',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'tyoajanlaatu'); ?>
	</div>

	<div class="section">
		<?php echo $form->labelEx($model,'tyoajanmerkinta'); ?>
		<?php echo $form->textField($model,'tyoajanmerkinta',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'tyoajanmerkinta'); ?>
	</div>


	<div class="section">
		<?php echo $form->labelEx($model,'tietoja'); ?>
		<?php echo $form->textArea($model,'tietoja',array('rows'=>6, 'cols'=>50,'value'=>$s->tietoja,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'tietoja'); ?>
	</div>
-->
*/
?>
