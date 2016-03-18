<?php

?>
<style>
th{
	text-align: center;
}
td .latikkoAsetukset{
	width: 290px;
	white-space: nowrap;
	min-height:70px;
}
td:first-child {
	white-space: normal;
}
.table-responsive{
	height: 500px;
	overflow: auto;
}
.fullRivi{
	height: 100%;
	margin-bottom: 2px;
	border:1px #ccc solid;
	padding:3px 7px;
	background: white;
	border-radius:5px;
}
</style>

<div id="checkedLaheta"></div>


        <!-- begin: .tray-center -->
        <div class="tray-center">

   <!-- tulostus -->
   <div class="pull-right">
     <form action="#" target="_blank" method="POST">
      <input type="submit" name="tulosta" class="btn btn-primary btn-sm myBgColors" value="PDF">
     </form>
   </div>
   <!-- tulostus -->

              <h2 class="myBgColors p10"> <i class="fa fa-paper-plane"></i> <?php echo Yii::t('main', 'TYÖVUOROJEN LÄHETYS'); ?></h2>

        <!-- loppu: .tray-center -->
        </div>




  <div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>

  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tvuoroot.js"></script>

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
<div class="row">
<div class="col-sm-3">
<?php echo CHtml::button(Yii::t('main', 'Lähetä kaikille'),array('target'=>'_blank','class'=>'btn btn-success myBgColors','id'=>'lahetaKaikkille'));
?>
</div>
<div class="col-sm-offset-4">
<h2>
  <a href="<?php echo $_SERVER['PHP_SELF'].'?week='.($week == 1 ? $wkMaara : $week -1).'&year='.($week == 1 ? $year - 1 : $year); ?>"><<</a> 
  <?php echo date('d.m.Y',strtotime($year ."W".$week .'1')).' - '.date('d.m.Y',strtotime($year ."W". $week .'7')); ?>
  <a href="<?php echo $_SERVER['PHP_SELF'].'?week='.($week == $wkMaara ? 1 : 1 + $week).'&year='.($week == $wkMaara ? 1 + $year : $year); ?>">>></a> 
</h2>
</div>
</div>




<div class="table-responsive" id="lahetysTable">
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
	echo '<td>'.$t->tekijan_nimi.' '.date('Y-m-d',strtotime($year ."W". $week)).' '.$week.'<BR>
	<input type="checkbox" for="'.$t->id.'">
	'.CHtml::link('Lähetä','/index.php/tyovuoroot/laheta?tid='.$t->id.'&week='.$week.'&year='.$year.'&tulosta=false',array('target'=>'_blank','class'=>'text-success'));

	$file = $week.'_'.$year.'_'.$t->id.'.pdf';
	$path = Yii::app()->request->baseUrl."emails/tyovuorot/".Yii::app()->user->domain;
	if (file_exists($path.'/'.$file))
	echo CHtml::link(Yii::t('main', ' Lähetetty'),'../../emails/tyovuorot/'.Yii::app()->user->domain.'/'.$file,array('target'=>'_blank','class'=>'text-danger'));

	echo '</td>';


	  for($day= 1; $day <= 7; $day++) {
	    $d = strtotime($year ."W". $week . $day);
	    $did = $this->renderPartial('//tyovuoroot/did',array('pvm'=>date('d.m.Y',$d),'tid'=>$t->id,'from'=>'mobiili'),true);
	    echo "
	    <td><div class='latikkoAsetukset'><b>".date('d.m.Y',$d)."</b><br>
	    ". json_decode($did, true) ."
	    </div></td>";
	  }

	echo '</tr>';
    }
  }
  ?>

</table>
</div>

<script type="text/javascript">
$(document).ready(function(){

$("#lahetaKaikkille").click(function(){

 setTimeout(function(){document.location.href = "laheta_k?week=<?php echo $week; ?>&year=<?php echo$year; ?>&tulosta=false&check="+checkChecked();},500);

});

function checkChecked() {
    var get = '';
    $('#lahetysTable table tr td input[type="checkbox"]').each(function() {
        if ($(this).is(":checked")) {
            get += $(this).attr("for")+",";
        }
    });

    return get; 
}


});
</script>














