<?php

/**
 * This is the model class for table "lasku_historia".
 *
 * The followings are the available columns in table 'lasku_historia':
 * @property integer $id
 * @property integer $lid
 * @property string $time
 * @property string $status
 * @property string $yht_euro
 */
class LaskuHistoria extends DB2ActiveRecord
{

public $viitenumero, $laskunumero, $yhteensa_total_veroton, $yhteensa_total_verot;


	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return LaskuHistoria the static model class
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
		$tb_name = 'lasku_historia';
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

                     'lid' => 'int(11) ',
                     'time' => 'timestamp DEFAULT CURRENT_TIMESTAMP ',
                     'status' => 'text ',
                     'yht_euro' => 'varchar(50) ',
                     'palvelu' => 'varchar(50) ',
                     'trust_statuscode' => 'varchar(100) ',
                     'paydate' => 'varchar(50) ',
                     'amount' => 'varchar(50) ',
                     'postita_statuscode' => 'varchar(100) ',




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
			//array('lid, time, status, yht_euro', 'required'),
			array('lid', 'numerical', 'integerOnly'=>true),
			array('status', 'length', 'max'=>2000),
			array('paydate, amount, yht_euro, palvelu', 'length', 'max'=>50),
			array('trust_statuscode, postita_statuscode', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, lid, time, status, yht_euro, trust_statuscode, paydate, amount', 'safe', 'on'=>'search'),
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
			'lid' => Yii::t('main', 'Lasku id'),
			'time' => Yii::t('main', 'Tapahtuma pvm'),
			'status' => Yii::t('main', 'Response'),
			'yht_euro' => Yii::t('main', 'Yht Euro'),
			'palvelu' => Yii::t('main', 'Palvelu'),
			'trust_statuscode'=> Yii::t('main', 'Trust statuscode'),
			'paydate' => Yii::t('main', 'Paydate'),
			'amount' => Yii::t('main', 'Amount'),
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
		$criteria->order = " id DESC ";

		$criteria->compare('id',$this->id);
		$criteria->compare('lid',$this->lid);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('yht_euro',$this->yht_euro,true);
		$criteria->compare('palvelu',$this->palvelu,true);
		$criteria->compare('trust_statuscode',$this->trust_statuscode);
		$criteria->compare('paydate',$this->paydate,true);
		$criteria->compare('amount',$this->amount,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}



}
