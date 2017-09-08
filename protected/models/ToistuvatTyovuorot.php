<?php

/**
 * This is the model class for table "toistuvat_tyovuorot".
 *
 * The followings are the available columns in table 'toistuvat_tyovuorot':
 * @property integer $id
 * @property string $time
 * @property string $pfrom
 * @property string $pto
 * @property integer $viikkoja
 * @property string $viikko_paivat
 * @property integer $tid
 * @property integer $kohde
 * @property string $pvm
 * @property string $alku
 * @property string $loppu
 * @property string $kesto
 * @property string $tyoajanmerkinta
 * @property integer $status
 * @property string $tietoja
 * @property string $tyopaari
 */
class ToistuvatTyovuorot extends DB2ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		
		$tb_name = 'toistuvat_tyovuorot';
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
                     'pfrom' => 'varchar(50) ',
                     'pto' => 'varchar(50) ',
                     'viikkoja' => 'int(1) ',
                     'viikko_paivat' => 'text ',
                     'tid' => 'int(11) ',
                     'kohde' => 'int(11) ',
                     'pvm' => 'varchar(50) ',
                     'alku' => 'varchar(10) ',
                     'loppu' => 'varchar(10) ',
                     'pituus' => 'varchar(10) ',
                     'kesto' => 'varchar(10) ',
                     'tyoajanmerkinta' => 'varchar(100) ',
                     'status' => 'int(3) ',
                     'tietoja' => 'text ',
                     'tyopaari' => 'text ',
                     'ilmoitus_paattymisesta' => 'int(1) ',
                     'tvuoro_ids' => 'text ',




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
			//array('time, pfrom, pto, viikkoja, viikko_paivat, tid, kohde, pvm, alku, loppu, kesto, tyoajanmerkinta, status, tietoja, tyopaari', 'required'),
			array('viikkoja, tid, kohde, status, ilmoitus_paattymisesta', 'numerical', 'integerOnly'=>true),
			array('pfrom, pto, pvm', 'length', 'max'=>50),
			array('alku, loppu, pituus, kesto', 'length', 'max'=>10),
			array('tyoajanmerkinta', 'length', 'max'=>100),
			array('tietoja', 'length', 'max'=>10000),
			array('tyopaari, tvuoro_ids', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, time, pfrom, pto, viikkoja, viikko_paivat, tid, kohde, pvm, alku, loppu, kesto, tyoajanmerkinta, status, tietoja, tyopaari', 'safe', 'on'=>'search'),
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
			'pfrom' => 'Pfrom',
			'pto' => 'Pto',
			'viikkoja' => 'Viikkoja',
			'viikko_paivat' => 'Viikko Paivat',
			'tid' => 'Tid',
			'kohde' => 'Kohde',
			'pvm' => 'Pvm',
			'alku' => 'Alku',
			'loppu' => 'Loppu',
			'kesto' => 'Kesto',
			'tyoajanmerkinta' => 'Tyoajanmerkinta',
			'status' => 'Status',
			'tietoja' => 'Tietoja',
			'tyopaari' => 'Tyopaari',
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
		$criteria->compare('pfrom',$this->pfrom,true);
		$criteria->compare('pto',$this->pto,true);
		$criteria->compare('viikkoja',$this->viikkoja);
		$criteria->compare('viikko_paivat',$this->viikko_paivat,true);
		$criteria->compare('tid',$this->tid);
		$criteria->compare('kohde',$this->kohde);
		$criteria->compare('pvm',$this->pvm,true);
		$criteria->compare('alku',$this->alku,true);
		$criteria->compare('loppu',$this->loppu,true);
		$criteria->compare('kesto',$this->kesto,true);
		$criteria->compare('tyoajanmerkinta',$this->tyoajanmerkinta,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('tietoja',$this->tietoja,true);
		$criteria->compare('tyopaari',$this->tyopaari,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ToistuvatTyovuorot the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
