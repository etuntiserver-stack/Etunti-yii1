<?php
     $tas = array();
   if(isset(Yii::app()->user->adminPaketti)) 
     $tas = explode(",",Yii::app()->user->adminPaketti);

	// <-- Järjestelmanvalvojan kuluvia ryhmiä
	$criteria = new CDbCriteria();
	$criteria->order = " value ";
	$criteria->condition = " 
		select_type='tyoryhma'	
		AND value2 LIKE '%\"".Yii::app()->user->adminID."\"%'
	";
	$valikot = Valikkoot::model()->findAll($criteria);
	
	$tyoryhmat = array();
	foreach($valikot as $data){
		$tyoryhmat[] = $data->value;
	}

	$ryhmaBody = '';
	if(count($tyoryhmat) > 0){
		$ryhmaBody .= '
		<div class="alert alert-default">
			<h3>'.Yii::app()->user->nimi.'</h3> '.Yii::t('main', 'Työntekijöiden työryhmät').': <b>'.implode(", ", $tyoryhmat).'</b>
		</div';
	}
	// Järjestelmanvalvojan kuluvia ryhmiä -->
?>


        <!-- begin: .tray-center -->
        <div class="tray-center">


        <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-th-list"></i> <?php echo Yii::t('main', 'Raportit'); ?>
	<?php if(!isset($_GET['kaikki_tyontekijat'])): ?>
	<?php echo ', '.Yii::t('main', 'aktiiviset työntekijät'); ?>
	<?php echo CHtml::link(Yii::t('main', 'Näytä kaikki'),'raportit?kaikki_tyontekijat', array('class'=>'btn btn-primary')); ?>
	<?php endif; ?>
	</h2>


	<?php echo $ryhmaBody; ?>	

          <div class="row">


	  <!-- Luetut -->
            <div class="admin-form col-sm-4">
              <div class="panel heading-border">
		<h3 class="p10"><?php echo Yii::t('main', 'Luetut'); ?></h3>
                <div class="panel-body bg-light">
                 <div class="row">


    <form action="#" id="luetutForm" target="_blank" method=POST>
    <input type="hidden" name="method" value="luetut">

      <div class="row">
       <div class="col-sm-6">

                          <label class="field prepend-icon">

   			    <input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?php if(isset(Yii::app()->session['from'])) echo date('d.m.Y', strtotime(Yii::app()->session['from'])); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>

       </div><div class="col-sm-6">

                          <label class="field prepend-icon">

   			    <input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?php if(isset(Yii::app()->session['to'])) echo date('d.m.Y', strtotime(Yii::app()->session['to'])); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>

       </div>
      </div>

      <br>

      <div class="row">
       <div class="col-sm-6">
      	<?php
	   $criteriaT = new CDbCriteria();

		// <-- Return order etu ja sukunimella
		$site = Yii::app()->createController('Site');
		$criteriaT = $site[0]->etuSukunimiCriteria($criteriaT);
		//     Return order etu ja sukunimella -->

	   if(!isset($_GET['kaikki_tyontekijat']))
	   $criteriaT->condition = " aktiivinen=1 ";
	   $tlist = Tyontekijat::model()->findAll($criteriaT);

	   echo '<select name="tekija[]" multiple class="mult">';
	   foreach($tlist as $val){
		if(count($tyoryhmat) > 0 and in_array($val->tyoryhma, $tyoryhmat))
			echo '<option value="'.$val->id.'" selected>'.$this->etuSukunimi($val->id).'</option>';
		else
			echo '<option value="'.$val->id.'">'.$this->etuSukunimi($val->id).'</option>';
	   }
	   echo '</select>';
	?>
       </div>
       <div class="col-sm-6">
   	<?php
    	   $k = CHtml::listData(Kohteet::model()->findAll(array('order' => 'osoite')), 'id', 'osoite');
    	   echo '<select name="kohteet" class="form-control" id="kohteet" title="Kohteet">';
	       echo '<option value="kaikki">'.Yii::t('main', 'Kaikki kohteet').'</option>';
    	   foreach($k as $key=>$val){
       	 	echo '<option value="'.$key.'">'.$val.'</option>';
    	   }
    	   echo '</select>';
   	?>
       </div>
      </div>
      <br>

      <div class="row">
       <div class="col-sm-6">
    	   <select name="ilman[]" class="eilasketa"  multiple="multiple"  title="<?php echo Yii::t('main', 'Ei lasketa'); ?>">
    	   <option value="Lounastauko"><?php echo Yii::t('main', 'Lounastauko'); ?></option>
    	   <option value="MATKA"><?php echo Yii::t('main', 'MATKA'); ?></option>
    	   </select>
       </div>

       <div class="col-sm-6">
       <?php
   	$kohdenTnimike = Valikkoot::model()->findAll(" select_type='siivous' ",array('order' => "select_type"));
    	echo '<select class="form-control" name="siivousPaaSivulla">';
        echo '<option value="">'.Yii::t('main', 'Kohteen työnimike').'</option>';
    	foreach($kohdenTnimike as $key=>$val){
    		echo '<option value="'.$val->value.'">'.$val->value.'</option>';
    	}
   	echo '</select>';
   	?>
       </div>

      </div>
      <br>
    </form>

      <div class="row">
        <div class="col-sm-6">
    	  <button class="btn btn-primary btn-block myBgColors submitExcelluetut"><?php echo Yii::t('main', 'Luo Excel raportti'); ?></button>
        </div><div class="col-sm-6">
    	  <button class="btn btn-primary btn-block myBgColors submitPDFluetut"><?php echo Yii::t('main', 'Luo PDF raportti'); ?></button>
        </div>
      </div>

                 </div>
                </div>
              </div>
            </div>


