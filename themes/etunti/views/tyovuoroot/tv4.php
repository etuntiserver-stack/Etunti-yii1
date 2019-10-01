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
/*
echo '<pre>';
print_r($tv_arr);
echo '</pre>';
exit;
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

  foreach($tt as $tid=>$item){
     $etusukunimi = $item['etusukunimi'];
     echo '<tr>';
     echo '<td class="laatiko_td" style="z-index: 999;"><div class="nimi">'.$etusukunimi.'</div></td>';
     $f = date("d.m.Y", strtotime($from));
     $pvm = date("d.m.Y", strtotime($from));
     $did = date("Ymd", strtotime($from));
     $onkoMennyt = '';
     if($did < date("Ymd")){ $onkoMennyt = 'mennytPaivat'; }

     while (strtotime($f) <= strtotime($to)) {
           echo '<td class="laatiko_td"><div id="'.$did.'_'.$tid.'">';
           if( isset($tv_arr[$tid][$f]) ){
	      $bod = json_decode($this->tv4head($did, $tid, $pvm));
	      $bod .= '<div style="position:relative" class="latikkoAsetukset '.$onkoMennyt.'" pvm="'.date("d.m.Y",strtotime($pvm)).'" tid="'.$tid.'">';

              foreach($tv_arr[$tid][$f] as $tvVal){ 
		$color = '#888';
		$bgcol = 'color:#333';
		// <-- Osoite
		$osoite = $tvVal['osoite'];
		// Osoite -->

		if( isset($loppu[0]) and empty($loppu[0]) and isset($loppu[1]) and ($this->num(strtotime($tvVal['alku'])-strtotime($loppu[1])) > 0) ){
			$valilyonti = strtotime($tvVal['alku'])-strtotime($loppu[1]);
			$reikatyyppi = 'reika-warning';
			$bod .= '<div class="reika '.$reikatyyppi.' text-center"><i class="glyphicon glyphicon-time"></i> Aika: '.$this->sprint($valilyonti).'</div>';
		}
		$kellot = '<b class="kellot">'.$tvVal['alku'].'-'.$tvVal['loppu'].'&nbsp; </b>';
	        $muokkaus =  '<i class="link tvikooni fa fa-pencil-square-o muistin" for="'.$tvVal['id'].'_'.$did.'_'.$tid.'" data-toggle="tooltip" data-placement="top" title="'.Yii::t('main', 'Valinta kopiontia tai siirtämistä varten').'"></i>';
		if(!empty($tvVal['tyoajanmerkinta'])){
			$expl = explode("/",$tvVal['tyoajanmerkinta']);
			if(isset($expl[1]) and !empty($expl[1])){
				$color = $expl[1];
				$bgcol = 'color:'.$color;
			}
		}
		if(!empty($tvVal['tyoajanlaatu']) and empty($osoite)){
			$expl1 = explode("/",$tvVal['tyoajanlaatu']);
			if(isset($expl1[1]) and !empty($expl1[1])){ $color = $expl1[1]; }
			$osoite = (isset($expl1[0])) ? '<div class="text-center"><b style="color:'.$color.'">'.$expl1[0].'</b></div>' : '';
			$kellot = '';
		}

	   	$bod .= '<div id="'.$tvVal['id'].'_'.$did.'_'.$tid.'" class="fullRivi" style="'.$bgcol.'">';
		$bod .= '<div class="pull-left ikoonintila" style="display:none;margin-right: 5px">'.$muokkaus.' </div>';
		$bod .= '<span class="tv_edit" id="'.$tvVal['id'].'">';
		$bod .= $kellot.$osoite;
	   	$bod .= '</span>';
	   	$bod .= '</div>';
		$loppu = array($tvVal['tyoajanlaatu'], $tvVal['loppu']);
              }
	      $bod .= '</div>';
	      echo $bod;
           }
           echo '</div></td>';
	$f = date ("d.m.Y", strtotime("+1 day", strtotime($f)));
     }
     echo '</tr>';
  }
  echo '</table></div>';


 
?>
