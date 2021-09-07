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
	1=>Yii::t('main', 'Tammikuu'),
	2=>Yii::t('main', 'Helmikuu'),
	3=>Yii::t('main', 'Maaliskuu'),
	4=>Yii::t('main', 'Huhtikuu'),
	5=>Yii::t('main', 'Toukokuu'),
	6=>Yii::t('main', 'Kesäkuu'),
	7=>Yii::t('main', 'Heinäkuu'),
	8=>Yii::t('main', 'Elokuu'),
	9=>Yii::t('main', 'Syyskuu'),
	10=>Yii::t('main', 'Lokakuu'),
	11=>Yii::t('main', 'Marraskuu'),
	12=>Yii::t('main', 'Joulukuu')
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


	<h2 class="myBgColors p10"> <i class="fa fa-calendar-o"></i> <?php echo Yii::t('main', 'Kuukausinäkymä'); ?> 

	&nbsp;&nbsp;&nbsp;
	  <a href="kk?pvm=<?php echo $previous; ?>" class="text-warning">
	  <<</a> <?php echo $months[$month].' '.$year; ?> 
 	  <a href="kk?pvm=<?php echo $next; ?>" class="text-warning">>></a>

	</h2>

        <!-- loppu: .tray-center -->
        </div>




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
				    vph = $(window).height()-240;

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

	echo '<TR>';
	echo '<TH>Nimi</TH>';

	for ($i = 1; $i <= $number; $i++) 
	{
		echo '<TH>'.$i.'</TH>';
	}
	echo '<TH>Yht.</TH>';
	echo '</TR>';

	$criteria=new CDbCriteria;
	$criteria->condition =" aktiivinen=1  ";

	// <-- Return order etu ja sukunimella
	$site = Yii::app()->createController('Site');
	$criteria = $site[0]->etuSukunimiCriteria($criteria);
	//     Return order etu ja sukunimella -->

	// <-- Tyoryhmat
	$tt = Yii::app()->createController('Tyontekijat');
	$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper(null);
	$ids = implode(",", $tt_arr);
	if( count($tt_arr) > 0 ){
		$criteria->addCondition (" id IN ($ids) ");
	}
	//     Tyoryhmat -->


	$t = Tyontekijat::model()->findAll($criteria);
	$tids = [];
	foreach($t as $v){
		$tids[$v->id] = $v->id;
	}

	$from = date("Y-m-d", strtotime($year.'-'.$month.' first day of this month'));
	$to = date("Y-m-d", strtotime($year.'-'.$month.' last day of this month'));
	$getAll = $this->TidfromtoTyovuoroWithVirtual($from, $to, $tids, true, true, [3]);
	$summ_all = 0;
	foreach($tids as $v)
	{
		$yht = 0;
		echo '<TR>';
		echo '<TD>'.$this->etuSukunimi($v).'</TD>';

		for ($i = 1; $i <= $number; $i++) 
		{
			$thisDate = date("d.m.Y", strtotime($year.'-'.$month.'-'.$i));
			$date = $i.'.'.$month;

			$tot[$i] = (isset($getAll[$v][$thisDate]))? $getAll[$v][$thisDate] : 0;
			$yht += (int)$tot[$i];

			$cl = "";
			if((int)$tot[$i] < 18000)
				$cl = "btn btn-xs btn-warning";
			elseif((int)$tot[$i] > 28800)
				$cl = "btn btn-xs btn-danger";

			echo '<td>';
			if(!empty($tot[$i]))
			{
				echo '<span class="link text-small '.$cl.'" tyle="font-size:90%" pvm="'.date("Y-m-d", strtotime($thisDate)).'" did="'.date("Ymd", strtotime($thisDate)).'_'.$v.'" tid="'.$v.'">'.$this->num($tot[$i]).'</span>';
				$summ_all += $this->num($tot[$i]);
			}
			echo '</td>';

		}
		echo '<TD class="text-small"><b>'.$this->sprint($yht).'</b></TD>';
		echo '<TR>';
	}
	?>
  </TABLE>
</div>
Yhteensä: <?=$summ_all?>

                 </div>
                </div>
              </div>
            </div>


	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/tyovuorot_v4.css">
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tvuoroot_v4.js"></script>

     <div id="temaus-modal" class="modal fade" tabindex="-1" role="dialog">
        <!-- Admin Form Popup -->
        <div id="modal-form" class=" popup-basic popup-xl admin-form mfp-with-anim mfp-hide">
          <div class="panel">
            <div class="panel-heading">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size:170%">
				<span aria-hidden="true">&times;</span>
			</button>
              <span class="panel-title"></span>
            </div>
            <!-- end .panel-heading section -->

              <div class="panel-body p25">


              </div>
              <!-- end .form-body section -->

              <div class="panel-footer">
		<button type="button" class="button btn-default" data-dismiss="modal" aria-label="Close">Sulje</button>
                <!--<button type="submit" class="button btn-primary">Post Comment</button>-->
              </div>
              <!-- end .form-footer section -->
          </div>
          <!-- end: .panel -->
        </div>
        <!-- end: .admin-form -->
     </div>


<script>
$(document).ready(function() {

  $(".link").click(function(){

	var pvm = $(this).attr('pvm');
	var did = $(this).attr('did');
	var tids = [];
	tids.push($(this).attr('tid'));
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/did4?from='+pvm+'&to='+pvm,
           type: "POST",
	   data: { tids : tids },
           success: function(data){
		data = JSON.parse(data);
		console.log(data);
		$('#temaus-modal').modal().find('.panel-body').html('<div id="' + did + '"></div>');
		$.tv_arr_update(data);
           }
        });

  });

});
</script>
