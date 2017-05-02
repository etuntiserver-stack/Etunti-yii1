<?php

/**
 * This is the model class for table "digisten_tunnit_kk".
 *
 * The followings are the available columns in table 'digisten_tunnit_kk':
 * @property integer $id
 * @property string $time
 * @property string $domain
 * @property integer $year
 * @property integer $month
 * @property integer $tunnit
 * @property string $tasot
 * @property integer $maksettu
 */
class DigistenTunnitKk extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'digisten_tunnit_kk';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('time, year, month, tunnit, tasot', 'required'),
			array('year, month, tunnit, maksettu', 'numerical', 'integerOnly'=>true),
			array('domain', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, time, domain, year, month, tunnit, tasot, maksettu', 'safe', 'on'=>'search'),
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
			'domain' => 'Domain',
			'year' => 'Year',
			'month' => 'Month',
			'tunnit' => 'Tunnit',
			'tasot' => 'Tasot',
			'maksettu' => 'Maksettu',
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
		$criteria->compare('domain',$this->domain,true);
		$criteria->compare('year',$this->year);
		$criteria->compare('month',$this->month);
		$criteria->compare('tunnit',$this->tunnit);
		$criteria->compare('tasot',$this->tasot,true);
		$criteria->compare('maksettu',$this->maksettu);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DigistenTunnitKk the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
