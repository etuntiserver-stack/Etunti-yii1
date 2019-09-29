<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/tyovuorot_v3.css">
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tvuoroot.js"></script>
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
<div id="showres" class="modal fade" aria-hidden="true" data-backdrop="static" data-keyboard="false"></div>
<!-- TV laatiko -->

<div id="temaus-modal" class="modal fade" tabindex="-1" role="dialog">
   <!-- Admin Form Popup -->
   <div id="modal-form" class=" popup-basic popup-lg admin-form mfp-with-anim mfp-hide">
     <div class="panel">
       <div class="panel-heading">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size:170%">
			<span aria-hidden="true">&times;</span>
		</button>
         <span class="panel-title"></span>
       </div>
       <!-- end .panel-heading section -->

       <form method="post" action="/" id="comment">
         <div class="panel-body p25">
         </div>
         <!-- end .form-body section -->

         <div class="panel-footer">
		<button type="button" class="button btn-default" data-dismiss="modal" aria-label="Close">Sulje</button>
           <!--<button type="submit" class="button btn-primary">Post Comment</button>-->
         </div>
         <!-- end .form-footer section -->
       </form>
     </div>
     <!-- end: .panel -->
   </div>
   <!-- end: .admin-form -->
</div>

<?php
  $arr = array();
  $site = Yii::app()->createController('Site');

  foreach($model as $key=>$item){
	$arr[(isset($item->tt->id))?$this->etuSukunimi($item->tt->id):''][$item->pvm][] = array(
		'id'	 => $item->id,
		'pvm'	 => $item->pvm,
		'alku'	 => $item->alku,
		'loppu'	 => $item->loppu,
		'tid'	 => $item->tid,
		'kohde'	 => $item->kohde,
		'osoite' => (!empty($item->osoite))? $item->osoite : (isset($item->kohteet->osoite))? $item->kohteet->osoite : ''
	);
  }
  ksort($arr);

  echo '<div class="table-responsive" id="parent">';
  echo '<table class="table table-bordered" id="fixTable">';
  $f = date("d.m.Y", strtotime($from));
  echo '<thead><tr>';
  echo '<th>Nimi</th>';
  while (strtotime($f) <= strtotime($to)) {
	echo '<th>'.$f.'</th>';
	$f = date ("d.m.Y", strtotime("+1 day", strtotime($f)));
  }
  echo '</tr></thead>';
  foreach($arr as $key=>$item){
	echo '<tr>';
	echo '<th>'.$key.'</th>';
	$f = date("d.m.Y", strtotime($from));
	while (strtotime($f) <= strtotime($to)) {
		$sum = 0;
		$pvm = $f;
		$did = date("Ymd", strtotime($f));
		$onkoMennyt = '';
		if($did < date("Ymd")){ $onkoMennyt = 'mennytPaivat'; }

		echo '<td class="laatiko_td">';
		foreach($item as $k=>$p){
			$tid = $p[0]['tid'];
			if( $k == $f){
			head($did, $tid, $pvm);
			echo  '<div id="'.$did.'_'.$tid.'">
			<div style="position:relative" class="latikkoAsetukset '.$onkoMennyt.'" pvm="'.date("d.m.Y",strtotime($pvm)).'" tid="'.$tid.'">';
				foreach($p as $k1=>$tvVal){
				   	echo '<div id="'.$tvVal['id'].'_'.$did.'_'.$tid.'" class="fullRivi">';
					echo '<span class="tv_edit" id="tv_'.$tvVal['id'].'">';
					echo $tvVal['alku'].'-'.$tvVal['loppu'].'  '.$tvVal['osoite'];
				   	echo '</span>';
				   	echo '</div>';
				}

			echo '</div></div>';
			}
		}
		echo '</td>';
       	        $f = date ("d.m.Y", strtotime("+1 day", strtotime($f)));
	}
	echo '</tr>';
  }
  echo '</table>';
  echo '</div>';





  function  head($did, $tid, $pvm){
	echo '
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
  }
?>
