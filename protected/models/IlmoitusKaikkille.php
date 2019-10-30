<?php

/**
 * This is the model class for table "ilmoitus_kaikkille".
 *
 * The followings are the available columns in table 'ilmoitus_kaikkille':
 * @property integer $id
 * @property string $time
 * @property string $viesti
 * @property string $lopetus
 */
class IlmoitusKaikkille extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		$tb_name = 'ilmoitus_kaikkille';
		$check_this_table = true;

		if($check_this_table)
		{
		$table = Yii::app()->db->schema->getTable($tb_name);
		if(!isset($table->columns['id'])) {

			Yii::app()->db->createCommand(" CREATE TABLE IF NOT EXISTS $tb_name 
			(`id` int(11) AUTO_INCREMENT PRIMARY KEY)
			")->execute();
		}

		$table_structure = array(
                     'time' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
                     'viesti' => 'TEXT DEFAULT NULL',
                     'aloitus' => 'DATETIME DEFAULT NULL',
                     'lopetus' => 'DATETIME DEFAULT NULL',
                     'vastaanottajat' => 'TEXT DEFAULT NULL',
		);

		foreach($table_structure as $key=>$value)
		{
			if (!isset($table->columns[$key])) {
				Yii::app()->db->createCommand()->addColumn($tb_name, $key, $value);
			}
		}

		} // check

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
			array('viesti, vastaanottajat, aloitus, lopetus', 'required'),
			array('viesti, aloitus, lopetus, vastaanottajat', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, time, viesti, aloitus, lopetus', 'safe', 'on'=>'search'),
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
			'viesti' => 'Viesti',
			'lopetus' => 'Lopetus',
			'aloitus' => 'Aloitus'
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
		$criteria->compare('viesti',$this->viesti,true);
		$criteria->compare('aloitus',$this->aloitus,true);
		$criteria->compare('lopetus',$this->lopetus,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return IlmoitusKaikkille the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
