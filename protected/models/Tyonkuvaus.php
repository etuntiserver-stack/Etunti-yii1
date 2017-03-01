<?php

/**
 * This is the model class for table "tyonkuvaus".
 *
 * The followings are the available columns in table 'tyonkuvaus':
 * @property integer $id
 * @property string $time
 * @property integer $yhteystiedot_id
 * @property integer $asiakas_id
 * @property string $otsikko
 */
class Tyonkuvaus extends DB2ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tyonkuvaus';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('otsikko', 'required'),
			array('yhteystiedot_id, asiakas_id', 'numerical', 'integerOnly'=>true),
			array('otsikko', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, time, yhteystiedot_id, asiakas_id, otsikko', 'safe', 'on'=>'search'),
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
			'yhteystiedot_id' => 'Yhteystiedot',
			'asiakas_id' => 'Asiakas',
			'otsikko' => 'Otsikko',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('yhteystiedot_id',$this->yhteystiedot_id);
		$criteria->compare('asiakas_id',$this->asiakas_id);
		$criteria->compare('otsikko',$this->otsikko,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Tyonkuvaus the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
