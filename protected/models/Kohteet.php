<?php

/**
 * This is the model class for table "sivex_kohdet".
 *
 * The followings are the available columns in table 'sivex_kohdet':
 * @property integer $id
 * @property string $time
 * @property string $tag_id
 * @property string $gps_sijainti
 * @property string $lyhenne
 * @property string $osoite
 * @property string $katuosoite
 * @property string $kaupunki
 * @property string $toimipaikka
 * @property string $pnumero
 * @property string $email
 * @property string $aikataulu
 * @property string $hinnoittelu
 * @property string $muut
 * @property string $toimenpiteet
 * @property string $tietoja
 * @property string $tyoryhma
 * @property string $ryhma
 * @property integer $aktiivinen
 * @property string $avain
 * @property string $kenella_on_avain
 * @property string $puh_nro
 * @property string $siivous
 * @property string $etu_suku_nimet
 * @property integer $maksuehto_paiva
 * @property string $viivastyskorko
 * @property string $lasku_tiedot
 */
class Kohteet extends DB2ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Kohteet the static model class
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
		return 'sivex_kohdet';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('asiakas_id,etu_suku_nimet,osoite', 'required'),
			array('asiakas_id,aktiivinen, maksuehto_paiva', 'numerical', 'integerOnly'=>true),
			array('tag_id, kaupunki, toimipaikka, tyoryhma', 'length', 'max'=>20),
			array('gps_sijainti, osoite, katuosoite, kenella_on_avain, puh_nro', 'length', 'max'=>50),
			array('lyhenne', 'length', 'max'=>46),
			array('pnumero', 'length', 'max'=>7),
			array('email', 'length', 'max'=>72),
			array('ryhma, viivastyskorko', 'length', 'max'=>10),
			array('avain, lasku_tiedot', 'length', 'max'=>255),
			array('siivous, etu_suku_nimet', 'length', 'max'=>100),
			array('aikataulu, hinnoittelu, muut, toimenpiteet, tietoja', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, time, tag_id, gps_sijainti, lyhenne, osoite, katuosoite, kaupunki, toimipaikka, pnumero, email, aikataulu, hinnoittelu, muut, toimenpiteet, tietoja, tyoryhma, ryhma, aktiivinen, avain, kenella_on_avain, puh_nro, siivous, etu_suku_nimet, maksuehto_paiva, viivastyskorko, lasku_tiedot, asiakas_id', 'safe', 'on'=>'search'),
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
			'asiakas_id' => 'Asiakas',
			'time' => 'Luotu',
			'tag_id' => 'Tag',
			'gps_sijainti' => 'Gps Sijainti',
			'lyhenne' => 'Lyhenne',
			'osoite' => 'Osoite',
			'katuosoite' => 'Katuosoite',
			'kaupunki' => 'Kaupunki',
			'toimipaikka' => 'Toimipaikka',
			'pnumero' => 'Postitoimipaikka',
			'email' => 'Sähköposti',
			'aikataulu' => 'Aikataulu',
			'hinnoittelu' => 'Hinnoittelu',
			'muut' => 'Muut',
			'toimenpiteet' => 'Toimenpiteet',
			'tietoja' => 'Tietoja',
			'tyoryhma' => 'Työryhmä',
			'ryhma' => 'Toimialue',
			'aktiivinen' => 'Aktiivinen',
			'avain' => 'Avain',
			'kenella_on_avain' => 'Kenellä avain',
			'puh_nro' => 'Puh Nro',
			'siivous' => 'Työnimike',
			'etu_suku_nimet' => 'Nimi',
			'maksuehto_paiva' => 'Maksuehto Paiva',
			'viivastyskorko' => 'Viivästyskorko',
			'lasku_tiedot' => 'Lasku Tiedot',
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
		$criteria->order = "osoite";

		$criteria->compare('id',$this->id);
		$criteria->compare('asiakas_id',$this->asiakas_id);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('tag_id',$this->tag_id,true);
		$criteria->compare('gps_sijainti',$this->gps_sijainti,true);
		$criteria->compare('lyhenne',$this->lyhenne,true);
		$criteria->compare('osoite',$this->osoite,true);
		$criteria->compare('katuosoite',$this->katuosoite,true);
		$criteria->compare('kaupunki',$this->kaupunki,true);
		$criteria->compare('toimipaikka',$this->toimipaikka,true);
		$criteria->compare('pnumero',$this->pnumero,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('aikataulu',$this->aikataulu,true);
		$criteria->compare('hinnoittelu',$this->hinnoittelu,true);
		$criteria->compare('muut',$this->muut,true);
		$criteria->compare('toimenpiteet',$this->toimenpiteet,true);
		$criteria->compare('tietoja',$this->tietoja,true);
		$criteria->compare('tyoryhma',$this->tyoryhma,true);
		$criteria->compare('ryhma',$this->ryhma,true);
		$criteria->compare('aktiivinen',$this->aktiivinen);
		$criteria->compare('avain',$this->avain,true);
		$criteria->compare('kenella_on_avain',$this->kenella_on_avain,true);
		$criteria->compare('puh_nro',$this->puh_nro,true);
		$criteria->compare('siivous',$this->siivous,true);
		$criteria->compare('etu_suku_nimet',$this->etu_suku_nimet,true);
		$criteria->compare('maksuehto_paiva',$this->maksuehto_paiva);
		$criteria->compare('viivastyskorko',$this->viivastyskorko,true);
		$criteria->compare('lasku_tiedot',$this->lasku_tiedot,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
