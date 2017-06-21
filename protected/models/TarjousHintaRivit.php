<?php

/**
 * This is the model class for table "tarjous_hinta_rivit".
 *
 * The followings are the available columns in table 'tarjous_hinta_rivit':
 * @property integer $id
 * @property string $time
 * @property integer $tarjous_id
 * @property integer $rivi
 * @property string $tkoodi
 * @property string $nimike
 * @property string $kpl
 * @property string $yksikko
 * @property string $hinta
 * @property string $alv
 * @property string $hinta_alv
 * @property string $ale
 * @property string $veroton
 * @property string $yhteensa_alv
 * @property integer $tuoteID
 * @property string $free_text
 */
class TarjousHintaRivit extends DB2ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tarjous_hinta_rivit';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('id, time, tarjous_id, rivi, tkoodi, nimike, kpl, yksikko, hinta, alv, hinta_alv, ale, veroton, yhteensa_alv, tuoteID', 'required'),
			array('tarjous_id, rivi, tuoteID', 'numerical', 'integerOnly'=>true),
			array('tkoodi', 'length', 'max'=>255),
			array('nimike', 'length', 'max'=>100),
			array('kpl, yksikko, hinta, alv, hinta_alv, ale, veroton, yhteensa_alv', 'length', 'max'=>20),
			array('free_text', 'length', 'max'=>250),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, time, tarjous_id, rivi, tkoodi, nimike, kpl, yksikko, hinta, alv, hinta_alv, ale, veroton, yhteensa_alv, tuoteID, free_text', 'safe', 'on'=>'search'),
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
			'tarjous_id' => 'Tarjous',
			'rivi' => 'Rivi',
			'tkoodi' => 'Tkoodi',
			'nimike' => 'Nimike',
			'kpl' => 'Kpl',
			'yksikko' => 'Yksikko',
			'hinta' => 'Hinta',
			'alv' => 'Alv',
			'hinta_alv' => 'Hinta Alv',
			'ale' => 'Ale',
			'veroton' => 'Veroton',
			'yhteensa_alv' => 'Yhteensa Alv',
			'tuoteID' => 'Tuote',
			'free_text' => 'Free Text',
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
		$criteria->compare('tarjous_id',$this->tarjous_id);
		$criteria->compare('rivi',$this->rivi);
		$criteria->compare('tkoodi',$this->tkoodi,true);
		$criteria->compare('nimike',$this->nimike,true);
		$criteria->compare('kpl',$this->kpl,true);
		$criteria->compare('yksikko',$this->yksikko,true);
		$criteria->compare('hinta',$this->hinta,true);
		$criteria->compare('alv',$this->alv,true);
		$criteria->compare('hinta_alv',$this->hinta_alv,true);
		$criteria->compare('ale',$this->ale,true);
		$criteria->compare('veroton',$this->veroton,true);
		$criteria->compare('yhteensa_alv',$this->yhteensa_alv,true);
		$criteria->compare('tuoteID',$this->tuoteID);
		$criteria->compare('free_text',$this->free_text,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TarjousHintaRivit the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
