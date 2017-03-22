<?php
/* @var $this CrmTarjouksetController */
/* @var $model CrmTarjoukset */
/* @var $form CActiveForm */

 $asiakas_selected = array();
 $yhteystiedot_selected = array();
 $tyonkuvaus = '';
 $tarjouslaskenta = '';
 if(isset($_GET['asiakas_id']) and !empty($_GET['asiakas_id']))
 {
	$asiakas_selected[$_GET['asiakas_id']] = array('selected' => 'selected');
	$tyonkuvaus = $this->get_tyonkuvaus('asiakas_id', $_GET['asiakas_id']);
	$tarjouslaskenta = $this->get_tarjouslaskenta('asiakas_id', $_GET['asiakas_id']);
 }

/*
 if(isset($_GET['yhteystiedot_id']) and !empty($_GET['yhteystiedot_id']))
 {
	$yhteystiedot_selected[$_GET['yhteystiedot_id']] = array('selected' => 'selected');
	$tyonkuvaus = $this->get_tyonkuvaus('yhteystiedot_id', $_GET['yhteystiedot_id']);
	$tarjouslaskenta = $this->get_tarjouslaskenta('yhteystiedot_id', $_GET['yhteystiedot_id']);
 }
*/

 if(isset($model->id))
 {
	$tyonkuvaus_arr = json_decode($model->tyonkuvaus, true);
	$tarjouslaskenta_arr = json_decode($model->tarjouslaskenta, true);


			$tl_table = '';

/*
			$tl_table .= '<h2>'.Yii::t('main', 'Tarjouslaskenta').'</h2>';
			$tl_table .= '<table class="table table-bordered bg-white">';
			$tl_table .= '<tr>';
			$tl_table .= '<th>'.Yii::t('main', 'Kuvaus').'</th>';
			$tl_table .= '<th>'.Yii::t('main', 'Arvo').'</th>';
			$tl_table .= '</tr>';

			foreach($tarjouslaskenta_arr as $key=>$item)
			{
				if( $key == 'muut_kulut' and is_array(json_decode($item, true)['otsikko']))
				{
					$uusiItem = '';
					foreach(json_decode($item, true)['otsikko'] as $k2=>$muut)
					{
						$uusiItem .= $muut.": ".json_decode($item, true)['hinta'][$k2]."<br>";

					}
					$item = $uusiItem;
				}

				$label = Tarjouslaskenta::model()->getAttributeLabel($key);

				$tl_table .= '<tr>';
				$tl_table .= '<td>'.$label.'</td>';
				$tl_table .= '<td>'.$item.'</td>';
				$tl_table .= '</tr>';

			}
			$tl_table .= '</table>';

			echo $tl_table;
*/

			$tk_table = '';
			$tk_table .= '<h2>'.Yii::t('main', 'Työnkuvaus').'</h2>';
			$tk_table .= '<table class="table table-bordered bg-white">';
			$tk_table .= '<tr>';
			$tk_table .= '<th>'.Yii::t('main', 'Tilat').'</th>';
			$tk_table .= '<th>'.Yii::t('main', 'Työtehtävät').'</th>';
			$tk_table .= '<th>'.Yii::t('main', 'Laatutaso').'</th>';
			$tk_table .= '<th>'.Yii::t('main', 'Kommenti').'</th>';
			$tk_table .= '</tr>';

			foreach($tyonkuvaus_arr['tilat'] as $key=>$items)
			{
				$tyontehtavat = $tyonkuvaus_arr['tyontehtavat'][$key];
				$tt_result = '';
				foreach($tyontehtavat as $kt=>$it)
					$tt_result .= $it['tyotehtava'].': '.$it['vkopvm']."\n";

				$tk_table .= '<tr>';
				$tk_table .= '<td>'.implode("<br>", $items).'</td>';
				$tk_table .= '<td>'.$tt_result.'</td>';
				$tk_table .= '<td>'.implode("<br>", $tyonkuvaus_arr['laatutaso'][$key]).'</td>';
				$tk_table .= '<td>'.implode("<br>", $tyonkuvaus_arr['kommenti'][$key]).'</td>';
				$tk_table .= '</tr>';

			}
			$tk_table .= '</table>';

			echo $tk_table;
 }

/*
 if(!isset($model->id) and isset($_GET['yhteystiedot_id']))
 {
	$yt = Yhteystiedot::model()->findByPk($_GET['yhteystiedot_id']);
	if(isset($yt->id))
	{
		$model->kohteen_osoite = $yt->osoite;
		$model->kohteen_postinumero = $yt->postinumero;
		$model->kohteen_postitoimipaikka = $yt->postitoimipaikka;
	}
 }
*/
?>


<style>
.kohdeHide { display:none }
</style>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'crm-tarjoukset-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

<?php if(!isset($model->id)) : ?>
<div class="row">
  <div class="col-sm-4">


