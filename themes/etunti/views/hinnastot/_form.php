<?php
/* @var $this HinnastotController */
/* @var $model Hinnastot */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'hinnastot-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

<div class="row">
 <div class="col-sm-4">

	<?php echo $form->errorSummary($model); ?>


	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'hinnaston_otsikko'); ?>
		<?php echo $form->textField($model,'hinnaston_otsikko',array('class' => 'form-control','maxlength'=>255)); ?>
		<?php echo $form->error($model,'hinnaston_otsikko'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'aktiivinen'); ?>
		<?php
		$list = array(1=>Yii::t('main', 'Kyllä'),0=>Yii::t('main', 'Ei'));
        	echo $form->dropDownList($model, 'aktiivinen', $list,
		array('class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'aktiivinen'); ?>
	</div>

 </div>
</div><!-- form -->

<br>

<!-- Taulu -->
<?php if(isset($model->id)) : ?>
<?php $rivit = HinnastotRivi::model()->findAll(" hinnastot_id='".$model->id."' "); ?>
<?php endif; ?>

<div class="row">
 <div class="col-sm-12">

	<div class="row">
	 <div class="col-sm-4">
		<b>Hinnat sis. ALV</b> <input type="radio" name="alvsis" value="sis"> <br>
		<b>Hinnat ALV0</b> <input type="radio" name="alvsis" value="nolla" checked>
	 </div>
	</div>

  <div class="table-responsive">
   <table class="table table-bordered bg-white" id="TableHinnasto">
    <tr>
     <th><?=Yii::t('main', 'TUOTE')?></th>
     <th><?=Yii::t('main', 'HINTA TUOTTEISTA JA PALVELUISTA')?></th>
     <th><?=Yii::t('main', 'Hinta ALV0')?></th>
     <th><?=Yii::t('main', 'HINNASTON ALV%')?></th>
     <th width="150"><?=Yii::t('main', 'Hinta sis. ALV')?></th>
     <th width="100"><?=Yii::t('main', 'YKSIKKÖ')?></th>
     <th></th>
    </tr>

    <?php if(isset($model->id) and count($rivit) > 0) : ?>
    <?php foreach($rivit as $r) : ?>
    <tr>
     <td>
	<select name="Rivi[tuote][tuote][]" class="form-control tuotevalikko">
	 <?php foreach($tp as $itm) : ?>
	 <option value=<?=$itm->id?> hinta_alv_0="<?=$itm->hinta_alv_0?>" yksikko="<?=$itm->yksikko?>" alv="<?=$itm->alv?>" <?php echo ($itm->id == $r->tuote_palvelu_id)? 'selected':''; ?>><?=$itm->nimike?></option>
	 <?php endforeach; ?>
	</select>
     </td>
     <td><input type="number" class="form-control hinta_tuote" name="Rivi[tuote][hinta_tuote][]" step="any" value="<?=$r->hinta_tuote?>"></td>
     <td><input type="number" class="form-control hinnasto_hinta" name="Rivi[tuote][hinnasto_hinta][]" step="any" value="<?=$r->hinnasto_hinta?>"></td>
     <td>
	<?php $l = array(0=>0,10=>10,14=>14,24=>24); ?>
	<select name="Rivi[tuote][hinnasto_alv][]" class="form-control hinnasto_alv">
	 <option value="24">24</option>
	 <?php foreach($l as $itm) : ?>
	 <option value=<?=$itm?> <?php echo ($itm == $r->hinnasto_alv)? 'selected':''; ?>><?=$itm?></option>
	 <?php endforeach; ?>
	</select>
     </td>
     <td><input type="number" class="form-control hinnasto_yht" name="Rivi[tuote][hinnasto_yht][]" step="any" value="<?=$r->hinnasto_yht?>" readonly></td>
     <td>
	<select name="Rivi[tuote][yksikko][]" class="form-control yksikkovalikko">
	 <option value=>Valitse</option>
	 <?php foreach($yksikkot as $itm) : ?>
	 <option value=<?=$itm->value?> <?php echo ($itm->value == $r->hinnasto_yksikko)? 'selected':''; ?>><?=$itm->value?></option>
	 <?php endforeach; ?>
	</select>
     </td>
     <td>
	<i class="fa fa-trash-o fa-2x link poistarivi" aria-hidden="true"></i>
     </td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>

   </table>
   <br><p><span class="btn btn-success btn-sm uusiRivi"><i class="fa fa-plus" aria-hidden="true"></i></span></p>
  </div>
 </div>
</div>

<br>

	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna', array('class' => 'btn btn-primary myBgColors')); ?>
	</div>

<?php $this->endWidget(); ?>

<!-- uusiRiviKontentti -->
<textarea id="uusiRiviKontentti" style="display:none">
    <tr>
     <td>
	<select name="Rivi[tuote][tuote][]" class="form-control tuotevalikko">
	 <option value=>Valitse</option>
	 <?php foreach($tp as $itm) : ?>
	 <option value=<?=$itm->id?> hinta_alv_0="<?=$itm->hinta_alv_0?>" yksikko="<?=$itm->yksikko?>" alv="<?=$itm->alv?>"><?=$itm->nimike?></option>
	 <?php endforeach; ?>
	</select>
     </td>
     <td><input type="number" class="form-control hinta_tuote" name="Rivi[tuote][hinta_tuote][]" step="any" value="0"></td>
     <td><input type="number" class="form-control hinnasto_hinta" name="Rivi[tuote][hinnasto_hinta][]" step="any" value="0"></td>
     <td>
	<?php $l = array(0=>0,10=>10,14=>14,24=>24); ?>
	<select name="Rivi[tuote][hinnasto_alv][]" class="form-control hinnasto_alv">
	 <option value="24">24</option>
	 <?php foreach($l as $itm) : ?>
	 <option value=<?=$itm?>><?=$itm?></option>
	 <?php endforeach; ?>
	</select>
     </td>
     <td><input type="number" class="form-control hinnasto_yht" name="Rivi[tuote][hinnasto_yht][]" step="any" readonly></td>
     <td>
	<select name="Rivi[tuote][yksikko][]" class="form-control yksikkovalikko">
	 <option value=>Valitse</option>
	 <?php foreach($yksikkot as $itm) : ?>
	 <option value=<?=$itm->value?>><?=$itm->value?></option>
	 <?php endforeach; ?>
	</select>
     </td>
     <td>
	<i class="fa fa-trash-o fa-2x link poistarivi" aria-hidden="true"></i>
     </td>
    </tr>
</textarea>
<!-- uusiRiviKontentti -->


<script type="text/javascript">
$(document).ready(function(){

  $(".uusiRivi").click(function(){
	var kontenti = $("#uusiRiviKontentti").val();
	$("table#TableHinnasto tr").last().after( kontenti );
  });

  $(document).delegate(".tuotevalikko","change",function(){hinnasto_alv
	var hinta_alv_0 = $('option:selected', this).attr('hinta_alv_0');
	var hinnasto_alv = $('option:selected', this).attr('alv');
	var yksikko = $('option:selected', this).attr('yksikko');
	$(this).closest('tr').find('.hinta_tuote').val(hinta_alv_0);
	$(this).closest('tr').find('.yksikkovalikko').val(yksikko);
	$(this).closest('tr').find('.hinnasto_alv').val(hinnasto_alv);
  });

  $(document).delegate(".hinnasto_hinta","keyup",function(){
	var alvsis = $('input[name=alvsis]:checked').val();
	if( alvsis == 'nolla'){
		var hinnasto_hinta = parseFloat($(this).closest('tr').find('.hinnasto_hinta').val());
		var alv = $(this).closest('tr').find('.hinnasto_alv option:selected').val();
		var yht = hinnasto_hinta/100*alv;
		var summ = hinnasto_hinta+yht;
		$(this).closest('tr').find('.hinnasto_yht').val(summ.toFixed(2));
	}
  });

  $(document).delegate(".hinnasto_alv","change",function(){
	var alvsis = $('input[name=alvsis]:checked').val();
	if( alvsis == 'nolla'){
		var hinnasto_hinta = parseFloat($(this).closest('tr').find('.hinnasto_hinta').val());
		var alv = $(this).closest('tr').find('.hinnasto_alv option:selected').val();
		var yht = hinnasto_hinta/100*alv;
		var summ = hinnasto_hinta+yht;
		$(this).closest('tr').find('.hinnasto_yht').val(summ.toFixed(2));
	}
	if( alvsis == 'sis'){
		var hinnasto_yht = parseFloat($(this).closest('tr').find('.hinnasto_yht').val());
		var alv = $(this).closest('tr').find('.hinnasto_alv option:selected').val();
		var laske = hinnasto_yht/100*alv;
		var summ = hinnasto_yht-laske;
		$(this).closest('tr').find('.hinnasto_hinta').val(summ.toFixed(2));
	}
  });

  $(document).delegate(".poistarivi","click",function(){
	$(this).closest('tr').remove();
  });

  $('input[name=alvsis]').change(function(){
	var alvsis = $('input[name=alvsis]:checked').val();
	if( alvsis == 'nolla'){
		$('.hinnasto_yht').attr('readonly', 'yes');
		$('.hinnasto_hinta').removeAttr('readonly');
	}
	if( alvsis == 'sis'){
		$('.hinnasto_yht').removeAttr('readonly');
		$('.hinnasto_hinta').attr('readonly', 'yes');
	}
  });

  $(document).delegate(".hinnasto_yht","keyup",function(){
	var alvsis = $('input[name=alvsis]:checked').val();
	if( alvsis == 'sis'){
		var hinnasto_yht = $(this).closest('tr').find('.hinnasto_yht').val();
		var alv = $(this).closest('tr').find('.hinnasto_alv option:selected').val();
		var jakaa = '1.'+alv;
		var laske = hinnasto_yht/parseFloat(jakaa);
		$(this).closest('tr').find('.hinnasto_hinta').val(laske.toFixed(2));
	}
  });

});
</script>




