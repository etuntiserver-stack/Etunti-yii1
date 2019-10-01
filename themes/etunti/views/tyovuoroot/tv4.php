<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/tyovuorot_v4.css">
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tvuoroot_v4.js"></script>

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


<?php
  $site = Yii::app()->createController('Site');
  echo '<div class="" id="parent">';
  echo '<table class="table table-bordered" id="fixTable">';
  $f = date("d.m.Y", strtotime($from));
  echo '<thead><tr>';
  echo '<th>Nimi</th>';
  while (strtotime($f) <= strtotime($to)) {
	echo '<th style="z-index: 999;">'.$f.'</th>';
	$f = date ("d.m.Y", strtotime("+1 day", strtotime($f)));
  }
  echo '</tr></thead>';

  foreach($tt as $tid=>$item){
     $etusukunimi = $item['etusukunimi'];
     echo '<tr>';
     echo '<td class="laatiko_td" style="z-index: 999;"><div class="nimi">'.$etusukunimi.'</div></td>';
     $f = date("d.m.Y", strtotime($from));
     $pvm = date("d.m.Y", strtotime($from));
     $did = date("Ymd", strtotime($from));
     while (strtotime($f) <= strtotime($to)) {
           echo '<td class="laatiko_td"><div id="'.$did.'_'.$tid.'">';
           if( !isset($tv_arr[$tid][$f]) ){
           	echo json_decode($this->tv4head($did, $tid, $pvm));
           } else {
 	     $content = $this->renderPartial('//tyovuoroot/did4',array(
					'tv_arr' => $tv_arr[$tid][$f],
					'pvm'=>$pvm,
					'did'=>$did,
					'tid'=>$tid,
					'from'=>'tvuoro', 
					'kohteet_siivous'=>json_encode($kohteet_siivous), 
					'asiakas'=>$asiakas,
					'kohde'=>$kohde,
	     ), true);
	     echo json_decode($content);
           }
           echo '</div></div></td>';
	$f = date ("d.m.Y", strtotime("+1 day", strtotime($f)));
     }
     echo '</tr>';
  }
  echo '</table>';
?>
