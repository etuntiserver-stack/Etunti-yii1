<?php
/* @var $this LaskuHistoriaController */
/* @var $dataProvider CActiveDataProvider */

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
	width: 10%;	
	padding:3px 7px;
}
</style>
<div style="text-align: center">
<h1><?php echo Yii::t('main','Reskontraluettelo'); ?></h1>
<br>
<?php echo $yritys.date("d.m.Y",strtotime($from)).' - '.date("d.m.Y",strtotime($to)); ?>
</div>
<hr>
<?php endif; ?>

<?php if(!isset($_POST['tulosta']) and !isset($_POST['laheta'])) : ?>


        <!-- begin: .tray-center -->
        <div class="tray-center">

   <!-- tulostus -->
   <div class="pull-right">
   <div class="form-inline">
     <?php
     if(isset($_POST['asiakasLaskulle']) and !empty($_POST['asiakasLaskulle']) and count($model) > 0)
     {
     $a = Asiakkaat::model()->find(" asiakasnumero='".$_POST['asiakasLaskulle']."' ");
     if(isset($a->id) and !empty($a->sahkoposti))
     {
     echo '
     <div class="form-group">
     <form action="#" method="POST">
      <input type="hidden" name="from" value="'.$from.'">
      <input type="hidden" name="to" value="'.$to.'">
      <input type="hidden" name="sahkoposti" value="'.$a->sahkoposti.'">
      <input type="hidden" name="asiakasLaskulle" value="'.$_POST['asiakasLaskulle'].'">
      <input type="submit" name="laheta" class="btn btn-default btn-sm" value="Lähetä: '.$a->sahkoposti.'">
     </form>
     </div> ';
     }
     }
     ?>
     <div class="form-group">
     <form action="#" target="_blank" method="POST">
      <input type="hidden" name="from" value="<?php echo $from; ?>">
      <input type="hidden" name="to" value="<?php echo $to; ?>">
      <input type="hidden" name="asiakasLaskulle" value="<?php if(isset($_POST['asiakasLaskulle'])) echo $_POST['asiakasLaskulle']; ?>">
      <input type="submit" name="tulosta" class="btn btn-default btn-sm" value="PDF">
     </form>
     </div>
   </div>
   </div>
   <!-- tulostus -->

              <h2 class="myBgColors p10"> <i class="fa fa-barcode"></i> <?php echo Yii::t('main','Reskontraluettelo'); ?></h2>



   	    <form id="mobForm" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">


    		<?php 
       		$criteria = new CDbCriteria();
		$criteria->order = " etunimi ";
		$criteria->condition = " asiakasnumero!=0 and asiakasnumero!='' ";

        	$a = Asiakkaat::model()->findAll($criteria);
		echo '<select name="asiakasLaskulle" class="gui-input">';

		if(isset($_POST['asiakasLaskulle']) and !empty($_POST['asiakasLaskulle']))
		{
        	  $aon = Asiakkaat::model()->find(" asiakasnumero='".$_POST['asiakasLaskulle']."' ");
		  if(isset($aon->id) and !empty($aon->asiakasnumero))
		  {
			echo '<option value="'.$aon->asiakasnumero.'">'.$aon->Fullname.'</option>';
		  }

		} else {
	        echo '<option></option>';
		}

		foreach($a as $aa)
		{
		  if(isset($aa->id))
		  {
		    echo '<option value="'.$aa->asiakasnumero.'">'.$aa->Fullname.'</option>';
		  }

		}
		echo '</select>';
		?>

                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>


                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input datepickerFI" name="from" value="<?php echo date('d.m.Y', strtotime($from)); ?>" >

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input datepickerFI" name="to" value="<?php echo date('d.m.Y', strtotime($to)); ?>" >

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2 col-sm-offset-4">
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
<?php if(!isset($_POST['tulosta']) and !isset($_POST['laheta'])) : ?>
 <thead class="myBgColors">
<?php endif; ?>

  <th><?php echo Yii::t('main','Laskunro'); ?></th>
  <th><?php echo Yii::t('main','Asiakas'); ?></th>
  <th><?php echo Yii::t('main','Laskun päivä'); ?></th>
  <th><?php echo Yii::t('main','Maksupäivä'); ?></th>
  <th><?php echo Yii::t('main','Eräpäivä'); ?></th>
  <th><?php echo Yii::t('main','Suorituksen summa'); ?></th>
  <th><?php echo Yii::t('main','Avoinna'); ?></th>

<?php if(!isset($_POST['tulosta']) and !isset($_POST['laheta'])) : ?>
 </thead>
<?php endif; ?>

 </tr>
 <?php 
	$saldo = 0;
	$asetukset=Asetukset::model()->findbypk(1);
 foreach($model as $data)
 {
	$asiakas='';
	$a = Asiakkaat::model()->findbypk($data->as_nro);
	if(isset($a->id))
		$asiakas = $a->Fullname;


	$saldo += $data->yhteensa_total;
	$this->renderPartial('_reskontraluettelo',array('data'=>$data, 'saldo'=>$saldo, 'asiakas'=>$asiakas, 'asetukset'=>$asetukset));

 }
 ?>
 <tr>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td><?php echo Yii::t('main','Yhteensä'); ?></td>
  <td><?php echo number_format($saldo, 2, ',', ' '); ?></td>
  <td></td>
 </tr>
</table>

   </div>
  </div>

