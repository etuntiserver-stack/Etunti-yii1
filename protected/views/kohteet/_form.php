<?php
/* @var $this KohteetController */
/* @var $model Kohteet */
/* @var $form CActiveForm */
?>

<div class="row form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'kohteet-form',
	'enableAjaxValidation'=>false,
)); ?>



	<?php echo $form->errorSummary($model); ?>

  <div class="col-sm-3">


	<div class="row">
		<?php echo $form->labelEx($model,'asiakas_id'); ?>
    		<?php 
       		$criteria = new CDbCriteria();
		//$criteria->select = " COALESCE(NULLIF(yhteyshenkilo,yhteyshenkilo),'gg') AS yht ";
		$criteria->order = " yhteyshenkilo ";

        	$a = Asiakkaat::model()->findAll($criteria);
		echo '<select name="Kohteet[asiakas_id]" class="form-control input-sm" id="Kohteet_asiakas_id">';

		if(isset($model->asiakas_id) and !empty($model->asiakas_id))
		{
        	  $aon = Asiakkaat::model()->findbypk($model->asiakas_id);
		  if(!empty($aa->yrityksen_nimi))
		    echo '<option value="'.$aon->id.'">'.$aon->yrityksen_nimi.'</option>';
		  elseif(empty($aon->yhteyshenkilo) and empty($aon->yrityksen_nimi))
		    echo '<option value="'.$aon->id.'">nimet puutuu '.$aon->id.'</option>';
		  else
		    echo '<option value="'.$aon->id.'">'.$aon->yhteyshenkilo.' ID:'.$aon->id.'</option>';
		} else {
	        echo '<option></option>';
		}

		foreach($a as $aa)
		{
		  if(!empty($aa->yrityksen_nimi))
		    echo '<option value="'.$aa->id.'">'.$aa->yrityksen_nimi.'</option>';
		  elseif(empty($aa->yhteyshenkilo) and empty($aa->yrityksen_nimi))
		    echo '<option value="'.$aa->id.'">nimet puutuu '.$aa->id.'</option>';
		  else
		    echo '<option value="'.$aa->id.'">'.$aa->yhteyshenkilo.' ID:'.$aa->id.'</option>';
		}
		echo '</select>';
		?>
	</div>


	<div class="row">
		<?php echo $form->labelEx($model,'etu_suku_nimet'); ?>
		<?php echo $form->textField($model,'etu_suku_nimet',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'etu_suku_nimet'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('size'=>50,'maxlength'=>50,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'osoite'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kaupunki'); ?>
		<?php echo $form->textField($model,'kaupunki',array('size'=>20,'maxlength'=>20,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'kaupunki'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pnumero'); ?>
		<?php echo $form->textField($model,'pnumero',array('size'=>7,'maxlength'=>7,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'pnumero'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>72,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'puh_nro'); ?>
		<?php echo $form->textField($model,'puh_nro',array('size'=>50,'maxlength'=>50,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'puh_nro'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'toimipaikka'); ?>
		<?php echo $form->textField($model,'toimipaikka',array('size'=>20,'maxlength'=>20,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'toimipaikka'); ?>
	</div>

  </div><div class="col-sm-3">

	<div class="row">
		<?php echo $form->labelEx($model,'tag_id'); ?>
		<?php echo $form->textField($model,'tag_id',array('size'=>20,'maxlength'=>20,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'tag_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'gps_sijainti'); ?>
		<?php echo $form->textField($model,'gps_sijainti',array('size'=>50,'maxlength'=>50,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'gps_sijainti'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'siivous'); ?>

		<?php
      		$l = Valikkoot::model()->findAll(" select_type='siivous' ",array('order' => "select_type"));
		foreach($l as $v)
		$list[$v->id."//".$v->value] = $v->value;

        	echo $form->dropDownList($model, 'siivous', $list,
		array('empty'=>'','class'=>'form-control input-sm'));
        	?>

		<?php echo $form->error($model,'siivous'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'aktiivinen'); ?>
		<?php
		$list = array(0=>'Ei',1=>'Kyllä');
        	echo $form->dropDownList($model, 'aktiivinen', $list,
		array('empty'=>'','class'=>'form-control input-sm'));
        	?>
		<?php echo $form->error($model,'aktiivinen'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'avain'); ?>
		<?php echo $form->textField($model,'avain',array('size'=>60,'maxlength'=>255,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'avain'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kenella_on_avain'); ?>

		<?php
      		$l = Tyontekijat::model()->findAll(array('order' => "tekijan_nimi"));
		foreach($l as $v)
		$listt[$v->id."//".$v->tekijan_nimi] = $v->tekijan_nimi;

        	echo $form->dropDownList($model, 'kenella_on_avain', $listt,
		array('empty'=>'','class'=>'form-control input-sm'));
        	?>

		<?php echo $form->error($model,'kenella_on_avain'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ryhma'); ?>
		<?php
		$list = array();
      		$l = Valikkoot::model()->findAll(" select_type='asiakas_ryhma' ",array('order' => "select_type"));
		foreach($l as $v)
		$list[$v->id] = $v->value;

		if(count($list) > 0)
		{
        	echo $form->dropDownList($model, 'ryhma', $list,
		array('empty'=>'Valitse ryhmä','class'=>'form-control input-sm'));
		} else {
		echo 'Luo Valikko tietokannassa "Select Type = asiakas_ryhma"';
		}		
        	?>
		<?php echo $form->error($model,'ryhma'); ?>
	</div>

  </div><div class="col-sm-6">

	<div class="row">
		<?php echo $form->labelEx($model,'aikataulu'); ?>
		<?php echo $form->textArea($model,'aikataulu',array('rows'=>6, 'cols'=>50,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'aikataulu'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hinnoittelu'); ?>
		<?php echo $form->textArea($model,'hinnoittelu',array('rows'=>6, 'cols'=>50,'class'=>'form-control input-sm')); ?>

		<?php echo $form->error($model,'hinnoittelu'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'muut'); ?>
		<?php echo $form->textArea($model,'muut',array('rows'=>6, 'cols'=>50,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'muut'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'toimenpiteet'); ?>
		<?php echo $form->textArea($model,'toimenpiteet',array('rows'=>6, 'cols'=>50,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'toimenpiteet'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tietoja'); ?>
		<?php echo $form->textArea($model,'tietoja',array('rows'=>6, 'cols'=>50,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'tietoja'); ?>
	</div>

  </div>
</div><!-- form -->

	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

<br>

<?php
   echo '<div class="row">';
	$i = 0;
	foreach(array_reverse(glob(Yii::app()->basePath."/../img/uploadedfromphone/".Yii::app()->user->domain."/".$model->id."_*.*")) as $file) {
	$i++;
	$explNimi = explode("/",$file);
 	echo '
	<div class="col-sm-3">
	  <div class="btn btn-danger poistaKuvan pull-right" this="'.$file.'">X</div>
	  <img src="../../img/uploadedfromphone/'.Yii::app()->user->domain.'/'.end($explNimi).'" class="img-responsive thumbnail">
	</div>
	';
	}
   echo '</div>';
?>



<script type="text/javascript">
$(document).ready(function(){


// Send form by ajax
$('#Kohteet_asiakas_id').change(function(){

   var thisVal = $(this).val();

   $.ajax({
      url: 'autotaytaminen?id='+thisVal,
      //type: "POST",
      //data: { index_ajax : "true" },
      success: function(data){
	  console.log(data);
	  var sp = data.split("//");
	  $("#Kohteet_etu_suku_nimet").val(sp[0]);
	  //$("#Kohteet_kaupunki").val(sp[2]);
	  //$("#Kohteet_pnumero").val(sp[3]);
	  $("#Kohteet_email").val(sp[3]);
	  $("#Kohteet_puh_nro").val(sp[4]);

	  if(sp[6] != 0)
	  {
	  var ryhma = sp[5].split("-");
	  $("#Kohteet_ryhma option:selected").val(ryhma[0]);
	  $("#Kohteet_ryhma option:selected").text(ryhma[1]);
	  }
      }
   });

});


});
</script>


<?php /*

	<div class="row">
		<?php echo $form->labelEx($model,'tyoryhma'); ?>
		<?php echo $form->textField($model,'tyoryhma',array('size'=>20,'maxlength'=>20,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'tyoryhma'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ryhma'); ?>
		<?php echo $form->textField($model,'ryhma',array('size'=>10,'maxlength'=>10,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'ryhma'); ?>
	</div>


	<div class="row">
		<?php echo $form->labelEx($model,'maksuehto_paiva'); ?>
		<?php echo $form->textField($model,'maksuehto_paiva',array('class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'maksuehto_paiva'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'viivastyskorko'); ?>
		<?php echo $form->textField($model,'viivastyskorko',array('size'=>10,'maxlength'=>10,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'viivastyskorko'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lasku_tiedot'); ?>
		<?php echo $form->textField($model,'lasku_tiedot',array('size'=>60,'maxlength'=>255,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'lasku_tiedot'); ?>
	</div>
*/
?>


