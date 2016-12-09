<?php

if($lasku['tyyppi'] == 'yritys')
$BuyerOrganisationName = $lasku['yritys'];
if($lasku['tyyppi'] == 'henkilo')
$BuyerOrganisationName = $lasku['nimi'];

if($lasku['tyyppi'] == 'yritys')
$BuyerContactPersonName = $lasku['yhteyshenkilo'];
if($lasku['tyyppi'] == 'henkilo')
$BuyerContactPersonName = $lasku['nimi'];


$xml = '<?xml version="1.0" encoding="UTF-8"?>
<Finvoice Version="1.3" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="Finvoice.xsd">
<SellerPartyDetails>
<SellerPartyIdentifier>'.$yritys['y_tunnus'].'</SellerPartyIdentifier>

<SellerOrganisationName>'.$yritys['tyonantaja'].'</SellerOrganisationName>
<SellerOrganisationTaxCode>'.$yritys['y_tunnus'].'</SellerOrganisationTaxCode>
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
<SellerCommonEmailaddressIdentifier>'.$yritys['sahkoposti'].'</SellerCommonEmailaddressIdentifier>
<SellerWebaddressIdentifier></SellerWebaddressIdentifier>
<SellerFreeText></SellerFreeText>

<SellerAccountDetails>
<SellerAccountID IdentificationSchemeName="IBAN">'.$asetukset['iban'].'</SellerAccountID>
<SellerBic IdentificationSchemeName="BIC">'.$asetukset['bic'].'</SellerBic>
</SellerAccountDetails>

<InvoiceRecipientDetails>
<InvoiceRecipientAddress>'.$lasku['verkkolaskuosoite'].'</InvoiceRecipientAddress>
<InvoiceRecipientIntermediatorAddress>'.$lasku['v_tunnus'].'</InvoiceRecipientIntermediatorAddress>

</InvoiceRecipientDetails>
</SellerInformationDetails>
<InvoiceSenderPartyDetails>
<InvoiceSenderPartyIdentifier>'.$yritys['y_tunnus'].'</InvoiceSenderPartyIdentifier>

<InvoiceSenderOrganisationName>'.$yritys['tyonantaja'].'</InvoiceSenderOrganisationName>
</InvoiceSenderPartyDetails>
<InvoiceRecipientPartyDetails>
<InvoiceRecipientPartyIdentifier/>

<InvoiceRecipientOrganisationName>'.$lasku['yritys'].'</InvoiceRecipientOrganisationName>
<InvoiceRecipientPostalAddressDetails>
<InvoiceRecipientStreetName>'.$lasku['osoite'].'</InvoiceRecipientStreetName>
<InvoiceRecipientTownName>'.$lasku['toimipaikka'].'</InvoiceRecipientTownName>

<InvoiceRecipientPostCodeIdentifier>'.$lasku['postinumero'].'</InvoiceRecipientPostCodeIdentifier>
<CountryCode>FI</CountryCode>
<CountryName>FINLAND</CountryName>
<InvoiceRecipientPostOfficeBoxIdentifier></InvoiceRecipientPostOfficeBoxIdentifier>

</InvoiceRecipientPostalAddressDetails>
</InvoiceRecipientPartyDetails>
<BuyerPartyDetails>
<BuyerPartyIdentifier>'.$lasku['as_nro'].'</BuyerPartyIdentifier>

<BuyerOrganisationName>'.$BuyerOrganisationName.'</BuyerOrganisationName>
<BuyerOrganisationTaxCode>'.$lasku['y_tunnus'].'</BuyerOrganisationTaxCode>
<BuyerPostalAddressDetails>
<BuyerStreetName>'.$lasku['osoite'].'</BuyerStreetName>

<BuyerTownName>'.$lasku['toimipaikka'].'</BuyerTownName>
<BuyerPostCodeIdentifier>'.$lasku['postinumero'].'</BuyerPostCodeIdentifier>
</BuyerPostalAddressDetails>
</BuyerPartyDetails>

