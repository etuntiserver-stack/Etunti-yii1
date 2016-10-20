	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php

$link = mysql_connect('localhost', 'root', '');
if (!$link) {
    die('Не удалось соединиться : ' . mysql_error());
}

// выбираем foo в качестве текущей базы данных
$db_selected = mysql_select_db('klaara', $link);
if (!$db_selected) {
    die ('Не удалось выбрать базу foo: ' . mysql_error());
}

/* Asiakaat ja kohteet */
/*
$sql = mysql_query("select * from table_53");
while($r = mysql_fetch_array($sql))
{

$id		= $r['Asiakasnumero'];
$asiakasnumero	= $r['Asiakasnumero'];
$yrityksen_nimi = $r['Nimi'];
$yhteyshenkilo	= $r['Yhteyshenkilo'];
$osoite		= $r['Katuosoite'];
$kaupunki	= $r['Kaupunki'];
$postinumero	= $r['Postinumero'];
$puhelin	= $r['Puhelin'];
$sahkoposti	= $r['sahkoposti'];


if($r['Laskukanava'] == 'Posti')
  $laskutus_kanava = 'posti';
else if($r['Laskukanava'] == 'Sähköposti')
  $laskutus_kanava = 'sahkoposti';
else if($r['Laskukanava'] == 'Verkkolasku')
  $laskutus_kanava = 'verkkolasku';

$me 		= explode("/", $r['Maksuehto']);
if(isset($me[0]))
	$maksuehto	= preg_replace('/\D/', '', $me[0]);
else
	$maksuehto	= 0;

$ovt_tunnus		= $r['OVT-tunnus'];
$valittajan_tunnus	= $r['Verkkolaskuoperaattori'];
$verkkolaskuosoite	= $r['Verkkolaskuosoite'];
$alv			= 24;
$aktiivinen		= 1;


// <-- kohde
if(!empty($yhteyshenkilo))
	$etu_suku_nimet		= $yhteyshenkilo;
else if(empty($yhteyshenkilo) and !empty($yrityksen_nimi))
	$etu_suku_nimet		= $yrityksen_nimi;
else
	$etu_suku_nimet		= '';
// kohde -->


echo $osoite.'<br>';


	mysql_query("insert into asiakkaat(
		id,
		asiakasnumero,
		yrityksen_nimi,
		yhteyshenkilo,
		osoite,
		kaupunki,
		postinumero,
		puhelin,
		sahkoposti,
		laskutus_kanava,
		maksuehto,
		ovt_tunnus,
		valittajan_tunnus,
		verkkolaskuosoite,
		alv,
		aktiivinen
	)
	VALUES(
		'".$id."',
		'".$asiakasnumero."',
		'".$yrityksen_nimi."',
		'".$yhteyshenkilo."',
		'".$osoite."',
		'".$kaupunki."',
		'".$postinumero."',
		'".$puhelin."',
		'".$sahkoposti."',
		'".$laskutus_kanava."',
		'".$maksuehto."',
		'".$ovt_tunnus."',
		'".$valittajan_tunnus."',
		'".$verkkolaskuosoite."',
		'".$alv."',
		'".$aktiivinen."'
	)
	");

	
	//$s1 = mysql_query("select id from asiakkaat order by id desc");
	//$rs = mysql_fetch_array($s1);
	

	mysql_query("insert into sivex_kohdet(
		asiakas_id,
		osoite,
		kaupunki,
		pnumero,
		puh_nro,
		email,
		aktiivinen,
		hinta_tyyppi,
		etu_suku_nimet,
		maksuehto_paiva
		)
	VALUES(
		'".$id."',
		'".$osoite."',
		'".$kaupunki."',
		'".$postinumero."',
		'".$puhelin."',
		'".$sahkoposti."',
		'1',
		'1',
		'".$etu_suku_nimet."',
		'".$maksuehto."'
		)
	");

}
*/





?>
