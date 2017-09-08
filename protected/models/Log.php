<?php

/**
 * This is the model class for table "log".
 *
 * The followings are the available columns in table 'log':
 * @property integer $id
 * @property string $time
 * @property string $text
 * @property string $kuka
 * @property integer $log_category
 * @property string $email_to
 * @property string $email_subject
 * @property string $email_message
 * @property string $email_attachment
 */
class Log extends DB2ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		$tb_name = 'log';
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
                     'text' => 'text ',
                     'kuka' => 'varchar(50) ',
                     'log_category' => 'int(3) ',
                     'email_to' => 'varchar(255) ',
                     'email_subject' => 'varchar(255) ',
                     'email_message' => 'text ',
                     'email_attachment' => 'varchar(500) ',
                     'email_attachment_sisalto' => 'text ',
                     'log_nimike' => 'varchar(100) ',




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
			//array('text, kuka, log_category', 'required'),
			array('log_category', 'numerical', 'integerOnly'=>true),
			array('kuka', 'length', 'max'=>50),
			array('log_nimike', 'length', 'max'=>100),
			array('email_to, email_subject', 'length', 'max'=>255),
			array('email_attachment', 'length', 'max'=>500),
			array('text, email_message, email_attachment_sisalto', 'length', 'max'=>60000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, time, text, kuka, log_category, email_to, email_subject, email_message, email_attachment', 'safe', 'on'=>'search'),
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
			'text' => 'Text',
			'kuka' => 'Kuka',
			'log_category' => 'Log Category',
			'email_to' => 'Email To',
			'email_subject' => 'Email Subject',
			'email_message' => 'Email Message',
			'email_attachment' => 'Email Attachment',
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
		$criteria->compare('text',$this->text,true);
		$criteria->compare('kuka',$this->kuka,true);
		$criteria->compare('log_category',$this->log_category);
		$criteria->compare('email_to',$this->email_to,true);
		$criteria->compare('email_subject',$this->email_subject,true);
		$criteria->compare('email_message',$this->email_message,true);
		$criteria->compare('email_attachment',$this->email_attachment,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Log the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
