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
 */
class Asetukset extends DB2ActiveRecord
{
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

		$table = Yii::app()->db1->schema->getTable($tb_name);
		if(!isset($table->columns['id'])) {

			Yii::app()->db1->createCommand(" CREATE TABLE IF NOT EXISTS $tb_name 
			(`id` int(11) AUTO_INCREMENT PRIMARY KEY)
			")->execute();
		}

		$table_structure = array(

                     'syntyrin_emails' => 'text AFTER id',
                     'paivan_uutinen' => 'varchar(500) AFTER syntyrin_emails',
                     'logon_polkku' => 'varchar(500) AFTER paivan_uutinen',
                     'logon_korkeus' => 'int(4) AFTER logon_polkku',
                     'johtaja' => 'varchar(100) AFTER logon_korkeus',
                     'viivastyskorko' => 'varchar(50) AFTER johtaja',
                     'tilinumero' => 'varchar(100) AFTER viivastyskorko',
                     'iban' => 'varchar(100) AFTER tilinumero',
                     'bic' => 'varchar(100) AFTER iban',
                     'postita_username' => 'varchar(100) AFTER bic',
                     'postita_password' => 'varchar(100) AFTER postita_username',
                     'trust_cid' => 'varchar(100) AFTER postita_password',
                     'trust_api' => 'varchar(100) AFTER trust_cid',
                     'palvelu_tyyppi' => 'int(1) AFTER trust_api',
                     'trust_url' => 'varchar(255) AFTER palvelu_tyyppi',
                     'pyhapaivat' => 'text AFTER trust_url',
                     'erikoislauantai' => 'text AFTER pyhapaivat',
                     'sovellus_tyovuorot' => 'int(2) AFTER erikoislauantai',
                     'checkout_id' => 'varchar(100) AFTER sovellus_tyovuorot',
                     'checkout_salasana' => 'varchar(255) AFTER checkout_id',
                     'viikonloppulisa_la' => 'varchar(10) AFTER checkout_salasana',
                     'viikonloppulisa_su' => 'varchar(10) AFTER viikonloppulisa_la',
                     'tilausvahvistus' => 'text AFTER viikonloppulisa_su',
                     'oikeudet' => 'text AFTER tilausvahvistus',
                     'rekisteriseloste' => 'text AFTER oikeudet',
                     'lasku_asiakasnumero' => 'int(1) AFTER rekisteriseloste',
                     'show_name' => 'int(1) AFTER lasku_asiakasnumero',
                     'trust_ws_api_url' => 'varchar(255) AFTER show_name',
                     'trust_ws_cid' => 'varchar(100) AFTER trust_ws_api_url',
                     'trust_ws_salasana' => 'varchar(100) AFTER trust_ws_cid',
                     'vinkki_tunnit' => 'varchar(10) AFTER trust_ws_salasana',
                     'vinkki_prosentti' => 'varchar(10) AFTER vinkki_tunnit',
                     'onlinevaraus_laatu_luotettavuus' => 'text AFTER vinkki_prosentti',
                     'onlinevaraus_takuu_turvallisuus' => 'text AFTER onlinevaraus_laatu_luotettavuus',
                     'onlinevaraus_asiakaspalvelu' => 'text AFTER onlinevaraus_takuu_turvallisuus',
                     'onlinevaraus_arvio_siivouksesta' => 'text AFTER onlinevaraus_asiakaspalvelu',
                     'aikavali_halytys' => 'int(3) AFTER onlinevaraus_arvio_siivouksesta',
                     'ilmoitus_avoimista_kohteesta_sahkopostiin' => 'int(1) AFTER aikavali_halytys',
                     'ilmoitus_myohastyneista_kohteesta_sahkopostiin' => 'int(1) AFTER ilmoitus_avoimista_kohteesta_sahkopostiin',
                     'netvisor_customer_id' => 'varchar(255) AFTER ilmoitus_myohastyneista_kohteesta_sahkopostiin',
                     'netvisor_partner_id' => 'varchar(255) AFTER netvisor_customer_id',
                     'netvisor_userkey' => 'varchar(255) AFTER netvisor_partner_id',
                     'netvisor_partnerkey' => 'varchar(255) AFTER netvisor_userkey',
                     'netvisor_kaytto' => 'int(1) AFTER netvisor_partnerkey',
                     'netvisor_organisation_identifier' => 'varchar(255) AFTER netvisor_kaytto',
                     'merkkipaivailmoitukset_sahkoposti' => 'varchar(255) AFTER netvisor_organisation_identifier',
                     'asiakas_tyovuorossa' => 'int(1) AFTER merkkipaivailmoitukset_sahkoposti',
                     'onlinevaraus_aikaisintaan_paivamaara' => 'int(2) AFTER asiakas_tyovuorossa',
                     'onlinevaraus_alku' => 'int(2) AFTER onlinevaraus_aikaisintaan_paivamaara',
                     'onlinevaraus_loppu' => 'int(2) AFTER onlinevaraus_alku',
                     'app_show_phone' => 'int(1) AFTER onlinevaraus_loppu',
                     'paikkakunta_tyovuorossa' => 'int(1) AFTER app_show_phone',
                     'app_lopettaa_vain_tagilla' => 'int(1) AFTER paikkakunta_tyovuorossa',
                     'ilmoitus_toistuvien_tyovuorojen_paattymisesta' => 'int(1) AFTER app_lopettaa_vain_tagilla',
                     'ilmoitus_toistuvien_tyovuorojen_paattymisesta_paivat_ennen' => 'int(3) AFTER ilmoitus_toistuvien_tyovuorojen_paattymisesta',
                     'ilmoitus_toistuvien_tyovuorojen_paattymisesta_saajat' => 'text AFTER ilmoitus_toistuvien_tyovuorojen_paattymisesta_paivat_ennen',
                     'tyovuorolahetys_naytetaanko_asiakas' => 'int(1) AFTER ilmoitus_toistuvien_tyovuorojen_paattymisesta_saajat',
                     'tyovuorolahetys_naytetaanko_kohteen_postitoimipaikka' => 'int(1) AFTER tyovuorolahetys_naytetaanko_asiakas',
                     'ilmoitus_merkkipaivasta' => 'int(1) AFTER tyovuorolahetys_naytetaanko_kohteen_postitoimipaikka',
                     'ilmoitus_uudesta_kuvasta_saajat' => 'text AFTER ilmoitus_merkkipaivasta',
                     'netvisor_host' => 'varchar(500) AFTER ilmoitus_uudesta_kuvasta_saajat',
                     'tyontekijan_etunimi_sukunimi_jarjestys' => 'int(1) AFTER netvisor_host',
                     'netvisor_acceptancestatus' => 'varchar(50) AFTER tyontekijan_etunimi_sukunimi_jarjestys',
                     'netvisor_mita_lahetetaan' => 'varchar(255) AFTER netvisor_acceptancestatus',
                     'app_hyvaksytyt_tyot_vkomaara' => 'int(3) 4 AFTER netvisor_mita_lahetetaan',
                     'app_naytetaanko_hyvaksyttyt_tunnit' => 'int(1) AFTER app_hyvaksytyt_tyot_vkomaara',
                     'app_naytta_avain' => 'int(1) AFTER app_naytetaanko_hyvaksyttyt_tunnit',
                     'edico_laatutaso_1' => 'text AFTER app_naytta_avain',
                     'edico_laatutaso_2' => 'text AFTER edico_laatutaso_1',
                     'edico_laatutaso_3' => 'text AFTER edico_laatutaso_2',
                     'edico_muut_kulut' => 'text AFTER edico_laatutaso_3',
                     'tapaturmavakuutus' => 'int(11) AFTER edico_muut_kulut',
                     'ryhmahenkivakuutus' => 'int(11) AFTER tapaturmavakuutus',
                     'tyottomyysvakuutusmaksu' => 'int(11) AFTER ryhmahenkivakuutus',
                     'sosiaaliturvamaksu' => 'int(11) AFTER tyottomyysvakuutusmaksu',
                     'tyel_maksun_osuus_palkkansummasta' => 'int(11) AFTER sosiaaliturvamaksu',
                     'gtm' => 'varchar(255) AFTER tyel_maksun_osuus_palkkansummasta',
                     'app_naytetaanko_kohteen_yhteyshenkilo' => 'int(1) AFTER gtm',
                     'onlinevaraus_viikonlopput' => 'int(1) AFTER app_naytetaanko_kohteen_yhteyshenkilo',
                     'maksullinen' => 'int(1) 1 AFTER onlinevaraus_viikonlopput',
                     'ilmainen_versio_kayttotunnit' => 'int(11) AFTER maksullinen',
                     'alennus_max_euro' => 'float AFTER ilmainen_versio_kayttotunnit',
                     'alennus_max_prosentti' => 'float AFTER alennus_max_euro',
                     'peruutta_paiva_ennen' => 'int(2) YES AFTER alennus_max_prosentti',
                     'lasketaanko_lounastauko' => 'int(1) AFTER peruutta_paiva_ennen',
                     'apuaika_meneeko_laskutukseen' => 'int(1) AFTER lasketaanko_lounastauko',
                     'apuaika_palkkalaji' => 'varchar(255) AFTER apuaika_meneeko_laskutukseen',


		);

		foreach($table_structure as $key=>$value)
		{
			if (!isset($table->columns[$key])) {
				Yii::app()->db1->createCommand()->addColumn($tb_name, $key, $value);
			}
		}	

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
			array('id,logon_polkku, logon_korkeus, johtaja', 'required'),
			array('id, show_name, app_show_phone, sovellus_tyovuorot, logon_korkeus, palvelu_tyyppi, lasku_asiakasnumero, ilmoitus_avoimista_kohteesta_sahkopostiin, ilmoitus_myohastyneista_kohteesta_sahkopostiin, netvisor_kaytto, asiakas_tyovuorossa, onlinevaraus_aikaisintaan_paivamaara, onlinevaraus_alku, onlinevaraus_loppu, paikkakunta_tyovuorossa, app_lopettaa_vain_tagilla, ilmoitus_toistuvien_tyovuorojen_paattymisesta, ilmoitus_toistuvien_tyovuorojen_paattymisesta_paivat_ennen, tyovuorolahetys_naytetaanko_asiakas, tyovuorolahetys_naytetaanko_kohteen_postitoimipaikka, ilmoitus_merkkipaivasta, tyontekijan_etunimi_sukunimi_jarjestys, app_hyvaksytyt_tyot_vkomaara, app_naytetaanko_hyvaksyttyt_tunnit, app_naytta_avain, tapaturmavakuutus, ryhmahenkivakuutus, tyottomyysvakuutusmaksu, sosiaaliturvamaksu, tyel_maksun_osuus_palkkansummasta, app_naytetaanko_kohteen_yhteyshenkilo, onlinevaraus_viikonlopput, maksullinen, ilmainen_versio_kayttotunnit, alennus_max_euro, alennus_max_prosentti, peruutta_paiva_ennen, lasketaanko_lounastauko, apuaika_meneeko_laskutukseen', 'numerical', 'integerOnly'=>true),
			array('paivan_uutinen, logon_polkku, netvisor_host', 'length', 'max'=>500),
			array('johtaja, viivastyskorko, tilinumero, iban, bic, , postita_username, postita_password, trust_cid, trust_api, checkout_id, trust_ws_cid, trust_ws_salasana, netvisor_acceptancestatus', 'length', 'max'=>100),
			array('trust_url, checkout_salasana, trust_ws_api_url, netvisor_customer_id, netvisor_partner_id, netvisor_userkey, netvisor_partnerkey, netvisor_organisation_identifier, merkkipaivailmoitukset_sahkoposti, netvisor_mita_lahetetaan, gtm, apuaika_palkkalaji', 'length', 'max'=>255),
			array('viikonloppulisa_la, viikonloppulisa_su, vinkki_tunnit, vinkki_prosentti', 'length', 'max'=>10),
			array('aikavali_halytys', 'length', 'max'=>3),
			array('oikeudet, pyhapaivat, erikoislauantai, tilausvahvistus, rekisteriseloste, onlinevaraus_laatu_luotettavuus, onlinevaraus_takuu_turvallisuus, onlinevaraus_asiakaspalvelu, onlinevaraus_arvio_siivouksesta, ilmoitus_toistuvien_tyovuorojen_paattymisesta_saajat, ilmoitus_uudesta_kuvasta_saajat, edico_muut_kulut, edico_laatutaso_1, edico_laatutaso_2, edico_laatutaso_3', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, syntyrin_emails, paivan_uutinen, logon_polkku, logon_korkeus, johtaja, viivastyskorko, tilinumero, iban, bic, trust_cid, trust_api, palvelu_tyyppi, trust_url, pyhapaivat, erikoislauantai, sovellus_tyovuorot', 'safe', 'on'=>'search'),
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
			'trust_url'=> Yii::t('main', 'Trust URL'),
			'pyhapaivat'=> Yii::t('main', 'Viralliset pyhäpäivät / pp.kk.vvvv'),
			'erikoislauantai' => Yii::t('main', 'Erikoislauantai'),
			'sovellus_tyovuorot'=> Yii::t('main', 'Työvuorojen näyttäminen'),
			'checkout_id'=> Yii::t('main', 'Kauppiastunnus'),
			'checkout_salasana'=> Yii::t('main', 'Turva-avain'),
			'viikonloppulisa_la'=> Yii::t('main', 'Lauantai %'),
			'viikonloppulisa_su'=> Yii::t('main', 'Sunnuntai %'),
			'lasku_asiakasnumero'=> Yii::t('main', 'Syöttääkö itse asiakasnumeron vai lasketaan edellisestä automaattisesti'),
			'show_name' => Yii::t('main', 'Näytä asiakas nimi'),
			'app_show_phone' => Yii::t('main', 'Näytä kohteen puhelinnumero'),
			'trust_ws_api_url'=> Yii::t('main', 'Trust WS API'),
			'onlinevaraus_laatu_luotettavuus' => Yii::t('main', 'Laatu ja luotettavuus'),
			'onlinevaraus_takuu_turvallisuus' => Yii::t('main', 'Takuu ja turvallisuus'),
			'onlinevaraus_asiakaspalvelu' => Yii::t('main', 'Asiakaspalvelu'),
			'onlinevaraus_arvio_siivouksesta' => Yii::t('main', 'Arvio palvelusta'),
			'aikavali_halytys' => Yii::t('main', 'Aikaväli hälytys (min)'),
			'ilmoitus_avoimista_kohteesta_sahkopostiin' => Yii::t('main', 'Ilmoitus määräajan ylittäneistä kohteista sähköpostiin'),
			'ilmoitus_myohastyneista_kohteesta_sahkopostiin' => Yii::t('main', 'Ilmoitus myöhästyneistä kohteesta sähköpostiin'),
			'merkkipaivailmoitukset_sahkoposti' => Yii::t('main', 'Sähköposti, johoon tulevat merkkipäiväilmoitukset'),
			'asiakas_tyovuorossa'=>Yii::t('main', 'Näytetäänkö asiakas työvuorossa'),
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
			'ilmoitus_merkkipaivasta'=>Yii::t('main', 'Ilmoitus merkkipäivästä sähköpostiin'),
			'ilmoitus_uudesta_kuvasta_saajat'=>Yii::t('main', 'Sähköpostit johon lähetetään ilmoitus uudesta kuvasta.'),
			'tyontekijan_etunimi_sukunimi_jarjestys'=>Yii::t('main', 'Työntekijän etunimi ja sukunimi järjestys'),
			'netvisor_mita_lahetetaan'=>Yii::t('main', 'Mitä lähetetään'),
			'netvisor_acceptancestatus'=>Yii::t('main', 'Acceptancestatus'),
			'app_hyvaksytyt_tyot_vkomaara'=>Yii::t('main', 'Hyväksytyt työt vko määrä'),
			'app_naytetaanko_hyvaksyttyt_tunnit'=>Yii::t('main', 'Näytetäänkö hyväksytyt tunnit'),
			'app_naytta_avain'=>Yii::t('main', 'Näytetäänkö avaimet'),
			'edico_laatutaso_1' => Yii::t('main', 'Laatutaso 1'),
			'edico_laatutaso_2' => Yii::t('main', 'Laatutaso 2'),
			'edico_laatutaso_3' => Yii::t('main', 'Laatutaso 3'),
			'edico_muut_kulut' => Yii::t('main', 'Muut kulut'),
			'tapaturmavakuutus' => Yii::t('main', 'TAPATURMAVAKUUTUS '),
			'ryhmahenkivakuutus' => Yii::t('main', 'RYHMÄHENKIVAKUUTUS'),
			'tyottomyysvakuutusmaksu' => Yii::t('main', 'TYÖTTÖMYYSVAKUUTUSMAKSU'),
			'sosiaaliturvamaksu' => Yii::t('main', 'SOSIAALITURVAMAKSU'),
			'tyel_maksun_osuus_palkkansummasta' => Yii::t('main', 'TyEL-MAKSUN OSUUS PALKKASUMMASTA'),
			'gtm' => Yii::t('main', 'Google Tag Manager'),
			'app_naytetaanko_kohteen_yhteyshenkilo' => Yii::t('main', 'Näytetäänkö kohteen yhteyshenkilö'),
			'onlinevaraus_viikonlopput'=> Yii::t('main', 'Näytetäänkö viikonloput'),
			'peruutta_paiva_ennen' => Yii::t('main', 'Montako päivää ennen voidaan peruuttaa'),
			'apuaika_meneeko_laskutukseen' => Yii::t('main', 'Meneeko laskutukseen'),
			'apuaika_palkkalaji' => Yii::t('main', 'Palkkalaji'),
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
}
