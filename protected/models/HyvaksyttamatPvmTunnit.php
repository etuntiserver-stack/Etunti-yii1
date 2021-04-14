<?php

/**
 * This is the model class for table "hyvaksyttamat_pvm_tunnit".
 *
 * The followings are the available columns in table 'hyvaksyttamat_pvm_tunnit':
 * @property integer $id
 * @property string $time
 * @property string $pvm
 * @property integer $tid
 * @property integer $admin
 * @property string $json_arvot
 */
class HyvaksyttamatPvmTunnit extends DB2ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		$tb_name = 'hyvaksyttamat_pvm_tunnit';

		$table = Yii::app()->db1->schema->getTable($tb_name);
		if(!isset($table->columns['id'])) {
			Yii::app()->db1->createCommand(" CREATE TABLE IF NOT EXISTS $tb_name 
			(`id` int(11) AUTO_INCREMENT PRIMARY KEY)
			")->execute();
		}

		$table_structure = array(
                     'time' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
                     'pvm' => 'varchar(50) DEFAULT NULL',
                     'tid' => 'int(11) DEFAULT 0',
                     'admin' => 'int(11) DEFAULT 0',
                     'json_arvot' => 'text DEFAULT NULL',
                     'netvisor_ok_list' => 'text DEFAULT NULL',
                     'xml' => 'text DEFAULT NULL'
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
			array('pvm, tid, admin', 'required'),
			array('tid, admin', 'numerical', 'integerOnly'=>true),
			array('pvm', 'length', 'max'=>50),
			array('netvisor_ok_list, xml, json_arvot', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, time, pvm, tid, admin, json_arvot', 'safe', 'on'=>'search'),
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
			'pvm' => 'Pvm',
			'tid' => 'Tid',
			'admin' => 'Admin',
			'json_arvot' => 'Json Arvot',
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
		$criteria->compare('pvm',$this->pvm,true);
		$criteria->compare('tid',$this->tid);
		$criteria->compare('admin',$this->admin);
		$criteria->compare('json_arvot',$this->json_arvot,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HyvaksyttamatPvmTunnit the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
