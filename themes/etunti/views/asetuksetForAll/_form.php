<?php
/* @var $this AsetuksetForAllController */
/* @var $model AsetuksetForAll */
/* @var $form CActiveForm */
$ilmoitusKaikkille = Yii::app()->createController('ilmoitusKaikkille');
$vastaanottajat = [];
if(is_array(json_decode($model->app_ilmoitus_vastaanottajat, true))){
	$vastaanottajat = json_decode($model->app_ilmoitus_vastaanottajat, true);
}
if( isset($model->id) and !empty($model->app_ilmoitus_voimassa_asti)){
	$model->app_ilmoitus_voimassa_asti = date("d.m.Y H:i", strtotime($model->app_ilmoitus_voimassa_asti));
} else {
	$model->app_ilmoitus_voimassa_asti = date("d.m.Y 16:00");
}
?>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'asetukset-for-all-form',
	'enableAjaxValidation'=>false,
)); ?>


	<?php echo $form->errorSummary($model); ?>

<div class="row">
  <div class="col-sm-6">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'max_ilmaiset_tunnit'); ?>
		<?php echo $form->numberField($model,'max_ilmaiset_tunnit',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'max_ilmaiset_tunnit'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'session_aikamaara'); ?>
		<?php
		$list = array();
		for ($i = 0; $i <= 24; $i++) {
			$list[] = $i;
		}
		unset($list[0]);
        	echo $form->dropDownList($model, 'session_aikamaara', $list,
		array('class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'session_aikamaara'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'api_access_key'); ?>
		<?php echo $form->textField($model,'api_access_key',array('size'=>60,'maxlength'=>500,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'api_access_key'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'googlemaps_apikey'); ?>
		<?php echo $form->textField($model,'googlemaps_apikey',array('size'=>60,'maxlength'=>500,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'googlemaps_apikey'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'viralliset_pyhapaivat'); ?>
		<?php echo $form->textarea($model,'viralliset_pyhapaivat',array('rows'=>8,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'viralliset_pyhapaivat'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'erikoislauantai'); ?>
		<?php echo $form->textarea($model,'erikoislauantai',array('rows'=>8,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'erikoislauantai'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'app_info_sivu'); ?>
		<?php echo $form->textarea($model,'app_info_sivu',array('rows'=>10,'maxlength'=>50000,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'app_info_sivu'); ?>
	</div>

  </div><div class="col-sm-6">
	<legend>APP Ilmoitus kaikkille</legend>

	<div class="section fill mb5">
				<?php
		   		$domainit = $ilmoitusKaikkille[0]->domainitMulti( 
						'app_ilmoitus_vastaanottajat[]', // name
						'form-control', //class
						'domain', // id
						(count($vastaanottajat) > 0)?$vastaanottajat:'', //selected
						1 // aktiivinen
				);
				echo $domainit;
				?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'app_ilmoitus_voimassa_asti'); ?>
		<?php echo $form->textField($model,'app_ilmoitus_voimassa_asti', array('class'=>'form-control datetimepicker')); ?>
		<?php echo $form->error($model,'app_ilmoitus_voimassa_asti'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'app_ilmoitus_versio_eisamakun'); ?>
		<?php echo $form->textField($model,'app_ilmoitus_versio_eisamakun',array('rows'=>8,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'app_ilmoitus_versio_eisamakun'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'app_ilmoitus_kaikkille'); ?>
		<?php echo $form->textarea($model,'app_ilmoitus_kaikkille',array('rows'=>8,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'app_ilmoitus_kaikkille'); ?>
	</div>

  </div>
</div><!-- form -->


	<br>
	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Tallenna', array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

<script type="text/javascript">
$(document).ready(function(){

  $('#domain').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: 'Valitse domainia',
	selectAllText: 'Valitse kaikki',
	allSelectedText: 'Kaikki',
	nSelectedText: 'valittu',
	numberDisplayed: 0,
	buttonWidth: '100%',
        maxHeight: 300,
  });

});
</script>
