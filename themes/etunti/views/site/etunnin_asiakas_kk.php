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

              <h2 class="myBgColors p10"> <i class="fa fa-calendar-check-o"></i> <?php echo Yii::t('main', 'Tuntien toteuma kk').', '.strtoupper($model->yritys); ?> 

		</h2>

	</div>
        <!-- end: .tray-center -->


<div class="row" id="haku">
    <div class="col-sm-12 form-inline">
       	<b class="form-control form-group myBgColors"><a href="etunnin_asiakas_kk?id=<?php echo $id; ?>&pvm=<?php echo $previous; ?>">
	  <<</a> <?php echo $months[$month].' '.$year; ?> <a href="etunnin_asiakas_kk?id=<?php echo $id; ?>&pvm=<?php echo $next; ?>">>></a>
       	</b>
	
   	<?php
	    $lounas = '';
	    $lounas = ( isset(Yii::app()->session['Lounastauko']))  ? 'selected' : '';
	    $matka = '';
	    $matka = ( isset(Yii::app()->session['MATKA']))  ? 'selected' : '';

	    echo '<select name="ilman[]" class="selectpicker form-group" id="ilman"  multiple="multiple" title="'.Yii::t('main', 'Ei lasketa').'..." >'; //
	    echo '<option value="Lounastauko" '.$lounas.'>'.Yii::t('main', 'Lounastauko').'</option>';
	    echo '<option value="MATKA" '.$matka.'>'.Yii::t('main', 'Matka').'</option>';
	    echo '</select>';
	   ?>
	   <div class="form-group input-group-btn">
	        <button id="send" class="btn btn-primary btn-group myBgColors">OK</button>
	   </div>
    </div>
</div>
<br>

<?php
		$mob = Yii::app()->createController('Mobile');
?>
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">


<div class="table-responsive" id="taulukkoPaa">

  <TABLE id="verkko" class="table table-bordered">
  <?php 

  echo '<thead class="myBgColors"><TR>';
  echo '<TH>Nimi</TH>';

   for ($i = 1; $i <= $number; $i++) 
   {
     echo '<TH>'.$i.'</TH>';
   }
     echo '<TH>Yht.</TH>';
  echo '</TR></thead>';
  $t = Tyontekijat::model()->findAll(" aktiivinen = '1' ");
  foreach($t as $v)
  {
  $yht = 0;
  echo '<TR>';
  echo '<TD>'.$v->tekijan_nimi.'</TD>';
  $from = date("Y-m-d", strtotime($year.'-'.$month.' first day of this month'));
  $to = date("Y-m-d", strtotime($year.'-'.$month.' last day of this month'));
  $tyotunnit_all = $mob[0]->TidfromtoMobiiliAll($from, $to, $v->id, array(3), 2, false, 0, true);
   for ($i = 1; $i <= $number; $i++) 
   {
     $thisDate = $year.'-'.$month.'-'.$i;
     $date = $i.'.'.$month;


	$toteutuneet = 0;
	$tot[$i] = (isset($tyotunnit_all[$thisDate][$v->id]))? $tyotunnit_all[$thisDate][$v->id] : 0;//$mob[0]->TidfromtoStatus($thisDate,$thisDate,$v->id,3);
	$toteutuneet = $tot[$i];

	$yht += $toteutuneet;

	$cl = "";
	if($toteutuneet < 18000)
	$cl = "btn btn-xs btn-warning";
	elseif($toteutuneet > 28800)
	$cl = "btn btn-xs btn-danger";

	echo '<TD class="text-small" style="font-size:90%"><span class="'.$cl.'">'.$this->sprint($toteutuneet).'</span></TD>';

   }
  echo '<TD class="text-small"><b>'.$this->sprint($yht).'</b></TD>';
  echo '<TR>';
  }
  ?>
  </TABLE>

</div>


                 </div>
                </div>
              </div>
            </div>


<script type="text/javascript">
$(document).ready(function(){

  $("#send").click(function(){

	var thisVal = $("#ilman").val();
	$("#taulukkoPaa").html('<h1>ODOTA...</h>');

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/toteutuneet/kk',
	   type:'POST',
	   data: { "ilman" : thisVal },
           success: function(data){
        	console.log(data);
	 	window.location.reload();
    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
 	   }
        });


  });

});
</script>
