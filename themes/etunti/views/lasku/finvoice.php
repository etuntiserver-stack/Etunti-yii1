<?php

if(isset($_GET['id']))
  $id = $_GET['id'];

// Procountor invoice creation and approval.
if (isset($_GET['hyvaksyminen']) && isset($_GET['procountor'])) {
  $l = Lasku::model()->findbypk($_GET['id']);

  // Establish notes section (lisätiedot -kenttä).
  $hyvitys = $l->laskun_nimetys == 'Hyvityslasku';
  $notes = $l->freetext ?? ''; // default to actual freetext field
  if (empty($notes) && ($l->laskun_nimetys ?? '') == 'Hyvityslasku') {
    $notes = 'Hyvityslasku';
  }

  $local = in_array($_SERVER['REMOTE_ADDR'], ['::1', '127.0.0.1']);

  // var_dump($l->attributes);
  // echo '<br><br>';
  // $lr = LaskunRivit::model()->findAll('lid=' . $_GET['id']);
  // var_dump($lr);
  // exit;

  /** @var Procountor */
  $pc = Yii::createComponent('Procountor');

  // Check that authorization is valid.
  if (!$pc->isAuthorized()) {
    Yii::app()->user->setFlash('danger', 'Procountor kirjautuminen on viallinen tai vanhentunut. Kirjaudu Procountoriin uudelleen asetuksista.');
    $this->redirect(array('update','id'=>$id));
  }

  // Create new invoice on Procountor only if it hasn't been created there
  // already. It's possible that the invoice was previously sent to Procountor,
  // and then marked UNFINISHED, allowing the code to reach this again.
  if (empty($l->procountor_id ?? '')) {

    // Use testing bank account for localhost (for etunti.test).
    $current_iban = $local ? 'FI7999999900032082' : str_replace(' ', '', $l->saaja_iban);
    $bank_account_found = false;
    $bank_account_results = $pc->getBankAccounts();

    if (isset($bank_account_results['errors']) || !isset($bank_account_results['results'])) {
      // $pc->logError('getBankAccounts', $bank_account_results, ['Lasku ID' => $l->id], 'Failed to get bank accounts from Procountor.');
      Yii::app()->user->setFlash('danger', 'Pankkitilien haku Procountorista epäonnistui.<br>'. json_encode($bank_account_results));
      $this->redirect(array('update','id'=>$id));
    }

    // Loop result bank accounts and compare IBAN.
    foreach($bank_account_results['results'] as $bank_account) {
      if (($iban = $bank_account['iban'] ?? '') == $current_iban) {

        // Ensure this bank account is active, because otherwise we get errors.
        if (($bank_account['status'] ?? '') != 'ACTIVE') {
          Yii::app()->user->setFlash('danger', 'Valittu pankkitili ei ole aktivoitu Procountorissa.');
          $this->redirect(array('update','id'=>$id));
        }

        $bank_account_found = true;
        break;
      }
    }

    if (!$bank_account_found) {
      Yii::app()->user->setFlash('danger', 'Valittua pankkitiliä ei löydy Procountorista. Jos pankkitili
                                            on lisätty Procountor tilillesi, ota yhteyttä ylläpitoon.');
      $this->redirect(array('update','id'=>$id));

      // TODO (?): Create bank account, BIC is needed! Not saved currently.
      // Also: ledgerAccount and bankingCode (important).
      $bank_account_params = [
        "iban" => $current_iban,
        "bic" => "",
        "bankName" => "",
        "currency" => "EUR",
        "defaultForInvoice" => false,
        "defaultForPayment" => false,
        "status" => "ACTIVE",
        "ledgerAccount" => "1910", // TODO
        "bankingCode" => "",
        "showAccountOnInvoice" => true,
        "allowPayments" => true,
        "allowForeignPayments" => true,
        "allowSalaryPayments" => true,
        "allowExpressPayments" => true
      ];
    }

    $name = $l->tyyppi == 'yritys' ? $l->yritys : $l->yhteyshenkilo;
    $channel = ($l->laskutus == 'verkkolasku') ? 'ELECTRONIC_INVOICE' : ($l->laskutus == 'posti' ? 'MAIL' : 'EMAIL');

    $params = [
      //"partnerId" => 0,                         // (int) Technical ID for the business partner. Used to link the invoice to a customer or supplier in the business partner register. If supplied, the company must have this partner ID in the corresponding register.
      "type" => "SALES_INVOICE",                  // (string) Invoice type. Note that this affects validation requirements.
      "status" => "UNFINISHED",                   // (string) Invoice status. A new invoice created through the API will have its status set as UNFINISHED.
      "date" => $l->paivays,                      // (string) Invoice date. This is synonymous to billing date.

      // This object holds information about the counterparty of the invoice. With sales invoices, it is the buyer. With
      // purchase invoices, it is the seller. With travel and expense invoices, it is the reporter of the expenses
      "counterParty" => (object) [
        "contactPersonName" => $l->yhteyshenkilo, // (string) Name of the contact person.
        "identifier" => $l->y_tunnus,             // (string) SALES_INVOICE and PURCHASE_INVOICE only. Business ID or national identification number.
        "taxCode" => "",                          // (string) SALES_INVOICE only. Tax code of the customer.
        "customerNumber" => $l->as_nro,           // (string) SALES_INVOICE and PURCHASE_INVOICE only. Customer number.
        "email" => trim($l->sahkoposti),          // (string) SALES_INVOICE only. Email address of the buyer. Required if invoicing channel is EMAIL, otherwise not visible on the UI.

        // Intermediary bank name and address.
        "counterPartyAddress" => (object) [
          "name" => $name,                        // (string) Name ("first line") in the address.
          "specifier" => "",                      // (string) Specifier, such as c/o address.
          "street" => $l->osoite,                 // (string) Street. Required for SALES_INVOICE if invoicing channel is MAIL. In that case, must be specified in counterPartyAddress if not specified in billingAddress.
          "zip" => $l->postinumero,               // (string) Zip code. Required for SALES_INVOICE if invoicing channel is MAIL. In that case, must be specified in counterPartyAddress if not specified in billingAddress.
          "city" => $l->toimipaikka,              // (string) City.
          "country" => "FINLAND",                 // (string) Country.
          "subdivision" => ""                     // (string) Subdivision of the city.
        ],

        // Payment bank account. Not required if payment method is cash.
        "bankAccount" => (object) [
          // Bank account IBAN. If using a financing agreement, the account number must match the account of the specified
          // financing agreement. The account number must be valid for the specified country, include country code and
          // exclude any spaces.
          "accountNumber" => $local ? 'FI7999999900032082' : str_replace(' ', '', $l->saaja_iban),
          // TODO: replace with above commented line. This IBAN is for the testing environment.
          // "accountNumber" => 'FI7999999900032082',

          // (string) PURCHASE_INVOICE only. Bank account BIC/SWIFT.
          "bic" => ""
        ],

        // SALES_INVOICE only. EInvoice address of the buyer. Required if invoicing channel is ELECTRONIC_INVOICE,
        // otherwise not visible on the UI.
        "einvoiceAddress" => (object) [
          "operator" => $l->v_tunnus,         // (string) SALES_INVOICE Only. Operator code. Required if the invoiceChannel is ELECTRONIC_INVOICE and country is FINLAND.
          "address" => $l->verkkolaskuosoite  // (string) SALES_INVOICE Only. EInvoice Address. Required if the invoiceChannel is ELECTRONIC_INVOICE, format must be valid for the specified country.
        ]
      ],

      // Intermediary bank name and address.
      "billingAddress" => (object) [
        "name" => $name,                      // (string) Name ("first line") in the address.
        "specifier" => "",                    // (string) Specifier, such as c/o address.
        "street" => $l->osoite,               // (string) Street. Required for SALES_INVOICE if invoicing channel is MAIL. In that case, must be specified in counterPartyAddress if not specified in billingAddress.
        "zip" => $l->postinumero,             // (string) Zip code. Required for SALES_INVOICE if invoicing channel is MAIL. In that case, must be specified in counterPartyAddress if not specified in billingAddress.
        "city" => $l->toimipaikka,            // (string) City.
        "country" => "FINLAND",               // (string) Country.
        "subdivision" => ""                   // (string) Subdivision of the city.
      ],

      // Intermediary bank name and address.
      "deliveryAddress" => (object) [
        "name" => $name,                      // (string) Name ("first line") in the address.
        "specifier" => "",                    // (string) Specifier, such as c/o address.
        "street" => $l->osoite,               // (string) Street. Required for SALES_INVOICE if invoicing channel is MAIL. In that case, must be specified in counterPartyAddress if not specified in billingAddress.
        "zip" => $l->postinumero,             // (string) Zip code. Required for SALES_INVOICE if invoicing channel is MAIL. In that case, must be specified in counterPartyAddress if not specified in billingAddress.
        "city" => $l->toimipaikka,            // (string) City.
        "country" => "FINLAND",               // (string) Country.
        "subdivision" => ""                   // (string) Subdivision of the city.
      ],

      // Invoice payment info. Includes the bank account to which the invoice should be paid, how it should be paid and when it should be paid.
      "paymentInfo" => (object) [
        "paymentMethod" => "BANK_TRANSFER",   // (string) Payment method. Methods other than BANK_TRANSFER, CASH, CLEARING, OTHER may require fields not supported by the API. Method DIRECT_DEBIT is not supported for new invoices.
        "currency" => "EUR",                  // (string) Currency of the payment in ISO 4217 format.

        // Changes in version 20.06:
        // The referenceCode field has been renamed to bankReferenceCode in GET /invoices/{id}, POST /invoices and PUT /invoices/{id} endpoints.
        // "referenceCode" => $l->viitenumero,   // (string) Payment reference code. If specified, must be a valid reference code where the last digit is a check digit. If the field is given an empty string value, a reference code is automatically generated by Procountor. If the field is not provided at all, no reference code will be assigned to the invoice.
        "bankReferenceCode" => $l->viitenumero,   // (string) Payment reference code. If specified, must be a valid reference code where the last digit is a check digit. If the field is given an empty string value, a reference code is automatically generated by Procountor. If the field is not provided at all, no reference code will be assigned to the invoice.

        "dueDate" => $l->erapaiva,            // (string) Payment due date. The payment term can be 0-999 days.
        "currencyRate" => 1,                  // (number) Currency exchange rate. Calculated as the amount of one unit of domestic currency in foreign currency. Only foreign currency payments should have a value other than 1.
        "paymentTermPercentage" => 0,         // (number) Discount percentage set in term of payment. Determines the discount if the invoice is paid before due date.
        "clearingCode" => "",                 // (string) Receiver bank's clearing code for foreign payments.

        // Payment bank account. Not required if payment method is cash.
        "bankAccount" => (object) [
          // (string) Bank account IBAN. If using a financing agreement, the account number must match the account of the
          // specified financing agreement. The account number must be valid for the specified country, include country
          // code and exclude any spaces.
          "accountNumber" => $local ? 'FI7999999900032082' : str_replace(' ', '', $l->saaja_iban),
          // TODO: replace with above commented line. This IBAN is for the testing environment.
          // "accountNumber" => 'FI7999999900032082',

          // (bic) PURCHASE_INVOICE only. Bank account BIC/SWIFT.
          "bic" => ""
        ],

        // Only SALES_INVOICE and PURCHASE_INVOICE. Cash discount set on the invoice.
        "cashDiscount" => (object) [
          "numberOfDays" => 0,          // (int) Days specified in cash discount
          "discountPercentage" => 0     // (number) Discount percentage specified in cash discount
        ],
      ],

      // Invoice extra info.
      "extraInfo" => (object) [
        "accountingByRow" => false,     // (bool) Accounting by row means that a separate ledger transaction is created for each invoice row.
        "unitPricesIncludeVat" => false  // (bool) Indicates if the unit prices on invoice rows include VAT (true) or not (false).
      ],

      "discountPercent" => 0,           // (int) Invoice discount percentage. Scale: 4.
      "orderReference" => $l->viitenne, // (string) Order reference of the invoice. This will be copied to the payment as message if no reference code is specified.
      "invoiceRows" => [],              // Filled later in a loop.
      "vatStatus" => 1,                 // (int) Invoice VAT status. Required for all invoices except travel invoices and expense claims.
      "originalInvoiceNumber" => "",    // (string) Invoice number from the biller in an external system.
      "deliveryStartDate" => "",        // (string) First day of the delivery period.
      "deliveryEndDate" => "",          // (string) Last day of the delivery period.
      //"deliveryMethod" => "OTHER",    // (string) Delivery method for the goods. Sales invoices do not support type OTHER.
      "deliveryInstructions" => "",     // (string) Delivery instructions.
      "invoiceChannel" => $channel,     // (string) Channel of distribution for the invoice. Values EDIFACT and PAPER_INVOICE are not allowed for new invoices.
      "penaltyPercent" => 0,            // (number) Penal interest rate. Scale: 2.
      "language" => "FINNISH",          // (string) Language of the invoice. Required for sales invoices, otherwise ignored.
      "additionalInformation" => $notes, // (string) Invoice notes containing additional information. Visible on the invoice. Use \n as line break.
      "vatCountry" => "FINLAND",        // (string) Country code describing which country is VAT standards are being used. Usage of foreign VAT settings must be agreed on separately with Procountor. Required if the company uses foreign VATs. Example value: SWEDEN.See Address.country in POST /invoices for a list of allowable values
      "notes" => $hyvitys ? 'Hyvityslasku' : "", // (string) Invoice notes (seller's/buyer's notes). Not visible on the invoice. Use \n as line break.
      // "factoringContractId" => 0,    // (int) SALES_INVOICE only. ID for external financing agreement. The bankAccount.accountNumber specified must match the one used by the specified financing agreement. Financing agreements cannot be used with cash payments.
      "factoringText" => "",            // (string) SALES_INVOICE only. Additional notes about external financing agreement.
      "orderNumber" => "",              // (string) Order number
      "agreementNumber" => "",          // (string) Agreement number
      "accountingCode" => "",           // (string) Accounting code
      "deliverySite" => "",             // (string) Delivery site
      "tenderReference" => ""           // (string) Tender reference

      // Travel information items. A travel invoice may have one or more travel information items containing departure
      // date, return date, destinations and travel purpose.
      /* "travelInformationItems" => [
        (object)[
          "departure" => "",
          "arrival" => "",
          "places" => "",
          "purpose" => ""
        ]
      ], */

    ];

    // Specify invoice rows.
    foreach (LaskunRivit::model()->findAll('lid=' . $_GET['id']) as $lr) {
      $params['invoiceRows'][] = (object) [
        "product" => $lr->tkoodi,       // (string) Product name.
        "productCode" => $lr->tkoodi,   // (string) Product code.
        "quantity" => $lr->kpl,         // (number) Product quantity.
        "unit" => "NO_UNIT",            // (string) Product unit.
        "unitPrice" => $lr->hinta,      // (number) Product unit price. This value is affected by the "unit prices include VAT" setting on the invoice.
        "discountPercent" => $lr->ale,  // (number) Product discount percentage.
        "vatPercent" => $lr->alv,       // (number) Product VAT percentage. Must be a percentage currently in use for the company.
        //"vatStatus" => 1,             // (int) Product VAT status.

        // Free text fix 06.07.2020; replace tkoodi with free_text (this model needs to be cleaned).
        // "comment" => $lr->tkoodi     // (string) Invoice row comment. Visible on the invoice. Use \ as line break.
        "comment" => $lr->free_text     // (string) Invoice row comment. Visible on the invoice. Use \ as line break.
      ];
    }

    // Send request to Procountor API.
    $response = $pc->createInvoice($params);

    // Look for the generated ID.
    if (isset($response['id'])) {

      // Invoice was sent successfully. Save ID.
      $l->procountor_id = $response['id'];
      $l->save();
    }
  }

  // Get the ID that was generated now or in previous event.
  if (!empty($procountor_id = $l->procountor_id ?? '')) {

    // Approve invoice.
    $approve_result = $pc->approveInvoice($procountor_id);
    if (isset($approve_result['errors'])) {
      $pc->logError('approveInvoice', $approve_result, ['Lasku ID' => $l->id], 'Error while approving invoice.');
      Yii::app()->user->setFlash('danger', 'Laskun hyväksymisessä tapahtui virhe. Vika on kirjattu, ja ylläpidolle on ilmoitettu asiasta.');
      $this->redirect(array('update', 'id' => $id));
    }
  } else {

    // If response doesn't contain ID, the invoice was not sent properly. Return
    // to the form now to avoid finvoice setting the status to 'LÄHETETTY'.
    $pc->logError('createInvoice', $response, ['Lasku ID' => $l->id], 'Server didn\'t return a generated invoice ID.');
    Yii::app()->user->setFlash('danger', 'Laskun hyväksymisessä/lähettämisessä tapahtui virhe. Viasta on ilmoitettu
                                          ylläpidolle. Jos vika jatkuu, ota yhteyttä ylläpitoon.');
    $this->redirect(array('update','id'=>$id));
  }
}

// Procountor invalidation.
if (isset($_GET['mitatointi']) && isset($_GET['procountor'])) {
  $pc = Yii::createComponent('Procountor');
  $l = Lasku::model()->findbypk($_GET['id']);

  // Check that authorization is valid.
  if (!$pc->isAuthorized()) {
    Yii::app()->user->setFlash('danger', 'Procountor kirjautuminen on viallinen tai vanhentunut. Kirjaudu Procountoriin uudelleen asetuksista.');
    $this->redirect(array('update','id'=>$id));
  }

  // If invoice was not created in Procountor, dont do anything here.
  if ($l->procountor_id) {

    // Change state to unfinished first, as an invoice cannot be invalidated in
    // NOT_SENT state. Set unfinished even if the invoice already is unfinished
    // (ignore any errors in the operation).
    $pc->setInvoiceUnfinished($l->procountor_id);

    // Invalidate
    $invalidate_results = $pc->invalidateInvoice($l->procountor_id);
    if (isset($invalidate_results['errors'])) {
      $pc->logError('getBankAccounts', $invalidate_results, ['Lasku ID' => $_GET['id']], 'Failed to invalidate invoice.');
      Yii::app()->user->setFlash('danger', 'Laskun mitätöinti Procountorissa epäonnistui. Vika on ilmoitettu ylläpitoon.');
      $this->redirect(array('update', 'id' => $id));
    }
  }
}

// Procountor sending.
if (isset($_GET['merkitseLahetettavaksi']) && isset($_GET['procountor'])) {
  $pc = Yii::createComponent('Procountor');
  $l = Lasku::model()->findbypk($_GET['id']);

  // Check that authorization is valid.
  if (!$pc->isAuthorized()) {
    Yii::app()->user->setFlash('danger', 'Procountor kirjautuminen on viallinen tai vanhentunut. Kirjaudu Procountoriin uudelleen asetuksista.');
    $this->redirect(array('update','id'=>$id));
  }

  // Ensure that the invoice was created to Procountor from the invoice view.
  if (!$l->procountor_id) {
    Yii::app()->user->setFlash('danger', 'Laskua ei voida lähettää Procountorissa koska sitä ei ole luotu Procountoriin Etunti käyttöliittymän kautta.');
    $this->redirect(array('update', 'id' => $id));
  }

  // Send invoice.
  $send_results = $pc->sendInvoice($l->procountor_id);
  if (isset($send_results['errors'])) {
    $pc->logError('sendInvoice', $send_results, ['Lasku ID' => $_GET['id']], 'Failed to send invoice.');
    Yii::app()->user->setFlash('danger', 'Laskun lähetys Procountorissa epäonnistui. Vika on ilmoitettu ylläpitoon.');
    $this->redirect(array('update', 'id' => $id));
  }
}

if(isset($_GET['kopio'])){

     // <-- Viimeinen laskunumero taulusta
     $criteria = new CDbCriteria();
     $criteria->order = " laskunumero!='' DESC,id DESC ";
     $ln = 0;
     $vm = Lasku::model()->find($criteria);
     if(isset($vm->id) and empty($model->laskunumero))
     $ln = $vm->laskunumero+1;
     // Viimeinen laskunumero taulusta -->

     $tapahtumapvm = date("Y-m-d H:i:s");
     $l = Lasku::model()->findbypk($id);

     $uusi = new Lasku;
     $uusi->attributes = $l->attributes;
     $uusi->paivays = date("Y-m-d");
     $uusi->erapaiva = date("Y-m-d", strtotime("+".$l->maksuehto." day"));
     $uusi->tilanne = '0';
     $uusi->laskunumero = $ln;
     $uusi->viitenumero = '';
     $uusi->netvisorkey = 0;
     $uusi->trust_jobid = '';
     if($uusi->save())
     {
	$viite = $this->Viite($uusi->as_nro."00".$uusi->id);
	Lasku::model()->updatebypk($uusi->id, array('viitenumero'=>$viite));
     }

     $lr = LaskunRivit::model()->findAll(" lid='".$id."' ");
     foreach($lr as $rivi)
     {

     $uusiR = new LaskunRivit;
     $uusiR->attributes = $rivi->attributes;
     $uusiR->lid = $uusi->id;
     $uusiR->save();

     }


		    $historia = new LaskuHistoria;
		    $historia->time = $tapahtumapvm;
		    $historia->lid = $uusi->id;
		    $historia->status = 'Lasku luotu';
		    $historia->palvelu = "local";
		    $historia->yht_euro = $l->yhteensa_total;
		    $historia->save();

	$this->redirect(array('update','id'=>$uusi->id));
}


if(isset($_GET['merkitseMaksetuksi'])){

     $tapahtumapvm = date("Y-m-d H:i:s");
     Lasku::model()->updatebypk($id, array('tilanne'=>3,'tapahtumapvm'=>$tapahtumapvm));

		    // Lasku historia
		    $l = Lasku::model()->findbypk($id);
		    $yhteensa_total = 0;
		    if(isset($l->yhteensa_total))
		    $yhteensa_total = $l->yhteensa_total;

		    $historia = new LaskuHistoria;
		    $historia->time = $tapahtumapvm;
		    $historia->lid = $id;
		    $historia->status = 'MAKSETTU';
		    $historia->palvelu = "local";
		    $historia->yht_euro = $yhteensa_total;
		    $historia->save();

	$this->redirect(array('update','id'=>$id));
}

if(isset($_GET['merkitseLahetettavaksi'])){

     $tapahtumapvm = date("Y-m-d H:i:s");
     Lasku::model()->updatebypk($id, array('tilanne'=>2,'tapahtumapvm'=>$tapahtumapvm));

		    // Lasku historia
		    $l = Lasku::model()->findbypk($id);
		    $historia = new LaskuHistoria;
		    $historia->time = $tapahtumapvm;
		    $historia->lid = $id;
		    $historia->status = 'LÄHETETTY';
		    $historia->palvelu = "local";
		    $historia->yht_euro = $l->yhteensa_total;
		    $historia->save();

	$this->redirect(array('update','id'=>$id));
}

if(isset($_GET['merkitseMaksumuistutusLahetettavaksi'])){

     $tapahtumapvm = date("Y-m-d H:i:s");
     Lasku::model()->updatebypk($id, array('tapahtumapvm'=>$tapahtumapvm));

		    // Lasku historia
		    $l = Lasku::model()->findbypk($id);
		    $historia = new LaskuHistoria;
		    $historia->time = $tapahtumapvm;
		    $historia->lid = $id;
		    $historia->status = 'MAKSUMUISTUTUS';
		    $historia->palvelu = "local";
		    $historia->yht_euro = $l->yhteensa_total;
		    $historia->save();

	$this->redirect(array('update','id'=>$id));
}

if(isset($_GET['hyvaksyminen'])){

     $tapahtumapvm = date("Y-m-d H:i:s");
     Lasku::model()->updatebypk($id, array('tilanne'=>1,'tapahtumapvm'=>$tapahtumapvm));

		    // Lasku historia
		    $l = Lasku::model()->findbypk($id);
		    $historia = new LaskuHistoria;
		    $historia->time = $tapahtumapvm;
		    $historia->lid = $id;
		    $historia->status = 'HYVÄKSYTTY';
		    $historia->palvelu = "local";
		    $historia->yht_euro = $l->yhteensa_total;
		    $historia->save();

	$this->redirect(array('update','id'=>$id));
}

if(isset($_GET['mitatointi'])){


     $tapahtumapvm = date("Y-m-d H:i:s");
     Lasku::model()->updatebypk($id, array('tilanne'=>999,'tapahtumapvm'=>$tapahtumapvm));

		    // Lasku historia
		    $l = Lasku::model()->findbypk($id);
		    $historia = new LaskuHistoria;
		    $historia->time = $tapahtumapvm;
		    $historia->lid = $id;
		    $historia->status = 'Lasku mitätöity';
		    $historia->palvelu = "local";
		    $historia->yht_euro = $l->yhteensa_total;
		    $historia->save();

	$this->redirect(array('update','id'=>$id));
}




// <-- laheta Netvisor
if(isset($_GET['lahetaNetvisor']))
{
	$return = $this->lahetaNetvisoriin($id);
	if( $return != false ){ $this->redirect(array('index')); }
}
//  laheta Netvisor -->





// <-- Trust Hyvityslasku
if(isset($_GET['finvoiceTrust']) or isset($_GET['hyvityslasku']) or isset($finvoiceTrust)){

 $cid = $asetukset['trust_cid'];
 $api = $asetukset['trust_api'];
 $trust_url = $asetukset['trust_url'];

 require_once ('lib/trust/inc.trust.php');

 $rowsArray 	= array();
 $taxrow_arr 	= array();
 $arr 		= array();
 $netamount_yht	= 0;
 $vatamount_yht	= 0;
 $totalamount_yht = 0;
 foreach($laskunRivit as $rivi){
        $rowsArray[] =  array(
                        "productid" => $rivi->id, # tuotenro
                        "desc" => $rivi->tkoodi,
                        "freetext" => $rivi->free_text,
                        "count" => $rivi->kpl, # määrä
                        "amount" => $rivi->hinta, # yksikköhinta
                        "totalitemprice" => $rivi->yhteensa_alv, # verollinen yksikköhinta
                        "taxpr" => $rivi->alv, # alv-prosentti
                        "discount" => $rivi->ale, # alennusprosentti
                        "itemtype" => $rivi->yksikko, # yksikkö
                        "netamount" => $rivi->veroton, # veroton summa
                        "vatamount" => $rivi->hinta_alv, # veron määrä
                        "totalamount" => $rivi->yhteensa_alv, # verollinen summa
                        "salesman" => "", # Myyjä
                        //"startdate" => "2013-01-01", # ajankohta
                        //"enddate" => "2015-12-31",
                        //"eancode" => "" # EAN-viivakoodi
                    );

		    $arr[$rivi->alv]['netamount'][] 	= $rivi->veroton;
		    $arr[$rivi->alv]['vatamount'][] 	= $rivi->hinta_alv;
		    $arr[$rivi->alv]['totalamount'][] 	= $rivi->yhteensa_alv;


		    $netamount_yht += $rivi->veroton;
		    $vatamount_yht += $rivi->hinta_alv;
		    $totalamount_yht += $rivi->yhteensa_alv;
  }

  foreach($arr as $key => $itm){
		    $taxrow_arr[] = array(
                        "taxpr" => $key,
                        "netamount" 	=> array_sum($arr[$key]['netamount']),
                        "vatamount" 	=> array_sum($arr[$key]['vatamount']),
                        "totalamount" 	=> array_sum($arr[$key]['totalamount'])
                    );
  }
  /*
  echo '<pre>';
  print_r( $taxrow_arr );
  echo '</pre>';
  exit;
  */

if($lasku['tyyppi'] == 'yritys')
$BuyerOrganisationName = $lasku['yritys'];
if($lasku['tyyppi'] == 'henkilo')
$BuyerOrganisationName = $lasku['nimi'];

if($lasku['tyyppi'] == 'yritys'){
$person = $lasku['yhteyshenkilo'];
$customertype = 1;
}
if($lasku['tyyppi'] == 'henkilo'){
$person = $lasku['nimi'];
$customertype = 2;
}

$cashdiscountrow = array();

$refundtojobid = '';
if(isset($_GET['refundtojobid']) and !empty($_GET['refundtojobid']))
$refundtojobid = $_GET['refundtojobid'];

if(isset($_GET['refundtojobid']) and empty($_GET['refundtojobid']))
{
echo 'refundtojobid puutuu';
exit;
}

  $sendtype = '';
  $evoice = '';
  $evoiceint = '';

if($lasku['laskutus'] == 'posti')
  $sendtype = 'post';

if($lasku['laskutus'] == 'verkkolasku'){
  $evoice = $lasku['verkkolaskuosoite'];
  $evoiceint = $lasku['v_tunnus'];
  $sendtype = 'evoice';
}

if($lasku['laskutus'] == 'sahkoposti')
  $sendtype = 'email';

  $sensible = 0;
if($lasku['muistutuslasku_auto'] != $sensible)
  $sensible = $lasku['muistutuslasku_auto'];

  $postclass = 1;
if($lasku['kirjeenluokka'] != $postclass)
  $postclass = $lasku['kirjeenluokka'];


  $jobtype = 0;
if(isset($_GET['jobtype']))
  $jobtype = $_GET['jobtype'];


/* Hae siirtoavain (korvaa cid ja apicode omillasi) */
$transferkey = getTransferKey ($cid, $api);
if (!$transferkey) {
    die ("Kirjautuminen epäonnistui\n");
}


if($jobtype == 0)
{
/* Muodosta täydellinen XML-lasku */
$xml = encodeXml (array(
    'datastream' => array(
        'transferkey' => $transferkey,
        'dataset' => array(
            array(
		"deliverymethod" => $lasku['deliverymethod'],
		"deliveryterm" => $lasku['deliveryterm'],
		"refundtojobid" => $refundtojobid,
                "custnum" => $lasku['as_nro'], # asiakasnumero
                //"addressaddline1" => $lasku['osoite'],
                "person" => $person,
                "company" => $lasku['yritys'], # yrityksen nimi
                //"addressaddline2" => "Edunvalvoja Essi Vuori",
                "address" => $lasku['osoite'], # katuosoite

                "postcode" => $lasku['postinumero'],
                "city" => $lasku['toimipaikka'],
                "addresscountry" => "FIN",
                //"vatperiod" => $lasku['vatperiod'],
                "customertype" => $customertype, # asiakastyyppi: 2=kuluttaja
                "jobtype" => $jobtype, # tehtävän tyyppi: 0 = lasku
                "paydate" => $lasku['erapaiva'], # eräpäivä
                "billdate" => $lasku['paivays'], # laskun päiväys
                "govid" => $lasku['y_tunnus'], # y-tunnus tai hetu
                "vatid" => "", # alv-tunniste
                "evoice" => $evoice, # verkkolaskuosoite
                "evoiceint" => $evoiceint, # välittäjän tunnus
                "overdueinterest" => $lasku['viivastyskorko'], # korkopros: tyhjä = oletus
                "billnum" => ($asetukset->lasku_laskunumero == 1)?$lasku['laskunumero']:'', # laskun numero
                "billcode" => "", # tilitysviite tai viesti
                "ourcode" => $lasku['viitemme'],
                "yourcode" => $lasku['viitenne'],
                "email" => $lasku['sahkoposti'], # 1.email osoite
                "email2" => "", # 2.email osoite
                //"salesman" => "MM", # vapaavalintainen myyjän tunniste
                //"salesmanname" => "Masa Myyjä", # myyjän nimi
                "checkbillnum" => ($asetukset->lasku_laskunumero == 1)?1:0, # 1=tarkista laskunumero, 0=ei
                "language" => "fin", # laskun kieli
                "freetext" => $lasku['freetext'],
                "sendtype" => $sendtype, # laskun lähetystapa
                "cashbill" => 0, # 0 = ei käteiskuitti
                "sensible" => $sensible, # 0 = lähetä muistutus automaattisesti
                //"ownref" => "x123", # sisäinen viite
                //"ordernumber" => "10232", # tilausnumero
                "negvat" => 0, # 0 = ei käänteistä alvia
                "postclass" => $postclass, # 1 = postitus 1.luokassa
                "color" => 0, # 0 = mustavalko
                "printoperator" => "enfo", # tulostusoperaattori
                "billtemplate" => "CUSTOM", # laskupohja
                "collectionprocess" => "AUTO", # saatavan laji

                # Myytävät tuotteet
                "payrow" => $rowsArray,

                # alv-erittely (tässä vain yksi rivi)
                "taxrow" => $taxrow_arr,

                "netamount" => $netamount_yht, # veroton hinta yhteensä
                "vatamount" => $vatamount_yht, # veron määrä yhteensä
                "totalamount" => $totalamount_yht, # verollinen loppusumma

                # Kassa-alennus
                "cashdiscountrow" => $cashdiscountrow,
            )
        )
    )
));
	/*
	echo '<pre>';
	print_r( parseXml($xml) );
	echo '</pre>';
	exit;
	*/
}


if($jobtype == 2)
{
/* Muodosta täydellinen XML-lasku */
$xml = encodeXml (array(
    'datastream' => array(
        'transferkey' => $transferkey,
        'dataset' => array(
            array(
                "custnum" => $lasku['as_nro'], # asiakasnumero
                "person" => $person,
                "company" => $lasku['yritys'], # yrityksen nimi
                "address" => $lasku['osoite'], # katuosoite
                "postcode" => $lasku['postinumero'],
                "city" => $lasku['toimipaikka'],
                "addresscountry" => "FIN",
                "customertype" => $customertype, # asiakastyyppi: 2=kuluttaja
                "jobtype" => $jobtype, # tehtävän tyyppi: 0 = lasku
                "paydate" => $lasku['erapaiva'], # eräpäivä
                "billdate" => $lasku['paivays'], # laskun päiväys
                "govid" => $lasku['y_tunnus'], # y-tunnus tai hetu
                "vatid" => "", # alv-tunniste
                "evoice" => $evoice, # verkkolaskuosoite
                "evoiceint" => $evoiceint, # välittäjän tunnus
                "overdueinterest" => $lasku['viivastyskorko'], # korkopros: tyhjä = oletus
                "billnum" => $lasku['laskunumero'], # laskun numero
                "billcode" => $lasku['viitenumero'], # tilitysviite tai viesti
                "email" => $lasku['sahkoposti'], # 1.email osoite
                "email2" => "", # 2.email osoite
                "checkbillnum" => 1, # 1=tarkista laskunumero, 0=ei
                "language" => "fin", # laskun kieli
                "sendtype" => $sendtype, # laskun lähetystapa
                "amount" => $lasku['yhteensa_total'], # verollinen loppusumma
		"noticedate" => $lasku['paivays'],

                # Myytävät tuotteet
                "payrow" => $rowsArray,

                # alv-erittely (tässä vain yksi rivi)
                "taxrow" => array(
                    array(
                        "taxpr" => 24.0,
                        "netamount" => $lasku['yhteensa_total_veroton'],
                        "vatamount" => $lasku['yhteensa_total_verot'],
                        "totalamount" => $lasku['yhteensa_total']
                    )
                ),

                # Kassa-alennus
                "cashdiscountrow" => $cashdiscountrow,
            )
        )
    )
));
}



/* Lähetä lasku palvelimelle */
$res = commitTransfer ($xml);
/* Tulosta vastausviesti */
//echo '<textarea class="form-control" rows="20" cols="40">'.$res.'</textarea>';
//echo "<br>";

/* Tulkitse palvelimen vastausviesti */
$doc = parseXml ($res);

/* Tulosta hyväksytyt ja hylätyt laskut */
for ($i = 0; $i < count ($doc->row); $i++) {

    if ($doc->row[$i]->accepted == '1') {

     	Lasku::model()->updatebypk($id, array('tilanne'=>2,'trust_jobid'=>$doc->row[$i]->jobid,'viitenumero'=>$doc->row[$i]->reference));

		    // Lasku historia
		    $l = Lasku::model()->findbypk($id);
		    $historia = new LaskuHistoria;
		    $historia->lid = $id;
		    $historia->status = json_encode($doc->row);
		    $historia->palvelu = "trust";
		    $historia->yht_euro = $l->yhteensa_total;
		    $historia->save();

	if(!isset($autolaskutus)){ $this->redirect(array('index')); }
	if(isset($autolaskutus)){
		$criteria=new CDbCriteria;
		$criteria->condition = " lasku_id='".$id."' ";
		Tyovuoroot::model()->updateAll(array('laskutettu' => '1'), $criteria);
	}

    } else {
        echo 'reject billnum ' . $doc->row[$i]->billnum
            . '<br> error ' . utf8_decode ($doc->row[$i]->error) . "<br>";
	exit;
    }
}



}







if(isset($_GET['laskutus']))
{
	$xml = $this->renderPartial('xml',array(
			'asetukset'=>$asetukset,
			'lasku'=>$lasku, 
			'yritys'=>$yritys,
			'laskunRivit'=>$laskunRivit
	), true);
}


if(isset($_GET['laskutus']) and $_GET['laskutus'] == 'verkkolasku'){

//echo $xml;
//exit;

if(!empty($asetukset['postita_username']) and !empty($asetukset['postita_password']))
{
$username = $asetukset['postita_username'];
$password = $asetukset['postita_password'];
$auth_string = $username . ":" . $password;


$account_info_url = 'https://postita.fi/api/account_info/';
$send_url = 'https://postita.fi/api/send/';
$send_finvoice_url = 'https://'.$auth_string.'@postita.fi/api/send_finvoice/';

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $account_info_url);
curl_setopt($ch, CURLOPT_USERPWD, $auth_string);
curl_setopt($ch, CURLOPT_FAILONERROR, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 100);

$account_info = curl_exec($ch);
$account_info = json_decode($account_info, true);


$pdf = trim($xml);
$pdf_b64 = $this->base64url_encode($pdf);

$data = array('job_name' => 'Verkkolasku', 'confirm' => false, 'finvoice' => $pdf_b64);
curl_setopt($ch, CURLOPT_URL, $send_finvoice_url);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:')); 

$send_response = curl_exec($ch);
$resultJson = json_encode($send_response);

if (curl_errno($ch)) {
  echo "\n\ncURL error number: " . curl_errno($ch);
  echo "\n\ncURL error: " . curl_error($ch);
}
$send_response = json_decode($send_response, true);

echo '<pre>';
print_r($send_response);
echo '</pre>';

curl_close($ch);

  if(isset($send_response[0]['status']) and $send_response[0]['status'] == 'NE')
  {

       $job_id = '';
       $created = '';

     foreach($send_response[0] as $k => $v ) {
       $prep[$k] = $k.":".$v;
       if($k == 'id')
       $job_id = $v;
       if($k == 'created')
       $created = $v;
     }

     $tapahtumapvm = date("Y-m-d H:i:s",strtotime(trim($created)));
     Lasku::model()->updatebypk($id, array('tilanne'=>2,'response_finvoice'=>$resultJson,'postita_jobid'=>$job_id,'tapahtumapvm'=>$tapahtumapvm));

		    // Lasku historia
		    $l = Lasku::model()->findbypk($id);
		    $historia = new LaskuHistoria;
		    $historia->time = $tapahtumapvm;
		    $historia->lid = $id;
		    $historia->status = $resultJson;
		    $historia->postita_statuscode = $send_response[0]['status'];
		    $historia->palvelu = "postita";
		    $historia->yht_euro = $l->yhteensa_total;
		    $historia->save();


  	if (!file_exists(Yii::app()->basePath."/../tiedostot/laskut/".Yii::app()->user->domain)) {
  		mkdir(Yii::app()->basePath."/../tiedostot/laskut/".Yii::app()->user->domain, 0777, true);
  	}

	$url = 'https://postita.fi/api/job_pdf/'.(int)$job_id;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERPWD, $auth_string);
	curl_setopt($ch, CURLOPT_FAILONERROR, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 100);
	
	$content = curl_exec($ch);
	curl_close($ch);
	
	$base64 = $content;
	$binary = $base64;
	$putPath = Yii::app()->basePath."/../tiedostot/laskut/".Yii::app()->user->domain;
	file_put_contents($putPath.'/'.$id.'.pdf', $binary);


     		    $this->redirect(array('update','id'=>$id));

  }


} else { 
	echo 'POSTITA tunnukset ei löydy';
} //if /pass


/*

$file = "tiedostot/finvoice/report.xml";
file_put_contents($file, $xml); 


header('Content-type: application/xml');
header('Content-Disposition: inline; filename="report.xml"');
@readfile($file);
*/
	//unlink($file);


}





