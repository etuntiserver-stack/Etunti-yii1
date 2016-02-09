<?php

Yii::app()->clientScript->registerScript('avoimet', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#lasku-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");

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
<?php endif; ?>





<?php if(!isset($_POST['tulosta'])) : ?>
        <!-- begin: .tray-center -->
        <div class="tray-center">


              <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-barcode"></i> <?php echo Yii::t('main', 'Avoimet laskut ').$palvelu; ?></h2>



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

   			    <input type="text" name="from" id="from" class="gui-input datepicker" value="<?php if(isset($_POST['from'])) echo $_POST['from']; ?>">
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
<?php endif; ?>





<table class="table table-bordered table-striped small">
 <tr>
  <th><?php echo Yii::t('main','Laskunro'); ?></th>
  <th><?php echo Yii::t('main','Status'); ?></th>
  <th><?php echo Yii::t('main','Paydate'); ?></th>
  <th><?php echo Yii::t('main','Amount'); ?></th>
  <th><?php echo Yii::t('main','Tapahtuma pvm'); ?></th>
  <th><?php echo Yii::t('main','Yhteensä'); ?></th>
  <th><?php echo Yii::t('main','Asiakas'); ?></th>
 </tr>
 <?php 
 $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_avoimet',
)); 
 ?>
</table>
