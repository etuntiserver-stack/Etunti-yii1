<?php

/**
 * The command doesn't actually reset the passwords, but it will send
 * an email to all active workers with a password change link.
 * 
 * You can specify a single domain, to only send password reset links
 * to that domain. Default is all domains.
 */
class MassResetPasswordCommand extends BatchEmailCommand
{

    public function actionIndex($domain = null, $server = "https://app.etunti.fi", $testRun = 0)
    {
        $domains = [];
        if($domain) {
            $domains = [$domain];
        } else {
            $domains = $this->getAllDbs();
        }

        echo implode(", ", $domains). PHP_EOL;
        echo "------" . PHP_EOL;
        echo "Sending password reset emails to all active workers in the forementioned domains" . PHP_EOL;
        echo "Links will be generated for server: " . $server . PHP_EOL;
        echo "Simulated URL: " . $this->createResetUrl($server, $domains[0], "some_token_here", 1) . PHP_EOL;
        if($testRun) {
            echo "Test run active, not actually sending emails." . PHP_EOL;
        }
        $confirmed = $this->confirm("Is this ok?");
        if($confirmed) {
            echo "Sending..." . PHP_EOL;
            $code = $this->createEmails($server, $domains, $testRun);
            echo "Successfully sent reset emails!" . PHP_EOL;
            return $code;
        } else {
            echo "Canceled" . PHP_EOL;
            echo "You can define the server using --server= param, make sure to include https:// or http://" . PHP_EOL;
            return 0;
        }

        return 0;
    }

    private function createEmails($server, $domains, $testRun = 0)
    {
        $subject = "Etunnin salasanan vaihto / Etunti password change";
        $format = "Y-m-d H:i:s";

        foreach($domains as $domain) {
            // loop through all employees
            $this->changeDbConnectionTo($domain);
            $now = new DateTime("now");
            echo $now->format($format) . " Working on domain $domain..." . PHP_EOL;
            $employees = $this->getAllEmployees();
            /*
            //test / example data
            $employees = [
                ["id" => 618, "tekijan_email" => "simo@kotipuhtaaksi.fi", "tekijan_nimi" => "Simo"],
            ];
            */
            foreach($employees as $employee) {
                $email = $employee["tekijan_email"];
                $id = $employee["id"];
                $name = $employee["tekijan_nimi"];
                // validate email
                if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $token = $token = sha1(uniqid(time().$id, true));
                    // update employees token to DB
                    $this->insertToken($token, $id);
                    // save token to employees data
                    $resetUrl = $this->createResetUrl($server, $domain, $token, $id);

                    $body = "Hei / Hi $name!<br>
                    <strong>Domain: </strong>$domain<br>
                    <strong>Käyttäjätunnus: </strong>$email<br>
                    <strong>Vaihda salasana / Change password: </strong>
                    <a href=\"$resetUrl\">täällä / here</a><br>
                    ";

                    if($testRun == 0) {
                        // craft & send email
                        $mail = new YiiMailer();
                        $mail->setFrom("no-reply@etunti.fi");
                        $mail->setLayout("console-mail");
                        $mail->setTo($email);
                        $mail->setSubject($subject);
                        $mail->setBody($body);
                        $mail->send();
                    }
                    
                } else {
                    echo "Skipping employee $id, invalid email '$email'" . PHP_EOL;
                }
            }
            $then = new DateTime("now");
            echo $then->format($format) . " Done." . PHP_EOL;
        }
        return 0;
    }

    /**
     * Returns a list of all employees for the current domain.
     * example:
     * [
     *     [
     *         "id" => 1,
     *         "tekijan_email" => "some_email@email.com",
     *         "tekijan_nimi" => "Name"
     *     ],
     *     ...
     * ]
     */
    private function getAllEmployees()
    {
        return Yii::app()->db1->createCommand()
            ->select("id, tekijan_email, tekijan_nimi")
            ->from("sivex_ttekijat")
            ->where("tekijan_email != '' AND aktiivinen = 1")
            ->queryAll();
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

    private function createResetUrl($server, $domain, $token, $id)
    {
        return $server . "/index.php/tyontekijat/salasana?domain=$domain&token=$token&id=$id";
    }

    /**
     * Inserts a generated token to some employees data
     */
    private function insertToken($token, $id)
    {
        Yii::app()->db1->createCommand()
            ->update("sivex_ttekijat",
            [
                "token" => $token
            ],
            "id=:id",
            [":id" => $id]
        );
    }
}
