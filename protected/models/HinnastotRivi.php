<?php

/**
 * This is the model class for table "hinnastot_rivi".
 *
 * The followings are the available columns in table 'hinnastot_rivi':
 * @property integer $id
 * @property integer $hinnastot_id
 * @property integer $tuote_palvelu_id
 * @property double $hinnasto_hinta
 * @property integer $hinnasto_alv
 * @property string $hinnasto_yksikko
 */
class HinnastotRivi extends DB2ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		$tb_name = 'hinnastot_rivi';
		$check_this_table = true;

		if($check_this_table)
		{
		$table = Yii::app()->db1->schema->getTable($tb_name);
		if(!isset($table->columns['id'])) {

			Yii::app()->db1->createCommand(" CREATE TABLE IF NOT EXISTS $tb_name 
			(`id` int(11) AUTO_INCREMENT PRIMARY KEY)
			")->execute();
		}

		$table_structure = array(
			'hinnastot_id' => 'int(11) DEFAULT 0',
			'tuote_palvelu_id' => 'int(11) DEFAULT 0',
			'hinnasto_hinta' => 'float DEFAULT 0',
			'hinnasto_alv' => 'int(3) DEFAULT 0',
			'hinnasto_yksikko' => 'varchar(100) DEFAULT NULL',
			'hinta_tuote' => 'float DEFAULT 0',
			'hinta_tuote_sis' => 'float DEFAULT 0',
			'hinnasto_yht' => 'float DEFAULT 0',
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
			array('hinnastot_id, tuote_palvelu_id, hinnasto_hinta, hinnasto_alv', 'required'),
			array('hinnastot_id, tuote_palvelu_id, hinnasto_alv', 'numerical', 'integerOnly'=>true),
			array('hinnasto_hinta, hinta_tuote, hinta_tuote_sis, hinnasto_yht', 'numerical'),
			array('hinnasto_yksikko', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, hinnastot_id, tuote_palvelu_id, hinnasto_hinta, hinnasto_alv, hinnasto_yksikko', 'safe', 'on'=>'search'),
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
			'tuotteet' => array(self::BELONGS_TO, 'TuotteetPalvelut', 'tuote_palvelu_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'hinnastot_id' => 'Hinnastot',
			'tuote_palvelu_id' => 'Tuote Palvelu',
			'hinnasto_hinta' => 'Hinnasto Hinta',
			'hinnasto_alv' => 'Hinnasto Alv',
			'hinnasto_yksikko' => 'Hinnasto Yksikko',
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
		$criteria->compare('hinnastot_id',$this->hinnastot_id);
		$criteria->compare('tuote_palvelu_id',$this->tuote_palvelu_id);
		$criteria->compare('hinnasto_hinta',$this->hinnasto_hinta);
		$criteria->compare('hinnasto_alv',$this->hinnasto_alv);
		$criteria->compare('hinnasto_yksikko',$this->hinnasto_yksikko,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HinnastotRivi the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
