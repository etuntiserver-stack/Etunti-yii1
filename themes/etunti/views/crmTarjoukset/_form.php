<?php
/* @var $this CrmTarjouksetController */
/* @var $model CrmTarjoukset */
/* @var $form CActiveForm */
?>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'crm-tarjoukset-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>


<div class="row">
  <div class="col-sm-3">

	<legend><?php echo Yii::t('main', 'Perus tiedot'); ?></legend>

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

        		echo $form->dropDownList($model, 'asiakas_id', $list,
			array('empty'=>'Valitse','class'=>'form-control'));
		
        	?>
		<?php echo $form->error($model,'asiakas_id'); ?>
	</div>

	<div class="section fill mb5 kohdeHide">
		<?php echo $form->labelEx($model,'asiakkaan_sahkoposti'); ?>
		<?php echo $form->textField($model,'asiakkaan_sahkoposti',array('maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'asiakkaan_sahkoposti'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kohde_id'); ?>
		<?php
		$list = array();

		if( $model->asiakas_id != 0 )
		{
		$criteria=new CDbCriteria;
		$criteria->condition=" asiakas_id='".$model->asiakas_id."' ";
      		$l = Kohteet::model()->findAll($criteria);
		    foreach($l as $v)
			$list[$v->id] = $v->osoite;
		}
        	echo $form->dropDownList($model, 'kohde_id', $list,
			array('empty'=>'Valitse','class'=>'form-control'));		
        	?>
		<?php echo $form->error($model,'kohde_id'); ?>
	</div>

	<div class="section fill mb5 tyonkuvaus collapse">
		<?php echo $form->labelEx($model,'tyonkuvaus_id'); ?>
		<?php
		$list = array();

		if( $model->tyonkuvaus_id != 0 and $model->kohde_id != 0 )
		{
			echo '<script>$(document).ready(function(){$(\'.tyonkuvaus\').addClass(\'in\');});</script>';
			$criteria=new CDbCriteria;
			$criteria->condition=" kohde_id='".$model->kohde_id."' ";
	      		$l = Tyonkuvaus::model()->findAll($criteria);
			if( $l != null )
			{
			    foreach($l as $v)
			    {
				$k = Kohteet::model()->findByPk($v->kohde_id);
				if(isset($k->id))
				$list[$v->id] = date("d.m.Y", strtotime($v->time)).' - '.$k->osoite;
			    }
			}
		}

        	echo $form->dropDownList($model, 'tyonkuvaus_id', $list,
			array('empty'=>'Valitse','class'=>'form-control required'));
		
        	?>
		<?php echo $form->error($model,'tyonkuvaus_id'); ?>
	</div>



	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tarjous'); ?>
		<?php echo $form->textArea($model,'tarjous',array('class'=>'form-control', 'rows'=>4)); ?>
		<?php echo $form->error($model,'kohteen_osoite'); ?>
	</div>

 </div><div class="col-sm-3">

	<legend><?php echo Yii::t('main', 'Kohde'); ?></legend>

	<div class="section fill mb5 kohdeHide">
		<?php echo $form->labelEx($model,'kohteen_osoite'); ?>
		<?php echo $form->textField($model,'kohteen_osoite',array('maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'kohteen_osoite'); ?>
	</div>

	<div class="section fill mb5 kohdeHide">
		<?php echo $form->labelEx($model,'kohteen_postinumero'); ?>
		<?php echo $form->textField($model,'kohteen_postinumero',array('maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'kohteen_postinumero'); ?>
	</div>

	<div class="section fill mb5 kohdeHide">
		<?php echo $form->labelEx($model,'kohteen_postitoimipaikka'); ?>
		<?php echo $form->textField($model,'kohteen_postitoimipaikka',array('maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'kohteen_postitoimipaikka'); ?>
	</div>

 </div><div class="col-sm-6">

	<legend><?php echo Yii::t('main', 'Asiakkaan tiedot'); ?></legend>
	<div class="section fill mb5 kohdeHide">

		<div id="asiakas_tiedot">
		<?php
		if( $model->asiakas_id != 0 )
		{
			$a = Asiakkaat::model()->findByPk($model->asiakas_id);
			echo $this->renderPartial('//asiakkaat/view', 
				array('id'=>$a->id, 'model'=>$a)
			, true);
		}
		?>
		</div>
	</div>

 </div>
</div><!-- form -->






		<?php //echo $form->textArea($model,'tyonkuvaus',array('rows'=>6, 'cols'=>50, 'class'=>'form-control', 'style'=>'display:none')); ?>
		<?php //echo $form->textArea($model,'tarjouslaskenta',array('rows'=>6, 'cols'=>50, 'class'=>'form-control', 'style'=>'display:none')); ?>


	<div class="section fill mb5">
		<div id="tyonkuvaus"></div>
	</div>


<br>
	<div class="section">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary myBgColors submitButton')); ?>
	</div>

<?php $this->endWidget(); ?>



<?php if(isset($model->tyonkuvaus_id) and !empty($model->tyonkuvaus_id)) : ?>
<script type="text/javascript">
$(document).ready(function(){

        $.ajax({
           url: 'view_tyonkuvaus?id=<?php echo $model->tyonkuvaus_id; ?>',
           //type: "POST",
           //data: { },
           success: function(data){
		var data = JSON.parse(data);
		//console.log(data);
		if(data)
		{
			$('#tyonkuvaus').html('<br>' + data);
		}
           }
        });

});
</script>
<?php endif; ?>

<script type="text/javascript">
$(document).ready(function(){



 $('#crm-tarjoukset-form').on("submit", function(e){

    $(this).submit();
    e.preventDefault();

 });


 $('#CrmTarjoukset_tyonkuvaus_id').change(function(){

	var thisVal = $(this).val();
        $.ajax({
           url: 'get_tyonkuvaus?id=' + thisVal,
           //type: "POST",
           //data: { },
           success: function(data){
		var data = JSON.parse(data);
		//console.log(data);
		if(data)
		{
			$('#tyonkuvaus').html('<br>' + data);
		}
           }
        });

 });


 $('#CrmTarjoukset_kohde_id').change(function(){

	var thisVal = $(this).val();
        $.ajax({
           url: 'get_kohteentiedot?id=' + thisVal,
           //type: "POST",
           //data: { },
           success: function(data){
		var data = JSON.parse(data);
		console.log(data);
		if(data['kohde'])
		{
			$('#CrmTarjoukset_kohteen_osoite').val(data['kohde']['osoite']);
			$('#CrmTarjoukset_kohteen_postinumero').val(data['kohde']['pnumero']);
			$('#CrmTarjoukset_kohteen_postitoimipaikka').val(data['kohde']['kaupunki']);
		}

		if(data['tk'] !== '')
		{
			$('.tyonkuvaus').addClass('in');
			$('#CrmTarjoukset_tyonkuvaus_id').html(data['tk']);
		} else {
			$('.tyonkuvaus').removeClass('in');
			$('#CrmTarjoukset_tyonkuvaus_id').html(data['tk']);
		}
           }
        });

 });


 $('#CrmTarjoukset_asiakas_id').change(function(){

	var thisVal = $(this).val();
        $.ajax({
           url: 'get_kohde?id=' + thisVal,
           //type: "POST",
           //data: { },
           success: function(data){
		var data = JSON.parse(data);
		console.log(data);
		if(data['options'])
		{
			$('#CrmTarjoukset_kohde_id').html(data['options']);
		}
		if(data['asiakas_sahkoposti'])
		{
			$('#CrmTarjoukset_asiakkaan_sahkoposti').val(data['asiakas_sahkoposti']);
		}
		if(data['asiakas_tiedot'])
		{
			$('#asiakas_tiedot').html(data['asiakas_tiedot']);
		}

           }
        });

 });


});
</script>

