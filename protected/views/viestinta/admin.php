<?php
/* @var $this ViestintaController */
/* @var $model Viestinta */

//<-- Users siirto
if(isset($_GET['users_siirto']))
{
	echo '<style>b{color: red}</style>';
	function clearMail($mail)
	{
		$mail = trim(strtolower($mail));	
		return $mail;
	}
	
	$db_host = 'localhost';
	$site = Yii::app()->createController('Site');
	$conn = $site[0]->dbConnectArr();
	$list = Domainit::model()->findAll(" domain!='defdb' AND aktiivinen=1 ");

	try {
		$mysqli = new mysqli($conn['host'], $conn['username'], $conn['password']);
	} catch (\Exception $e) {
		echo $e->getMessage(), PHP_EOL;
		exit;
	}

	$all_users 		= [];
	$ongelmat		= [];
	$tehty			= 0;
	$yhteensa		= 0;
	
	// <-- Admins
	foreach($list as $d)
	{
		if ($mysqli->select_db($d->domain) === false) { continue; }
		Yii::app()->db1->setActive(false);
		Yii::app()->db1->connectionString = 'mysql:host=' . $db_host. ';dbname=' . $d->domain;
		//Yii::app()->db1->charset = 'utf8';
		Yii::app()->db1->setActive(true);
		
		$asetukset 			= Asetukset::model()->findByPk(1);
		$administrators 	= Administrators::model()->findAll();
		$yhteensa			+= count($administrators);
		
		$del = OikeusRyhmat::model()->find("nimike='Sisäänkirjautunut käyttäjä'");
		if($del !== null) $del->delete();
		
		$OikeusRyhmat		= OikeusRyhmat::model()->find("nimike='Mobiili'");
		
		$ryhma_id 		= 0;
		$ryhma_nimike 	= '';
		if(!isset($OikeusRyhmat->nimike))
		{
			$new_ryhma = new OikeusRyhmat;
			$new_ryhma->nimike = 'Mobiili';
			if($new_ryhma->save()){
				$ryhma_id 		= $new_ryhma->id;
				$ryhma_nimike 	= $new_ryhma->nimike;
			}
		} else {
			$ryhma_id 		= $OikeusRyhmat->id;
			$ryhma_nimike 	= $OikeusRyhmat->nimike;
		}

		// < oikeudet
		$as_oikeudet = json_decode($asetukset->oikeudet, true);
		$merge_oikeudet = array_merge($as_oikeudet, ["mobiili_0_1","mobiili_0_".$ryhma_id]);
		$clear = [];
		foreach($merge_oikeudet as $oikeus)
			$clear[$oikeus] = $oikeus;
			
		$asetukset->oikeudet = json_encode(array_values($clear));
		$asetukset->save();
		
		
		$toisto_checker 	= [];
		foreach($administrators as $admin)
		{
			$sahkoposti = clearMail($admin->adm_email);

			if($admin->adm_login == 'etunti') continue;
						
			if(empty($sahkoposti))
			{
				$ongelmat[] = '<b>'.$d->domain.'</b> domainissa, Adminilla: '.$admin->adm_nimi. ' Sähköposti <b>PUUTUU</b>';
				continue;
			}

			if(isset($toisto_checker[$sahkoposti][$d->domain]))
			{
				$ongelmat[] = '<b>'.$d->domain.'</b>. ADMIN: '.$toisto_checker[$sahkoposti][$d->domain]['nimi'].', ID: <b>'.$toisto_checker[$sahkoposti][$d->domain]['id'].'</b>. Sähköposti '. $sahkoposti . ' <b>TOISTUU</b>';
				$ongelmat[] = '<b>'.$d->domain.'</b>. ADMIN: '.$admin->adm_nimi.', ID: <b>'.$admin->id.'</b>. Sähköposti '. $sahkoposti . ' <b>TOISTUU</b>';

				continue;
			}
			$toisto_checker[$sahkoposti][$d->domain] = ['nimi' => $admin->adm_nimi, 'id' => $admin->id];
			
			if(!empty($sahkoposti))
			{
				$domains = [
					$d->domain => [
						'default' => isset($all_users[$sahkoposti])? "0" : "1",
						'aktiivinen' => "1",
						'adminID' => $admin->id, 
						'tid' => "0",
						'aid' => "0",
						'oikeusryhmat' => [$admin->status]
						]
					];
							
				if(isset($all_users[$sahkoposti]['User']))
				{
					$domains = array_merge($all_users[$sahkoposti]['User']['domains'], $domains);
					$all_users[$sahkoposti]['User']['domains'] = $domains;
					continue;
				}
				
				$nimet = explode(" ", $admin->adm_nimi);
				$all_users[$sahkoposti] = [
						'User' => [
							'id' => 0,
							'confirmed_at' => time(),
							'domains' => $domains,
							'username' => $sahkoposti,
							'email' => $sahkoposti,
							'password_hash' => $admin->adm_salasana
						],
						'Profile' => [
									'user_id' => 0,
									'etunimi' => $nimet[0] ?? '',
									'sukunimi' => $nimet[1] ?? ''
								]
						];
			}
		}
	}
	
	// <-- Tyontekijat
	foreach ($list as $d)
	{
		if ($mysqli->select_db($d->domain) === false) { continue; }
		Yii::app()->db1->setActive(false);
		Yii::app()->db1->connectionString = 'mysql:host=' . $db_host. ';dbname=' . $d->domain;
		//Yii::app()->db1->charset = 'utf8';
		Yii::app()->db1->setActive(true);
		
		$tyontekijat 		= Tyontekijat::model()->findAll();
		$yhteensa			+= count($tyontekijat)+count($administrators);
		$OikeusRyhmat		= OikeusRyhmat::model()->find("nimike='Mobiili'");
		$ryhma_id 			= $OikeusRyhmat->id;
		$toisto_checker		= [];
		
		foreach($tyontekijat as $tekija)
		{
			$sahkoposti = clearMail($tekija->tekijan_email);
			if(strpos($sahkoposti, '@') === false) continue;
			$nimi		= $tekija->FullName;
		
			if(empty($sahkoposti))
			{
				$ongelmat[] = '<b>'.$d->domain.'</b> domainissa, työntekijällä: '.$sahkoposti. ' Sähköposti <b>PUUTUU</b>';
				continue;
			}
			
			if(isset($toisto_checker[$sahkoposti][$d->domain]))
			{
				$ongelmat[] = '<b>'.$d->domain.'</b>. Työntekijä: '.$toisto_checker[$sahkoposti][$d->domain]['nimi'].', ID: <b>'.$toisto_checker[$sahkoposti][$d->domain]['id'].'</b>. Sähköposti '. $sahkoposti . ' <b>TOISTUU</b>';
				$ongelmat[] = '<b>'.$d->domain.'</b>. Työntekijä: '.$nimi.', ID: <b>'.$tekija->id.'</b>. Sähköposti '. $sahkoposti . ' <b>TOISTUU</b>';

				continue;
			}
			$toisto_checker[$sahkoposti][$d->domain] = ['nimi' => $nimi, 'id' => $tekija->id];
			
			if(!empty($sahkoposti))
			{
				$domains = [
					$d->domain => [
						'default' => isset($all_users[$sahkoposti])? "0" : "1",
						'aktiivinen' => $tekija->aktiivinen,
						'adminID' => "0", 
						'tid' => $tekija->id,
						'aid' => "0",
						'oikeusryhmat' => [$ryhma_id]
						]
				];
						
				if(isset($all_users[$sahkoposti]['User']))
				{
					foreach($all_users[$sahkoposti] as $tiedot)
					{							
						if(isset($tiedot['domains'][$d->domain]))
						{
							$domains = [
								$d->domain => [
									'default' => $tiedot['domains'][$d->domain]['default'],
									'aktiivinen' => $tiedot['domains'][$d->domain]['aktiivinen'],
									'adminID' => $tiedot['domains'][$d->domain]['adminID'], 
									'tid' => $tekija->id,
									'aid' => "0",
									'oikeusryhmat' => array_merge($tiedot['domains'][$d->domain]['oikeusryhmat'], [$ryhma_id])
									]
							];
							$domains = array_merge($tiedot['domains'], $domains);
							$all_users[$sahkoposti]['User']['domains'] = $domains;
							continue 2;
							
						} else {
						
							$domains = array_merge($tiedot['domains'], $domains);
							$all_users[$sahkoposti]['User']['domains'] = $domains;
							continue 2;
						}
					}
				}

				$all_users[$sahkoposti] = [
					'User' => [
						'id' => 0,
						'confirmed_at' => time(),
						'domains' => $domains,
						'username' => $sahkoposti,
						'email' => $sahkoposti,
						'password_hash' => $tekija->salasana,
						//'password_hash' => password_hash($tekija->salasana, PASSWORD_DEFAULT),
					],
					'Profile' => [
								'user_id' => 0,
								'name' => $tekija->tekijan_nimi,
								'sukunimi' => $tekija->sukunimi
							]
					];
				
			} else {
				if($tekija->aktiivinen != 1) continue;
				
				$ei_siirrettyt[] = '<b>'.$d->domain.'</b> domainissa, työntekijällä: '.$tekija->tekijan_nimi. ' Sähköposti on: ' . $sahkoposti;
				continue;
			}
		}
	}
				
	// <-- Asiakkaat
	foreach ($list as $d)
	{
		if ($mysqli->select_db($d->domain) === false) { continue; }
		Yii::app()->db1->setActive(false);
		Yii::app()->db1->connectionString = 'mysql:host=' . $db_host. ';dbname=' . $d->domain;
		//Yii::app()->db1->charset = 'utf8';
		Yii::app()->db1->setActive(true);
		
		$asetukset 			= Asetukset::model()->findByPk(1);
		$asiakkaat 			= Asiakkaat::model()->findAll("sahkoposti!='' AND salasana!=''");
		$yhteensa			+= count($asiakkaat);		
		$OikeusRyhmat		= OikeusRyhmat::model()->find("nimike='eDico'");
		
		$ryhma_id 		= 0;
		$ryhma_nimike 	= '';
		if(!isset($OikeusRyhmat->nimike))
		{
			$new_ryhma = new OikeusRyhmat;
			$new_ryhma->nimike = 'eDico';
			if($new_ryhma->save()){
				$ryhma_id 		= $new_ryhma->id;
				$ryhma_nimike 	= $new_ryhma->nimike;
			}
		} else {
			$ryhma_id 		= $OikeusRyhmat->id;
			$ryhma_nimike 	= $OikeusRyhmat->nimike;
		}

		// < oikeudet
		$as_oikeudet = json_decode($asetukset->oikeudet, true);
		$merge_oikeudet = array_merge($as_oikeudet, ["edico_0_1","edico_0_".$ryhma_id]);
		$clear = [];
		foreach($merge_oikeudet as $oikeus){
			if(strpos($oikeus, 'customers') !== false) continue;
			$clear[$oikeus] = $oikeus;
		}
			
		$asetukset->oikeudet = json_encode(array_values($clear));
		$asetukset->save();
		
		$toisto_checker 	= [];
		foreach($asiakkaat as $asiakas)
		{
			$sahkoposti = clearMail($asiakas->sahkoposti);
			if(strpos($sahkoposti, '@') === false) continue;
			$nimi		= $asiakas->Fullname;
			
			if(empty($sahkoposti))
			{
				$ongelmat[] = '<b>'.$d->domain.'</b> domainissa, Asiakas: '.$nimi. ' Sähköposti <b>PUUTUU</b>';
				continue;
			}

			if(isset($toisto_checker[$sahkoposti][$d->domain]))
			{
				$ongelmat[] = '<b>'.$d->domain.'</b>. Asiakas: '.$toisto_checker[$sahkoposti][$d->domain]['nimi'].', ID: <b>'.$toisto_checker[$sahkoposti][$d->domain]['id'].'</b>. Sähköposti '. $sahkoposti . ' <b>TOISTUU</b>';
				$ongelmat[] = '<b>'.$d->domain.'</b>. Asiakas: '.$nimi.', ID: <b>'.$asiakas->id.'</b>. Sähköposti '. $sahkoposti . ' <b>TOISTUU</b>';

				continue;
			}
			$toisto_checker[$sahkoposti][$d->domain] = ['nimi' => $nimi, 'id' => $asiakas->id];
			
			if(!empty($sahkoposti))
			{
				$domains = [
					$d->domain => [
						'default' => isset($all_users[$sahkoposti])? "0" : "1",
						'aktiivinen' => "1",
						'adminID' => "0",
						'tid' => "0", 
						'aid' => $asiakas->id,
						'oikeusryhmat' => [$ryhma_id]
						]
					];

				if(isset($all_users[$sahkoposti]['User']))
				{
					foreach($all_users[$sahkoposti] as $tiedot)
					{
						if(isset($tiedot['domains'][$d->domain]))
						{
							$domains = [
								$d->domain => [
									'default' => $tiedot['domains'][$d->domain]['default'],
									'aktiivinen' => $tiedot['domains'][$d->domain]['aktiivinen'],
									'adminID' => $tiedot['domains'][$d->domain]['adminID'], 
									'tid' => $tiedot['domains'][$d->domain]['tid'], 
									'aid' => $asiakas->id,
									'oikeusryhmat' => array_merge($tiedot['domains'][$d->domain]['oikeusryhmat'], [$ryhma_id])
									]
							];

							$domains = array_merge($tiedot['domains'], $domains);
							$all_users[$sahkoposti]['User']['domains'] = $domains;
							continue 2;
						} else {
						
							$domains = array_merge($tiedot['domains'], $domains);
							$all_users[$sahkoposti]['User']['domains'] = $domains;
							continue 2;
						}
					}
				}					
				
				$nimet = explode(" ", $nimi);
				$all_users[$sahkoposti] = [
						'User' => [
							'id' => 0,
							'confirmed_at' => time(),
							'domains' => $domains,
							'username' => $sahkoposti,
							'email' => $sahkoposti,
							'password_hash' => $asiakas->salasana
						],
						'Profile' => [
									'user_id' => 0,
									'name' => $nimet[0] ?? '',
									'sukunimi' => $nimet[1] ?? ''
								]
						];
			}
		}
	}
	
	//$ongelmat = [];
	
	/*
	echo '<pre>';
	print_r($all_users);
	echo '</pre>';
	exit;
	*/
	
	if(count($ongelmat) == 0 || isset($_GET['pakko']))
	{
		$users 		= [];
		$profiles 	= [];
		$i = 0;
		
		foreach($all_users as $attributes)
		{
			$i++;
			$attributes['User']['id'] = $i;
			$attributes['Profile']['user_id'] = $i;
			$attributes['User']['domains'] = json_encode($attributes['User']['domains']);

			$tehty++;
			$users[] = $attributes['User'];
			$profiles[] = $attributes['Profile'];
		}

		try{

			// Poistetaan ensin kaikki
			Yii::app()->db->createCommand()->delete('user');
			Yii::app()->db->createCommand()->delete('profile');
			$builder 	= Yii::app()->db->schema->commandBuilder;
			$builder->createMultipleInsertCommand('user', $users)->execute();
			$builder->createMultipleInsertCommand('profile', $profiles)->execute();

		}

		catch (Exception $e){

			var_dump($e->getMessage());
			die();

		}

		echo 'Yhteensä '.$yhteensa.'<br>';
		echo 'tehty_hash: '.$tehty;

		echo '<pre>';
		print_r($users);
		echo '</pre>';	
		
	} else {

	
		echo '<pre>';
		foreach($ongelmat as $tieto)
			echo $tieto.'<br>';
		echo '</pre>';
	
	}

}
// Users siirto -->


