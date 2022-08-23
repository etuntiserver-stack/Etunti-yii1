<?php

/**
 * etunti-cos-backup.php - Backs up databases to the Cloud Object Storage.
 * @author Arttu Huurinainen
 */

/** IBM Cloud Object Storage API controller. */
class COS
{
	private const API_KEY = '0Hns83I5xf2KAHBH0yb9iwdqF-8Ul9zef6-SzV8zjXzL';

	// Bucket etuntivs
	//private $ResourceInstanceID = 'crn:v1:bluemix:public:cloud-object-storage:global:a/852e56fec3654b9ca7428b1e0600cfe0:7c79c20e-8197-41b1-a795-82a7296925fe::';
	//private $ServiceEndpoint = 's3.ap.cloud-object-storage.appdomain.cloud';
	//private $DefaultBucketEndpoint = 's3.eu-de.cloud-object-storage.appdomain.cloud';
	//private $DefaultBucket = "etuntivs";

	// Bucket etunti-backup (with 7 day expiration)
	private $ResourceInstanceID = 'crn:v1:bluemix:public:cloud-object-storage:global:a/852e56fec3654b9ca7428b1e0600cfe0:7c79c20e-8197-41b1-a795-82a7296925fe::';
	private $ServiceEndpoint = 's3.private.eu-de.cloud-object-storage.appdomain.cloud';
	private $DefaultBucketEndpoint = 's3.private.eu-de.cloud-object-storage.appdomain.cloud';
	private $DefaultBucket = "etunti-backup";
	public $token;

	public function __construct()
	{
	}

	public function refreshToken()
	{
		// Request an IAM token using the API key. This is required for all action.
		$api_key = urlencode(self::API_KEY);
		$response_type = urlencode('cloud_iam');
		$grant_type = urlencode('urn:ibm:params:oauth:grant-type:apikey');
		$header = ['Accept: application/json', 'Content-Type: application/x-www-form-urlencoded'];
		$ch = curl_init("https://iam.cloud.ibm.com/identity/token?apikey=$api_key&response_type=$response_type&grant_type=$grant_type");
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		$result = curl_exec($ch);
		$result_decode = json_decode($result);
		if (!$result_decode || empty($result_decode->access_token))
			return false;
		$this->token = $result_decode->access_token;
		return true;
	}

	/**
	 * Get a list of buckets.
	 *
	 * @return mixed
	 * If request is successful and response is valid, returns associative array
	 * containing a list of buckets. If data is invalid, returns the HTTP response
	 * (result). If request fails, returns false.
	 */
	public function listBuckets()
	{
		return $this->request(
			"https://{$this->ServiceEndpoint}/",
			"ibm-service-instance-id: {$this->ResourceInstanceID}",
			'',
			'',
			true,
			true
		);
	}

	/**
	 * Add a bucket. Name needs to be lower-case.
	 * @return mixed
	 * HTTP response (result) on success, false on failure. Response is usually
	 * empty; if not, it contains error message.
	 */
	public function addBucket($name, $endpoint = '')
	{
		$endpoint = empty($endpoint) ? $this->DefaultBucketEndpoint : $endpoint;
		return $this->request(
			"https://$endpoint/$name",
			"ibm-service-instance-id: {$this->ResourceInstanceID}",
			"PUT"
		);
	}

	/**
	 * List objects in a bucket.
	 *
	 * @return mixed
	 * If request is successful and response is valid, returns associative array
	 * containing a list of objects. If data is invalid, returns the HTTP response
	 * (result). If request fails, returns false.
	 */
	public function listObjects($bucket = '', $endpoint = '')
	{
		$bucket = empty($bucket) ? $this->DefaultBucket : $bucket;
		$endpoint = empty($endpoint) ? $this->DefaultBucketEndpoint : $endpoint;
		return $this->request("https://$endpoint/$bucket", '', '', '', true, true);
	}

