<?php
		if(isset($from)){
			$pvm = date("d.m.Y",strtotime($pvm));
		}
		$site = Yii::app()->createController('Site');
       		$criteria = new CDbCriteria();
		$criteria->with = array('kohteet');
		$criteria->select = " id, tid, osoite, pvm, alku, loppu, tyoajanmerkinta, tyoajanlaatu";
		$criteria->order = "alku ASC"; //tt.$tt_order_1 ASC, 
		$criteria->condition = "
			DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d')='".date("Y-m-d", strtotime($pvm))."' 
			AND tid='".$tid."'
		";
		$tv = Tyovuoroot::model()->findAll($criteria);
		$tv_arr = array();
		foreach($tv as $arvo){
			$osoite = (!empty($arvo['osoite']))?$arvo['osoite']:(isset($arvo['kohteet']['osoite']))?$arvo['kohteet']['osoite']:'';
			$color = '#888';
			$bgcol = 'color:#333';
			if(!empty($arvo['tyoajanmerkinta'])){
				$expl = explode("/",$arvo['tyoajanmerkinta']);
				if(isset($expl[1]) and !empty($expl[1])){
					$color = $expl[1];
					$bgcol = 'color:'.$color;
				}
			}
			if(!empty($arvo['tyoajanlaatu'])){
				$expl1 = explode("/",$arvo['tyoajanlaatu']);
				if(isset($expl1[1]) and !empty($expl1[1])){ $color = $expl1[1]; }
				$arvo['osoite'] = (isset($expl1[0])) ? '<b class="tv_edit" id="'.$arvo['id'].'" style="color:'.$color.'">'.$expl1[0].'</b>' : '';
			} else {
				$arvo['osoite'] = '<span class="tv_edit" id="'.$arvo['id'].'" style="'.$bgcol.'">'.$arvo['alku'].'-'.$arvo['loppu'].' '.$osoite.'</span>';
			}
			$tv_arr[$arvo->tid][$arvo->pvm][] = $arvo['osoite'];
		} 
		$content = '';
		if(isset($tv_arr[$tid][$pvm])){
			foreach($tv_arr[$tid][$pvm] as $item){
				$content .= '<p>'.$item.'</p>';
			}
		}
  echo json_encode($content);
?>