if(isset($_GET['mail'])){
	$m = $_GET['mail'];
	$ft = FirmanTiedot::model()->findByPk(1);
	$mail = new YiiMailer();
	$mail->setFrom('no-reply@etunti.com');
	$mail->setTo($m);
	$mail->setSubject('test');
	$mail->setBody('testi');
	if($mail->send())
	{
		echo 'sähköposti lähetetty ok '.$m;
	}
}
if(isset($_GET['kk_yhteensta_from']) and isset($_GET['kk_yhteensta_to']))
{
	echo '<h1>'.Yii::app()->user->domain.'</h1>';
	$site = Yii::app()->createController('Site');
	echo $site[0]->digistenTunnitYhteensa($_GET['kk_yhteensta_from'], $_GET['kk_yhteensta_to'], 'table');
}	
/*
if( Yii::app()->user->domain == 'kotimaan_huolenpitopalvelut_oy' ){
   $tv = Asiakkaat::model()->findAll();
   foreach($tv as $item){

	//if(!empty($item->y_tunnus))
	//	Asiakkaat::model()->updatebypk($item->id, ['tyyppi' => 'yritys']);
	//else
	//	Asiakkaat::model()->updatebypk($item->id, ['tyyppi' => 'henkilo']);

	$yhteyshenkilo = '';
	if($item->tyyppi == 'henkilo')
		$yhteyshenkilo = trim($item->yhteyshenkilo);
	if($item->tyyppi == 'yritys')
		$yhteyshenkilo = trim($item->yrityksen_nimi);

	$kohde = Kohteet::model()->find(" asiakas_id='".$item->id."' ");
	if(!isset($kohde->id) and !empty($yhteyshenkilo) and !empty($item->osoite)){
		echo $yhteyshenkilo.'<br>';

		$k = new Kohteet;
		$k->asiakas_id = $item->id;
		$k->etu_suku_nimet = $yhteyshenkilo;
		$k->osoite = $item->osoite;
		$k->kaupunki = $item->kaupunki;
		$k->pnumero = $item->postinumero;
		$k->email = $item->sahkoposti;
		$k->puh_nro = $item->puhelin;
		$k->aktiivinen = 1;
		if(!$k->save()){
			print_r($k->getErrors());
			exit;
		}
	}
   }
}
*/
/* Asiakas siirto 
$tv = Asiakkaat::model()->findAll();
foreach($tv as $item){

	echo $item->yhteyshenkilo.' '.$item->y_tunnus.' '.$item->tyyppi.'<br>';
	//if(!empty($item->y_tunnus))
	//	Asiakkaat::model()->updatebypk($item->id, ['tyyppi' => 'yritys']);
	//else
	//	Asiakkaat::model()->updatebypk($item->id, ['tyyppi' => 'henkilo']);

	$k = new Kohteet;
	$k->asiakas_id = $item->id;
	$k->etu_suku_nimet = $item->yhteyshenkilo;
	$k->osoite = $item->osoite;
	$k->kaupunki = $item->kaupunki;
	$k->pnumero = $item->postinumero;
	$k->email = $item->sahkoposti;
	$k->puh_nro = $item->puhelin;
	$k->aktiivinen = 1;
	$k->save();

}

exit;
*/

if(isset($_GET['phpinfo']))
	phpinfo();

