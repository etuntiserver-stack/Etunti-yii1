<?php

/**
 * This is the model class for table "lasku_historia".
 *
 * The followings are the available columns in table 'lasku_historia':
 * @property integer $id
 * @property integer $lid
 * @property string $time
 * @property string $status
 * @property string $yht_euro
 */
class LaskuHistoria extends DB2ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return LaskuHistoria the static model class
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
		return 'lasku_historia';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('lid, time, status, yht_euro', 'required'),
			array('lid', 'numerical', 'integerOnly'=>true),
			array('status', 'length', 'max'=>2000),
			array('paydate, amount, yht_euro, palvelu', 'length', 'max'=>50),
			array('trust_statuscode', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, lid, time, status, yht_euro, trust_statuscode, paydate, amount', 'safe', 'on'=>'search'),
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
			'lid' => 'Lasku id',
			'time' => 'Tapahtuma pvm',
			'status' => 'Response',
			'yht_euro' => 'Yht Euro',
			'palvelu' => 'Palvelu',
			'trust_statuscode'=>'Trust statuscode',
			'paydate' => 'Paydate',
			'amount' => 'Amount',
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
		$criteria->order = " id DESC ";

		$criteria->compare('id',$this->id);
		$criteria->compare('lid',$this->lid);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('yht_euro',$this->yht_euro,true);
		$criteria->compare('palvelu',$this->palvelu,true);
		$criteria->compare('trust_statuscode',$this->trust_statuscode);
		$criteria->compare('paydate',$this->paydate,true);
		$criteria->compare('amount',$this->amount,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}



}