<BuyerContactPersonName>'.$BuyerContactPersonName.'</BuyerContactPersonName>
<BuyerCommunicationDetails>
<BuyerPhoneNumberIdentifier>'.$lasku['puhelin'].'</BuyerPhoneNumberIdentifier>

<BuyerEmailaddressIdentifier>'.$lasku['sahkoposti'].'</BuyerEmailaddressIdentifier>
</BuyerCommunicationDetails>
<DeliveryPartyDetails>
<DeliveryPartyIdentifier/>

<DeliveryOrganisationName>'.$lasku['t_yritys'].'</DeliveryOrganisationName>
<DeliveryPostalAddressDetails>
<DeliveryStreetName>'.$lasku['t_osoite'].'</DeliveryStreetName>
<DeliveryTownName>'.$lasku['t_toimipaikka'].'</DeliveryTownName>

<DeliveryPostCodeIdentifier>'.$lasku['t_postinumero'].'</DeliveryPostCodeIdentifier>
<DeliveryPostofficeBoxIdentifier/>
</DeliveryPostalAddressDetails>
</DeliveryPartyDetails>

<DeliveryDetails>
<DeliveryDate Format="CCYYMMDD">'.date("Ymd").'</DeliveryDate>
<DeliveryMethodText>'.$lasku['deliverymethod'].'</DeliveryMethodText>
<DeliveryTermsText>'.$lasku['deliveryterm'].'</DeliveryTermsText>

<TerminalAddressText></TerminalAddressText>
<WaybillIdentifier></WaybillIdentifier>
<WaybillTypeCode></WaybillTypeCode>
<DelivererIdentifier></DelivererIdentifier>

<DelivererName></DelivererName>
<DelivererCountryCode></DelivererCountryCode>

<DelivererCountryName></DelivererCountryName>

<ManufacturerIdentifier></ManufacturerIdentifier>
<ManufacturerName></ManufacturerName>
<ManufacturerCountryCode></ManufacturerCountryCode>
<ManufacturerCountryName>Germany</ManufacturerCountryName>

</DeliveryDetails>
<InvoiceDetails>

<InvoiceTypeCode>INV01</InvoiceTypeCode>
<InvoiceTypeText>LASKU</InvoiceTypeText>
<OriginCode>Original</OriginCode>
<InvoiceNumber>'.$lasku['laskunumero'].'</InvoiceNumber>

<InvoiceDate Format="CCYYMMDD">'.date("Ymd",strtotime($lasku['paivays'])).'</InvoiceDate>
<SellerReferenceIdentifier></SellerReferenceIdentifier>
<OrderIdentifier></OrderIdentifier>
<InvoiceTotalVatExcludedAmount AmountCurrencyIdentifier="EUR">'.str_replace(".",",",$lasku['yhteensa_total_veroton']).'</InvoiceTotalVatExcludedAmount>

<InvoiceTotalVatAmount AmountCurrencyIdentifier="EUR">'.str_replace(".",",",$lasku['yhteensa_total_verot']).'</InvoiceTotalVatAmount>
<InvoiceTotalVatIncludedAmount AmountCurrencyIdentifier="EUR">'.str_replace(".",",",$lasku['yhteensa_total']).'</InvoiceTotalVatIncludedAmount>

<VatSpecificationDetails>
<VatBaseAmount AmountCurrencyIdentifier="EUR">'.str_replace(".",",",$lasku['yhteensa_total_veroton']).'</VatBaseAmount>
<VatRatePercent>24</VatRatePercent>
<VatRateAmount AmountCurrencyIdentifier="EUR">'.str_replace(".",",",$lasku['yhteensa_total_verot']).'</VatRateAmount>

</VatSpecificationDetails>
<PaymentTermsDetails>
<PaymentTermsFreeText>'.$lasku['maksuehto'].' pv</PaymentTermsFreeText>
<InvoiceDueDate Format="CCYYMMDD">'.date("Ymd",strtotime($lasku['erapaiva'])).'</InvoiceDueDate>

