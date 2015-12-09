<?php
/* @var $this LaskuHistoriaController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Lasku Historias',
);
/*
$this->menu=array(
	array('label'=>'Create LaskuHistoria', 'url'=>array('create')),
	array('label'=>'Manage LaskuHistoria', 'url'=>array('admin')),
);
*/
?>
<?php if(isset($_POST['tulosta'])) : ?>
<?php
 $yritys = '';
 $f = FirmanTiedot::model()->findbypk(1);
 if(!empty($f->tyonantaja))
 $yritys = $f->tyonantaja.', ';
?>
<style>
table{
	width: 200px;
	font-size: 80%;
}
th{
	width: 100%;	
	padding:3px 7px;
}
</style>
<div style="text-align: center">
<h1><?php echo Yii::t('main','Reskontraluettelo'); ?></h1>
<br>
<?php echo $yritys.date("d.m.Y",strtotime(Yii::app()->session['from'])).' - '.date("d.m.Y",strtotime(Yii::app()->session['to'])); ?>
</div>
<hr>
<?php endif; ?>

<?php if(!isset($_POST['tulosta'])) : ?>
<legend>
   <!-- tulostus -->
   <div class="pull-right">
     <form action="#" target="_blank" method="POST">
      <input type="submit" name="tulosta" class="btn btn-success btn-sm" value="PDF">
     </form>
   </div>
   <!-- tulostus -->
<h1><?php echo Yii::t('main','Reskontraluettelo'); ?></h1>
</legend>

<br>

<div class="row">
 <div class="col-md-12">

  <form action="#" class="form-inline" method="POST">
  <input type="hidden" name="formResko">

    		<?php 
       		$criteria = new CDbCriteria();
		//$criteria->select = " COALESCE(NULLIF(yhteyshenkilo,yhteyshenkilo),'gg') AS yht ";
		$criteria->order = " yhteyshenkilo ";

        	$a = Asiakkaat::model()->findAll($criteria);
		echo '<select name="asiakasLaskulle" class="form-control input-sm">';

		if(isset(Yii::app()->session['asiakasLaskulle']) and !empty(Yii::app()->session['asiakasLaskulle']))
		{
        	  $aon = Asiakkaat::model()->findbypk(Yii::app()->session['asiakasLaskulle']);
		  if(!empty($aon->yrityksen_nimi))
		    echo '<option value="'.$aon->id.'">'.$aon->yrityksen_nimi.'</option>';
		  elseif(empty($aon->yhteyshenkilo) and empty($aon->yrityksen_nimi))
		    echo '<option value="'.$aon->id.'">nimet puutuu '.$aon->id.'</option>';
		  else
		    echo '<option value="'.$aon->id.'">'.$aon->yhteyshenkilo.' ID:'.$aon->id.'</option>';
		} else {
	        echo '<option></option>';
		}

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


   <b class="glyphicon glyphicon-calendar"></b>
   <input type="text" name="from" id="from" class="form-control form-group input-sm datepicker" value="<?php echo Yii::app()->session['from']; ?>">
   <b class="glyphicon glyphicon-calendar"></b>
   <input type="text" name="to" id="to" class="form-control form-group input-sm datepicker" value="<?php echo Yii::app()->session['to']; ?>">

   <div class="form-group input-group-btn">
      <input type="submit" class="btn btn-primary btn-sm" value="<?php echo Yii::t('main', 'Hae'); ?>">
   </div>
   </form>

 </div>
</div>
<br>
<?php endif; ?>


<table class="table table-bordered table-striped">
 <tr>
  <th><?php echo Yii::t('main','Laskunro'); ?></th>
  <th><?php echo Yii::t('main','Laskupvm'); ?></th>
  <th><?php echo Yii::t('main','Suorituksen numero'); ?></th>
  <th><?php echo Yii::t('main','Suorituksen summa'); ?></th>
  <th><?php echo Yii::t('main','Avoina'); ?></th>
 </tr>
 <?php 
	$saldo = '';
 foreach($model as $data)
 {
	$saldo += $data->yhteensa_total;
	$this->renderPartial('_reskontraluettelo',array('data'=>$data, 'saldo'=>$saldo));

 }
 ?>
</table>

