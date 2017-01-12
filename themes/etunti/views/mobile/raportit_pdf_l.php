<?php ?>
<link rel="stylesheet" type="text/css" href="css/pdf_table.css">

<?php if($tyyppi == 'Toteutuneet') : ?>
<style>
#ylataulu{
	width: 675px;
}
</style>
<?php endif; ?>

<?php if($tyyppi == 'Luetut') : ?>
<style>
#ylataulu{
	width: 710px;
}
</style>
<?php endif; ?>

<table id="ylataulu">
 <tr><td style="width:80%">
  <?php $asetukset=Asetukset::model()->find("id=1"); ?>
  <img src="<?php echo $asetukset->logon_polkku; ?>" height="<?php echo $asetukset->logon_korkeus; ?>">
 </td><td valign="right" style="width:20%">
  <?php echo Yii::t('main', $tyyppi); ?>
  <?php if(isset(Yii::app()->session['from']) and isset(Yii::app()->session['to'])) : ?>
    <?php echo date("d.m.Y",strtotime(Yii::app()->session['from'])).'-'.date("d.m.Y",strtotime(Yii::app()->session['to'])); ?>
  <?php endif; ?>
 </td>
 </tr>
</table>

<br>


<div class="tb">
<table>
  <thead>
  <tr>
    <th><?php echo Yii::t('main', 'Pvm.'); ?></th>
    <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
    <th><?php echo Yii::t('main', 'Osoite'); ?></th>
    <th><?php echo Yii::t('main', 'Aloitus'); ?></th>
    <th><?php echo Yii::t('main', 'Lopetus'); ?></th>
    <th><?php echo Yii::t('main', 'Kesto'); ?></th>
    <th><?php echo Yii::t('main', 'Viesti'); ?></th>
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
    <th></th>
    <th><?php echo sprint($kkesto); ?> <b>(<?php echo num($kkesto); ?>)</b></th>
    <th></th>
  </tr>
  </tfoot>
</table>
</div>