<CashDiscountDate Format="CCYYMMDD"></CashDiscountDate>
<CashDiscountBaseAmount AmountCurrencyIdentifier="EUR">'.str_replace(".",",",$lasku['yhteensa_total']).'</CashDiscountBaseAmount>
<CashDiscountPercent>2</CashDiscountPercent>
<CashDiscountAmount AmountCurrencyIdentifier="EUR"></CashDiscountAmount>

<PaymentOverDueFineDetails>
<PaymentOverDueFineFreeText>Viivästyskorko '.$lasku['viivastyskorko'].'%</PaymentOverDueFineFreeText>
<PaymentOverDueFinePercent>'.$lasku['viivastyskorko'].'</PaymentOverDueFinePercent>
</PaymentOverDueFineDetails>

</PaymentTermsDetails>
</InvoiceDetails>
<PaymentStatusDetails>

<PaymentStatusCode>PARTLYPAID</PaymentStatusCode>
</PaymentStatusDetails>
<VirtualBankBarcode></VirtualBankBarcode>';

foreach($laskunRivit as $rivi){
$xml .= '<InvoiceRow>
<RowSubIdentifier></RowSubIdentifier>
<ArticleIdentifier></ArticleIdentifier>

<ArticleName>'.$rivi['tkoodi'].'</ArticleName>
<DeliveredQuantity QuantityUnitCode="kpl">'.$rivi['kpl'].'</DeliveredQuantity>
<OrderedQuantity QuantityUnitCode="kpl">'.$rivi['kpl'].'</OrderedQuantity>
<UnitPriceAmount AmountCurrencyIdentifier="EUR">'.$rivi['hinta'].'</UnitPriceAmount>

<RowIdentifier></RowIdentifier>
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

<RowVatRatePercent>'.$rivi['alv'].'</RowVatRatePercent>
<RowVatAmount AmountCurrencyIdentifier="EUR">'.str_replace(".",",",($rivi['yhteensa_alv']-$rivi['veroton'])).'</RowVatAmount>
<RowVatExcludedAmount AmountCurrencyIdentifier="EUR">'.str_replace(".",",",$rivi['veroton']).'</RowVatExcludedAmount>

<RowAmount AmountCurrencyIdentifier="EUR">'.str_replace(".",",",$rivi['yhteensa_alv']).'</RowAmount>
</InvoiceRow>';
}

$xml .= '<EpiDetails>
<EpiIdentificationDetails>
<EpiDate Format="CCYYMMDD">'.$lasku['erapaiva'].'</EpiDate>
<EpiReference>2004486</EpiReference>

</EpiIdentificationDetails>
<EpiPartyDetails>
<EpiBfiPartyDetails>
<EpiBfiIdentifier IdentificationSchemeName="BIC">'.$asetukset['bic'].'</EpiBfiIdentifier>

</EpiBfiPartyDetails>
<EpiBeneficiaryPartyDetails>
<EpiNameAddressDetails>'.$yritys['tyonantaja'].'</EpiNameAddressDetails>
<EpiBei></EpiBei>

<EpiAccountID IdentificationSchemeName="BBAN">'.$asetukset['tilinumero'].'</EpiAccountID>
</EpiBeneficiaryPartyDetails>
</EpiPartyDetails>
<EpiPaymentInstructionDetails>

<EpiRemittanceInfoIdentifier IdentificationSchemeName="SPY">'.$lasku['viitenumero'].'</EpiRemittanceInfoIdentifier>
<EpiInstructedAmount AmountCurrencyIdentifier="EUR">'.str_replace(".",",",$lasku['yhteensa_total']).'</EpiInstructedAmount>
<EpiCharge ChargeOption="SHA">SHA</EpiCharge>
<EpiDateOptionDate Format="CCYYMMDD">'.$lasku['erapaiva'].'</EpiDateOptionDate>

</EpiPaymentInstructionDetails>
</EpiDetails>
<InvoiceUrlNameText></InvoiceUrlNameText>
<InvoiceUrlNameText></InvoiceUrlNameText>

<InvoiceUrlText></InvoiceUrlText>
<InvoiceUrlText></InvoiceUrlText>
</Finvoice>';

	echo $xml;
?>
