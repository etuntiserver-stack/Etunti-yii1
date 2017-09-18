<?php
/* @var $this KupongitController */
/* @var $model Kupongit */
/* @var $form CActiveForm */

(isset($model->id))? $model->voimassa = date("d.m.Y", strtotime($model->voimassa)):$model->voimassa = date("d.m.Y", strtotime("+1 month"))
?>



<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'kupongit-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
)); ?>


	<?php echo $form->errorSummary($model); ?>

<div class="row">
  <div class="col-sm-3">


	<div class="section fill mb5">
		<?php echo $form->hiddenField($model,'asiakas_id',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>

			<!-- Autocomplete -->
			    <?php echo $form->labelEx($model,'asiakas_id'); ?>
			    <?php
	   			$site = Yii::app()->createController('Site');
				$mod = 'Asiakkaat';
				$placeholder = 'Asiakas';

				$postvalue = '';
		      		$la = Asiakkaat::model()->findByPk($model->asiakas_id);
				if(isset($la->id) and $la->tyyppi == 'yritys')
				$postvalue = $la->yrityksen_nimi; 
				elseif(isset($la->id) and $la->tyyppi == 'henkilo')
				$postvalue = $la->yhteyshenkilo; 

		 	        $site[0]->autocompleteFor($mod,array('yrityksen_nimi','yhteyshenkilo'), $placeholder, $postvalue);
			    ?>
			<!-- Autocomplete -->


		<script type="text/javascript">
		$(document).ready(function(){

		 $("#Tyonkuvaus_asiakas_id").change(function(){
			window.location.href= "create?asiakas_id=" + $(this).val();
		 });

		 $("#yrityksen_nimi").blur(function(){
		
			var thisVal = $("#yrityksen_nimi").val()
		        $.ajax({
		           url: location.protocol + "//" + location.host + "/index.php/site/getasiakasidbynimi?nimi="+ thisVal,
		           success: function(data){
				if(data !== '0'){
				  $("#Kupongit_asiakas_id").val(data);
				}
		           }
		        });
		
		 });

		});
		</script>
		<?php echo $form->error($model,'asiakas_id'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kupongin_id'); ?>
		<?php echo $form->textField($model,'kupongin_id',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'kupongin_id'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'voimassa'); ?>
		<?php echo $form->textField($model,'voimassa',array('class'=>'form-control datepickerFI')); ?>
		<?php echo $form->error($model,'voimassa'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'euro_maara'); ?>
		<?php echo $form->numberField($model,'euro_maara',array('class'=>'form-control', 'step'=>'any')); ?>
		<?php echo $form->error($model,'euro_maara'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'prosentti_maara'); ?>
		<?php echo $form->numberField($model,'prosentti_maara',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'prosentti_maara'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'maara_tyyppi'); ?>
		<?php
		$list = array('euro'=>Yii::t('main', 'Euro'),'prosentti'=>Yii::t('main', 'Prosentti'));
        	echo $form->dropDownList($model, 'maara_tyyppi', $list,
		array('class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'maara_tyyppi'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'jatkuva'); ?>
		<?php
		$list = array('0'=>Yii::t('main', 'Ei'),'1'=>Yii::t('main', 'Kyllä'));
        	echo $form->dropDownList($model, 'jatkuva', $list,
		array('class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'jatkuva'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php
		$list = array('0'=>Yii::t('main', 'Ei'),'1'=>Yii::t('main', 'Kyllä'));
        	echo $form->dropDownList($model, 'status', $list,
		array('class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'status'); ?>
	</div>
 </div>
</div>
	<div class="section">
				<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('main', 'Luo') : Yii::t('main', 'Tallenna'),array('class'=>'btn btn-primary myBgColors')); ?>
	</div>

<?php $this->endWidget(); ?>


