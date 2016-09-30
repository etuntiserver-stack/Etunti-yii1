<?php
/**
 * 
 * Used for db1 database connection
 *
 */
class DB2ActiveRecord extends CActiveRecord {

    private static $db1 = null;

    public function getDbConnection()
    {
        if (self::$db1 !== null)
            return self::$db1;
        else
        {
	  try{
            self::$db1 = Yii::app()->db1;
            if (self::$db1 instanceof CDbConnection)
            {
                self::$db1->setActive(true);
                return self::$db1;
            }
            else
                throw new CDbException(Yii::t('yii','Active Record requires a "db" CDbConnection application component.'));
	  } catch (Exception $e) {
    		header("Location: /index.php/user/login");
    		exit;
	  }
        }
    }
}
