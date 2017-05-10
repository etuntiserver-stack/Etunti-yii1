<?php

/**
 * This is the model class for table "digisten_hinnasto".
 *
 * The followings are the available columns in table 'digisten_hinnasto':
 * @property integer $id
 * @property integer $etyo_1000
 * @property integer $etyo_1000_2000
 * @property integer $etyo_2000_3000
 * @property integer $etyo_3000_6000
 * @property integer $etyo_6000_9000
 * @property integer $etyo_9000_plus
 * @property integer $elasku_1000
 * @property integer $elasku_1000_2000
 * @property integer $elasku_2000_3000
 * @property integer $elasku_3000_6000
 * @property integer $elasku_6000_9000
 * @property integer $elasku_9000_plus
 * @property integer $eonline_1000
 * @property integer $eonline_1000_2000
 * @property integer $eonline_2000_3000
 * @property integer $eonline_3000_6000
 * @property integer $eonline_6000_9000
 * @property integer $eonline_9000_plus
 * @property integer $edico_1000
 * @property integer $edico_1000_2000
 * @property integer $edico_2000_3000
 * @property integer $edico_3000_6000
 * @property integer $edico_6000_9000
 * @property integer $edico_9000_plus
 */
class DigistenHinnasto extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'digisten_hinnasto';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('etyo_1000, etyo_1000_2000, etyo_2000_3000, etyo_3000_6000, etyo_6000_9000, etyo_9000_plus, elasku_1000, elasku_1000_2000, elasku_2000_3000, elasku_3000_6000, elasku_6000_9000, elasku_9000_plus, eonline_1000, eonline_1000_2000, eonline_2000_3000, eonline_3000_6000, eonline_6000_9000, eonline_9000_plus, edico_1000, edico_1000_2000, edico_2000_3000, edico_3000_6000, edico_6000_9000, edico_9000_plus, jarjestelmanvalvoja, snapshot_pvm', 'required'),
			array('etyo_1000, etyo_1000_2000, etyo_2000_3000, etyo_3000_6000, etyo_6000_9000, etyo_9000_plus, elasku_1000, elasku_1000_2000, elasku_2000_3000, elasku_3000_6000, elasku_6000_9000, elasku_9000_plus, eonline_1000, eonline_1000_2000, eonline_2000_3000, eonline_3000_6000, eonline_6000_9000, eonline_9000_plus, edico_1000, edico_1000_2000, edico_2000_3000, edico_3000_6000, edico_6000_9000, edico_9000_plus, jarjestelmanvalvoja', 'type', 'type'=>'float'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, etyo_1000, etyo_1000_2000, etyo_2000_3000, etyo_3000_6000, etyo_6000_9000, etyo_9000_plus, elasku_1000, elasku_1000_2000, elasku_2000_3000, elasku_3000_6000, elasku_6000_9000, elasku_9000_plus, eonline_1000, eonline_1000_2000, eonline_2000_3000, eonline_3000_6000, eonline_6000_9000, eonline_9000_plus, edico_1000, edico_1000_2000, edico_2000_3000, edico_3000_6000, edico_6000_9000, edico_9000_plus', 'safe', 'on'=>'search'),
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
			'etyo_1000' => Yii::t('main', 'Etyö 1000'),
			'etyo_1000_2000' => Yii::t('main', 'Etyö 1000-2000'),
			'etyo_2000_3000' => Yii::t('main', 'Etyö 2000-3000'),
			'etyo_3000_6000' => Yii::t('main', 'Etyö 3000-6000'),
			'etyo_6000_9000' => Yii::t('main', 'Etyö 6000-9000'),
			'etyo_9000_plus' => Yii::t('main', 'Etyö 9000 Plus'),
			'elasku_1000' => Yii::t('main', 'Elasku 1000'),
			'elasku_1000_2000' => Yii::t('main', 'Elasku 1000-2000'),
			'elasku_2000_3000' => Yii::t('main', 'Elasku 2000-3000'),
			'elasku_3000_6000' => Yii::t('main', 'Elasku 3000-6000'),
			'elasku_6000_9000' => Yii::t('main', 'Elasku 6000-9000'),
			'elasku_9000_plus' => Yii::t('main', 'Elasku 9000 Plus'),
			'eonline_1000' => Yii::t('main', 'Eonline 1000'),
			'eonline_1000_2000' => Yii::t('main', 'Eonline 1000-2000'),
			'eonline_2000_3000' => Yii::t('main', 'Eonline 2000-3000'),
			'eonline_3000_6000' => Yii::t('main', 'Eonline 3000-6000'),
			'eonline_6000_9000' => Yii::t('main', 'Eonline 6000-9000'),
			'eonline_9000_plus' => Yii::t('main', 'Eonline 9000 Plus'),
			'edico_1000' => Yii::t('main', 'Edico 1000'),
			'edico_1000_2000' => Yii::t('main', 'Edico 1000-2000'),
			'edico_2000_3000' => Yii::t('main', 'Edico 2000-3000'),
			'edico_3000_6000' => Yii::t('main', 'Edico 3000-6000'),
			'edico_6000_9000' => Yii::t('main', 'Edico 6000-9000'),
			'edico_9000_plus' => Yii::t('main', 'Edico 9000 Plus'),
			'jarjestelmanvalvoja' => Yii::t('main', 'Järjestelmävalvoja hinta'),
			'snapshot_pvm' => Yii::t('main', 'Snapshot päivämäärä (Esim. 15)'),
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
		$criteria->compare('etyo_1000',$this->etyo_1000);
		$criteria->compare('etyo_1000_2000',$this->etyo_1000_2000);
		$criteria->compare('etyo_2000_3000',$this->etyo_2000_3000);
		$criteria->compare('etyo_3000_6000',$this->etyo_3000_6000);
		$criteria->compare('etyo_6000_9000',$this->etyo_6000_9000);
		$criteria->compare('etyo_9000_plus',$this->etyo_9000_plus);
		$criteria->compare('elasku_1000',$this->elasku_1000);
		$criteria->compare('elasku_1000_2000',$this->elasku_1000_2000);
		$criteria->compare('elasku_2000_3000',$this->elasku_2000_3000);
		$criteria->compare('elasku_3000_6000',$this->elasku_3000_6000);
		$criteria->compare('elasku_6000_9000',$this->elasku_6000_9000);
		$criteria->compare('elasku_9000_plus',$this->elasku_9000_plus);
		$criteria->compare('eonline_1000',$this->eonline_1000);
		$criteria->compare('eonline_1000_2000',$this->eonline_1000_2000);
		$criteria->compare('eonline_2000_3000',$this->eonline_2000_3000);
		$criteria->compare('eonline_3000_6000',$this->eonline_3000_6000);
		$criteria->compare('eonline_6000_9000',$this->eonline_6000_9000);
		$criteria->compare('eonline_9000_plus',$this->eonline_9000_plus);
		$criteria->compare('edico_1000',$this->edico_1000);
		$criteria->compare('edico_1000_2000',$this->edico_1000_2000);
		$criteria->compare('edico_2000_3000',$this->edico_2000_3000);
		$criteria->compare('edico_3000_6000',$this->edico_3000_6000);
		$criteria->compare('edico_6000_9000',$this->edico_6000_9000);
		$criteria->compare('edico_9000_plus',$this->edico_9000_plus);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DigistenHinnasto the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
