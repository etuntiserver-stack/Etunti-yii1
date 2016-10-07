<?php

?>
<link rel="stylesheet" type="text/css" href="css/pdf_table.css">

<div style="100%">

<table id="ylataulu">
 <tr><td style="width:80%">
  <?php $asetukset=Asetukset::model()->find("id=1"); ?>
  <img src="<?php echo $asetukset->logon_polkku; ?>" height="<?php echo $asetukset->logon_korkeus; ?>">
 </td><td valign="right" style="width:20%">
  <?php echo Yii::t('main', 'Lomat ja poissaolot'); ?>
  <?php if(isset($_POST['from']) and isset($_POST['to'])) : ?>
    <?php echo date("d.m.Y",strtotime($_POST['from'])).'-'.date("d.m.Y",strtotime($_POST['to'])); ?>
  <?php endif; ?>
 </td>
 </tr>
</table>

<br>



<div class="tb">
<table>
  <thead>
  <tr>
    <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
    <th><?php echo Yii::t('main', 'Lomaan nimike'); ?></th>
    <th><?php echo Yii::t('main', 'Kpl'); ?></th>
  </tr>
  </thead>
  <tbody>

  <?php
  $kplYht	= 0;
  ?>
  <?php foreach($model as $data) : ?>

  <?php
	$expl = explode("/",$data->status);
	$back = " style='background:".$expl[2].";color: white; text-align:left'";
  	$kplYht	+= $data->kpl;
  ?>
  <tr>
    <td style="text-align:left"><?php echo $data->tekijan_nimi; ?></td>
    <td <?php echo $back; ?>><?php echo $expl[4]; ?></td>
    <td><?php echo $data->kpl; ?></td>
  </tr>

  <?php endforeach; ?>
  </tbody>
  <tfoot>
  <tr>
    <td><?php echo Yii::t('main', 'Yhteenssä'); ?></td>
    <td></td>
    <td><?php echo $kplYht; ?></td>
  </tr>
  </tfoot>
</table>
</div>


</div>
