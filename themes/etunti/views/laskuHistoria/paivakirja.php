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
<?php echo file_get_contents('css/raportit_table2.css'); ?>
</style>

<div style="text-align: center">
<h1><?php echo Yii::t('main','Laskupäiväkirja'); ?></h1>
<br>
<?php echo $yritys.date("d.m.Y",strtotime($from)).' - '.date("d.m.Y",strtotime($to)); ?>
</div>
<hr>
<?php endif; ?>

<?php if(!isset($_POST['tulosta'])) : ?>


        <!-- begin: .tray-center -->
        <div class="tray-center">

   <!-- tulostus -->
   <div class="pull-right">
     <form action="#" target="_blank" method="POST">
      <input type="hidden" name="from" value="<?php echo $from; ?>">
      <input type="hidden" name="to" value="<?php echo $to; ?>">
      <input type="submit" name="tulosta" class="btn btn-primary myBgColors btn-sm" value="PDF">
     </form>
   </div>
   <!-- tulostus -->


              <h2 class="myBgColors p10"> <i class="fa fa-barcode"></i> <?php echo Yii::t('main', 'Laskupäiväkirja'); ?></h2>



   	    <form id="mobForm" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="from" class="gui-input datepickerFI" value="<?php echo date('d.m.Y', strtotime($from)); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="to" class="gui-input datepickerFI" value="<?php echo date('d.m.Y', strtotime($to)); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="Hae">
		      </div>

                    </div>



                </div>
              </div>
            </div>

	    </form>


        <!-- loppu: .tray-center -->
        </div>


<br>
<?php endif; ?>

  <div class="panel heading-border">
   <div class="panel-body tb">

<table class="table table-bordered table-striped">
 <tr>

<?php if(!isset($_POST['tulosta'])) : ?>
 <thead class="myBgColors">
<?php endif; ?>

  <th><?php echo Yii::t('main','Asiakas nro'); ?></th>
  <th><?php echo Yii::t('main','Asiakas'); ?></th>
  <th><?php echo Yii::t('main','Laskunro'); ?></th>
  <th><?php echo Yii::t('main','Laskupvm'); ?></th>
  <th><?php echo Yii::t('main','Veroton'); ?></th>
  <th><?php echo Yii::t('main','ALV'); ?></th>
  <th><?php echo Yii::t('main','Yhteensä'); ?></th>

<?php if(!isset($_POST['tulosta'])) : ?>
 </thead>
<?php endif; ?>

 </tr>
 <?php 
	$saldo = 0;
	$veroton = 0;
	$alv = 0;
 foreach($model as $data)
 {
	$veroton += $data->yhteensa_total_veroton;
	$alv += $data->yhteensa_total_verot;
	$saldo += $data->yhteensa_total;
	$this->renderPartial('_paivakirja',array('data'=>$data));
 }
 ?>
 <tr>
  <th></th>
  <th></th>
  <th></th>
  <th><?php echo Yii::t('main','Yhteensä'); ?></th>
  <th><?=number_format($veroton, 2, ',', ' ')?></th>
  <th><?=number_format($alv, 2, ',', ' ')?></th>
  <th><?php echo number_format($saldo, 2, ',', ' '); ?></th>
 </tr>

</table>

   </div>
  </div>
