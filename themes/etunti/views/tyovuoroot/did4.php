<?php
	$bod = '';
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

              foreach($tv_arr as $tvVal){ 
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

  echo json_encode($bod);
?>