if(isset($_GET['laskutus']) and $_GET['laskutus'] == 'posti'){

if(!empty($asetukset['postita_username']) and !empty($asetukset['postita_password']))
{
$username = $asetukset['postita_username'];
$password = $asetukset['postita_password'];
$auth_string = $username . ":" . $password;


$account_info_url = 'https://postita.fi/api/account_info/';
$send_url = 'https://postita.fi/api/send/';
$send_finvoice_url = 'https://'.$auth_string.'@postita.fi/api/send_finvoice/';

/* First initialize curl and set some options. For more information about
   curl with PHP refer to http://php.net/manual/en/book.curl.php */
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $account_info_url);
curl_setopt($ch, CURLOPT_USERPWD, $auth_string);
curl_setopt($ch, CURLOPT_FAILONERROR, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 100);

/* Getting account info */
$account_info = curl_exec($ch);
$account_info = json_decode($account_info, true);


echo '<pre>';
print_r($account_info);
echo '</pre>';


  $asetukset=Asetukset::model()->find("id=1");
  $firmanTiedot=FirmanTiedot::model()->find("id=1");

  $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
  $html2pdf->setDefaultFont('Arial');
  $html2pdf->WriteHTML($this->renderPartial('lasku_pdf', 
			array(
			'lasku'=>$lasku,
			'asetukset'=>$asetukset,
			'laskunRivit'=>$laskunRivit,
			'yritys'=>$firmanTiedot,
			),true));


  $content_PDF = $html2pdf->Output('my_doc.pdf', EYiiPdf::OUTPUT_TO_STRING);

  $pdf = $content_PDF;
  $pdf_b64 = base64url_encode($pdf);

$data = array('job_name' => 'PDF muoto', 'confirm' => false, 'pdf' => $pdf_b64, 'post_class' => $lasku['kirjeenluokka']);
curl_setopt($ch, CURLOPT_URL, $send_url);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:')); 

$send_response = curl_exec($ch);
$resultJson = json_encode($send_response);

if (curl_errno($ch)) {
  echo "\n\ncURL error number: " . curl_errno($ch);
  echo "\n\ncURL error: " . curl_error($ch);
}
$send_response = json_decode($send_response, true);

echo '<pre>';
print_r($send_response);
echo '</pre>';

curl_close($ch);


  if(isset($send_response['status']) and $send_response['status'] == 'NE')
  {
       $job_id = '';
       $created = '';
     foreach($send_response as $k => $v ) {
       $prep[$k] = $k.":".$v;
       if($k == 'id')
       $job_id = $v;
       if($k == 'created')
       $created = $v;
     }
     $tapahtumapvm = date("Y-m-d H:i:s",strtotime(trim($created)));
     Lasku::model()->updatebypk($id, array('tilanne'=>2,'response'=>$resultJson,'postita_jobid'=>$job_id,'tapahtumapvm'=>$tapahtumapvm));

		    // Lasku historia
		    $l = Lasku::model()->findbypk($id);
		    $historia = new LaskuHistoria;
		    $historia->time = $tapahtumapvm;
		    $historia->lid = $id;
		    $historia->status = $resultJson;
		    $historia->postita_statuscode = $send_response['status'];
		    $historia->palvelu = "postita";
		    $historia->yht_euro = $l->yhteensa_total;
		    $historia->save();


  	if (!file_exists(Yii::app()->basePath."/../tiedostot/laskut/".Yii::app()->user->domain)) {
  		mkdir(Yii::app()->basePath."/../tiedostot/laskut/".Yii::app()->user->domain, 0777, true);
  	}

	$url = 'https://postita.fi/api/job_pdf/'.(int)$job_id;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERPWD, $auth_string);
	curl_setopt($ch, CURLOPT_FAILONERROR, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 100);
	
	$content = curl_exec($ch);
	curl_close($ch);
	
	$base64 = $content;
	$binary = $base64;
	$putPath = Yii::app()->basePath."/../tiedostot/laskut/".Yii::app()->user->domain;
	file_put_contents($putPath.'/'.$id.'.pdf', $binary);



     		    $this->redirect(array('update','id'=>$id));

  }

} else { 
	echo 'POSTITA tunnukset ei löydy';
} //if /pass

}



