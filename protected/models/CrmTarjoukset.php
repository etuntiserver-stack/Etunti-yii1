<?php

/**
 * This is the model class for table "sivex_tarjoukset".
 *
 * The followings are the available columns in table 'sivex_tarjoukset':
 * @property integer $id
 * @property string $time
 * @property integer $asiakas_id
 * @property string $tarjous
 * @property string $hyvaksyn_koodi
 * @property string $asiakkaan_sahkoposti
 * @property integer $status
 */
class CrmTarjoukset extends DB2ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sivex_tarjoukset';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('asiakas_id', 'required'),
			array('asiakas_id, status', 'numerical', 'integerOnly'=>true),
			array('hyvaksyn_koodi, liite', 'length', 'max'=>255),
			array('asiakkaan_sahkoposti', 'length', 'max'=>100),
			array('tarjous', 'length', 'max'=>3000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, time, asiakas_id, tarjous, hyvaksyn_koodi, asiakkaan_sahkoposti, status', 'safe', 'on'=>'search'),
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
			'asiakas_id' => 'Asiakas',
			'tarjous' => 'Tarjous',
			'hyvaksyn_koodi' => 'Hyvaksyn Koodi',
			'asiakkaan_sahkoposti' => 'Asiakkaan Sahkoposti',
			'status' => 'Status',
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
		$criteria->compare('asiakas_id',$this->asiakas_id);
		$criteria->compare('tarjous',$this->tarjous,true);
		$criteria->compare('hyvaksyn_koodi',$this->hyvaksyn_koodi,true);
		$criteria->compare('asiakkaan_sahkoposti',$this->asiakkaan_sahkoposti,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CrmTarjoukset the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
