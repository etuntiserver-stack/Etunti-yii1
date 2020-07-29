<?php

/** Freshdesk API manager. */
class Freshdesk extends CComponent
{
  /** @var string Base URL for Freshdesk services. */
  private const BASE_URL = 'https://kotipuhtaaksi.freshdesk.com';
  /** @var string Default production API key. */
  private const API_KEY = 'zhyJbMtxnYcaDjaY0wr';
  /** @var string Base URL for Freshdesk services. */
  private const TEST_BASE_URL = 'https://santelo.freshdesk.com';
  /** @var string Default testing API key. */
  private const TEST_API_KEY = 'DSoSK72c321RokLStzw4';

  /** @var int Amount of seconds until next request when request limit is reached. */
  private static $retryAfter = 0;

  /** @var bool Whether this component is using the testing environment. */
  private $testing;
  /** @var string API key. */
  private $key;
  /** @var string Base URL for Freshdesk services. */
  private $baseUrl;
  /** @var string API URL, without trailing slash. */
  private $url;
  /** @var bool Flag true if invalid domain and not in testing environment */
  private $disabled = false;

  /**
   * Initialize Freshdesk.
   *
   * @param bool $testing
   * If true or false, force testing/production environment. If null, testing
   * environment is used when remote address is localhost (::1 or 127.0.0.1).
   */
  public function __construct($testing = null)
  {
    $domain = Yii::app()->user->domain;
    if (is_bool($testing))
      $this->testing = $testing;
    else
      $this->testing = in_array($_SERVER['REMOTE_ADDR'], ['::1', '127.0.0.1']) || $domain == 'demo' || $domain == 'staging_demo';

    // Specify base url and api key for actions.
    if ($this->testing) {
      $this->key = static::TEST_API_KEY;
      $this->baseUrl = static::TEST_BASE_URL;
      $this->url = static::TEST_BASE_URL . '/api/v2';
    } elseif ($domain == 'kotipuhtaaksi') {
      $this->key = static::API_KEY;
      $this->baseUrl = static::BASE_URL;
      $this->url = static::BASE_URL . '/api/v2';
    } else {
      $this->disabled = true;
    }
  }

  //*------------------------------------------------------------------------------------------------
  //* cURL Functions
  //*------------------------------------------------------------------------------------------------

