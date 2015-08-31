<?php

/**
 * This is the model class for table "sivex_viestinta".
 *
 * The followings are the available columns in table 'sivex_viestinta':
 * @property integer $id
 * @property string $time
 * @property string $pvm
 * @property string $tekija
 * @property string $viesti
 * @property string $admin
 */
class Viestinta extends DB2ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Viestinta the static model class
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
		return 'sivex_viestinta';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tekija, viesti', 'required'),
			array('pvm, time', 'length', 'max'=>20),
			array('tekija', 'length', 'max'=>255),
			array('status', 'length', 'max'=>1),
			array('admin', 'length', 'max'=>50),
			array('viesti', 'length', 'max'=>2000),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, time, pvm, tekija, viesti, admin', 'safe', 'on'=>'search'),
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
			'pvm' => 'Pvm',
			'tekija' => 'Työntekijä',
			'viesti' => 'Viesti',
			'admin' => 'Admin',
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
		$criteria->compare('status',$this->status);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('pvm',$this->pvm,true);
		$criteria->compare('tekija',$this->tekija,true);
		$criteria->compare('viesti',$this->viesti,true);
		$criteria->compare('admin',$this->admin,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
