<style>
table{
	//width: 150px;
}
td,th{
	padding:3px 7px;
	border:1px #333 solid;
}
</style>

<h1> <?php echo Yii::t('main', 'YHTEENVETO KOHTEET'); ?></h1>
<h3><?php echo date("d.m.Y",strtotime(Yii::app()->session['from']))." - ".date("d.m.Y",strtotime(Yii::app()->session['to'])); ?></h3>

<?php if(Yii::app()->session['from'] and Yii::app()->session['to']) : ?>
  <table>
  <thead>
  <tr>
  <th><?php echo Yii::t('main', 'Osoite'); ?></th>

  <?php
  $tas = explode(",",Yii::app()->user->adminPaketti);
  if(in_array('2',$tas)) : 
  ?>
  <th><?php echo Yii::t('main', 'Suunniteltu tunnit'); ?></th>
  <?php endif; ?>

  <th><?php echo Yii::t('main', 'Luetut tunnit'); ?></th>
  <th><?php echo Yii::t('main', 'Toteutuneet tunnit'); ?></th>
  <th><?php echo Yii::t('main', 'Kpl'); ?></th>
  </tr>
  </thead>

  <tbody>
  <?php 
  foreach($lu as $key=>$val)
	$this->renderPartial('_kyhteenveto',array('kohde_kannasta'=>$key,'kohdenID'=>$val));
  ?>
  </tbody>

  <tfoot>
  <?php
	$lu = '0';
	$tot = '0';
	$suunn = '0';
	if(isset(Yii::app()->session['mitkatKohteet']))
	{
		$suunn = $this->yhtSUUNN();
		$lu = $this->yhtLU(Yii::app()->session['mitkatKohteet']);
		$tot = $this->yhtTOT(Yii::app()->session['mitkatKohteet']);
	}
  ?>
  <tr>
  <th><?php echo Yii::t('main', 'Yhteensä'); ?></th>

  <?php
  $tas = explode(",",Yii::app()->user->adminPaketti);
  if(in_array('2',$tas)) : 
  ?>
  <th><?php echo $this->sprint($suunn); ?></th>
  <?php endif; ?>

  <th><?php echo $this->sprint($lu); ?></th>
  <th><?php echo $this->sprint($tot); ?></th>
  <th></th>
  </tr>
  </tfoot>

  </table>
<?php endif; ?>
