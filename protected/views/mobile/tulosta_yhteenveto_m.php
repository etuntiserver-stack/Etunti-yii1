
<style>
table{
	width: 290px;
}
td,th{
	padding:3px 7px;
	border:1px #333 solid;
}
</style>

<h1> <?php echo Yii::t('main', 'YHTEENVETO MATKAT'); ?></h1>
<h3><?php echo date("d.m.Y",strtotime(Yii::app()->session['from']))." - ".date("d.m.Y",strtotime(Yii::app()->session['to'])); ?></h3>

<?php if(Yii::app()->session['from'] and Yii::app()->session['to']) : ?>
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
	$this->renderPartial('_yhteenveto_m',array('data'=>$data));
  }
  ?>
  </tbody>
  <tfoot>
  <?php
	$lu = '0';
	$tot = '0';

		$lu = $this->yhtLUmatka();
		$tot = $this->yhtTOTmatka();
  ?>
  <tr>
  <th><?php echo Yii::t('main', 'Yhteensä'); ?></th>
  <th><?php echo $this->sprint($lu); ?></th>
  <th><?php echo $this->sprint($tot); ?></th>
  </tr>
  </tfoot>
  </table>
<?php endif; ?>






