<?php

?>


        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-envelope"></i> <?php echo Yii::t('main', 'Lähetä kirje ETUNTI asiakkaalle'); ?> </h2>

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">



<div class="row form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'domainit-form',
	'enableAjaxValidation'=>false,
)); ?>

  <div class="col-sm-4">
	<div class="section fill mb5">
		<?php

      		$criteria = new CDbCriteria();
	        $criteria->order = " id DESC ";
	        $criteria->condition = " domain!='defdb' AND sahkoposti!=''";

        	  $list = CHtml::listData(Domainit::model()->findAll($criteria), 'sahkoposti', 'yritys');
        	  echo $form->dropDownList($model, 'sahkoposti', $list,
			array('class'=>'form-control','multiple'=>'yes'));

		?>
	</div>

	<div class="section fill mb5">
		<label><?php echo Yii::t('main', 'Viesti'); ?></label>
		<?php  echo $form->textarea($model,'viesti',array('class'=>'form-control', 'rows'=>6, 'cols'=>20)); ?>
	</div>

  </div>
</div>


<div class="row">
  <div class="col-sm-3">
	<div class="section fill mb5">

	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'LÄHETÄ' : 'LÄHETÄ',array('class'=>'btn btn-sm btn-primary myBgColors')); ?>
	</div>

	</div>
  </div>
</div>


<?php $this->endWidget(); ?>


                 </div>
                </div>
              </div>
            </div>


        <!-- loppu: .tray-center -->
        </div>




<script>
$(document).ready(function(){

/*
$(document).delegate("#tekijanToimialue","change",function(){

	var tekijanToimialue = $(this).val();


        $.ajax({
           url: 'toimialue',
           type: "POST",
           data: { "tekijanToimialue" : tekijanToimialue },
           success: function(data){
		var d = JSON.parse(data);
		console.log(d);

		$("#Viestinta_tekija").val(d);
		// Then refresh
		$("#Viestinta_tekija").multiselect("refresh");

           }
        });

});
*/


$('#Domainit_sahkoposti').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: 'Tyhjä',
	selectAllText: 'Valitse kaikki',
	allSelectedText: 'Kaikki',
	nSelectedText: 'valittu',
});

});
</script>
