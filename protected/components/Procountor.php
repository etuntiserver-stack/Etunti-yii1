<?php

/** Procountor API manager. */
class Procountor extends CComponent
{
  private $redirect_uri;
  private $api_base_url;
  private $client_id;
  private $client_secret;

  /** Initialize Procountor. */
  public function __construct()
  {
    // If localhost, use testing environment.
    if (in_array($_SERVER['REMOTE_ADDR'], ['::1', '127.0.0.1'])) {
      $protocol = isset($_SERVER["HTTPS"]) ? "https://" : "http://";
      // even though I made the redirect_uri dynamic, at the time of writing
      // the allowed uri is http://etunti.local/index.php/asetukset/procountor_auth
      // in procountors end. it will not work on any other uri unless changed by them. shoot an email to support about it.
      $this->redirect_uri =  $protocol . $_SERVER["HTTP_HOST"] . "/index.php/asetukset/procountor_auth";
      $this->api_base_url  = 'https://api-test.procountor.com/api';
      $this->client_id     = 'etuntiTestClient';
      $this->client_secret = 'testsecret_W2ir6fiE4fdtO3Htevx9';
    }
    // Specify client id and secret for production.
    else {
      $this->redirect_uri  = 'https://app.etunti.fi/index.php/asetukset/procountor_auth';
      $this->api_base_url  = 'https://api.procountor.com/api';
      $this->client_id     = 'etuntiClient';
      $this->client_secret = 'secret_ib4Phz9XGYAoBrim7RQxUsuaarFCOH3Dky1ZP8vX6eHyJUCnox';
    }

    // Encode redirect URI to be used for authentication.
    $this->redirect_uri = urlencode($this->redirect_uri);
  }

  /** Get the encoded redirect uri. */
  public function getRedirectUri()
  {
    return $this->redirect_uri;
  }

  /**
   * Check if current user is authorized.
   *
   * @return bool
   * True if authorized; otherwise false. If current authorization is invalid
   * (access token expired and unable to refresh), returns false.
   */
  public function isAuthorized()
  {
    return !empty($this->getAccessToken());
  }

  /**
   * Log error in request, usually when 'errors' is defined in results.
   * Depending on server configuration, this may send error email to admin.
   *
   * @param string $request
   * Requested API call, e.g. "createInvoice".
   * @param array $results
   * Results array returned by the API function.
   * @param array $params
   * Additional parameters, like ['uid' => 123].
   * @param string $start_msg
   * First line of the log message.
   */
  public function logError(string $request, array $results, array $params = [], string $start_msg = 'Error in Procountor API request.')
  {
    $json = json_encode($results);
    $params_str = "";
    foreach ($params as $param => $value)
      $params_str .= "$param: $value\n";
    $stacktrace = (new \Exception())->getTraceAsString();
    $message = "$start_msg\nRequest: $request\nResponse: $json\n{$params_str}Stack trace:\n$stacktrace";
    Yii::getLogger()->log($message, 'error', 'procountor');
  }

  /**
   * Log general debug information (as WARNING to include results in the same
   * common log route) for solving some obscure problems.
   *
   * @param string $request
   * Requested API call, e.g. "createInvoice".
   * @param string $message
   * Main log message text.
   * @param array $results
   * Possible results array returned by an API function, if logging results.
   * @param array $params
   * Additional parameters, like ['uid' => 123] (included before stacktrace).
   */
  public function logDebug(string $request, string $message, array $results, array $params = [])
  {
    $message = "(Procountor debug :: /$request): $message";

    // Append request results when logging results.
    if (!empty($results)) {
      $message .= sprintf("\nResponse: %s", json_encode($results));
    }

    // Append possible parameters.
    if (!empty($params) && is_array($results)) {
      $params_str = "";
      foreach ($params as $param => $value)
        $params_str .= "$param: $value\n";
      $message .= sprintf("\n%s", $params_str);
    }

    // Append stacktrace.
    $message .= sprintf("Stack trace:\n%s", (new \Exception())->getTraceAsString());

    // Pass message to the logger.
    Yii::getLogger()->log($message, 'info', 'procountor');
  }

