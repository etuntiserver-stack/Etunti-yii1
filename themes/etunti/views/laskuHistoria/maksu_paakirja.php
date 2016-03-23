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
<h1><?php echo Yii::t('main','Maksupääkirja'); ?></h1>
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


              <h2 class="myBgColors p10"> <i class="fa fa-barcode"></i> <?php echo Yii::t('main', 'Maksupääkirja'); ?></h2>



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

   			    <input type="text" name="from" class="gui-input datepicker" value="<?php echo $from; ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="to" class="gui-input datepicker" value="<?php echo $to; ?>">
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
   <div class="panel-body">

<table class="table table-bordered table-striped">
 <tr>

<?php if(!isset($_POST['tulosta'])) : ?>
 <thead class="myBgColors">
<?php endif; ?>

  <th><?php echo Yii::t('main','Asiakas nro'); ?></th>
  <th><?php echo Yii::t('main','Asiakas'); ?></th>
  <th><?php echo Yii::t('main','Laskunro'); ?></th>
  <th><?php echo Yii::t('main','Maksu pvm'); ?></th>
  <th><?php echo Yii::t('main','Kredit'); ?></th>
  <th><?php echo Yii::t('main','Debit'); ?></th>

<?php if(!isset($_POST['tulosta'])) : ?>
 </thead>
<?php endif; ?>

 </tr>
 <?php 
	$saldo = 0;
	$saldoPlus = 0;
 foreach($model as $data)
 {
	$saldo -= $data->yhteensa_total;
	$saldoPlus += $data->yhteensa_total;
	$this->renderPartial('_maksu_paakirja',array('data'=>$data, 'saldo'=>$saldo));

 }
 ?>

  <th></th>
  <th></th>
  <th></th>
  <th><?php echo Yii::t('main','Yhteensä'); ?></th>
  <th><?php echo number_format($saldoPlus, 2, ',', ' '); ?></th>
  <th></th>

</table>

   </div>
  </div>

