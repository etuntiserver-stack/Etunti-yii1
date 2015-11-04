<?php
if(isset($_POST['showLasku'])){
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
      <SellerAccountID IdentificationSchemeName="IBAN">'.$yritys['iban'].'</SellerAccountID>
      <SellerBic IdentificationSchemeName="BIC">'.$yritys['bic'].'</SellerBic>
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
    <ArticleName>'.$rivi['nimike'].'</ArticleName>
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

$file = "tiedostot/finvoice/report.xml";
file_put_contents($file, $xml); 

header('Content-type: application/xml');
header('Content-Disposition: inline; filename="report.xml"');
@readfile($file);
	unlink($file);
}


?>

<?php if(!isset($_POST['showLasku'])) : ?>
<legend>
<h1><?php echo Yii::t('main','FINVOICE'); ?></h1>
</legend>

<div class="row">
 <div class="col-sm-3">
   <form action="#" method="POST" target="_blank">
   <input type="hidden" name="showLasku">
   <input type="submit" class="btn btn-primary btn-sm" value="<?php echo Yii::t('main','Näytä finvoice'); ?>">
   </form>
 </div>
</div>
<?php endif; ?>
