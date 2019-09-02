<?php

/**
 * This is the model class for table "onlinevaraus".
 *
 * The followings are the available columns in table 'onlinevaraus':
 * @property integer $id
 * @property string $time
 */
class Onlinevaraus extends DB2ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Onlinevaraus the static model class
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
		$tb_name = 'onlinevaraus';
		$check_this_table = true;
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

                     'time' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
                     'tv_id' => 'int(11) DEFAULT 0',
                     'maksun_onnistu_koodi' => 'varchar(1000) DEFAULT NULL',
                     'tila' => 'int(1) DEFAULT 0',
                     'asiakas_id' => 'int(11) DEFAULT 0',
                     'kohde_id' => 'int(11) DEFAULT 0',
                     'kesto' => 'varchar(50) DEFAULT NULL',
                     'hinta' => 'varchar(50) DEFAULT NULL',
                     'tilauksen_kuvaus' => 'text DEFAULT NULL',
                     'yhteyshenkilo' => 'varchar(100) DEFAULT NULL',
                     'puhelin' => 'varchar(100) DEFAULT NULL',
                     'osoite' => 'varchar(255) DEFAULT NULL',
                     'postinumero' => 'varchar(10) DEFAULT NULL',
                     'kaupunki' => 'varchar(255) DEFAULT NULL',
                     'lisatietoja' => 'text DEFAULT NULL',
                     'sahkoposti' => 'varchar(255) DEFAULT NULL',
                     'alv' => 'int(3) DEFAULT 0',
                     'veroton_hinta' => 'varchar(10) DEFAULT NULL',
                     'tyyppi' => 'varchar(100) DEFAULT NULL',
                     'yrityksen_nimi' => 'varchar(255) DEFAULT NULL',
                     'y_tunnus' => 'varchar(100) DEFAULT NULL',
                     'valokuvat' => 'text DEFAULT NULL',
                     'laskutettu' => 'int(1) DEFAULT 0',
                     'lasku_id' => 'int(11) DEFAULT 0',
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
			//array('time', 'required'),
			array('tv_id, asiakas_id, kohde_id', 'length', 'max'=>11),
			array('alv, laskutettu, lasku_id', 'numerical', 'integerOnly'=>true),
			array('tila', 'length', 'max'=>1),
			array('yhteyshenkilo, puhelin, tyyppi, y_tunnus', 'length', 'max'=>100),
			array('osoite, postinumero, kaupunki, sahkoposti, yrityksen_nimi', 'length', 'max'=>255),
			array('kesto, hinta, veroton_hinta', 'length', 'max'=>50),
			array('maksun_onnistu_koodi', 'length', 'max'=>1000),
			array('tilauksen_kuvaus, lisatietoja, valokuvat', 'length', 'max'=>20000),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, time', 'safe', 'on'=>'search'),
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
