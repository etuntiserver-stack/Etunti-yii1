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
	foreach ($list as $d)
	{
		if ($mysqli->select_db($d->domain) === false) { continue; }
		Yii::app()->db1->setActive(false);
		Yii::app()->db1->connectionString = 'mysql:host=' . $db_host. ';dbname=' . $d->domain;
		//Yii::app()->db1->charset = 'utf8';
		Yii::app()->db1->setActive(true);
		
		$tyontekijat 		= Tyontekijat::model()->findAll();
		$administrators 	= Administrators::model()->findAll();
		$yhteensa			+= count($tyontekijat)+count($administrators);

		$admin_users 	= [];
		$tt_users 		= [];
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
				$id = 0;
				$admin_users[$admin->adm_email] = [
						'User' => [
							'id' => $id,
							'adminID' => $admin->id,
							'username' => $admin->adm_email,
							'email' => $admin->adm_email,
							'password_hash' => $admin->adm_salasana,
							'status' => $admin->status,
							'domain' => $d->domain,
							'role_admin' => true,
							'role_tyontekija' => false,
							'tid' => 0,
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

		foreach($tyontekijat as $tekija)
		{
			$tekija->tekijan_email = clearMail($tekija->tekijan_email);
			
			if(isset($tt_users[$tekija->tekijan_email]))
			{
				$ongelmat[] = '<b>'.$d->domain.'</b>. Työntekijä: '.$tekija->FullName.', ID: <b>'.$tt_users[$tekija->tekijan_email]['User']['tid'].'</b>. Sähköposti '. $tekija->tekijan_email . ' <b>TOISTUU</b>';
				$ongelmat[] = '<b>'.$d->domain.'</b>. Työntekijä: '.$tekija->FullName.', ID: <b>'.$tekija->id.'</b>. Sähköposti '. $tekija->tekijan_email . ' <b>TOISTUU</b>';
			}
			
			if(empty($tekija->tekijan_email))
			{
				$ongelmat[] = '<b>'.$d->domain.'</b> domainissa, työntekijällä: '.$tekija->tekijan_nimi. ' Sähköposti <b>PUUTUU</b>';
				continue;
			}
			
			if(strpos($tekija->tekijan_email, '@') !== false)
			{
				$id = 0;
				if(isset($admin_users[$tekija->tekijan_email]))
				{
					$tt_users[$tekija->tekijan_email] = [
						'User' => [
							'id' => $id,
							'adminID' => $admin_users[$tekija->tekijan_email]['User']['adminID'],
							'username' => $tekija->tekijan_email,
							'email' => $tekija->tekijan_email,
							'password_hash' => $admin_users[$tekija->tekijan_email]['User']['password_hash'],
							'status' => $admin_users[$tekija->tekijan_email]['User']['status'],
							'domain' => $d->domain,
							'role_admin' => 'true',
							'role_tyontekija' => 'true',
							'tid' => $tekija->id,
							'aktiivinen' => $tekija->aktiivinen
						],
						'Profile' => [
									'user_id' => $id,
									'etunimi' => $tekija->tekijan_nimi,
									'sukunimi' => $tekija->sukunimi
								]
						];
				} else {
					$tt_users[$tekija->tekijan_email] = [
						'User' => [
							'id' => $id,
							'adminID' => 0,
							'username' => $tekija->tekijan_email,
							'email' => $tekija->tekijan_email,
							'password_hash' => $tekija->salasana,
							//'password_hash' => password_hash($tekija->salasana, PASSWORD_DEFAULT),
							'status' => 0,
							'domain' => $d->domain,
							'role_admin' => 'false',
							'role_tyontekija' => 'true',
							'tid' => $tekija->id,
							'aktiivinen' => $tekija->aktiivinen
						],
						'Profile' => [
									'user_id' => $id,
									'name' => $tekija->tekijan_nimi,
									'sukunimi' => $tekija->sukunimi
								]
						];
				}
				
			} else {
				if($tekija->aktiivinen != 1) continue;
				
				$ei_siirrettyt[] = '<b>'.$d->domain.'</b> domainissa, työntekijällä: '.$tekija->tekijan_nimi. ' Sähköposti on: ' . $tekija->tekijan_email;
				continue;
			}
		}
		
		$merge = array_merge($admin_users, $tt_users);
		
		foreach($all_users as $domain => $arr)
		{
			foreach($arr as $email => $tiedot)
			{
				if (array_key_exists($email, $merge)) {
					$ongelmat[] = '<b>'.$d->domain.'</b> domainissa oleva '.$email. ' sähköposti, on olemassa myös <b>'.$tiedot['User']['domain'].'</b> domainissa<br>';
				}
			}
		}
		
		$all_users[strtolower($d->domain)] = $merge;
	}
	
	if(count($ongelmat) == 0)
	{
		$repaired_users = [];
		$i = 0;
		foreach($all_users as $domain => $arr)
		{
			foreach($arr as $email => $attributes)
			{
				if(isset($repaired_users[$email]))
				{
					echo 'SP on olemassa<br>';
					print_r($attributes);
					exit;
				}
				$i++;
				$attributes['User']['id'] = $i;
				$attributes['Profile']['user_id'] = $i;
				$repaired_users[$email] = $attributes;
			}
		}
		
		$users 		= [];
		$profiles 	= [];
		foreach($repaired_users as $email => $arr)
		{
			$tehty++;
			$users[] = $arr['User'];
			$profiles[] = $arr['Profile'];
		}

		/*
		// Poistetaan ensin kaikki
		Yii::app()->db->createCommand()->delete('user');
		$builder 	= Yii::app()->db->schema->commandBuilder;
		$builder->createMultipleInsertCommand('user', $users)->execute();
		$builder->createMultipleInsertCommand('profile', $profiles)->execute();
		*/
		
		echo 'Yhteensä '.$yhteensa.'<br>';
		echo 'tehty_hash: '.$tehty;

		/*
		echo '<pre>';
		print_r($users);
		echo '</pre>';
		*/
		
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

