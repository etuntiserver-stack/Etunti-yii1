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
			//array('etunimi, sukunimi, osoite, kaupunki, postinumero, puhelin, sahkoposti, ryhma, aktiivinen', 'required'),
			array('ryhma, aktiivinen', 'numerical', 'integerOnly'=>true),
			array('postinumero, yhteyshenkilo, yrityksen_nimi, y_tunnus, kaupunki, puhelin, sahkoposti', 'length', 'max'=>100),
			array('laskutus_kanava, osoite', 'length', 'max'=>255),
			array('maksuehto, osoite', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, time, etunimi, sukunimi, osoite, kaupunki, postinumero, puhelin, sahkoposti, ryhma, aktiivinen, laskutus_kanava,maksuehto', 'safe', 'on'=>'search'),
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
			'time' => 'Luotu',
			'yhteyshenkilo' => 'Yhteyshenkilö',
			'yrityksen_nimi' => 'Yrityksen Nimi',
			'y_tunnus' => 'Y-Tunnus',
			'osoite' => 'Osoite',
			'kaupunki' => 'Kaupunki',
			'postinumero' => 'Postinumero',
			'puhelin' => 'Puhelin',
			'sahkoposti' => 'Sähköposti',
			'ryhma' => 'Ryhmä',
			'aktiivinen' => 'Aktiivinen',
			'laskutus_kanava' => 'Laskutus kanava',
			'maksuehto' => 'Maksuehto',
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
