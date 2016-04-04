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
			array('id, sovellus_tyovuorot, logon_korkeus, palvelu_tyyppi', 'numerical', 'integerOnly'=>true),
			array('paivan_uutinen, logon_polkku', 'length', 'max'=>500),
			array('johtaja, viivastyskorko, tilinumero, iban, bic, , postita_username, postita_password, trust_cid, trust_api, checkout_id', 'length', 'max'=>100),
			array('trust_url, checkout_salasana', 'length', 'max'=>255),
			array('viikonloppulisa_la, viikonloppulisa_su', 'length', 'max'=>10),
			array('oikeudet, pyhapaivat, erikoislauantai, tilausvahvistus', 'length', 'max'=>10000),
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
			'id' => 'ID',
			'syntyrin_emails' => 'Syntyrin Emails',
			'paivan_uutinen' => 'Paivan Uutinen',
			'logon_polkku' => 'Logo',
			'logon_korkeus' => 'Logon Korkeus',
			'johtaja' => 'Johtaja',
			'tilinumero' => 'Tilinumero',
			'iban' => 'IBAN',
			'bic' => 'BIC',
			'viivastyskorko' => 'Viivästyskorko %',
			'postita_username' => 'Postita käyttäjätunnus',
			'postita_password' => 'Postita salasana',
			'palvelu_tyyppi' => 'Palvelutyyppi',
			'trust_url'=>'Trust URL',
			'pyhapaivat'=>'Viralliset pyhäpäivät / pp.kk.vvvv',
			'erikoislauantai' => 'Erikoislauantai',
			'sovellus_tyovuorot'=>'Työvuorojen näyttäminen',
			'checkout_id'=>'Checkout tunnus',
			'checkout_salasana'=>'Checkout salasana',
			'viikonloppulisa_la'=>'Lauantai %',
			'viikonloppulisa_su'=>'Sunnuntai %',
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
