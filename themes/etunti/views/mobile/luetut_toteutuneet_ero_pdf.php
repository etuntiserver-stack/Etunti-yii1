<?php

?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/pdf_table.css">

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
  </tr>
  </thead>
  <tbody>

  <?php
  $suunnittellutYht	= 0;
  $toteutuneetYht	= 0;
  $suunnittellut 	= 0;
  $toteutuneet 		= 0;
  $osoite 		= '';
  $tyontekija 		= '';
  ?>
  <?php foreach($model as $data) : ?>

  <tr>
    <td><?php echo $data->pvm; ?></td>
    <td style="text-align:left"><?php echo $data->osoite; ?></td>
    <td style="text-align:left"><?php echo $data->tekijan_nimi; ?></td>
    <td><?php echo $this->sprint($data->suunnittellut); ?></td>
    <td><?php echo $this->sprint($data->luetutIlmanToteutuneet+$data->toteutuneet); ?></td>
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
  </tr>
  </tfoot>
</table>
</div>


</div>
