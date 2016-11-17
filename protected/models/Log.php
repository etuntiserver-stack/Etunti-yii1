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
		return 'log';
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
			array('email_to, email_subject', 'length', 'max'=>255),
			array('email_attachment', 'length', 'max'=>500),
			array('text, email_message', 'length', 'max'=>30000),
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