<?php /*
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'yhteystiedot_id'); ?>
		<?php
		$list = array();
		$criteria=new CDbCriteria;
		//$criteria->condition="";
      		$l = Yhteystiedot::model()->findAll($criteria);
		foreach($l as $v)
		{
			if(!empty($v->yrityksen_nimi) and empty($v->yhteyshenkilo))
			$list[$v->id] = $v->yrityksen_nimi;
			elseif(empty($v->yrityksen_nimi) and !empty($v->yhteyshenkilo))
			$list[$v->id] = $v->yhteyshenkilo;
		}

        		echo $form->dropDownList($model, 'yhteystiedot_id', $list,
			array('empty'=>'Valitse','class'=>'form-control', 'options'=>$yhteystiedot_selected));
		
        	?>
		<?php echo $form->error($model,'yhteystiedot_id'); ?>
	</div>
*/ ?>

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

	<?php if(isset($_GET['asiakas_id'])) : ?>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kohde_id'); ?>
		<?php
		$list = array();
		$criteria=new CDbCriteria;
		$criteria->condition=" asiakas_id='".$_GET['asiakas_id']."' ";
      		$l = Kohteet::model()->findAll($criteria);
		if( $l != null )
		{
		    foreach($l as $v)
		    {
			$list[$v->id] = $v->osoite;
		    }
		}

		if(count($list) > 0)
		{
        		echo $form->dropDownList($model, 'kohde_id', $list,
			array('empty'=>'Valitse','class'=>'form-control required', 'options'=>$asiakas_selected));
		}		
        	?>
		<?php echo $form->error($model,'kohde_id'); ?>
	</div>
	<?php endif; ?>

 </div><div class="col-sm-4">

	<?php if(isset($_GET['yhteystiedot_id'])) : ?>
	<script type="text/javascript">
	$(document).ready(function(){
	
	  // <-- onkoAsOsoiteSamaKunKohde
	  $(".onkoAsOsoiteSamaKunKohde").change(function() {
	    var value = $(this).val();
	    if(value == 'ei')
	    {
		$('.kohdeHide').show();
	    } else {
		$('#CrmTarjoukset_kohteen_osoite').val('');
		$('#CrmTarjoukset_kohteen_postinumero').val('');
		$('#CrmTarjoukset_kohteen_postitoimipaikka').val('');
		$('.kohdeHide').hide();
	    }
	  });
	
	});
	</script>

	<div class="section fill mb5">
		<label><?php echo Yii::t('main', 'Onko kohteen osoite sama kuin asiakkaan osoite?'); ?> </label><br>
		<input type="radio" name="CrmTarjoukset[onko_osoite_sama]" class="onkoAsOsoiteSamaKunKohde" checked value="kylla"> <?php echo Yii::t('main', 'Kyllä'); ?><br>
		<input type="radio" name="CrmTarjoukset[onko_osoite_sama]" class="onkoAsOsoiteSamaKunKohde" value="ei"> <?php echo Yii::t('main', 'Ei'); ?>
	</div>
	<?php endif; ?>

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

 </div>
</div><!-- form -->
<?php endif; ?>



		<?php echo $form->textArea($model,'tyonkuvaus',array('rows'=>6, 'cols'=>50, 'class'=>'form-control', 'style'=>'display:none')); ?>
		<?php echo $form->textArea($model,'tarjouslaskenta',array('rows'=>6, 'cols'=>50, 'class'=>'form-control', 'style'=>'display:none')); ?>


	<div class="section fill mb5">
		<?php echo $tarjouslaskenta; ?>
		<?php echo $tyonkuvaus; ?>
	</div>


<!--
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tarjous'); ?>
		<?php echo $form->textArea($model,'tarjous',array('rows'=>6, 'cols'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tarjous'); ?>
	</div>
-->
<br>
	<div class="section">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary myBgColors')); ?>
	</div>

<?php $this->endWidget(); ?>



<?php if(!isset($model->id)) : ?>
<script type="text/javascript">
$(document).ready(function(){

 $('#CrmTarjoukset_asiakas_id').change(function(){
	window.location.href= "create?asiakas_id=" + $(this).val();
 });

 $('#CrmTarjoukset_yhteystiedot_id').change(function(){
	window.location.href= "create?yhteystiedot_id=" + $(this).val();
 });

 $('.tyokuvaus').change(function(){

    if ($("input[name='tyokuvaus']:checked").val()) {
	var thisFor = $(this).attr('for');
        $.ajax({
           url: 'get_tyonkuvaus_by_id?id=' + thisFor,
           //type: "POST",
           //data: { },
           success: function(data){
		//var data = JSON.parse(data);
		//console.log(data);
		$('#CrmTarjoukset_tyonkuvaus').val(data);
           }
        });
	
    }

 });

 $('.tarjouslaskenta').change(function(){

    if ($("input[name='tarjouslaskenta']:checked").val()) {
	var thisFor = $(this).attr('for');
        $.ajax({
           url: 'get_tarjouslaskenta_by_id?id=' + thisFor,
           //type: "POST",
           //data: { },
           success: function(data){
		//var data = JSON.parse(data);
		//console.log(data);
		$('#CrmTarjoukset_tarjouslaskenta').val(data);
           }
        });
    }

 });

 $('#crm-tarjoukset-form').on("submit", function(e){

    if ($("#CrmTarjoukset_tyonkuvaus").val() === '') {
       	alert('Valitse työnkuvaus.');
        return false;
    }

    if ($("#CrmTarjoukset_tarjouslaskenta").val() === '') {
       	alert('Valitse tarjouslaskenta.');
        return false;
    }

    $(this).submit();
    e.preventDefault();

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
		if(data['id'])
		{
			$('.kohdeHide').show();
			$('#CrmTarjoukset_kohteen_osoite').val(data['osoite']).attr('readonly', 'yes');
			$('#CrmTarjoukset_kohteen_postinumero').val(data['pnumero']).attr('readonly', 'yes');
			$('#CrmTarjoukset_kohteen_postitoimipaikka').val(data['kaupunki']).attr('readonly', 'yes');
		}
           }
        });

 });


});
</script>
<?php endif; ?>
