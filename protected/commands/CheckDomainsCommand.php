<?php

/**
 * A simple command to check for the existance of a domains
 * database. 
 * Queries all domain names from etuntifw domains table by default.
 */
class CheckDomainsCommand extends CConsoleCommand
{
    public function actionIndex($db = null)
    {
        $domains = [];
        if($db) {
            $domains = [$db];
        } else {
            $domains = $this->getAllDbs();
        }

        echo implode(", " , $domains) . PHP_EOL;
        echo "Checking domain database existance for the forementioned domains." . PHP_EOL;
        echo "Database/domain count: " . count($domains) . PHP_EOL;
        $errors = $this->checkDomains($domains);
        if(count($errors) > 0) {
            echo "Errors occured in the following domains: " . PHP_EOL;
            echo implode(", ", $errors) . PHP_EOL;
            echo "Error count: " . count($errors) . PHP_EOL;
        } else {
            echo "No errors, all domains should exist." . PHP_EOL;
        }
        return 0;
    }

    private function checkDomains($domains)
    {
        $errorDomains = [];
        foreach($domains as $domain){
            try {
                $this->changeDbConnectionTo($domain);
            } catch (Exception $e) {
                $errorDomains[] = $domain;
            }
        }
        return $errorDomains;
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
     * Default db1 connection is always for "defdb" by default,
     * this function can be used to change the current database.
     */
    private function changeDbConnectionTo(string $domain)
    {
        Yii::app()->db1->setActive(false);
        Yii::app()->db1->connectionString = 'mysql:host=localhost;dbname=' . $domain;
        Yii::app()->db1->setActive(true);
    }
}
