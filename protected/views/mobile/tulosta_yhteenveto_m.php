
<style>
table{
	font-size: 80%;
	width: 290px;
}
td,th{
	padding:3px 7px;
	border:1px #333 solid;
}
</style>

  <?php $asetukset=Asetukset::model()->find("id=1"); ?>
  <img src="<?php echo $asetukset->logon_polkku; ?>" height="<?php echo $asetukset->logon_korkeus; ?>">
  <br>

<h1> <?php echo Yii::t('main', 'YHTEENVETO MATKAT'); ?></h1>
<h3><?php echo date("d.m.Y",strtotime($from))." - ".date("d.m.Y",strtotime($to)); ?></h3>

<?php if($from and $to) : ?>
  <table>
  <thead>
  <tr>
  <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
  <th><?php echo Yii::t('main', 'Luetut'); ?></th>
  <th><?php echo Yii::t('main', 'Toteutuneet'); ?></th>
<?php 
/*
  <th><?php echo Yii::t('main', 'Työpäiviä'); ?></th>
  <th><?php echo Yii::t('main', 'Ilta'); ?></th>
  <th><?php echo Yii::t('main', 'Yö'); ?></th>
  <th><?php echo Yii::t('main', 'Su'); ?></th>
*/
?>
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
<?php endif; ?>






