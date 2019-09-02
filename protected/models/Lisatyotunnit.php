<?php

/**
 * This is the model class for table "sivex_lisatyot".
 *
 * The followings are the available columns in table 'sivex_lisatyot':
 * @property integer $id
 * @property integer $tid
 * @property string $time
 * @property string $pvm
 * @property string $syy
 * @property string $prosentti
 * @property string $tunnimaara
 */
class Lisatyotunnit extends DB2ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Lisatyotunnit the static model class
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
		$tb_name = 'sivex_lisatyot';
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
                     'tid' => 'int(11) DEFAULT 0',
                     'time' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
                     'pvm' => 'varchar(20) DEFAULT NULL',
                     'syy' => 'varchar(255) DEFAULT NULL',
                     'prosentti' => 'varchar(10) DEFAULT NULL',
                     'tunnimaara' => 'varchar(10) DEFAULT NULL',
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
			array('tid, pvm, syy, prosentti, tunnimaara', 'required'),
			array('tid', 'numerical', 'integerOnly'=>true),
			array('pvm', 'length', 'max'=>20),
			array('syy', 'length', 'max'=>255),
			array('prosentti, tunnimaara', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, tid, time, pvm, syy, prosentti, tunnimaara', 'safe', 'on'=>'search'),
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
			'tid' => Yii::t('main', 'Työntekijä'),
			'time' => Yii::t('main', 'Luotu'),
			'pvm' => Yii::t('main', 'Päivämäärä'),
			'syy' => Yii::t('main', 'Syy'),
			'prosentti' => Yii::t('main', 'Prosentti'),
			'tunnimaara' => Yii::t('main', 'Tuntimäärä'),
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
		$criteria->compare('tid',$this->tid);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('pvm',$this->pvm,true);
		$criteria->compare('syy',$this->syy,true);
		$criteria->compare('prosentti',$this->prosentti,true);
		$criteria->compare('tunnimaara',$this->tunnimaara,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
