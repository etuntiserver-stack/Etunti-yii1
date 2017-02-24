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
  elseif(!empty($tietoja) and !isset($_POST['tulosta']))
     $riviTietoja =  '<br> <b class="fa fa-file-text-o text-warning" title="Tietoja"></b>';

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
    $ap = ' <i class="link text-danger fa fa-refresh poistaTot" rivi="'.$rivi.'" for="'.$did.'_'.$tid.'" data-toggle="tooltip" title="'.Yii::t('main', 'Palauta alkuperäinen').'" aria-hidden="true"></i>';
  } else {
    $mod = 'create';
    $ap = '';
  }

 	  $strlen = strlen($kohde);
	   if($strlen > 22)
	    $kohde = substr($kohde,0,22).'..';
	   else
	    $kohde = $kohde;

	   if($aloitan > 0 and $loppui > 0)
	    $al = date("H:i",strtotime($aloitan)).'-'.date("H:i",strtotime($loppui));
	   else
	    $al = '';

	   if( date("d",strtotime($aloitan)) != date("d",strtotime($loppui)) ) 
	    $isEripaivat = '<i class="fa fa-retweet text-danger" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" style="font-size: 130%" title="'.Yii::t('main', 'Päivämäärät  eivät täsmää').'"></i> '; 
	   else 
	    $isEripaivat = '';


	   echo '
	   <div id="'.$rivi.'_'.$did.'_'.$tid.'" class="fullRivi form-inline">
	   <div class="pull-right">'.$ap.'</div>';
	   echo '
		<span class="form-group">
			<input type="checkbox" class="chckbxHyvaksynta" id="hyv_'.$rivi.'" '.$chk[$rivi].' kuka="'.Yii::app()->user->username.'///'.date('d.m.Y').'" data-toggle="tooltip" data-placement="bottom" title="'.Yii::t('main', 'Hyväksy').'">&nbsp; 
		</span><span class="form-group">
			'.$isEripaivat.'<i class="form-group link totRivi '.$admin.'" mod="'.$mod.'" id="tot_'.$rivi.'">'.$al.$spl.'<br>'.$kohde.'</i>
		</span>
		'.$asiakas_hyvaksy.'
		'.$riviTietoja.'
	   </div>';

  } // if empty loppui
?>
