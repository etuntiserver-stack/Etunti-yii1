<?php

/** Procountor API manager. */
class Procountor extends CComponent
{
  private $settings;
  private $api_base_url  = 'https://api-test.procountor.com/api';
  private $client_id     = 'etuntiTestClient';
  private $client_secret = 'testsecret_W2ir6fiE4fdtO3Htevx9';
  private $redirect_uri  = 'redirect-uri-placeholder';
  private $user          = 'etunti.test';
  private $pw            = 'Elias2011!';
  private $company       = 15022;

  /** Initialize Procountor. */
  public function __construct()
  {
    $this->settings = Asetukset::model()->findByPk(1);
    $this->client_id = urlencode($this->client_id);
    $this->client_secret = urlencode($this->client_secret);
    $this->redirect_uri = urlencode($this->redirect_uri);
    $this->user = urlencode($this->user);
    $this->pw = urlencode($this->pw);
    $this->company = urlencode($this->company);
  }

  /**
   * Log error in request, usually when 'errors' is defined in results.
   * @param string $request Requested API call, e.g. "createInvoice".
   * @param array $results Results array returned by the API function.
   * @param array $params Additional parameters, like ['uid' => 123].
   * @param string $start_msg First line of the log message.
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
   * Create cURL request.
   *
   * @param string $target
   * URL after / (e.g. "invoices")
   * @param mixed $data
   * String or array containing post field data.
   * @param array $tags
   * Tags ( [ OPTION => VALUE, OPTION2 => VALUE2 ... ] )
   */
  private function request($target, $data = null, array $tags = ['CURLOPT_POST' => true])
  {
    if (empty($token = $this->getAccessToken()))
      return false;
    if (is_array($data))
      $data = json_encode($data);
    $ch = curl_init("{$this->api_base_url}/$target");
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json", "Authorization: Bearer $token"]);
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
    $tags['CURLOPT_CUSTOMREQUEST'] = 'GET';
    $this->request($target, $data, $tags);
  }

  /** Shortcut to request() with CURLOPT_CUSTOMREQUEST = 'PUT'. */
  private function requestPut($target, $data = null, array $tags = [])
  {
    $tags['CURLOPT_CUSTOMREQUEST'] = 'PUT';
    $this->request($target, $data, $tags);
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
  public function authorize()
  {
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
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "{$this->api_base_url}/oauth/authz?response_type=code&client_id={$this->client_id}");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "response_type=code&username={$this->user}&password={$this->pw}&company={$this->company}&redirect_uri={$this->redirect_uri}");
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/x-www-form-urlencoded"]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); //don't follow redirects
    curl_exec($ch);
    $curl_info = curl_getinfo($ch);
    curl_close($ch);

    // Parse authorization code from response.
    if (isset($curl_info['redirect_url'])) {
      $response_url_parts = parse_url($curl_info['redirect_url']);
      if (isset($response_url_parts['query'])) {
        parse_str($response_url_parts['query'], $query);
        if (isset($query['code']))
          $code = $query['code'];
      }
    }

    if (!isset($code)) {
      // echo "Unable to parse authorization code.";
      return false;
    }

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

    // Check that the request was successful.
    if (isset($response['access_token']) && isset($response['refresh_token']) && isset($response['expires_in'])) {

      // Success, store current access token in settings.
      Asetukset::model()->updateByPk(1, [
        'procountor_access_token' => $response['access_token'],
        'procountor_refresh_token' => $response['refresh_token'],
        'procountor_refresh_time' => time(),
        'procountor_expires_in' => $response['expires_in']
      ]);

      return true;
    }

    return false;
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
    $access_token = $this->settings->procountor_access_token;
    $refresh_time = $this->settings->procountor_refresh_time;
    $expires_in = $this->settings->procountor_expires_in;

    // Check if current access token is valid.
    if (!$force && !empty($access_token) && !empty($expires_in) && !empty($refresh_time))
      if (time() - $refresh_time < $expires_in) return $access_token;

    // Access token has expired and needs to be refreshed. If refresh token is
    // unavailable, return now to avoid errors.
    if (empty($refresh_token = $this->settings->procountor_refresh_token))
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
        'procountor_expires_in' => $response['expires_in']
      ]);

      return $response['access_token'];
    }

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
    return $this->requestGet('invoices', $params);
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
    return $this->requestGet($target);
  }
}
