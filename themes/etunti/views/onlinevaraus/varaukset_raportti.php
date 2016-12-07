<?php

?>
<link rel="stylesheet" type="text/css" href="css/pdf_table.css">

<table id="ylataulu">
 <tr><td style="width:200px">
  <?php $asetukset=Asetukset::model()->findbypk(1); ?>
  <img src="<?php echo $asetukset->logon_polkku; ?>" height="<?php echo $asetukset->logon_korkeus; ?>">
 </td><td valign="right" style="width:20%">
    <h2><?php echo Yii::t('main', 'Onlinevaraus ALV-raportti'); ?></h2><br>
    <?php echo date("d.m.Y",strtotime($from)).' - '.date("d.m.Y",strtotime($to)); ?>
 </td>
 </tr>
</table>

<br>


<div class="tb">
<table>
  <thead>
  <tr>
    <th><?php echo Yii::t('main', 'Tuote'); ?></th>
    <th><?php echo Yii::t('main', 'Pvm.'); ?></th>
    <th><?php echo Yii::t('main', 'ALV %'); ?></th>
    <th><?php echo Yii::t('main', 'Veroton'); ?></th>
    <th><?php echo Yii::t('main', 'ALV määrä'); ?></th>
    <th><?php echo Yii::t('main', 'Verollinen'); ?></th>
  </tr>
  </thead>
  <tbody>
  <?php
  $yhtVeroton	= 0;
  $yhtalvMaara	= 0;
  $yhtVerollinen= 0;
  foreach($model as $data){
	
	$tuote = '';
	$t = json_decode($data->tilauksen_kuvaus, true);
	if(isset($t['paa']))
	   $t2 = array_keys($t['paa']);
		if(isset($t2[0]))
		   $tuote = $t2[0];

	$alvMaara 	= str_replace(",",".",$data->hinta)-str_replace(",",".",$data->veroton_hinta);

	$yhtVeroton	+= str_replace(",",".",$data->veroton_hinta);
  	$yhtalvMaara	+= $alvMaara;
	$yhtVerollinen	+= str_replace(",",".",$data->hinta);

	echo '<tr>';
	echo '<td align="left">'.$tuote.'</td>';
	echo '<td>'.date("d.m.Y", strtotime($data->time)).'</td>';
	echo '<td>'.$data->alv.'%</td>';
	echo '<td>'.number_format($data->veroton_hinta, 2, ",", " ").'</td>';
	echo '<td>'.number_format($alvMaara, 2, ",", " ").'</td>';
	echo '<td>'.number_format($data->hinta, 2, ",", " ").'</td>';
	echo '</tr>';
  }
  ?>
  </tbody>
  <tfoot>
  <tr>
    <th><?php echo Yii::t('main', 'Yhteensä'); ?></th>
    <th></th>
    <th></th>
    <th><?php echo number_format($yhtVeroton, 2, ",", " "); ?></th>
    <th><?php echo number_format($yhtalvMaara, 2, ",", " "); ?></th>
    <th><?php echo number_format($yhtVerollinen, 2, ",", " "); ?></th>
  </tr>
  </tfoot>
</table>
</div>
