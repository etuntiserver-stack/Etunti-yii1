<?php

/**
 * This is the model class for table "blog".
 *
 * The followings are the available columns in table 'blog':
 * @property integer $id
 * @property string $time
 * @property string $luoja
 * @property string $otsikko
 * @property string $teksti
 */
class Autolahetteet extends DB2ActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		$tb_name = 'autolahetteet';
		$table = Yii::app()->db1->schema->getTable($tb_name);
		if(!isset($table->columns['id'])) {
			Yii::app()->db1->createCommand(" CREATE TABLE IF NOT EXISTS $tb_name 
			(`id` int(11) AUTO_INCREMENT PRIMARY KEY)
			")->execute();
		}
		$table_structure = array(
                     'time' => 'timestamp DEFAULT CURRENT_TIMESTAMP AFTER id',
                     'from_date' => 'varchar(50) DEFAULT NULL',
                     'to_date' => 'varchar(50) DEFAULT NULL',
                     'asiakas_id' => 'int(11) DEFAULT NULL',
                     'adm_id' => 'int(11) DEFAULT NULL',
		     'tab_array' => 'text DEFAULT NULL'
		);
		foreach($table_structure as $key=>$value)
		{
			if (!isset($table->columns[$key])) {
				Yii::app()->db1->createCommand()->addColumn($tb_name, $key, $value);
			}
		}	
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
			array('from_date, to_date', 'length', 'max'=>50),
			array('asiakas_id, adm_id', 'numerical', 'integerOnly'=>true),
			array('tab_array', 'safe'),
		);
	}

}
