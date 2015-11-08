<?php

$xml = '<?xml-stylesheet type="text/xsl" href="/../tiedostot/finvoice/Finvoice.xsl"?>
<Finvoice Version="2.0" xmlns:xsi="http://www.w3.org/2001/XMLSchemainstance"
xsi:noNamespaceSchemaLocation="/../tiedostot/finvoice/Finvoice.xsd">
  <SellerPartyDetails>
    <SellerPartyIdentifier>'.$yritys['y_tunnus'].'</SellerPartyIdentifier>
    <SellerOrganisationName>'.$yritys['tyonantaja'].'</SellerOrganisationName>
    <SellerOrganisationTaxCode></SellerOrganisationTaxCode>
    <SellerPostalAddressDetails>
      <SellerStreetName>'.$yritys['osoite'].'</SellerStreetName>
      <SellerTownName>'.$yritys['postitoimipaikka'].'</SellerTownName>
      <SellerPostCodeIdentifier>'.$yritys['postinumero'].'</SellerPostCodeIdentifier>
      <CountryCode>FI</CountryCode>
      <CountryName>Finland</CountryName>
    </SellerPostalAddressDetails>
  </SellerPartyDetails>
  <SellerContactPersonName>'.$yritys['johtaja'].'</SellerContactPersonName>
  <SellerCommunicationDetails>
    <SellerPhoneNumberIdentifier></SellerPhoneNumberIdentifier>
    <SellerEmailaddressIdentifier></SellerEmailaddressIdentifier>
  </SellerCommunicationDetails>
  <SellerInformationDetails>
    <SellerHomeTownName></SellerHomeTownName>
    <SellerVatRegistrationText></SellerVatRegistrationText>
    <SellerPhoneNumber>'.$yritys['puhelin'].'</SellerPhoneNumber>
    <SellerFaxNumber></SellerFaxNumber>
    <SellerCommonEmailaddressIdentifier>'.$yritys['sahkoposti'].'</SellerCommonEmailaddressIdentifier>
    <SellerWebaddressIdentifier></SellerWebaddressIdentifier>
    <SellerFreeText></SellerFreeText>
    <SellerAccountDetails>
      <SellerAccountID IdentificationSchemeName="IBAN">'.$asetukset['iban'].'</SellerAccountID>
      <SellerBic IdentificationSchemeName="BIC">'.$asetukset['bic'].'</SellerBic>
    </SellerAccountDetails>
  </SellerInformationDetails>
  <BuyerPartyDetails>
    <BuyerPartyIdentifier>'.$lasku['as_nro'].'</BuyerPartyIdentifier>
    <BuyerOrganisationName>'.$lasku['yritys'].'</BuyerOrganisationName>
    <BuyerOrganisationTaxCode></BuyerOrganisationTaxCode>
    <BuyerPostalAddressDetails>
      <BuyerStreetName>'.$lasku['osoite'].'</BuyerStreetName>
      <BuyerTownName>'.$lasku['toimipaikka'].'</BuyerTownName>
      <BuyerPostCodeIdentifier>'.$lasku['postinumero'].'</BuyerPostCodeIdentifier>
      <CountryName></CountryName>
    </BuyerPostalAddressDetails>
  </BuyerPartyDetails>
  <BuyerContactPersonName>'.$lasku['yhteyshenkilo'].'</BuyerContactPersonName>
  <BuyerCommunicationDetails>
    <BuyerPhoneNumberIdentifier></BuyerPhoneNumberIdentifier>
    <BuyerEmailaddressIdentifier>'.$lasku['sahkoposti'].'</BuyerEmailaddressIdentifier>
  </BuyerCommunicationDetails>
  <InvoiceDetails>
    <InvoiceTypeCode></InvoiceTypeCode>
    <InvoiceTypeText>LASKU</InvoiceTypeText>
    <OriginCode></OriginCode>
    <InvoiceNumber>'.$lasku['id'].'</InvoiceNumber>
    <InvoiceDate Format="CCYYMMDD">'.date("Ymd",strtotime($lasku['time'])).'</InvoiceDate>
    <SellerReferenceIdentifier></SellerReferenceIdentifier>
    <OrderIdentifier></OrderIdentifier>
    <InvoiceTotalVatExcludedAmount AmountCurrencyIdentifier="EUR">'.$lasku['yhteensa_total_veroton'].'</InvoiceTotalVatExcludedAmount>
    <InvoiceTotalVatAmount AmountCurrencyIdentifier="EUR">'.$lasku['yhteensa_total_verot'].'</InvoiceTotalVatAmount>
    <InvoiceTotalVatIncludedAmount AmountCurrencyIdentifier="EUR">'.$lasku['yhteensa_total'].'</InvoiceTotalVatIncludedAmount>
    <VatSpecificationDetails>
      <VatBaseAmount AmountCurrencyIdentifier="EUR">'.$lasku['maksettu_euro'].'</VatBaseAmount>
      <VatRatePercent>24</VatRatePercent>
      <VatRateAmount AmountCurrencyIdentifier="EUR">'.$lasku['yhteensa_total_verot'].'</VatRateAmount>
    </VatSpecificationDetails>
    <PaymentTermsDetails>
      <PaymentTermsFreeText></PaymentTermsFreeText>
      <InvoiceDueDate Format="CCYYMMDD">'.date("Ymd",strtotime($lasku['erapaiva'])).'</InvoiceDueDate>
      <PaymentOverDueFineDetails>
        <PaymentOverDueFineFreeText></PaymentOverDueFineFreeText>
        <PaymentOverDueFinePercent></PaymentOverDueFinePercent>
      </PaymentOverDueFineDetails>
    </PaymentTermsDetails>
  </InvoiceDetails>
  <PaymentStatusDetails>
    <PaymentStatusCode></PaymentStatusCode>
  </PaymentStatusDetails>
  <VirtualBankBarcode>'.$lasku['saaja_virtualkoodi'].'</VirtualBankBarcode>';

  foreach($laskunRivit as $rivi){
  $xml .= '
  <InvoiceRow>
    <ArticleIdentifier>'.$rivi['id'].'</ArticleIdentifier>
    <ArticleName>'.$rivi['tkoodi'].'</ArticleName>
    <DeliveredQuantity QuantityUnitCode="pcs">'.$rivi['kpl'].'</DeliveredQuantity>
    <OrderedQuantity QuantityUnitCode="pcs">'.$rivi['kpl'].'</OrderedQuantity>
    <UnitPriceAmount AmountCurrencyIdentifier="EUR" UnitPriceUnitCode="pcs">'.$rivi['hinta'].'</UnitPriceAmount>
    <RowNormalProposedAccountIdentifier></RowNormalProposedAccountIdentifier>
    <RowAccountDimensionText></RowAccountDimensionText>
    <RowVatRatePercent>'.$rivi['alv'].',00</RowVatRatePercent>
    <RowVatAmount AmountCurrencyIdentifier="EUR">'.str_replace(".",",",($rivi['yhteensa_alv']-$rivi['veroton'])).'</RowVatAmount>
    <RowVatExcludedAmount AmountCurrencyIdentifier="EUR">'.str_replace(".",",",$rivi['veroton']).'</RowVatExcludedAmount>
    <RowAmount AmountCurrencyIdentifier="EUR">'.str_replace(".",",",$rivi['yhteensa_alv']).'</RowAmount>
  </InvoiceRow>';
  }

