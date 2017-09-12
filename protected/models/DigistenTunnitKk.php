<?php

/**
 * This is the model class for table "digisten_tunnit_kk".
 *
 * The followings are the available columns in table 'digisten_tunnit_kk':
 * @property integer $id
 * @property string $time
 * @property string $domain
 * @property integer $year
 * @property integer $month
 * @property integer $tunnit
 * @property string $tasot
 * @property integer $maksettu
 */
class DigistenTunnitKk extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		$tb_name = 'digisten_tunnit_kk';

		$table = Yii::app()->db->schema->getTable($tb_name);
		if(!isset($table->columns['id'])) {

			Yii::app()->db->createCommand(" CREATE TABLE IF NOT EXISTS $tb_name 
			(`id` int(11) AUTO_INCREMENT PRIMARY KEY)
			")->execute();
		}

		$table_structure = array(

                     'time' => 'timestamp DEFAULT CURRENT_TIMESTAMP ',
                     'domain_id' => 'int(11) ',
                     'domain' => 'varchar(255) YES ',
                     'year' => 'int(4) ',
                     'month' => 'int(2) ',
                     'tunnit' => 'int(11) ',
                     'tasot' => 'text ',
                     'maksettu' => 'int(1) 0 ',
                     'laskutettu' => 'int(1) ',
                     'lasku_id' => 'int(11) ',
		     'jarjestelmanvalvoja_maara' => 'int(11) ',


		);

		foreach($table_structure as $key=>$value)
		{
			if (!isset($table->columns[$key])) {
				Yii::app()->db->createCommand()->addColumn($tb_name, $key, $value);
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
			array('year, month, tunnit, tasot', 'required'),
			array('year, month, tunnit, maksettu, domain_id, laskutettu, lasku_id, jarjestelmanvalvoja_maara', 'numerical', 'integerOnly'=>true),
			array('domain', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, time, domain, year, month, tunnit, tasot, maksettu', 'safe', 'on'=>'search'),
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
		        'domainit' => array(self::BELONGS_TO, 'Domainit', 'domain_id'),
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
			'domain' => 'Domain',
			'year' => 'Year',
			'month' => 'Month',
			'tunnit' => 'Tunnit',
			'tasot' => 'Tasot',
			'maksettu' => 'Maksettu',
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
		$criteria->compare('domain',$this->domain,true);
		$criteria->compare('year',$this->year);
		$criteria->compare('month',$this->month);
		$criteria->compare('tunnit',$this->tunnit);
		$criteria->compare('tasot',$this->tasot,true);
		$criteria->compare('maksettu',$this->maksettu);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DigistenTunnitKk the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
