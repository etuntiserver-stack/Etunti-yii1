<?php

/**
 * This is the model class for table "palautteet".
 *
 * The followings are the available columns in table 'palautteet':
 * @property integer $id
 * @property integer $keskustelu_id
 * @property string $time
 * @property integer $asiakas_id
 * @property string $teksti
 * @property string $otsikko
 * @property integer $status
 */
class Palautteet extends DB2ActiveRecord
{

	public $sisainen;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		$tb_name = 'palautteet';
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
                     'keskustelu_id' => 'int(11) DEFAULT 0',
                     'time' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
                     'asiakas_id' => 'int(11) DEFAULT 0',
                     'teksti' => 'text DEFAULT NULL',
                     'status' => 'int(1) DEFAULT 0',
                     'emoji_tila' => 'int(1) DEFAULT 0',
                     'asiakas_luettu' => 'int(1) DEFAULT 0',
                     'lahettaja' => 'varchar(20) DEFAULT NULL',
                     'viimeinen_tyo' => 'varchar(255) DEFAULT NULL',
                     'kategoria' => 'int(11) DEFAULT 0',
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
			array('asiakas_id, teksti', 'required'),
			array('keskustelu_id, asiakas_id, status, emoji_tila, asiakas_luettu, kategoria', 'numerical', 'integerOnly'=>true),
			array('viimeinen_tyo', 'length', 'max'=>255),
			array('lahettaja', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, keskustelu_id, time, asiakas_id, teksti, otsikko, status', 'safe', 'on'=>'search'),
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
			'keskustelu_id' => 'Keskustelu',
			'time' => 'Time',
			'asiakas_id' => 'Asiakas',
			'teksti' => 'Teksti',
			'otsikko' => 'Otsikko',
			'status' => 'Status',
			'viimeinen_tyo' => Yii::t('main', 'Viimeiset työt')
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
		$criteria->compare('keskustelu_id',$this->keskustelu_id);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('asiakas_id',$this->asiakas_id);
		$criteria->compare('teksti',$this->teksti,true);
		$criteria->compare('otsikko',$this->otsikko,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Palautteet the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
