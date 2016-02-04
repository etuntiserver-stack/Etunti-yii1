<link rel="stylesheet" type="text/css" href="css/pdf_table.css">

<style>
#ylataulu{
	width: 710px;
}
td{ height: auto }
.tb .col0{  text-align: left; font-size: 50%; line-height: 130%; }
.tb .col1{ width: 22%; text-align: left; font-size: 50%; line-height: 130%; }
.tb .col2{ width: 22%; text-align: left; font-size: 50%; line-height: 130%; }
.tb .col3{ width: 22%; text-align: left; font-size: 50%; line-height: 130%; }
.tb .col4{ width: 22%; text-align: left; font-size: 50%; line-height: 130%; }
.tb .col5{ width: 22%; text-align: left; font-size: 50%; line-height: 130%; }
.tb .col6{ width: 22%; text-align: left; font-size: 50%; line-height: 130%; }
.tb .col7{ width: 22%; text-align: left; font-size: 50%; line-height: 130%; }
</style>


<?php
$paivat=array(
	1=>'Maanantai',
	2=>'Tiistai',
	3=>'Keskiviikko',
	4=>'Torstai',
	5=>'Perjantai',
	6=>'Lauantai',
	7=>'Sunnuntai',
	);

$wkMaara = 53;

$year = (isset($_GET['year'])) ? $_GET['year'] : date("Y");
$week = (isset($_GET['week'])) ? $_GET['week'] : date('W');

if($week > $wkMaara) {
    $year++;
    $week = 1;
} elseif($week < 1) {
    $year--;
    $week = $wkMaara;
}

    $week = sprintf("%02d", $week);
?>

<table id="ylataulu">
 <tr><td>
  <?php $asetukset=Asetukset::model()->find("id=1"); ?>
  <img src="<?php echo $asetukset->logon_polkku; ?>" height="<?php echo $asetukset->logon_korkeus; ?>">
 </td><td valign="right" style="width:20%">
  <?php echo Yii::t('main', 'Työvuorot'); ?>

  <?php echo date('d.m.Y',strtotime($year ."W".$week .'1')).' - '.date('d.m.Y',strtotime($year ."W". $week .'7')); ?>
 
 </td>
 </tr>
</table>

<br>



<div class="tb">
<table class="table table-bordered table-condensed small">
  <thead class="myBgColors">
  <tr>
  <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
  <?php
/*
  if($week < 10) {
    $week = ($week/10)*10;
  }
*/

  for($day= 1; $day <= 7; $day++) {
    $d = strtotime($year ."W". $week . $day);
    echo "<th>". $paivat[date('N', sprintf("%02d", $d))] ."<br>". date('d.m', $d) ."</th>";
  }
  ?>
  </tr>
  </thead>
  <?php
  $criteria = new CDbCriteria();
  $criteria->order = " tekijan_nimi ";
  $criteria->condition = " aktiivinen=1 ";
  $tt = Tyontekijat::model()->findAll($criteria);

  foreach($tt as $t)
  {
  $week = sprintf("%02d", $week);
  $dataByWeek = '';
  $dataByWeek = date('Y-m-d',strtotime($year ."W". $week));
  $criteria = new CDbCriteria();
  $criteria->condition = " tid='".$t->id."' AND 
  DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d')  
  BETWEEN  '".date('Y-m-d',strtotime($dataByWeek." this monday"))."' AND '".date('Y-m-d',strtotime($dataByWeek." this sunday"))."' 
  AND pvm!='' ";
  $tv = Tyovuoroot::model()->findAll($criteria);

  //echo date('Y-m-d',strtotime($dataByWeek." this sunday")).'<br>';

    if(count($tv) > 0)
    {
	echo '<tr>';
	echo '<td class="col0">'.$t->tekijan_nimi.'</td>';


	  for($day= 1; $day <= 7; $day++) {
	    $d = strtotime($year ."W". $week . $day);
	    echo "
	    <td class='col$day'><div class='latikkoAsetukset'>
	    ". $this->renderPartial('//tyovuoroot/did',array('pvm'=>date('d.m.Y',$d),'tid'=>$t->id,'from'=>'mobiili','tietoja'=>1),true) ."
	    </div></td>";
	  }

	echo '</tr>';
    }
  }
  ?>

</table>
</div>
