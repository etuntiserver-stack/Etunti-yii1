<?php
/* @var $this ViestintaController */
/* @var $model Viestinta */

/*
if(isset($_GET['asiakas_updater'])) 
{
	$db_host = 'localhost';
	$site = Yii::app()->createController('Site');
	$conn = $site[0]->dbConnectArr();
	$list = Domainit::model()->findAll(" domain!='sivex' and aktiivinen=1 ");

	try {
		$mysqli = new mysqli($conn['host'], $conn['username'], $conn['password']);
	} catch (\Exception $e) {
		echo $e->getMessage(), PHP_EOL;
		exit;
	}

	// <-- Asiakkaat Etunimi ja sukunimi updater
	foreach ($list as $d)
	{
		if ($mysqli->select_db($d->domain) === false) { continue; }
		Yii::app()->db1->setActive(false);
		Yii::app()->db1->connectionString = 'mysql:host=' . $db_host. ';dbname=' . $d->domain;
		//Yii::app()->db1->charset = 'utf8';
		Yii::app()->db1->setActive(true);

		$tb_name = 'asiakkaat';
		$table = Yii::app()->db1->schema->getTable($tb_name);
		$table_structure = array(
			'etunimi' => 'varchar(255) DEFAULT NULL',
			'sukunimi' => 'varchar(255) DEFAULT NULL',
		);
		foreach($table_structure as $key=>$value)
		{
			if (!isset($table->columns[$key])) {
				Yii::app()->db1->createCommand()->addColumn($tb_name, $key, $value);
			}
		}	

		$criteria = new CDBCriteria;
		$criteria->condition = "
			yhteyshenkilo!=''
		";

		echo $d->domain.'<br>';
		try {
			$asiakkaat 	= Asiakkaat::model()->findAll($criteria);
		} catch (\Exception $e) {
			echo $e->getMessage(), PHP_EOL;
			exit;
		}

		foreach($asiakkaat as $item)
		{
			$nimet = explode(" ", trim($item->yhteyshenkilo));
			if(isset($nimet[0]))
			{
				$etunimi = $nimet[0];
				$sukunimi = str_replace($nimet[0], "", $item->yhteyshenkilo);
				Asiakkaat::model()->updateByPk($item->id, ['etunimi' => trim($etunimi), 'sukunimi' => trim($sukunimi)]);
				//echo $item->id.'# '.$item->yhteyshenkilo.' - '.$etunimi.' '.$sukunimi.'<br>';
			}
		}
	}
}
*/

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
	$criteria = new CDbCriteria();
	$criteria->condition = '
