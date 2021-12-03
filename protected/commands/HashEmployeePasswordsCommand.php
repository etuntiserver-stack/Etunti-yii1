<?php

// https://www.yiiframework.com/doc/guide/1.1/en/topics.console
// invoke with protected/yiic HashEmployeePasswords from the projects root
class HashEmployeePasswordsCommand extends CConsoleCommand
{

    public function actionIndex($db = null, $exclude = null, $only = null)
    {
        $domains = [];
        if ($db) {
            $domains = [$db];
        } else if ($only) {
            $domains = explode(",", $only);
        } else {
            $domains = $this->getAllDbs();
        }
        // remove any exluded domains
        if ($exclude) {
            $excluded = explode(",", $exclude);
            foreach ($excluded as $excl) {
                if (($key = array_search($excl, $domains)) !== false) {
                    unset($domains[$key]);
                }
            }
        }
        echo implode(", ", $domains) . PHP_EOL;
        echo "--------" . PHP_EOL;
        echo "Hashing sivex_ttekijat passwords for the forementioned domains." . PHP_EOL;
        echo "Database/domain count: " . count($domains) . PHP_EOL;
        // print exluded domains
        if ($exclude) {
            echo "Excluded domains: " . $exclude . PHP_EOL;
        }
        // confirm execution
        $confirmed = $this->confirm("Is this ok?");
        if ($confirmed) {
            echo "Hashing..." . PHP_EOL;
            $code = $this->hashPasswords($domains);
            echo "Successfully hashed passwords!" . PHP_EOL;
            return $code;
        } else {
            echo "Canceled" . PHP_EOL;
            return 0;
        }
    }

    /**
     * Loops through the domain list, hashing passwords
     * for employees if they have one defined in the database.
     */
    private function hashPasswords($domainList)
    {
        $format = "Y-m-d H:i:s";
        foreach ($domainList as $domain) {
            // change db connection
            $this->changeDbConnectionTo($domain);
            // print status
            $now = new DateTime("now");
            echo $now->format($format) . " Working on domain $domain..." . PHP_EOL;
            // get employee list
            $workers = $this->getAllEmployees();
            foreach ($workers as $workerRow) {
                $id = $workerRow["id"];
                $pw = $workerRow["salasana"];
                $email = $workerRow["tekijan_email"];
                $active = $workerRow["aktiivinen"];
                $hashed_pw = password_hash($pw, PASSWORD_BCRYPT);
                // insert user into temp table
                $this->insertIntoTmp($domain, $id, $pw, $hashed_pw, $email, $active);

                // update employees password
                Yii::app()->db1->createCommand()
                    ->update(
                        "sivex_ttekijat",
                        [
                            "salasana" => $hashed_pw
                        ],
                        "id=:id",
                        [":id" => $id]
                    );
            }
            // print done status
            $then = new DateTime("now");
            echo $then->format($format) . " Done." . PHP_EOL;
        }
        // return 0 on success
        return 0;
    }

    /**
     * Default db1 connection is always for "defdb" by default,
     * this function can be used to change the current database.
     */
    private function changeDbConnectionTo(string $domain)
    {
        Yii::app()->db1->setActive(false);
        Yii::app()->db1->connectionString = 'mysql:host=localhost;dbname=' . $domain;
        Yii::app()->db1->setActive(true);
    }

    /**
     * Returns a list of all domain names
     */
    private function getAllDbs()
    {
        $list = Yii::app()->db->createCommand()
            ->select("domain")
            ->from("domainit")
            ->queryAll();
        $domainNames = [];
        foreach ($list as $result) {
            $domainNames[] = $result["domain"];
        }
        return $domainNames;
    }

    /**
     * Returns a list of all employee rows for the currently
     * configured db1. Example of data structure:
     * It'll be an array of arrays with 'id' and 'salasana' key-value pairs.
     * [
     *   [
     *      "id" => 1,
     *      "salasana" => "some_password"
     *   ],
     *   [
     *     ...
     *   ], ...
     * ]
     */
    private function getAllEmployees()
    {
        return Yii::app()->db1->createCommand()
            ->select("id, salasana, tekijan_email, aktiivinen")
            ->from("sivex_ttekijat")
            // we only want defined passwords
            ->where("salasana != '' OR salasana != null")
            ->queryAll();
    }

    /**
     * Inserts users data into a temporary table, which can be used to verify
     * the hashed passwords.
     */
    private function insertIntoTmp($domain, $id, $unhashed, $hashed, $email, $active)
    {
        Yii::app()->db->createCommand()
            ->insert("tmp_user", [
                "domain" => $domain,
                "domain_id" => $id,
                "password_unhashed" => $unhashed,
                "password_hashed" => $hashed,
                "email" => $email,
                "active" => $active
            ]);
    }
}
