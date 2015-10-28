<?php

/**
 * This is the model class for table "asetukset".
 *
 * The followings are the available columns in table 'asetukset':
 * @property integer $id
 * @property string $asetus
 * @property string $api_access_key
 * @property string $muut
 */
class AsetuksetForAll extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AsetuksetForAll the static model class
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
			array('asetus, api_access_key', 'required'),
			array('asetus', 'length', 'max'=>255),
			array('api_access_key', 'length', 'max'=>500),
			array('ohjesivu', 'length', 'max'=>50000),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, asetus, api_access_key, ohjesivu', 'safe', 'on'=>'search'),
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
			'asetus' => 'Asetus',
			'api_access_key' => 'Api Access Key',
			'ohjesivu' => 'Ohjesivu',
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
		$criteria->compare('asetus',$this->asetus,true);
		$criteria->compare('api_access_key',$this->api_access_key,true);
		$criteria->compare('ohjesivu',$this->ohjesivu,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