domain="sivex" OR domain="moppi" OR domain="demo" OR domain="defdb" OR domain="etunti" OR domain="kotipuhtaaksi" OR domain="digisten" OR domain="testi" OR domain="siivouspalvelukota" OR domain="realclean" OR domain="kairen" OR domain="klaara" OR domain="malli" OR domain="tamsii" OR domain="vayla_ry" OR domain="esittely" OR domain="talentas_oy" OR domain="baltic_palvelu" OR domain="washup" OR domain="oxline_oy" OR domain="rlkotipalvelut_oy" OR domain="paakaupunkiseudun_spsiivous_oy" OR domain="siiivouspalvelu_kuura_oy" OR domain="kivat_pihat" OR domain="elevent_group_oy" OR domain="majatalo_kupittaa_oy" OR domain="kotipalvelu_aura_oy" OR domain="vbe_service_oy" OR domain="royal_clean_oy" OR domain="fritec_oy" OR domain="stone_partners_oy" OR domain="moppimuija" OR domain="siivouspalvelu_jelppi_" OR domain="etunti_testi" OR domain="rlyhtiot_oy_" OR domain="stt_sahko_mikkeli_oy" OR domain="kotipari_oy" OR domain="kvkoneurakointi_oy" OR domain="elamyspalvelu_kolmipyora" OR domain="onnellisten_tiimi" OR domain="saaristo_kotipalvelu" OR domain="vaakkutech_oy" OR domain="kvhpalvelut" OR domain="siivousliike_puuninki" OR domain="kmvkotipalvelut_oy" OR domain="santelo" OR domain="turvapalvelut_salminen" OR domain="perustettava_yhtio" OR domain="saronia_oy" OR domain="finndiiling_oy" OR domain="kototiimi_oy" OR domain="siivouspalvelu_loiste_oy" OR domain="badbaado_ry" OR domain="k__m_elokuvat_oy" OR domain="puhdas_unelma_oy" OR domain="kuljetus_pennanen" OR domain="tmi_ari_paatelainen" OR domain="sysman_autoservis_oy" OR domain="city_saukko_oy" OR domain="katinala_av_oy" OR domain="korkea_tekniikka_oy" OR domain="seoptimi_oy" OR domain="happy_bowling_oy" OR domain="staffone_oy" OR domain="muuble_oy" OR domain="yleissiivous_oy" OR domain="tomuttamo_oy" OR domain="cleana_oy" OR domain="taloke_tmi" OR domain="takomee_tmi" OR domain="arjen_avuxi_oy" OR domain="greenwitch_oy" OR domain="opepooli_oy" OR domain="swappie_oy" OR domain="soittolinja_oy" OR domain="electric_shark_tmi" OR domain="ateljee_kuvastin_oy" OR domain="tietohallintomaisterit_oy" OR domain="rt_work_oy" OR domain="the_process__health__performance_oy" OR domain="upsteam_oy" OR domain="alueenykkosketju" OR domain="inkan_kotitalouspalvelut_oy" OR domain="pirkanmaan_aluesiivous_oy" OR domain="koti_puhtaaksi_testi" OR domain="kotipalvelu_sydankapy_oy" OR domain="kaveko_oy" OR domain="hammslaboratorio_pure_art_oy" OR domain="hoitokoti" OR domain="rvk_palvelut_oy" OR domain="jarvo_oy" OR domain="arkkitiimi_osuuskunta__huoltopojat" OR domain="sisustussuunnittelu_jonita_raikko" OR domain="sk_puhtaaksi_oy" OR domain="multivision_tmi" OR domain="pohjanmaan_kotisiivous" OR domain="eiole_oy" OR domain="talouspaja_oy" OR domain="awareness_recreated_oy" OR domain="kotipalvelu_pajunkissa" OR domain="pesiola_oy" OR domain="leenan_puhdistuspalvelu_oy" OR domain="amazing_city_oy" OR domain="koti_consulting_oy" OR domain="siivouspalvelu_forssan_ilona_oy" OR domain="sinun_avuksi_oy" OR domain="costan_kotihoito" OR domain="mukanas" OR domain="oiva_kotisiivous" OR domain="siivouspro" OR domain="remaster" OR domain="kotova_oy" OR domain="labsense_oy" OR domain="krassat_oy" OR domain="sapindus_oy" OR domain="blue_tower_oy" OR domain="nixisiivous" OR domain="koti1_palvelut_oy" OR domain="mitoka" OR domain="askeleet_polulle" OR domain="hm_tasoite_oy" OR domain="suomen_art_ry" OR domain="tulola_oy" OR domain="petax_oy" OR domain="siivouspalvelu_humalajoki_oy" OR domain="kiurun_kuriiri_oy" OR domain="kuljetus_vkujala_oy" OR domain="siivouspalvelu_minhof" OR domain="bado_palvelut_oy" OR domain="brosiivous" OR domain="cutiopalvelut_oy" OR domain="beauty" OR domain="planca_oy" OR domain="tmi_hannan_kotiapu" OR domain="pinhata_oy" OR domain="myhelp" OR domain="alt_siivouspalvelut" OR domain="ymparistonsuunnittelu_oy" OR domain="escosmetics_ky" OR domain="maatalousyhtyma_maitohovi" OR domain="sinivihrea_oy" OR domain="wms_palvelut_oy" OR domain="kotikylan_taksi_oy" OR domain="kotimaan_huolenpitopalvelut_oy" OR domain="shelter_family_oy" OR domain="spn_palvelut_oy" OR domain="perintaritari_oy" OR domain="rojuka_oy" OR domain="orastaja_oy" OR domain="production_group_specsign" OR domain="evetta_oy" OR domain="moniapu_vakkinen" OR domain="puhtosin_oy" OR domain="miprax_oy" OR domain="brittas_och_carinas_stadtjanst_ab" OR domain="kotiapusi_oy" OR domain="magico_kiinteistopalvelut_oy" OR domain="clefax" OR domain="hecare_oy" OR domain="services_netto" OR domain="kuviopuu" OR domain="kuopion_teollisuus_ja_lvi_eristystekniikka_oy" OR domain="svryhma" OR domain="putipuhdas_hameenlinna_oy" OR domain="omicor_oy" OR domain="hyssiivous" OR domain="arvojes_oy" OR domain="jetsi_oy" OR domain="lakeuden_moniala_tmi" OR domain="viroma_siivouspalvelut_oy" OR domain="seran" OR domain="tmikytogmailcom" OR domain="lecator" OR domain="jiko_oy" OR domain="jaksu_oy" OR domain="urhopesu_oy" OR domain="siivous_ja_tyhjennyspalvelu_super_oy" OR domain="andresrakennus_oy" OR domain="areclean" OR domain="pdge" OR domain="siivouspalvelu_tuhkimo" OR domain="padel_tampere_oy" OR domain="siilinjarven_teatteri" OR domain="power_mountain" OR domain="huippusiivous_oy"
	';
	$list = Domainit::model()->findAll($criteria);
	//$list = Domainit::model()->findAll(" domain='demo' AND aktiivinen=1 ");
