<?php

/**
 * This is the model class for table "vinkki_extranet".
 *
 * The followings are the available columns in table 'vinkki_extranet':
 * @property integer $id
 * @property string $time
 * @property integer $asiakas_id
 * @property string $nimi
 * @property string $puhelin
 * @property string $sahkoposti
 * @property string $teksti
 */
class VinkkiExtranet extends DB2ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vinkki_extranet';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('asiakas_id, nimi, puhelin, sahkoposti', 'required'),
			array('tila, asiakas_id, vinkkaja_asiakas_id', 'numerical', 'integerOnly'=>true),
			array('time, nimi, puhelin', 'length', 'max'=>100),
			array('muutos_pvm', 'length', 'max'=>50),
			array('sahkoposti, token', 'length', 'max'=>255),
			array('teksti', 'length', 'max'=>3000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, time, asiakas_id, nimi, puhelin, sahkoposti, teksti', 'safe', 'on'=>'search'),
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
			'time' => 'Luotu',
			'asiakas_id' => 'Asiakas',
			'nimi' => 'Nimi',
			'puhelin' => 'Puhelin',
			'sahkoposti' => 'Sahkoposti',
			'teksti' => 'Teksti',
			'muutos_pvm' => Yii::t('main', 'Muutokset päivämäärä'),
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
		$criteria->compare('nimi',$this->nimi,true);
		$criteria->compare('puhelin',$this->puhelin,true);
		$criteria->compare('sahkoposti',$this->sahkoposti,true);
		$criteria->compare('teksti',$this->teksti,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VinkkiExtranet the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