	/**
	 * Delete a bucket and all data contained in it.
	 * @return mixed
	 * HTTP response (result) on success, false on failure. Response is usually
	 * empty; if not, it contains error message.
	 */
	public function deleteBucket($bucket = '', $endpoint = '')
	{
		$bucket = empty($bucket) ? $this->DefaultBucket : $bucket;
		$endpoint = empty($endpoint) ? $this->DefaultBucketEndpoint : $endpoint;
		return $this->request("https://$endpoint/$bucket", "", "DELETE");
	}

	/**
	 * Upload an object into the bucket. Content-Type commonly:
	 *  'application/x-www-form-urlencoded' for raw data, or
	 *  'application/json'
	 * @see \COSManager::uploadObjectJson()
	 * @see \COSManager::uploadObjectData()
	 */
	public function uploadObject($data, $content_type, $object_key, $bucket = '', $endpoint = '')
	{
		$bucket = empty($bucket) ? $this->DefaultBucket : $bucket;
		$endpoint = empty($endpoint) ? $this->DefaultBucketEndpoint : $endpoint;
		return $this->request(
			"https://$endpoint/$bucket/$object_key",
			"Content-Type: $content_type",
			"PUT",
			$data
		);
	}

	/**
	 * Shortcut to uploadObject(), application/json
	 * @return mixed
	 * HTTP response (result) on success, false on failure. Response is usually
	 * empty; if not, it contains error message.
	 */
	public function uploadObjectJson($data, $object_key, $bucket = '', $endpoint = '')
	{
		return $this->uploadObject($data, "application/json", $object_key, $bucket, $endpoint);
	}

	/**
	 * Shortcut to uploadObject(), application/x-www-form-urlencoded
	 * @return mixed
	 * HTTP response (result) on success, false on failure. Response is usually
	 * empty; if not, it contains error message.
	 */
	public function uploadObjectData($data, $object_key, $bucket = '', $endpoint = '')
	{
		return $this->uploadObject($data, "application/x-www-form-urlencoded", $object_key, $bucket, $endpoint);
	}

	/**
	 * Loads file data and passes it to uploadObjectData().
	 * @return mixed
	 * HTTP response (result) on success, false on failure. Response is usually
	 * empty; if not, it contains error message.
	 */
	public function uploadFile($filename, $object_key, $bucket = '', $endpoint = '')
	{
		if (!file_exists($filename) || !($data = file_get_contents($filename)))
			return false;
		return $this->uploadObjectData($data, $object_key, $bucket, $endpoint);
	}

	/**
	 * Download an object from the bucket.
	 * @return mixed
	 * HTTP response (result) on success, false on failure. Response is usually
	 * empty; if not, it contains error message.
	 */
	public function downloadObject($object_key, $bucket = '', $endpoint = '')
	{
		$bucket = empty($bucket) ? $this->DefaultBucket : $bucket;
		$endpoint = empty($endpoint) ? $this->DefaultBucketEndpoint : $endpoint;
		return $this->request("https://$endpoint/$bucket/$object_key");
	}

	/**
	 * Delete an object from the bucket.
	 * @return mixed
	 * HTTP response (result) on success, false on failure. Response is usually
	 * empty; if not, it contains error message.
	 */
	public function deleteObject($object_key, $bucket = '', $endpoint = '')
	{
		$bucket = empty($bucket) ? $this->DefaultBucket : $bucket;
		$endpoint = empty($endpoint) ? $this->DefaultBucketEndpoint : $endpoint;
		return $this->request("https://$endpoint/$bucket/$object_key", "", "DELETE");
	}

	/**
	 * Create cURL request.
	 * @param string $addr URL Address
	 * @param mixed $header Additional header item, or array. Auth bearer is automatic.
	 * @param string $custom_request Customrequest field.
	 * @param string $post_fields Possible post fields.
	 * @param bool $return_transfer ReturnTransfer flag
	 * @param bool $return_xml If $return_transfer, whether to parse XML from returned data.
	 */
	private function request($addr, $header = '', $custom_request = '', $post_fields = '', $return_transfer = true, $return_xml = false)
	{
		$ch = curl_init($addr);

		// Set HTTPHeader
		$header_final = ["Authorization: bearer {$this->token}"];
		if (!empty($header)) {
			if (is_array($header)) {
				foreach ($header as $item)
					$header_final[] = $item;
			} else {
				$header_final[] = $header;
			}
		}
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header_final);

