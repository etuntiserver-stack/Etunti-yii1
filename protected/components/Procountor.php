<?php

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

	public function invoices($params)
	{
		// $result = $this->request('invoices', CURLOPT_POST, urlencode(json_encode($params)));
		$token = $this->getAccessToken();
		$ch = curl_init("{$this->api_base_url}/invoices");
		$data_json = json_encode($params);
		$len = strlen($data_json);

		// $result = file_get_contents("{$this->api_base_url}/invoices", false, stream_context_create([
		// 	'http' => [
		// 		'method' => 'POST',
		// 		'header' => "Content-Type: application/json\r\nContent-Length: $len\r\nAuthorization: bearer $token\r\n",
		// 		'content' => $data_json
		// 	]
		// ]));

		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
				"Content-Type: application/json",
				"Content-Length: $len",
				"Authorization: bearer $token"
		]);

		$result = curl_exec($ch);
		$info = curl_getinfo($ch);
		var_dump($result);
		var_dump($info);
		exit;
	}

	/**
	 * Create cURL request.
	 * @param string $addr URL after / (e.g. "invoices")
	 * @param string $method Method, e.g. CURLOPT_GET/CURLOPT_POST. Null to skip.
	 * @param string $post_fields Possible post fields (JSON).
	 * @param bool $return_transfer ReturnTransfer flag. If true, output of curl_exec is returned.
	 */
	private function request($addr, $method = CURLOPT_POST, $post_fields = '', $return_transfer = true)
	{
		if (empty($token = $this->getAccessToken()))
			return false;

		$ch = curl_init("{$this->api_base_url}/$addr");

		// Set HTTPHeader
		$header_final = [
			"Content-Type: application/json",
			"Authorization: bearer $token"
		];
		/* if (!empty($header)) {
			if (is_array($header)) {
				foreach ($header as $item)
					$header_final[] = $item;
			} else {
				$header_final[] = $header;
			}
		} */
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header_final);

		// Set method
		if (!empty($method))
			curl_setopt($ch, $method, 1);

		// Set postfields
		if (!empty($post_fields))
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);

		// Execute
		if ($return_transfer) {
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			return curl_exec($ch);
		} else {
			curl_exec($ch);
			return;
		}
	}
}
