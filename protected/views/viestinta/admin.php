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
	$admin_users 	= [];
	$tt_users 		= [];
	$ongelmat		= [];
	$tehty			= 0;
	$yhteensa		= 0;
	foreach ($list as $d)
	{
		if ($mysqli->select_db($d->domain) === false) { continue; }
		Yii::app()->db1->setActive(false);
		Yii::app()->db1->connectionString = 'mysql:host=' . $db_host. ';dbname=' . $d->domain;
		//Yii::app()->db1->charset = 'utf8';
		Yii::app()->db1->setActive(true);
		
		$administrators 	= Administrators::model()->findAll();
		$yhteensa			+= count($administrators);
		$OikeusRyhmat		= OikeusRyhmat::model()->find("nimike='Sisäänkirjautunut käyttäjä'");
		
		$ryhma_id = 0;
		if(!isset($OikeusRyhmat->nimike))
		{
			$new_ryhma = new OikeusRyhmat;
			$new_ryhma->nimike = 'Sisäänkirjautunut käyttäjä';
			if($new_ryhma->save())
				$ryhma_id = $new_ryhma->id;
		} else {
			$ryhma_id = $OikeusRyhmat->id;
		}
		
		foreach($administrators as $admin)
		{
			$admin->adm_email = clearMail($admin->adm_email);

			if($admin->adm_login == 'etunti') continue;
			
			if(empty($admin->adm_email))
			{
				$ongelmat[] = '<b>'.$d->domain.'</b> domainissa, Adminilla: '.$admin->adm_nimi. ' Sähköposti <b>PUUTUU</b>';
				continue;
			}

			if(!empty($admin->adm_email))
			{
				$domains = [
					$d->domain => [
						'default' => 1,
						'adminID' => $admin->id, 
						'tid' => 0, 
						'oikeusryhma_id' => $admin->status
						]
					];
							
				if(isset($all_users[$admin->adm_email]))
				{
					foreach($all_users[$admin->adm_email] as $key => $tiedot)
					{
						$domains = array_merge(
										$tiedot['User']['domains'], 
										$domains
						);
						unset($all_users[$admin->adm_email][$key]);
					}
				}
					
				$id = 0;
				$all_users[$admin->adm_email][] = [
						'User' => [
							'id' => $id,
							'confirmed_at' => time(),
							'domains' => $domains,
							'username' => $admin->adm_email,
							'email' => $admin->adm_email,
							'password_hash' => $admin->adm_salasana,
							'aktiivinen' => 1
						],
						'Profile' => [
									'user_id' => $id,
									'etunimi' => $admin->adm_nimi,
									'sukunimi' => ''
								]
						];
			}
		}
	}

	foreach ($list as $d)
	{
		if ($mysqli->select_db($d->domain) === false) { continue; }
		Yii::app()->db1->setActive(false);
		Yii::app()->db1->connectionString = 'mysql:host=' . $db_host. ';dbname=' . $d->domain;
		//Yii::app()->db1->charset = 'utf8';
		Yii::app()->db1->setActive(true);
		
		$tyontekijat 		= Tyontekijat::model()->findAll();
		$yhteensa			+= count($tyontekijat)+count($administrators);
		$OikeusRyhmat		= OikeusRyhmat::model()->find("nimike='Sisäänkirjautunut käyttäjä'");
		$ryhma_id 			= $OikeusRyhmat->id;
		
		foreach($tyontekijat as $tekija)
		{
			$tekija->tekijan_email = clearMail($tekija->tekijan_email);
			
			//if(isset($all_users[$tekija->tekijan_email]['User']['domains'][$d->domain]))
			//{
				//$ongelmat[] = '<b>'.$d->domain.'</b>. Työntekijä: '.$tekija->FullName.', ID: <b>'.$all_users[$tekija->tekijan_email]['User']['tid'].'</b>. Sähköposti '. $tekija->tekijan_email . ' <b>TOISTUU</b>';
				//$ongelmat[] = '<b>'.$d->domain.'</b>. Työntekijä: '.$tekija->FullName.', ID: <b>'.$tekija->id.'</b>. Sähköposti '. $tekija->tekijan_email . ' <b>TOISTUU</b>';

				//continue;
			//}

			if(empty($tekija->tekijan_email))
			{
				$ongelmat[] = '<b>'.$d->domain.'</b> domainissa, työntekijällä: '.$tekija->tekijan_nimi. ' Sähköposti <b>PUUTUU</b>';
				continue;
			}

			if(strpos($tekija->tekijan_email, '@') !== false)
			{
				$domains = [
					$d->domain => [
						'default' => 1,
						'adminID' => 0, 
						'tid' => $tekija->id, 
						'oikeusryhma_id' => $ryhma_id
						]
				];
							
				if(isset($all_users[$tekija->tekijan_email]))
				{
					foreach($all_users[$tekija->tekijan_email] as $key => $tiedot)
					{
						if(isset($tiedot['User']['domains'][$d->domain]['adminID']) and $tiedot['User']['domains'][$d->domain]['adminID'] > 0)
						{
							$domains = [
								$d->domain => [
									'default' => $tiedot['User']['domains'][$d->domain]['default'],
									'adminID' => $tiedot['User']['domains'][$d->domain]['adminID'], 
									'tid' => $tekija->id, 
									'oikeusryhma_id' => [$tiedot['User']['domains'][$d->domain]['oikeusryhma_id'], $ryhma_id]
									]
							];
						}
						
						$domains = array_merge(
										$tiedot['User']['domains'], 
										$domains
						);
						unset($all_users[$tekija->tekijan_email][$key]);
					}
				}
			
				$id = 0;

				$all_users[$tekija->tekijan_email][] = [
					'User' => [
						'id' => $id,
						'confirmed_at' => time(),
						'domains' => $domains,
						'username' => $tekija->tekijan_email,
						'email' => $tekija->tekijan_email,
						'password_hash' => $tekija->salasana,
						//'password_hash' => password_hash($tekija->salasana, PASSWORD_DEFAULT),
						'aktiivinen' => $tekija->aktiivinen
					],
					'Profile' => [
								'user_id' => $id,
								'name' => $tekija->tekijan_nimi,
								'sukunimi' => $tekija->sukunimi
							]
					];
				
			} else {
				if($tekija->aktiivinen != 1) continue;
				
				$ei_siirrettyt[] = '<b>'.$d->domain.'</b> domainissa, työntekijällä: '.$tekija->tekijan_nimi. ' Sähköposti on: ' . $tekija->tekijan_email;
				continue;
			}
		}
	}

	$ongelmat = [];
/*
	echo '<pre>';
	print_r($all_users);
	echo '</pre>';
*/
	
	if(count($ongelmat) == 0)
	{
		$users 		= [];
		$profiles 	= [];
		$i = 0;
		
		foreach($all_users as $email => $arr)
		{
			foreach($arr as $attributes)
			{
				$i++;
				$attributes['User']['id'] = $i;
				$attributes['Profile']['user_id'] = $i;

				$tehty++;
				$users[] = $attributes['User'];
				$profiles[] = $attributes['Profile'];
			}
		}
		
		
		// Poistetaan ensin kaikki
		//Yii::app()->db->createCommand()->delete('user');
		//Yii::app()->db->createCommand()->delete('profile');
		//$builder 	= Yii::app()->db->schema->commandBuilder;
		//$builder->createMultipleInsertCommand('user', $users)->execute();
		//$builder->createMultipleInsertCommand('profile', $profiles)->execute();
		
		
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

