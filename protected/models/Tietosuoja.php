<?php

/**
 * This is the model class for table "tietosuoja".
 *
 * The followings are the available columns in table 'tietosuoja':
 * @property integer $id
 * @property integer $col_1
 */
class Tietosuoja extends DB2ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{

		$tb_name = 'tietosuoja';
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
                     'asiakas_oikeusperuste' => 'varchar(255)',
                     'asiakas_kayttotarkoitus' => 'TEXT',
                     'asiakas_viesti' => 'TEXT',
                     'asiakas_sailytysajan_tyyppi' => 'int(1)',
                     'asiakas_sailytysaika_lukumaara' => 'int(3)',

                     'tyontekija_oikeusperuste' => 'varchar(255)',
                     'tyontekija_kayttotarkoitus' => 'TEXT',
                     'tyontekija_viesti' => 'TEXT',
                     'tyontekija_sailytysajan_tyyppi' => 'int(1)',
                     'tyontekija_sailytysaika_lukumaara' => 'int(3)',

                     'onlinevaraus_oikeusperuste' => 'varchar(255)',
                     'onlinevaraus_kayttotarkoitus' => 'TEXT',
                     'onlinevaraus_viesti' => 'TEXT',
                     'onlinevaraus_sailytysajan_tyyppi' => 'int(1)',
                     'onlinevaraus_sailytysaika_lukumaara' => 'int(3)',
		);
		$is_added_somthing = false;
		foreach($table_structure as $key=>$value)
		{
			if (!isset($table->columns[$key])) {
				Yii::app()->db1->createCommand()->addColumn($tb_name, $key, $value);
				$is_added_somthing = true;
			}
		}
		if($is_added_somthing)
		Yii::app()->controller->refresh();


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
			//array('col_1', 'required'),
			array('
				asiakas_sailytysajan_tyyppi, asiakas_sailytysaika_lukumaara, 
				tyontekija_sailytysajan_tyyppi, tyontekija_sailytysaika_lukumaara,
				onlinevaraus_sailytysajan_tyyppi, onlinevaraus_sailytysaika_lukumaara
			', 'numerical', 'integerOnly'=>true),
			array('asiakas_oikeusperuste, tyontekija_oikeusperuste, onlinevaraus_oikeusperuste', 'length', 'max'=>255),
			array('
				asiakas_kayttotarkoitus, asiakas_viesti,
				tyontekija_kayttotarkoitus, tyontekija_viesti,
				onlinevaraus_kayttotarkoitus, onlinevaraus_viesti
			', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id', 'safe', 'on'=>'search'),
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
			'asiakas_oikeusperuste' => Yii::t('main', 'Oikeusperuste'),
			'asiakas_kayttotarkoitus' => Yii::t('main', 'Käyttotarkoitus'),
			'asiakas_sailytysajan_tyyppi' => Yii::t('main', 'Säilytysajan tyyppi'),
			'asiakas_sailytysaika_lukumaara' => Yii::t('main', 'Säilytysaika'),
			'asiakas_viesti' => Yii::t('main', 'Viesti'),

			'tyontekija_oikeusperuste' => Yii::t('main', 'Oikeusperuste'),
			'tyontekija_kayttotarkoitus' => Yii::t('main', 'Käyttotarkoitus'),
			'tyontekija_sailytysajan_tyyppi' => Yii::t('main', 'Säilytysajan tyyppi'),
			'tyontekija_sailytysaika_lukumaara' => Yii::t('main', 'Säilytysaika'),
			'tyontekija_viesti' => Yii::t('main', 'Viesti'),

			'onlinevaraus_oikeusperuste' => Yii::t('main', 'Oikeusperuste'),
			'onlinevaraus_kayttotarkoitus' => Yii::t('main', 'Käyttotarkoitus'),
			'onlinevaraus_sailytysajan_tyyppi' => Yii::t('main', 'Säilytysajan tyyppi'),
			'onlinevaraus_sailytysaika_lukumaara' => Yii::t('main', 'Säilytysaika'),
			'onlinevaraus_viesti' => Yii::t('main', 'Viesti'),
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
		$criteria->compare('col_1',$this->col_1);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Tietosuoja the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
