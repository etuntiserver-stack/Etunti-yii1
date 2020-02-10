<?php
ini_set('memory_limit', '512M');
//print_r($_SESSION['muistin']);
//exit;
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/tyovuorot_v4.css">
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tvuoroot_v4.js"></script>
<?php echo CHtml::button(Yii::t('main', 'Lähetä'),array('class'=>'btn btn-lg btn-success','id'=>'lahetaTyovuoroja', 'style' => 'display: none; position:fixed; bottom: 0; right: 0')); ?>
<!-- Fixed Table -->
<!-- http://www.jqueryscript.net/table/jQuery-Plugin-For-Fixed-Table-Header-Footer-Columns-TableHeadFixer.html -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/tableHeadFixer.js"></script>
<script>
	$(document).ready(function() {
		window.onload = function(event) { resizeDiv(); }
		//window.onresize = function(event) { resizeDiv(); }
			function resizeDiv() {
		    	    vpw = $(window).width()-100; 
		    	    vph = $(window).height()-100;
			    $('#parent').css({'height': vph + 'px', 'overflow-y' : 'hidden'});
			    $("#fixTable").tableHeadFixer({
				"left" : 1,
				"foot" : 1,
				'z-index': 0
		    	    }); 
			}
		});
</script>
<!-- Fixed Table -->

<!-- TV laatiko -->
<div id="showres" class="modal" aria-hidden="true" data-backdrop="static" data-keyboard="false"></div>
<!-- TV laatiko -->
<div id="yht_tv"></div>
<div class="" id="parent">
 <table class="table table-bordered" id="fixTable">
 <thead>
 <tr>
 <th class="text-center" width="1"></th>
 <th>
	<span class="nimi">VARAUS</span>
 </th>
 <?php
 foreach($tt as $tid => $arr){
	echo '<th style="z-index: 999;"><div class="text-center laatiko_td">'.$arr['etusukunimi'].'</div></th>';
 }
 ?>
 </tr>
 </thead>
 <tbody>
 <?php 
 $f = date("d.m.Y", strtotime($from));
 while (strtotime($f) <= strtotime($to)){
	$did 		= date("Ymd", strtotime($f));
	$columnDate 	= date("N/d.m",strtotime($f));
	$explColDate 	= explode("/",$columnDate);
	$tr_pyhat 	= '';
	$ispyha 	= '';
	if( isset($pyhapaivat[$f]['su']) or isset($pyhapaivat[$f]['vp']) or isset($pyhapaivat[$f]['el']) ){
		$tr_pyhat = 'tr_pyhat';
		$ispyha = ' <i class="text-warning fa fa-flag-o" aria-hidden="true" style="font-size:150%" data-toggle="tooltip" data-placement="bottom" title="'.Yii::t('main', 'Pyhäpäivä').'"></i>';
	}
	if(isset($pyhapaivat[$f]['el'])){
		$ispyha = ' <i class="text-warning fa fa-flag-o" aria-hidden="true" style="font-size:150%" data-toggle="tooltip" data-placement="bottom" title="'.Yii::t('main', 'Erikoislauantai').'"></i>';
	}
	echo '<tr class="'.$tr_pyhat.'">';
	echo '<td style="z-index: 999; min-width: 100px"><div class="text-center laatiko_td"><b>'.$arrDate[$explColDate[0]].'</b>, '.$explColDate[1].$ispyha.'</b></div></td>';
		// <-- VARAUKSET
		echo '<td>';
		echo '<div id="'.$did.'_'.$tid.'" class="latikkoAsetukset" pvm="'.$f.'" tid="0">';
			$pvm_vaaraus_yhteensa 	= 0;
			$last_vaaraus_loppu 	= null;
			if (isset($tv_arr[0][$f])) {
				ksort($tv_arr[0][$f]);
				foreach ($tv_arr[0][$f] as $k => $v) {
					foreach ($v as $v2) {
						$alku_ts = strtotime($v2['alku']);
						if ($last_vaaraus_loppu !== null && $alku_vaaraus_ts - ($loppu_vaaraus_ts = strtotime($last_vaaraus_loppu)) > 0) {
							echo "<p class=\"reika-warning text-center\">Vapaa-aika: " . $this->sprint($alku_vaaraus_ts - $loppu_vaaraus_ts) . "</p>";
						}
						echo "<p>{$v2['tv_edit']}</p>";
						$pvm_vaaraus_yhteensa 	+= $v2['tv_kesto'];
						$last_vaaraus_loppu 	= $v2['loppu'];
					}
				}
			}
			if ($pvm_vaaraus_yhteensa > 0)
				echo '<div class="pull-right pvm_yht">' . $this->sprint($pvm_vaaraus_yhteensa) . '</div>';
		echo '</div>';
		echo '</td>';
		//     VARAUKSET -->
		foreach($tt as $tid => $item){
		echo '<td>';
		echo '<div id="'.$did.'_'.$tid.'" class="latikkoAsetukset" pvm="'.$f.'" tid="'.$tid.'">';
			$pvm_yhteensa 	= 0;
			$last_loppu 	= null;
			if (isset($tv_arr[$tid][$f])) {
				ksort($tv_arr[$tid][$f]);
				foreach ($tv_arr[$tid][$f] as $k => $v) {

					foreach ($v as $v2) {
						$alku_ts = strtotime($v2['alku']);
						if ($last_loppu !== null && $alku_ts - ($loppu_ts = strtotime($last_loppu)) > 0) {
							echo "<p class=\"reika-warning text-center\">Vapaa-aika: " . $this->sprint($alku_ts - $loppu_ts) . "</p>";
						}
						echo "<p>{$v2['tv_edit']}</p>";
						$pvm_yhteensa 	+= $v2['tv_kesto'];
						$last_loppu 	= $v2['loppu'];
					}
				}
			}
			if ($pvm_yhteensa > 0)
				echo '<div class="pull-right pvm_yht">' . $this->sprint($pvm_yhteensa) . '</div>';
		echo '</div>';
		echo '</td>';
		}
	echo '</tr>';
	if(date('N', strtotime($f)) == 7){
	$vko 	= date("W",strtotime($f));
	$year 	= date("Y",strtotime($f));
	echo '<tr>';
  		echo '<td class="text-center myBgColors viikkoRivi"><b>'.Yii::t('main', 'Viikko').' '.date("W",strtotime($f)).' <i class="fa fa-arrow-up" aria-hidden="true"></i>
</b></td>';
		echo '<td class="myBgColors viikkoRivi"></td>';
		foreach($tt as $tid => $item){
		echo '<td class="myBgColors viikkoRivi">';
		echo '<div id="vko_'.$did.'_'.$tid.'" class="link viikkolaatiko text-center" pvm="'.$f.'" tid="'.$tid.'"><i class="fa fa-2x fa-eye"></i></div>';
		echo '</td>';
		}
	echo '</tr>';
	}
	$f = date ("d.m.Y", strtotime("+1 day", strtotime($f)));
 }
 ?>
 </table>
</div>

<script type="text/javascript">
$(document).ready(function(){

});
</script>