$xml .= '
  <SpecificationDetails />
  <EpiDetails>
    <EpiIdentificationDetails>
      <EpiDate Format="CCYYMMDD">'.date("Ymd",strtotime($lasku['erapaiva'])).'</EpiDate>
      <EpiReference></EpiReference>
    </EpiIdentificationDetails>
    <EpiPartyDetails>
      <EpiBfiPartyDetails>
        <EpiBfiIdentifier IdentificationSchemeName="BIC"></EpiBfiIdentifier>
      </EpiBfiPartyDetails>
      <EpiBeneficiaryPartyDetails>
        <EpiNameAddressDetails></EpiNameAddressDetails>
        <EpiBei></EpiBei>
        <EpiAccountID IdentificationSchemeName="IBAN"></EpiAccountID>
      </EpiBeneficiaryPartyDetails>
    </EpiPartyDetails>
    <EpiPaymentInstructionDetails>
      <EpiPaymentInstructionId>1001</EpiPaymentInstructionId>
      <EpiRemittanceInfoIdentifier IdentificationSchemeName="SPY"></EpiRemittanceInfoIdentifier>
      <EpiInstructedAmount AmountCurrencyIdentifier="EUR"></EpiInstructedAmount>
      <EpiCharge ChargeOption="SHA"></EpiCharge>
      <EpiDateOptionDate Format="CCYYMMDD"></EpiDateOptionDate>
    </EpiPaymentInstructionDetails>
  </EpiDetails>
