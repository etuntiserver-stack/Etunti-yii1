<?php


if(isset($model))
{
  foreach($model as $tvVal){
   if($tvVal->id){
   echo  $tvVal->aloitan."//".$tvVal->loppui."//".$tvVal->kohde_kannasta;
   }
  }
exit;
}

$this->breadcrumbs=array(
	Yii::t('main', 'Raportit'),
);
?>

<h1><?php echo Yii::t('main', 'Raportit'); ?></h1>

<br>

<div class="row">
  <div class="alert alert-info col-sm-4 col-sm-offset-1">
    <legend><?php echo Yii::t('main', 'Luetut'); ?></legend>
    <form action="#" target="_blank" method=POST>
    <input type="hidden" name="method" value="luetut">

      <div class="row">
       <div class="col-sm-6">
        <input type="date" name="from" id="from" class="form-control" value="<?php echo Yii::app()->session['from']; ?>">
       </div><div class="col-sm-6">
        <input type="date" name="to" id="to" class="form-control" value="<?php echo Yii::app()->session['to']; ?>">
       </div>
      </div>

      <br>

      <div class="row">
       <div class="col-sm-6">
      	<?php
	   $list = CHtml::listData(Mobile::model()->findAll(array('order' => 'tekijan_nimi','group'=>'tekijan_nimi')), 'tid', 'tekijan_nimi');
	   echo '<select name="tekija" id="nimi" class="form-control">';
       	   echo '<option>'.Yii::t('main', 'Työntekijä').'</option>';	
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
       	   echo '<option>'.Yii::t('main', 'Kohde').'</option>';	
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


	   <input type="submit" class="btn btn-primary pull-right">

    </form>
  </div>

  <div class="alert alert-info col-sm-4 col-sm-offset-1">
    <legend><?php echo Yii::t('main', 'Toteutuneet'); ?></legend>
    <form action="#" target="_blank" method=POST>
    <input type="hidden" name="method" value="toteutuneet">

      <div class="row">
       <div class="col-sm-6">
        <input type="date" name="from" id="from" class="form-control" value="<?php echo Yii::app()->session['from']; ?>">
       </div><div class="col-sm-6">
        <input type="date" name="to" id="to" class="form-control" value="<?php echo Yii::app()->session['to']; ?>">
       </div>
      </div>

      <br>

      <div class="row">
       <div class="col-sm-6">
      	<?php
	   $list = CHtml::listData(Mobile::model()->findAll(array('order' => 'tekijan_nimi','group'=>'tekijan_nimi')), 'tid', 'tekijan_nimi');
	   echo '<select name="tid" id="nimi" class="form-control">';
       	   echo '<option>'.Yii::t('main', 'Työntekijä').'</option>';
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
       	   echo '<option>'.Yii::t('main', 'Kohde').'</option>';	
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

	   <input type="submit" class="btn btn-primary pull-right">

    </form>
  </div>
</div>
