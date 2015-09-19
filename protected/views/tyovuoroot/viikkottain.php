<?php

?>

<legend>
<h1> <?php echo Yii::t('main', 'TYÖVUOROT VIIKKO'); ?> <i class="glyphicon glyphicon-time"></i></h1>
</legend>


  <div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>
  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tvuoroot.js"></script>

<?php
$paivat=array(
	1=>'Maanantai',
	2=>'Tiistai',
	3=>'Keskiviikko',
	4=>'Torstai',
	5=>'Perjantai',
	6=>'Lauantai',
	7=>'Suunnuntai',
	);

$year = (isset($_GET['year'])) ? $_GET['year'] : date("Y");
$week = (isset($_GET['week'])) ? $_GET['week'] : date('W');
if($week > 52) {
    $year++;
    $week = 1;
} elseif($week < 1) {
    $year--;
    $week = 52;
}
?>
<center>
<h2>
  <a href="<?php echo $_SERVER['PHP_SELF'].'?week='.($week == 1 ? 52 : $week -1).'&year='.($week == 1 ? $year - 1 : $year); ?>"><<</a> 
  <?php echo date('d.m.Y',strtotime($year ."W". $week .'1')).' - '.date('d.m.Y',strtotime($year ."W". $week .'7')); ?>
  <a href="<?php echo $_SERVER['PHP_SELF'].'?week='.($week == 52 ? 1 : 1 + $week).'&year='.($week == 52 ? 1 + $year : $year); ?>">>></a> 
</h2>
</center>

<table class="table table-bordered">
  <tr>
  <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
  <?php
  if($week < 10) {
    $week = '0'. $week;
  }
  for($day= 1; $day <= 7; $day++) {
    $d = strtotime($year ."W". $week . $day);
    echo "<th>". $paivat[date('N', $d)] ." ". date('d.m', $d) ."</th>";
  }
  ?>
  </tr>

  <?php
  $criteria = new CDbCriteria();
  $criteria->order = " tekijan_nimi ";
  $criteria->condition = " aktiivinen=1 ";
  $tt = Tyontekijat::model()->findAll($criteria);

  foreach($tt as $t)
  {
  $criteria = new CDbCriteria();
  $criteria->condition = " tid='".$t->id."' AND 
  DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d')  
  BETWEEN  '".date('Y-m-d',strtotime($year ."W". $week .'1'))."' AND '".date('Y-m-d',strtotime($year ."W". $week .'7'))."' 
  AND pvm!='' ";
  $tv = Tyovuoroot::model()->findAll($criteria);

    if(count($tv) > 0)
    {
	$tnimi = explode(" ",$t->tekijan_nimi);
	echo '<tr>';
	echo '<td>'.CHtml::link('','/index.php/tyovuoroot/laheta?tid='.$t->id.'&week='.$week.'&year='.$year.'&tulosta=false',array('target'=>'_blank','class'=>'glyphicon glyphicon-file')).'
	 '.$tnimi[0].'</td>';

	  if($week < 10) {
	    $week = '0'. $week;
	  }
	  for($day= 1; $day <= 7; $day++) {
	    $d = strtotime($year ."W". $week . $day);
	    echo "
	    <td><div class='small'>
	    ". $this->renderPartial('//tyovuoroot/did',array('pvm'=>date('d.m.Y',$d),'tid'=>$t->id,'from'=>'mobiili'),true) ."
	    </div></td>";
	  }

	echo '</tr>';
    }
  }
  ?>

</table>

















