<?php
	      $bod = json_decode($this->tv4head($did, $tid, $pvm));
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

  echo json_encode($bod);
?>
