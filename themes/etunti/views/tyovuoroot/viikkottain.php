<?php

$paivat=array(
	1=>Yii::t('main', 'Maanantai'),
	2=>Yii::t('main', 'Tiistai'),
	3=>Yii::t('main', 'Keskiviikko'),
	4=>Yii::t('main', 'Torstai'),
	5=>Yii::t('main', 'Perjantai'),
	6=>Yii::t('main', 'Lauantai'),
	7=>Yii::t('main', 'Sunnuntai'),
	);

//$wkMaara = 53;

$year = (isset($_GET['year'])) ? $_GET['year'] : date("Y");
$week = (isset($_GET['week'])) ? $_GET['week'] : date('W');
$week = sprintf("%02d", $week);

		// <-- Previous Next Weeks
	   	$tv = Yii::app()->createController('Tyovuoroot');
	   	$getWeeks = $tv[0]->previousNextWeeks($year,$week);

		$previousWeek 	= $getWeeks['previousWeek'];
		$previousYear	= $getWeeks['previousYear'];
		$nextWeek 	= $getWeeks['nextWeek'];
		$nextYear 	= $getWeeks['nextYear'];
		//     Previous Next Weeks -->
?>
<style>
.mennytPaivat{
	opacity: 0.4;
}
td .latikkoAsetukset{
	min-width: 70px;
	width: 200px;
	white-space: normal;
}
.forCut, .forCopy{ 
	display: none;
}
.mplus, .mcut, .clear, .trash{ 
	display: none;
}
td:hover .mplus, td:hover .mcut, td:hover .clear, td:hover .trash{
	display : block;
}
td .tp{
	//position:absolute;
}
.fullRivi{
	height: 100%;
	margin-bottom: 2px;
	border:1px #ccc solid;
	padding:3px 7px;
	background: white;
	border-radius:5px;
}
.luominen, .valitseKokopaiva{
	display: none;
}
.table tbody>tr>td{
    	vertical-align: top;
}
.table{
    height: 100%;
}
.oikeallaPlusV{
	display: none;
}
</style>


<div id="checkedLaheta"></div>


        <!-- begin: .tray-center -->
        <div class="tray-center">



<h2 class="myBgColors p15"> 
    <div class="form-inline">
     <div class="form-group">
   	<?php echo Yii::t('main', 'Työvuorojen lähetys'); ?>&nbsp;&nbsp;&nbsp;
     </div><div class="form-group">


  <b>
  <a href="<?php echo $_SERVER['PHP_SELF'].'?week='.$previousWeek.'&year='.$previousYear; ?>"><i class="fa fa-arrow-left btn btn-default"></i></a> 
  <span class="btn btn-default"><?php echo date('d.m.Y',strtotime($year ."W".$week .'1')).' - '.date('d.m.Y',strtotime($year ."W". $week .'7')); ?></span>
  <a href="<?php echo $_SERVER['PHP_SELF'].'?week='.$nextWeek.'&year='.$nextYear; ?>"><i class="fa fa-arrow-right btn btn-default"></i></a> 
  </b>


     </div><div class="form-group pull-right row">
      <div class="form-inline">
 	<div class="form-group">
          <?php echo CHtml::button(Yii::t('main', 'Valitse kaikki'),array('target'=>'_blank','class'=>'btn btn-default valitseKaikki')); ?>&nbsp;
 	<div class="form-group">
          <?php echo CHtml::button(Yii::t('main', 'Lähetä'),array('target'=>'_blank','class'=>'btn btn-default','id'=>'lahetaKaikkille')); ?> &nbsp;
 	</div><?php /*<div class="form-group">
      	  <form action="#" target="_blank" method="POST">
           <input type="submit" name="tulosta" class="btn btn-default" value="PDF">
          </form>
	</div>*/ ?>
      </div>
     </div>
    </div>
</h2>

        <!-- loppu: .tray-center -->
        </div>





		<!-- Fixed Table -->
		<!-- http://www.jqueryscript.net/table/jQuery-Plugin-For-Fixed-Table-Header-Footer-Columns-TableHeadFixer.html -->
		<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/tableHeadFixer.js"></script>

		<style>	
			#fixTable {
				width: 1800px !important;
			}
		</style>

		<script>
			$(document).ready(function() {
				window.onload = function(event) { resizeDiv(); }
				//window.onresize = function(event) { resizeDiv(); }

				function resizeDiv() {
				    vpw = $(window).width()-100; 
				    vph = $(window).height()-250;

				    $('#lahetysTable').css({'height': vph + 'px', 'overflow-y' : 'hidden'});

				    $("#fixTable").tableHeadFixer({
					"left" : 1,
					'z-index': 99999
				    }); 
				}

			});
		</script>
		<!-- Fixed Table -->






            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">


<div class="table-responsive" id="lahetysTable">
  <table class="table table-bordered table-striped small" id="fixTable">
  <thead>
  <tr>
  <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
  <?php
