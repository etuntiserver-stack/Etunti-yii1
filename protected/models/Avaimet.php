<?php

/**
 * This is the model class for table "avaimet".
 *
 * The followings are the available columns in table 'avaimet':
 * @property integer $id
 * @property string $time
 * @property string $avainnumero
 * @property integer $kohde_id
 * @property integer $tid
 * @property string $sijainti
 * @property string $lisatiedot
 * @property integer $status
 */
class Avaimet extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		$tb_name = 'avaimet';
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
		  'time' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		  'avainnumero' => 'varchar(255) DEFAULT NULL',
		  'asiakas_id' => 'int(11) DEFAULT 0',
		  'kohde' => 'int(11) DEFAULT 0',
		  'tid' => 'int(11) DEFAULT 0',
		  'sijainti' => 'varchar(255) DEFAULT NULL',
		  'sijainti_omatekstti' => 'int(11) DEFAULT 1',
		  'lisatiedot' => 'text DEFAULT NULL',
		  'status' => 'int(11) DEFAULT NULL',
		  'palautetu_asiakkaalle_pvm' => 'varchar(50) DEFAULT NULL',
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
			array('avainnumero, kohde, sijainti', 'required'),
                        array('avainnumero','unique', 'message'=>'Tämä avainnumero on jo olemassa!'),
			array('kohde, tid, status, asiakas_id, sijainti_omatekstti', 'numerical', 'integerOnly'=>true),
			array('avainnumero, sijainti, palautetu_asiakkaalle_pvm', 'length', 'max'=>255),
			array('lisatiedot', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, time, avainnumero, kohde_id, tid, sijainti, lisatiedot, status', 'safe', 'on'=>'search'),
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
		        'kohteet' => array(self::BELONGS_TO, 'Kohteet', 'kohde'),
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
			'avainnumero' => 'Avainnumero',
			'asiakas_id' => Yii::t('main', 'Asiakas'),
			'kohde_id' => Yii::t('main', 'Osoite'),
			'tid' => Yii::t('main', 'Työntekijä'),
			'sijainti' => 'Sijainti',
			'lisatiedot' => Yii::t('main', 'Lisätiedot'),
			'sijainti_omatekstti' => Yii::t('main', 'Sijannin vaihtoehto'),
			'status' => 'Status',
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
		$criteria->compare('avainnumero',$this->avainnumero,true);
		$criteria->compare('kohde_id',$this->kohde_id);
		$criteria->compare('tid',$this->tid);
		$criteria->compare('sijainti',$this->sijainti,true);
		$criteria->compare('lisatiedot',$this->lisatiedot,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db1;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Avaimet the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
