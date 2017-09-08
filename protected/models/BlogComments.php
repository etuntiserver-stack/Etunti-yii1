<?php

/**
 * This is the model class for table "blog_comments".
 *
 * The followings are the available columns in table 'blog_comments':
 * @property integer $id
 * @property integer $blog_id
 * @property string $time
 * @property string $nimimerkki
 * @property string $teksti
 */
class BlogComments extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		$tb_name = 'blog_comments';
		$check_this_table = false;
		if(!isset(Yii::app()->session[$tb_name]))
		{
			Yii::app()->session[$tb_name] = true;
			$check_this_table = true;
		}


		if($check_this_table)
		{
		$table = Yii::app()->db->schema->getTable($tb_name);
		if(!isset($table->columns['id'])) {

			Yii::app()->db->createCommand(" CREATE TABLE IF NOT EXISTS $tb_name 
			(`id` int(11) AUTO_INCREMENT PRIMARY KEY)
			")->execute();
		}

		$table_structure = array(


                     'blog_id' => 'int(11) AFTER id',
                     'time' => 'timestamp DEFAULT CURRENT_TIMESTAMP AFTER blog_id',
                     'nimimerkki' => 'varchar(255) AFTER time',
                     'teksti' => 'text AFTER nimimerkki',
               


		);

		foreach($table_structure as $key=>$value)
		{
			if (!isset($table->columns[$key])) {
				Yii::app()->db->createCommand()->addColumn($tb_name, $key, $value);
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
			array('blog_id, nimimerkki, teksti', 'required'),
			array('blog_id', 'numerical', 'integerOnly'=>true),
			array('nimimerkki', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, blog_id, time, nimimerkki, teksti', 'safe', 'on'=>'search'),
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
			'blog_id' => 'Blog',
			'time' => 'Time',
			'nimimerkki' => 'Nimimerkki',
			'teksti' => 'Teksti',
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
		$criteria->order=" id DESC ";

		$criteria->compare('id',$this->id);
		$criteria->compare('blog_id',$this->blog_id);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('nimimerkki',$this->nimimerkki,true);
		$criteria->compare('teksti',$this->teksti,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BlogComments the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