// vahvistus
if(isset($_GET['vahvistus'])){

$username = $asetukset['postita_username'];
$password = $asetukset['postita_password'];
$auth_string = $username . ":" . $password;
$url = 'https://postita.fi/api/confirm/'.(int)$_GET['vahvistus'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERPWD, $auth_string);
curl_setopt($ch, CURLOPT_FAILONERROR, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 100);

$send_response = curl_exec($ch);
$resultJson = json_encode($send_response);

if (curl_errno($ch)) {
  echo "\n\ncURL error number: " . curl_errno($ch);
  echo "\n\ncURL error: " . curl_error($ch);
}
$send_response = json_decode($send_response, true);

echo '<pre>';
print_r($send_response);
echo '</pre>';
curl_close($ch);

  if(isset($send_response['status']) and $send_response['status'] == 'CO')
  {
       $job_id = '';
       $created = '';
     foreach($send_response as $k => $v ) {
       $prep[$k] = $k.":".$v;
       if($k == 'id')
       $job_id = $v;
       if($k == 'created')
       $created = $v;
     }
     $tapahtumapvm = date("Y-m-d H:i:s",strtotime(trim($created)));
     Lasku::model()->updatebypk($id, array('tapahtumapvm'=>$tapahtumapvm));

		    // Lasku historia
		    $l = Lasku::model()->findbypk($id);
		    $historia = new LaskuHistoria;
		    $historia->time = $tapahtumapvm;
		    $historia->lid = $id;
		    $historia->status = $resultJson;
		    $historia->postita_statuscode = $send_response['status'];
		    $historia->palvelu = "postita";
		    $historia->yht_euro = $l->yhteensa_total;
		    $historia->save();

     		    $this->redirect(array('update','id'=>$id));

  }

}


