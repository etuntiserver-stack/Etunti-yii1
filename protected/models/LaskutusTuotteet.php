<?php

/**
 * This is the model class for table "laskutus_tuotteet".
 *
 * The followings are the available columns in table 'laskutus_tuotteet':
 * @property integer $id
 * @property string $time
 * @property string $tuotenimi
 * @property string $hinta_alv_0
 * @property string $hinta_alv_sis
 * @property string $alv
 * @property string $yksikko
 */
class LaskutusTuotteet extends DB2ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return LaskutusTuotteet the static model class
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
		$tb_name = 'laskutus_tuotteet';
		$check_this_table = false;
		//unset(Yii::app()->session[$tb_name]); // this use if want many times play
		if(!isset(Yii::app()->session[$tb_name]))
		{
			Yii::app()->session[$tb_name] = true;
			$check_this_table = true;
		}


		if($check_this_table)
		{
		$table = Yii::app()->db1->schema->getTable($tb_name);
		if(!isset($table->columns['id'])) {

			Yii::app()->db1->createCommand(" CREATE TABLE IF NOT EXISTS $tb_name 
			(`id` int(11) AUTO_INCREMENT PRIMARY KEY)
			")->execute();
		}

		$table_structure = array(

                     'time' => 'timestamp DEFAULT CURRENT_TIMESTAMP ',
                     'tuotenimi' => 'varchar(100) ',
                     'hinta_alv_0' => 'varchar(20) ',
                     'hinta_alv_sis' => 'varchar(20) ',
                     'alv' => 'varchar(10) ',
                     'yksikko' => 'varchar(20) ',
                     'netvisorkey' => 'int(11) ',
                     'ryhma' => 'int(3) ',
                     'is_active' => 'int(1) ',
                     'varastoitava' => 'int(1) ',
                     'myyntituote' => 'int(1) DEFAULT 1 ',




		);

		foreach($table_structure as $key=>$value)
		{
			if (!isset($table->columns[$key])) {
				Yii::app()->db1->createCommand()->addColumn($tb_name, $key, $value);
			}
		}	
		} // if($check_this_table)

		return $tb_name;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tuotenimi, hinta_alv_0, alv, yksikko', 'required'),
			array('netvisorkey, ryhma, is_active, varastoitava, myyntituote', 'numerical', 'integerOnly'=>true),
			array('tuotenimi', 'length', 'max'=>100),
			array('hinta_alv_0, hinta_alv_sis, yksikko', 'length', 'max'=>20),
			array('alv', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, time, tuotenimi, hinta_alv_0, hinta_alv_sis, alv, yksikko', 'safe', 'on'=>'search'),
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
			'tuotenimi' => Yii::t('main', 'Tuotenimi'),
			'hinta_alv_0' => Yii::t('main', 'Hinta Alv 0'),
			'hinta_alv_sis' => Yii::t('main', 'Hinta Alv Sis'),
			'alv' => Yii::t('main', 'Alv'),
			'yksikko' => Yii::t('main', 'Yksikko'),
			'ryhma' => Yii::t('main', 'Laskutuksen tuoteryhmä'),
			'is_active'=>Yii::t('main', 'Aktiivinen'),
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
		$criteria->compare('tuotenimi',$this->tuotenimi,true);
		$criteria->compare('hinta_alv_0',$this->hinta_alv_0,true);
		$criteria->compare('hinta_alv_sis',$this->hinta_alv_sis,true);
		$criteria->compare('alv',$this->alv,true);
		$criteria->compare('yksikko',$this->yksikko,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
