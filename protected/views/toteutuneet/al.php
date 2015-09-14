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

  if(isset($muutos) and $muutos == true){
    $mod = 'update';
    $ap = ' <span class="link text-danger poistaTot" rivi="'.$rivi.'" for="'.$did.'_'.$tid.'">AP</span>';
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
	   <div id="'.$rivi.'_'.$did.'_'.$tid.'" class="fullRivi">';
	   echo '&nbsp;<span class="link totRivi" mod="'.$mod.'" id="tot_'.$rivi.'">'.$al.' '.$kohde.'</span>'.$ap.'<br>
	   </div>';

?>
