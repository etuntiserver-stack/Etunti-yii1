<?php

/**
 * Parameters class for Procountor searchInvoices() API call.
 *
 * @property string $status Invoice
 * Invoice status.
 * @property string $startDate
 * Start date of the search (invoice billing date).
 * Format: Y-m-d\TH:i:s.v\Z (2016-08-31T00:00:00.000Z).
 * @property string $endDate
 * End date of the search (invoice billing date).
 * Format: Y-m-d\TH:i:s.v\Z (2016-08-31T00:00:00.000Z).
 * @property string $createdStartDate
 * Start date of the search (invoice created date).
 * Format: Y-m-d\TH:i:s.v\Z (2016-08-31T00:00:00.000Z).
 * @property string $createdEndDate
 * End date of the search (invoice created date).
 * Format: Y-m-d\TH:i:s.v\Z (2016-08-31T00:00:00.000Z).
 * @property string $versionStartDate
 * Start date of the search (invoice updated date).
 * Format: Y-m-d\TH:i:s.v\Z (2016-08-31T00:00:00.000Z).
 * @property string $versionEndDate
 * End date of the search (invoice updated date).
 * Format: Y-m-d\TH:i:s.v\Z (2016-08-31T00:00:00.000Z).
 * @property array $types
 * Invoice types. Available values: PERIODIC_TAX_RETURN, PURCHASE_INVOICE,
 * SALES_INVOICE, TRAVEL_INVOICE, BILL_OF_CHARGES.
 * @property int $businessPartnerId
 * Search invoices with given business partner ID.
 * @property int $previousId
 * Previous invoice ID for pagination.
 * @property string $orderById
 * Order the results by invoice ID.
 * Available values: ASC, DESC. Default value: DESC.
 * @property string $orderByDate
 * Order the results by date.
 * Available values: ASC, DESC. Default value: DESC.
 * @property string $orderByCreated
 * Order the results by created date.
 * Available values: ASC, DESC. Default value: DESC.
 * @property string $orderByVersion
 * Order the results by version (updated date).
 * Available values: ASC, DESC. Default value: DESC.
 */
class ProcountorInvoiceSearchParameters extends CComponent
{
  public $status;
  public $startDate;
  public $endDate;
  public $createdStartDate;
  public $createdEndDate;
  public $versionStartDate;
  public $versionEndDate;
  public $types;
  public $businessPartnerId;
  public $previousId;
  public $orderById;
  public $orderByDate;
  public $orderByCreated;
  public $orderByVersion;

  /**
   * Get a date string formatted to what the API expects.
   *
   * @param string $date_str
   * Date string for strtotime() (format e.g. "y-m-d" or "y-m-d H:i:s" etc.)
   */
  public function formatDate($date_str)
  {
    return date($this->getDateFormat(), strtotime($date_str));
  }

  /**
   * Get a format string for dates for the API call.
   */
  public function getDateFormat()
  {
    return 'Y-m-d\TH:i:s.v\Z';
  }

  /**
   * Build final parameters for the API call.
   * @return array Parameters.
   */
  public function buildParameters()
  {
    $params = [];

    $options = [
      'status' => $this->status,
      'startDate' => $this->startDate,
      'endDate' => $this->endDate,
      'createdStartDate' => $this->createdStartDate,
      'createdEndDate' => $this->createdEndDate,
      'versionStartDate' => $this->versionStartDate,
      'versionEndDate' => $this->versionEndDate,
      'types' => $this->types,
      'businessPartnerId' => $this->businessPartnerId,
      'previousId' => $this->previousId,
      'orderById' => $this->orderById,
      'orderByDate' => $this->orderByDate,
      'orderByCreated' => $this->orderByCreated,
      'orderByVersion' => $this->orderByVersion
    ];

    foreach($options as $key => $option) {
      if (!empty($option))
        $params[$key] = urlencode($option);
    }

    return $params;
  }
}
