<?php

/**
 * Parameters class for Procountor searchInvoices() API call.
 *
 * @property string $status Invoice
 * Invoice status.
 * @property string $startDate
 * Start date of the search (invoice billing date). Given value is formatted
 * automatically when assigned, if the value is accepted by strtotime().
 * Format: Y-m-d (2016-08-31).
 * @property string $endDate
 * End date of the search (invoice billing date). Given value is formatted
 * automatically when assigned, if the value is accepted by strtotime().
 * Format: Y-m-d (2016-08-31).
 * @property string $createdStartDate
 * Start date of the search (invoice created date). Given value is formatted
 * automatically when assigned, if the value is accepted by strtotime().
 * Format: Y-m-d\TH:i:s (2016-08-31T00:00:00).
 * @property string $createdEndDate
 * End date of the search (invoice created date). Given value is formatted
 * automatically when assigned, if the value is accepted by strtotime().
 * Format: Y-m-d\TH:i:s (2016-08-31T00:00:00).
 * @property string $versionStartDate
 * Start date of the search (invoice updated date). Given value is formatted
 * automatically when assigned, if the value is accepted by strtotime().
 * Format: Y-m-d\TH:i:s (2016-08-31T00:00:00).
 * @property string $versionEndDate
 * End date of the search (invoice updated date). Given value is formatted
 * automatically when assigned, if the value is accepted by strtotime().
 * Format: Y-m-d\TH:i:s (2016-08-31T00:00:00).
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
  protected $startDate;
  protected $endDate;
  protected $createdStartDate;
  protected $createdEndDate;
  protected $versionStartDate;
  protected $versionEndDate;
  public $types;
  public $businessPartnerId;
  public $previousId;
  public $orderById;
  public $orderByDate;
  public $orderByCreated;
  public $orderByVersion;

  public function __get($property)
  {
    // Empty getter to allow retrieving protected date properties.
    if (property_exists($this, $property))
      return $this->$property;
  }

  public function __set($property, $value)
  {
    // Attempt to parse value if requested property is one of the date
    // properties. startDate and endDate formats are different from the other
    // dates (no time). Alternative regex conditionals commented above the if
    // -lines in case in_array calls are too slow.
    if (property_exists($this, $property)) {
      // if (preg_match('/(start|end)Date/', $property))
      if (in_array($property, ['createdStartDate', 'createdEndDate', 'versionStartDate', 'versionEndDate']))
        $this->$property = (!empty($time = strtotime($value))) ? date('Y-m-d\TH:i:s', $time) : $value;
      // elseif (preg_match('/(created|version)[\w]*Date/', $property))
      elseif (in_array($property, ['startDate', 'endDate']))
        $this->$property = (!empty($time = strtotime($value))) ? date('Y-m-d', $time) : $value;
      // other non-public property
      else
        $this->$property = $value;
    }

    return $this;
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
      //'createdStartDate' => $this->createdStartDate,
      //'createdEndDate' => $this->createdEndDate,
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

    return http_build_query($params);
  }
}
