<?php
ini_set('memory_limit', '512M');
//print_r($_SESSION['muistin']);
//exit;
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/tyovuorot_v4.css">
<?php
if( isset($_SESSION['skrollaus']) )
	echo '<style>td .latikkoAsetukset{max-height: none;}</style>';
?>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tvuoroot_v4.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/select_valiko.js"></script>
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
<?php $odotus_ikooni = '<img src="../../lib/img/etusivu/odotus.gif" height="20px">'; ?>
<div id="yht_tv"></div>
<div class="" id="parent">
 <table class="table table-bordered" id="fixTable">
 <thead>
 <tr>
 <th class="bg-default text-center" width="1"></th>
 <th class="bg-default">
	<span class="nimi">VARAUS</span>
 </th>
 <th class="bg-default">
	<span class="nimi">VAPAAT TYÖVUOROT</span>
 </th>
 <?php
 foreach($tt as $tid=>$item){
	echo '<th class="bg-default small" style="z-index: 999; vertical-align: middle;"><div class="text-center laatiko_td">
		<div>
		    	<a href="#" class="getTekijanTiedot '.((isset($tyosuhteet[$tid]['loppu']) and !empty($tyosuhteet[$tid]['loppu']) and date("Ymd", strtotime($tyosuhteet[$tid]['loppu'])) < date("Ymd"))? 'text-danger' : '').'" for="'.$tid.'">';
			// <-- Kuva
			if(
				isset(Yii::app()->user->domain) 
				and file_exists(dirname(Yii::app()->getBasePath()).'/img/tekijat/'.strtolower(Yii::app()->user->domain).'/'.$tid.".jpg") 
			){
				echo '<img src="../../img/tekijat/'.strtolower(Yii::app()->user->domain).'/'.$tid.".jpg".'" alt="avatar" class="mw40 br64 mr15">';
			} else {
				echo '<img src="../../img/tekijat/noname.jpg" alt="avatar" class="mw40 br64 mr5">';
			}
			//     Kuva -->
			echo ' <span class="small">'.$item['etusukunimi'].'</span></a>
		</div>
	</th>';
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
	echo '<td class="bg-default p15" style="z-index: 999;"><div class="text-center laatiko_td"><b>'.$arrDate[$explColDate[0]].'</b><br>'.$explColDate[1].$ispyha.'</b></div></td>';
		// <-- VARAUKSET
		echo '<td>';
		echo '<div id="'.$did.'_0" class="latikkoAsetukset" pvm="'.$f.'" tid="0"><span class="odotus">'.$odotus_ikooni.'</span></div>';
		echo '</td>';
		//     VARAUKSET -->
		// <-- VAPAAT TYÖVUOROT
		echo '<td>';
		echo '<div id="'.$did.'_'.Tyovuoroot::OPEN_SHIFT_TID.'" class="latikkoAsetukset" pvm="'.$f.'" tid="'.Tyovuoroot::OPEN_SHIFT_TID.'"><span class="odotus">'.$odotus_ikooni.'</span></div>';
		echo '</td>';
		//     VAPAAT TYÖVUOROT -->
		foreach($tt as $tid => $item){
			echo '<td>';
			echo '<div id="'.$did.'_'.$tid.'" class="latikkoAsetukset" pvm="'.$f.'" tid="'.$tid.'"><span class="odotus">'.$odotus_ikooni.'</span></div>';
			echo '</td>';
		}
	echo '</tr>';
	if(date('N', strtotime($f)) == 7){
		echo '<tr class="sunday" sunday="'.$f.'" tids="'.json_encode(array_values($haku_tids)).'">';
  			echo '<td class="text-center myBgColors viikkoRivi"><b>'.Yii::t('main', 'Viikko').' '.date("W",strtotime($f)).' <i class="fa fa-arrow-up" aria-hidden="true"></i>
</b></td>';
			echo '<td class="myBgColors viikkoRivi"></td>';
			echo '<td class="myBgColors viikkoRivi"></td>';
			foreach($tt as $tid => $item){
				echo '<td class="myBgColors viikkoRivi">';
				echo '<div class="viikkolaatiko text-center" id="vko_'.$did.'_'.$tid.'""><span class="odotusweeklaskennan">'.$odotus_ikooni.'</span></div>';
				echo '</td>';
			}
	echo '</tr>';
	}
	$f = date ("d.m.Y", strtotime("+1 day", strtotime($f)));
 }
 ?>
 </table>
</div>

<div id="temaus-modal" class="modal fade" tabindex="-1" role="dialog">
        <div id="modal-form" class=" popup-basic popup-lg admin-form mfp-with-anim mfp-hide">
          <div class="panel">
            <div class="panel-heading">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size:170%">
				<span aria-hidden="true">&times;</span>
			</button>
              <span class="panel-title"></span>
            </div>
            <form method="post" action="/" id="comment">
              <div class="panel-body p25">
              </div>
              <div class="panel-footer">
		<button type="button" class="button btn-default" data-dismiss="modal" aria-label="Close">Sulje</button>
              </div>
            </form>
          </div>
        </div>
</div>

<!-- Täytetään tauluu -->
<?php echo $this->tv_arrJava($from, $to, $haku_criteria, $haku_tids, 'tt', $customer_tickets ?? []); ?>
