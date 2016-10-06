<?php

?>
<link rel="stylesheet" type="text/css" href="css/pdf_table.css">

<div style="100%">

<table id="ylataulu">
 <tr><td style="width:80%">
  <?php $asetukset=Asetukset::model()->find("id=1"); ?>
  <img src="<?php echo $asetukset->logon_polkku; ?>" height="<?php echo $asetukset->logon_korkeus; ?>">
 </td><td valign="right" style="width:20%">
  <?php echo Yii::t('main', 'Toteutuneen ja suunnitellun työn erot'); ?>
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
    <th><?php echo Yii::t('main', 'Osoite'); ?></th>
    <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
    <th><?php echo Yii::t('main', 'Suunnittelut'); ?></th>
    <th><?php echo Yii::t('main', 'Toteutuneet'); ?></th>
    <th><?php echo Yii::t('main', 'Ero'); ?></th>
  </tr>
  </thead>
  <tbody>

  <?php
  $suunnittellutYht	= 0;
  $toteutuneetYht	= 0;
  $eroYht		= 0;
  $suunnittellut 	= 0;
  $toteutuneet 		= 0;
  $ero			= 0;
  $osoite 		= '';
  $tyontekija 		= '';
  ?>
  <?php foreach($model as $data) : ?>

  <?php
	if($data->status == 0)
	$data->status = 3;

	$toteutuneet = $this->toteutuneet($data->tid,$data->pvm,$data->status,$data->kohde);
	if($data->suunnittellut > $toteutuneet)
		$ero = '<b style="color:red">-'.$this->sprint($data->suunnittellut-$toteutuneet).'</b>';
	elseif($data->suunnittellut < $toteutuneet)
		$ero = '<b style="color:green">+'.$this->sprint($toteutuneet-$data->suunnittellut).'</b>';
	elseif($data->suunnittellut == $toteutuneet)
		$ero = '<b>00:00</b>';
  ?>
  <tr>
    <td><?php echo $data->pvm; ?></td>
    <td style="text-align:left"><?php echo $data->osoite; ?></td>
    <td style="text-align:left"><?php echo $data->tekijan_nimi; ?></td>
    <td><?php echo $this->sprint($data->suunnittellut); ?></td>
    <td><?php echo $this->sprint($toteutuneet); ?></td>
    <td><?php echo $ero; ?></td>
  </tr>

  <?php endforeach; ?>
  </tbody>
  <tfoot>
  <tr>
    <td><?php echo Yii::t('main', 'Yhteenssä'); ?></td>
    <td></td>
    <td></td>
    <td><?php echo $suunnittellutYht; ?></td>
    <td><?php echo $toteutuneetYht; ?></td>
    <td><?php echo $eroYht; ?></td>
  </tr>
  </tfoot>
</table>
</div>


</div>
