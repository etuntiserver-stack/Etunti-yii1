<?php



  $explStr = explode("//", $str);
  $rivi = $explStr['0'];
  $aloitan = $explStr['1'];
  $loppui = $explStr['2'];
  $kohde = $explStr['3'];
  $did = $explStr['4'];
  $tid = $explStr['5'];
  $muutos = $explStr['6'];
  $kesto = $explStr['7'];
  $idKid = $explStr['8'];


  $m = Mobile::model()->findbypk($idKid,array("select"=>"hyvaksytty"));


    $chk[$rivi] = '';
  if(!empty($m->hyvaksytty))
  {
    $chk[$rivi] = 'checked';
  }

  if(isset($muutos) and $muutos == true){
    $mod = 'update';
    $ap = ' <i class="link text-danger poistaTot" rivi="'.$rivi.'" for="'.$did.'_'.$tid.'">AP</i>';
  } else {
    $mod = 'create';
    $ap = '';
  }

 	  $strlen = strlen($kohde);
	   if($strlen > 18)
	    $kohde = substr($kohde,0,18).'..';
	   else
	    $kohde = $kohde;

	   if($aloitan > 0 and $loppui > 0)
	    $al = date("H:i",strtotime($aloitan)).'-'.date("H:i",strtotime($loppui));
	   else
	    $al = '';

	   echo '
	   <div id="'.$rivi.'_'.$did.'_'.$tid.'" class="fullRivi form-inline">';
	   echo '&nbsp;
		<span class="form-group">
			<input type="checkbox" class="chckbxHyvaksynta" id="hyv_'.$rivi.'" '.$chk[$rivi].' kuka="'.Yii::app()->user->username.'///'.date('d.m.Y').'">&nbsp; 
		</span><span class="form-group">
			<i class="form-group link totRivi" mod="'.$mod.'" id="tot_'.$rivi.'">'.$al.' '.$kohde.'</i>'.$ap.'
		</span>
	   </div>';

?>
