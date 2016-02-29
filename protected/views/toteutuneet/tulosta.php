<?php ?>
<link rel="stylesheet" type="text/css" href="css/pdf_table.css">
<style>
#ylataulu{
	width: 710px;
}
.tb table .chckbxHyvaksynta{ display:none }
.tb .col1{ width: 5%; text-align: left; }
.tb .col2{ width: 41%; text-align: left;}
.tb .col3{ width: 41%; text-align: left;}
.tb .col4{ width: 41%; text-align: left;}
.tb .col5{ width: 10%; }
.tb .col6{ width: 9%; }
.tb .col7{ width: 9%; }
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
  <th><?php echo Yii::t('main', 'Päivämäärä'); ?></th>

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
  </tr>
  </thead>
  <tbody>
  <?php 

  $arrDate = array(1=>"Ma",2=>"Ti",3=>"Ke",4=>"To",5=>"Pe",6=>"La",7=>"Su");
  $yhtMatka 	= 0;
  $yhtIlta 	= 0;
  $yhtYo 	= 0;
  $yhtMatkaWeek = 0;
  $yhtIltaWeek 	= 0;
  $yhtYoWeek	= 0;
  $viikkoBreak 	= '';

  for ($i = 0; $i <= $dateDiff; $i++) 
  {
  
    $plus = "+$i day";
    $date = '';
    $date = date("d.m.Y",strtotime($from2." ".$plus));

    $columnDate = date("N/d.m",strtotime($date));
    $explColDate = explode("/",$columnDate);
    $did = date("Ymd",strtotime($date));

    echo '
    <tr>
  	<td class="col1">'.$arrDate[$explColDate[0]].'<br> '.date("d.m",strtotime($date)).'</td>';
  
    $tas = explode(",",Yii::app()->user->adminPaketti);
    if(in_array('2',$tas)) 
    echo '<td class="col2">'.$this->renderPartial('//tyovuoroot/did',array('pvm'=>$date,'tid'=>$explTekija[0],'from'=>'mobiili'),true).'</td>';

    echo '
  	<td class="col3">'.$this->renderPartial('luetutpvmtid',array('pvm'=>$date,'tid'=>$explTekija[0],'from'=>'mobiili'),true).'</td>
  	<td class="col4" id="'.$did.'_'.$explTekija[0].'">'.$this->renderPartial('totpvmtid',array('pvm'=>$date,'tid'=>$explTekija[0],'from'=>'mobiili'),true).'</td>
  	<td class="col5" id="yht_'.$did.'_'.$explTekija[0].'">'.$this->renderPartial('yhteensapvm',array('pvm'=>date("Y-m-d",strtotime($date)),'tid'=>$explTekija[0],'from'=>'mobiili'),true).'</td>';

    $matka = '';
    $matka = $this->renderPartial('//mobile/tidfromtomatkat',array(
		'from'=>date("Y-m-d",strtotime($date)),
		'to'=>date("Y-m-d",strtotime($date)),
		'tid'=>$explTekija[0]
		),true);
    $yhtMatka += $matka;
    echo '<td class="col6">'.$this->sprint($matka).'</td>';

    $tyoIlta = '';
    $tyoIlta = $this->tyoIlta($explTekija[0],date("Y-m-d",strtotime($date)),'ilta');
    $yhtIlta += $tyoIlta;
    echo '<td class="col7">'.$this->sprint($tyoIlta).'</td>';

    $tyoYo = 0;
    $tyoYo = $this->tyoIlta($explTekija[0],date("Y-m-d",strtotime($date)),'yo');
    $yhtYo += $tyoYo;
    echo '<td>'.$this->sprint($tyoYo).'</td>';

    if($viikkoBreak == false){
       $yhtMatkaWeek += $matka;
       $yhtIltaWeek += $tyoIlta;
       $yhtYoWeek += $tyoYo;
    } else {
       $yhtMatkaWeek = 0;
       $yhtIltaWeek = 0;
       $yhtYoWeek = 0;
    }
    $viikkoBreak = false;

    echo '</tr>';

    echo $this->viikkonLoppu($date,$explTekija[0],$yhtMatkaWeek,$yhtIltaWeek,$viikkoBreak,$yhtYoWeek);

  }
  ?>
  </tbody>


  <tfoot>
<?php
    echo '<tr>';
    echo '<td>'.Yii::t('main', 'Yhteensä').'</td>';
    $tas = explode(",",Yii::app()->user->adminPaketti);
    if(in_array('2',$tas))
    echo '<td>'.sprint($this->renderPartial('//mobile/suunniteltu',array('id'=>$explTekija[0],'kohde_tid'=>'tid','from'=>$from, 'to'=>$to),true)).'</td>';
    echo '<td>'.sprint($this->renderPartial('//mobile/total_luettu',array('tid'=>$explTekija[0]),true)).'</td>';
    echo '<td>'.sprint($this->renderPartial('//mobile/total_toteutu',array('tid'=>$explTekija[0]),true)).'</td>';
    echo '<td></td>';
    echo '<td>'.$this->sprint($yhtMatka).'</td>';
    echo '<td>'.$this->sprint($yhtIlta).'</td>';
    echo '<td>'.$this->sprint($yhtYo).'</td>';
    echo '</tr>';
?>
  </tfoot>

  </table>
</div>
<?php endif; ?>

