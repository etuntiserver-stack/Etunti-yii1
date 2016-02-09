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
<h1><?php echo Yii::t('main','Maksupäiväkirja'); ?></h1>
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
<h1><?php echo Yii::t('main','Maksupäiväkirja'); ?></h1>
</legend>

<br>

<div class="row">
 <div class="col-md-12">

  <form action="#" class="form-inline" method="POST">
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
  <th><?php echo Yii::t('main','Yhteensä'); ?></th>
  <th><?php echo Yii::t('main','Asiakas'); ?></th>
 </tr>
 <?php 
 foreach($model as $data)
 {
	$this->renderPartial('_maksu_paivakirja',array('data'=>$data));
 }
 ?>
</table>

