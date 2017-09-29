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
		$check_this_table = true;
		if(!isset(Yii::app()->session[$tb_name]))
		{
			Yii::app()->session[$tb_name] = true;
			$check_this_table = true;
		}


		if($check_this_table)
		{
		$table = Yii::app()->db1->schema->getTable($tb_name);
		if(!isset($table->columns['id'])) {

			Yii::app()->db1->createCommand(" CREATE TABLE IF NOT EXISTS $tb_name 
			(`id` int(11) AUTO_INCREMENT PRIMARY KEY)
			")->execute();
		}

		$table_structure = array(

                     'syntyrin_emails' => 'text ',
                     'paivan_uutinen' => 'varchar(500) ',
                     'logon_polkku' => 'varchar(500) ',
                     'logon_korkeus' => 'int(4) ',
                     'johtaja' => 'varchar(100) ',
                     'viivastyskorko' => 'varchar(50) ',
                     'tilinumero' => 'varchar(100) ',
                     'iban' => 'varchar(100) ',
                     'bic' => 'varchar(100) ',
                     'postita_username' => 'varchar(100) ',
                     'postita_password' => 'varchar(100) ',
                     'trust_cid' => 'varchar(100) ',
                     'trust_api' => 'varchar(100) ',
                     'palvelu_tyyppi' => 'int(1) ',
                     'trust_url' => 'varchar(255) ',
                     'pyhapaivat' => 'text ',
                     'erikoislauantai' => 'text ',
                     'sovellus_tyovuorot' => 'int(2) ',
                     'checkout_id' => 'varchar(100) ',
                     'checkout_salasana' => 'varchar(255) ',
                     'viikonloppulisa_la' => 'varchar(10) ',
                     'viikonloppulisa_su' => 'varchar(10) ',
                     'tilausvahvistus' => 'text ',
                     'oikeudet' => 'text ',
                     'rekisteriseloste' => 'text ',
                     'lasku_asiakasnumero' => 'int(1) ',
                     'show_name' => 'int(1) ',
                     'trust_ws_api_url' => 'varchar(255) ',
                     'trust_ws_cid' => 'varchar(100) ',
                     'trust_ws_salasana' => 'varchar(100) ',
                     'vinkki_tunnit' => 'varchar(10) ',
                     'vinkki_prosentti' => 'varchar(10) ',
                     'onlinevaraus_laatu_luotettavuus' => 'text ',
                     'onlinevaraus_takuu_turvallisuus' => 'text ',
                     'onlinevaraus_asiakaspalvelu' => 'text ',
                     'onlinevaraus_arvio_siivouksesta' => 'text ',
                     'aikavali_halytys' => 'int(3) ',
                     'ilmoitus_avoimista_kohteesta_sahkopostiin' => 'int(1) ',
                     'ilmoitus_myohastyneista_kohteesta_sahkopostiin' => 'int(1) ',
                     'netvisor_customer_id' => 'varchar(255) ',
                     'netvisor_partner_id' => 'varchar(255) ',
                     'netvisor_userkey' => 'varchar(255) ',
                     'netvisor_partnerkey' => 'varchar(255) ',
                     'netvisor_kaytto' => 'int(1) ',
                     'netvisor_organisation_identifier' => 'varchar(255) ',
                     'merkkipaivailmoitukset_sahkoposti' => 'varchar(255) ',
                     'asiakas_tyovuorossa' => 'int(1) ',
                     'onlinevaraus_aikaisintaan_paivamaara' => 'int(2) ',
                     'onlinevaraus_alku' => 'int(2) ',
                     'onlinevaraus_loppu' => 'int(2) ',
                     'app_show_phone' => 'int(1) ',
                     'paikkakunta_tyovuorossa' => 'int(1) ',
                     'app_lopettaa_vain_tagilla' => 'int(1) ',
                     'ilmoitus_toistuvien_tyovuorojen_paattymisesta' => 'int(1) ',
                     'ilmoitus_toistuvien_tyovuorojen_paattymisesta_paivat_ennen' => 'int(3) ',
                     'ilmoitus_toistuvien_tyovuorojen_paattymisesta_saajat' => 'text ',
                     'tyovuorolahetys_naytetaanko_asiakas' => 'int(1) ',
                     'tyovuorolahetys_naytetaanko_kohteen_postitoimipaikka' => 'int(1) ',
                     'ilmoitus_merkkipaivasta' => 'int(1) ',
                     'ilmoitus_uudesta_kuvasta_saajat' => 'text ',
                     'netvisor_host' => 'varchar(500) ',
                     'tyontekijan_etunimi_sukunimi_jarjestys' => 'int(1) ',
                     'netvisor_acceptancestatus' => 'varchar(50) ',
                     'netvisor_mita_lahetetaan' => 'varchar(255) ',
                     'app_hyvaksytyt_tyot_vkomaara' => 'int(3) DEFAULT 4 ',
                     'app_naytetaanko_hyvaksyttyt_tunnit' => 'int(1) ',
                     'app_naytta_avain' => 'int(1) ',
                     'edico_laatutaso_1' => 'text ',
                     'edico_laatutaso_2' => 'text ',
                     'edico_laatutaso_3' => 'text ',
                     'edico_muut_kulut' => 'text ',
                     'tapaturmavakuutus' => 'int(11) ',
                     'ryhmahenkivakuutus' => 'int(11) ',
                     'tyottomyysvakuutusmaksu' => 'int(11) ',
                     'sosiaaliturvamaksu' => 'int(11) ',
                     'tyel_maksun_osuus_palkkansummasta' => 'int(11) ',
                     'peruutusehdot' => 'text ',
                     'gtm' => 'varchar(255) ',
                     'app_naytetaanko_kohteen_yhteyshenkilo' => 'int(1) ',
                     'onlinevaraus_viikonlopput' => 'int(1) ',
                     'maksullinen' => 'int(1) DEFAULT 1 ',
                     'ilmainen_versio_kayttotunnit' => 'int(11) ',
                     'alennus_max_euro' => 'float ',
                     'alennus_max_prosentti' => 'float ',
                     'peruutta_paiva_ennen' => 'int(2) YES ',
		     'edico_tehdyt_tyot' => 'varchar(100)',
		     'netvisor_lahetetaanko_tyontekija' => 'int(1)',
		     'lasketaanko_lounastauko' => 'int(1)',

		);
		$is_added_somthing = false;
		foreach($table_structure as $key=>$value)
		{
			if (!isset($table->columns[$key])) {
				Yii::app()->db1->createCommand()->addColumn($tb_name, $key, $value);
				$is_added_somthing = true;
			}
		}
		if($is_added_somthing)
		Yii::app()->controller->refresh();


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
			array('id,logon_polkku, logon_korkeus, johtaja', 'required'),
			array('id, show_name, app_show_phone, sovellus_tyovuorot, logon_korkeus, palvelu_tyyppi, lasku_asiakasnumero, ilmoitus_avoimista_kohteesta_sahkopostiin, ilmoitus_myohastyneista_kohteesta_sahkopostiin, netvisor_kaytto, asiakas_tyovuorossa, onlinevaraus_aikaisintaan_paivamaara, onlinevaraus_alku, onlinevaraus_loppu, paikkakunta_tyovuorossa, app_lopettaa_vain_tagilla, ilmoitus_toistuvien_tyovuorojen_paattymisesta, ilmoitus_toistuvien_tyovuorojen_paattymisesta_paivat_ennen, tyovuorolahetys_naytetaanko_asiakas, tyovuorolahetys_naytetaanko_kohteen_postitoimipaikka, ilmoitus_merkkipaivasta, tyontekijan_etunimi_sukunimi_jarjestys, app_hyvaksytyt_tyot_vkomaara, app_naytetaanko_hyvaksyttyt_tunnit, app_naytta_avain, tapaturmavakuutus, ryhmahenkivakuutus, tyottomyysvakuutusmaksu, sosiaaliturvamaksu, tyel_maksun_osuus_palkkansummasta, app_naytetaanko_kohteen_yhteyshenkilo, onlinevaraus_viikonlopput, maksullinen, ilmainen_versio_kayttotunnit, alennus_max_euro, alennus_max_prosentti, peruutta_paiva_ennen, lasketaanko_lounastauko, apuaika_meneeko_laskutukseen, netvisor_lahetetaanko_tyontekija', 'numerical', 'integerOnly'=>true),
			array('paivan_uutinen, logon_polkku, netvisor_host', 'length', 'max'=>500),
			array('johtaja, viivastyskorko, tilinumero, iban, bic, , postita_username, postita_password, trust_cid, trust_api, checkout_id, trust_ws_cid, trust_ws_salasana, netvisor_acceptancestatus, edico_tehdyt_tyot', 'length', 'max'=>100),
			array('trust_url, checkout_salasana, trust_ws_api_url, netvisor_customer_id, netvisor_partner_id, netvisor_userkey, netvisor_partnerkey, netvisor_organisation_identifier, merkkipaivailmoitukset_sahkoposti, netvisor_mita_lahetetaan, gtm, apuaika_palkkalaji', 'length', 'max'=>255),
			array('viikonloppulisa_la, viikonloppulisa_su, vinkki_tunnit, vinkki_prosentti', 'length', 'max'=>10),
			array('aikavali_halytys', 'length', 'max'=>3),
			array('oikeudet, pyhapaivat, erikoislauantai, tilausvahvistus, rekisteriseloste, onlinevaraus_laatu_luotettavuus, onlinevaraus_takuu_turvallisuus, onlinevaraus_asiakaspalvelu, onlinevaraus_arvio_siivouksesta, ilmoitus_toistuvien_tyovuorojen_paattymisesta_saajat, ilmoitus_uudesta_kuvasta_saajat, edico_muut_kulut, edico_laatutaso_1, edico_laatutaso_2, edico_laatutaso_3,peruutusehdot', 'safe'),
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
			'peruutusehdot' => Yii::t('main', 'Asiakkaalle naytettavat varauksen peruutusehdot'),
			'gtm' => Yii::t('main', 'Google Tag Manager'),
			'app_naytetaanko_kohteen_yhteyshenkilo' => Yii::t('main', 'Näytetäänkö kohteen yhteyshenkilö'),
			'onlinevaraus_viikonlopput'=> Yii::t('main', 'Näytetäänkö viikonloput'),
			'peruutta_paiva_ennen' => Yii::t('main', 'Montako päivää ennen voidaan peruuttaa'),
			'apuaika_meneeko_laskutukseen' => Yii::t('main', 'Meneeko laskutukseen'),
			'apuaika_palkkalaji' => Yii::t('main', 'Palkkalaji'),
			'edico_tehdyt_tyot' => Yii::t('main', 'Edico tehdyt työt'),
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
