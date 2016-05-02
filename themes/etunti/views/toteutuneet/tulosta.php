<?php ini_set("max_execution_time", "120"); ?>
<link rel="stylesheet" type="text/css" href="css/pdf_table.css">
<style>
#ylataulu{
	width: 710px;
}
.tb table .chckbxHyvaksynta{ display:none }
.tb .col1{ width: 5%; text-align: left; }
.tb .col2{ width: 31%; text-align: left;}
.tb .col3{ width: 31%; text-align: left;}
.tb .col4{ width: 31%; text-align: left;}
.tb .col5{ width: 10%; }
.tb .col6{ width: 9%; }
.tb .col7{ width: 9%; }
.tb .col8{ width: 9%; }
input[type="checkbox"]{ display:none }
</style>

<table id="ylataulu">
 <tr><td>
  <?php $asetukset=Asetukset::model()->find("id=1"); ?>
  <img src="<?php echo $asetukset->logon_polkku; ?>" height="<?php echo $asetukset->logon_korkeus; ?>">
 </td><td valign="right" style="width:20%">
  <?php echo Yii::t('main', 'Yhteenveto tunnit'); ?>
  <?php if(isset($from) and isset($to)) : ?>
    <?php echo $tekija.", ".date("d.m.Y",strtotime($from)).'-'.date("d.m.Y",strtotime($to)); ?>
  <?php endif; ?>
 </td>
 </tr>
</table>

<br>



<?php
    if(Yii::app()->session['tekija'])
       $explTekija = explode("//",Yii::app()->session['tekija']);
?>

<?php if(Yii::app()->session['tekija']) : ?>
<?php

function dateDiff($start, $end) {
  $start_ts = strtotime($start);
  $end_ts = strtotime($end);
  $diff = $end_ts - $start_ts;
  return round($diff / 86400);
}

		if(isset($from))
		$from2 = date("d.m.Y",strtotime($from));
		if(isset($to))
		$to2 = date("d.m.Y",strtotime($to));

		$dateDiff = dateDiff($from2, $to2);
?>


