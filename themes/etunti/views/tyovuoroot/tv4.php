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
  $tv_arr = array();
  foreach($tv as $val){
     $val['osoite'] = (!empty($val['osoite']))?$val['osoite']:(isset($val['kohteet']['osoite']))?$val['kohteet']['osoite']:'';
     $tv_arr[$val->tid][$val->pvm][] = $val;
  } 
  /*
  echo '<pre>';
  print_r($tv_arr);
  echo '</pre>';
  */
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

  foreach($tt as $ttVal){
     $etusukunimi = $ttVal->$tt_order_1.' '.$ttVal->$tt_order_2;
     echo '<tr>';
     echo '<td class="laatiko_td" style="z-index: 999;"><div class="nimi">'.$etusukunimi.'</div></td>';
     $f = date("d.m.Y", strtotime($from));
     $pvm = date("d.m.Y", strtotime($from));
     $did = date("Ymd", strtotime($from));
     $tid = $ttVal->id;
     $onkoMennyt = '';
     $sum = 0;
     if($did < date("Ymd")){ $onkoMennyt = 'mennytPaivat'; }
     while (strtotime($f) <= strtotime($to)) {
           echo '<td class="laatiko_td"><div id="'.$did.'_'.$tid.'">';
           echo json_decode(head($did, $tid, $pvm, $onkoMennyt));
           if( isset($tv_arr[$tid][$f]) ){
 	     $content = $this->renderPartial('//tyovuoroot/did4',array(
					'tv_arr' => $tv_arr[$tid][$f],
					'pvm'=>$pvm,
					'did'=>$did,
					'tid'=>$tid,
					'from'=>'tvuoro', 
					'kohteet_siivous'=>json_encode($kohteet_siivous), 
					'asiakas'=>$asiakas,
					'kohde'=>$kohde,
					'site_0' => $site[0],
	     ), true);
	     echo json_decode($content);
           }
           echo '</div></div></td>';
	$f = date ("d.m.Y", strtotime("+1 day", strtotime($f)));
     }
     echo '</tr>';
  }
  echo '</table>';


  function  head($did, $tid, $pvm, $onkoMennyt){
	$bod = '
	<div class="latikkolisatiedot_paa">
		<div class="latikkolisatiedot">
		 <div class="form-inline">
			<div class="form-group">
			   <span class="text-center" id="sum_tunnit_'.$did.'_'.$tid.'" style="text-align:center;opacity:0.6"></span>
			</div>
			<div class="kokopaiva form-group">
			   <span class="valitseKokopaiva link glyphicon glyphicon-th-large" pvm="'.date("d.m.Y",strtotime($pvm)).'" tid="'.$tid.'"></span>
			</div>
			<div class="plussamerkki form-group">
			   <span class="plussa link fa fa-plus luominen" pvm="'.date("d.m.Y",strtotime($pvm)).'" tid="'.$tid.'"></span>
			</div>
			<div class="form-group">
		   	   <i class="'.((isset($_SESSION['muistin']) and count($_SESSION['muistin']) > 0)?'mcut fa fa-exchange link':'forCut').'"" id="forCut_'.$did.'_'.$tid.'" style="margin-right: 5px"></i>
		 	</div><div class="form-group">
		   	   <i class="'.((isset($_SESSION['muistin']) and count($_SESSION['muistin']) > 0)?'mplus fa fa-copy link':'forCopy').'" id="forCopy_'.$did.'_'.$tid.'"></i> 
			</div>
		 </div>
		</div>
	</div>
	';
	$bod .= '<div style="position:relative" class="latikkoAsetukset '.$onkoMennyt.'" pvm="'.date("d.m.Y",strtotime($pvm)).'" tid="'.$tid.'">';
	return json_encode($bod);
  }
?>