  /**
   * Create and execute a cURL request.
   *
   * @param string $target
   * URL after / (API function name).
   * @param array $post_fields
   * Optional post field data.
   * @param array $tags
   * Tags ( [ OPTION => VALUE, OPTION2 => VALUE2 ... ] )
   * @param mixed $headers
   * If provided, possible headers returned by the request are assigned here.
   * @return mixed
   * Decoded response.
   *
   * If an error occurs, and the returned array includes "errors", the error is
   * automatically logged. However, the results are returned as is. General
   * error result format:
   * {
   *   "description":"Validation failed",
   *   "errors":[
   *     {
   *       "field":"name",
   *       "message":"Mandatory attribute missing",
   *       "code":"missing_field"
   *     }
   *   ]
   * }
   */
  private function request(string $target, array $post_fields = [], array $tags = [], &$headers = null, $ignore_errors = false)
  {
    if ($this->disabled) {
      $this->logError('Request from invalid domain');
      return [];
    }

    if (empty($target) && !$ignore_errors) {
      static::logError("request() was called with null target.", $post_fields);
      return false;
    }

    if (static::$retryAfter > 0) {
      $this->log('Waiting on request %s (retryAfter=%d; rate limit reached).', $target, static::$retryAfter);
      sleep(static::$retryAfter + 1);
      static::$retryAfter = 0;
    }

    $this->logLocal('Request: %s (params: %s)', $target, json_encode($post_fields));

    if ($headers === null)
      $headers = [];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "{$this->url}/$target");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "{$this->key}:x");
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    if (!empty($post_fields))
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_fields));
    foreach ($tags as $tag => $value)
      curl_setopt($ch, $tag, $value);

    // Execute request and get header size to separate headers and body.
    curl_setopt($ch, CURLOPT_HEADER, true);
    $response = curl_exec($ch);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    curl_close($ch);

    // Parse headers
    if (!empty($header_string = substr($response, 0, $header_size))) {
      foreach (explode("\r\n", $header_string) as $i => $line) {
        if (!empty($line) && !ctype_space($line)) {
          if ($i === 0)
            $headers['http_code'] = $line;
          elseif (($split = explode(': ', $line, 2)) !== false)
            $headers[strtolower($split[0])] = $split[1];
        }
      }
    }

    // Parse and decode body, and log possible errors.
    $body = json_decode(substr($response, $header_size), true);
    if (isset($body['errors']) && !$ignore_errors) {
      $this->logLocalRequestError($target, $body, $post_fields, $headers);
    }

    // Check if rate limit was reached.
    if (isset($headers['retry-after'])) {
      static::$retryAfter = $headers['retry-after'];
      $this->log('Request limit reached, retry-after: %d', $headers['retry-after']);
    }

    return $body;
  }

  /**
   * Create and execute a cURL request.
   *
   * Shortcut to request() with CURLOPT_POST = true.
   *
   * @param string $target
   * URL after / (API function name).
   * @param array $post_fields
   * Optional post field data.
   * @param array $tags
   * Tags ( [ OPTION => VALUE, OPTION2 => VALUE2 ... ] )
   * @param mixed $headers
   * If provided, possible headers returned by the request are assigned here.
   * @return mixed
   * Decoded response.
   *
   * If an error occurs, and the returned array includes "errors", the error is
   * automatically logged. However, the results are returned as is. General
   * error result format:
   * {
   *   "description":"Validation failed",
   *   "errors":[
   *     {
   *       "field":"name",
   *       "message":"Mandatory attribute missing",
   *       "code":"missing_field"
   *     }
   *   ]
   * }
   */
  private function requestPost(string $target, array $post_fields = [], array $tags = [], &$headers = null, $ignore_errors = false)
  {
    $tags["CURLOPT_POST"] = 1;
    return $this->request($target, $post_fields, $tags, $headers, $ignore_errors);
  }

  /**
   * Create and execute a cURL request.
   *
   * Shortcut to request() with CURLOPT_CUSTOMREQUEST = 'GET'.
   *
   * @param string $target
   * URL after / (API function name).
   * @param array $query_params
   * Optional parameters for the query string. If not empty, query string is
   * formed using http_build_query. Empty strings are removed first.
   * @param array $tags
   * Tags ( [ OPTION => VALUE, OPTION2 => VALUE2 ... ] )
   * @param mixed $headers
   * If provided, possible headers returned by the request are assigned here.
   * @return mixed
   * Decoded response.
   *
   * If an error occurs, and the returned array includes "errors", the error is
   * automatically logged. However, the results are returned as is. General
   * error result format:
   * {
   *   "description":"Validation failed",
   *   "errors":[
   *     {
   *       "field":"name",
   *       "message":"Mandatory attribute missing",
   *       "code":"missing_field"
   *     }
   *   ]
   * }
   */
  private function requestGet(string $target, array $query_params = [], array $tags = [], &$headers = null, $ignore_errors = false)
  {
    // Remove empty strings from query params.
    $query_params = array_filter($query_params, function ($v, $k) {
      return (!is_string($v) || !empty($v));
    }, ARRAY_FILTER_USE_BOTH);

    // Create query string.
    $query_str = http_build_query($query_params);
    if (!empty($query_str))
      $query_str = '?' . $query_str;

    // Create and execute request.
    $tags[CURLOPT_CUSTOMREQUEST] = 'GET';
    return $this->request($target . $query_str, [], $tags, $headers, $ignore_errors);
  }

  /**
   * Create and execute a cURL request.
   *
   * Shortcut to request() with CURLOPT_CUSTOMREQUEST = 'PUT'.
   *
   * @param string $target
   * URL after / (API function name).
   * @param array $tags
   * Tags ( [ OPTION => VALUE, OPTION2 => VALUE2 ... ] )
   * @param mixed $headers
   * If provided, possible headers returned by the request are assigned here.
   * @return mixed
   * Decoded response.
   *
   * If an error occurs, and the returned array includes "errors", the error is
   * automatically logged. However, the results are returned as is. General
   * error result format:
   * {
   *   "description":"Validation failed",
   *   "errors":[
   *     {
   *       "field":"name",
   *       "message":"Mandatory attribute missing",
   *       "code":"missing_field"
   *     }
   *   ]
   * }
   */
  private function requestPut(string $target, array $post_fields = [], array $tags = [], &$headers = null, $ignore_errors = false)
  {
    $tags[CURLOPT_CUSTOMREQUEST] = 'PUT';
    return $this->request($target, $post_fields, $tags, $headers, $ignore_errors);
  }

  //*------------------------------------------------------------------------------------------------
  //* API Functions
  //*------------------------------------------------------------------------------------------------
  #region Tickets

  /**
   * Call api /tickets (POST) - create ticket.
   *
   * @param array $opts
   * name (string): Name of the requester
   * requester_id (number): User ID of the requester. For existing contacts, the requester_id can be passed instead of the requester's email. (Any of the five attributes is mandatory)
   * email (string): Email address of the requester. If no contact exists with this email address in Freshdesk, it will be added as a new contact. (Any of the five attributes is mandatory)
   * facebook_id (string): Facebook ID of the requester. If no contact exists with this facebook_id, then a new contact will be created. (Any of the five attributes is mandatory)
   * phone (string): Phone number of the requester. If no contact exists with this phone number in Freshdesk, it will be added as a new contact. If the phone number is set and the email address is not, then the name attribute is mandatory. (Any of the five attributes is mandatory)
   * twitter_id (string): Twitter handle of the requester. If no contact exists with this handle in Freshdesk, it will be added as a new contact. (Any of the five attributes is mandatory)
   * unique_external_id (string): External ID of the requester. If no contact exists with this external ID in Freshdesk, they will be added as a new contact. (Any of the five attributes is mandatory)
   * subject (string): Subject of the ticket. The default Value is null.
   * type (string): Helps categorize the ticket according to the different kinds of issues your support team deals with. The default Value is null.
   * status (number): Status of the ticket. The default Value is 2. (Refer Ticket properties table for supported values)
   * priority (number): Priority of the ticket. The default value is 1. (Refer Ticket properties table for supported values)
   * description (string): HTML content of the ticket.
   * responder_id (number): ID of the agent to whom the ticket has been assigned
   * attachments (array of objects): Ticket attachments. The total size of these attachments cannot exceed 15MB.
   * cc_emails (array of strings): Email address added in the 'cc' field of the incoming ticket email
   * custom_fields (dictionary): Key value pairs containing the names and values of custom fields. Read more here
   * due_by (datetime): Timestamp that denotes when the ticket is due to be resolved
   * email_config_id (number): ID of email config which is used for this ticket. (i.e., support@yourcompany.com/sales@yourcompany.com)
   * If (product_id): is given and email_config_id is not given, product's primary email_config_id will be set
   * fr_due_by (datetime): Timestamp that denotes when the first response is due
   * group_id (number): ID of the group to which the ticket has been assigned. The default value is the ID of the group that is associated with the given email_config_id
   * product_id (number): ID of the product to which the ticket is associated.
   * It (will): be ignored if the email_config_id attribute is set in the request.
   * source (number): The channel through which the ticket was created. The default value is 2. (Refer Ticket properties table for supported values)
   * tags (array of strings): Tags that have been associated with the ticket
   * company_id (number): Company ID of the requester. This attribute can only be set if the Multiple Companies feature is enabled (Estate plan and above)
   *
   * Ticket properties:
   * Every ticket uses certain fixed numerical values to denote its Source, Status, and Priorities.
   * These numerical values along with their meanings are given below:
   *   - SOURCE: Email 1, Portal 2, Phone 3, Chat 7, Mobihelp 8, Feedback Widget 9, Outbound Email 10
   *   - PRIORITY: Low 1, Medium 2, High 3, Urgent 4
   *
   * Ticket status:
   *   Open 2,
   *   Pending 3,
   *   Resolved 4,
   *   Closed 5,
   *   Waiting on customer 6,
   *   Waiting for third party 7
   *
   * @return mixed
   * Decoded response. Additional headers include link to created ticket.
   *
   * Body contents if successful:
   * {
   *   "cc_emails" : ["ram@freshdesk.com", "diana@freshdesk.com"],
   *   "fwd_emails" : [ ],
   *   "reply_cc_emails" : ["ram@freshdesk.com", "diana@freshdesk.com"],
   *   "email_config_id" : null,
   *   "group_id" : null,
   *   "priority" : 1,
   *   "requester_id" : 129,
   *   "responder_id" : null,
   *   "source" : 2,
   *   "status" : 2,
   *   "subject" : "Support needed..",
   *   "company_id" : 1,
   *   "id" : 1,
   *   "type" : "Question",
   *   "to_emails" : null,
   *   "product_id" : null,
   *   "fr_escalated" : false,
   *   "spam" : false,
   *   "urgent" : false,
   *   "is_escalated" : false,
   *   "created_at" : "2015-07-09T13:08:06Z",
   *   "updated_at" : "2015-07-23T04:41:12Z",
   *   "due_by" : "2015-07-14T13:08:06Z",
   *   "fr_due_by" : "2015-07-10T13:08:06Z",
   *   "description_text" : "Some details on the issue ...",
   *   "description" : "<div>Some details on the issue ..</div>",
   *   "tags" : [ ],
   *   "attachments" : [ ]
   * }
   *
   * Ticket status:
   *   Open 2,
   *   Pending 3,
   *   Resolved 4,
   *   Closed 5,
   *   Waiting on customer 6,
   *   Waiting for third party 7
   *
   * If an error occurs, and the returned array includes "errors", the error is
   * automatically logged. However, the results are returned as is. General
   * error result format:
   * {
   *   "description":"Validation failed",
   *   "errors":[
   *     {
   *       "field":"name",
   *       "message":"Mandatory attribute missing",
   *       "code":"missing_field"
   *     }
   *   ]
   * }
   */
  public function createTicket(array $opts, &$headers = null)
  {
    // Create and execute request.
    return $this->request('tickets', $opts, [], $headers);
  }

  /**
   * Call API /tickets/[id] (GET) - get ticket details.
   *
   * @param int $id
   * Ticket ID.
   *
   * @param array $additional_details
   * By default, certain fields such as conversations, company name and requester email will not be
   * included in the response. They can be retrieved via the embedding functionality. Options:
   *
   * - conversations:
   *     Will return up to ten conversations sorted by "created_at" in ascending order. Including
   *     conversations will consume two API calls. In order to access more than ten conversations
   *     belonging to a ticket, use the List All Conversations of a Ticket API.
   * - requester:
   *     Will return the requester's email, id, mobile, name, and phone.
   * - company:
   *     Will return the company's id and name.
   * - stats:
   *     Will return the ticket's closed_at, resolved_at and first_responded_at time
   *
   * @return mixed
   * Decoded response.
   *
   * Body contents if successful:
   * {
   *   "cc_emails" : ["user@cc.com"],
   *   "fwd_emails" : [ ],
   *   "reply_cc_emails" : ["user@cc.com"],
   *   "email_config_id" : null,
   *   "fr_escalated" : false,
   *   "group_id" : null,
   *   "priority" : 1,
   *   "requester_id" : 1,
   *   "responder_id" : null,
   *   "source" : 2,
   *   "spam" : false,
   *   "status" : 2,
   *   "subject" : "",
   *   "company_id" : 1,
   *   "id" : 20,
   *   "type" : null,
   *   "to_emails" : null,
   *   "product_id" : null,
   *   "created_at" : "2015-08-24T11:56:51Z",
   *   "updated_at" : "2015-08-24T11:59:05Z",
   *   "due_by" : "2015-08-27T11:30:00Z",
   *   "fr_due_by" : "2015-08-25T11:30:00Z",
   *   "is_escalated" : false,
   *   "association_type" : null,
   *   "description_text" : "Not given.",
   *   "description" : "<div>Not given.</div>",
   *   "custom_fields" : {
   *     "category" : "Primary"
   *   },
   *   "tags" : [ ],
   *   "attachments" : [ ]
   * }
   *
   * Ticket status:
   *   Open 2,
   *   Pending 3,
   *   Resolved 4,
   *   Closed 5,
   *   Waiting on customer 6,
   *   Waiting for third party 7
   *
   * If an error occurs, and the returned array includes "errors", the error is
   * automatically logged. However, the results are returned as is. General
   * error result format:
   * {
   *   "description":"Validation failed",
   *   "errors":[
   *     {
   *       "field":"name",
   *       "message":"Mandatory attribute missing",
   *       "code":"missing_field"
   *     }
   *   ]
   * }
   */
  public function viewTicket(int $id, array $additional_details = [], &$headers = null)
  {
    $query_params = [];

    // Remove invalid values from additional details.
    static $valid_options = ['conversations', 'requester', 'company', 'stats'];
    $additional_details = array_values($additional_details);
    for ($i = 0; $i < count($additional_details); $i++) {
      if (!in_array($additional_details[$i], $valid_options)) {
        static::logError("Invalid option {$additional_details[$i]} for additional details of viewing a ticket");
        unset($additional_details[$i]);
      }
    }

    // Create and execute request.
    return $this->requestGet("tickets/$id", ['include' => implode(',', $additional_details)], [], $headers);
  }

  /**
   * Call API /tickets (GET) - list tickets.
   *
   * Use filters to view only specific tickets (those which match the criteria that you choose). By
   * default, only tickets that have not been deleted or marked as spam will be returned, unless you
   * use the 'deleted' filter.
   *
   * Note:
   * 1. By default, only tickets that have been created within the past 30 days will be returned. For older tickets, use the updated_since filter
   * 2. A maximum of 300 pages (9000 tickets) will be returned.
   * 3. When using filters, the query string must be URL encoded - see example
   * 4. Use 'include' to embed additional details in the response. Each include will consume an additional 2 credits. For example if you embed the stats information you will be charged a total of 3 API credits for the call.
   * 5. For accounts created after 2018-11-30, you will have to use include to get description.
   *
   * Search by company ID is not included as companies are not used (yet).
   *
   * @param string $filter
   * The various filters available are: new_and_my_open, watching, spam, deleted.
   *
   * @param mixed $requester
   * Requester ID (int) or requester email (string). Null to disable.
   *
   * @param int $page
   * Page number if paginating the results. Recommended when searching all data.
   * Disable with -1 (return full result set).
   *
   * @param int $per_page
   * Items per page when $page > 0. Defaults to 10 when paginating.
   *
   * @param string $updated_since
   * Time string from @see Freshdesk::getTimeString().
   *
   * Formats accepted by the API:
   *   YYYY-MM-DD
   *   YYYY-MM-DDTHH:MM
   *   YYYY-MM-DDTHH:MMZ
   *   YYYY-MM-DDTHH:MM:SS
   *   YYYY-MM-DDTHH:MM:SSZ
   *   YYYY-MM-DDTHH:MM:SS±hh:mm
   *   YYYY-MM-DDTHH:MM:SS±hh
   *   YYYY-MM-DDTHH:MM:SS±hhmm
   *
   * @param array $embed
   * stats: Will return the ticket's closed_at, resolved_at and first_responded_at time
   * requester: Will return the requester's email, id, mobile, name, and phone.
   * description: Will return the ticket description and description_text.
   *
   * @param string $order_by
   * Sort by option. Options: created_at, due_by, updated_at, status
   * Default sort order is created_at
   *
   * Ticket status:
   *   Open 2,
   *   Pending 3,
   *   Resolved 4,
   *   Closed 5,
   *   Waiting on customer 6,
   *   Waiting for third party 7
   *
   * @param string $order_type
   * Order of possible specified sort option. Options: asc, desc
   * Default sort order type is desc
   *
   * @return mixed
   * Decoded response.
   *
   * Body contents if successful:
   * [
   *   {
   *     "cc_emails" : ["user@cc.com", "user2@cc.com"],
   *     "fwd_emails" : [ ],
   *     "reply_cc_emails" : ["user@cc.com", "user2@cc.com"],
   *     "fr_escalated" : false,
   *     "spam" : false,
   *     "email_config_id" : null,
   *     "group_id" : 2,
   *     "priority" : 1,
   *     "requester_id" : 5,
   *     "responder_id" : 1,
   *     "source" : 2,
   *     "status" : 2,
   *     "subject" : "Please help",
   *     "to_emails" : null,
   *     "product_id" : null,
   *     "id" : 18,
   *     "type" : Lead,
   *     "created_at" : "2015-08-17T12:02:50Z",
   *     "updated_at" : "2015-08-17T12:02:51Z",
   *     "due_by" : "2015-08-20T11:30:00Z",
   *     "fr_due_by" : "2015-08-18T11:30:00Z",
   *     "is_escalated" : false,
   *     "custom_fields" : {
   *       "category" : "Default"
   *     }
   *   },
   *   ...
   * ]
   *
   * Ticket status:
   *   Open 2,
   *   Pending 3,
   *   Resolved 4,
   *   Closed 5,
   *   Waiting on customer 6,
   *   Waiting for third party 7
   *
   * If an error occurs, and the returned array includes "errors", the error is
   * automatically logged. However, the results are returned as is. General
   * error result format:
   * {
   *   "description":"Validation failed",
   *   "errors":[
   *     {
   *       "field":"name",
   *       "message":"Mandatory attribute missing",
   *       "code":"missing_field"
   *     }
   *   ]
   * }
   */
  public function listTickets(string $filter = null, $requester = null, int $page = -1, int $per_page = -1, string $updated_since = null, array $embed = [], string $order_by = 'created_at', string $order_type = 'desc', &$headers = null)
  {
    $query_params = [];

    // Filter: check for valid filter and add to query parameters.
    if (!empty($filter)) {
      static $valid_filters = ['new_and_my_open', 'watching', 'spam', 'deleted'];
      if (!in_array($filter, $valid_filters)) {
        static::logError("Invalid option {$filter} for filters of listTickets() (/tickets GET).");
        $filter = '';
      }
      $query_params['filter'] = $filter;
    }

    // Requester: if int, set requester_id, or if string, set email.
    if (is_int($requester))
      $query_params['requester_id'] = $requester;
    elseif (is_string($requester))
      $query_params['email'] = $requester;

    // Set pagination.
    if ($page > 0) {
      $query_params['page'] = $page;
      $query_params['per_page'] = $per_page > 0 ? $per_page : 10;
    }

    // Updated since: check that time string is valid and add to query parameters.
    if (!empty($updated_since)) {
      if (!static::validateTimeString($updated_since))
        $this->logLocalError("Invalid date string for listTickets(): $updated_since");
      else
        $query_params['updated_since'] = $updated_since;
    }

    // Embed: remove invalid values and add to query parameters.
    if (!empty($embed)) {
      static $valid_embed_options = ['stats', 'requester', 'description'];
      $embed = array_values($embed);
      for ($i = 0; $i < count($embed); $i++) {
        if (!in_array($embed[$i], $valid_embed_options)) {
          $this->logLocalError('Invalid embed option for listTickets(): ' . $embed[$i]);
          unset($embed[$i]);
        }
      }
      $query_params['include'] = implode(',', $embed);
    }

    // Order by: check that value is valid and add to query parameters.
    if (!empty($order_by)) {
      static $valid_order_by_options = ['created_at', 'due_by', 'updated_at', 'status'];
      if (!in_array($order_by, $valid_order_by_options))
        $this->logLocalError("Invalid order by option for listTickets(): $order_by");
      else
        $query_params['order_by'] = $order_by;
    }

    // Order type: check that value is valid and add to query parameters.
    if (!empty($order_type)) {
      static $valid_order_types = ['asc', 'desc'];
      if (!in_array($order_type, $valid_order_types))
        $this->logLocalError("Invalid order type for listTickets(): $order_type");
      else
        $query_params['order_type'] = $order_type;
    }

    // Create and execute request.
    return $this->requestGet('tickets', $query_params, [], $headers);
  }

  /**
   * Call API /search/tickets?query=[query] (GET) - filter (search) tickets.
   *
   * Use custom ticket fields that you have created in your account to filter through the tickets
   * and get a list of tickets matching the specified ticket fields.
   *
   * Format - "(ticket_field:integer OR ticket_field:'string') AND ticket_field:boolean"
   *
   * Note:
   * 1. Archived tickets will not be included in the results
   * 2. The query must be URL encoded
   * 3. Query can be framed using the name of the ticket fields, which can be obtained from Ticket Fields endpoint. Ticket Fields are case sensitive
   * 4. Query string must be enclosed between a pair of double quotes and can have up to 512 characters
   * 5. Logical operators AND, OR along with parentheses () can be used to group conditions
   * 6. Relational operators greater than or equal to :> and less than or equal to :< can be used along with date fields and numeric fields
   * 7. Input for date fields should be in UTC Format
   * 8. The number of objects returned per page is 30 also the total count of the results will be returned along with the result
   * 9. To scroll through the pages add page parameter to the url. The page number starts with 1 and should not exceed 10
   * 10. To filter for fields with no values assigned, use the null keyword
   * 11. Please note that the updates will take a few minutes to get indexed, after which it will be available through API
   *
   * Supported Ticket Fields
   * - agent_id (integer): ID of the agent to whom the ticket has been assigned
   * - group_id (integer): ID of the group to which the ticket has been assigned
   * - priority (integer): Priority of the ticket
   * - status (integer): Status of the ticket
   * - tag (string): Tag that has been associated to the tickets
   * - type (string): Type of issue that has been associated to the tickets
   * - due_by (date): Date (YYYY-MM-DD) when the ticket is due to be resolved
   * - fr_due_by (date): Date (YYYY-MM-DD) when the first response is due
   * - created_at (date): Ticket creation date (YYYY-MM-DD)
   * - updated_at (date): Date (YYYY-MM-DD) when the ticket was last updated
   *
   * Ticket status:
   *   Open 2,
   *   Pending 3,
   *   Resolved 4,
   *   Closed 5,
   *   Waiting on customer 6,
   *   Waiting for third party 7
   *
   * Custom Fields
   * - Single line text (string)
   * - Number (integer)
   * - Checkbox (boolean)
   * - Dropdown (string)
   *
   * @link https://developers.freshdesk.com/api/?_ga=2.172412835.1040340892.1587407475-922287059.1587407475#filter_tickets
   *
   * @param string $query
   * Query string. Provide query string without encoding, e.g.:
   *   "priority:4 OR priority:3"
   * ..instead of..
   *   "priority:4%20OR%20priority:3"
   *
   * @return mixed
   * Decoded response.
   *
   * Body contents if successful:
   * {
   *   "total":49,
   *   "results":[
   *     {
   *       "cc_emails":["clark.kent@kryptonspace.com"],
   *       "fwd_emails":[ ],
   *       "reply_cc_emails":[ ],
   *       "fr_escalated":false,
   *       "spam":false,
   *       "email_config_id":17,
   *       "group_id":156,
   *       "priority":3,
   *       "requester_id":6007738334,
   *       "responder_id":6001263404,
   *       "source":2,
   *       "company_id":2,
   *       "status":2,
   *       "subject":"Sample Title",
   *       "to_emails":null,
   *       "product_id":null,
   *       "id":47,
   *       "type":null,
   *       "due_by":"2016-02-23T16:00:00Z",
   *       "fr_due_by":"2016-02-22T17:00:00Z",
   *       "is_escalated":true,
   *       "description":"<div>Sample description</div>",
   *       "description_text":"Sample description",
   *       "created_at":"2016-02-20T09:16:58Z",
   *       "updated_at":"2016-02-23T16:14:57Z",
   *       "custom_fields":{
   *         "sector_no":7,
   *         "locked":true
   *       }
   *     },
   *     ...
   *   ]
   * }
   *
   * Ticket status:
   *   Open 2,
   *   Pending 3,
   *   Resolved 4,
   *   Closed 5,
   *   Waiting on customer 6,
   *   Waiting for third party 7
   *
   * If an error occurs, and the returned array includes "errors", the error is
   * automatically logged. However, the results are returned as is. General
   * error result format:
   * {
   *   "description":"Validation failed",
   *   "errors":[
   *     {
   *       "field":"name",
   *       "message":"Mandatory attribute missing",
   *       "code":"missing_field"
   *     }
   *   ]
   * }
   */
  public function filterTickets(string $query = null, &$headers = null)
  {
    if (empty($query))
      $this->logLocalError('Empty query string in filterTickets().');

    // Create and execute cURL request. If query is empty, execute anyway, so
    // that the resulting API error is returned.
    return $this->requestGet('search/tickets', ['query' => $query], [], $headers);
  }

  /**
   * Call api /tickets/[id] (PUT) - update a ticket.
   *
   * Note: Unlike normal tickets, the subject and description of outbound
   * tickets cannot be updated. More information on the difference between
   * normal and outbound tickets can be found here:
   *
   * @link https://support.freshdesk.com/support/solutions/articles/211975-the-differences-between-an-outbound-ticket-and-a-normal-ticket
   *
   * @param int $id
   * Ticket ID to update.
   *
   * @param array $opts
   * name (string): Name of the requester
   * requester_id (number): User ID of the requester. For existing contacts, the requester_id can be passed instead of the requester's email.
   * email (string): Email address of the requester. If no contact exists with this email address in Freshdesk, it will be added as a new contact.
   * facebook_id (string): Facebook ID of the requester. A contact should exist with this facebook_id in Freshdesk.
   * phone (string): Phone number of the requester. If no contact exists with this phone number in Freshdesk, it will be added as a new contact. If the phone number is set and the email address is not, then the name attribute is mandatory.
   * twitter_id (string): Twitter handle of the requester. If no contact exists with this handle in Freshdesk, it will be added as a new contact.
   * unique_external_id (string): External ID of the requester. If no contact exists with this external ID in Freshdesk, they will be added as a new contact.
   * subject (string): Subject of the ticket. The default Value is null.
   * type (string): Helps categorize the ticket according to the different kinds of issues your support team deals with. The default Value is null.
   * status (number): Status of the ticket. The default Value is 2. (Refer Ticket properties table for supported values)
   * priority (number): Priority of the ticket. The default value is 1. (Refer Ticket properties table for supported values)
   * description (string): HTML content of the ticket.
   * responder_id (number): ID of the agent to whom the ticket has been assigned
   * attachments (array): of objects	Ticket attachments. The total size of these attachments cannot exceed 15MB.
   * custom_fields (dictionary): Key value pairs containing the names and values of custom fields. Read more here
   * due_by (datetime): Timestamp that denotes when the ticket is due to be resolved
   * email_config_id (number): ID of email config which is used for this ticket. (i.e., support@yourcompany.com/sales@yourcompany.com)
   * If (the): product_id is changed and the current email_config_id doesn't belong to that product, then this value will be automatically updated to the selected product's primary email_config_id
   * fr_due_by (datetime): Timestamp that denotes when the first response is due
   * group_id (number): ID of the group to which the ticket has been assigned. The default value is the ID of the group that is associated with the given email_config_id
   * product_id (number): ID of the product to which the ticket is associated.
   * source (number): The channel through which the ticket was created. The default value is 2. (Refer Ticket properties table for supported values)
   * tags (array of strings):	Tags that have been associated with the ticket
   * company_id (number): Company ID of the requester. This attribute can only be updated if the Multiple Companies feature is enabled (Estate plan and above)
   *
   * Ticket properties:
   * Every ticket uses certain fixed numerical values to denote its Source, Status, and Priorities.
   * These numerical values along with their meanings are given below.
   *   - SOURCE: Email 1, Portal 2, Phone 3, Chat 7, Mobihelp 8, Feedback Widget 9, Outbound Email 10
   *   - PRIORITY: Low 1, Medium 2, High 3, Urgent 4
   *
   * Ticket status:
   *   Open 2,
   *   Pending 3,
   *   Resolved 4,
   *   Closed 5,
   *   Waiting on customer 6,
   *   Waiting for third party 7
   *
   * @return mixed
   * Decoded response. Additional headers are requested, as the headers include
   * link to created ticket, so if successful, return value is an array with
   * first item being the headers and second item the return body.
   *
   * Body contents if successful:
   * {
   *   "cc_emails" : [ ],
   *   "fwd_emails" : [ ],
   *   "reply_cc_emails" : [ ],
   *   "description_text" : "Not given.",
   *   "description" : "<div>Not given.</div>",
   *   "spam" : false,
   *   "email_config_id" : null,
   *   "fr_escalated" : false,
   *   "group_id" : null,
   *   "priority" : 2,
   *   "requester_id" : 1,
   *   "responder_id" : null,
   *   "source" : 3,
   *   "status" : 3,
   *   "subject" : "",
   *   "id" : 20,
   *   "type" : null,
   *   "to_emails" : null,
   *   "product_id" : null,
   *   "attachments" : [ ],
   *   "is_escalated" : false,
   *   "tags" : [ ],
   *   "created_at" : "2015-08-24T11:56:51Z",
   *   "updated_at" : "2015-08-24T11:59:05Z",
   *   "due_by" : "2015-08-27T11:30:00Z",
   *   "fr_due_by" : "2015-08-25T11:30:00Z"
   * }
   *
   * Ticket status:
   *   Open 2,
   *   Pending 3,
   *   Resolved 4,
   *   Closed 5,
   *   Waiting on customer 6,
   *   Waiting for third party 7
   *
   * If an error occurs, and the returned array includes "errors", the error is
   * automatically logged. However, the results are returned as is. General
   * error result format:
   * {
   *   "description":"Validation failed",
   *   "errors":[
   *     {
   *       "field":"name",
   *       "message":"Mandatory attribute missing",
   *       "code":"missing_field"
   *     }
   *   ]
   * }
   */
  public function updateTicket(int $id, array $opts, &$headers = null)
  {
    if ($id <= 0) {
      $this->logLocalError("Zero or negative ID in deleteTicket(): $id");
      return null;
    } else {
      return $this->requestPut("tickets/$id", $opts, [], $headers);
    }
  }

  /**
   * Call API /tickets/[id] (DELETE) - deletes a ticket by ID.
   *
   * Note: Rest assured. When deleted, tickets are not cast into the fiery
   * volcanoes of Mount Doom. You can retrieve them using the Restore Ticket API.
   *
   * response: HTTP Status: 204 No Content
   *
   * @param int $id
   * Ticket ID to delete.
   *
   * @return mixed
   * Decoded response.
   *
   * Body contents if successful:
   * HTTP Status: 204 No Content
   *
   * If an error occurs, and the returned array includes "errors", the error is
   * automatically logged. However, the results are returned as is. General
   * error result format:
   * {
   *   "description":"Validation failed",
   *   "errors":[
   *     {
   *       "field":"name",
   *       "message":"Mandatory attribute missing",
   *       "code":"missing_field"
   *     }
   *   ]
   * }
   */
  public function deleteTicket(int $id, &$headers = null)
  {
    if ($id <= 0) {
      $this->logLocalError("Zero or negative ID in deleteTicket(): $id");
      return null;
    } else {
      return $this->request("tickets/$id", [], [CURLOPT_CUSTOMREQUEST => 'delete'], $headers);
    }
  }

  /**
   * Get arrays of ticket IDs indexed by local customer ID (not freshdesk ID).
   *
   * This function gets a list of tickets, and for each ticket, finds a local
   * customer with a freshdesk_id value that matches the customer ID value on
   * the ticket. If the customer ID on the ticket is not specified on any local
   * customer's freshdesk_id field, that ticket is not included at all.
   *
   * All this results in an associative array of customer IDs, with each index
   * containing an array of ticket IDs. This can be used to find the amount of
   * open tickets for a customer, for example.
   *
   * This function uses the {@see CachePaginator} object created by
   * {@see getTicketPaginator()} to obtain the list of tickets.
   *
   * @param array $status_ignore
   * Ticket statuses to ignore:
   *   Open 2,
   *   Pending 3,
   *   Resolved 4,
   *   Closed 5,
   *   Waiting on customer 6,
   *   Waiting for third party 7
   *
   * @return array
   * Array indexed by local customer IDs, with each index containing an array of
   * customer IDs. If a customer doesn't have any matching tickets, they are not
   * included in this array, so there are no empty arrays in the resulting set.
   */
  public function ticketsByCustomerId($status_ignore = [4, 5])
  {
    if ($this->isDisabled()) {
      return [];
    }

    $freshdesk_pager = $this->getTicketPaginator(10);
    $criteria = new CDbCriteria();
    $criteria->select = 'id, freshdesk_id';
    $criteria->condition = 'freshdesk_id != 0';

    // get freshdesk id for each customer
    $freshdesk_ids = [];
    foreach (Asiakkaat::model()->findAll($criteria) as $result) {
      if (!empty($result->freshdesk_id))
        $freshdesk_ids[$result->id] = $result->freshdesk_id;
    }

    // get tickets associated with any customer with defined freshdesk id
    $tickets = $freshdesk_pager->filtered(1, function ($item) use ($freshdesk_ids) {
      return (in_array($item['requester_id'] ?? 0, $freshdesk_ids));
    }, 100);

    // map results to list of customer ids that have open tickets
    $customer_tickets = [];
    foreach ($tickets as $ticket) {
      if (in_array($ticket['status'] ?? 0, $status_ignore))
        continue;
      if ($cid = array_search($ticket['requester_id'], $freshdesk_ids))
        $customer_tickets[$cid][] = $ticket['id'];
    }

    // previous method (repeated requests)
    // Filter cached tickets to find which customers have open tickets
    // foreach (Asiakkaat::model()->findAll($criteria) as $result) {
    //   if (!empty($freshdesk_pager->filtered(1, function ($item) use ($result) {
    //     return ($item['requester_id'] ?? 0) == ($result->freshdesk_id ?? -1);
    //   }, 50, null, 1))) { // limit 1
    //     $customer_tickets[] = $result['id'];
    //   }
    // }
    // echo "<pre>" . print_r($customer_tickets, true) . "</pre>";exit;

    return $customer_tickets;
  }

  #endregion
  #region Conversations

  /**
   * Call API tickets/[id]/conversations (GET) - List ticket conversations.
   *
   * @param int $id
   * Ticket ID to list conversations for.
   *
   * @param int $page
   * If the ticket's conversation has more than 30 entries, only 30 are returned
   * per page. Defaults to page 1. Use 2 to return entries from 31 to 60.
   *
   * @return mixed
   * Decoded response.
   *
   * Body contents if successful:
   * [
   *   {
   *     "body_text" : "Please reply as soon as possible.",
   *     "body" : "<div>Please reply as soon as possible.</div>",
   *     "id" : 3,
   *     "incoming" : false,
   *     "private" : true,
   *     "user_id" : 1,
   *     "support_email" : null,
   *     "source" : 2,
   *     "ticket_id" : 20,
   *     "created_at" : "2015-08-24T11:59:05Z",
   *     "updated_at" : "2015-08-24T11:59:05Z",
   *     "from_email" : "agent2@yourcompany.com",
   *     "to_emails" : ["agent1@yourcompany.com"],
   *     "cc_emails": ["example@ccemail.com"],
   *     "bcc_emails": ["example@bccemail.com"],
   *     "attachments" : [ ]
   *   },
   *   ...
   * ]
   *
   * If an error occurs, and the returned array includes "errors", the error is
   * automatically logged. However, the results are returned as is. General
   * error result format:
   * {
   *   "description":"Validation failed",
   *   "errors":[
   *     {
   *       "field":"name",
   *       "message":"Mandatory attribute missing",
   *       "code":"missing_field"
   *     }
   *   ]
   * }
   */
  public function listTicketConversations(int $id, int $page = 1, &$headers = null)
  {
    if ($id <= 0) {
      $this->logLocalError("Zero or negative ID in listTicketConversations(): $id");
      return null;
    } else {
      return $this->requestGet("tickets/$id/conversations", $page > 1 ? ['page' => $page] : [], [], $headers);
    }
  }

  /**
   * Call API /tickets/[id]/reply (POST) - Create a reply.
   *
   * @param int $id
   * Ticket ID to create a reply to.
   *
   * @param array $opts
   * body (string) Content of the note in HTML format (mandatory)
   * attachments (array of attachment objects): Attachments. The total size of all the ticket's attachments (not just this note) cannot exceed 15MB.
   * from_email (string): The email address from which the reply is sent. By default the global support email will be used.
   * user_id (number): ID of the agent who is adding the note
   * cc_emails (array of strings): Email address added in the 'cc' field of the outgoing ticket email.
   * bcc_emails (array of strings): Email address added in the 'bcc' field of the outgoing ticket email.
   *
   * @return mixed
   * Decoded response. Additional headers are requested, as the headers include
   * link to created ticket, so if successful, return value is an array with
   * first item being the headers and second item the return body.
   *
   * Body contents if successful:
   * {
   *   "cc_emails" : ["ram@freshdesk.com", "diana@freshdesk.com"],
   *   "fwd_emails" : [ ],
   *   "reply_cc_emails" : ["ram@freshdesk.com", "diana@freshdesk.com"],
   *   "email_config_id" : null,
   *   "group_id" : null,
   *   "priority" : 1,
   *   "requester_id" : 129,
   *   "responder_id" : null,
   *   "source" : 2,
   *   "status" : 2,
   *   "subject" : "Support needed..",
   *   "company_id" : 1,
   *   "id" : 1,
   *   "type" : "Question",
   *   "to_emails" : null,
   *   "product_id" : null,
   *   "fr_escalated" : false,
   *   "spam" : false,
   *   "urgent" : false,
   *   "is_escalated" : false,
   *   "created_at" : "2015-07-09T13:08:06Z",
   *   "updated_at" : "2015-07-23T04:41:12Z",
   *   "due_by" : "2015-07-14T13:08:06Z",
   *   "fr_due_by" : "2015-07-10T13:08:06Z",
   *   "description_text" : "Some details on the issue ...",
   *   "description" : "<div>Some details on the issue ..</div>",
   *   "tags" : [ ],
   *   "attachments" : [ ]
   * }
   *
   * If an error occurs, and the returned array includes "errors", the error is
   * automatically logged. However, the results are returned as is. General
   * error result format:
   * {
   *   "description":"Validation failed",
   *   "errors":[
   *     {
   *       "field":"name",
   *       "message":"Mandatory attribute missing",
   *       "code":"missing_field"
   *     }
   *   ]
   * }
   */
  public function createReply(int $id, array $opts = [], &$headers = null)
  {
    if ($id <= 0) {
      $this->logLocalError("Zero or negative ID in viewContact(): $id");
      return null;
    } else {
      return $this->request("tickets/$id/reply", $opts, [], $headers);
    }
  }

  #endregion
  #region Contacts

  /**
   * Call API /contacts (POST) - Create a contact.
   *
   * @param array $opts
   * name (mandatory) (string) Name of the contact
   * email * (unique) (string) Primary email address of the contact. If you want to associate additional email(s) with this contact, use the other_emails attribute.
   * phone * (string) Telephone number of the contact
   * mobile * (number) Mobile number of the contact
   * twitter_id * (unique) (string) Twitter handle of the contact
   * unique_external_id * (unique) (string) External ID of the contact
   * other_emails (array of strings) Additional emails associated with the contact
   * company_id (number): ID of the primary company to which this contact belongs
   * view_all_tickets (boolean): Set to true if the contact can see all the tickets that are associated with the company to which he belong
   * other_companies (array of hashes): Additional companies associated with the contact. This attribute can only be set if the Multiple Companies feature is enabled (Estate plan and above)
   * address (string): Address of the contact.
   * avatar (object): Avatar image of the contact The maximum file size is 5MB and the supported file types are .jpg, .jpeg, .jpe, and .png
   * custom_fields (dictionary): Key value pairs containing the name and value of the custom field. Only dates in the format YYYY-MM-DD are accepted as input for custom date fields. Read more here
   * description (string): A small description of the contact
   * job_title (string): Job title of the contact
   * language (string): Language of the contact. Default language is "en". This attribute can only be set if the Multiple Language feature is enabled (Garden plan and above)
   * tags (array of strings): Tags associated with this contact
   * time_zone (string): Time zone of the contact. Default value is the time zone of the domain. This attribute can only be set if the Multiple Time Zone feature is enabled (Garden plan and above)
   *
   * * One of these five attributes is mandatory
   *
   * @return mixed
   * Decoded response.
   *
   * Body contents if successful:
   * {
   *   "active": false,
   *   "address": null,
   *   "company_id":23,
   *   "view_all_tickets":false,
   *   "deleted": false,
   *   "description": null,
   *   "email": "superman@freshdesk.com",
   *   "id": 432,
   *   "job_title": null,
   *   "language": "en",
   *   "mobile": null,
   *   "name": "Super Man",
   *   "phone": null,
   *   "time_zone": "Chennai",
   *   "twitter_id": null,
   *   "other_emails":["lex@freshdesk.com","louis@freshdesk.com"],
   *   "other_companies":[
   *     { "company_id":25, "view_all_tickets":true },
   *     { "company_id":26, "view_all_tickets":false }
   *   ],
   *   "created_at": "2015-08-28T09:08:16Z",
   *   "updated_at": "2015-08-28T09:08:16Z",
   *   "tags": [ ],
   *   "avatar": null
   * }
   *
   * If an error occurs, and the returned array includes "errors", the error is
   * automatically logged. However, the results are returned as is. General
   * error result format:
   * {
   *   "description":"Validation failed",
   *   "errors":[
   *     {
   *       "field":"name",
   *       "message":"Mandatory attribute missing",
   *       "code":"missing_field"
   *     }
   *   ]
   * }
   */
  public function createContact(array $opts, &$headers = null, $ignore_errors = false)
  {
    return $this->requestPost('contacts', $opts, [], $headers, $ignore_errors);
  }

  /**
   * Call API /contacts/[id] (GET) - View contact.
   *
   * @param int $id
   * Contact ID to view.
   *
   * @return mixed
   * Decoded response.
   *
   * Body contents if successful:
   * {
   *   "active": false,
   *   "address": null,
   *   "company_id":23,
   *   "view_all_tickets":false,
   *   "description": null,
   *   "email": "greenlantern@freshdesk.com",
   *   "id": 434,
   *   "job_title": null,
   *   "language": "en",
   *   "mobile": null,
   *   "name": "Green Lantern",
   *   "phone": null,
   *   "time_zone": "Chennai",
   *   "twitter_id": null,
   *   "other_emails": [],
   *   "other_companies":[
   *     { "company_id":25, "view_all_tickets":true },
   *     { "company_id":26, "view_all_tickets":false }
   *   ],
   *   "created_at": "2015-08-28T10:27:58Z",
   *   "updated_at": "2015-08-28T10:27:58Z",
   *   "custom_fields": {
   *     "department": "Operations"
   *     "fb_profile": null,
   *     "permanent": false
   *   },
   *   "tags": [],
   *   "avatar": {
   *     "avatar_url": "<AVATAR_URL>",
   *     "content_type": "application/octet-stream",
   *     "id": 4,
   *     "name": "rails.png",
   *     "size": 13036,
   *     "created_at": "2015-08-28T10:27:58Z",
   *     "updated_at": "2015-08-28T10:27:58Z"
   *   }
   * }
   *
   * If an error occurs, and the returned array includes "errors", the error is
   * automatically logged. However, the results are returned as is. General
   * error result format:
   * {
   *   "description":"Validation failed",
   *   "errors":[
   *     {
   *       "field":"name",
   *       "message":"Mandatory attribute missing",
   *       "code":"missing_field"
   *     }
   *   ]
   * }
   */
  public function viewContact(int $id, &$headers = null)
  {
    if ($id <= 0) {
      $this->logLocalError("Zero or negative ID in viewContact(): $id");
      return null;
    } else {
      return $this->requestGet("contacts/$id", [], [], $headers);
    }
  }

  /**
   * Call API /contacts (GET) - List contacts.
   *
   * Use filters to view only specific contacts (those which match the criteria
   * that you choose). The filters listed in the table below can also be combined.
   *
   * Note:
   * 1. When using filters, the query string must be URL encoded.
   *    => The request function encodes the query string automatically.
   * 2. All unblocked and undeleted contacts will be returned by default.
   *
   * @param int $page
   * Page number.
   *
   * @param int $per_page
   * Items per page (max 100).
   *
   * @param array $filter_by
   * Key & value pairs. Key options: email, mobile, phone. Example:
   *   [ 'mobile' => 7654367287 ]
   *
   * @param string $state
   * State of the contact. Options: blocked, deleted, unverified, verified
   *
   * @param string $updated_since
   * Time string to list contacts that have been updated since specific date.
   *
   * @return mixed
   * Decoded response.
   *
   * Body contents if successful:
   * [
   *   {
   *     "active":false,
   *     "address":null,
   *     "company_id":null,
   *     "description":null,
   *     "email":"rachel@freshdesk.com",
   *     "id":2,
   *     "job_title":null,
   *     "language":"en",
   *     "mobile":null,
   *     "name":"Rachel",
   *     "phone":null,
   *     "time_zone":"Chennai",
   *     "twitter_id":null,
   *     "created_at":"2015-08-18T16:18:14Z",
   *     "updated_at":"2015-08-24T09:25:19Z",
   *     "custom_fields":{
   *       "department": "Admin"
   *       "fb_profile": null,
   *       "permanent": true
   *     }
   *   },
   *   ...
   * ]
   *
   * If an error occurs, and the returned array includes "errors", the error is
   * automatically logged. However, the results are returned as is. General
   * error result format:
   * {
   *   "description":"Validation failed",
   *   "errors":[
   *     {
   *       "field":"name",
   *       "message":"Mandatory attribute missing",
   *       "code":"missing_field"
   *     }
   *   ]
   * }
   */
  public function listContacts(int $page = 1, int $per_page = 30, array $filter_by = [], string $state = null, string $updated_since = null, &$headers = null)
  {
    $query_params = [
      'page' => max(1, $page),
      'per_page' => min(100, max(5, $per_page))
    ];

    // Validate provided filter by values and add to query parameters.
    foreach ($filter_by as $key => $value) {
      if ($key == 'email') {
        $query_params['email'] = $value;
      } elseif (in_array($key, ['mobile', 'phone'])) {
        if (!is_numeric($value))
          $this->logLocalError("Invalid number for filter by $key in listContacts: $value");
        else
          $query_params[$key] = $value;
      }
    }

    // Check that state is valid and add to query parameters.
    if (!empty($state)) {
      static $valid_states = ['blocked', 'deleted', 'unverified', 'verified'];
      if (!in_array($state, $valid_states))
        $this->logLocalError("Invalid state in listContacts: $state");
      else
        $query_params['state'] = $state;
    }

    // Validate updated since date string and add to query parameters.
    if (!empty($updated_since)) {
      if (!static::validateTimeString($updated_since))
        $this->logLocalError("Invalid updated since date string in listContacts: $updated_since");
      else
        $query_params['updated_since'] = $updated_since;
    }

    return $this->requestGet('contacts', $query_params, [], $headers);
  }

  /**
   * Call API /contacts/[id] (PUT) - Update a contact by ID.
   *
   * @param array $opts
   * name (string): Name of the contact
   * email (unique) (string): Primary email address of the contact. If you want to associate additional email(s) with this contact, use the other_emails attribute.
   * phone (string): Telephone number of the contact
   * mobile (number): Mobile number of the contact
   * twitter_id (unique) (string): Twitter handle of the contact
   * unique_external_id (unique) (string): External ID of the contact
   * other_emails (array of strings): Additional emails associated with the contact
   * company_id (number): ID of the primary company to which this contact belongs
   * view_all_tickets (boolean): Set to true if the contact can see all the tickets that are associated with the company to which he belong
   * other_companies (array of hashes): Additional companies associated with the contact. This attribute can only be updated if the Multiple Companies feature is enabled (Estate plan and above)
   * address (string): Address of the contact.
   * avatar (object): Avatar image of the contact The maximum file size is 5MB and the supported file types are .jpg, .jpeg, .jpe, and .png
   * custom_fields (dictionary): Key value pairs containing the name and value of the custom field. Only dates in the format YYYY-MM-DD are accepted as input for custom date fields. Read more here
   * description (string): A small description of the contact
   * job_title (string): Job title of the contact
   * language (string): Language of the contact. Default language is "en". This attribute can only be updated if the Multiple Language feature is enabled (Garden plan and above)
   * tags (array of strings): Tags associated with this contact
   * time_zone (string): Time zone of the contact. Default value is the time zone of the domain. This attribute can only be updated if the Multiple Time Zone feature is enabled (Garden plan and above)
   *
   * @return mixed
   * Decoded response.
   *
   * Body contents if successful:
   * {
   *   "active":false,
   *   "address":null,
   *   "company_id":23,
   *   "view_all_tickets":false,
   *   "deleted":false,
   *   "description":null,
   *   "email":"superman@freshdesk.com",
   *   "id":432,
   *   "job_title":"Journalist",
   *   "language":"en",
   *   "mobile":null,
   *   "name":"Clark Kent",
   *   "phone":null,
   *   "time_zone":"Chennai",
   *   "twitter_id":null,
   *   "other_emails":["louis@freshdesk.com","jonathan.kent@freshdesk.com"],
   *   "other_companies":[
   *     { "company_id":25, "view_all_tickets":true },
   *     { "company_id":26, "view_all_tickets":false }
   *   ],
   *   "created_at":"2015-08-28T09:08:16Z",
   *   "updated_at":"2015-08-28T11:37:05Z",
   *   "tags":[],
   *   "avatar":null
   * }
   *
   * If an error occurs, and the returned array includes "errors", the error is
   * automatically logged. However, the results are returned as is. General
   * error result format:
   * {
   *   "description":"Validation failed",
   *   "errors":[
   *     {
   *       "field":"name",
   *       "message":"Mandatory attribute missing",
   *       "code":"missing_field"
   *     }
   *   ]
   * }
   */
  public function updateContact(int $id, array $opts, &$headers = null)
  {
    if ($id <= 0) {
      $this->logLocalError("Zero or negative ID in updateContact(): $id");
      return null;
    } else {
      return $this->requestPut("contacts/$id", $opts, [], $headers);
    }
  }

  #endregion

  //*------------------------------------------------------------------------------------------------
  //* Log
  //*------------------------------------------------------------------------------------------------
  #region Log

  /**
   * Log a regular non-error message without stacktrace.
   *
   * This non-static version of the logging function includes local data in
   * $params automatically (e.g. base API URL).
   *
   * @param string $format
   * Format for sprintf.
   * @param array $args
   * Possible args for sprintf.
   */
  public function logLocal(string $format = null, ...$args)
  {
    $args[] = $this->getLocals(true)['locals'] ?? '';
    array_unshift($args, $format . ' (locals: %s)');
    return call_user_func_array(['Freshdesk', 'log'], $args);
  }

  /**
   * Log error with the Freshdesk component, whether with an API response, or
   * with how the component is used. If a request returns an error, the
   * logLocalRequestError function should generally be used.
   *
   * This non-static version of the logging function includes local data in
   * $params automatically (e.g. base API URL).
   *
   * Depending on server configuration, this may send error email to admin.
   *
   * @param string $message
   * Main message to be logged.
   * @param array $params
   * Additional parameters, like ['uid' => 123]. If not empty, the whole array
   * is appended to the log message in JSON encoded format.
   * @param bool $stacktrace
   * If true, automatic stacktrace from built-in \Exception is added to the end.
   */
  public function logLocalError(string $message, array $params = [], bool $stacktrace = true)
  {
    return static::logError($message, $this->getLocals(true) + $params, $stacktrace);
  }

  /**
   * Log error in an API request, usually when 'errors' is defined in results.
   *
   * This non-static version of the logging function includes local data in
   * $params automatically (e.g. base API URL).
   *
   * Depending on server configuration, this may send error email to admin.
   *
   * @param string $request
   * Requested API call.
   * @param array $response
   * Results array returned by the API function.
   * @param array $request_params
   * Parameters used in the request.
   * @param array $other_params
   * Additional parameters, like ['uid' => 123]. If not empty, the whole array
   * is appended to the log message in JSON encoded format.
   * @param bool $stacktrace
   * If true, automatic stacktrace from built-in \Exception is added to the end.
   */
  public function logLocalRequestError(string $request, array $response, array $request_params = [], array $other_params = [], bool $stacktrace = true)
  {
    return static::logRequestError($request, $response, $request_params, $this->getLocals(true) + $other_params, $stacktrace);
  }

  /**
   * Log a regular non-error message without stacktrace.
   *
   * @param string $format
   * Format for sprintf.
   * @param array $args
   * Possible args for sprintf.
   */
  public static function log(string $format = null, ...$args)
  {
    if (!empty($format)) {
      $format = sprintf('%s (from uid %d@%s)', $format, Yii::app()->user->getId(), Yii::app()->user->domain);
      array_unshift($args, $format);
      $message = call_user_func_array('sprintf', $args);
      Yii::getLogger()->log($message, 'info', 'freshdesk');
    }
  }

  /**
   * Log error with the Freshdesk component, whether with an API response, or
   * with how the component is used. If a request returns an error, the
   * logLocalRequestError function should generally be used.
   *
   * Depending on server configuration, this may send error email to admin.
   *
   * @param string $message
   * Main message to be logged.
   * @param array $params
   * Additional parameters, like ['uid' => 123]. If not empty, the whole array
   * is appended to the log message in JSON encoded format.
   * @param bool $stacktrace
   * If true, automatic stacktrace from built-in \Exception is added to the end.
   */
  public static function logError(string $message, array $params = [], bool $stacktrace = true)
  {
    if (empty($message)) {
      $message = 'Freshdesk error: No message provided.';
      $stacktrace = true;
    } else {
      $message = "Freshdesk error: $message";
    }

    if (!empty($params)) {
      $message .= "\nParameters: " . json_encode($params);
    }

    if ($stacktrace) {
      $message .= "\nStacktrace: " . (new \Exception())->getTraceAsString();
    }

    $message = sprintf('(user %s@%s): %s', Yii::app()->user->getName(), Yii::app()->user->domain, $message);
    Yii::getLogger()->log($message, 'error', 'freshdesk');
  }

  /**
   * Log error in an API request, usually when 'errors' is defined in results.
   *
   * Depending on server configuration, this may send error email to admin.
   *
   * @param string $request
   * Requested API call.
   * @param array $response
   * Results array returned by the API function.
   * @param array $request_params
   * Parameters used in the request.
   * @param array $other_params
   * Additional parameters, like ['uid' => 123]. If not empty, the whole array
   * is appended to the log message in JSON encoded format.
   * @param bool $stacktrace
   * If true, automatic stacktrace from built-in \Exception is added to the end.
   */
  public static function logRequestError(string $request, array $response, array $request_params = [], array $other_params = [], bool $stacktrace = true)
  {
    if (empty($request)) {
      $message = 'Error in unspecified API request.';
      $stacktrace = true;
    } else {
      $message = "Error in API request $request.";
    }

    if (!empty($response)) {
      $message .= "\nResponse: " . json_encode($response);
    }

    if (!empty($request_params)) {
      $message .= "\nRequest parameters: " . json_encode($request_params);
    }

    if (!empty($other_params)) {
      $message .= "\nOther parameters: " . json_encode($other_params);
    }

    return static::logError($message, [], $stacktrace);
  }

  #endregion

  //*------------------------------------------------------------------------------------------------
  //* Other Functions
  //*------------------------------------------------------------------------------------------------
  #region Other Functions

  /**
   * Get local variables, generally for log parameters. Note that the non-static
   * logging methods include this information by default.
   *
   * @param bool $json
   * If true, locals are encoded to JSON, and return value is a single-item
   * associative array like: ['locals' => '<json>'].
   *
   * @return array
   * Essential information from local attributes. If $json = true, the returned
   * array contains a single item 'locals' with JSON data. Data includes:
   * {
   *   "environment" : str("testing"/"production")
   *   "api_base_url" : str
   * }
   */
  public function getLocals(bool $json = true)
  {
    $locals = [
      'environment' => $this->testing ? 'testing' : 'production',
      'api_base_url' => $this->url
    ];

    return $json ? ['locals' => json_encode($locals)] : $locals;
  }

  /**
   * Get additional information on error code.
   *
   * @param string $error_code
   * Error code from API request response.
   * @return string
   * Text explaining the error that has happened.
   */
  public static function errorCodeText(string $error_code)
  {
    switch ($error_code) {
      case 'missing_field':
        return 'A mandatory attribute is missing. For example, calling Create a Contact without the mandatory email field in the request will result in this error.';
      case 'invalid_value':
        return 'This code indicates that a request contained an incorrect or blank value, or was in an invalid format.';
      case 'duplicate_value':
        return 'Indicates that this value already exists. This error is applicable to fields that require unique values such as the email address in a contact or the name in a company.';
      case 'datatype_mismatch':
        return 'Indicates that the field value doesn\'t match the expected data type. Entering text in a numerical field would trigger this error.';
      case 'invalid_field':
        return 'An unexpected field was part of the request. If any additional field is included in the request payload (other than what is specified in the API documentation), this error will occur.';
      case 'invalid_json':
        return 'Request parameter is not a valid JSON. We recommend that you validate the JSON payload with a JSON validator before firing the API request.';
      case 'invalid_credentials':
        return 'Incorrect or missing API credentials. As the name suggests, it indicates that the API request was made with invalid credentials. Forgetting to apply Base64 encoding on the API key is a common cause of this error.';
      case 'access_denied':
        return 'Insufficient privileges to perform this action. An agent attempting to access admin APIs will result in this error.';
      case 'require_feature':
        return 'Not allowed as a specific feature has to be enabled in your Freshdesk portal for you to perform this action.';
      case 'account_suspended':
        return 'Account has been suspended.';
      case 'ssl_required':
        return 'HTTPS is required in the API URL.';
      case 'readonly_field':
        return 'Read only field cannot be altered.';
      case 'inconsistent_state':
        return 'An email should be associated with the contact before converting the contact to an agent.';
      case 'max_agents_reached':
        return 'The account has reached the maximum number of agents.';
      case 'password_lockout':
        return 'The agent has reached the maximum number of failed login attempts.';
      case 'password_expired':
        return 'The agent\'s password has expired.';
      case 'no_content_required':
        return 'No JSON data required.';
      case 'inaccessible_field':
        return 'The agent is not authorized to update this field.';
      case 'incompatible_field':
        return 'The field cannot be updated due to the current state of the record.';
      default:
        return 'Unknown error code.';
    }
  }

  /**
   * Get information on error based on HTTP status code.
   *
   * @param int $error_status
   * HTTP status code from API request response.
   * @return string
   * Text explaining the error that has happened.
   */
  public static function errorStatusCodeText(int $error_status)
  {
    switch ($error_status) {
      case 400:
        return 'Client or Validation Error: The request body/query string is not in the correct format. For example, the Create a ticket API requires the requester_id field to be sent as part of the request and if it is missing, this status code is returned.';
      case 401:
        return 'Authentication Failure: Indicates that the Authorization header is either missing or incorrect. You can learn more about the Authorization header here.';
      case 403:
        return 'Access Denied: This indicates that the agent whose credentials were used in making this request was not authorized to perform this API call. It could be that this API call requires admin level credentials or perhaps the Freshdesk portal doesn\'t have the corresponding feature enabled. It could also indicate that the user has reached the maximum number of failed login attempts or that the account has reached the maximum number of agents';
      case 404:
        return 'Requested Resource not Found: This status code is returned when the request contains invalid ID/Freshdesk domain in the URL or an invalid URL itself. For example, an API call to retrieve a ticket with an invalid ID will return a HTTP 404 status code to let you know that no such ticket exists.';
      case 405:
        return 'Method not allowed: This API request used the wrong HTTP verb/method. For example an API PUT request on /api/v2/tickets endpoint will return a HTTP 405 as /api/v2/tickets allows only GET and POST requests.';
      case 406:
        return 'Unsupported Accept Header: Only application/json and */* are supported. When uploading files multipart/form-data is supported.';
      case 409:
        return 'Inconsistent/Conflicting State: The resource that is being created/updated is in an inconsistent or conflicting state. For example, if you attempt to Create a Contact with an email that is already associated with an existing user, this code will be returned.';
      case 415:
        return 'Unsupported Content-type: Content type application/xml is not supported. Only application/json is supported.';
      case 429:
        return 'Rate Limit Exceeded: The API rate limit allotted for your Freshdesk domain has been exhausted.';
      case 500:
        return 'Unexpected Server Error: Phew!! You can\'t do anything more here. This indicates an error at Freshdesk\'s side. Please email us your API script along with the response headers. We will reach you out to you and fix this ASAP.';
      default:
        return 'Unknown error code.';
    }
  }

  /**
   * Get time string from timestamp that is compatible with the API.
   *
   * Formats accepted by the API:
   *   YYYY-MM-DD
   *   YYYY-MM-DDTHH:MM
   *   YYYY-MM-DDTHH:MMZ
   *   YYYY-MM-DDTHH:MM:SS
   *   YYYY-MM-DDTHH:MM:SSZ
   *   YYYY-MM-DDTHH:MM:SS±hh:mm
   *   YYYY-MM-DDTHH:MM:SS±hh
   *   YYYY-MM-DDTHH:MM:SS±hhmm
   *
   * @param int $timestamp
   * Timestamp in UTC or Europe/Helsinki timezone.
   * @param bool $adjust_tz
   * If true, the timestamp is assumed to be in Europe/Helsinki timezone.
   * Otherwise, it is assumed to be UTC.
   * @return string
   * Formatted string ready for an API request.
   */
  private static function getTimeString(int $timestamp, bool $adjust_tz = true)
  {
    if (!$adjust_tz)
      return date('Y-m-d\TH:i:s\Z', $timestamp);
    $d = new DateTime('now', new DateTimeZone('Europe/Helsinki'));
    $d->setTimestamp($timestamp);
    return $d->format('Y-m-d\TH:i:sP');
  }

  /**
   * Performs a quick regex match on the specified time string to check if it
   * follows the syntax of the time string format required by the API.
   *
   * Formats accepted by the API:
   *   YYYY-MM-DD
   *   YYYY-MM-DDTHH:MM
   *   YYYY-MM-DDTHH:MMZ
   *   YYYY-MM-DDTHH:MM:SS
   *   YYYY-MM-DDTHH:MM:SSZ
   *   YYYY-MM-DDTHH:MM:SS±hh:mm
   *   YYYY-MM-DDTHH:MM:SS±hh
   *   YYYY-MM-DDTHH:MM:SS±hhmm
   */
  private static function validateTimeString(string $time_string): bool
  {
    // Quick regex to match all the example time strings and nothing else. This
    // effectively checks if a time string is formatted correctly for the API.
    $result = preg_match('/^[\d]{4}-[\d]{2}-[\d]{2}(?:T[\d]{2}:[\d]{2}(?::[\d]{2})?(?:[\+-][\d]{2}(?:[:]?[\d]{2})?)?[Z]?)?$/', $time_string);
    if (is_bool($result) && !$result) {
      static::logError('Error in preg_match inside validateTimeString() (return value FALSE).');
      return false;
    } else {
      return ($result == 1);
    }
  }

  /**
   * Get a configured cache paginator for ticket listing.
   *
   * This function should be used instead of manually creating paginator, so
   * that all places which require tickets use the same cache key. Use
   * {@see CachePaginator::filtered()} for filtering the tickets.
   *
   * @param int $per_page
   * Items per page.
   *
   * @param string $id
   * ID for the CachePaginator object. This should be unique among use cases, as
   * the cache items are prefixed with this.
   *
   * @param mixed $callback
   * Optional callback function which takes 2 parameters: page, page_size, and
   * returns the requested list of items. If not provided, default callback is
   * used.
   *
   * @return CachePaginator
   * CachePaginator object with configured cache key ID and callback.
   */
  public function getTicketPaginator(int $per_page = 10, $id = 'freshdesk_tickets', $callback = null)
  {
    /** @var CachePaginator object. */
    $paginator = Yii::createComponent('CachePaginator', $id);
    $paginator->logCategory = 'freshdesk';
    $paginator->pageSize = $per_page;

    if ($callback == null || !is_callable($callback)) {
      $paginator->callback = function ($page, $page_size) {
        return $this->listTickets(null, null, $page, $page_size, null, ['requester', 'description'], 'updated_at', 'desc');
      };
    } else {
      $paginator->callback = $callback;
    }

    return $paginator;
  }

  public function getContactPaginator(int $per_page = 10)
  {
    /** @var CachePaginator object. */
    $paginator = Yii::createComponent('CachePaginator', 'freshdesk_contacts');
    $paginator->logCategory = 'freshdesk';
    $paginator->pageSize = $per_page;
    $paginator->callback = function ($page, $page_size) {
      return $this->listContacts($page, $page_size);
    };
    return $paginator;
  }

  /**
   * Get URL for customer page.
   *
   * @param int $freshdesk_id
   * Customer ID.
   * @return string
   * URL for customer page.
   */
  public function getCustomerUrl(int $local_id)
  {
    $criteria = new CDbCriteria();
    $criteria->select = 'freshdesk_id';
    $criteria->condition = "id = $local_id";
    $fid = Asiakkaat::model()->find($criteria);
    return $this->getFreshdeskCustomerUrl($fid->freshdesk_id ?? 0);
  }

  public function getFreshdeskCustomerUrl(int $freshdesk_id)
  {
    $url = $this->baseUrl . '/a/contacts';
    if ($freshdesk_id > 0)
      $url .= "/$freshdesk_id";
    return $url;
  }

  public function getTicketUrl(int $ticket_id)
  {
    return $this->baseUrl . '/a/tickets/' . $ticket_id;
  }

  /**
   * Check whether Freshdesk functionality should be disabled.
   */
  public function isDisabled()
  {
    return $this->disabled;
  }

  /**
   * Export customer by ID to Freshdesk. If contact already exists, Freshdesk
   * will detect it and update existing contact instead.
   *
   * @param mixed $customer_id
   * Customer ID, or 'all' to export _ALL_ customers (not only new ones). Can
   * also be an array of multiple IDs.
   * @return array
   * [
   *   'updated_count' => 0,
   *   'created_count' => 0,
   *   'errors' => []
   * ]
   */
  public function exportContact($customer_id, $xhr = false)
  {
    $error_array = [];
    $this->logLocal('Exporting contact(s): %s', is_array($customer_id) ? json_encode($customer_id) : $customer_id);

    $error = function (string $text, string $description, array $errors = [], $headers = null, $asiakas = null) use (&$error_array) {
      $push = ['text' => $text, 'description' => $description, 'errors' => $errors, 'headers' => $headers];

      if ($asiakas) {
        $push['asiakas_id'] = $asiakas->id;
        $push['asiakas'] = $asiakas->yhteyshenkilo ?: $asiakas->sahkoposti ?: $asiakas->id;
      }

      $error_array[] = $push;
      $this->logLocalError('Error in exportContact()', $push, true);
    };

    if (!is_numeric($customer_id) && !is_array($customer_id) && $customer_id != 'all') {
      $error(sprintf('Invalid customer ID for export: %s', $customer_id), '');
      return ['updated_count' => 0, 'created_count' => 0, 'errors' => $error_array];
    }

    if (is_numeric($customer_id)) {

      if (!$asiakkaat = Asiakkaat::model()->findByPk($customer_id))
        $error('Sisäinen virhe: asiakasta ei löytynyt', "Customer by ID $customer_id was not found");
      else
        $asiakkaat = [$asiakkaat]; // findByPk returns single model

    } elseif (is_array($customer_id)) {

      // Check that array contains only integers.
      $filtered_non_numeric = array_filter($customer_id, function ($v, $k) {
        return !is_numeric($v);
      }, ARRAY_FILTER_USE_BOTH);
      if (!empty($filtered_non_numeric))
        $error('Sisäinen virhe: asiakkaita ei löytynyt', 'Invalid primary key(s) inside export array: ' . implode(', ', $filtered_non_numeric));
      elseif (!$asiakkaat = Asiakkaat::model()->findAllByPk($customer_id))
        $error('Sisäinen virhe: asiakkaita ei löytynyt', 'No customers found by ID(s) ' . implode(', ', $customer_id));
    } elseif (!$asiakkaat = Asiakkaat::model()->findAll()) {
      $error('Sisäinen virhe: asiakkaita ei löytynyt', 'No customers found');
    }

    if (empty($error_array)) {

      list($prevflush_m, $prevflush_s) = explode(' ', microtime());
      $pager = $this->getContactPaginator(100);
      $updated_count = 0;
      $created_count = 0;
      $processed_count = 0;

      foreach ($asiakkaat as $a) {

        $name = $a->yhteyshenkilo;
        if (empty($name)) {
          if (empty($a->sahkoposti)) {
            // $error(
            //   'Sisäinen virhe: vaadittu tieto (nimi) puuttuu',
            //   'Cannot fill required value \'name\': fields \'yhteyshenkilo\' and \'sahkoposti\' are empty',
            //   [], null, $a
            // );
            continue;
          }

          $name = $a->sahkoposti;
        }

        $opts = [
          'name' => $name,                //? (mandatory) (string) Name of the contact
          // 'email' => '',               //? * (unique) (string) Primary email address of the contact. If you want to associate additional email(s) with this contact, use the other_emails attribute.
          // 'phone' => 0,                //? * (string) Telephone number of the contact
          // 'mobile' => 0,               //? * (number) Mobile number of the contact
          // 'twitter_id' => '',          //? * (unique) (string) Twitter handle of the contact
          'unique_external_id' => $a->id, //? * (unique) (string) External ID of the contact
          //? * = One of these five attributes is mandatory (when creating, not updating).
          // 'other_emails' => [],        //? (array of strings) Additional emails associated with the contact
          // 'company_id' => 0,           //? (number) ID of the primary company to which this contact belongs
          // 'view_all_tickets' => true,  //? (boolean) Set to true if the contact can see all the tickets that are associated with the company to which he belong
          // 'other_companies' => [],     //? (array of hashes) Additional companies associated with the contact. This attribute can only be set if the Multiple Companies feature is enabled (Estate plan and above)
          // 'address' => '',             //? (string) Address of the contact.
          // 'avatar' => null,            //? (object) Avatar image of the contact The maximum file size is 5MB and the supported file types are .jpg, .jpeg, .jpe, and .png
          // 'custom_fields' => [],       //? (dictionary) Key value pairs containing the name and value of the custom field. Only dates in the format YYYY-MM-DD are accepted as input for custom date fields. Read more here
          // 'description' => '',         //? (string) A small description of the contact
          // 'job_title' => '',           //? (string) Job title of the contact
          // 'language' => 'fi',          //? (string) Language of the contact. Default language is "en". This attribute can only be set if the Multiple Language feature is enabled (Garden plan and above)
          // 'tags' => [],                //? (array of strings) Tags associated with this contact
          // 'time_zone' => ''            //? (string) Time zone of the contact. Default value is the time zone of the domain. This attribute can only be set if the Multiple Time Zone feature is enabled (Garden plan and above)
        ];

        if (filter_var($a->sahkoposti, FILTER_VALIDATE_EMAIL)) {
          $opts['email'] = $a->sahkoposti;

          if (!empty($a->puhelin) && preg_match('/^\+?[\d ]+$/', $a->puhelin)) {
            $opts['phone'] = $a->puhelin;
            $opts['mobile'] = $a->puhelin;
          }

          if (!empty($a->osoite)) {
            $opts['address'] = $a->osoite;
          }

          // Check for duplicate, in which case, update existing contact.
          $is_duplicate = false;
          $duplicate_id = 0;
          $filtered = $pager->filtered(1, function ($item) use ($a) {
            return (($item['unique_external_id'] ?? 0) == ($a->id ?? -1) || ($item['email'] ?? '<>') == ($a->sahkoposti ?? ''));
          }, 100, null, 1);
          if (!empty($filtered)) {
            $is_duplicate = true;
            $duplicate_id = $filtered[0]['id'];
          }

          $headers = [];
          $results = null;

          if ($is_duplicate) {
            $results = $this->updateContact($duplicate_id, $opts, $headers);

            if (!empty($results['errors'])) {
              // $error('Olemassaolevan asiakkaan päivittämisessä tapahtui virhe', $results['description'] ?? '', $results['errors'], $headers, $a);
            } else {
              $updated_count++;
            }
          } else {
            $results = $this->createContact($opts, $headers, true);

            if (!empty($results['errors'])) {

              // If code duplicate_value, call updateContact(). This can happen when a contact with
              // same info has been deleted but not deleted forever (in "trash can").
              if (
                count($results['errors']) == 1 &&
                ($results['errors'][0]['code'] ?? '') == 'duplicate_value' &&
                !empty($results['errors'][0]['additional_info']['user_id'])
              ) {
                $results = $this->updateContact($results['errors'][0]['additional_info']['user_id'], $opts, $headers);

                if (!empty($results['errors'])) {
                  // $error('Olemassaolevan asiakkaan päivittämisessä tapahtui virhe', $results['description'] ?? '', $results['errors'], $headers, $a);
                } else {
                  $updated_count++;
                }
              } else {
                // $error('Asiakkaan luonnissa tapahtui virhe', $results['description'] ?? '', $results['errors'], $headers, $a);
              }
            } else {
              $created_count++;
            }
          }

          if (!empty($results['id'])) {
            // var_dump($a->id, $a->yhteyshenkilo, $results);exit;
            // saveAttributes skips onAfterSave() event to avoid infinite loop.
            $a->saveAttributes(['freshdesk_id' => $results['id']]);
          }

          // var_dump("<pre>" . print_r($headers, true) . "</pre>");
          // var_dump("<pre>" . print_r($results, true) . "</pre>");
          // exit;
        }

        $processed_count++;
        if ($xhr && $processed_count < (count($asiakkaat) - 3)) {
          $temp_m = $prevflush_m;
          $temp_s = $prevflush_s;
          list($now_m, $now_s) = explode(' ', microtime());
          if ($temp_m > $now_m) {
            $temp_s++;
            $temp_m = -$temp_m;
          }
          if ($now_s - $temp_s > 0) {
            $prevflush_m = $now_m;
            $prevflush_s = $now_s;
            echo json_encode(['current' => $processed_count, 'total' => count($asiakkaat)]);
            ob_flush();
            flush();
          }
        }
      }
    }

    return [
      'updated_count' => $updated_count,
      'created_count' => $created_count,
      'errors' => $error_array
    ];
  }

  public static function getStatusText(int $status)
  {
    switch ($status) {
      case 3: // Pending
        return Yii::t('main', 'Vastattu');
        break;
      case 4: // Resolved
        return Yii::t('main', 'Ratkaistu');
        break;
      case 5: // Closed
        return Yii::t('main', 'Suljettu');
        break;
      case 6: // Waiting on customer
        return Yii::t('main', 'Odottaa Asiakasta');
        break;
      case 7: // Waiting for third party
        return Yii::t('main', 'Odottaa Tietoa');
        break;
      case 2: // Open
      default:
        return Yii::t('main', 'Vastaamatta');
        break;
    }
  }

  #endregion
}
