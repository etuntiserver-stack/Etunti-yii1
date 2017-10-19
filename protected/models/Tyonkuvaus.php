<?php

/**
 * This is the model class for table "tyonkuvaus".
 *
 * The followings are the available columns in table 'tyonkuvaus':
 * @property integer $id
 * @property string $time
 * @property integer $yhteystiedot_id
 * @property integer $asiakas_id
 * @property string $otsikko
 */
class Tyonkuvaus extends DB2ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		$tb_name = 'tyonkuvaus';
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

                     'time' => 'timestamp DEFAULT CURRENT_TIMESTAMP ',
                     'yhteystiedot_id' => 'int(11) ',
                     'asiakas_id' => 'int(11) ',
                     'otsikko' => 'varchar(255) ',
                     'aktiivinen' => 'int(1) DEFAULT 1 ',
                     'kohde_id' => 'int(11) ',

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
			array('asiakas_id, kohde_id', 'required'),
			array('yhteystiedot_id, asiakas_id, kohde_id, aktiivinen', 'numerical', 'integerOnly'=>true),
			array('otsikko', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, time, yhteystiedot_id, asiakas_id, otsikko', 'safe', 'on'=>'search'),
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
		        'kohteet' => array(self::BELONGS_TO, 'Kohteet', 'kohde_id'),
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
			'yhteystiedot_id' => 'Yhteystiedot',
			'asiakas_id' => 'Asiakas',
			'otsikko' => 'Otsikko',
			'kohde_id' => 'Kohde',
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
		$criteria->compare('yhteystiedot_id',$this->yhteystiedot_id);
		$criteria->compare('asiakas_id',$this->asiakas_id);
		$criteria->compare('otsikko',$this->otsikko,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Tyonkuvaus the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
