<?php ?>
<link rel="stylesheet" type="text/css" href="css/pdf_table.css">
<style>
#ylataulu{
	width: 710px;
}

.tb .col1{ width: 3%; text-align: left; }
.tb .col2{ width: 10%; }
.tb .col3{ width: 7%; }
.tb .col4{ width: 7%; }
.tb .col5{ width: 7%; }
.tb .col6{ width: 7%; }
.tb .col7{ width: 7%; }
.tb .col8{ width: 7%; }
</style>

<table id="ylataulu">
 <tr><td>
  <?php $asetukset=Asetukset::model()->find("id=1"); ?>
  <img src="<?php echo $asetukset->logon_polkku; ?>" height="<?php echo $asetukset->logon_korkeus; ?>">
 </td><td valign="right" style="width:20%">
  <?php echo Yii::t('main', 'Yhteenveto tunnit'); ?>
  <?php if(isset($from) and isset($to)) : ?>
    <?php echo date("d.m.Y",strtotime($from)).'-'.date("d.m.Y",strtotime($to)); ?>
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

  <?php
  $tas = explode(",",Yii::app()->user->adminPaketti);
  if(in_array('2',$tas)) : 
  ?>
  <th><?php echo Yii::t('main', 'Suunniteltu tunnit'); ?></th>
  <?php endif; ?>

  <th><?php echo Yii::t('main', 'Luetut'); ?></th>
  <th><?php echo Yii::t('main', 'Toteutuneet'); ?></th>
  <th><?php echo Yii::t('main', 'Työpäiviä'); ?></th>
  <th><?php echo Yii::t('main', 'Ilta'); ?></th>
  <th><?php echo Yii::t('main', 'Yö'); ?></th>
  <th><?php echo Yii::t('main', 'Su'); ?></th>
  </tr>
  </thead>

  <?php 
  $tids = array();
  $total_lu 	= 0;
  $totalTp	= 0;
  $total_sunniteltu = 0;
  $tot_sun	=0;
  $tp		= 0;

  $yht[0] = 0;
  $yht[1] = 0;
  $yht[2] = 0;
  $yht[3] = 0;
  foreach($model as $data)
  {
	$tids[] = $data->tid;
        $total_lu += $data->l_tunnit;
	$tp = $this->Tp($data->tid,$from,$to);
	$totalTp += $tp;
	$tot_sun = $this->renderPartial('//mobile/suunniteltu',array('id'=>$data->tid,'kohde_tid'=>'tid','from'=>$from,'to'=>$to),true);
	$total_sunniteltu += $tot_sun;

	$return = $this->toteutu($data->tid,"yhteenveto",$from,$to);
	$this->renderPartial('_yhteenveto',array('data'=>$data,'tp'=>$tp,'tot_sun'=>$tot_sun,'return'=>$return));

	$yht[0] += $return[0];
	$yht[1] += $return[1];
	$yht[2] += $return[2];
	$yht[3] += $return[3];
  }


/*
		$total_l 	= 0;
		$total_t 	= 0;
		$totalIlta 	= 0;
		$totalYo 	= 0;
		$totalSu	= 0;

		$al		= '';
		$lop		= '';

  foreach($tids as $t)
  {


       		$criteria = new CDbCriteria();
        	$criteria->select = "
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'))) as l_tunnit,aloitan,loppui
		";

        	$criteria->condition = "  
			tid = '".$t."' and aloitan !='' and loppui !='' 
			AND id NOT IN(select kid from sivexkuitti_repaired)
		";

		if(Yii::app()->session['Lounastauko'])
	        $criteria->addCondition (" status != '10' ");

		if(Yii::app()->session['MATKA'])
	        $criteria->addCondition (" status != '2' ");

	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."' ");

		$lu = Mobile::model()->findAll($criteria);
		foreach($lu as $l)
		{
		    $l->l_tunnit = (strtotime($l->loppui)-strtotime($l->aloitan));
		    $al = explode(" ",$l->aloitan);
		    $lop = explode(" ",$l->loppui);
		    $totalIlta += $this->ilta($al,$lop);
		    $totalYo += $this->yo($al,$lop);
		    $total_l += $l->l_tunnit;
		    if(date('N', strtotime($al[0])) == 7)
		    $totalSu += (strtotime($lop[0]." ".$lop[1])-strtotime($al[0]." ".$al[1]));
		}


       		$criteria = new CDbCriteria();
        	$criteria->select = "
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'))) as l_tunnit,aloitan,loppui 
		";

        	$criteria->condition = "  
			tid = '".$t."' and aloitan !='' and loppui !='' 
		";

		if(Yii::app()->session['Lounastauko'])
	        $criteria->addCondition (" status != '10' ");

		if(Yii::app()->session['MATKA'])
	        $criteria->addCondition (" status != '2' ");

	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."' ");

		$tot = Toteutuneet::model()->findAll($criteria);
		foreach($tot as $l)
		{
		    $l->l_tunnit = (strtotime($l->loppui)-strtotime($l->aloitan));
		    $al = explode(" ",$l->aloitan);
		    $lop = explode(" ",$l->loppui);
		    $totalIlta += $this->ilta($al,$lop);
		    $totalYo += $this->yo($al,$lop);
		    $total_l += $l->l_tunnit;
		    if(date('N', strtotime($al[0])) == 7)
		    $totalSu += (strtotime($lop[0]." ".$lop[1])-strtotime($al[0]." ".$al[1]));
		}
}
		$total = $total_l;
*/
  ?>
  <tfoot>
  <tr>
  	<th><?php echo Yii::t('main', 'Yhteensä'); ?></th>
	<?php
	$tas = explode(",",Yii::app()->user->adminPaketti);
	if(in_array('2',$tas)) {
	echo '<td>'.$this->sprint($total_sunniteltu).'</td>';
	}
	?>

	<td><?php echo $this->sprint($total_lu); ?></td>
	<td><?php echo $this->sprint($yht[0]); ?></td>
	<td><?php echo $totalTp; ?></td>
	<td><?php echo $this->sprint($yht[1]); ?></td>
	<td><?php echo $this->sprint($yht[2]); ?></td>
	<td><?php echo $this->sprint($yht[3]); ?></td>
  </tr>
  </tfoot>
  </table>
</div>






