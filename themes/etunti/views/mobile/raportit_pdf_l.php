<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title></title>
</head>
<body>
<?php 
	ini_set('memory_limit', '256M');
?>

<!-- Style -->
<?php if(isset($_POST['luoPrintSivu'])) : ?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.min.css">
<script>
window.onload = function () {
    window.print();
}
</script>
<?php endif; ?>
<!-- Style -->



<?php if($tyyppi == 'Toteutuneet') : ?>
<style>
#ylataulu{
	width: 100%;
}
</style>
<?php endif; ?>




  <p><?php $site = Yii::app()->createController('Site'); echo $site[0]->logoShower(null); ?></p>
  
  <?php if(isset(Yii::app()->session['from']) and isset(Yii::app()->session['to'])) : ?>
    <p><b><?php echo Yii::t('main', $tyyppi); ?> <?php echo date("d.m.Y",strtotime(Yii::app()->session['from'])).'-'.date("d.m.Y",strtotime(Yii::app()->session['to'])); ?></b></p>
  <?php endif; ?>


<br>


<div class="tb">
<table class="table">
  <thead>
  <tr>
    <th><?php echo Yii::t('main', 'Pvm.'); ?></th>
    <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
    <th><?php echo Yii::t('main', 'Osoite'); ?></th>
    <th><?php echo Yii::t('main', 'Aloitus'); ?></th>
    <th><?php echo Yii::t('main', 'Lopetus'); ?></th>
    <th><?php echo Yii::t('main', 'Kesto (h)'); ?></th>
    <th><?php echo Yii::t('main', 'Kesto'); ?></th>
    <th><?php echo Yii::t('main', 'Tila'); ?></th>
  </tr>
  </thead>
  <tbody>
  <?php
  $kkesto = 0;
  foreach($model as $data){

	$data->loppui = date("Y-m-d H:i",strtotime($data->loppui));
	$data->aloitan = date("Y-m-d H:i",strtotime($data->aloitan));
  
	$kesto = strtotime($data->loppui)-strtotime($data->aloitan);
	$kkesto += $kesto;

	$viesti = '';
	if($data->viesti != '' and $data->viesti != 'xxx')
	$viesti = $data->viesti;

	$date1 = new DateTime($data->loppui);
	$date2 = new DateTime($data->aloitan);
	$interval = $date1->diff($date2);

	$spl = $this->sairausMerkki($data->sairaus);

	// <-- Vain jos ero on 1 päivä, erotellaan rivit
	if(date('d', strtotime($data->loppui)) != date('d', strtotime($data->aloitan)) and ( (int)$interval->d == 0 or (int)$interval->d == 1) )
	{

		// <--Ensimmäinen osa
		$loppuiOrigin = $data->loppui;
		$data->loppui = date("Y-m-d 00:00",strtotime($data->loppui));
		$kesto = strtotime($data->loppui)-strtotime($data->aloitan);
		echo $this->rivit($data, $kesto, $viesti, $spl);
		// Ensimmäinen osa -->

		// <--Toinen osa
		$data->aloitan = date("Y-m-d 00:00",strtotime($data->loppui));
		$data->loppui = date("Y-m-d H:i",strtotime($loppuiOrigin));
		$kesto = strtotime($data->loppui)-strtotime($data->aloitan);
		echo $this->rivit($data, $kesto, $viesti, $spl);
		// Toinen osa -->

		continue;

	}
	// Vain jos ero on 1 päivä, erotellaan rivit -->


		echo $this->rivit($data, $kesto, $viesti, $spl);


  }
  ?>
  </tbody>
  <tfoot>
  <tr>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th><?php echo Yii::t('main', 'Yhteensä'); ?></th>
    <th><?php echo $this->sprint($kkesto); ?></th>
    <th></th>
    <th></th>
  </tr>
  </tfoot>
</table>
</div>


</body>
</html>
