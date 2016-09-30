<?php
/* @var $this VuosilomatController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('main', 'Toteutuneet (kk)'),
);
/*
$this->menu=array(
	array('label'=>'Create Vuosilomat', 'url'=>array('create')),
	array('label'=>'Manage Vuosilomat', 'url'=>array('admin')),
);
*/

$months=array(
	'1'=>Yii::t('main', 'Tammikuu'),
	'2'=>Yii::t('main', 'Helmikuu'),
	'3'=>Yii::t('main', 'Maaliskuu'),
	'4'=>Yii::t('main', 'Huhtikuu'),
	'5'=>Yii::t('main', 'Toukokuu'),
	'6'=>Yii::t('main', 'Kesäkuu'),
	'7'=>Yii::t('main', 'Heinäkuu'),
	'8'=>Yii::t('main', 'Elokuu'),
	'9'=>Yii::t('main', 'Syyskuu'),
	'10'=>Yii::t('main', 'Lokakuu'),
	'11'=>Yii::t('main', 'Marraskuu'),
	'12'=>Yii::t('main', 'Joulukuu'),
	);

$pvm = '';
if(!isset($_GET['pvm'])){
$pvm = date("Y-m");
$month 	= date("n");
$year 	= date("Y");
} else {
$pvm = $_GET['pvm'];
$month 	= date("n",strtotime($pvm));
$year 	= date("Y",strtotime($pvm));
}


$number = cal_days_in_month(CAL_GREGORIAN, $month, $year); 

$next	= date("Y-n",strtotime("+1 month ".$pvm));
$previous = date("Y-n",strtotime("-1 month ".$pvm));

echo	'<input type=hidden id=month value='.$month.'>';
echo	'<input type=hidden id=Year value='.$year.'>';
echo	'<input type=hidden id=number value='.cal_days_in_month(CAL_GREGORIAN, $month, $year).'>'; 
?>





        <!-- begin: .tray-center -->
        <div class="tray-center">

              <h2 class="myBgColors p10"> <i class="fa fa-calendar-check-o"></i> <?php echo Yii::t('main', 'Laskuri kk').', '.strtoupper($model->yritys); ?> 

		</h2>

	</div>
        <!-- end: .tray-center -->


<div class="row" id="haku">
    <div class="col-sm-12 col-sm-offset-5">
       	<b class=""><a href="etunnin_asiakas_kk_laskuri?id=<?php echo $id; ?>&pvm=<?php echo $previous; ?>">
	  <<</a> <?php echo $months[$month].' '.$year; ?> <a href="etunnin_asiakas_kk_laskuri?id=<?php echo $id; ?>&pvm=<?php echo $next; ?>">>></a>
       	</b>
    </div>
</div>
<br>

<?php
	$mobile = Yii::app()->createController('Mobile');

	$from = date("Y-m-d", strtotime("01.".$month.".".$year));
	$to = date("Y-m-d", strtotime($from." last day of this month"));

	$criteria = new CDbCriteria();
       	$criteria->order = "id";
	$tt = Tyontekijat::model()->findAll($criteria);
	$pt = '<table class="table table-hover">';
	$yhtYlitp = 0;
	foreach($tt as $tdata)
	{
		$tp = '';
		$tp = $mobile[0]->TP($tdata->id,$from,$to);

		$pt .= '<tr><td>'.Yii::t('main', 'Siivoja').' #'.$tdata->id.'</td><td><b>'.$tp.'</b> '.Yii::t('main', 'työpäivä').'</td></tr>';
		if($tp >= 5)
			$yhtYlitp +=1;

	}
		$pt .= '</table>';




	$tvp = '<table class="table table-hover">';
	$yhtYlitvp = 0;
	foreach($tt as $tdata)
	{

		$criteria = new CDbCriteria();
        	$criteria->group = "DATE(DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d'))";
	        $criteria->condition = "
			DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."' 
			AND tid='".$tdata->id."'
		";
		$tv = Tyovuoroot::model()->findAll($criteria);

		$tvp .= '<tr><td>'.Yii::t('main', 'Siivoja').' #'.$tdata->id.'</td><td><b>'.count($tv).'</b> '.Yii::t('main', 'työpäivä').'</td></tr>';

		if(count($tv) >= 5)
		{
			$yhtYlitvp +=1;
		}
	}
		$tvp .= '</table>';
?>



            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row table-responsive">

<table class="table table-bordered">
 <tr>
  <th><?php echo Yii::t('main', 'Palvelutunnit työpäiviä yhteensä'); ?></th>
  <th><?php echo Yii::t('main', 'Työvuorot työpäiviä yhteensä'); ?></th>
 </tr>
 <tr>
  <td><?php echo $pt; ?></td>
  <td><?php echo $tvp; ?></td>
 </tr>
<tfooter>
 <tr>
  <th>
  <?php 
	echo Yii::t('main', 'Yhteensä yli 5 työpäivä').': &nbsp;'.$yhtYlitp.' '.Yii::t('main', 'siivoja').'<br>';
	$yhtHinta = $model->palveluhinta_persiivoja*$yhtYlitp;
	echo Yii::t('main', 'Yhteensä hinta').': '.$yhtHinta.' &euro;'; 
  ?>
  </th>
  <th>
  <?php 
	echo Yii::t('main', 'Yhteensä yli 5 työpäivä').': &nbsp;'.$yhtYlitvp.' '.Yii::t('main', 'siivoja').'<br>';
	$yhtHintaTV = $model->tyovuorohinta_persiivoja*$yhtYlitvp;
	echo Yii::t('main', 'Yhteensä hinta').': '.$yhtHintaTV.' &euro;'; 
  ?>
  </th>
 </tr>
</tfooter>
</table>


                 </div>

  <?php
	$muut_tyokaluhinta = $model->muut_tyokaluhinta;
	$total = $yhtHinta+$yhtHintaTV+$muut_tyokaluhinta;
	echo '<h3>'.Yii::t('main', 'Muut työkalut').': '.$model->muut_tyokaluhinta.' &euro;</h3>';
	echo '<h1>'.Yii::t('main', 'YHTEENSÄ').': '.$total.' &euro;</h1>'; 
  ?>

                </div>
              </div>
            </div>


<script type="text/javascript">
$(document).ready(function(){

  $("#send").click(function(){


  });

});
</script>