// poitaminen
if(isset($_GET['delete'])){

$username = $asetukset['postita_username'];
$password = $asetukset['postita_password'];
$auth_string = $username . ":" . $password;
$url = 'https://postita.fi/api/delete/'.(int)$_GET['delete'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERPWD, $auth_string);
curl_setopt($ch, CURLOPT_FAILONERROR, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 100);

$send_response = curl_exec($ch);
$resultJson = json_encode($send_response);

if (curl_errno($ch)) {
  echo "\n\ncURL error number: " . curl_errno($ch);
  echo "\n\ncURL error: " . curl_error($ch);
}
$send_response = json_decode($send_response, true);

echo '<pre>';
print_r($send_response);
echo '</pre>';
curl_close($ch);

     $tapahtumapvm = date("Y-m-d H:i:s");
     Lasku::model()->updatebypk($id, array('tapahtumapvm'=>$tapahtumapvm));

		    // Lasku historia
		    $l = Lasku::model()->findbypk($id);
		    $historia = new LaskuHistoria;
		    $historia->time = $tapahtumapvm;
		    $historia->lid = $id;
		    $historia->status = 'POISTETTU';
		    $historia->postita_statuscode = 'POISTETTU';
		    $historia->palvelu = "postita";
		    $historia->yht_euro = $l->yhteensa_total;
		    $historia->save();

     		    $this->redirect(array('update','id'=>$id));


}