<div class="tb">
  <table>
  <thead>
  <tr>
  <th width=1><?php echo Yii::t('main', 'Pvm'); ?></th>

  <?php
  $tas = explode(",",Yii::app()->user->adminPaketti);
  if(in_array('2',$tas)) : 
  ?>
  <th><?php echo Yii::t('main', 'Suunnitellut'); ?></th>
  <?php endif; ?>

   <th><?php echo Yii::t('main', 'Luettu'); ?></th>
  <th><?php echo Yii::t('main', 'Toteutuneet'); ?></th>
  <th><?php echo Yii::t('main', 'Yhteensä'); ?></th>
  <th><?php echo Yii::t('main', 'Ma'); ?></th>
  <th><?php echo Yii::t('main', 'Ilta'); ?></th>
  <th><?php echo Yii::t('main', 'Yö'); ?></th>
  <th><?php echo Yii::t('main', 'Su'); ?></th>
  <th><?php echo Yii::t('main', 'Py'); ?></th>
  <th><?php echo Yii::t('main', 'El'); ?></th>
  </tr>
  </thead>
  <tbody>
  <?php 
  $arrDate = array(1=>"Ma",2=>"Ti",3=>"Ke",4=>"To",5=>"Pe",6=>"La",7=>"Su");
  $yhtMatka 	= 0;
  $yhtIlta 	= 0;
  $yhtYo 	= 0;
  $yhtSu 	= 0;
  $yhtMatkaWeek = 0;
  $yhtIltaWeek 	= 0;
  $yhtYoWeek	= 0;
  $yhtSuWeek	= 0;
  $yhtTotpvmtid	= 0;
  $yhtLuetutpvmtid = 0;
  $viikkoBreak 	= false;

  $tyoPy 	= 0;
  $yhtPy	= 0;
  $tyoEl 	= 0;
  $yhtEl	= 0;
  $yhtPyWeek	= 0;
  $yhtElWeek	= 0;


  for ($i = 0; $i <= $dateDiff; $i++) 
  {
  
    $plus = "+$i day";
    $date = '';
    $date = date("d.m.Y",strtotime($from." ".$plus));

    $columnDate = date("N/d.m",strtotime($date));
    $explColDate = explode("/",$columnDate);
    $did = date("Ymd",strtotime($date));

    echo '
    <tr>
  	<td class="col1">'.$arrDate[$explColDate[0]].'<br>'.date("d.m",strtotime($date)).'</td>';
  
    $tas = explode(",",Yii::app()->user->adminPaketti);
    if(in_array('2',$tas))
    {
	$dido = '';
	$dido = $this->renderPartial('//tyovuoroot/did',array('pvm'=>$date,'tid'=>$explTekija[0],'from'=>'mobiili'),true);
    	echo '<td class="col2">'.json_decode($dido, true).'</td>';
    }

    echo '<td class="col3">';
	   $luetutpvmtid = $this->renderPartial('luetutpvmtid',array('pvm'=>$date,'tid'=>$explTekija[0],'from'=>'mobiili'),true);
	   $exLatikoLu = explode('explode999', $luetutpvmtid);
	   echo $exLatikoLu[0];

    echo '</td>';

    echo '<td id="'.$did.'_'.$explTekija[0].'" class="col4">';

	   $totpvmtid = $this->renderPartial('totpvmtid',array('pvm'=>$date,'tid'=>$explTekija[0],'from'=>'mobiili'),true);
	   $exLatiko = explode('explode999', $totpvmtid);
	   echo $exLatiko[0];

    echo '</td>';

    echo '<td id="yht_'.$did.'_'.$explTekija[0].'" class="col5">'.$this->renderPartial('yhteensapvm',array('pvm'=>date("Y-m-d",strtotime($date)),'tid'=>$explTekija[0],'from'=>'mobiili'),true).'</td>';

    $matka = '';
    $matka = $this->renderPartial('//mobile/tidfromtomatkat',array(
		'from'=>date("Y-m-d",strtotime($date)),
		'to'=>date("Y-m-d",strtotime($date)),
		'tid'=>$explTekija[0]
		),true);
    $yhtMatka += $matka;
    echo '<td class="col6">'.$this->sprint($matka).'</td>';

    $tyoIlta = 0;
    $tyoIlta = $this->tyoIlta($explTekija[0],date("Y-m-d",strtotime($date)),'ilta');
    $yhtIlta += $tyoIlta;
    echo '<td class="col7">'.$this->sprint($tyoIlta).'</td>';

    $tyoYo = 0;
    $tyoYo = $this->tyoIlta($explTekija[0],date("Y-m-d",strtotime($date)),'yo');
    $yhtYo += $tyoYo;
    echo '<td class="col8">'.$this->sprint($tyoYo).'</td>';

    $tyoSu = 0;
    $tyoSu = $this->tyoIlta($explTekija[0],date("Y-m-d",strtotime($date)),'su');
    $yhtSu += $tyoSu;
    echo '<td class="col9">'.$this->sprint($tyoSu).'</td>';

    $tyoPy = 0;
    $tyoPy = $this->pyhapaivat($explTekija[0],$date,"pyhat");
    $yhtPy += $tyoPy;
    echo '<td class="col10">'.$this->sprint($tyoPy).'</td>';

    $tyoEl = 0;
    $tyoEl = $this->pyhapaivat($explTekija[0],$date,"el");
    $yhtEl += $tyoEl;
    echo '<td class="col11">'.$this->sprint($tyoEl).'</td>';





    $yhtMatkaWeek 	+= $matka;
    $yhtIltaWeek 	+= $tyoIlta;
    $yhtYoWeek 		+= $tyoYo;
    $yhtSuWeek 		+= $tyoSu;
    $yhtPyWeek 		+= $tyoPy;
    $yhtElWeek 		+= $tyoEl;
    if(isset($exLatiko[1]))
    $yhtTotpvmtid 	+= $exLatiko[1];
    if(isset($exLatikoLu[1]))
    $yhtLuetutpvmtid 	+= $exLatikoLu[1];



    echo '</tr>';



    		$tid = $explTekija[0];

	    if(date('N', strtotime($date)) == 7)
	    {
  	    echo '<tr>';
  		echo '<td style="background: #669999;color: white" class="text-center viikkoRivi small myBgColors"><b>'.Yii::t('main', 'Viikko').' '.date("W",strtotime($date)).'</b></td>';

		 $vktyoaika = '';
		 $ts = Tyosuhdet::model()->find(" tid = '".$tid."' ");
		 if(isset($ts->id) and !empty($ts['vktyoaika']))
		  $vktyoaika = $ts['vktyoaika'];

		  echo '<td style="background: #669999;color: white; text-align:center" class="viikkoRivi small myBgColors" id="vk_'.date("W",strtotime($date)).'_'.$tid.'">';
		  $kokoViikko = '';
		  $vko = '';
		  $vko = date("W",strtotime($date));
		  $year = date("Y",strtotime($date));
		  $kokoViikko = $this->renderPartial('//tyovuoroot/viikko',array('tid'=>$tid,'viikko'=>$vko,'year'=>$year),true);

		  $cl = '';
		  if(	(int)str_replace(":","",$kokoViikko) > (int)str_replace(":","",$vktyoaika)
			and (int)str_replace(":","",$kokoViikko) > 0
			and (int)str_replace(":","",$vktyoaika) > 0
		  )
		  $cl = 'class="btn btn-xs btn-danger"';

		  echo '<span '.$cl.'>'.$kokoViikko. '<br>('.$vktyoaika.')</span>';

		  echo '</td>';

		  echo '<td style="background: #669999;color: white; text-align:center" class="small myBgColors">'.$this->sprint($yhtLuetutpvmtid).'<br>('.$this->num($yhtLuetutpvmtid).')</td>';
		  echo '<td style="background: #669999;color: white; text-align:center" class="small myBgColors">'.$this->sprint($yhtTotpvmtid).'<br>('.$this->num($yhtTotpvmtid).')</td>';
		  echo '<td style="background: #669999;color: white; text-align:center" class="small myBgColors"></td>';
		  echo '<td style="background: #669999;color: white; text-align:center" class="small myBgColors">'.$this->sprint($yhtMatkaWeek).'<br>('.$this->num($yhtMatkaWeek).')</td>';
		  echo '<td style="background: #669999;color: white; text-align:center" class="small myBgColors">'.$this->sprint($yhtIltaWeek).'<br>('.$this->num($yhtIltaWeek).')</td>';
		  echo '<td style="background: #669999;color: white; text-align:center" class="small myBgColors">'.$this->sprint($yhtYoWeek).'<br>('.$this->num($yhtYoWeek).')</td>';
		  echo '<td style="background: #669999;color: white; text-align:center" class="small myBgColors">'.$this->sprint($yhtSuWeek).'<br>('.$this->num($yhtSuWeek).')</td>';
		  echo '<td style="background: #669999;color: white; text-align:center" class="small myBgColors">'.$this->sprint($yhtPyWeek).'<br>('.$this->num($yhtPyWeek).')</td>';
		  echo '<td style="background: #669999;color: white; text-align:center" class="small myBgColors">'.$this->sprint($yhtElWeek).'<br>('.$this->num($yhtElWeek).')</td>';
	    echo '</tr>';


		$yhtMatkaWeek 	= 0;
		$yhtIltaWeek 	= 0;
		$yhtYoWeek 	= 0;
		$yhtSuWeek 	= 0;
		$yhtPyWeek 	= 0;
		$yhtElWeek 	= 0;
		$yhtTotpvmtid 	= 0;
	 	$yhtLuetutpvmtid = 0;

	    }



  }
  ?>
  </tbody>


  <tr>
  <th><?php echo Yii::t('main', 'Yhteensä'); ?></th>

  <?php
  $tas = explode(",",Yii::app()->user->adminPaketti);
  if(in_array('2',$tas) and isset($explTekija[0])){
  $total_sunniteltu = $this->renderPartial('//mobile/suunniteltu',array('id'=>$explTekija[0],'kohde_tid'=>'tid','from'=>Yii::app()->session['from'], 'to'=>Yii::app()->session['to']),true);
  echo '<th>'.sprint($total_sunniteltu).'</th>';
  }
  ?>

  <?php
  $total_luettu = 0;
  $total_luettu = $this->renderPartial('//mobile/total_luettu',array('tid'=>$explTekija[0]),true);
  echo '<th>'.sprint($total_luettu).'</th>';
  ?>

  <?php
  $total_toteutu = $this->renderPartial('//mobile/total_toteutu',array('tid'=>$explTekija[0]),true);
  echo '<th>'.sprint($total_toteutu).'</th>';
  ?>

  <th></th>
  <th><?php echo $this->sprint($yhtMatka); ?></th>
  <th><?php echo $this->sprint($yhtIlta); ?></th>
  <th><?php echo $this->sprint($yhtYo); ?></th>
  <th><?php echo $this->sprint($yhtSu); ?></th>
  <th><?php echo $this->sprint($yhtPy); ?></th>
  <th><?php echo $this->sprint($yhtEl); ?></th>
  </tr>


  </table>

</div>

<?php endif; ?>