  /**
   * Create cURL request.
   *
   * @param string $target
   * URL after / (e.g. "invoices")
   * @param mixed $data
   * String or array containing post field data.
   * @param array $tags
   * Tags ( [ OPTION => VALUE, OPTION2 => VALUE2 ... ] )
   * @param bool $json
   * If true, 'Content-Type: application/json' is passed to the request.
   */
  private function request($target, $data = null, array $tags = ['CURLOPT_POST' => true], $json = true)
  {
    $asetukset = Asetukset::model()->findByPk(1);

    // Check if user hasn't authorized Procountor in this environment.
    if (empty($asetukset->procountor_refresh_token ?? ''))
      return ['auth_none' => true, 'errors' => 'Procountor is not authorized in this environment.'];

    // If refresh fails, getAccessToken() will mark this authorization as
    // invalid/expired. In that case, return now.
    if (empty($token = $this->getAccessToken()))
      return ['auth_invalid' => true, 'errors' => 'Procountor access token has expired and was unable to be refreshed.'];

    if (is_array($data))
      $data = json_encode($data);
    $ch = curl_init("{$this->api_base_url}/$target");
    if ($json) $header[] = "Content-Type: application/json";
    $header[] = "Authorization: Bearer $token";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    if (!empty($data))
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    foreach ($tags as $tag => $value)
      curl_setopt($ch, $tag, $value);
    return json_decode(curl_exec($ch), true);
  }

  /** Shortcut to request() with CURLOPT_CUSTOMREQUEST = 'GET'. */
  private function requestGet($target, $data = null, array $tags = [])
  {
    $tags[CURLOPT_CUSTOMREQUEST] = 'GET';
    return $this->request($target, $data, $tags, false);
  }

  /** Shortcut to request() with CURLOPT_CUSTOMREQUEST = 'PUT'. */
  private function requestPut($target, $data = null, array $tags = [])
  {
    $tags[CURLOPT_CUSTOMREQUEST] = 'PUT';
    return $this->request($target, $data, $tags, false);
  }

  // ---------------------------------------------------------------------------
  // Authorization
  // ---------------------------------------------------------------------------

  /**
   * Login to Procountor test environment.
   *
   * @param bool $force
   * If true, authorize even if already authorized.
   */
  public function authorize($code)
  {
    // This is needed for testing. However, once using the Procountor login
    // page, it provides an authorization code. I'm leaving this first part here
    // in case the authorization process needs to be updated in the future.

    // REQUEST 1: Authorization code.
    // POST https://api-test.procountor.com/api/oauth/authz
    // URL parameters:
    // 	response_type=code
    // 	client_id=<client_id>
    // 	state=<state>
    // POST parameters:
    // 	response_type=code
    // 	username=<username>
    // 	password=<password>
    // 	company=<company>
    // 	redirect_uri=<redirect_uri>
    // Headers:
    // 	Content-Type: application/x-www-form-urlencoded

    // Request authorization code.
    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, "{$this->api_base_url}/oauth/authz?response_type=code&client_id={$this->client_id}");
    // curl_setopt($ch, CURLOPT_POST, 1);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, "response_type=code&username={$this->user}&password={$this->pw}&company={$this->company}&redirect_uri={$this->redirect_uri}");
    // curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/x-www-form-urlencoded"]);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); //don't follow redirects
    // curl_exec($ch);
    // $curl_info = curl_getinfo($ch);
    // curl_close($ch);

    // Parse authorization code from response.
    // if (isset($curl_info['redirect_url'])) {
    //   $response_url_parts = parse_url($curl_info['redirect_url']);
    //   if (isset($response_url_parts['query'])) {
    //     parse_str($response_url_parts['query'], $query);
    //     if (isset($query['code']))
    //       $code = $query['code'];
    //   }
    // }

    // if (!isset($code))
    //   return false;

    // REQUEST 2: Swap the authorization code for an access token.
    // POST https://api-test.procountor.com/api/oauth/token
    // POST parameters:
    // 	grant_type=authorization_code
    // 	redirect_uri=<redirect_uri>
    // 	code=<code>
    // 	client_id=<client_id>
    // 	client_secret=<client_secret>
    // Headers:
    // 	Content-Type: application/x-www-form-urlencoded

