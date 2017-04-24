<?php
/* @var $this CrmTarjouksetController */
/* @var $model CrmTarjoukset */
/* @var $form CActiveForm */

 $asiakas_selected = array();
 $yhteystiedot_selected = array();
 $tyonkuvaus = '';
 $tarjouslaskenta = '';

 $crmTarjoukset = Yii::app()->createController('CrmTarjoukset');

 if(isset($_GET['asiakas_id']) and !empty($_GET['asiakas_id']))
 {
	$asiakas_selected[$_GET['asiakas_id']] = array('selected' => 'selected');
	$tyonkuvaus = $crmTarjoukset[0]->get_tyonkuvaus('asiakas_id', $_GET['asiakas_id']);
	//$tarjouslaskenta = $crmTarjoukset[0]->get_tarjouslaskenta('asiakas_id', $_GET['asiakas_id']);
 }
/*
 if(isset($_GET['yhteystiedot_id']) and !empty($_GET['yhteystiedot_id']))
 {
	$yhteystiedot_selected[$_GET['yhteystiedot_id']] = array('selected' => 'selected');
	$tyonkuvaus = $crmTarjoukset[0]->get_tyonkuvaus('yhteystiedot_id', $_GET['yhteystiedot_id']);
	$tarjouslaskenta = $crmTarjoukset[0]->get_tarjouslaskenta('yhteystiedot_id', $_GET['yhteystiedot_id']);
 }
*/
?>



<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'crm-tarjoukset-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>



<!-- hattu -->
<div class="row">
     <div class="col-sm-4">
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'template'); ?>
		<?php
		$tmp_list = array();
		foreach(glob(Yii::app()->baseUrl.$this->templates_polkku().'/*.docx') as $file) 
		{
			$explNimi = explode("/",$file);
		 	$tmp_list[end($explNimi)] = end($explNimi);
		}
		?>
		<?php
        		echo $form->dropDownList($model, 'template', $tmp_list,
			array('empty'=>'Valitse', 'class'=>'form-control'));
		?>
		<?php echo $form->error($model,'template'); ?>
	</div>
     </div>
</div>
<hr>
<!-- hattu -->

<div class="row">
  <div class="col-sm-4">

	<?php echo $form->errorSummary($model); ?>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'asiakas_id'); ?>
		<?php
		$list = array();
		$criteria=new CDbCriteria;
		//$criteria->condition="";
      		$l = Asiakkaat::model()->findAll($criteria);
		foreach($l as $v)
		{
			if(!empty($v->yrityksen_nimi) and empty($v->yhteyshenkilo))
			$list[$v->id] = $v->yrityksen_nimi;
			elseif(empty($v->yrityksen_nimi) and !empty($v->yhteyshenkilo))
			$list[$v->id] = $v->yhteyshenkilo;
		}

		if(count($list) > 0)
		{
        		echo $form->dropDownList($model, 'asiakas_id', $list,
			array('empty'=>'Valitse','class'=>'form-control', 'options'=>$asiakas_selected));
		}		
        	?>
		<?php echo $form->error($model,'asiakas_id'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tarjous_id'); ?>
		<?php
		$list = array();
		$criteria=new CDbCriteria;
		//if(isset($_GET['yhteystiedot_id']))
			//$criteria->condition=" yhteystiedot_id='".$_GET['yhteystiedot_id']."' ";
		if(isset($_GET['asiakas_id']))
			$criteria->condition=" asiakas_id='".$_GET['asiakas_id']."' AND status=3 ";
      		$l = CrmTarjoukset::model()->findAll($criteria);
		foreach($l as $v)
		{
			$list[$v->id] = date("d.m.Y H:i", strtotime($v->time)).', '.$v->kohteen_osoite;
		}

        		echo $form->dropDownList($model, 'tarjous_id', $list,
			array('empty'=>'Valitse','class'=>'form-control'));
		
        	?>
		<?php echo $form->error($model,'yhteystiedot_id'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'teksti'); ?>
		<?php echo $form->textArea($model,'teksti',array('rows'=>6, 'cols'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'teksti'); ?>
	</div>

 </div>
</div><!-- form -->

<br>
	<div class="section">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary myBgColors')); ?>
	</div>

<?php $this->endWidget(); ?>




<script type="text/javascript">
$(document).ready(function(){


/*
 $('#CrmSopimukset_yhteystiedot_id').change(function(){
	window.location.href= "create?yhteystiedot_id=" + $(this).val();
 });
*/

});
</script>