if(isset($_GET['lahetaMuistutusPostita']) and !empty($asetukset['postita_username']) and !empty($asetukset['postita_password'])){


$username = $asetukset['postita_username'];
$password = $asetukset['postita_password'];
$auth_string = $username . ":" . $password;


$account_info_url = 'https://postita.fi/api/account_info/';
$send_url = 'https://postita.fi/api/send/';
$send_finvoice_url = 'https://'.$auth_string.'@postita.fi/api/send_finvoice/';

/* First initialize curl and set some options. For more information about
   curl with PHP refer to http://php.net/manual/en/book.curl.php */
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $account_info_url);
curl_setopt($ch, CURLOPT_USERPWD, $auth_string);
curl_setopt($ch, CURLOPT_FAILONERROR, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 100);

/* Getting account info */
$account_info = curl_exec($ch);
$account_info = json_decode($account_info, true);


echo '<pre>';
print_r($account_info);
echo '</pre>';


  $asetukset=Asetukset::model()->find("id=1");
  $firmanTiedot=FirmanTiedot::model()->find("id=1");

  $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
  $html2pdf->setDefaultFont('Arial');
  $html2pdf->WriteHTML($this->renderPartial('lasku_pdf', 
			array(
			'lasku'=>$lasku,
			'asetukset'=>$asetukset,
			'laskunRivit'=>$laskunRivit,
			'yritys'=>$firmanTiedot,
			'lahetaMuistutusPostita' => true,
			),true));


  $content_PDF = $html2pdf->Output('my_doc.pdf', EYiiPdf::OUTPUT_TO_STRING);

  $pdf = $content_PDF;
  $pdf_b64 = base64url_encode($pdf);

