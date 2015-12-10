
<style>
table{
	width: 290px;
	font-size:65%;
}
td,th{
	padding:3px 7px;
	border:1px #333 solid;
}
.well{
	width:150px;
}
.tulostus_tekija{
	width:100px;
}
</style>

  <?php $asetukset=Asetukset::model()->find("id=1"); ?>
  <img src="<?php echo $asetukset->logon_polkku; ?>" height="<?php echo $asetukset->logon_korkeus; ?>">
  <br>

<h1> <?php echo Yii::t('main', 'PALKKATAULUKKO'); ?></h1>
<h3><?php echo date("d.m.Y",strtotime(Yii::app()->session['from']))." - ".date("d.m.Y",strtotime(Yii::app()->session['to'])); ?></h3>

<?php if(Yii::app()->session['from'] and Yii::app()->session['to']) : ?>
  <table>
  <thead>
  <tr>
  <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
  <th><?php echo Yii::t('main', 'Työpäiviä'); ?></th>
  <th><?php echo Yii::t('main', 'Matkat'); ?></th>
  <th><?php echo Yii::t('main', 'Työtunnit'); ?></th>
  <th><?php echo Yii::t('main', 'matka+<br>tunnit yht'); ?></th>
  <th><?php echo Yii::t('main', 'Ilta'); ?></th>
  <th><?php echo Yii::t('main', 'iltamatka+<br>iltatunnit yht'); ?></th>
  <th><?php echo Yii::t('main', 'Yö'); ?></th>
  <th><?php echo Yii::t('main', 'Su'); ?></th>
  <th><?php echo Yii::t('main', 'SL'); ?></th>
  <th><?php echo Yii::t('main', 'SPL<br>Pvm'); ?></th>
  <th><?php echo Yii::t('main', 'LS'); ?></th>
  <th><?php echo Yii::t('main', 'Korvaus'); ?></th>
  <th><?php echo Yii::t('main', 'Lisätyötunnit'); ?></th>
  <th><?php echo Yii::t('main', 'Ennakko'); ?></th>
  </tr>
  </thead>

  <?php 
  $tids = array();
  $totalTp	= 0;
  $tot_sun	=0;
  $tp		= 0;
  $sl 		= 0;
  $ls 		= 0;
  $spl 		= 0;
  $slYht	= 0;
  $splYht	= 0;
  $lsYht	= 0;
  foreach($model as $data)
  {
	$tids[] = $data->id;
	$tp = $this->Tp($data->id);
  	$sl = $this->TidfromtoSL(Yii::app()->session['from'],Yii::app()->session['to'],$data->id);
	$slYht += $sl;
  	$ls = $this->TidfromtoLS(Yii::app()->session['from'],Yii::app()->session['to'],$data->id);
	$lsYht += $ls;
  	$spl = $this->TidfromtoSPL(Yii::app()->session['from'],Yii::app()->session['to'],$data->id);
	$splYht += $spl;
	$totalTp += $tp;
	$this->renderPartial('_palkkataulukko',array('data'=>$data,'tp'=>$tp,'sl'=>$sl,'spl'=>$spl,'ls'=>$ls,));
  }

	$yht[0] = 0;
	$yht[1] = 0;
	$yht[2] = 0;
	$yht[3] = 0;
	$matka = 0;
	$matkaIlta = 0;

  foreach($tids as $t)
  {
	$matka += $this->renderPartial('//mobile/tidfromtomatkat',array(
		'from'=>Yii::app()->session['from'],
		'to'=>Yii::app()->session['to'],
		'tid'=>$t
		),true);


	$matkaIlta += $this->matkaIlta($t);

	$return = $this->toteutu($t,"palkkataulukko");
	$yht[0] += $return[0];
	$yht[1] += $return[1];
	$yht[2] += $return[2];
	$yht[3] += $return[3];
  }

  if($matka != 0)
  $matka = $this->sprint($matka).'<br>('.$this->num($matka).')';
  if($matkaIlta != 0)
  $matkaIlta = '<br><b>Matkat</b>:<br>'.$this->num($matkaIlta);
  ?>
  <tfoot>
  <tr>
  	<th><?php echo Yii::t('main', 'Yhteensä'); ?></th>
	<td><?php echo $totalTp; ?></td>
	<td><?php echo $matka; ?></td>
	<td><?php echo $this->num($yht[0]); ?></td>
	<td><?php echo '<b>Työt</b>:<br>'.$this->num($yht[1]).$matkaIlta; ?></td>
	<td><?php echo $this->sprint($yht[2]); ?></td>
	<td><?php echo $this->sprint($yht[3]); ?></td>
	<td></td>
	<td></td>
	<td><?php echo $this->num($slYht); ?></td>
	<td><?php echo $splYht; ?></td>
	<td><?php echo $this->num($lsYht); ?></td>
	<td></td>
	<td></td>
	<td></td>
  </tr>
  </tfoot>
  </table>
<?php endif; ?>






