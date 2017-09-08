<?php

/**
 * This is the model class for table "sivex_administrators".
 *
 * The followings are the available columns in table 'sivex_administrators':
 * @property integer $id
 * @property string $adm_login
 * @property string $adm_salasana
 * @property string $adm_email
 * @property string $adm_nimi
 * @property integer $status
 */
class Administrators extends DB2ActiveRecord
{

public $adm_salasana_repeat;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Administrators the static model class
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

		$tb_name = 'sivex_administrators';

		$check_this_table = false;
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

                     'adm_login' => 'varchar(100) ',
                     'adm_salasana' => 'varchar(100) ',
                     'adm_email' => 'varchar(100) ',
                     'adm_nimi' => 'varchar(100) ',
                     'status' => 'int(1) ',
                     'ulkonaky' => 'text ',
                     'token' => 'varchar(255) ',



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
			array('adm_login, adm_email, adm_nimi', 'required'),
			//array('adm_salasana', 'required', 'on'=>'create'),
			//array('adm_salasana', 'compare', 'compareAttribute'=>'adm_salasana_repeat' , 'on'=>'update'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('adm_login, adm_salasana, adm_email, adm_nimi', 'length', 'max'=>100),
			array('ulkonaky, token', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, adm_login, adm_salasana, adm_email, adm_nimi, status', 'safe', 'on'=>'search'),
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
			'adm_login' => Yii::t('main', 'Käyttäjätunnus'),
			'adm_salasana' => Yii::t('main', 'Uusi salasana'),
			'adm_salasana2' => Yii::t('main', 'Toista uusi salasana'),
			'adm_email' => Yii::t('main', 'Sähköposti'),
			'adm_nimi' => Yii::t('main', 'Nimi'),
			'status' => Yii::t('main', 'Käyttöoikeudet'),
			'adm_salasana_repeat' => Yii::t('main', 'Vahvista uusi salasana'),
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
		$criteria->compare('adm_login',$this->adm_login,true);
		$criteria->compare('adm_salasana',$this->adm_salasana,true);
		$criteria->compare('adm_email',$this->adm_email,true);
		$criteria->compare('adm_nimi',$this->adm_nimi,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
