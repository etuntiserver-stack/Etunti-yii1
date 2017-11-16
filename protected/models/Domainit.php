<?php

/**
 * This is the model class for table "domainit".
 *
 * The followings are the available columns in table 'domainit':
 * @property integer $id
 * @property string $domain
 * @property integer $paketti
 */
class Domainit extends CActiveRecord
{

public $viesti;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Domainit the static model class
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
		$tb_name = 'domainit';

		$check_this_table = true;
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

                     'time' => 'timestamp DEFAULT CURRENT_TIMESTAMP ',
                     'domain' => 'varchar(100) ',
                     'paketti' => 'varchar(100) ',
                     'yritys' => 'varchar(100) ',
                     'puhelin' => 'varchar(255) ',
                     'sahkoposti' => 'varchar(255) ',
                     'pakettin_nimetus' => 'varchar(100) ',
                     'huoltokatko' => 'int(1) ',
                     'palveluhinta_persiivoja' => 'int(11) ',
                     'tyovuorohinta_persiivoja' => 'int(11) ',
                     'muut_tyokaluhinta' => 'int(11) ',
                     'aktiivinen' => 'int(1) DEFAULT 1 ',
                     'maksullinen' => 'int(1) DEFAULT 0 ',
                     'ilmainen_versio_kayttotunnit' => 'int(11) ',
                     'kirjautumistunnus' => 'varchar(100) ',
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

/*
	public static function PushNotify($tid,$title,$message,$sound){

		$t = Tyontekijat::model()->findbypk($tid); 
		$a = AsetuksetForAll::model()->find(" asetus='asetus1' ");
		
		//define( 'API_ACCESS_KEY', $a->api_access_key );
		$registrationIds = array( $t->gcm_reg_id );
		// prep the bundle
		$msg = array
		(
			'message' 	=> $message,
			'title'		=> $title,
			'subtitle'	=> 'This is a subtitle. subtitle',
			'tickerText'	=> 'Ticker text here...Ticker text here...Ticker text here',
			'vibrate'	=> 1,
			'sound'		=> $sound, // viella on "danger"
			'largeIcon'	=> 'large_icon',
			'smallIcon'	=> 'small_icon'
		);
		$fields = array
		(
			'registration_ids' 	=> $registrationIds,
			'data'			=> $msg
		);
		 
		$headers = array
		(
			'Authorization: key=' . $a->api_access_key,
			'Content-Type: application/json'
		);
		 
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );

		$result = json_decode($result);
		//var_dump($result->success);

		return $result;
	}
*/

	public static function sendGCM($tid, $subject, $message, $sound) 
	{
		$t = Tyontekijat::model()->findbypk($tid);
		$a = FirmanTiedot::model()->findbypk(1);
		$ApiKey = 'AIzaSyAPd72xCXt93mjgCq2gQu7F0Dg6BvLZ1tg';

		if(!isset($t->gcm_reg_id) or empty($t->gcm_reg_id))
		{
			//echo 'Push nitification error';
			return false;
		}
	
		$json_data = '{ 
			"data": { 
			  "Viesti": "'.$message.'"
	                },
	                "notification": {
	                  "title": "'.$a->tyonantaja.': '.$subject.'",
	                  "body": "'.$message.'",
	                  "sound": "default",
	                  "click_action": "FCM_PLUGIN_ACTIVITY",
	                  "icon": "icon_name"
	                },
	                "to": "'.$t->gcm_reg_id.'",
	                "priority": "high"
	              }';

		/*
		"data": { 
  	                  "price": "0",
	                  "currency": "EUR" 
	                },
		"to": "/topics/all",
		*/
	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                                            'Content-Type: application/json',                                                                                
                                            'Content-Length: '.strlen($json_data),
                                            'Authorization:key='.$ApiKey  
                                          ));           
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$output = curl_exec($ch);
		curl_close($ch);
		//echo $output;
		//exit;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('domain', 'required'),
			array('huoltokatko, palveluhinta_persiivoja, tyovuorohinta_persiivoja, muut_tyokaluhinta, aktiivinen, maksullinen', 'numerical', 'integerOnly'=>true),
			array('time, domain, paketti, yritys, pakettin_nimetus', 'length', 'max'=>100),
			array('puhelin, sahkoposti', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, domain, paketti, yritys, pakettin_nimetus', 'safe', 'on'=>'search'),
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
			'domain' => Yii::t('main', 'Domain'),
			'paketti' => Yii::t('main', 'Tasot'),
			'yritys' => Yii::t('main', 'Yritys'),
			'puhelin' => Yii::t('main', 'Puhelin'),
			'sahkoposti' => Yii::t('main', 'Sähköposti'),
			'pakettin_nimetus' => Yii::t('main', 'Pakettin nimetus'),
			'palveluhinta_persiivoja'=> Yii::t('main', 'Palveluhinta per siivoja'),
			'tyovuorohinta_persiivoja'=> Yii::t('main', 'Työvuorot hinta per siivoja'),
			'muut_tyokaluhinta'=> Yii::t('main', 'Muut työkalut hinta'),
			'time' => Yii::t('main', 'Perustettu'),
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

		$criteria->compare('id',$this->id);
		$criteria->compare('yritys',$this->yritys);
		$criteria->compare('pakettin_nimetus',$this->pakettin_nimetus);
		$criteria->compare('domain',$this->domain,true);
		$criteria->compare('paketti',$this->paketti);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
