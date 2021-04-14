<?php

/**
 * This is the model class for table "asetukset".
 *
 * The followings are the available columns in table 'asetukset':
 * @property integer $id
 * @property string $syntyrin_emails
 * @property string $paivan_uutinen
 * @property string $logon_polkku
 * @property integer $logon_korkeus
 * @property string $johtaja
 * @property string $procountor_access_token Procountor access token, usually valid only temporarily.
 * @property string $procountor_refresh_token For refreshing the Procountor access token.
 * @property int $procountor_refresh_time Time when Procountor access token was last refreshed.
 * @property int $procountor_expires_in Expiration time of Procountor access token, usually 300 seconds.
 * @property int $procountor_invalid If 1, Procountor access token expired and refreshing failed.
 */
class Asetukset extends DB2ActiveRecord
{

	public $kirjautumistunnus;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Asetukset the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{

		$tb_name = 'asetukset';
		$check_this_table = true;

		if($check_this_table)
		{
		    $table = Yii::app()->db1->schema->getTable($tb_name);
		    if(!isset($table->columns['id'])) {

			Yii::app()->db1->createCommand(" CREATE TABLE IF NOT EXISTS $tb_name 
			(`id` int(11) AUTO_INCREMENT PRIMARY KEY)
			")->execute();
		    }

		    $table_structure = array(
				'syntyrin_emails' => 'text DEFAULT NULL',
				'paivan_uutinen' => 'varchar(500) DEFAULT NULL',
				'logon_polkku' => 'varchar(500) DEFAULT NULL',
				'logon_korkeus' => 'int(4) DEFAULT 0',
				'johtaja' => 'varchar(100) DEFAULT NULL',
				'viivastyskorko' => 'varchar(50) DEFAULT NULL',
				'tilinumero' => 'varchar(100) DEFAULT NULL',
				'iban' => 'varchar(100) DEFAULT NULL',
				'bic' => 'varchar(100) DEFAULT NULL',
				'postita_username' => 'varchar(100) DEFAULT NULL',
				'postita_password' => 'varchar(100) DEFAULT NULL',
				'trust_cid' => 'varchar(100) DEFAULT NULL',
				'trust_api' => 'varchar(100) DEFAULT NULL',
				'palvelu_tyyppi' => 'int(1) DEFAULT 0',
				'trust_url' => 'varchar(255) DEFAULT NULL',
				'pyhapaivat' => 'text DEFAULT NULL',
				'erikoislauantai' => 'text DEFAULT NULL',
				'sovellus_tyovuorot' => 'int(2) DEFAULT 0',
				'checkout_id' => 'varchar(100) DEFAULT NULL',
				'checkout_salasana' => 'varchar(255) DEFAULT NULL',
				'viikonloppulisa_la' => 'varchar(10) DEFAULT NULL',
				'viikonloppulisa_su' => 'varchar(10) DEFAULT NULL',
				'tilausvahvistus' => 'text DEFAULT NULL',
				'oikeudet' => 'text DEFAULT NULL',
				'rekisteriseloste' => 'text DEFAULT NULL',
				'lasku_asiakasnumero' => 'int(1) DEFAULT 0',
				'lasku_laskunumero' => 'int(1) DEFAULT 1',
				'show_name' => 'int(1) DEFAULT 0',
				'trust_ws_api_url' => 'varchar(255) DEFAULT NULL',
				'trust_ws_cid' => 'varchar(100) DEFAULT NULL',
				'trust_ws_salasana' => 'varchar(100) DEFAULT NULL',
				'onlinevaraus_laatu_luotettavuus' => 'text DEFAULT NULL',
				'onlinevaraus_takuu_turvallisuus' => 'text DEFAULT NULL',
				'onlinevaraus_asiakaspalvelu' => 'text DEFAULT NULL',
				'onlinevaraus_aikavali' => 'int(1) DEFAULT 1',
				'onlinevaraus_arvio_siivouksesta' => 'text DEFAULT NULL',
				'onlinevaraus_aikaisintaan_paivamaara' => 'int(2) DEFAULT 0',
				'onlinevaraus_alku' => 'int(2) DEFAULT 0',
				'onlinevaraus_loppu' => 'int(2) DEFAULT 0',
				'onlinevaraus_autoremove' => 'int(3) DEFAULT 20',
				'aikavali_halytys' => 'int(3) DEFAULT 0',
				'ilmoitus_avoimista_kohteesta_sahkopostiin' => 'int(1) DEFAULT 0',
				'ilmoitus_myohastyneista_kohteesta_sahkopostiin' => 'int(1) DEFAULT 0',
				'netvisor_mita_onkayttossa' => 'int(1) DEFAULT 0',
				'netvisor_customer_id' => 'varchar(255) DEFAULT NULL',
				'netvisor_partner_id' => 'varchar(255) DEFAULT NULL',
				'netvisor_userkey' => 'varchar(255) DEFAULT NULL',
				'netvisor_partnerkey' => 'varchar(255) DEFAULT NULL',
				'netvisor_kaytto' => 'int(1) DEFAULT 0',
				'netvisor_organisation_identifier' => 'varchar(255) DEFAULT NULL',
				'netvisor_accountingaccountsuggestion' => 'varchar(255) DEFAULT 3000',
				'merkkipaivailmoitukset_sahkoposti' => 'varchar(255) DEFAULT NULL',
				'asiakas_tyovuorossa' => 'int(1) DEFAULT 0',
				'tuote_tyovuorossa' => 'int(1) DEFAULT 0',
				'app_show_phone' => 'int(1) DEFAULT 0',
				'paikkakunta_tyovuorossa' => 'int(1) DEFAULT 0',
				'app_lopettaa_vain_tagilla' => 'int(1) DEFAULT 0',
				'ilmoitus_toistuvien_tyovuorojen_paattymisesta' => 'int(1) DEFAULT 0',
				'ilmoitus_toistuvien_tyovuorojen_paattymisesta_paivat_ennen' => 'int(3) DEFAULT 0',
				'ilmoitus_toistuvien_tyovuorojen_paattymisesta_saajat' => 'text DEFAULT NULL',
				'tyovuorolahetys_naytetaanko_asiakas' => 'int(1) DEFAULT 0',
				'tyovuorolahetys_naytetaanko_kohteen_postitoimipaikka' => 'int(1) DEFAULT 0',
				'tyovuoro_tietoja_mobiilisovellukseen' => 'text DEFAULT NULL',
				'ilmoitus_merkkipaivasta' => 'int(1) DEFAULT 0',
				'ilmoitus_uudesta_kuvasta_saajat' => 'text DEFAULT NULL',
				'netvisor_host' => 'varchar(500) DEFAULT NULL',
				'tyontekijan_etunimi_sukunimi_jarjestys' => 'int(1) DEFAULT 0',
				'netvisor_acceptancestatus' => 'varchar(50) DEFAULT NULL',
				'netvisor_mita_lahetetaan' => 'varchar(255) DEFAULT NULL',
				'netvisor_lahetyksen_muoto' => 'int(1) DEFAULT 0',
				'app_hyvaksytyt_tyot_vkomaara' => 'int(3) DEFAULT 4',
				'app_naytetaanko_hyvaksyttyt_tunnit' => 'int(1) DEFAULT 0',
				'app_naytta_avain' => 'int(1) DEFAULT 0',
				'app_naytta_osoitekenta' => 'int(1) DEFAULT 1',
				'app_matka_osoite' => 'int(1) DEFAULT 1',
				'app_lounastauko_osoite' => 'int(1) DEFAULT 1',
				'app_auto_hyvaksyminen' => 'int(1) DEFAULT 0',
				'app_hyvaksynnan_peruste' => 'int(1) DEFAULT 0',
				'app_auto_hyvaksyminen_aikavali' => 'int(2) DEFAULT 10',
				'app_auto_hyvaksyminen_tvmukaan' => 'int(1) DEFAULT 0',
				'app_naytta_sairauslomat' => 'int(1) DEFAULT 1',
				//'edico_laatutaso_1' => 'text ',
				//'edico_laatutaso_2' => 'text ',
				//'edico_laatutaso_3' => 'text ',
				//'edico_muut_kulut' => 'text ',
				'tapaturmavakuutus' => 'int(11) ',
				'ryhmahenkivakuutus' => 'int(11) ',
				'tyottomyysvakuutusmaksu' => 'int(11) ',
				'sosiaaliturvamaksu' => 'int(11) ',
				'tyel_maksun_osuus_palkkansummasta' => 'int(11) ',
				'peruutusehdot' => 'text ',
				'gtm' => 'varchar(255) ',
				'app_naytetaanko_kohteen_yhteyshenkilo' => 'int(1) ',
				'onlinevaraus_viikonlopput' => 'int(1) ',
				'maksullinen' => 'int(1) DEFAULT 0 ',
				'alennus_max_euro' => 'float ',
				'alennus_max_prosentti' => 'float ',
				'peruutta_paiva_ennen' => 'int(2) YES ',
				'edico_tehdyt_tyot' => 'varchar(100)',
				'netvisor_lahetetaanko_tyontekija' => 'int(1)',
				'lasketaanko_lounastauko' => 'int(1)',
				'tuotteet_palvelut_muoto' => 'int(1) DEFAULT 0',
				'tyoryhmat' => 'int(1) DEFAULT 0',
				'tyoryhmat_kohde' => 'int(1) DEFAULT 0',
				'tyoryhmat_tyontekijat' => 'int(1) DEFAULT 1',
				'tietosuoja_vinkki_sailyttaminen' => 'int(3) DEFAULT 14',
				'palautteet_autovastaus_hyva' => 'TEXT',
				'palautteet_autovastaus_huono' => 'TEXT',
				'app_version_playmarket' => 'varchar(255)',
				'asiakas_laskutus_kanava' => 'varchar(255) DEFAULT "posti"',
				'asiakas_kirjeenluokka' => 'int(1) DEFAULT 1',
				'asiakas_viivastyskorko' => 'int(1) DEFAULT 7',
				'asiakas_maksuehto' => 'int(1) DEFAULT 14',
				'asiakas_myyja' => 'int(3) DEFAULT NULL',
				'asiakas_tyoryhma' => 'int(11) DEFAULT NULL',
				'asiakas_ryhma' => 'varchar(255) DEFAULT NULL',
				'asiakas_alv' => 'int(3) DEFAULT 24',
				'asiakas_hinta_tyyppi' => 'varchar(50) DEFAULT NULL',
				'asiakas_pakkoliset' => 'varchar(2000) DEFAULT "[\"osoite\",\"kaupunki\",\"postinumero\"]"',
				'auto_hyvaksynta_klo' => 'varchar(100) DEFAULT "12:00"',
				//'vinkki_tunnit' => 'varchar(10) ',
				//'vinkki_prosentti' => 'varchar(10) ',
				//'ilmainen_versio_kayttotunnit' => 'int(11) ',
				//'edico_laatutaso_1' => 'text ',
				//'edico_laatutaso_2' => 'text ',
				//'edico_laatutaso_3' => 'text ',
				//'edico_muut_kulut' => 'text ',
				'procountor_access_token' => 'varchar(500) DEFAULT NULL',   // Access token, usually valid only temporarily.
				'procountor_refresh_token' => 'varchar(500) DEFAULT NULL',  // For refreshing the access token.
				'procountor_refresh_time' => 'int(11) DEFAULT 0',           // Time when access token was last refreshed.
				'procountor_expires_in' => 'int(6) DEFAULT 0',              // Expiration time of access token, usually 300 seconds.
				'procountor_invalid' => 'int(1) DEFAULT 0',                 // If 1, access token expired and refreshing failed.
				'freshdesk_active' => 'int(1) DEFAULT 0',
				'rivien_teko' => 'int(1) DEFAULT 0',
				'onlinevaraus_palvelu' => 'int(1) DEFAULT 0',               // 0: checkout, 1: bambora
				'bambora_private_key' => 'varchar(128) DEFAULT NULL',
				'bambora_api_key' => 'varchar(128) DEFAULT NULL',

				// Whether the regulars warnings/notification system is enabled.
				'omasiistijat_enabled' => 'int(1) DEFAULT 0',
				'omasiistijat_email_subject' => 'varchar(120) DEFAULT NULL',
				'omasiistijat_email_body' => 'text DEFAULT NULL',

				// Whether to show the notification button for starting times on shift.
				'aloitusajat_enabled' => 'int(1) DEFAULT 0',
				'aloitusajat_email_subject' => 'varchar(120) DEFAULT NULL',
				'aloitusajat_email_body' => 'text DEFAULT NULL',
		    );

		    foreach($table_structure as $key=>$value)
		    {
			if (!isset($table->columns[$key])) {
				Yii::app()->db1->createCommand()->addColumn($tb_name, $key, $value);
			}
		    }

		} // if($check_this_table)	

		return $tb_name;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, johtaja', 'required'),
			array('id, show_name, app_show_phone, sovellus_tyovuorot, logon_korkeus, palvelu_tyyppi, lasku_asiakasnumero, ilmoitus_avoimista_kohteesta_sahkopostiin, ilmoitus_myohastyneista_kohteesta_sahkopostiin, netvisor_kaytto, asiakas_tyovuorossa, onlinevaraus_aikaisintaan_paivamaara, onlinevaraus_alku, onlinevaraus_loppu, onlinevaraus_palvelu, paikkakunta_tyovuorossa, app_lopettaa_vain_tagilla, ilmoitus_toistuvien_tyovuorojen_paattymisesta, ilmoitus_toistuvien_tyovuorojen_paattymisesta_paivat_ennen, tyovuorolahetys_naytetaanko_asiakas, tyovuorolahetys_naytetaanko_kohteen_postitoimipaikka, ilmoitus_merkkipaivasta, tyontekijan_etunimi_sukunimi_jarjestys, app_hyvaksytyt_tyot_vkomaara, app_naytetaanko_hyvaksyttyt_tunnit, app_naytta_avain, tapaturmavakuutus, ryhmahenkivakuutus, tyottomyysvakuutusmaksu, sosiaaliturvamaksu, tyel_maksun_osuus_palkkansummasta, app_naytetaanko_kohteen_yhteyshenkilo, onlinevaraus_viikonlopput, maksullinen, ilmainen_versio_kayttotunnit, alennus_max_euro, alennus_max_prosentti, peruutta_paiva_ennen, lasketaanko_lounastauko, netvisor_lahetetaanko_tyontekija, tuote_tyovuorossa, app_matka_osoite, app_lounastauko_osoite, tuotteet_palvelut_muoto, onlinevaraus_aikavali, onlinevaraus_autoremove, tyoryhmat, tyoryhmat_kohde, tyoryhmat_tyontekijat, tietosuoja_vinkki_sailyttaminen, app_auto_hyvaksyminen, app_hyvaksynnan_peruste, app_auto_hyvaksyminen_aikavali, app_auto_hyvaksyminen_tvmukaan, netvisor_mita_onkayttossa, lasku_laskunumero, asiakas_kirjeenluokka, asiakas_viivastyskorko, asiakas_maksuehto, asiakas_myyja, asiakas_tyoryhma, asiakas_alv, app_naytta_osoitekenta, app_naytta_sairauslomat, rivien_teko', 'numerical', 'integerOnly'=>true),

			array('paivan_uutinen, logon_polkku, netvisor_host', 'length', 'max'=>500),
			array('johtaja, viivastyskorko, tilinumero, iban, bic, , postita_username, postita_password, trust_cid, trust_api, checkout_id, trust_ws_cid, trust_ws_salasana, netvisor_acceptancestatus, edico_tehdyt_tyot, asiakas_hinta_tyyppi, auto_hyvaksynta_klo, bambora_private_key, bambora_api_key', 'length', 'max'=>100),
      array('trust_url, checkout_salasana, trust_ws_api_url, netvisor_customer_id, netvisor_partner_id, netvisor_userkey, netvisor_partnerkey, netvisor_organisation_identifier, merkkipaivailmoitukset_sahkoposti, netvisor_mita_lahetetaan, netvisor_lahetyksen_muoto, gtm, app_version_playmarket, asiakas_laskutus_kanava, asiakas_ryhma, netvisor_accountingaccountsuggestion', 'length', 'max'=>255),
			array('viikonloppulisa_la, viikonloppulisa_su', 'length', 'max'=>10),
			array('aikavali_halytys', 'length', 'max'=>3),
			array('oikeudet, pyhapaivat, erikoislauantai, tilausvahvistus, rekisteriseloste, onlinevaraus_laatu_luotettavuus, onlinevaraus_takuu_turvallisuus, onlinevaraus_asiakaspalvelu, onlinevaraus_arvio_siivouksesta, ilmoitus_toistuvien_tyovuorojen_paattymisesta_saajat, ilmoitus_uudesta_kuvasta_saajat, peruutusehdot, palautteet_autovastaus_hyva, palautteet_autovastaus_huono, asiakas_pakkoliset, tyovuoro_tietoja_mobiilisovellukseen', 'safe'),
      // The following rule is used by search().
      // Please remove those attributes that should not be searched.
      array('id, syntyrin_emails, paivan_uutinen, logon_polkku, logon_korkeus, johtaja, viivastyskorko, tilinumero, iban, bic, trust_cid, trust_api, palvelu_tyyppi, trust_url, pyhapaivat, erikoislauantai, sovellus_tyovuorot', 'safe', 'on' => 'search'),

      //- Omasiistijät
      ['omasiistijat_enabled', 'numerical', 'integerOnly' => true, 'integerPattern' => '/^[0-1]$/', 'skipOnError' => true],
      ['omasiistijat_email_subject', 'length', 'max' => 120, 'skipOnError' => true],
      ['omasiistijat_email_body', 'length', 'max' => 8000, 'skipOnError' => true],

      //- Aloitusaikailmoitukset
      ['aloitusajat_enabled', 'numerical', 'integerOnly' => true, 'integerPattern' => '/^[0-1]$/', 'skipOnError' => true], 
      ['aloitusajat_email_subject', 'length', 'max' => 120, 'skipOnError' => true],
      ['aloitusajat_email_body', 'length', 'max' => 8000, 'skipOnError' => true],
    );
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('main', 'ID'),
			'syntyrin_emails' => Yii::t('main', 'Syntyrin Emails'),
			'paivan_uutinen' => Yii::t('main', 'Paivan Uutinen'),
			'logon_polkku' => Yii::t('main', 'Logo'),
			'logon_korkeus' => Yii::t('main', 'Logon Korkeus'),
			'johtaja' => Yii::t('main', 'Johtaja'),
			'tilinumero' => Yii::t('main', 'Tilinumero'),
			'iban' => Yii::t('main', 'IBAN'),
			'bic' => Yii::t('main', 'BIC'),
			'viivastyskorko' => Yii::t('main', 'Viivästyskorko %'),
			'postita_username' => Yii::t('main', 'Postita käyttäjätunnus'),
			'postita_password' => Yii::t('main', 'Postita salasana'),
			'palvelu_tyyppi' => Yii::t('main', 'Palvelutyyppi'),
			'trust_url'=> Yii::t('main', 'Ropo24 URL'),
			'pyhapaivat'=> Yii::t('main', 'Viralliset pyhäpäivät / pp.kk.vvvv'),
			'erikoislauantai' => Yii::t('main', 'Erikoislauantai'),
			'sovellus_tyovuorot'=> Yii::t('main', 'Työvuorojen näyttäminen'),
			'checkout_id'=> Yii::t('main', 'Kauppiastunnus'),
			'checkout_salasana'=> Yii::t('main', 'Turva-avain'),
			'viikonloppulisa_la'=> Yii::t('main', 'Lauantai %'),
			'viikonloppulisa_su'=> Yii::t('main', 'Sunnuntai %'),
			'lasku_laskunumero'=> Yii::t('main', 'Syöttääkö itse laskutusnumeron vai automaattisesti'),
			'lasku_asiakasnumero'=> Yii::t('main', 'Syöttääkö itse asiakasnumeron vai lasketaan edellisestä automaattisesti'),
			'show_name' => Yii::t('main', 'Näytä asiakas nimi'),
			'app_show_phone' => Yii::t('main', 'Näytä kohteen puhelinnumero'),
			'trust_ws_api_url'=> Yii::t('main', 'Ropo24 WS API'),
			'onlinevaraus_laatu_luotettavuus' => Yii::t('main', 'Laatu ja luotettavuus'),
			'onlinevaraus_takuu_turvallisuus' => Yii::t('main', 'Takuu ja turvallisuus'),
			'onlinevaraus_asiakaspalvelu' => Yii::t('main', 'Asiakaspalvelu'),
			'onlinevaraus_arvio_siivouksesta' => Yii::t('main', 'Arvio palvelusta'),
			'onlinevaraus_aikavali' => Yii::t('main', 'Varauksessa matka-aika, ennen ja jälkeen'),
			'onlinevaraus_autoremove' => Yii::t('main', 'Vahvistamaton varaus poistetaan, minuuttia'),
			'aikavali_halytys' => Yii::t('main', 'Aikaväli hälytys (min)'),
			'ilmoitus_avoimista_kohteesta_sahkopostiin' => Yii::t('main', 'Ilmoitus määräajan ylittäneistä kohteista sähköpostiin'),
			'ilmoitus_myohastyneista_kohteesta_sahkopostiin' => Yii::t('main', 'Ilmoitus myöhästyneistä kohteesta sähköpostiin'),
			'merkkipaivailmoitukset_sahkoposti' => Yii::t('main', 'Sähköposti, johoon tulevat merkkipäiväilmoitukset'),
			'asiakas_tyovuorossa'=>Yii::t('main', 'Näytetäänkö asiakas työvuorossa'),
			'tuote_tyovuorossa'=>Yii::t('main', 'Näytetäänkö tuote työvuorossa'),
			'paikkakunta_tyovuorossa' => Yii::t('main', 'Näytetäänkö paikkakunta työvuorossa'),
			'onlinevaraus_aikaisintaan_paivamaara'=>Yii::t('main', 'Monenko päivän päästä vuoroja voi varata.'),
			'onlinevaraus_alku'=>Yii::t('main', 'Varauksen alku kellon aika'),
			'onlinevaraus_loppu'=>Yii::t('main', 'Varauksen loppu kellon aika'),
			'app_lopettaa_vain_tagilla' => Yii::t('main', 'Kohde mahdollista lopettaa vain aloitetulla Tagilla'),
			'ilmoitus_toistuvien_tyovuorojen_paattymisesta' => Yii::t('main', 'Ilmoitus toistuvien työvuorojen päättymisestä'),
			'ilmoitus_toistuvien_tyovuorojen_paattymisesta_paivat_ennen' => Yii::t('main', 'Ilmoitus päättymisestä päivät ennen'),
			'ilmoitus_toistuvien_tyovuorojen_paattymisesta_saajat' => Yii::t('main', 'Saajan sähköpostit'),
			'tyovuorolahetys_naytetaanko_asiakas' => Yii::t('main', 'Työvuorolahetys näytetäänkö asiakas'),
			'tyovuorolahetys_naytetaanko_kohteen_postitoimipaikka' => Yii::t('main', 'Työvuorolahetys näytetäänkö postitoimipaikka'),
			'tyovuoro_tietoja_mobiilisovellukseen' => Yii::t('main', 'Tietoja mobiilisovellukseen'),
			'ilmoitus_merkkipaivasta'=>Yii::t('main', 'Ilmoitus merkkipäivästä sähköpostiin'),
			'ilmoitus_uudesta_kuvasta_saajat'=>Yii::t('main', 'Sähköpostit johon lähetetään ilmoitus uudesta kuvasta.'),
			'tyontekijan_etunimi_sukunimi_jarjestys'=>Yii::t('main', 'Työntekijän etunimi ja sukunimi järjestys'),
			'netvisor_mita_lahetetaan'=>Yii::t('main', 'Mitä lähetetään'),
			'netvisor_acceptancestatus'=>Yii::t('main', 'Acceptancestatus'),
			'app_hyvaksytyt_tyot_vkomaara'=>Yii::t('main', 'Hyväksytyt työt vko määrä'),
			'app_naytetaanko_hyvaksyttyt_tunnit'=>Yii::t('main', 'Näytetäänkö hyväksytyt tunnit'),
			'app_naytta_avain'=>Yii::t('main', 'Näytetäänkö avaimet'),
			'app_naytta_osoitekenta'=>Yii::t('main', 'Näytä osoite sovelluksessa'),
			'edico_laatutaso_1' => Yii::t('main', 'Laatutaso 1'),
			'edico_laatutaso_2' => Yii::t('main', 'Laatutaso 2'),
			'edico_laatutaso_3' => Yii::t('main', 'Laatutaso 3'),
			'edico_muut_kulut' => Yii::t('main', 'Muut kulut'),
			'tapaturmavakuutus' => Yii::t('main', 'TAPATURMAVAKUUTUS '),
			'ryhmahenkivakuutus' => Yii::t('main', 'RYHMÄHENKIVAKUUTUS'),
			'tyottomyysvakuutusmaksu' => Yii::t('main', 'TYÖTTÖMYYSVAKUUTUSMAKSU'),
			'sosiaaliturvamaksu' => Yii::t('main', 'SOSIAALITURVAMAKSU'),
			'tyel_maksun_osuus_palkkansummasta' => Yii::t('main', 'TyEL-MAKSUN OSUUS PALKKASUMMASTA'),
			'peruutusehdot' => Yii::t('main', 'Asiakkaalle näytettävät varauksen peruutusehdot'),
			'gtm' => Yii::t('main', 'Google Tag Manager'),
			'app_naytetaanko_kohteen_yhteyshenkilo' => Yii::t('main', 'Näytetäänkö kohteen yhteyshenkilö'),
			'onlinevaraus_viikonlopput'=> Yii::t('main', 'Näytetäänkö viikonloput'),
			'peruutta_paiva_ennen' => Yii::t('main', 'Montako päivää ennen voidaan peruuttaa'),
			'edico_tehdyt_tyot' => Yii::t('main', 'Edico tehdyt työt'),
			'app_matka_osoite' => Yii::t('main', 'Matka voidaan kirjata osoitteelle'),
			'app_lounastauko_osoite' => Yii::t('main', 'Lounastauko voidaan kirjata osoitteelle'),
			'tuotteet_palvelut_muoto' => Yii::t('main', 'Laskutuksen hinta. Hinnasto / Asiakas'),
			'tyoryhmat' => Yii::t('main', 'Ota työryhmät käyttöön'),
			'tyoryhmat_kohde' => Yii::t('main', 'Työryhmien sisällön rajaus asiakkaiden ja kohteiden mukaan'),
			//'tyoryhmat_tyontekijat' => Yii::t('main', 'Työryhmien sisällön rajaus työntekijöiden mukaan'),
			'tietosuoja_vinkki_sailyttaminen' => Yii::t('main', ' Vinkki henkilötietojen säilyttämisen (pvm määrä)'),
			'palautteet_autovastaus_hyva' => Yii::t('main', ' Palautteet autovastaus HYVÄ'),
			'palautteet_autovastaus_huono' => Yii::t('main', 'Palautteet autovastaus HUONO'),
			'app_auto_hyvaksyminen' => Yii::t('main', 'Automaatinen hyväksyntä'),
			'app_auto_hyvaksyminen_aikavali' => Yii::t('main', 'Aikaero työvuoron ja toteutuneen välillä'),
			'app_hyvaksynnan_peruste' => Yii::t('main', 'Hyväksynnän peruste'),
			'app_auto_hyvaksyminen_tvmukaan' => Yii::t('main', 'Hyväksyt tunnit työvuoron mukaan'),
			'netvisor_mita_onkayttossa' => Yii::t('main', 'Mitä käytössä'),
			'asiakas_laskutus_kanava' => Yii::t('main', 'Laskutus kanava'),
			'asiakas_kirjeenluokka' => Yii::t('main', 'Kirjeenluokka'),
			'asiakas_viivastyskorko' => Yii::t('main', 'Viivästyskorko'),
			'asiakas_maksuehto' => Yii::t('main', 'Maksuehto'),
			'asiakas_myyja' => Yii::t('main', 'Myyjä'),
			'asiakas_tyoryhma' => Yii::t('main', 'Työryhmä'),
			'asiakas_ryhma' => Yii::t('main', 'Asiakasryhmä'),
			'asiakas_alv' => Yii::t('main', 'ALV %'),
			'asiakas_hinta_tyyppi' => Yii::t('main', 'Hinta tyyppi'),
			'asiakas_pakkoliset' => Yii::t('main', 'Pakolliset kentät'),
			'netvisor_accountingaccountsuggestion' => Yii::t('main', 'Myyntilaskut kirjanpidon oletustili'),
			'auto_hyvaksynta_klo' => Yii::t('main', 'Hyväksyminen aika'),
			'netvisor_lahetetaanko_tyontekija' => Yii::t('main', 'Lähetä työntekijä'),
      'app_naytta_sairauslomat' => Yii::t('main', 'Näytetäänkö sairauslomat'),
      'onlinevaraus_palvelu' => Yii::t('main', 'Onlinevaraus Palvelu'),
      'bambora_private_key' => Yii::t('main', 'Bambora Yksityisavain'),
      'bambora_api_key' => Yii::t('main', 'Bambora Api-avain'),
      'omasiistijat_enabled' => Yii::t('main', 'Kohteen omasiistjät, varoitukset ja ilmoitukset'),
      'omasiistijat_email_subject' => Yii::t('main', 'Omasiistjäilmoituksen otsikko'),
      'omasiistijat_email_body' => Yii::t('main', 'Omasiistjäilmoituksen teksti'),
      'aloitusajat_enabled' => Yii::t('main', 'Aloitusaikailmoitukset'),
      'aloitusajat_email_subject' => Yii::t('main', 'Aloitusaikailmoituksen otsikko'),
      'aloitusajat_email_body' => Yii::t('main', 'Aloitusaikailmoituksen teksti'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('syntyrin_emails',$this->syntyrin_emails,true);
		$criteria->compare('paivan_uutinen',$this->paivan_uutinen,true);
		$criteria->compare('logon_polkku',$this->logon_polkku,true);
		$criteria->compare('logon_korkeus',$this->logon_korkeus);
		$criteria->compare('johtaja',$this->johtaja,true);
		$criteria->compare('tilinumero',$this->tilinumero,true);
		$criteria->compare('iban',$this->iban,true);
		$criteria->compare('bic',$this->bic,true);
		$criteria->compare('viivastyskorko',$this->viivastyskorko,true);
		$criteria->compare('palvelu_tyyppi',$this->palvelu_tyyppi);
		$criteria->compare('trust_url',$this->trust_url,true);
		$criteria->compare('pyhapaivat',$this->pyhapaivat,true);
		$criteria->compare('erikoislauantai',$this->erikoislauantai,true);
		$criteria->compare('sovellus_tyovuorot',$this->sovellus_tyovuorot,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function uploadFile($domain, $folder, $fname){
		$domain = strtolower($domain);
		if (!file_exists(Yii::app()->basePath."/../tiedostot/".$folder."/".$domain)) {
		  	mkdir(Yii::app()->basePath."/../tiedostot/".$folder."/".$domain, 0777, true);
		}

		$uploaddir = Yii::app()->basePath."/../tiedostot/".$folder."/".$domain."/";
		$uploadfile = $uploaddir . basename($fname);
		if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
		  	Yii::app()->user->setFlash('success', "Tiedosto tallennettu.");
  		} else {
			Yii::app()->user->setFlash('danger', "Laataminen ei onnistunut.");
		}
	}

	public function getFiles($domain, $folder, $id, $r=false, $delete=true){
		$domain = strtolower($domain);
		$i = 0;
		$return = '';
		foreach(array_reverse(glob('tiedostot/'.$folder.'/'.$domain.'/'.$id.'_*.*')) as $file) {
		$i++;
		$explNimi = explode("/",$file);
	 	$return .= '<div class="form-inline" id="t_'.$id.$i.'">';
		if($delete)
		$return .= '<div class="btn btn-xs btn-danger poistaTiedosto" this="'.$file.'" model="'.$id.'" for="t_'.$id.$i.'">X</div>&nbsp;&nbsp;&nbsp;';

			// <-- file_safe_opener
			$e = explode(".", end($explNimi));
			$ext = $e[1];
			$filepath = $file;
			$return .= CHtml::link(end($explNimi),
				array('/site/file_safe_opener', 'filepath' => $filepath, 'ext' => $ext),
				array('target'=>'_blank','class'=>'text-danger'
			));
			//     file_safe_opener -->

		$return .= '</div>';
		}
		if($r == null)
			echo $return;
		else
			return $return;
	}

	public function uploadImage($domain, $folder, $fname){
		$domain = strtolower($domain);
		if (!file_exists(Yii::app()->basePath."/../img/".$folder."/".$domain)) {
		  	mkdir(Yii::app()->basePath."/../img/".$folder."/".$domain, 0777, true);
		}

		$uploaddir = Yii::app()->basePath."/../img/".$folder."/".$domain."/";
		$uploadfile = $uploaddir . basename($fname);
		if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
			// <-- Image resize
			//header('Content-Type: image/jpeg');
			$url = $uploadfile;
			$width = 640;
			$image = imagecreatefromjpeg($url);
			$orig_width = imagesx($image);
			$orig_height = imagesy($image);
			$height = (($orig_height * $width) / $orig_width);
			$new_image = imagecreatetruecolor($width, $height);
			imagecopyresized($new_image, $image,
				0, 0, 0, 0,
				$width, $height,
				$orig_width, $orig_height);
	
			imagejpeg($new_image, $uploadfile);
			//     Image resize -->
		  	Yii::app()->user->setFlash('success', "Kuva tallennettu.");
  		} else {
			Yii::app()->user->setFlash('danger', "Laataminen ei onnistunut.");
		}
  }
}