$data = array('job_name' => 'MAKSUMUISTUTUS', 'pdf' => $pdf_b64, 'post_class' => $lasku['kirjeenluokka']);
curl_setopt($ch, CURLOPT_URL, $send_url);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:')); 

$send_response = curl_exec($ch);
$resultJson = json_encode($send_response);

if (curl_errno($ch)) {
  echo "\n\ncURL error number: " . curl_errno($ch);
  echo "\n\ncURL error: " . curl_error($ch);
}
$send_response = json_decode($send_response, true);

echo '<pre>';
print_r($send_response);
echo '</pre>';

curl_close($ch);


  if(isset($send_response['status']) and $send_response['status'] == 'CO')
  {
       $job_id = '';
       $created = '';
     foreach($send_response as $k => $v ) {
       $prep[$k] = $k.":".$v;
       if($k == 'id')
       $job_id = $v;
       if($k == 'created')
       $created = $v;
     }
     $tapahtumapvm = date("Y-m-d H:i:s",strtotime(trim($created)));
     Lasku::model()->updatebypk($id, array('tapahtumapvm'=>$tapahtumapvm));
		    // Lasku historia
		    $l = Lasku::model()->findbypk($id);
		    $historia = new LaskuHistoria;
		    $historia->time = $tapahtumapvm;
		    $historia->lid = $id;
		    $historia->status = $resultJson;
		    $historia->postita_statuscode = 'MAKSUMUISTUTUS';
		    $historia->palvelu = "postita";
		    $historia->yht_euro = $l->yhteensa_total;
		    $historia->save();

     		    $this->redirect(array('update','id'=>$id));

  }

}