/*
  if($week < 10) {
    $week = ($week/10)*10;
  }
*/

  $asetukset = Asetukset::model()->findByPk(1);
  for($day= 1; $day <= 7; $day++) {
    $d = strtotime($year ."W". $week . $day);
    echo "<th>". $paivat[date('N', sprintf("%02d", $d))] ."<br>". date('d.m.Y', $d) ."</th>";
  }
  ?>
  </tr>
  </thead>
  <?php
  $criteria = new CDbCriteria();

		// <-- Return order etu ja sukunimella
		$site = Yii::app()->createController('Site');
		$criteria = $site[0]->etuSukunimiCriteria($criteria);
		//     Return order etu ja sukunimella -->

		// <-- Tyoryhmat
		$checkOikeus = "tyoryhmat_4_".Yii::app()->user->adminStatus;
		$site = Yii::app()->createController('Site');
		if( $site[0]->checkOikeusFields($checkOikeus) == 0 ){
			$tt = Yii::app()->createController('Tyontekijat');
			$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper();
			$ids = implode(",", $tt_arr);
			if( count($tt_arr) > 0 ){
		       		$criteria->condition = " id IN ($ids) ";
			} else {
				$criteria->condition =" 1!=1 ";
			}
		} else {
			$criteria->condition =" aktiivinen=1  ";
		}
		//     Tyoryhmat -->


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
	echo '<td>'.$this->etuSukunimi($t->id).'<br>
	<input type="checkbox" for="'.$t->id.'" data-toggle="tooltip" data-placement="right" title="'.Yii::t('main', 'Määrittele lähetettäväksi').'"><br>';
	//CHtml::link(Yii::t('main', 'Lähetä'),'/index.php/tyovuoroot/laheta?tid='.$t->id.'&week='.$week.'&year='.$year.'&tulosta=false',array('target'=>'_blank','class'=>'btn btn-sm btn-success'))

	$file = $week.'_'.$year.'_'.$t->id.'.pdf';
	$path = Yii::app()->request->baseUrl."emails/tyovuorot/".Yii::app()->user->domain;
	if (file_exists($path.'/'.$file))
	{
		// <-- file_safe_opener
		$filepath = 'emails/tyovuorot/'.Yii::app()->user->domain.'/'.$file;
		echo CHtml::link(Yii::t('main', ' Lähetetty'),
			array('/site/file_safe_opener', 'filepath' => $filepath, 'ext' => 'pdf'),
			array('target'=>'_blank','class'=>'text-danger'
		));
		//     file_safe_opener -->
	}

	echo '</td>';


	  for($day= 1; $day <= 7; $day++) {
	    $d = strtotime($year ."W". $week . $day);
	    $did = $this->renderPartial('//tyovuoroot/did',array('pvm'=>date('d.m.Y',$d),'tid'=>$t->id,'from'=>'mobiili', 'asetukset'=>$asetukset),true);
	    echo "
	    <td><div class='latikkoAsetukset'>
	    ". json_decode($did, true) ."
	    </div></td>";
	  }

	echo '</tr>';
    }
  }
  ?>

</table>
</div>


                 </div>
                </div>
              </div>
            </div>







	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tvuoroot.js"></script>



<script type="text/javascript">
$(document).ready(function(){


$("#lahetaKaikkille").click(function(){

   if(checkChecked() !== '')
   setTimeout(function(){document.location.href = "laheta_k?week=<?php echo $week; ?>&year=<?php echo$year; ?>&tulosta=false&check="+checkChecked();},500);
   else
   alert('Valitse työntekijä');

});

function checkChecked() {
    var get = '';
    $('#lahetysTable table tr td input[type="checkbox"]:checked').each(function() {
        if ($(this).is(":checked")) {
            get += $(this).attr("for")+",";
        }
    });

    return get; 
}


});
</script>






<script type="text/javascript">
$(document).ready(function(){

valitseTaiPiilota();

function valitseTaiPiilota(){
 if(localStorage.getItem('tvLahetysChckBoxes') == 'all'){
  $('input:checkbox').prop("checked", true);
  $('.valitseKaikki').val('Piilota kaikki').removeClass('valitseKaikki').addClass('piilotaKaikki');

  $(document).delegate(".piilotaKaikki","click",function(){
	$('input:checkbox').prop("checked", false);
	localStorage.removeItem('tvLahetysChckBoxes');
        $(this).val('Valitse kaikki').addClass('valitseKaikki').removeClass('piilotaKaikki');
  });
 }
}

$(document).delegate(".valitseKaikki","click",function(){
	$('input:checkbox').prop("checked", true);
	localStorage.setItem('tvLahetysChckBoxes', 'all');
	valitseTaiPiilota();
});


});
</script>


<?php
/*
	<?php Yii::app()->clientScript->registerPackage('fixedTable'); ?>

<script type="text/javascript">
$(document).ready(function(){

$(function () {

    var tableHeight = function () {
        var $tableHeader = $('.dataTables_scrollHeadInner thead tr');
        return $(window).height() - 4 - ($tableHeader.length ? $tableHeader.height() : 0);
    };

    var dataTable = $('table').dataTable({
        sDom: 'frtiS',
        sScrollY: tableHeight(),
        sScrollX: '100%',
        bAutoWidth: true,
        bScrollCollapse: true,
        bPaginate: false,
        bFilter: false,
        bInfo: false,
        bSort: false,
        bDeferRender: true
    });

    var onResize = function () {
        var oSettings = dataTable.fnSettings();
        oSettings.oScroll.sY = tableHeight()-240; 
        dataTable.fnDraw();
    };

    var firstDraw = false;
    new FixedColumns(dataTable, {
        iLeftWidth: 100,
        fnDrawCallback: function () {
            if (firstDraw) return;
            firstDraw = true;
            onResize();
        }
    });

    $(window).resize(onResize);
});


});
</script>
*/
?>








