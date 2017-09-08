<?php

/**
 * This is the model class for table "tyonkuvaus_rivit".
 *
 * The followings are the available columns in table 'tyonkuvaus_rivit':
 * @property integer $id
 * @property integer $tyonkuvaus_id
 * @property string $tilat
 * @property string $tyontehtavat
 * @property string $viikkon_paivat
 * @property string $laatutaso
 * @property string $kommenti
 */
class TyonkuvausRivit extends DB2ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		$tb_name = 'tyonkuvaus_rivit';
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

                     'tyonkuvaus_id' => 'int(11) ',
                     'tilat' => 'text ',
                     'tyontehtavat' => 'text ',
                     'laatutaso' => 'text ',
                     'kommenti' => 'text ',




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
			array('tyonkuvaus_id', 'required'),
			array('tyonkuvaus_id', 'numerical', 'integerOnly'=>true),
			array('tilat, tyontehtavat, laatutaso, kommenti', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, tyonkuvaus_id, tilat, tyontehtavat, viikkon_paivat, laatutaso, kommenti', 'safe', 'on'=>'search'),
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
			'tyonkuvaus_id' => 'Tyonkuvaus',
			'tilat' => 'Tilat',
			'tyontehtavat' => 'Tyontehtavat',
			'viikkon_paivat' => 'Viikkon Paivat',
			'laatutaso' => 'Laatutaso',
			'kommenti' => 'Kommenti',
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
		$criteria->compare('tyonkuvaus_id',$this->tyonkuvaus_id);
		$criteria->compare('tilat',$this->tilat,true);
		$criteria->compare('tyontehtavat',$this->tyontehtavat,true);
		$criteria->compare('viikkon_paivat',$this->viikkon_paivat,true);
		$criteria->compare('laatutaso',$this->laatutaso,true);
		$criteria->compare('kommenti',$this->kommenti,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TyonkuvausRivit the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
