<?php ?>
<link rel="stylesheet" type="text/css" href="css/pdf_table_palkka.css">
<style>
#ylataulu{
	width: 1060px;
}


.tb .col1{ width: 10%; }
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
.tb .col13{ width: 12%; text-align: left; }
.tb .col14{ width: 12%; text-align: left; }
.tb .col15{ width: 12%; text-align: left; }
</style>


<table id="ylataulu">
 <tr><td style="width:80%">
  <p><?php $site = Yii::app()->createController('Site'); echo $site[0]->logoShower(null); ?></p>
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
  <table class="table table-bordered small">
  <thead class="myBgColors">
  <tr>
  <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
  <th><?php echo Yii::t('main', 'Tp'); ?></th>
  <th><?php echo Yii::t('main', 'Matkat'); ?></th>
  <th><?php echo Yii::t('main', 'Työtunnit'); ?></th>
  <th><?php echo Yii::t('main', 'M+T'); ?></th>
  <th><?php echo Yii::t('main', 'Ilta'); ?></th>
  <th><?php echo Yii::t('main', 'Ilta M+<br>Ilta T'); ?></th>
  <th><?php echo Yii::t('main', 'Lounas'); ?></th>
  <th><?php echo Yii::t('main', 'Yö'); ?></th>
  <th><?php echo Yii::t('main', 'Su'); ?></th>
  <th><?php echo Yii::t('main', 'PY'); ?></th>
  <th><?php echo Yii::t('main', 'EL'); ?></th>
  <th><?php echo Yii::t('main', 'SL'); ?></th>
  <th><?php echo Yii::t('main', 'SPL<br>Pvm'); ?></th>
  <th><?php echo Yii::t('main', 'LS'); ?></th>
  <th><?php echo Yii::t('main', 'VL'); ?></th>
  <th><?php echo Yii::t('main', 'VKL'); ?></th>
  <th><?php echo Yii::t('main', 'Korvaus'); ?></th>
  <th><?php echo Yii::t('main', 'Lisätyötunnit'); ?></th>
  <th><?php echo Yii::t('main', 'Ennakko'); ?></th>
  </tr>
  </thead>

  <?php
  $toteutuneet = Yii::app()->createController('Toteutuneet');
  $tids = array();
  $totalTp	= 0;
  $tot_sun	= 0;
  $tp		= 0;
  $sl 		= 0;
  $ls 		= 0;
  $spl 		= 0;
  $vl 		= 0;
  $vlYht	= 0;
  $vklYht	= 0;
  $slYht	= 0;
  $splYht	= 0;
  $lsYht	= 0;
  $pyhatYht	= 0;
  $elYht	= 0;
  $matkaYht	= 0;
  $yht[0] 	= 0;
  $yht[1] 	= 0;
  $yht[2] 	= 0;
  $yht[3] 	= 0;
  $mPlusTYht	= 0;
  $matkaIltaYht = 0;
  $iltaMatkaPlusIltatunnitYht = 0;
  $loun		= 0;
  $lounYht	= 0;


  $begin = new DateTime(date("Y-m-d", strtotime($from)));
  $end = new DateTime(date("Y-m-d", strtotime($to)));
  $interval = DateInterval::createFromDateString('1 day');
  $period = new DatePeriod($begin, $interval, $end);

  foreach($model as $data)
  {
	$yotunnit	= 0;
	$iltatunnit	= 0;
	$sutunnit	= 0;

	$tids[] = $data->id;
	$tp = $this->Tp($data->id,$from,$to);
  	$sl = $this->TidfromtoSairaus($from,$to,$data->id,'SL');
	$slYht += $sl;
  	$ls = $this->TidfromtoSairaus($from,$to,$data->id,'LS');
	$lsYht += $ls;
  	$spl = $this->TidfromtoSairaus($from,$to,$data->id,'SPL');
	$splYht += $spl;
  	$vl = $this->TidfromtoVuosilomaPalkkatauluko($from,$to,$data->id, 'VL');
	$vlYht += $vl;
  	$vkl = $this->TidfromtoVuosilomaPalkkatauluko($from,$to,$data->id, 'VKL');
	$vklYht += $vkl;
	$totalTp += $tp;
  	$pyhat = $this->pyhapaivat($data->id,$from,$to,"pyhat");
	$pyhatYht += $pyhat;
  	$el = $this->pyhapaivat($data->id,$from,$to,"el");
	$elYht += $el;

	$m = $this->renderPartial('//mobile/tidfromtomatkat',array(
		'from'=>$from,
		'to'=>$to,
		'tid'=>$data->id
		),true);
	$matkaYht += $m;

	// Yo 
	foreach ($period as $dt) {
		$IltaYoSu = $toteutuneet[0]->IltaYoSu($data->id, $dt->format("Y-m-d"));
		$iltatunnit += $IltaYoSu[0];
		$yotunnit += $IltaYoSu[1];
		$sutunnit += $IltaYoSu[2];
	}

	$matkaIlta = $this->matkaIlta($data->id,$from,$to);
	$return = $this->toteutu($data->id,"palkkataulukko",$from,$to);

	$mPlusTYht += $return[0]+$m;

	$loun = $this->TidfromtoStatus($from,$to,$data->id,10);
	$lounYht += $loun;
	$matkaIltaYht += $matkaIlta;
	$iltaMatkaPlusIltatunnitYht += $iltatunnit;

	$yht[0] += $return[0];
	$yht[1] += $iltatunnit-$matkaIlta;
	$yht[2] += $yotunnit;
	$yht[3] += $sutunnit;


	$this->renderPartial('_palkkataulukko',array(
			'data'=>$data,
			'return'=>$return,
			'matka'=>$m,
			'matkaIlta'=>$matkaIlta,
			'tp'=>$tp,
			'sl'=>$sl,
			'spl'=>$spl,
			'ls'=>$ls,
			'vl'=>$vl,
			'vkl'=>$vkl,
			'from'=>$from,
			'to'=>$to,
			'pyhat'=>$pyhat,
			'el'=>$el,
			'loun'=>$loun,
			'yotunnit' => $yotunnit,
			'iltatunnit' => $iltatunnit,
			'sutunnit' => $sutunnit
	));
  }







  if($matkaIltaYht != 0)
  $matkaIltaYht = '<br><b>Matkat</b>:<br>'.$this->num($matkaIltaYht);
  ?>
  <tfoot>
  <tr>
  	<th><?php echo Yii::t('main', 'Yhteensä'); ?></th>
	<td><?php if($totalTp != 0) echo $totalTp; ?></td>
	<td><?php echo $this->num($matkaYht); ?></td>
	<td><?php echo $this->num($yht[0]); ?></td>
	<td><?php echo $this->num($mPlusTYht); ?></td>
	<td><?php if($matkaIltaYht != 0 or $this->num($yht[1]) != 0) echo '<b>Työt</b>:<br>'.$this->num($yht[1]).$matkaIltaYht; ?></td>
	<td><?php echo $this->num($iltaMatkaPlusIltatunnitYht); ?></td>
	<td><?php echo $this->num($lounYht); ?></td>
	<td><?php echo $this->num($yht[2]); ?></td>
	<td><?php echo $this->num($yht[3]); ?></td>
	<td><?php echo $this->num($pyhatYht); ?></td>
	<td><?php echo $this->num($elYht); ?></td>
	<td><?php echo $this->num($slYht); ?></td>
	<td><?php if($splYht != 0) echo $this->num($splYht); ?></td>
	<td><?php echo $this->num($lsYht); ?></td>
	<td><?php if($vlYht != 0) echo $vlYht; ?></td>
	<td><?php if($vklYht != 0) echo $vklYht; ?></td>
	<td></td>
	<td></td>
	<td></td>
  </tr>
  </tfoot>
  </table>
</div>
<?php endif; ?>






