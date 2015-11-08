<?php

function base64url_encode($input) {
    return strtr(base64_encode($input), '+/', '-_');
}

if(isset($_GET['finvoice'])){

/*
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
*/

$xml = '<?xml version="1.0" encoding="ISO-8859-15"?>
<!-- edited with XML Spy v4.2 U (http://www.xmlspy.com) by Jussi Paasikallio (OKOBANK Group) -->
<!--Sample XML file generated by XML Spy v4.2 U (http://www.xmlspy.com)-->
<!DOCTYPE Finvoice SYSTEM "/../tiedostot/finvoice/Finvoice.dtd">
<?xml-stylesheet type="text/xsl" href="/../tiedostot/finvoice/Finvoice.xsl"?>
<!--Finvoice xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="/../tiedostot/finvoice/Finvoice.xsd" Version="1.2"-->
<Finvoice Version="1.2">
<SellerPartyDetails>
<SellerPartyIdentifier>'.$yritys['y_tunnus'].'</SellerPartyIdentifier>
<SellerOrganisationName>'.$yritys['tyonantaja'].'</SellerOrganisationName>
<SellerOrganisationTaxCode></SellerOrganisationTaxCode>
<SellerOrganisationTaxCodeUrlText>http://etunti.fi</SellerOrganisationTaxCodeUrlText>
<SellerPostalAddressDetails>
<SellerStreetName>'.$yritys['osoite'].'</SellerStreetName>
<SellerTownName>'.$yritys['postitoimipaikka'].'</SellerTownName>
<SellerPostCodeIdentifier>'.$yritys['postinumero'].'</SellerPostCodeIdentifier>
</SellerPostalAddressDetails>
</SellerPartyDetails>
<SellerContactPersonName>'.$yritys['johtaja'].'</SellerContactPersonName>
<SellerCommunicationDetails>
<SellerEmailaddressIdentifier>'.$yritys['sahkoposti'].'</SellerEmailaddressIdentifier>
</SellerCommunicationDetails>
<SellerInformationDetails>
<SellerHomeTownName>'.$yritys['postitoimipaikka'].'</SellerHomeTownName>
<SellerPhoneNumber>'.$yritys['puhelin'].'</SellerPhoneNumber>
<SellerFaxNumber></SellerFaxNumber>
<SellerCommonEmailaddressIdentifier></SellerCommonEmailaddressIdentifier>
<SellerWebaddressIdentifier></SellerWebaddressIdentifier>
<SellerFreeText></SellerFreeText>
<SellerAccountDetails>
<SellerAccountID IdentificationSchemeName="IBAN">'.$asetukset['iban'].'</SellerAccountID>
<SellerBic IdentificationSchemeName="BIC">OKOYFIHH</SellerBic>
</SellerAccountDetails>
<InvoiceRecipientDetails>
<InvoiceRecipientAddress></InvoiceRecipientAddress>
<InvoiceRecipientIntermediatorAddress></InvoiceRecipientIntermediatorAddress>
</InvoiceRecipientDetails>
</SellerInformationDetails>
<InvoiceSenderPartyDetails>
<InvoiceSenderPartyIdentifier></InvoiceSenderPartyIdentifier>
<InvoiceSenderOrganisationName></InvoiceSenderOrganisationName>
<InvoiceSenderOrganisationName></InvoiceSenderOrganisationName>
</InvoiceSenderPartyDetails>
<InvoiceRecipientPartyDetails>
<InvoiceRecipientPartyIdentifier/>
<InvoiceRecipientOrganisationName>Tilitoimisto Ryynänen</InvoiceRecipientOrganisationName>
<InvoiceRecipientOrganisationName>Accounting Company Ryynänen</InvoiceRecipientOrganisationName>
<InvoiceRecipientPostalAddressDetails>
<InvoiceRecipientStreetName>Mäkelänkatu 2</InvoiceRecipientStreetName>
<InvoiceRecipientTownName>Helsinki</InvoiceRecipientTownName>
<InvoiceRecipientPostCodeIdentifier>00102</InvoiceRecipientPostCodeIdentifier>
<CountryCode>FI</CountryCode>
<CountryName>FINLAND</CountryName>
<InvoiceRecipientPostOfficeBoxIdentifier>PL 22</InvoiceRecipientPostOfficeBoxIdentifier>
</InvoiceRecipientPostalAddressDetails>
</InvoiceRecipientPartyDetails>
<BuyerPartyDetails>
<BuyerPartyIdentifier>0123456-7</BuyerPartyIdentifier>
<BuyerOrganisationName>Sensorit Oy</BuyerOrganisationName>
<BuyerOrganisationTaxCode>0123456-7</BuyerOrganisationTaxCode>
<BuyerPostalAddressDetails>
<BuyerStreetName>Sempalokatu 2</BuyerStreetName>
<BuyerTownName>HELSINKI</BuyerTownName>
<BuyerPostCodeIdentifier>00122</BuyerPostCodeIdentifier>
</BuyerPostalAddressDetails>
</BuyerPartyDetails>
<BuyerContactPersonName>Hannes Puumalainen</BuyerContactPersonName>
<BuyerCommunicationDetails>
<BuyerPhoneNumberIdentifier>050-543 2658</BuyerPhoneNumberIdentifier>
<BuyerEmailaddressIdentifier>hannes.puumalainen@sensorit.fi</BuyerEmailaddressIdentifier>
</BuyerCommunicationDetails>
<DeliveryPartyDetails>
<DeliveryPartyIdentifier/>
<DeliveryOrganisationName>Helsingin Tanssihalli</DeliveryOrganisationName>
<DeliveryPostalAddressDetails>
<DeliveryStreetName>Satamakatu 2</DeliveryStreetName>
<DeliveryTownName>Helsinki</DeliveryTownName>
<DeliveryPostCodeIdentifier>00100</DeliveryPostCodeIdentifier>
<CountryName/>
<DeliveryPostofficeBoxIdentifier/>
</DeliveryPostalAddressDetails>
</DeliveryPartyDetails>
<DeliveryDetails>
<DeliveryDate Format="CCYYMMDD">20041205</DeliveryDate>
<DeliveryMethodText>Noudetaan</DeliveryMethodText>
<DeliveryTermsText>Vapaasti varastosta VOB</DeliveryTermsText>
<TerminalAddressText>Vantaan postiterminaali</TerminalAddressText>
<WaybillIdentifier>419/2004</WaybillIdentifier>
<WaybillTypeCode>WBGF</WaybillTypeCode>
<DelivererIdentifier>DeliID12222</DelivererIdentifier>
<DelivererName>Oy Lähettifirma Ab</DelivererName>
<DelivererName>Packgage Ltd.</DelivererName>
<DelivererCountryCode>FI</DelivererCountryCode>
<DelivererCountryName>FINLAND</DelivererCountryName>
<ManufacturerIdentifier>13331231233</ManufacturerIdentifier>
<ManufacturerName>AKG International</ManufacturerName>
<ManufacturerCountryCode>DE</ManufacturerCountryCode>
<ManufacturerCountryName>Germany</ManufacturerCountryName>
</DeliveryDetails>
<InvoiceDetails>
<InvoiceTypeCode>INV01</InvoiceTypeCode>
<InvoiceTypeText>LASKU</InvoiceTypeText>
<OriginCode>Original</OriginCode>
<InvoiceNumber>159</InvoiceNumber>
<InvoiceDate Format="CCYYMMDD">20041215</InvoiceDate>
<SellerReferenceIdentifier>MYY21231</SellerReferenceIdentifier>
<OrderIdentifier>TIL21222</OrderIdentifier>
<InvoiceTotalVatExcludedAmount AmountCurrencyIdentifier="EUR">2830,30</InvoiceTotalVatExcludedAmount>
<InvoiceTotalVatAmount AmountCurrencyIdentifier="EUR">622,68</InvoiceTotalVatAmount>
<InvoiceTotalVatIncludedAmount AmountCurrencyIdentifier="EUR">3352,98</InvoiceTotalVatIncludedAmount>
<VatSpecificationDetails>
<VatBaseAmount AmountCurrencyIdentifier="EUR">2830,30</VatBaseAmount>
<VatRatePercent>22</VatRatePercent>
<VatRateAmount AmountCurrencyIdentifier="EUR">622,68</VatRateAmount>
</VatSpecificationDetails>
<PaymentTermsDetails>
<PaymentTermsFreeText>5 pävää ./.2%, 14 päivää netto</PaymentTermsFreeText>
<InvoiceDueDate Format="CCYYMMDD">'.date("Ymd",strtotime($lasku['erapaiva'])).'</InvoiceDueDate>
<CashDiscountDate Format="CCYYMMDD">20041220</CashDiscountDate>
<CashDiscountBaseAmount AmountCurrencyIdentifier="EUR">3352,98</CashDiscountBaseAmount>
<CashDiscountPercent>2</CashDiscountPercent>
<CashDiscountAmount AmountCurrencyIdentifier="EUR">67,06</CashDiscountAmount>
<PaymentOverDueFineDetails>
<PaymentOverDueFineFreeText>Viivästyskorko 16%</PaymentOverDueFineFreeText>
<PaymentOverDueFinePercent>16</PaymentOverDueFinePercent>
</PaymentOverDueFineDetails>
</PaymentTermsDetails>
</InvoiceDetails>
<PaymentStatusDetails>
<PaymentStatusCode>PARTLYPAID</PaymentStatusCode>
</PaymentStatusDetails>
<VirtualBankBarcode>257800750155447003352980000000000000200448604122900008</VirtualBankBarcode>';

foreach($laskunRivit as $rivi){
$xml .= '<InvoiceRow>
<RowSubIdentifier>'.$rivi['id'].'</RowSubIdentifier>
<ArticleIdentifier>'.$rivi['id'].'</ArticleIdentifier>
<ArticleName>'.$rivi['tkoodi'].'</ArticleName>
<DeliveredQuantity QuantityUnitCode="kpl">'.$rivi['kpl'].'</DeliveredQuantity>
<OrderedQuantity QuantityUnitCode="kpl">'.$rivi['kpl'].'</OrderedQuantity>
<UnitPriceAmount AmountCurrencyIdentifier="EUR">'.$rivi['hinta'].'</UnitPriceAmount>
<RowIdentifier>TIL2122</RowIdentifier>
<RowDeliveryDate Format="CCYYMMDD"></RowDeliveryDate>
<RowAgreementIdentifier></RowAgreementIdentifier>
<RowRequestOfQuotationIdentifier></RowRequestOfQuotationIdentifier>
<RowPriceListIdentifier></RowPriceListIdentifier>
<RowDeliveryDetails>
<RowWaybillIdentifier></RowWaybillIdentifier>
<RowDelivererIdentifier></RowDelivererIdentifier>
<RowDelivererName></RowDelivererName>
<RowDelivererName></RowDelivererName>
<RowDelivererCountryCode></RowDelivererCountryCode>
<RowDelivererCountryName></RowDelivererCountryName>
<RowManufacturerIdentifier></RowManufacturerIdentifier>
<RowManufacturerName></RowManufacturerName>
<RowManufacturerCountryCode></RowManufacturerCountryCode>
<RowManufacturerCountryName></RowManufacturerCountryName>
</RowDeliveryDetails>
<RowShortProposedAccountIdentifier></RowShortProposedAccountIdentifier>
<RowNormalProposedAccountIdentifier></RowNormalProposedAccountIdentifier>
<RowFreeText></RowFreeText>
<RowVatRatePercent></RowVatRatePercent>
<RowVatAmount AmountCurrencyIdentifier="EUR">'.str_replace(".",",",($rivi['yhteensa_alv']-$rivi['veroton'])).'</RowVatAmount>
<RowVatExcludedAmount AmountCurrencyIdentifier="EUR">'.str_replace(".",",",$rivi['veroton']).'</RowVatExcludedAmount>
<RowAmount AmountCurrencyIdentifier="EUR">'.str_replace(".",",",$rivi['yhteensa_alv']).'</RowAmount>
</InvoiceRow>';
}

$xml .= '<EpiDetails>
<EpiIdentificationDetails>
<EpiDate Format="CCYYMMDD">20041215</EpiDate>
<EpiReference>2004486</EpiReference>
</EpiIdentificationDetails>
<EpiPartyDetails>
<EpiBfiPartyDetails>
<EpiBfiIdentifier IdentificationSchemeName="BIC">OKOYFIHH</EpiBfiIdentifier>
</EpiBfiPartyDetails>
<EpiBeneficiaryPartyDetails>
<EpiNameAddressDetails>Pullin Musiikki Oy</EpiNameAddressDetails>
<EpiBei>0199920-7</EpiBei>
<EpiAccountID IdentificationSchemeName="BBAN">50001520000081</EpiAccountID>
</EpiBeneficiaryPartyDetails>
</EpiPartyDetails>
<EpiPaymentInstructionDetails>
<EpiRemittanceInfoIdentifier IdentificationSchemeName="SPY">00000000000002004486</EpiRemittanceInfoIdentifier>
<EpiInstructedAmount AmountCurrencyIdentifier="EUR">3352,98</EpiInstructedAmount>
<EpiCharge ChargeOption="SHA">SHA</EpiCharge>
<EpiDateOptionDate Format="CCYYMMDD">20041229</EpiDateOptionDate>
</EpiPaymentInstructionDetails>
</EpiDetails>
<InvoiceUrlNameText></InvoiceUrlNameText>
<InvoiceUrlNameText></InvoiceUrlNameText>
<InvoiceUrlText></InvoiceUrlText>
<InvoiceUrlText></InvoiceUrlText>
</Finvoice>';




$file = "tiedostot/finvoice/report.xml";
file_put_contents($file, $xml); 


$username = "Sivex";
$password = "Etunti2000";
$auth_string = $username . ":" . $password;


$account_info_url = 'https://postita.fi/api/account_info/';
$send_url = 'https://postita.fi/api/send/';
$send_finvoice_url = 'https://Sivex:Etunti2000@postita.fi/api/send_finvoice/';


$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $account_info_url);
curl_setopt($ch, CURLOPT_USERPWD, $auth_string);
curl_setopt($ch, CURLOPT_FAILONERROR, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 100);


$account_info = curl_exec($ch);
$account_info = json_decode($account_info, true);


echo '<pre>';
print_r($account_info);
echo '</pre>';


$pdf = $xml;
$pdf_b64 = base64url_encode($pdf);


$data = array('job_name' => 'A letter from PHP curl API', 'pdf' => $pdf_b64);
curl_setopt($ch, CURLOPT_URL, $send_finvoice_url);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:')); 


$send_response = curl_exec($ch);
if (curl_errno($ch)) {
  echo "\n\ncURL error number: " . curl_errno($ch);
  echo "\n\ncURL error: " . curl_error($ch);
}
$send_response = json_decode($send_response, true);

echo '<pre>';
print_r($send_response);
echo '</pre>';

curl_close($ch);


/*
  $id = $_GET['id'];
  if(isset($send_response) and $send_response['status'] == 'CO'){


  foreach($send_response as $k => $v ) {
    $prep[$k] = $k.":".$v;
  }

  Lasku::model()->updatebypk($id, array('tilanne'=>2,'response'=>implode('//',$prep)));
  $this->redirect(array('update','id'=>$id));
  //echo '<h2>'.Yii::t('main','Lasku lähetetty onnistuneesti').'</h2>';
  }
*/

/*
header('Content-type: application/xml');
header('Content-Disposition: inline; filename="report.xml"');
@readfile($file);
*/
	//unlink($file);


exit;
}






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







?>
