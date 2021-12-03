<?php

/**
 * Dumps sivex_ttekijat table into an sql file.
 * Files will be placed to the directory where you execute the command from.
 * 
 * https://www.yiiframework.com/doc/guide/1.1/en/topics.console
 * invoke with protected/yiic EmployeeBackup from the projects root
 * Required parameters (for actionIndex) are user and pw
 * unless you use --nocreds
 */
class EmployeeBackupCommand extends CConsoleCommand
{

    private $fileName = "_ttekijat_hashbackup.sql";

    public function actionIndex($user = null, $pw = null, $db = null, $only = null, $exclude = null, $nocreds = null)
    {
        if($nocreds === null) {
            if(!$user) {
                echo "Define a username via --user" . PHP_EOL;
                return 1;
            }
            if(!$pw) {
                echo "Define a password via --pw" . PHP_EOL;
                return 1;
            }
        }
        
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
        echo "Dumping sivex_ttekijat table for the forementioned domains." . PHP_EOL;
        echo "Database/domain count: " . count($domains) . PHP_EOL;
        echo "Backup files will be located in:" . PHP_EOL;
        $output = null;
        exec("pwd", $output);
        echo $output[0];
        echo PHP_EOL;
        // print exluded domains
        if ($exclude) {
            echo "Excluded domains: " . $exclude . PHP_EOL;
        }
        $confirmed = $this->confirm("Is this ok?");
        if($confirmed) {
            echo "Dumping..." . PHP_EOL;
            $code = $this->dumpTables($user, $pw, $domains, $nocreds);
            if($code == 0) {
                echo "Successfully dumped tables" . PHP_EOL;
            }
            return $code;
        } else {
            echo "Canceled" . PHP_EOL;
            return 0;
        }
    }

    public function actionPurge($db = null, $only = null, $exclude = null)
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
        echo "Backup files will be removed from:" . PHP_EOL;
        $output = null;
        exec("pwd", $output);
        echo $output[0];
        echo PHP_EOL;
        $confirmed = $this->confirm("Is this ok?");
        if($confirmed) {
            $code = $this->purge($domains);
            if($code == 0) {
                echo "Successfully purged backups" . PHP_EOL;
            }
            return $code;
        } else {
            echo "Canceled" . PHP_EOL;
            return 0;
        }
    }

    private function dumpTables($user, $pw, $domains, $nocreds)
    {
        foreach($domains as $domain) {
            $fileName = $domain . $this->fileName;
            $cmd = "mysqldump -u $user -p'$pw' $domain sivex_ttekijat > $fileName";
            if($nocreds !== null) {
                $cmd = "mysqldump $domain sivex_ttekijat > $fileName";
            }
            $output = null;
            $retval = null;
            exec($cmd, $output, $retval);
            if($retval == 1) {
                echo "Error while dumping $domain" . PHP_EOL;
                print_r($output);
                return 1;
            }
        }
        return 0;
    }

    private function purge($domains)
    {
        foreach($domains as $domain) {
            $fileName = $domain . $this->fileName;
            $cmd = "rm $fileName";
            $output = null;
            $retval = null;
            exec($cmd, $output, $retval);
            if($retval == 1) {
                echo "Error while purging $domain" . PHP_EOL;
                print_r($output);
                echo PHP_EOL;
                return 1;
            }
        }
        return 0;
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
}