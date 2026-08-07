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
  $ashyv = $explStr['9'];
  $tietoja = $explStr['10'];
  $sairaus = $explStr['11'];

     $riviTietoja = '';
  if(!empty($tietoja) and isset($_POST['tulosta']))
     $riviTietoja = '<br><br>&nbsp;&nbsp;&nbsp;<b>'.Yii::t('main','Tietoja: ').'</b> '.$tietoja.'<hr>';

  $spl = $this->sairausMerkki($sairaus);

  if(empty($loppui))
  {
    echo 'loppuaika puutuu<br>';

  } else {

	$asiakas_hyvaksy = '';
  if(isset($ashyv) and !empty($ashyv)){
    $exp = explode("_", $ashyv);
      if(isset($exp[0]) and $exp[0] == 0)
	$asiakas_hyvaksy = '<b class="fa fa-share pull-right text-warning"></b>';
      if(isset($exp[0]) and $exp[0] == 1)
  	$asiakas_hyvaksy = '<b class="glyphicon glyphicon-ok pull-right text-success"></b>';
      if(isset($exp[0]) and $exp[0] == 2)
	$asiakas_hyvaksy = '<b class="glyphicon glyphicon-warning-sign pull-right text-danger"></b>';
  }

  $m = Mobile::model()->findbypk($idKid,array("select"=>"hyvaksytty,admin"));

    $admin = '';
  if(isset($m->admin) and $m->admin == 1)
  {
    $admin = 'text-danger text-uppercase';
  }

    $chk[$rivi] = '';
  if(!empty($m->hyvaksytty))
  {
    $chk[$rivi] = 'checked';
  }

    $mod = '';

  if(isset($muutos) and $muutos == true  and !isset($_POST['tulosta'])){
    $mod = 'update';
    $ap = ' <i class="link text-danger poistaTot" rivi="'.$rivi.'" for="'.$did.'_'.$tid.'">AP</i>';
  } else {
    $mod = 'create';
    $ap = '';
  }

 	  $strlen = mb_strlen($kohde, 'UTF-8');
	   if($strlen > 22)
	    $kohde = mb_substr($kohde, 0, 22, 'UTF-8').'..';
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
			<i class="form-group link totRivi '.$admin.'" mod="'.$mod.'" id="tot_'.$rivi.'">'.$al.$spl.' '.$kohde.'</i>
			'.$ap.'
		</span>
		'.$asiakas_hyvaksy.'
		'.$riviTietoja.'
	   </div>';

  } // if empty loppui
?>
