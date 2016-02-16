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
  <?php echo Yii::t('main', 'Yhteenveto kohteet'); ?>
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

  <?php 
  $sunYht = 0;
  $luetutYht = 0;
  $toteutuneetYht = 0;
  $kplyht = 0;
  foreach($lu as $key=>$val)
  {
	// <-- sunniteltu
	$sunniteltu = $this->renderPartial('//mobile/suunniteltu',array('id'=>$val,'kohde_tid'=>'kohde','from'=>$from,'to'=>$to),true);
        $sunYht += $sunniteltu;
	// sunniteltu -->

	// <-- luetut
	$cr1 = new CDbCriteria();
	$this->totLu($cr1,$val,$from,$to);
	$lu = Mobile::model()->find($cr1);

	$luetut = $lu->l_tunnit;
  	$luetutYht += $luetut;
	// luetut -->

	// <-- toteutuneet
	$cr2 = new CDbCriteria();
	$this->totLu($cr2,$val,$from,$to);
	$cr2->addCondition (" id NOT IN (SELECT kid FROM sivexkuitti_repaired) ");
	$tot1 = Mobile::model()->find($cr2);

	$cr3 = new CDbCriteria();
	$this->totLu($cr3,$val,$from,$to);
	$tot2 = Toteutuneet::model()->find($cr3);

	$toteutuneet = $tot1->l_tunnit+$tot2->l_tunnit;
  	$toteutuneetYht += $toteutuneet;
	//  toteutuneet -->

	// <-- kpl
	$kpl = 0;
	$kpl1 = 0;
	$kpl2 = 0;
	$cr4 = new CDbCriteria();
	$this->totKpl($cr4,$val,$from,$to);
	$cr4->addCondition (" id NOT IN (SELECT kid FROM sivexkuitti_repaired) ");
	$k = Mobile::model()->find($cr4);

	if(isset($k->count)) $kpl1 += $k->count;

	$cr5 = new CDbCriteria();
	$this->totKpl($cr5,$val,$from,$to);
	$k = Toteutuneet::model()->find($cr5);

	if(isset($k->count)) $kpl2 += $k->count;

	$kpl = $kpl1+$kpl2;
	$kplyht += $kpl;
	// kpl -->

	$this->renderPartial('_kyhteenveto',array(
		'luetut'=>$luetut,
		'toteutuneet'=>$toteutuneet,
		'sunniteltu'=>$sunniteltu, 
		'kohde_kannasta'=>$key,
		'kohdenID'=>$val,
		'kpl'=>$kpl,
		'from'=>$from,
		'to'=>$to
	));
  }
  ?>

  <tfoot>
  <tr>
  <th><?php echo Yii::t('main', 'Yhteensä'); ?></th>

  <?php
  $tas = explode(",",Yii::app()->user->adminPaketti);
  if(in_array('2',$tas)) : 
  ?>
  <th><?php echo $this->sprint($sunYht); ?></th>
  <?php endif; ?>

  <th><?php echo $this->sprint($luetutYht); ?></th>
  <th><?php echo $this->sprint($toteutuneetYht); ?></th>
  <th><?php echo $kplyht; ?></th>
  </tr>
  </tfoot>

  </table>
</div>
<?php endif; ?>
