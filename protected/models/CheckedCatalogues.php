<?php

class CheckedCatalogues extends DB2ActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return CheckedCatalogues the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return "checked_catalogues";
    }

    public function rules()
    {
        return [
            ["property_id, user_id", "required"],
            ["property_id, user_id", "numerical", "integerOnly" => true],
        ];
    }

    public function relations()
    {
        return [
            "property" => [self::HAS_ONE, "Kohteet", ["id" => "property_id"]],
            "user" => [self::HAS_ONE, "Administrators", ["id" => "user_id"]]
        ];
    }
}
