<?php

    if(Yii::app()->session['tekija'])
       $explTekija = explode("//",Yii::app()->session['tekija']);

?>

<style>
table{
	width: 280px;
	font-size:80%;	
}

td{
	padding: 2px 5px;
	border:1px #333 solid;
	white-space: nowrap;
	min-height:70px;
}
</style>

<?php if(Yii::app()->session['tekija']) : ?>
<?php

function dateDiff($start, $end) {
  $start_ts = strtotime($start);
  $end_ts = strtotime($end);
  $diff = $end_ts - $start_ts;
  return round($diff / 86400);
}

		if(isset(Yii::app()->session['from']))
		$from = date("d.m.Y",strtotime(Yii::app()->session['from']));
		if(isset(Yii::app()->session['to']))
		$to = date("d.m.Y",strtotime(Yii::app()->session['to']));

		$dateDiff = dateDiff($from, $to);
?>

  <?php $asetukset=Asetukset::model()->find("id=1"); ?>
  <img src="<?php echo $asetukset->logon_polkku; ?>" height="<?php echo $asetukset->logon_korkeus; ?>">
  <br>

  <h1><?php echo $explTekija[1]; ?></h1>


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
  </tr>
  </thead>
  <tbody>
  <?php 

  $arrDate = array(1=>"Ma",2=>"Ti",3=>"Ke",4=>"To",5=>"Pe",6=>"La",7=>"Su");
  $yhtMatka = 0;
  $yhtIlta = 0;

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
  	<td>'.$arrDate[$explColDate[0]].'<br> '.date("d.m",strtotime($date)).'</td>';
  
    $tas = explode(",",Yii::app()->user->adminPaketti);
    if(in_array('2',$tas)) 
    echo '<td width="300">'.$this->renderPartial('//tyovuoroot/did',array('pvm'=>$date,'tid'=>$explTekija[0],'from'=>'mobiili'),true).'</td>';

    echo '
  	<td width="220">'.$this->renderPartial('luetutpvmtid',array('pvm'=>$date,'tid'=>$explTekija[0],'from'=>'mobiili'),true).'</td>
  	<td width="220" id="'.$did.'_'.$explTekija[0].'">'.$this->renderPartial('totpvmtid',array('pvm'=>$date,'tid'=>$explTekija[0],'from'=>'mobiili'),true).'</td>
  	<td id="yht_'.$did.'_'.$explTekija[0].'">'.$this->renderPartial('yhteensapvm',array('pvm'=>date("Y-m-d",strtotime($date)),'tid'=>$explTekija[0],'from'=>'mobiili'),true).'</td>';

    $matka = '';
    $matka = $this->renderPartial('//mobile/tidfromtomatkat',array(
		'from'=>date("Y-m-d",strtotime($date)),
		'to'=>date("Y-m-d",strtotime($date)),
		'tid'=>$explTekija[0]
		),true);
    $yhtMatka += $matka;
    echo '<td>'.$this->sprint($matka).'</td>';

    $tyoIlta = '';
    $tyoIlta = $this->tyoIlta($explTekija[0],date("Y-m-d",strtotime($date)));
    $yhtIlta += $tyoIlta;
    echo '<td>'.$this->sprint($tyoIlta).'</td>';
    echo '</tr>';

  }
  ?>
  </tbody>


  <tfoot>
<?php
    echo '<tr>';
    echo '<td>'.Yii::t('main', 'Yhteensä').'</td>';
    $tas = explode(",",Yii::app()->user->adminPaketti);
    if(in_array('2',$tas))
    echo '<td>'.sprint($this->renderPartial('//mobile/suunniteltu',array('id'=>$explTekija[0],'kohde_tid'=>'tid'),true)).'</td>';
    echo '<td>'.sprint($this->renderPartial('//mobile/total_luettu',array('tid'=>$explTekija[0]),true)).'</td>';
    echo '<td>'.sprint($this->renderPartial('//mobile/total_toteutu',array('tid'=>$explTekija[0]),true)).'</td>';
    echo '<td></td>';
    echo '<td>'.$this->sprint($yhtMatka).'</td>';
    echo '<td>'.$this->sprint($yhtIlta).'</td>';
    echo '</tr>';
?>
  </tfoot>

  </table>
<?php endif; ?>

