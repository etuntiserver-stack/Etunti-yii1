<?php

/**
 * This is the model class for table "asiakas_hyvaksynta".
 *
 * The followings are the available columns in table 'asiakas_hyvaksynta':
 * @property integer $id
 * @property integer $asiakas_id
 * @property string $time
 * @property string $ids
 * @property string $sahkoposti
 * @property string $code
 * @property integer $status
 * @property string $selitys
 * @property string $kirjen_body
 */
class AsiakasHyvaksynta extends DB2ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AsiakasHyvaksynta the static model class
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
		return 'asiakas_hyvaksynta';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('asiakas_id, ids, sahkoposti, code, status', 'required'),
			array('id, asiakas_id, status', 'numerical', 'integerOnly'=>true),
			array('sahkoposti', 'length', 'max'=>255),
			array('code', 'length', 'max'=>500),
			array('selitys', 'length', 'max'=>1000),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, asiakas_id, time, ids, sahkoposti, code, status, selitys, kirjen_body', 'safe', 'on'=>'search'),
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
			'ids' => 'Ids',
			'sahkoposti' => 'Sähköposti',
			'code' => 'Code',
			'status' => 'Status',
			'selitys' => 'Selitys',
			'kirjen_body' => 'Kirje',
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
		$criteria->order = 'id DESC';

		$criteria->compare('id',$this->id);
		$criteria->compare('asiakas_id',$this->asiakas_id);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('ids',$this->ids,true);
		$criteria->compare('sahkoposti',$this->sahkoposti,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('selitys',$this->selitys,true);
		$criteria->compare('kirjen_body',$this->kirjen_body,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