/*
	$d_lista = '';
	foreach($list as $item)
		$d_lista .= 'domain="'.$item->domain.'" OR ';
	
	echo $d_lista;
	exit;
*/
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

		$del = OikeusRyhmat::model()->find("nimike='Mobiili'");
		if($del !== null) $del->delete();
				
		$OikeusRyhmat		= OikeusRyhmat::model()->find("nimike='Työntekijät'");
		
		$ryhma_id 		= 0;
		$ryhma_nimike 	= '';
		if(!isset($OikeusRyhmat->nimike))
		{
			$new_ryhma = new OikeusRyhmat;
			$new_ryhma->nimike 			= 'Työntekijät';
			$OikeusRyhmat->for_delete 	= 'false';
			
			try {
					if($new_ryhma->save()){
						$ryhma_id 		= $new_ryhma->id;
						$ryhma_nimike 	= $new_ryhma->nimike;
					} else {
						print_r($new_ryhma->getErrors());
						echo $d->domain;
						exit;
					}
			} catch (\Exception $e) {
				echo $d->domain.'<br>';
				echo $e->getMessage(), PHP_EOL;
				exit;
			}

		} else {
			if($OikeusRyhmat->for_delete == 'true')
				OikeusRyhmat::model()->updateByPk($OikeusRyhmat->id, ['for_delete' => 'false']);
				
			$ryhma_id 		= $OikeusRyhmat->id;
			$ryhma_nimike 	= $OikeusRyhmat->nimike;
		}

		// < oikeudet
		$as_oikeudet = json_decode($asetukset->oikeudet, true);
		$merge_oikeudet = array_merge($as_oikeudet, ["tyontekijatstatic_0_1","tyontekijatstatic_0_".$ryhma_id]);
		$clear = [];
		foreach($merge_oikeudet as $oikeus)
		{
			if (strpos($oikeus, 'mobiili_0') !== false) {
				continue;
			}
			if (strpos($oikeus, 'edico_0') !== false) {
				continue;
			}
			$clear[$oikeus] = $oikeus;
		}
			
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

				if(isset($all_users[$sahkoposti]['User']['domains'][$d->domain]) and $all_users[$sahkoposti]['User']['domains'][$d->domain]['adminID'] == 0)
				{
					$all_users[$sahkoposti]['User']['domains'][$d->domain]['adminID'] = $admin->id;
					$all_users[$sahkoposti]['User']['domains'][$d->domain]['oikeusryhmat'][] = $admin->status;
					continue;
				}
				if(isset($all_users[$sahkoposti]['User']['domains']) and !isset($all_users[$sahkoposti]['User']['domains'][$d->domain]))
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
									'name' => $nimet[0] ?? '',
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
		$OikeusRyhmat		= OikeusRyhmat::model()->find("nimike='Työntekijät'");
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

				if(isset($all_users[$sahkoposti]['User']['domains'][$d->domain]) and $all_users[$sahkoposti]['User']['domains'][$d->domain]['tid'] == 0)
				{
					$all_users[$sahkoposti]['User']['domains'][$d->domain]['tid'] = $tekija->id;
					$all_users[$sahkoposti]['User']['domains'][$d->domain]['oikeusryhmat'][] = $ryhma_id;
					continue;
				}
				if(isset($all_users[$sahkoposti]['User']['domains']) and !isset($all_users[$sahkoposti]['User']['domains'][$d->domain]))
				{
					$domains = array_merge($all_users[$sahkoposti]['User']['domains'], $domains);
					$all_users[$sahkoposti]['User']['domains'] = $domains;
					continue;
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
		
		$del = OikeusRyhmat::model()->find("nimike='eDico'");
		if($del !== null) $del->delete();
		
		$OikeusRyhmat	= OikeusRyhmat::model()->find("nimike='Asiakkaat'");
		
		$ryhma_id 		= 0;
		$ryhma_nimike 	= '';
		if(!isset($OikeusRyhmat->nimike))
		{
			$new_ryhma = new OikeusRyhmat;
			$new_ryhma->nimike = 'Asiakkaat';
			$OikeusRyhmat->for_delete 	= 'false';
			if($new_ryhma->save()){
				$ryhma_id 		= $new_ryhma->id;
				$ryhma_nimike 	= $new_ryhma->nimike;
			}
		} else {
		
			if($OikeusRyhmat->for_delete == 'true')
				OikeusRyhmat::model()->updateByPk($OikeusRyhmat->id, ['for_delete' => 'false']);
			
			$ryhma_id 		= $OikeusRyhmat->id;
			$ryhma_nimike 	= $OikeusRyhmat->nimike;
		}

		// < oikeudet
		$as_oikeudet = json_decode($asetukset->oikeudet, true);
		$merge_oikeudet = array_merge($as_oikeudet, ["asiakasstatic_0_1","asiakasstatic_0_".$ryhma_id]);
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

				if(isset($all_users[$sahkoposti]['User']['domains'][$d->domain]) and $all_users[$sahkoposti]['User']['domains'][$d->domain]['aid'] == 0)
				{
					$all_users[$sahkoposti]['User']['domains'][$d->domain]['aid'] = $asiakas->id;
					$all_users[$sahkoposti]['User']['domains'][$d->domain]['oikeusryhmat'][] = $ryhma_id;
					continue;
				}
				if(isset($all_users[$sahkoposti]['User']['domains']) and !isset($all_users[$sahkoposti]['User']['domains'][$d->domain]))
				{
					$domains = array_merge($all_users[$sahkoposti]['User']['domains'], $domains);
					$all_users[$sahkoposti]['User']['domains'] = $domains;
					continue;
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