<script>
$(document).ready(function(){


  $('.submitPDFluetut').click(function(){

	var input = $("<input id='luoPDF'>")
               .attr({"type":"text","name":"luoPDF"}).val("true");
	$('#luetutForm').append(input).submit();
	$('#luoPDF').remove();

  });

  $('.submitExcelluetut').click(function(){

	var input = $("<input id='luoExcel'>")
               .attr({"type":"text","name":"luoExcel"}).val("true");
	$('#luetutForm').append(input).submit();
	$('#luoExcel').remove();

  });

});
</script>




	  <!-- Toteutuneet -->
            <div class="admin-form col-sm-4">
              <div class="panel heading-border">
		<h3 class="p10"><?php echo Yii::t('main', 'Toteutuneet'); ?></h3>
                <div class="panel-body bg-light">
                 <div class="row">


    <form action="#" target="_blank" method=POST>
    <input type="hidden" name="method" value="toteutuneet">

      <div class="row">
       <div class="col-sm-6">
                          <label class="field prepend-icon">

   			    <input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?php if(isset(Yii::app()->session['from'])) echo date('d.m.Y', strtotime(Yii::app()->session['from'])); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>

       </div><div class="col-sm-6">

                          <label class="field prepend-icon">

   			    <input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?php if(isset(Yii::app()->session['to'])) echo date('d.m.Y', strtotime(Yii::app()->session['to'])); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
       </div>
      </div>

      <br>

      <div class="row">
       <div class="col-sm-6">
      	<?php
	   echo '<select name="tekija[]" multiple class="mult">';
	   foreach($tlist as $val){
		if(count($tyoryhmat) > 0 and in_array($val->tyoryhma, $tyoryhmat))
			echo '<option value="'.$val->id.'" selected>'.$this->etuSukunimi($val->id).'</option>';
		else
			echo '<option value="'.$val->id.'">'.$this->etuSukunimi($val->id).'</option>';
	   }
	   echo '</select>';
	?>
       </div>
       <div class="col-sm-6">
   	<?php
    	   echo '<select name="kohteet" class="form-control" id="kohteet" title="Kohteet">';
	       echo '<option value="kaikki">'.Yii::t('main', 'Kaikki kohteet').'</option>';
    	   foreach($k as $key=>$val){
       	 	echo '<option value="'.$key.'">'.$val.'</option>';
    	   }
    	   echo '</select>';
   	?>
       </div>
      </div>
      <br>

      <div class="row">
       <div class="col-sm-6">
    	   <select name="ilman[]" class="eilasketa"  multiple="multiple"  title="<?php echo Yii::t('main', 'Ei lasketa'); ?>">
    	   <option value="Lounastauko"><?php echo Yii::t('main', 'Lounastauko'); ?></option>
    	   <option value="MATKA"><?php echo Yii::t('main', 'MATKA'); ?></option>
    	   </select>
       </div>

       <div class="col-sm-6">
       <?php
    	echo '<select class="form-control" name="siivousPaaSivulla">';
        echo '<option value="">'.Yii::t('main', 'Kohteen työnimike').'</option>';
    	foreach($kohdenTnimike as $key=>$val){
    		echo '<option value="'.$val->value.'">'.$val->value.'</option>';
    	}
   	echo '</select>';

   	?>
       </div>

      </div>
      <br>

	   <input type="submit" class="btn btn-primary pull-right myBgColors" value="<?php echo Yii::t('main', 'Luo raportti'); ?>">

    </form>


                 </div>
                </div>
              </div>
            </div>



