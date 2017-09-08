<?php

/**
 * This is the model class for table "kirjeiden_hallinta".
 *
 * The followings are the available columns in table 'kirjeiden_hallinta':
 * @property integer $id
 * @property string $time
 * @property string $ryhma
 * @property string $teksti
 * @property string $hyvaksyn_koodi
 * @property integer $status
 * @property string $liite
 */
class KirjeidenHallinta extends DB2ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		$tb_name = 'kirjeiden_hallinta';
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
                     'ryhma' => 'varchar(255) ',
                     'teksti' => 'text ',
                     'hyvaksyn_koodi' => 'varchar(255) ',
                     'status' => 'int(1) ',
                     'liite' => 'varchar(255) ',



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
			array('ryhma, teksti', 'required'),
			array('id, status', 'numerical', 'integerOnly'=>true),
			array('ryhma, hyvaksyn_koodi, liite', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, time, ryhma, teksti, hyvaksyn_koodi, status, liite', 'safe', 'on'=>'search'),
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
			'ryhma' => 'Ryhma',
			'teksti' => 'Teksti',
			'hyvaksyn_koodi' => 'Hyvaksyn Koodi',
			'status' => 'Status',
			'liite' => 'Liite',
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
		$criteria->compare('ryhma',$this->ryhma,true);
		$criteria->compare('teksti',$this->teksti,true);
		$criteria->compare('hyvaksyn_koodi',$this->hyvaksyn_koodi,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('liite',$this->liite,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return KirjeidenHallinta the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
