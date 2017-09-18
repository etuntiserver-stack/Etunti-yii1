<?php
/* @var $this KohteetController */
/* @var $model Kohteet */
/* @var $form CActiveForm */

     $tas = array();
   if(isset(Yii::app()->user->adminPaketti)) 
     $tas = explode(",",Yii::app()->user->adminPaketti);

if(isset($model->id))
$model->hinta = str_replace(",",".",$model->hinta);
?>

<div class="row">


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'kohteet-form',
	'enableAjaxValidation'=>false,
)); ?>



	<?php echo $form->errorSummary($model); ?>

  <div class="col-sm-3">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'asiakas_id'); ?>
    		<?php 
       		$criteria = new CDbCriteria();
		//$criteria->select = " COALESCE(NULLIF(yhteyshenkilo,yhteyshenkilo),'gg') AS yht ";
        	$criteria->condition = " id='$asiakas->id' ";

        	$a = Asiakkaat::model()->findAll($criteria);
		echo '<select name="Kohteet[asiakas_id]" class="form-control" id="Kohteet_asiakas_id">';

		foreach($a as $aa)
		{
		  if(!empty($aa->yrityksen_nimi))
		    echo '<option value="'.$aa->id.'">'.$aa->yrityksen_nimi.'</option>';
		  elseif(empty($aa->yhteyshenkilo) and empty($aa->yrityksen_nimi))
		    echo '<option value="'.$aa->id.'">nimet puutuu '.$aa->id.'</option>';
		  else
		    echo '<option value="'.$aa->id.'">'.$aa->yhteyshenkilo.'</option>';
		}
		echo '</select>';
		?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'etu_suku_nimet'); ?>
		<?php echo $form->textField($model,'etu_suku_nimet',array('value'=>$asiakas->yhteyshenkilo,'size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'etu_suku_nimet'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('value'=>$asiakas->osoite, 'size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'osoite'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'pnumero'); ?>
		<?php echo $form->textField($model,'pnumero',array('value'=>$asiakas->postinumero, 'size'=>7,'maxlength'=>7,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'pnumero'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kaupunki'); ?>
		<?php echo $form->textField($model,'kaupunki',array('value'=>$asiakas->kaupunki, 'size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'kaupunki'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('value'=>$asiakas->sahkoposti,'size'=>60,'maxlength'=>72,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'puh_nro'); ?>
		<?php echo $form->textField($model,'puh_nro',array('value'=>$asiakas->puhelin,'size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'puh_nro'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'toimipaikka'); ?>
		<?php echo $form->textField($model,'toimipaikka',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'toimipaikka'); ?>
	</div>


	<?php if(in_array('3',$tas)) : ?>
	<legend><?php echo Yii::t('main','Laskutus'); ?></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'hinta_tyyppi'); ?>
		<?php
		$list = array(0=>'tunti',1=>'kk',3=>'kpl');
        	echo $form->dropDownList($model, 'hinta_tyyppi', $list,
		array('empty'=>'Valitse tyyppi','class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'hinta_tyyppi'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'hinta'); ?>
		<?php echo $form->numberField($model,'hinta',array('size'=>10,'maxlength'=>100,'class'=>'form-control', 'step'=>'0.01')); ?>
		<?php echo $form->error($model,'hinta'); ?>
	</div>
	<?php endif; ?>

  </div><div class="col-sm-3">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tag_id'); ?>
		<?php echo $form->textField($model,'tag_id',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tag_id'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'gps_sijainti'); ?>
		<?php echo $form->textField($model,'gps_sijainti',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'gps_sijainti'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'siivous'); ?>

		<?php
      		$l = Valikkoot::model()->findAll(" select_type='siivous' ",array('order' => "select_type"));
		foreach($l as $v)
		$list[$v->id."//".$v->value] = $v->value;

        	echo $form->dropDownList($model, 'siivous', $list,
		array('empty'=>'','class'=>'form-control'));
        	?>

		<?php echo $form->error($model,'siivous'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'aktiivinen'); ?>
		<?php
		$list = array(1=>'Kyllä',0=>'Ei');
        	echo $form->dropDownList($model, 'aktiivinen', $list,
		array('class'=>'form-control'));
        	?>
		<?php echo $form->error($model,'aktiivinen'); ?>
	</div>


	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'avain'); ?>
		<?php echo $form->textField($model,'avain',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'avain'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'avaimen_sijainti'); ?>

		<?php
		$list = array(
			1=>Yii::t('main', 'Asiakas'),
			2=>Yii::t('main', 'Toimisto'),
			3=>Yii::t('main', 'Työntekijä')
		);
        	echo $form->dropDownList($model, 'avaimen_sijainti', $list,
		array('empty'=>'','class'=>'form-control'));
        	?>

		<?php echo $form->error($model,'avaimen_sijainti'); ?>
	</div>

	<div class="section fill mb5 kenella_on_avain">
		<?php echo $form->labelEx($model,'kenella_on_avain'); ?>

		<?php
      		$l = Tyontekijat::model()->findAll(array('order' => "tekijan_nimi"));
		foreach($l as $v)
		$listt[$v->id."//".$this->etuSukunimi($v->id)] = $this->etuSukunimi($v->id);

        	echo $form->dropDownList($model, 'kenella_on_avain', $listt,
		array('empty'=>'','class'=>'form-control'));
        	?>

		<?php echo $form->error($model,'kenella_on_avain'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'ryhma'); ?>
	   <div class="form-inline">
		<?php
		$list = array();
      		$l = Valikkoot::model()->findAll(" select_type='asiakas_ryhma' ",array('order' => "select_type"));
		foreach($l as $v)
		$list[$v->id] = $v->value;

		if(count($list) > 0)
		{
        	echo $form->dropDownList($model, 'ryhma', $list,
		array('empty'=>'Valitse toimialue','class'=>'form-control'));
		} else {
		echo 'Luo Valikko tietokannassa "Select Type = asiakas_ryhma"';
		}		
        	?>
		<span class="btn btn-primary myBgColors muokaValiko" for="asiakas_ryhma"><i class="fa fa-pencil-square-o"></i></span>
	   </div>

		<?php echo $form->error($model,'ryhma'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tarvittavien_tyontekijoiden_maara'); ?>
		<?php echo $form->numberField($model,'tarvittavien_tyontekijoiden_maara',array('maxlength'=>3,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tarvittavien_tyontekijoiden_maara'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'arvioitu_kesto'); ?>
		<?php echo $form->textField($model,'arvioitu_kesto',array('maxlength'=>5,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'arvioitu_kesto'); ?>
	</div>

	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.mask.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
	  $('#Kohteet_arvioitu_kesto').mask('00:00',{
	        placeholder: "__:__"
	  });
	});
	</script>

  </div><div class="col-sm-6">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'aikataulu'); ?>
		<?php echo $form->textArea($model,'aikataulu',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'aikataulu'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'hinnoittelu'); ?>
		<?php echo $form->textArea($model,'hinnoittelu',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>

		<?php echo $form->error($model,'hinnoittelu'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'muut'); ?>
		<?php echo $form->textArea($model,'muut',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'muut'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'toimenpiteet'); ?>
		<?php echo $form->textArea($model,'toimenpiteet',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'toimenpiteet'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tietoja'); ?>
		<?php echo $form->textArea($model,'tietoja',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tietoja'); ?>
	</div>

  </div>
</div><!-- form -->
<br>

	<div class="section">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary myBgColors')); ?>
	</div>

<?php $this->endWidget(); ?>



	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>


<script type="text/javascript">
$(document).ready(function(){

/* valikot */
$(".muokaValiko").click(function() {
    var thisFor = $(this).attr("for");
        $.ajax({
           url: location.protocol + "//" + location.host + "/index.php/site/valiko",
	   type:'POST',
	   data: { "select_type" : thisFor },
           success: function(data){
		//console.log(data);
		$('#showres').modal().html(JSON.parse(data));
           }
        });
});
/* valikot */



	var avainOn = $( "#Kohteet_avaimen_sijainti option:selected" ).val();
	if(avainOn !== '3')
	$('.kenella_on_avain').hide();


$("#Kohteet_avaimen_sijainti").change(function() {
    var thisVal = $(this).val();
	if(thisVal !== '3')
	{
		$('.kenella_on_avain').hide('slow');
		$('#Kohteet_kenella_on_avain').val('');
	} else {
		$('.kenella_on_avain').show('slow');
	}
});


});
</script>


