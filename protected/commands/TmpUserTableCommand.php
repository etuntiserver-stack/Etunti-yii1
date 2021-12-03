<?php

class TmpUserTableCommand extends CConsoleCommand
{

    private $tableName = "tmp_user";

    public function actionIndex()
    {
        $columns = [
            "id" => "int(11) AUTO_INCREMENT PRIMARY KEY",
            "domain" => "string",
            "domain_id" => "int",
            "password_unhashed" => "string",
            "password_hashed" => "string",
            "email" => "string",
            "active" => "string",
        ];
        Yii::app()->db->createCommand()->createTable($this->tableName, $columns);
        echo "Created table tmp_user in etuntifw" . PHP_EOL;
        return 0;
    }

    public function actionDrop()
    {
        Yii::app()->db->createCommand()->dropTable($this->tableName);
        echo "Dropped table tmp_user from etuntifw" . PHP_EOL;
    }
}