		// Set customrequest
		if (!empty($custom_request))
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $custom_request);

		// Set postfields
		if (!empty($post_fields))
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);

		// Execute
		if ($return_transfer) {
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			if ($return_xml) {
				// Try to parse XML
				if ($xml = simplexml_load_string($result))
					if ($json = json_encode($xml))
						return json_decode($json, true);
				return false;
			}
			return $result;
		} else {
			curl_exec($ch);
			return;
		}
	}
}

function error($key, $msg)
{
	echo "Error: $msg\n";
	error_log("Error in Etunti php backup script - $key: $msg");
	exit;
}

// Settings
//$error_mail = 'laptopsr@gmail.com'; // not used yet.
$db_skip = ['information_schema', 'mysql', 'performance_schema', 'sys', 'phpmyadmin'];
$db_host = 'localhost';
$db_user = 'etunti';
$db_pw = 'MfgTr1Bl8AAyid8Puq0g';
$db_port = 3306;

// Initialize COS - Using delfault settings for COS (default bucket: etuntivs)
$cos = new COS();
$cos->refreshToken();

// Get options (arguments).
$arg_domain = '';
$arg_full = false;
$val = getopt('d::f::', ['domain::', 'full::']);
if (count($val) <= 0)
	error('error_no_args', "no arguments provided\n");
while (count($val) > 0) {
	$key = array_keys($val)[0];
	$arg = array_shift($val);
	switch ($key) {
		case 'domain':
		case 'd':
			$arg_domain = $arg;
			break;
		case 'full':
		case 'f':
			$arg_full = $arg == true;
			break;
	}
}

// If no options, do nothing. This is for security reasons.
if (empty($arg_domain) && !$arg_full)
	error('error_no_action', "no action (provide domain or full=1)\n");

// Initialize database connection.
if (!$db = mysqli_connect($db_host, $db_user, $db_pw, 'information_schema', $db_port))
	error('db_connect_error', 'unable to connect to the database.');

// Build list of dbs to backup.
$schemas = [];
if (empty($arg_domain)) {
	// Domain not provided. This means, full=1 was provided.
	// Get a list of schemas from the host, excluding those in $db_skip.
	$schema_cmd = mysqli_query($db, "SELECT schema_name FROM information_schema.SCHEMATA;");
	if (!$schema_cmd)
		error('db_schemata_error', 'cannot get a list of databases from information_schema.');

	// Process results.
	while ($schema = $schema_cmd->fetch_row())
		if (!in_array($schema[0], $db_skip)) $schemas[] = $schema[0];

	// If no schemas, exit.
	if (count($schemas) <= 0)
		error('error_no_dbs', 'no databases to backup.');
} else {
	$schemas[] = $arg_domain;
}

// Do mysqldump and upload to COS, one by one.
foreach ($schemas as $schema) {
	// Parameters
	$key = date('Ymd-His-') . $schema . ".sql.gz";
	$fn = "/tmp/$key";

	// Perform mysqldump
	echo "Dumping $schema\n";
	exec("mysqldump -h$db_host -u$db_user -p$db_pw -P $db_port --single-transaction --quick $schema 2>/dev/null | gzip -c > $fn");
	$data = file_get_contents($fn);
	unlink($fn);

	// If data is empty, mysqldump has failed, for some reason.
	if (empty($data))
		error('error_dump_empty', "mysqldump output is empty for domain $schema.");

	// Upload to COS.
	echo "Uploading $schema dump to COS\n";
	if (!empty($result = $cos->uploadObjectData($data, $key)))
		error('error_upload_failed', "cloud object storage error: $result");
}

$log_handle = fopen('/var/log/etunti-backup-cos', 'a');
if ($log_handle) {
	fwrite($log_handle, 'Etunti backup finished');
	fclose($log_handle);
}