<?php if(in_array('2',$tas)) : ?>

	  <!-- Toteutuneet ja suunnittelut ero -->
            <div class="admin-form col-sm-4">
              <div class="panel heading-border">
		<h3 class="p10"><?php echo Yii::t('main', 'Toteutuneen ja suunnitellun työn erot'); ?></h3>

                <div class="panel-body bg-light">
                 <div class="row">


    <form action="#" target="_blank" method=POST>
    <input type="hidden" name="method" value="LuetutToteutuneetEro">

      <div class="row">
       <div class="col-sm-6">
                          <label class="field prepend-icon">

   			    <input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?php if(isset(Yii::app()->session['from'])) echo date('d.m.Y', strtotime(Yii::app()->session['from'])); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>


       </div><div class="col-sm-6">

                          <label class="field prepend-icon">

   			    <input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?php if(isset(Yii::app()->session['to'])) echo date('d.m.Y', strtotime(Yii::app()->session['to'])); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
       </div>
      </div>

      <br>

      <div class="row">
       <div class="col-sm-6">
      	<?php
	   echo '<select name="tekija[]" multiple class="mult">';
	   foreach($tlist as $val){
		if(count($tyoryhmat) > 0 and in_array($val->tyoryhma, $tyoryhmat))
			echo '<option value="'.$val->id.'" selected>'.$this->etuSukunimi($val->id).'</option>';
		else
			echo '<option value="'.$val->id.'">'.$this->etuSukunimi($val->id).'</option>';
	   }
	   echo '</select>';
	?>
       <br>
       </div>
       <div class="col-sm-6">
   	<?php
    	   echo '<select name="kohteet" class="form-control" id="kohteet" title="Kohteet">';
	       echo '<option value="kaikki">'.Yii::t('main', 'Kaikki kohteet').'</option>';
    	   foreach($k as $key=>$val){
       	 	echo '<option value="'.$key.'">'.$val.'</option>';
    	   }
    	   echo '</select>';
   	?>
       <br>
       </div>
       <div class="col-sm-6">
	<select name="is_kaikki" class="form-control">
	 <option value="erot"><?php echo Yii::t('main', 'Näytä erot'); ?></option>
	 <option value="kaikki"><?php echo Yii::t('main', 'Näytä kaikki'); ?></option>
	</select>
       </div>
       <div class="col-sm-6">
   	<?php
    	echo '<select class="form-control" name="siivousPaaSivulla">';
        echo '<option value="">'.Yii::t('main', 'Kohteen työnimike').'</option>';
    	foreach($kohdenTnimike as $key=>$val){
    		echo '<option value="'.$val->value.'">'.$val->value.'</option>';
    	}
   	echo '</select>';
   	?>
       </div>
      </div>
      <br>


	   <input type="submit" class="btn btn-primary pull-right myBgColors" value="<?php echo Yii::t('main', 'Luo raportti'); ?>">

    </form>


                 </div>
                </div>
              </div>
            </div>






	  <!-- Lomat ja poissaolot -->
            <div class="admin-form col-sm-4">
              <div class="panel heading-border">
		<h3 class="p10"><?php echo Yii::t('main', 'Lomat ja poissaolot'); ?></h3>

                <div class="panel-body bg-light">
                 <div class="row">


    <form action="#" target="_blank" method=POST>
    <input type="hidden" name="method" value="lomatJaPoissaolot">

      <div class="row">
       <div class="col-sm-6">
                          <label class="field prepend-icon">

   			    <input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?php if(isset(Yii::app()->session['from'])) echo date('d.m.Y', strtotime(Yii::app()->session['from'])); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>


       </div><div class="col-sm-6">

                          <label class="field prepend-icon">

   			    <input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?php if(isset(Yii::app()->session['to'])) echo date('d.m.Y', strtotime(Yii::app()->session['to'])); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
       </div>
      </div>

      <br>

      <div class="row">
       <div class="col-sm-6">
      	<?php
	   echo '<select name="tekija" class="form-control">';
	   echo '<option value="kaikki">'.Yii::t('main', 'Kaikki työntekijät').'</option>';
	   foreach($tlist as $val){
			echo '<option value="'.$val->id.'">'.$this->etuSukunimi($val->id).'</option>';
	   }
	   echo '</select>';
	?>
       <br>
       </div>
       <div class="col-sm-6">
       <?php
   	$lomat = Valikkoot::model()->findAll(" select_type='vuosilomat' ",array('order' => "select_type"));
    	echo '<select class="form-control selectpicker" multiple name="status[]" title="Lomat">';
        echo '<option value=""></option>';
    	foreach($lomat as $key=>$val){
		$expl = explode("/",$val->value);
		if(isset($expl[0]) and isset($expl[1]))
    		echo '<option value="'.$expl[0].'">'.$expl[1].'</option>';
    	}
   	echo '</select>';
   	?>
       </div>
      </div>
      <br>


	   <input type="submit" class="btn btn-primary pull-right myBgColors" value="<?php echo Yii::t('main', 'Luo raportti'); ?>">

    </form>


                 </div>
                </div>
              </div>
            </div>

<?php endif; ?>



	  </div><!--row-->



        <!-- loppu: .tray-center -->
        </div>






<script>
$(document).ready(function(){

$('.mult').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Työntekijät"); ?>',
	selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
	allSelectedText: '<?php echo Yii::t("main", "Kaikki"); ?>',
	nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
	numberDisplayed: 0,
	buttonWidth: '100%',
});

$('.eilasketa').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Ei lasketa"); ?>',
	selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
	allSelectedText: '<?php echo Yii::t("main", "Kaikki"); ?>',
	nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
	numberDisplayed: 0,
	buttonWidth: '100%',
});



});
</script>





