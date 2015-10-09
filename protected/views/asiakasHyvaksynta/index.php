<?php
/* @var $this AsiakasHyvaksyntaController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Asiakas Hyvaksyntas',
);

$this->menu=array(
	array('label'=>'Create AsiakasHyvaksynta', 'url'=>array('create')),
	array('label'=>'Manage AsiakasHyvaksynta', 'url'=>array('admin')),
);
?>

<legend>
<h1><?php echo Yii::t('main','HYVÄKSYTTY TUNNIT'); ?> <i class="glyphicon glyphicon-ok"></i></h1>
</legend>



<form action="#" id="hyvaksytunnit" class="form-inline" method="POST">
  <input type="hidden" name="hyvaksytunnit">

    	<?php 
       		$criteria = new CDbCriteria();
		$criteria->order = " yhteyshenkilo ";

        	$a = Asiakkaat::model()->findAll($criteria);
		echo '<select name="asiakas" class="form-control input-sm">';

	        echo '<option value="kaikki">'.Yii::t('main','Asiakas').'</option>';

		foreach($a as $aa)
		{
		  if(!empty($aa->yrityksen_nimi))
		    echo '<option value="'.$aa->id.'">'.$aa->yrityksen_nimi.'</option>';
		  elseif(empty($aa->yhteyshenkilo) and empty($aa->yrityksen_nimi))
		    echo '<option value="'.$aa->id.'">nimet puutuu '.$aa->id.'</option>';
		  else
		    echo '<option value="'.$aa->id.'">'.$aa->yhteyshenkilo.' ID:'.$aa->id.'</option>';
		}
		echo '</select>';
	?>

   <select name="status" class="form-control form-group input-sm">
   <option value="kaikki"><?php echo Yii::t('main','Tilanne'); ?></option>
   <option value="1"><?php echo Yii::t('main','Lähetetty'); ?></option>
   <option value="3"><?php echo Yii::t('main','Hyväksyty'); ?></option>
   <option value="2"><?php echo Yii::t('main','Hylätty'); ?></option>
   </select> 

   <b class="glyphicon glyphicon-calendar"></b>
   <input type="text" name="from" id="from" class="form-control form-group input-sm datepicker" value="<?php echo Yii::app()->session['from']; ?>">
   <b class="glyphicon glyphicon-calendar"></b>
   <input type="text" name="to" id="to" class="form-control form-group input-sm datepicker" value="<?php echo Yii::app()->session['to']; ?>">

   <input type="submit" class="btn btn-sm btn-primary" value="<?php echo Yii::t('main', 'haku'); ?>">

</form>



<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
