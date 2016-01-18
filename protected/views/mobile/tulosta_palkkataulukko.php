<?php ?>
<link rel="stylesheet" type="text/css" href="css/pdf_table.css">
<style>
#ylataulu{
	width: 1060px;
}


.tb .col1{ width: 20%; }
.tb .col2{ width: 7%; }
.tb .col3{ width: 7%; }
.tb .col4{ width: 7%; }
.tb .col5{ width: 7%; }
.tb .col6{ width: 7%; }
.tb .col7{ width: 7%; }
.tb .col8{ width: 7%; }
.tb .col9{ width: 7%; }
.tb .col10{ width: 7%; }
.tb .col11{ width: 7%; }
.tb .col12{ width: 7%; }
.tb .col13{ width: 24%; text-align: left; }
.tb .col14{ width: 24%; text-align: left; }
.tb .col15{ width: 24%; text-align: left; }
</style>


<table id="ylataulu">
 <tr><td style="width:80%">
  <?php $asetukset=Asetukset::model()->find("id=1"); ?>
  <img src="<?php echo $asetukset->logon_polkku; ?>" height="<?php echo $asetukset->logon_korkeus; ?>">
 </td><td valign="right" style="width:20%">
  <?php echo Yii::t('main', 'Palkkataulukko'); ?>
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
  <th><?php echo Yii::t('main', 'TP'); ?></th>
  <th><?php echo Yii::t('main', 'M'); ?></th>
  <th><?php echo Yii::t('main', 'T'); ?></th>
  <th><?php echo Yii::t('main', 'M+<br>T yht'); ?></th>
  <th><?php echo Yii::t('main', 'Ilta'); ?></th>
  <th><?php echo Yii::t('main', 'Im+<br>It yht'); ?></th>
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
	$tp = $this->Tp($data->id,$from,$to);
  	$sl = $this->TidfromtoSL($from,$to,$data->id);
	$slYht += $sl;
  	$ls = $this->TidfromtoLS($from,$to,$data->id);
	$lsYht += $ls;
  	$spl = $this->TidfromtoSPL($from,$to,$data->id);
	$splYht += $spl;
	$totalTp += $tp;
	$this->renderPartial('_palkkataulukko',array('data'=>$data,'tp'=>$tp,'sl'=>$sl,'spl'=>$spl,'ls'=>$ls,'from'=>$from,'to'=>$to));
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
		'from'=>$from,
		'to'=>$to,
		'tid'=>$t
		),true);


	$matkaIlta += $this->matkaIlta($t,$from,$to);

	$return = $this->toteutu($t,"palkkataulukko",$from,$to);
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
</div>
<?php endif; ?>






