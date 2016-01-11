<style>
table{
	//width: 150px;
	font-size: 80%;
}
td,th{
	padding:3px 7px;
	border:1px #333 solid;
}
</style>

  <?php $asetukset=Asetukset::model()->find("id=1"); ?>
  <img src="<?php echo $asetukset->logon_polkku; ?>" height="<?php echo $asetukset->logon_korkeus; ?>">
  <br>

<h1> <?php echo Yii::t('main', 'YHTEENVETO KOHTEET'); ?></h1>
<h3><?php echo date("d.m.Y",strtotime($from))." - ".date("d.m.Y",strtotime($to)); ?></h3>

<?php if($from and $to) : ?>
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
	$this->renderPartial('_kyhteenveto',array('kohde_kannasta'=>$key,'kohdenID'=>$val,'from'=>$from,'to'=>$to));
  ?>
  </tbody>

  <tfoot>
  <?php
	$lu = '0';
	$tot = '0';
	$suunn = '0';
	$kplyht = '0';

		$suunn = $this->yhtSUUNN($from,$to);
		$lu = $this->yhtLU($from,$to);
		$tot = $this->yhtTOT($from,$to);


	//kpl
	$kpl = 0;
	$kpl1 = 0;
	$kpl2 = 0;
	$cr4 = new CDbCriteria();
	$this->totKpl($cr4,"kaikki",$from,$to);
	$cr4->addCondition (" id NOT IN (SELECT kid FROM sivexkuitti_repaired) ");
	$k = Mobile::model()->findAll($cr4);
	foreach($k as $kk)
	$kpl1 += $kk->count;

	$cr5 = new CDbCriteria();
	$this->totKpl($cr5,"kaikki",$from,$to);
	$k = Toteutuneet::model()->findAll($cr5);
	foreach($k as $kk)
	$kpl2 += $kk->count;

	$kplyht = $kpl1+$kpl2;


  ?>
  <tr>
  <th><?php echo Yii::t('main', 'Yhteensä'); ?></th>

  <?php
  $tas = explode(",",Yii::app()->user->adminPaketti);
  if(in_array('2',$tas)) : 
  ?>
  <th>/ <?php echo Yii::t('main', 'oikeasti:'); ?><?php echo $this->sprint($suunn); ?></th>
  <?php endif; ?>

  <th><?php echo $this->sprint($lu); ?></th>
  <th><?php echo $this->sprint($tot); ?></th>
  <th><?php echo $kplyht; ?></th>
  </tr>
  </tfoot>
  </table>
<?php endif; ?>
