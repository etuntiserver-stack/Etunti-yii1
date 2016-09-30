<?php ?>
<link rel="stylesheet" type="text/css" href="css/pdf_table.css">
<style>
#ylataulu{
	width: 710px;
}

.tb .col1{ width: 28%; text-align: left; }
.tb .col2{ width: 10%; }
.tb .col3{ width: 10%; }
.tb .col4{ width: 10%; }
.tb .col5{ width: 10%; }
</style>

<table id="ylataulu">
 <tr><td>
  <?php $asetukset=Asetukset::model()->find("id=1"); ?>
  <img src="<?php echo $asetukset->logon_polkku; ?>" height="<?php echo $asetukset->logon_korkeus; ?>">
 </td><td valign="right" style="width:20%">
  <?php echo Yii::t('main', 'Yhteenveto matkat'); ?>
  <?php if(isset($from) and isset($to)) : ?>
    <?php echo date("d.m.Y",strtotime($from)).'-'.date("d.m.Y",strtotime($to)); ?>
  <?php endif; ?>
 </td>
 </tr>
</table>

<br>

<?php if($from and $to) : ?>
<div class="tb">
  <table>
  <thead>
  <tr>
  <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
  <th><?php echo Yii::t('main', 'Luetut'); ?></th>
  <th><?php echo Yii::t('main', 'Toteutuneet'); ?></th>
  </tr>
  </thead>

  <tbody>
  <?php 
  foreach($model as $data)
  {
	$this->renderPartial('_yhteenveto_m',array('data'=>$data,'from'=>$from,'to'=>$to));
  }
  ?>
  </tbody>
  <tfoot>
  <?php
	$lu = '0';
	$tot = '0';

		$lu = $this->yhtLUmatka($from,$to);
		$tot = $this->yhtTOTmatka($from,$to);
  ?>
  <tr>
  <th><?php echo Yii::t('main', 'Yhteensä'); ?></th>
  <th><?php echo $this->sprint($lu); ?></th>
  <th><?php echo $this->sprint($tot); ?></th>
  </tr>
  </tfoot>
  </table>
</div>
<?php endif; ?>






