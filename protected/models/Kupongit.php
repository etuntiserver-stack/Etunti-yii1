<?php

/**
 * This is the model class for table "kupongit".
 *
 * The followings are the available columns in table 'kupongit':
 * @property integer $id
 * @property string $time
 * @property integer $kupongin_id
 * @property string $voimassa
 * @property double $euro_maara
 * @property integer $prosentti_maara
 * @property string $maara_tyyppi
 * @property integer $jatkuva
 * @property integer $status
 */
class Kupongit extends DB2ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		$tb_name = 'kupongit';
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
                     'kupongin_id' => 'varchar(255) YES ',
                     'voimassa' => 'date ',
                     'euro_maara' => 'float YES 0 ',
                     'prosentti_maara' => 'int(11) YES 0 ',
                     'maara_tyyppi' => 'varchar(255) YES ',
                     'jatkuva' => 'int(1) 0 ',
                     'status' => 'int(1) 0 ',
                     //'asiakas_id' => 'int(11) ',
                     'lahetetyt_asiakas_id_lista' => 'text ',
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
			array('kupongin_id, voimassa, maara_tyyppi', 'required'),
                        array('kupongin_id','unique', 'message'=>'Tämä alennuskoodi on varattu.'),
			array('prosentti_maara, jatkuva, status', 'numerical', 'integerOnly'=>true),
			array('euro_maara', 'numerical'),
			array('kupongin_id, maara_tyyppi', 'length', 'max'=>255),
			array('lahetetyt_asiakas_id_lista', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, time, kupongin_id, voimassa, euro_maara, prosentti_maara, maara_tyyppi, jatkuva, status', 'safe', 'on'=>'search'),
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
			'kupongin_id' => Yii::t('main', 'Alennuskoodi'),
			'voimassa' => 'Voimassa',
			'euro_maara' => Yii::t('main', 'Euro määrä'),
			'prosentti_maara' => Yii::t('main', 'Prosentti määrä'),
			'maara_tyyppi' => Yii::t('main', 'Määrä tyyppi'),
			'jatkuva' => Yii::t('main', 'Useampikäyttöinen'),
			'status' => Yii::t('main', 'Käytetty'),
			'asiakas_id' => Yii::t('main', 'Asiakas'),
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
		$criteria->compare('kupongin_id',$this->kupongin_id);
		$criteria->compare('voimassa',$this->voimassa,true);
		$criteria->compare('euro_maara',$this->euro_maara);
		$criteria->compare('prosentti_maara',$this->prosentti_maara);
		$criteria->compare('maara_tyyppi',$this->maara_tyyppi,true);
		$criteria->compare('jatkuva',$this->jatkuva);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Kupongit the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
