<?php

/**
 * Abstract class which can be extended as a base to
 * use in any command that needs to send a batch of emails to Mailguns API
 */
abstract class BatchEmailCommand extends CConsoleCommand
{
    /**
     * @param string $from Sender address, can include a name i.e. Kotipuhtaaksi <no-reply@kotipuhtaaksi.fi>
     * @param array $to Array of recipients
     * @param array $toVars Array of variables for the recipients, every recipient 
     * in the $to array should have variables defined here i.e. ["some@email.com" => ["varName" => "varValue"]].
     * The variables can be accessed in the body part of the message using %recipient.varName% syntax.
     * More info in https://documentation.mailgun.com/en/latest/user_manual.html?highlight=template%20variables#batch-sending
     * @param string $subject Subject of the email
     * @param string $body Body of the email, can have accessors for variables like %recipient.myVariable%. See $toVars above.
     * 
     * @throws ErrorException if recipient count doesn't match recipient-variable count, or the batch is larger than 1000.
     * 
     * @return array An array containing the response code, possible errors and result text.
     * ["responseCode" => 200, "errors" => "", "result" => 
     *   "{
     *     "id": "<20210920112657.1.C3FEDD4BB634288A@etunti.fi>",
     *     "message": "Queued. Thank you."
     *   }"
     * ]
     */
    protected function batchSendMail(string $from, Array $to, 
        Array $toVars, string $subject, string $body) 
    {
        // validate that $to and $toVars have the same number of keys
        if(sizeof($toVars) !== sizeof($to)) {
            throw new ErrorException("Recipient and recipient variable counts don't match");
        }
        // the API will only handle a maximum of 1000 emails per batch
        if(sizeof($to) > 1000) {
            throw new ErrorException(("Batch maximum size is 1000, split your batch into chunks"));
        }
        // Mailguns API endpoint for Etunti
        $url = "https://api.mailgun.net/v3/etunti.fi/messages";
        // Etuntis Mailgun API key
        $API_KEY = "key-ce340c4ce3c30b141c08b558187cea2d";
        // init curl
        $ch = curl_init();
        // set url, HTTP verb & capture transfer flags
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // request should be POST
        curl_setopt($ch, CURLOPT_POST, 1);
        // build POST body
        $post = [
            "from" => $from,
            "to" => $to,
            // recipient-variables have to be an JSON encoded string
            // https://documentation.mailgun.com/en/latest/user_manual.html?highlight=template%20variables#batch-sending
            "recipient-variables" => json_encode($toVars),
            "subject" => $subject,
            "text" => "Sähköpostisi ei tue HTML",
            "html" => $body,
        ];
        
        // set POST body, encode into query string which Mailgun can handle
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        // set content type as application/x-www-form-urlencoded
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/x-www-form-urlencoded"]);
        // set API key
        curl_setopt($ch, CURLOPT_USERPWD, "api:" . $API_KEY);

        // execute query
        $result = curl_exec($ch);
        $errors = curl_error($ch);
        $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // close curl
        curl_close($ch);
        // return response code, possible errors and response message
        return ["responseCode" => $response, "errors" => $errors, "result" => $result];
    }
}