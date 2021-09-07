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

              <h2 class="myBgColors p10"> <i class="fa fa-calendar-check-o"></i> <?php echo Yii::t('main', 'Tuntien toteuma kk'); ?> 

		</h2>

	</div>
        <!-- end: .tray-center -->


<div class="row" id="haku">
    <div class="col-sm-12 form-inline">
       	<b class="form-control form-group myBgColors"><a href="kk?pvm=<?php echo $previous; ?>">
	  <<</a> <?php echo $months[$month].' '.$year; ?> <a href="kk?pvm=<?php echo $next; ?>">>></a>
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



		<!-- Fixed Table -->
		<!-- http://www.jqueryscript.net/table/jQuery-Plugin-For-Fixed-Table-Header-Footer-Columns-TableHeadFixer.html -->
		<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/tableHeadFixer.js"></script>

		<style>	
			#verkko {
				width: 1800px !important;
			}
			.laatikot{
				min-width: 30px;
				text-align: center;
			}
		</style>

		<script>
			$(document).ready(function() {
				window.onload = function(event) { resizeDiv(); }
				//window.onresize = function(event) { resizeDiv(); }

				function resizeDiv() {
				    vpw = $(window).width()-100; 
				    vph = $(window).height()-290;

				    $('#taulukkoPaa').css({'height': vph + 'px', 'overflow-y' : 'hidden'});

				    $("#verkko").tableHeadFixer({
					"left" : 1,
					'z-index': 0
				    }); 
				}

			});
		</script>
		<!-- Fixed Table -->


            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">


<div class="table-responsive" id="taulukkoPaa">
  <TABLE id="verkko" class="table table-bordered">
	<?php 

	$status = '';
	if(isset(Yii::app()->session['Lounastauko']))
	$status .= " AND status!='".Yii::app()->session['Lounastauko']."' ";
	if(isset(Yii::app()->session['MATKA']))
	$status .= " AND status!='".Yii::app()->session['MATKA']."' ";


	echo '<thead><TR>';
	echo '<TH>Nimi</TH>';

	for ($i = 1; $i <= $number; $i++) 
	{
		echo '<TH>'.$i.'</TH>';
	}
	echo '<TH>Yht.</TH>';
	echo '</TR></thead>';

	$criteria = new CDbCriteria();
	$criteria->condition = " aktiivinen=1 ";

	// <-- Return order etu ja sukunimella
	$site = Yii::app()->createController('Site');
	$criteria = $site[0]->etuSukunimiCriteria($criteria);
	//     Return order etu ja sukunimella -->

	// <-- Tyoryhmat
	$tt = Yii::app()->createController('Tyontekijat');
	$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper(null);
	$ids = implode(",", $tt_arr);
	if( count($tt_arr) > 0 ){
		$criteria->addCondition (" id IN ($ids)");
	}
	//    Tyoryhmat -->

	$t = Tyontekijat::model()->findAll($criteria);


	foreach($t as $v)
	{
		$yht = 0;
		echo '<TR>';
		echo '<TD>'.$this->etuSukunimi($v->id).'</TD>';

		for ($i = 1; $i <= $number; $i++) 
		{
			$thisDate = $year.'-'.$month.'-'.$i;
			$date = $i.'.'.$month;

			$getTime = $this->toteutuneetByPvm($v->id, $thisDate, $status);
			$tot[$i] = $this->sprint($getTime);
			$yht += $getTime;

			$cl = "";
			if(isset($getTime) and (int)$getTime < 18000 and (int)$getTime > 0)
				$cl = "btn btn-xs btn-warning";
			elseif(isset($getTime) and (int)$getTime > 28800 and (int)$getTime > 0)
				$cl = "btn btn-xs btn-danger";

			echo '<TD class="text-small" style="font-size:90%"><span class="'.$cl.'">'.$tot[$i].'</span></TD>';

		}
		echo '<TD class="text-small"><b>'.sprint($yht).'</b></TD>';
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
        	//console.log(data);
	 	window.location.reload();
    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
 	   }
        });


  });

});
</script>
