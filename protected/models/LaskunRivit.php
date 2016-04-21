<?php

/**
 * This is the model class for table "laskun_rivit".
 *
 * The followings are the available columns in table 'laskun_rivit':
 * @property integer $id
 * @property string $time
 * @property integer $lid
 * @property integer $rivi
 * @property integer $tkoodi
 * @property string $nimike
 * @property integer $kpl
 * @property string $yksikko
 * @property string $hinta
 * @property string $alv
 * @property string $hinta_alv
 * @property string $ale
 * @property string $veroton
 * @property string $yhteensa_alv
 */
class LaskunRivit extends DB2ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return LaskunRivit the static model class
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
		return 'laskun_rivit';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lid, rivi, tkoodi, kpl, yksikko, hinta, alv, hinta_alv, ale, veroton, yhteensa_alv', 'required'),
			array('lid, rivi', 'numerical', 'integerOnly'=>true),
			array('nimike,kpl', 'length', 'max'=>100),
			array('tkoodi, nimike', 'length', 'max'=>255),
			array('yksikko, hinta, alv, hinta_alv, ale, veroton, yhteensa_alv', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, time, lid, rivi, tkoodi, nimike, kpl, yksikko, hinta, alv, hinta_alv, ale, veroton, yhteensa_alv', 'safe', 'on'=>'search'),
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
			'id' => Yii::t('main', 'ID'),
			'time' => Yii::t('main', 'Time'),
			'lid' => Yii::t('main', 'Lid'),
			'rivi' => Yii::t('main', 'Rivi'),
			'tkoodi' => Yii::t('main', 'Tkoodi'),
			'nimike' => Yii::t('main', 'Nimike'),
			'kpl' => Yii::t('main', 'Kpl'),
			'yksikko' => Yii::t('main', 'Yksikko'),
			'hinta' => Yii::t('main', 'Hinta'),
			'alv' => Yii::t('main', 'Alv'),
			'hinta_alv' => Yii::t('main', 'Hinta Alv'),
			'ale' => Yii::t('main', 'Ale'),
			'veroton' => Yii::t('main', 'Veroton'),
			'yhteensa_alv' => Yii::t('main', 'Yhteensa Alv'),
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
		$criteria->compare('time',$this->time,true);
		$criteria->compare('lid',$this->lid);
		$criteria->compare('rivi',$this->rivi);
		$criteria->compare('tkoodi',$this->tkoodi);
		$criteria->compare('nimike',$this->nimike,true);
		$criteria->compare('kpl',$this->kpl);
		$criteria->compare('yksikko',$this->yksikko,true);
		$criteria->compare('hinta',$this->hinta,true);
		$criteria->compare('alv',$this->alv,true);
		$criteria->compare('hinta_alv',$this->hinta_alv,true);
		$criteria->compare('ale',$this->ale,true);
		$criteria->compare('veroton',$this->veroton,true);
		$criteria->compare('yhteensa_alv',$this->yhteensa_alv,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
