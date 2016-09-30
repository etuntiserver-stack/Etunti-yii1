<?php
$this->breadcrumbs=array(
	Yii::t('main', 'Raportit'),
);
?>

<legend>
<h1> <?php echo Yii::t('main', 'RAPORTIT'); ?> <i class="glyphicon glyphicon-th-list"></i></h1>
</legend>

<br>

<div class="row">
  <div class="alert alert-info col-sm-4 col-sm-offset-1">
    <legend><?php echo Yii::t('main', 'Luetut'); ?></legend>
    <form action="#" target="_blank" method=POST>
    <input type="hidden" name="method" value="luetut">

      <div class="row">
       <div class="col-sm-6">
        <input type="text" name="from" id="from" class="form-control datepicker" value="<?php echo Yii::app()->session['from']; ?>">
       </div><div class="col-sm-6">
        <input type="text" name="to" id="to" class="form-control datepicker" value="<?php echo Yii::app()->session['to']; ?>">
       </div>
      </div>

      <br>

      <div class="row">
       <div class="col-sm-6">
      	<?php
	$criteriaT = new CDbCriteria();
	$criteriaT->order = " tekijan_nimi ";

	   $list = CHtml::listData(Tyontekijat::model()->findAll($criteriaT), 'id', 'tekijan_nimi');
	   echo '<select name="tekija" id="nimi" class="form-control">';
	   echo '<option value="kaikki">'.Yii::t('main', 'Kaikki työntekijät').'</option>';

	   foreach($list as $key=>$val)
	   echo '<option value="'.$key.'//'.$val.'">'.$val.'</option>';

	   echo '</select>';
	?>
       </div>
       <div class="col-sm-6">
   	<?php
	   $model=new Mobile;
    	   $list = CHtml::listData(Mobile::model()->findAll(array('group' => 'kohde_kannasta','order' => 'kohde_kannasta')), 'kohde_kannasta', 'kohde_kannasta');
	
    	   echo '<select name="kohteet" class="form-control" id="kohteet" title="Kohteet">';
	       echo '<option value="kaikki">'.Yii::t('main', 'Kaikki kohteet').'</option>';
    	   foreach($list as $key=>$val){
       	 	echo '<option value="'.$key.'">'.$val.'</option>';
    	   }
    	   echo '</select>';
   	?>
       </div>
      </div>
      <br>

      <div class="row">
       <div class="col-sm-12">
    	   <select name="ilman[]" class="selectpicker form-control"  multiple="multiple"  title="Ei lasketa">
    	   <option value="Lounastauko"><?php echo Yii::t('main', 'Lounastauko'); ?></option>
    	   <option value="MATKA"><?php echo Yii::t('main', 'MATKA'); ?></option>
    	   </select>
       </div>
      </div>
      <br>


	   <input type="submit" class="btn btn-primary pull-right" value="<?php echo Yii::t('main', 'Luo raportti'); ?>">

    </form>
  </div>

  <div class="alert alert-info col-sm-4 col-sm-offset-1">
    <legend><?php echo Yii::t('main', 'Toteutuneet'); ?></legend>
    <form action="#" target="_blank" method=POST>
    <input type="hidden" name="method" value="toteutuneet">

      <div class="row">
       <div class="col-sm-6">
        <input type="text" name="from" id="from" class="form-control datepicker" value="<?php echo Yii::app()->session['from']; ?>">
       </div><div class="col-sm-6">
        <input type="text" name="to" id="to" class="form-control datepicker" value="<?php echo Yii::app()->session['to']; ?>">
       </div>
      </div>

      <br>

      <div class="row">
       <div class="col-sm-6">
      	<?php
	   $list = CHtml::listData(Tyontekijat::model()->findAll($criteriaT), 'id', 'tekijan_nimi');
	   echo '<select name="tekija" id="nimi" class="form-control">';
	   echo '<option value="kaikki">'.Yii::t('main', 'Kaikki työntekijät').'</option>';

	   foreach($list as $key=>$val)
	   echo '<option value="'.$key.'//'.$val.'">'.$val.'</option>';

	   echo '</select>';
	?>
       </div>
       <div class="col-sm-6">
   	<?php
	   $model=new Mobile;
    	   $list = CHtml::listData(Mobile::model()->findAll(array('group' => 'kohde_kannasta','order' => 'kohde_kannasta')), 'kohde_kannasta', 'kohde_kannasta');
	
    	   echo '<select name="kohteet" class="form-control" id="kohteet" title="Kohteet">';
	       echo '<option value="kaikki">'.Yii::t('main', 'Kaikki kohteet').'</option>';
    	   foreach($list as $key=>$val){
       	 	echo '<option value="'.$key.'">'.$val.'</option>';
    	   }
    	   echo '</select>';
   	?>
       </div>
      </div>
      <br>

      <div class="row">
       <div class="col-sm-12">
    	   <select name="ilman[]" class="selectpicker form-control"  multiple="multiple"  title="Ei lasketa">
    	   <option value="Lounastauko"><?php echo Yii::t('main', 'Lounastauko'); ?></option>
    	   <option value="MATKA"><?php echo Yii::t('main', 'MATKA'); ?></option>
    	   </select>
       </div>
      </div>
      <br>

	   <input type="submit" class="btn btn-primary pull-right" value="<?php echo Yii::t('main', 'Luo raportti'); ?>">

    </form>
  </div>
</div>
