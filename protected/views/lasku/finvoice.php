<?php
  $id = $_GET['id'];



if(isset($_GET['merkitseMaksetuksi'])){
     	Lasku::model()->updatebypk($id, array('tilanne'=>3));
	$this->redirect(array('update','id'=>$id));
}

if(isset($_GET['finvoiceTrust']) or isset($_GET['hyvityslasku'])){

 $cid = $asetukset['trust_cid'];
 $api = $asetukset['trust_api'];
 $trust_url = $asetukset['trust_url'];

 require_once ('tiedostot/trust/inc.trust.php');


   $rowsArray = array();
     foreach($laskunRivit as $rivi){

	if(isset($_GET['hyvityslasku']) and isset($_GET['refundtojobid']) and !empty($_GET['refundtojobid']))
	$rivi->kpl = '-'.$rivi->kpl;

        $rowsArray[] =  array(
                        "productid" => $rivi->id, # tuotenro
                        "desc" => $rivi->tkoodi,
                        "freetext" => "",
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
      }



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

/* Hae siirtoavain (korvaa cid ja apicode omillasi) */
$transferkey = getTransferKey ($cid, $api);
if (!$transferkey) {
    die ("Kirjautuminen epäonnistui\n");
}

/* Muodosta täydellinen XML-lasku */
$xml = encodeXml (array(
    'datastream' => array(
        'transferkey' => $transferkey,
        'dataset' => array(
            array(
		"refundtojobid" => $refundtojobid,
                "custnum" => $lasku['as_nro'], # asiakasnumero
                "addressaddline1" => $lasku['osoite'],
                "person" => $person,
                "company" => $lasku['yritys'], # yrityksen nimi
                //"addressaddline2" => "Edunvalvoja Essi Vuori",
                "address" => $lasku['osoite'], # katuosoite
                "postcode" => $lasku['postinumero'],
                "city" => $lasku['toimipaikka'],
                "addresscountry" => "FIN",
                "customertype" => $customertype, # asiakastyyppi: 2=kuluttaja
                "jobtype" => 0, # tehtävän tyyppi: 0 = lasku
                "paydate" => $lasku['erapaiva'], # eräpäivä
                "billdate" => $lasku['paivays'], # laskun päiväys
                "govid" => $lasku['y_tunnus'], # y-tunnus tai hetu
                "vatid" => "", # alv-tunniste
                "evoice" => $evoice, # verkkolaskuosoite
                "evoiceint" => $evoiceint, # välittäjän tunnus
                "overdueinterest" => "", # korkopros: tyhjä = oletus
                //"billnum" => $lasku['laskunumero'], # laskun numero
                "billcode" => "", # tilitysviite tai viesti
                //"ourcode" => $lasku['viitenumero'],
               // "yourcode" => "Asiakkaan viite",
                "email" => $lasku['sahkoposti'], # 1.email osoite
                "email2" => "", # 2.email osoite
                //"salesman" => "MM", # vapaavalintainen myyjän tunniste
                //"salesmanname" => "Masa Myyjä", # myyjän nimi
                "checkbillnum" => 1, # 1=tarkista laskunumero, 0=ei
                "language" => "fin", # laskun kieli
                "freetext" => "",
                "sendtype" => $sendtype, # laskun lähetystapa
                "cashbill" => 0, # 0 = ei käteiskuitti
                "sensible" => $sensible, # 0 = lähetä muistutus automaattisesti
                //"ownref" => "x123", # sisäinen viite
                //"ordernumber" => "10232", # tilausnumero
                "negvat" => 0, # 0 = ei käänteistä alvia
                "postclass" => $postclass, # 1 = postitus 1.luokassa
                "color" => 0, # 0 = mustavalko
                //"model" => "Malli tai merkki",
                "printoperator" => "enfo", # tulostusoperaattori
                "billtemplate" => "CUSTOM", # laskupohja
                "collectionprocess" => "AUTO", # saatavan laji

                //"voucherbatch" => "43", # tositelaji
                //"vouchernum" => "202132", # tositenumero
                //"period" => "2013-11-01", # mille kuukaudelle kohdistuu
                //"vatperiod" => "2013-11-01", # mille kuukaudella alv kohdistuu

                "netamount" => $lasku['yhteensa_total_veroton'], # veroton hinta yhteensä
                "vatamount" => $lasku['yhteensa_total_verot'], # veron määrä yhteensä
                "totalamount" => $lasku['yhteensa_total'], # verollinen loppusumma

/*
                # Lisäosoitteet
                "addaddress" => array(
                    array(
                        "addressaddline1" => "",
                        "addressaddline2" => "",
                        "address" => "Satamakatu 14",
                        "postcode" => "70100",
                        "city" => "KUOPIO",
                        "addresscountry" => "FIN",
                        "addresstype" => 2, # 2 = toimitusosoite
                    ),
                    array(
                        "addressaddline1" => "Edunvalvontatoimisto",
                        "addressaddline2" => "Edunvalvoja Essi Vuori",
                        "address" => "PL 358",
                        "postcode" => "02066",
                        "city" => "DOCUSCAN",
                        "addresscountry" => "FIN",
                        "addresstype" => 3, # 3 = laskutusosoite
                    ),
                ),
*/

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
/*
                # liitedokumentit
                "attachment" => array(
                    "attachmentfile" => base64_encode(
                        file_get_contents ("trust.jpg")
                    )
                ),
*/
/*
                # laskun tiliöinti
                "accountrow" => array(
                    array(
                        "accountid" => 3000, # myynti 24%
                        "servicecode" => 0,
                        "taxpr" => 0.0,
                        "vatamount" => 0,
                        "netamount" => 100.00,
                        "debit" => null,

                        "credit" => 100.00,
                        "desc" => "Rupi-webhotelli"
                    ),
                    array(
                        "accountid" => 3000, # myynti 24%
                        "servicecode" => 0,
                        "taxpr" => 0.0,
                        "vatamount" => 0,
                        "netamount" => 12.00,
                        "debit" => null,
                        "credit" => 12.00,
                        "desc" => "Fi-verkkotunnus"
                    ),
                    array(
                        "accountid" => 2939, # myynnin alv-velka
                        "servicecode" => 0,
                        "taxpr" => 0.0,
                        "vatamount" => 0,
                        "netamount" => 26.88,
                        "debit" => null,
                        "credit" => 26.88,
                        "desc" => ""
                    ),
                    array(
                        "accountid" => 1701, # myyntisaamiset
                        "servicecode" => 0,
                        "taxpr" => 0.0,
                        "vatamount" => 0,
                        "netamount" => 138.88,
                        "debit" => 138.88,
                        "credit" => null,
                        "desc" => "Heikki Henkilö"
                    )
                ),
*/

                # Kassa-alennus
                "cashdiscountrow" => $cashdiscountrow,
            )
        )
    )
));

/* Lähetä lasku palvelimelle */
echo "------ send ------\n";
echo $xml;
$res = commitTransfer ($xml);

/* Tulosta vastausviesti */
echo "------ receive ------\n";
//echo '<textarea class="form-control" rows="20">'.$res.'</textarea>';
echo "\n";

/* Tulkitse palvelimen vastausviesti */
$doc = parseXml ($res);
echo "------ parse ------\n";

/* Tulosta hyväksytyt ja hylätyt laskut */
for ($i = 0; $i < count ($doc->row); $i++) {
    if ($doc->row[$i]->accepted == '1') {
        echo 'accept billnum ' . $doc->row[$i]->billnum
            . ' jobid ' . $doc->row[$i]->jobid . "<br>";

     	Lasku::model()->updatebypk($id, array('tilanne'=>2,'trust_jobid'=>$doc->row[$i]->jobid,'viitenumero'=>$doc->row[$i]->reference));

		    // Lasku historia
		    $l = Lasku::model()->findbypk($id);
		    $historia = new LaskuHistoria;
		    $historia->lid = $id;
		    $historia->status = "Lasku//lähetetty//jobid:".$doc->row[$i]->jobid;
		    $historia->palvelu = "trust";
		    $historia->yht_euro = $l->yhteensa_total;
		    $historia->save();

	$this->redirect(array('admin'));
	break;

    } else {
        echo 'reject billnum ' . $doc->row[$i]->billnum
            . ' error ' . utf8_decode ($doc->row[$i]->error) . "<br>";
    }
}



}














function base64url_encode($input) {
    return strtr(base64_encode($input), '+/', '-_');
}



if(isset($_GET['finvoice'])){

if($lasku['tyyppi'] == 'yritys')
$BuyerOrganisationName = $lasku['yritys'];
if($lasku['tyyppi'] == 'henkilo')
$BuyerOrganisationName = $lasku['nimi'];

if($lasku['tyyppi'] == 'yritys')
$BuyerContactPersonName = $lasku['yhteyshenkilo'];
if($lasku['tyyppi'] == 'henkilo')
$BuyerContactPersonName = $lasku['nimi'];

$xml = '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE Finvoice SYSTEM "Finvoice.dtd">
<?xml-stylesheet type="text/xsl" href="Finvoice.xsl"?>
<Finvoice Version="1.2">
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
<InvoiceRecipientAddress>'.$lasku['osoite'].'</InvoiceRecipientAddress>
<InvoiceRecipientIntermediatorAddress></InvoiceRecipientIntermediatorAddress>
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
<DeliveryMethodText></DeliveryMethodText>
<DeliveryTermsText></DeliveryTermsText>
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
<VatRatePercent>22</VatRatePercent>
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
<VirtualBankBarcode>257800750155447003352980000000000000200448604122900008</VirtualBankBarcode>';

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
<RowVatRatePercent></RowVatRatePercent>
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


$pdf = $xml;
$pdf_b64 = base64url_encode($pdf);

$data = array('job_name' => 'A letter from PHP curl API', 'pdf' => $pdf_b64);
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

  if(isset($send_response[0]['status']) and $send_response[0]['status'] == 'CO')
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
		    $historia->palvelu = "postita";
		    $historia->yht_euro = $l->yhteensa_total;
		    $historia->save();

     $this->redirect(array('admin'));

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





if(isset($_GET['pdf'])){

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

$data = array('job_name' => 'A letter from PHP curl API', 'pdf' => $pdf_b64);
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


  if($send_response['status'] == 'CO')
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
		    $historia->palvelu = "postita";
		    $historia->yht_euro = $l->yhteensa_total;
		    $historia->save();

     $this->redirect(array('admin'));

  }

} else { 
	echo 'POSTITA tunnukset ei löydy';
} //if /pass

}






?>
