<?php

/**
 * This is the model class for table "users_online".
 *
 * The followings are the available columns in table 'users_online':
 * @property integer $id
 * @property string $ip
 * @property string $session
 * @property integer $time
 * @property string $user
 * @property string $url
 */
class UsersOnline extends DB2ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UsersOnline the static model class
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
		$tb_name = 'users_online';
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
                     'ip' => 'varchar(50) DEFAULT NULL',
                     'time' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
                     'session' => 'varchar(150) DEFAULT NULL',
                     'user' => 'varchar(100) DEFAULT NULL',
                     'url' => 'varchar(1000) DEFAULT NULL',
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
			array('ip, session, time, user', 'required'),
			array('time', 'numerical', 'integerOnly'=>true),
			array('ip', 'length', 'max'=>50),
			array('session', 'length', 'max'=>150),
			array('user', 'length', 'max'=>100),
			array('url', 'length', 'max'=>1000),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, ip, session, time, user, url', 'safe', 'on'=>'search'),
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
			'ip' => Yii::t('main', 'Ip'),
			'session' => Yii::t('main', 'Session'),
			'time' => Yii::t('main', 'Time'),
			'user' => Yii::t('main', 'User'),
			'url' => Yii::t('main', 'Url'),
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
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('session',$this->session,true);
		$criteria->compare('time',$this->time);
		$criteria->compare('user',$this->user,true);
		$criteria->compare('url',$this->url,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
