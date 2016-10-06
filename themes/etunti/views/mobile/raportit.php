<?php
$this->breadcrumbs=array(
	Yii::t('main', 'Raportit'),
);
?>


        <!-- begin: .tray-center -->
        <div class="tray-center">


              <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-th-list"></i> <?php echo Yii::t('main', 'RAPORTIT'); ?> 
	      </h2>
	      <br><br><br><br>
          <div class="row">


	  <!-- Luetut -->
            <div class="admin-form col-sm-4">
              <div class="panel heading-border">
		<h3 class="p10"><?php echo Yii::t('main', 'Luetut'); ?></h3>
                <div class="panel-body bg-light">
                 <div class="row">


    <form action="#" target="_blank" method=POST>
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
	   $criteriaT->order = " tekijan_nimi ";

	   $tlist = CHtml::listData(Tyontekijat::model()->findAll($criteriaT), 'id', 'tekijan_nimi');
	   echo '<select name="tekija" id="nimi" class="form-control">';
	   echo '<option value="kaikki">'.Yii::t('main', 'Kaikki työntekijät').'</option>';

	   foreach($tlist as $key=>$val)
	   echo '<option value="'.$key.'//'.$val.'">'.$val.'</option>';

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
       <div class="col-sm-12">
    	   <select name="ilman[]" class="selectpicker form-control"  multiple="multiple"  title="<?php echo Yii::t('main', 'Ei lasketa'); ?>">
    	   <option value="Lounastauko"><?php echo Yii::t('main', 'Lounastauko'); ?></option>
    	   <option value="MATKA"><?php echo Yii::t('main', 'MATKA'); ?></option>
    	   </select>
       </div>
      </div>
      <br>


	   <input type="submit" class="btn btn-primary pull-right myBgColors" value="<?php echo Yii::t('main', 'Luo raportti'); ?>">

    </form>

                 </div>
                </div>
              </div>
            </div>


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
	   echo '<select name="tekija" id="nimi" class="form-control">';
	   echo '<option value="kaikki">'.Yii::t('main', 'Kaikki työntekijät').'</option>';

	   foreach($tlist as $key=>$val)
	   echo '<option value="'.$key.'//'.$val.'">'.$val.'</option>';

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
       <div class="col-sm-12">
    	   <select name="ilman[]" class="selectpicker form-control"  multiple="multiple"  title="<?php echo Yii::t('main', 'Ei lasketa'); ?>">
    	   <option value="Lounastauko"><?php echo Yii::t('main', 'Lounastauko'); ?></option>
    	   <option value="MATKA"><?php echo Yii::t('main', 'MATKA'); ?></option>
    	   </select>
       </div>
      </div>
      <br>

	   <input type="submit" class="btn btn-primary pull-right myBgColors" value="<?php echo Yii::t('main', 'Luo raportti'); ?>">

    </form>


                 </div>
                </div>
              </div>
            </div>




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
	   echo '<select name="tekija" id="nimi" class="form-control">';
	   echo '<option value="kaikki">'.Yii::t('main', 'Kaikki työntekijät').'</option>';

	   foreach($tlist as $key=>$val)
	   echo '<option value="'.$key.'//'.$val.'">'.$val.'</option>';

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
      </div>
      <br>


	   <input type="submit" class="btn btn-primary pull-right myBgColors" value="<?php echo Yii::t('main', 'Luo raportti'); ?>">

    </form>


                 </div>
                </div>
              </div>
            </div>



	  </div><!--row-->



        <!-- loppu: .tray-center -->
        </div>