    // Request access token and refresh token.
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "{$this->api_base_url}/oauth/token");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "client_id={$this->client_id}&client_secret={$this->client_secret}&grant_type=authorization_code&redirect_uri={$this->redirect_uri}&code=$code");
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/x-www-form-urlencoded"]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = json_decode(curl_exec($ch), true);
    curl_close($ch);

    if(!is_array($response)) {
      $this->logError("oauth/token", [], ["auth_code" => $code], "Response not an array");
      return false;
    }

    // Log any errors.
    if (isset($response['errors'])) {
      $this->logError('oauth/token', $response, ['auth_code' => $code], 'Failed to authorize using provided authorization code.');
      return false;
    } elseif (!isset($response['access_token']) || !isset($response['refresh_token']) || !isset($response['expires_in'])) {
      $this->logError('oauth/token', $response, ['auth_code' => $code], 'Unexpected response received from the server.');
      return false;
    }

    // Success, store current access token in settings.
    Asetukset::model()->updateByPk(1, [
      'procountor_access_token' => $response['access_token'],
      'procountor_refresh_token' => $response['refresh_token'],
      'procountor_refresh_time' => time(),
      'procountor_expires_in' => $response['expires_in'],
      'procountor_invalid' => 0
    ]);

    return true;
  }

  /**
   * Get access token for Procountor API requests. If stored access token is
   * valid, it is returned. Otherwise, a new access token is requested using a
   * stored refresh token. If token has expired, and refresh token is
   * unavailable, or parsing the new access token fails, returns false.
   *
   * @param bool $force
   * If true, access token is refreshed even if previous token is still valid.
   *
   * @return mixed
   * Current access token if it is still valid, or newly requested access token
   * if previous token had expired. If the previous token has expired or is
   * unavailable, and refresh token is unavailable or invalid, returns false.
   */
  public function getAccessToken($force = false)
  {
  
	//  AINA TARKISTA SARAKKEEN PITUUS  VARCHAR 500
  // I created a migration (in yii2 version) which updates all 
  // procountor token fields to varchar(500)
  
    $settings = Asetukset::model()->findByPk(1);
    $access_token = $settings->procountor_access_token;
    $refresh_time = $settings->procountor_refresh_time;
    $expires_in = $settings->procountor_expires_in;

    // Check if current access token is valid.
    if (!$force && !empty($access_token) && !empty($expires_in) && !empty($refresh_time))
      if (time() - $refresh_time < $expires_in) return $access_token;

    // Access token has expired and needs to be refreshed. If refresh token is
    // unavailable, return now to avoid errors.
    if (empty($refresh_token = $settings->procountor_refresh_token))
      return false;

    // POST https://api-test.procountor.com/api/oauth/token
    // POST parameters:
    // 	grant_type=refresh_token
    // 	refresh_token=<refresh_token>
    // 	client_id=<client_id>
    // 	client_secret=<client_secret>
    // Headers:
    // 	Content-Type:application/x-www-form-urlencoded

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "{$this->api_base_url}/oauth/token");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=refresh_token&refresh_token=$refresh_token&client_id={$this->client_id}&client_secret={$this->client_secret}");
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/x-www-form-urlencoded"]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = json_decode(curl_exec($ch), true);
    curl_close($ch);

    if (isset($response['access_token']) && isset($response['expires_in'])) {
      Asetukset::model()->updateByPk(1, [
        'procountor_access_token' => $response['access_token'],
        'procountor_refresh_time' => time(),
        'procountor_expires_in' => $response['expires_in'],
        'procountor_invalid' => 0
      ]);

      return $response['access_token'];
    }

    Asetukset::model()->updateByPk(1, ['procountor_invalid' => 1]);
    return false;
  }

  // ---------------------------------------------------------------------------
  // API functions
  // ---------------------------------------------------------------------------

  /**
   * Call API /invoices.
   * @param mixed $params
   * Parameters array, or ProcountorInvoiceSearchParameters object.
   */
  public function searchInvoices($params)
  {
    if ($params instanceof ProcountorInvoiceSearchParameters)
      $params = $params->buildParameters();
    elseif (is_array($params) && count($params) > 0)
      $params = http_build_query($params);
    $target = 'invoices';
    if (!empty($params))
      $target .= "?$params";
    return $this->requestGet($target);
  }

  /**
   * Call API /invoices.
   * @param array $params Parameters. Automatically converted to JSON.
   * @return string JSON response from the server.
   */
  public function createInvoice(array $params)
  {
    return $this->request('invoices', $params);
  }

  /**
   * Call API /invoices/{invoice_id}.
   * @param int $invoice_id Procountor invoice ID (Lasku->procountor_id).
   */
  public function getInvoice(int $invoice_id)
  {
    return $this->requestGet("invoices/$invoice_id");
  }

  /**
   * Call API /invoices/{invoiceId}/approve.
   * @param int $invoice_id Procountor invoice ID (Lasku->procountor_id).
   * @param string $comment Comment for verification or approval event.
   */
  public function approveInvoice(int $invoice_id, string $comment = null)
  {
    return $this->requestPut("invoices/$invoice_id/approve", $comment);
  }

  /**
   * Call API /invoices/{invoiceId}/invalidate.
   * @param int $invoice_id Procountor invoice ID (Lasku->procountor_id).
   */
  public function invalidateInvoice(int $invoice_id)
  {
    return $this->requestPut("invoices/$invoice_id/invalidate");
  }

  /**
   * Call API /invoices/{invoiceId}/send.
   * @param int $invoice_id Procountor invoice ID (Lasku->procountor_id).
   */
  public function sendInvoice(int $invoice_id)
  {
    return $this->requestPut("invoices/$invoice_id/send");
  }

  /**
   * Call API /invoices/{invoiceId}/unfinished.
   * @param int $invoice_id Procountor invoice ID (Lasku->procountor_id).
   */
  public function setInvoiceUnfinished(int $invoice_id)
  {
    return $this->requestPut("invoices/$invoice_id/unfinished");
  }

  /**
   * Call API /invoices/{invoiceId}/verify.
   * @param int $invoice_id Procountor invoice ID (Lasku->procountor_id).
   * @param string $comment Comment for verification or approval event.
   */
  public function verifyInvoice(int $invoice_id, string $comment = null)
  {
    return $this->requestPut("invoices/$invoice_id/verify", $comment);
  }

  /**
   * Call API /bankaccounts.
   *
   * @param int $previous_id
   * Previous bank account ID for pagination. If this field is set and results
   * are ordered by order number, value has to an identifier of existing bank
   * account in the given company. <= 0 to disable.
   * @param string $order_by_id
   * Order the results by bank account ID. Null to disable.
   * @param string $order_by_order_no
   * Order the results by bank account order number. Null to disable.
   * @param int $size
   * Page size for the results. Default value: 50. -1 to disable.
   */
  public function getBankAccounts(int $previous_id = -1, string $order_by_id = null, string $order_by_order_no = null, int $size = -1)
  {
    $data = [];
    if ($previous_id > 0)
      $data['previousId'] = $previous_id;
    if (!empty($order_by_id))
      $data['orderById'] = $order_by_id;
    if (!empty($order_by_order_no))
      $data['orderByOrderNo'] = $order_by_order_no;
    if ($size > 0)
      $data['size'] = $size;
    $target = 'bankaccounts';
    if (!empty($query = http_build_query($data)))
      $target .= "&$query";

    // Pass information to the temp log for solving some obscure error.
    $this->logDebug('bankaccounts', 'Requesting bank accounts.', [], [
      'data' => json_encode($data),
      'query' => json_encode($query),
      'final_target' => json_encode($target),
      'domain' => Yii::app()->user->domain
    ]);

    return $this->requestGet($target);
  }

  // ---------------------------------------------------------------------------
  // General
  // ---------------------------------------------------------------------------

  /**
   * Get translated status message from a Procountor statuscode.
   *
   * @param string $statuscode
   * Remote statuscode.
   * @return string
   * Translated status message.
   */
  public function translateProcountorStatus($statuscode)
  {
    // Available statuscodes: [
    //   EMPTY, UNFINISHED, NOT_SENT, SENT, RECEIVED, PAID, PAYMENT_DENIED,
    //   VERIFIED, APPROVED, INVALIDATED, PAYMENT_QUEUED, PARTLY_PAID,
    //   PAYMENT_SENT_TO_BANK, MARKED_PAID, STARTED, INVOICED, OVERRIDDEN,
    //   DELETED, UNSAVED, PAYMENT_TRANSACTION_REMOVED
    // ]
    switch ($statuscode) {
      case 'UNFINISHED':                  return 'Kesken';
      case 'SENT':                        return 'Lähetetty';
      case 'PAID':                        return 'Maksettu';
      case 'INVALIDATED':                 return 'Mitätöity';
      case 'EMPTY':                       return 'Tyhjä';
      case 'NOT_SENT':                    return 'Ei lähetetty';
      case 'RECEIVED':                    return 'Vastaanotettu';
      case 'PAYMENT_DENIED':              return 'Maksu epäonnistunut';
      case 'VERIFIED':                    return 'Varmistettu';
      case 'APPROVED':                    return 'Hyväksytty';
      case 'PAYMENT_QUEUED':              return 'Maksu jonossa';
      case 'PARTLY_PAID':                 return 'Osittain maksettu';
      case 'PAYMENT_SENT_TO_BANK':        return 'Maksu lähetetty pankille';
      case 'MARKED_PAID':                 return 'Merkitty maksetuksi';
      case 'STARTED':                     return 'Aloitettu';
      case 'INVOICED':                    return 'Laskutettu';
      case 'OVERRIDDEN':                  return 'Ohitettu';
      case 'DELETED':                     return 'Poistettu';
      case 'UNSAVED':                     return 'Tallentamatta';
      case 'PAYMENT_TRANSACTION_REMOVED': return 'Maksutapahtuma poistettu';
      case 'MUU': default:                return 'Muu tilanne';
    }
  }
}