if(isset($_GET['lahetaSahkopostilla']) and !empty($lasku['sahkoposti']))
{

  $asetukset=Asetukset::model()->find("id=1");
  $firmanTiedot=FirmanTiedot::model()->find("id=1");

  $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
  $html2pdf->setDefaultFont('Arial');
  $html2pdf->WriteHTML($this->renderPartial('lasku_pdf', 
			array(
			'lasku'=>$lasku,
			'asetukset'=>$asetukset,
			'laskunRivit'=>$laskunRivit,
			'yritys'=>$firmanTiedot,
			),true));
  $content_PDF = $html2pdf->Output('my_doc.pdf', EYiiPdf::OUTPUT_TO_STRING);


		/* file */
		$file = 'lasku_'.date("YmdHi").'.pdf';
		$path = Yii::app()->request->baseUrl."emails/laskut/".Yii::app()->user->domain;

  		if (!file_exists($path))
		  	mkdir($path, 0777, true);

		file_put_contents($path.'/'.$file, $content_PDF);

		$message = Yii::t('main', 'Liitteenä uusi lasku');
		$saaja = $lasku['sahkoposti'];
		$subject = Yii::t('main', 'Ilmoitus saapuneesta laskusta');
		$mail = new YiiMailer();
		$mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
		$mail->setTo($saaja);
		$mail->setSubject($subject);
		$mail->setBody($message);
		$mail->setAttachment($path.'/'.$file);
	
		if($mail->send())
		{

							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $saaja;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->email_attachment	= $path.'/'.$file;
							$log->save();
							//     LOG -->

     		$tapahtumapvm = date("Y-m-d H:i:s");
     		Lasku::model()->updatebypk($id, array('tilanne'=>2,'tapahtumapvm'=>$tapahtumapvm));

		    // Lasku historia
		    $l = Lasku::model()->findbypk($id);
		    $historia = new LaskuHistoria;
		    $historia->time = $tapahtumapvm;
		    $historia->lid = $id;
		    $historia->status = 'Lähetetty sähköpostilla';
		    $historia->palvelu = "local";
		    $historia->yht_euro = $l->yhteensa_total;
		    $historia->save();


     		    $this->redirect(array('update','id'=>$id));
		}


} elseif(isset($_GET['lahetaSahkopostilla']) and empty($lasku['sahkoposti'])){

		echo Yii::t('main', 'Sähköposti puuttuu');

}



?>
