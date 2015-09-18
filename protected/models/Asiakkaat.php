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
			array('etunimi, sukunimi, osoite, kaupunki, postinumero, puhelin, sahkoposti, ryhma, aktiivinen', 'required'),
			array('ryhma, aktiivinen', 'numerical', 'integerOnly'=>true),
			array('postinumero,etunimi, sukunimi, kaupunki, puhelin, sahkoposti', 'length', 'max'=>100),
			array('osoite', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, time, etunimi, sukunimi, osoite, kaupunki, postinumero, puhelin, sahkoposti, ryhma, aktiivinen', 'safe', 'on'=>'search'),
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
			'time' => 'Time',
			'etunimi' => 'Etunimi',
			'sukunimi' => 'Sukunimi',
			'osoite' => 'Osoite',
			'kaupunki' => 'Kaupunki',
			'postinumero' => 'Postinumero',
			'puhelin' => 'Puhelin',
			'sahkoposti' => 'Sahkoposti',
			'ryhma' => 'Ryhma',
			'aktiivinen' => 'Aktiivinen',
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
		$criteria->compare('etunimi',$this->etunimi,true);
		$criteria->compare('sukunimi',$this->sukunimi,true);
		$criteria->compare('osoite',$this->osoite,true);
		$criteria->compare('kaupunki',$this->kaupunki,true);
		$criteria->compare('postinumero',$this->postinumero);
		$criteria->compare('puhelin',$this->puhelin,true);
		$criteria->compare('sahkoposti',$this->sahkoposti,true);
		$criteria->compare('ryhma',$this->ryhma);
		$criteria->compare('aktiivinen',$this->aktiivinen);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
