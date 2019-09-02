<?php

/**
 * This is the model class for table "tarjous_hinta_rivit".
 *
 * The followings are the available columns in table 'tarjous_hinta_rivit':
 * @property integer $id
 * @property string $time
 * @property integer $sopimus_id
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
class SopimusHintaRivit extends DB2ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		$tb_name = 'sopimus_hinta_rivit';
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
                     'sopimus_id' => 'int(11) DEFAULT 0',
                     'rivi' => 'int(11) DEFAULT 0',
                     'tkoodi' => 'varchar(255) DEFAULT NULL',
                     'nimike' => 'varchar(100) DEFAULT NULL',
                     'kpl' => 'varchar(20) DEFAULT NULL',
                     'yksikko' => 'varchar(20) DEFAULT NULL',
                     'hinta' => 'varchar(20) DEFAULT NULL',
                     'alv' => 'varchar(20) DEFAULT NULL',
                     'hinta_alv' => 'varchar(20) DEFAULT NULL',
                     'ale' => 'varchar(20) DEFAULT NULL',
                     'veroton' => 'varchar(20) DEFAULT NULL',
                     'yhteensa_alv' => 'varchar(20) DEFAULT NULL',
                     'tuoteID' => 'int(11) DEFAULT NULL',
                     'free_text' => 'varchar(250) DEFAULT NULL',
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
			//array('id, time, sopimus_id, rivi, tkoodi, nimike, kpl, yksikko, hinta, alv, hinta_alv, ale, veroton, yhteensa_alv, tuoteID', 'required'),
			array('sopimus_id, rivi, tuoteID', 'numerical', 'integerOnly'=>true),
			array('tkoodi', 'length', 'max'=>255),
			array('nimike', 'length', 'max'=>100),
			array('kpl, yksikko, hinta, alv, hinta_alv, ale, veroton, yhteensa_alv', 'length', 'max'=>20),
			array('free_text', 'length', 'max'=>250),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, time, sopimus_id, rivi, tkoodi, nimike, kpl, yksikko, hinta, alv, hinta_alv, ale, veroton, yhteensa_alv, tuoteID, free_text', 'safe', 'on'=>'search'),
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
			'sopimus_id' => 'Sopimus',
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
		$criteria->compare('sopimus_id',$this->sopimus_id);
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
	 * @return SopimusHintaRivit the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