<InvoiceUrlText>12345678+102030FK405060708091011121314156</InvoiceUrlText>
</Finvoice>';





$username = "Sivex";
$password = "Etunti2000";
$auth_string = $username . ":" . $password;


$account_info_url = 'https://postita.fi/api/account_info/';
$send_url = 'https://postita.fi/api/send/';
$send_finvoice_url = 'https://Sivex:Etunti2000@postita.fi/api/send_finvoice/';

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

  $prep = array();
  foreach($account_info as $k => $v ) {
    $prep[$k] = $k.":".$v;
  }

echo '<pre>';
print_r($account_info);
echo '</pre>';

/* Send PDF */
/* To send a PDF, you first need to read it and encode it to base64url.
Check RFC 4648 section 5 for details. */
function base64url_encode($input) {
    return strtr(base64_encode($input), '+/', '-_');
}


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
  //$content_PDF = $xml;

$pdf = $content_PDF;
$pdf_b64 = base64url_encode($pdf);

/* We're creating a POST request out of the pdf and job's name. */
$data = array('job_name' => 'A letter from PHP curl API', 'pdf' => $pdf_b64);
curl_setopt($ch, CURLOPT_URL, $send_url);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:')); /* lighttpd fix */

/* Send the request and check for errors. */
$send_response = curl_exec($ch);
if (curl_errno($ch)) {
  echo "\n\ncURL error number: " . curl_errno($ch);
  echo "\n\ncURL error: " . curl_error($ch);
}
$send_response = json_decode($send_response, true);

echo '<pre>';
print_r($send_response);
echo '</pre>';

/* Clean up. */
curl_close($ch);



  $id = $_GET['id'];
  if(isset($send_response) and $send_response['status'] == 'CO'){


  foreach($send_response as $k => $v ) {
    $prep[$k] = $k.":".$v;
  }

  Lasku::model()->updatebypk($id, array('tilanne'=>2,'response'=>implode('//',$prep)));
  $this->redirect(array('update','id'=>$id));
  //echo '<h2>'.Yii::t('main','Lasku lähetetty onnistuneesti').'</h2>';
  }








/*
function base64url_encode($input) {
    return strtr(base64_encode($input), '+/', '-_');
}


$send_finvoice_url = 'https://Sivex:Etunti2000@postita.fi/api/send_finvoice/';
$pdf = $xml;
$pdf_b64 = base64url_encode($pdf);


$data = array('job_name' => 'A Finvoice letter from PHP API', 'pdf' => $pdf_b64);
$data = http_build_query($data);
$opts = array('http' => array(
 'method' => 'POST',
 'header'=> "Content-type: application/x-www-form-urlencoded\r\n",
 'content' => $data
 )
);

$send_context = stream_context_create($opts);

*/





/*
$file = "tiedostot/finvoice/report.xml";
file_put_contents($file, $xml); 

header('Content-type: application/xml');
header('Content-Disposition: inline; filename="report.xml"');
@readfile($file);
	unlink($file);

*/

?>
