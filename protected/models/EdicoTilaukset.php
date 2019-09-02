<?php

/**
 * This is the model class for table "edico_tulaukset".
 *
 * The followings are the available columns in table 'edico_tulaukset':
 * @property integer $id
 * @property string $time
 * @property integer $asiakas_id
 * @property integer $kohde_id
 * @property string $osoite
 * @property integer $postinumero
 * @property string $postitoimipaikka
 * @property string $asiakas_puhelinnumero
 * @property string $toivottu_pvm
 * @property string $toivottu_aloitus
 * @property string $toivottu_lopetus
 * @property string $viesti
 * @property string $tuotteet
 */
class EdicoTilaukset extends DB2ActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		$tb_name = 'edico_tilaukset';
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
                     'time' => 'timestamp DEFAULT CURRENT_TIMESTAMP ',
                     'asiakas_id' => 'int(11) DEFAULT 0',
                     'kohde_id' => 'int(11) DEFAULT 0',
                     'osoite' => 'varchar(255) DEFAULT NULL',
                     'postinumero' => 'varchar(10) DEFAULT NULL',
                     'postitoimipaikka' => 'varchar(255) DEFAULT NULL',
                     'asiakas_puhelinnumero' => 'varchar(255) DEFAULT NULL',
                     'toivottu_pvm' => 'varchar(50) DEFAULT NULL',
                     'toivottu_aloitus' => 'varchar(50) DEFAULT NULL',
                     'toivottu_lopetus' => 'varchar(50) DEFAULT NULL',
                     'viesti' => 'text DEFAULT NULL',
                     'tuotteet' => 'text DEFAULT NULL',
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
			array('kohde_id, osoite, postinumero, postitoimipaikka, asiakas_puhelinnumero, toivottu_pvm, toivottu_aloitus, toivottu_lopetus, tuotteet', 'required'),
			array('asiakas_id, kohde_id', 'numerical', 'integerOnly'=>true),
			array('osoite, postitoimipaikka, asiakas_puhelinnumero', 'length', 'max'=>255),
			array('toivottu_pvm, toivottu_aloitus, toivottu_lopetus, postinumero', 'length', 'max'=>50),
			array('viesti', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, time, asiakas_id, kohde_id, osoite, postinumero, postitoimipaikka, asiakas_puhelinnumero, toivottu_pvm, toivottu_aloitus, toivottu_lopetus, viesti, tuotteet', 'safe', 'on'=>'search'),
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
		        'asiakkaat' => array(self::BELONGS_TO, 'Asiakkaat', 'asiakas_id'),
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
			'asiakas_id' => 'Asiakas',
			'kohde_id' => 'Kohde',
			'osoite' => 'Osoite',
			'postinumero' => 'Postinumero',
			'postitoimipaikka' => 'Postitoimipaikka',
			'asiakas_puhelinnumero' => 'Asiakas Puhelinnumero',
			'toivottu_pvm' => 'Toivottu Pvm',
			'toivottu_aloitus' => 'Toivottu Aloitus',
			'toivottu_lopetus' => 'Toivottu Lopetus',
			'viesti' => 'Viesti',
			'tuotteet' => 'Tuotteet',
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
		$criteria->compare('asiakas_id',$this->asiakas_id);
		$criteria->compare('kohde_id',$this->kohde_id);
		$criteria->compare('osoite',$this->osoite,true);
		$criteria->compare('postinumero',$this->postinumero);
		$criteria->compare('postitoimipaikka',$this->postitoimipaikka,true);
		$criteria->compare('asiakas_puhelinnumero',$this->asiakas_puhelinnumero,true);
		$criteria->compare('toivottu_pvm',$this->toivottu_pvm,true);
		$criteria->compare('toivottu_aloitus',$this->toivottu_aloitus,true);
		$criteria->compare('toivottu_lopetus',$this->toivottu_lopetus,true);
		$criteria->compare('viesti',$this->viesti,true);
		$criteria->compare('tuotteet',$this->tuotteet,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EdicoTilaukset the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
