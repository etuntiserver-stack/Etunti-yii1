<?php

/**
 * This is the model class for table "kuvia_kohteesta".
 *
 * The followings are the available columns in table 'kuvia_kohteesta':
 * @property integer $id
 * @property string $time
 * @property integer $kohde_id
 * @property string $osoite
 * @property integer $tid
 * @property integer $tekijan_nimi
 * @property string $tiedosto
 * @property string $kuvaus
 */
class KuviaKohteesta extends DB2ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		$tb_name = 'kuvia_kohteesta';
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
                     'kohde_id' => 'int(11) ',
                     'osoite' => 'varchar(255) ',
                     'tid' => 'int(11) ',
                     'tekijan_nimi' => 'varchar(255) ',
                     'tiedosto' => 'varchar(255) ',
                     'kuvaus' => 'text ',




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
			array('kohde_id, osoite, tekijan_nimi, tiedosto', 'required'),
			array('kohde_id, tid', 'numerical', 'integerOnly'=>true),
			array('osoite, tiedosto, tekijan_nimi', 'length', 'max'=>255),
			array('kuvaus', 'length', 'max'=>5000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, time, kohde_id, osoite, tid, tekijan_nimi, tiedosto, kuvaus', 'safe', 'on'=>'search'),
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
			'kohde_id' => 'Kohde',
			'osoite' => 'Osoite',
			'tid' => 'Tid',
			'tekijan_nimi' => 'Tekijan Nimi',
			'tiedosto' => 'Tiedosto',
			'kuvaus' => 'Kuvaus',
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
		$criteria->compare('kohde_id',$this->kohde_id);
		$criteria->compare('osoite',$this->osoite,true);
		$criteria->compare('tid',$this->tid);
		$criteria->compare('tekijan_nimi',$this->tekijan_nimi);
		$criteria->compare('tiedosto',$this->tiedosto,true);
		$criteria->compare('kuvaus',$this->kuvaus,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return KuviaKohteesta the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
