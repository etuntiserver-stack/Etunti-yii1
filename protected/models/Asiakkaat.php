<?php

/**
 * This is the model class for table "asiakkaat".
 *
 * The followings are the available columns in table 'asiakkaat':
 * @property integer $id
 * @property string $time
 * @property string $etunimi
 * @property string $sukunimi
 * @property string $osoite
 * @property string $kaupunki
 * @property integer $postinumero
 * @property string $puhelin
 * @property string $sahkoposti
 * @property integer $ryhma
 * @property integer $aktiivinen
 */
class Asiakkaat extends DB2ActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Asiakkaat the static model class
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
		return 'asiakkaat';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tyyppi, osoite, sahkoposti', 'required'),
                        array('asiakasnumero','unique', 'message'=>'Tämä asiakasnumero on jo olemassa!'),
			//array('etunimi, sukunimi, osoite, kaupunki, postinumero, puhelin, sahkoposti, ryhma, aktiivinen', 'required'),
			array('kirjeenluokka, muistutuslasku_auto, ryhma, aktiivinen', 'numerical', 'integerOnly'=>true),
			array('myyja, postinumero, yhteyshenkilo, yrityksen_nimi, y_tunnus, kaupunki, puhelin, sahkoposti', 'length', 'max'=>100),
			array('tyyppi, laskutus_kanava, osoite, verkkolaskuosoite', 'length', 'max'=>255),
			array('maksuehto', 'length', 'max'=>20),
			array('alv', 'length', 'max'=>3),
			array('hinta_tyyppi', 'length', 'max'=>50),
			array('hinta', 'length', 'max'=>10),
			array('asiakasnumero, ovt_tunnus, valittajan_tunnus', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, time, etunimi, sukunimi, osoite, kaupunki, postinumero, puhelin, sahkoposti, ryhma, aktiivinen, laskutus_kanava,maksuehto, tyyppi, asiakasnumero, ovt_tunnus, valittajan_tunnus, myyja, kirjeenluokka, muistutuslasku_auto', 'safe', 'on'=>'search'),
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
			'asiakasnumero' => 'Asiakasnumero',
			'time' => 'Luotu',
			'yhteyshenkilo' => 'Yhteyshenkilö',
			'yrityksen_nimi' => 'Yrityksen Nimi',
			'y_tunnus' => 'Y-tunnus',
			'osoite' => 'Osoite',
			'kaupunki' => 'Postitoimipaikka',
			'postinumero' => 'Postinumero',
			'puhelin' => 'Puhelin',
			'sahkoposti' => 'Sähköposti',
			'ryhma' => 'Ryhmä',
			'aktiivinen' => 'Aktiivinen',
			'laskutus_kanava' => 'Laskutus kanava',
			'maksuehto' => 'Maksuehto',
			'tyyppi' => 'Asiakastyyppi',
			'ovt_tunnus' => 'Yrityksen OVT-tunnus',
			'valittajan_tunnus' => 'Operaattorin välittäjän tunnus',
			'verkkolaskuosoite' => 'Verkkolaskuosoite',
			'alv'=>'ALV %',
			'hinta_tyyppi'=>'Hinta tyyppi',
			'hinta'=>'Hinta',
			'muistutuslasku_auto'=>'Muistutuslasku automaatiseesti',
			'kirjeenluokka'=>'Kirjeenluokka',
			'myyja'=>'Myyjä',
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
		$criteria->order = "id DESC";

		$criteria->compare('id',$this->id);
		$criteria->compare('asiakasnumero',$this->asiakasnumero);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('yhteyshenkilo',$this->yhteyshenkilo,true);
		$criteria->compare('yrityksen_nimi',$this->yrityksen_nimi,true);
		$criteria->compare('y_tunnus',$this->y_tunnus,true);
		$criteria->compare('osoite',$this->osoite,true);
		$criteria->compare('kaupunki',$this->kaupunki,true);
		$criteria->compare('postinumero',$this->postinumero);
		$criteria->compare('puhelin',$this->puhelin,true);
		$criteria->compare('sahkoposti',$this->sahkoposti,true);
		$criteria->compare('ryhma',$this->ryhma);
		$criteria->compare('aktiivinen',$this->aktiivinen);
		$criteria->compare('laskutus_kanava',$this->laskutus_kanava);
		$criteria->compare('maksuehto',$this->maksuehto);
		$criteria->compare('tyyppi',$this->tyyppi);
		$criteria->compare('ovt_tunnus',$this->ovt_tunnus,true);
		$criteria->compare('valittajan_tunnus',$this->valittajan_tunnus,true);
		$criteria->compare('verkkolaskuosoite',$this->verkkolaskuosoite,true);
		$criteria->compare('alv',$this->alv,true);
		$criteria->compare('hinta_tyyppi',$this->hinta_tyyppi,true);
		$criteria->compare('hinta',$this->hinta,true);
		$criteria->compare('muistutuslasku_auto',$this->muistutuslasku_auto,true);
		$criteria->compare('kirjeenluokka',$this->kirjeenluokka,true);
		$criteria->compare('myyja',$this->myyja,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
