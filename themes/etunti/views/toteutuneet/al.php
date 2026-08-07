<?php
foreach($attributes as $str){

	//$explStr = explode("//", $str_n);
	$rivi = $str['id'];
	$aloitan = $str['aloitan'];
	$loppui = $str['loppui'];
	$kohde = $str['kohde_kannasta'];
	$did = date("Ymd", strtotime($str['aloitan']));
	$tid = $str['tid'];
	$muutos = (isset($str['kid']))? true : false;
	$tot_lu = (($muutos)?'tot':'lu');
	$kesto = strtotime($str['loppui'])-strtotime($str['aloitan']);
	$idKid = (isset($str['kid']))? $str['kid'] : $str['id'];
	$ashyv = $str['asiakas_hyvaksy'];
	$status = $str['status'];
	$tuoteID = $str['tuoteID'];

	$kesto = strtotime($loppui)-strtotime($aloitan);

	$tp = TuotteetPalvelut::model()->findbypk($tuoteID);
	if(isset($tp->nimike)) { $tuote = '<br><b class="text-success">'.$tp->nimike.'</b>'; } else { $tuote = ''; }

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

	$admin = '';
	if(isset($str['admin']) and $str['admin'] == 1)
	$admin = 'text-danger text-uppercase';

	$chk[$rivi] = '';
	if(!empty($str['hyvaksytty']))
	$chk[$rivi] = 'checked';

	$mod = '';
	if(isset($muutos) and $muutos == true  and !isset($_POST['tulosta'])){
		$mod = 'update';
		$ap = ' <i class="form-group link text-danger fa fa-refresh poistaTot" rivi="'.$rivi.'" for="'.$did.'_'.$tid.'" data-toggle="tooltip" title="'.Yii::t('main', 'Palauta alkuperäinen').'" aria-hidden="true"></i> ';
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

	if( date("d",strtotime($aloitan)) != date("d",strtotime($loppui)) ) 
	$isEripaivat = '<i class="fa fa-retweet text-danger" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" style="font-size: 130%" title="'.Yii::t('main', 'Päivämäärät  eivät täsmää').'"></i> '; 
	else 
	$isEripaivat = '';

	if($status == 3)
	$kohde .= ' <i class="p3 fa fa-hourglass text-info"></i>';

	if($status == 2)
	$kohde = 'MATKA <i class="p3 fa fa-bus text-warning"></i>';

	if($status == 10)
	$kohde = 'LOUNASTAUKO <i class="p3 fa fa-cutlery text-danger"></i>';

	echo '<div id="'.$tot_lu.'_'.$rivi.'_'.$did.'_'.$tid.'" class="fullRivi">';

		echo '
		<div class="pull-right">
			<span class="form-group mob_kesto" idKid="'.$idKid.'" kesto="'.$kesto.'">&nbsp;'.$this->sprint($kesto).'</span><br>
			'.$ap.'
	   	</div>';
		
		echo '
		<div class="form-group">
			<input type="checkbox" class="chckbxHyvaksynta" id="hyv_'.$rivi.'" '.$chk[$rivi].' tot_lu="'.$tot_lu.'" for="'.$did.'_'.$tid.'" kuka="'.Yii::app()->user->username.'///'.date('d.m.Y').'" >&nbsp; 
			'.$isEripaivat.'<i class="form-group link totRivi '.$admin.'" mod="'.$mod.'" id="'.$tot_lu.'_'.$rivi.'">'.$al.'<br>'.$kohde.'</i>
			'.$tuote.'
			'.$asiakas_hyvaksy.'
		</div>';

	echo '</div>';
}
?>
