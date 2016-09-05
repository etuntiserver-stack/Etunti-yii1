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
		return 'asetukset';
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
			array('id, show_name, sovellus_tyovuorot, logon_korkeus, palvelu_tyyppi, lasku_asiakasnumero, ilmoitus_avoimista_kohteesta_sahkopostiin, ilmoitus_myohastyneista_kohteesta_sahkopostiin, netvisor_kaytto, asiakas_tyovuorossa, onlinevaraus_aikaisintaan_paivamaara, onlinevaraus_alku, onlinevaraus_loppu', 'numerical', 'integerOnly'=>true),
			array('paivan_uutinen, logon_polkku', 'length', 'max'=>500),
			array('johtaja, viivastyskorko, tilinumero, iban, bic, , postita_username, postita_password, trust_cid, trust_api, checkout_id, trust_ws_cid, trust_ws_salasana', 'length', 'max'=>100),
			array('trust_url, checkout_salasana, trust_ws_api_url, netvisor_customer_id, netvisor_partner_id, netvisor_userkey, netvisor_partnerkey, netvisor_organisation_identifier, merkkipaivailmoitukset_sahkoposti', 'length', 'max'=>255),
			array('viikonloppulisa_la, viikonloppulisa_su, vinkki_tunnit, vinkki_prosentti', 'length', 'max'=>10),
			array('aikavali_halytys', 'length', 'max'=>3),
			array('oikeudet, pyhapaivat, erikoislauantai, tilausvahvistus, rekisteriseloste, onlinevaraus_laatu_luotettavuus, onlinevaraus_takuu_turvallisuus, onlinevaraus_asiakaspalvelu, onlinevaraus_arvio_siivouksesta', 'length', 'max'=>10000),
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
			'checkout_id'=> Yii::t('main', 'Checkout tunnus'),
			'checkout_salasana'=> Yii::t('main', 'Checkout salasana'),
			'viikonloppulisa_la'=> Yii::t('main', 'Lauantai %'),
			'viikonloppulisa_su'=> Yii::t('main', 'Sunnuntai %'),
			'lasku_asiakasnumero'=> Yii::t('main', 'Syöttääkö itse asiakasnumeron vai lasketaan edellisestä automaattisesti'),
			'show_name' => Yii::t('main', 'Näytä asiakas nimi ja puhelinnumero'),
			'trust_ws_api_url'=> Yii::t('main', 'Trust WS API'),

			'onlinevaraus_laatu_luotettavuus' => Yii::t('main', 'Laatu ja luotettavuus'),
			'onlinevaraus_takuu_turvallisuus' => Yii::t('main', 'Takuu ja turvallisuus'),
			'onlinevaraus_asiakaspalvelu' => Yii::t('main', 'Asiakaspalvelu'),
			'onlinevaraus_arvio_siivouksesta' => Yii::t('main', 'Arvio siivouksesta'),
			'aikavali_halytys' => Yii::t('main', 'Aikaväli hälytys (min)'),
			'ilmoitus_avoimista_kohteesta_sahkopostiin' => Yii::t('main', 'Ilmoitus määräajan ylittäneistä kohteista sähköpostiin'),
			'ilmoitus_myohastyneista_kohteesta_sahkopostiin' => Yii::t('main', 'Ilmoitus myöhästyneistä kohteesta sähköpostiin'),
			'merkkipaivailmoitukset_sahkoposti' => Yii::t('main', 'Sähköposti, johoon tulevat merkkipäiväilmoitukset'),
			'asiakas_tyovuorossa'=>Yii::t('main', 'Asiakas työvuorossa'),
			'onlinevaraus_aikaisintaan_paivamaara'=>Yii::t('main', 'Monenko päivän päästä vuoroja voi varata.'),
			'onlinevaraus_alku'=>Yii::t('main', 'Varaukset alku'),
			'onlinevaraus_loppu'=>Yii::t('main', 'Varaukset loppu'),
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
