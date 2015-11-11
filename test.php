<?php
require_once ('inc.trust.php');

/* Hae siirtoavain (korvaa cid ja apicode omillasi) */
$transferkey = getTransferKey ('1008168', 'kvrj44mqp9');
if (!$transferkey) {
    die ("Kirjautuminen epäonnistui\n");
}

/* Muodosta täydellinen XML-lasku */
$xml = encodeXml (array(
    'datastream' => array(
        'transferkey' => $transferkey,
        'dataset' => array(
            array(
                "custnum" => 10232, # asiakasnumero
                "addressaddline1" => "Kuopion edunvalvontatoimisto",
                "person" => "Heikki Henkilö",
                "company" => "", # yrityksen nimi
                "addressaddline2" => "Edunvalvoja Essi Vuori",
                "address" => "Satamakatu 123", # katuosoite
                "postcode" => "70101",
                "city" => "KUOPIO",
                "addresscountry" => "FIN",
                "customertype" => 2, # asiakastyyppi: 2=kuluttaja
                "jobtype" => 0, # tehtävän tyyppi: 0 = lasku
                "paydate" => "2013-11-30", # eräpäivä
                "billdate" => "2013-11-01", # laskun päiväys
                "govid" => "111111-111C", # y-tunnus tai hetu
                "vatid" => "", # alv-tunniste
                "evoice" => "", # verkkolaskuosoite
                "evoiceint" => "", # välittäjän tunnus
                "overdueinterest" => "", # korkopros: tyhjä = oletus
                "billnum" => "137", # laskun numero
                "billcode" => "Heikki Henkilö", # tilitysviite tai viesti
                "ourcode" => "Myyjän viite",
                "yourcode" => "Asiakkaan viite",
                "email" => "", # 1.email osoite
                "email2" => "", # 2.email osoite
                "salesman" => "MM", # vapaavalintainen myyjän tunniste
                "salesmanname" => "Masa Myyjä", # myyjän nimi
                "checkbillnum" => 1, # 1=tarkista laskunumero, 0=ei
                "language" => "fin", # laskun kieli
                "freetext" => "
Suuri kiitos tilauksesta!

Huomaa muuttunut tilinumero 1.1.2012 alkaen.
",
                "sendtype" => "post", # laskun lähetystapa
                "cashbill" => 0, # 0 = ei käteiskuitti
                "sensible" => 0, # 0 = lähetä muistutus automaattisesti
                "ownref" => "x123", # sisäinen viite
                "ordernumber" => "10232", # tilausnumero
                "negvat" => 0, # 0 = ei käänteistä alvia
                "postclass" => 1, # 1 = postitus 1.luokassa
                "color" => 0, # 0 = mustavalko
                "model" => "Malli tai merkki",
                "printoperator" => "enfo", # tulostusoperaattori
                "billtemplate" => "CUSTOM", # laskupohja
                "collectionprocess" => "AUTO", # saatavan laji

                "voucherbatch" => "43", # tositelaji
                "vouchernum" => "202132", # tositenumero
                "period" => "2013-11-01", # mille kuukaudelle kohdistuu
                "vatperiod" => "2013-11-01", # mille kuukaudella alv kohdistuu

                "netamount" => 112.00, # veroton hinta yhteensä
                "vatamount" => 26.88, # veron määrä yhteensä
                "totalamount" => 138.88, # verollinen loppusumma

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

                # Myytävät tuotteet
                "payrow" => array(
                    array(
                        "productid" => "T001", # tuotenro
                        "desc" => "Rupi-webhotelli", # tuotteen nimi
                        "freetext" => 
                        "Ominaisuudet:
- 500 Mt kotisivutilaa
- 5 Gt/kk siirtokaistaa
- 10 kpl sähköposteja", # tuotteen kuvaus
                        "count" => 1.0, # määrä
                        "amount" => 120.00, # yksikköhinta
                        "totalitemprice" => 147.60, # verollinen yksikköhinta
                        "taxpr" => 24.0, # alv-prosentti
                        "discount" => 0, # alennusprosentti
                        "itemtype" => "kpl", # yksikkö
                        "netamount" => 100.00, # veroton summa
                        "vatamount" => 24.00, # veron määrä
                        "totalamount" => 124.60, # verollinen summa
                        "salesman" => "MM", # Myyjäkoodi
                        "startdate" => "2013-01-01", # ajankohta
                        "enddate" => "2013-12-31",
                        "eancode" => "5901234123457" # EAN-viivakoodi
                    ),
                    array(
                        "productid" => "T002", # tuotenro
                        "desc" => "Fi-verkkotunnus",
                        "freetext" => "",
                        "count" => 1.0, # määrä
                        "amount" => 12.00, # yksikköhinta
                        "totalitemprice" => 14.88, # verollinen yksikköhinta
                        "taxpr" => 24.0, # alv-prosentti
                        "discount" => 0, # alennusprosentti
                        "itemtype" => "kpl", # yksikkö
                        "netamount" => 12.00, # veroton summa
                        "vatamount" => 2.88, # veron määrä
                        "totalamount" => 14.88, # verollinen summa
                        "salesman" => "", # Myyjä
                        "startdate" => "2013-01-01", # ajankohta
                        "enddate" => "2015-12-31",
                        "eancode" => "" # EAN-viivakoodi
                    )
                ),

                # alv-erittely (tässä vain yksi rivi)
                "taxrow" => array(
                    array(
                        "taxpr" => 24.0,
                        "netamount" => 112.00,
                        "vatamount" => 26.88,
                        "totalamount" => 138.88
                    )
                ),

                # liitedokumentit
                "attachment" => array(
                    "attachmentfile" => base64_encode(
                        file_get_contents ("trust.jpg")
                    )
                ),

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

                # Kassa-alennus
                "cashdiscountrow" => array(
                    "discountdate" => "2013-11-10",
                    "discountpercent" => "2",
                    "discountamount" => 2.77,
                    "discountfreetext" => "10 pv -2%"
                )
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
echo $res;
echo "\n";

/* Tulkitse palvelimen vastausviesti */
$doc = parseXml ($res);
echo "------ parse ------\n";

/* Tulosta hyväksytyt ja hylätyt laskut */
for ($i = 0; $i < count ($doc->row); $i++) {
    if ($doc->row[$i]->accepted == '1') {
        echo 'accept billnum ' . $doc->row[$i]->billnum
            . ' jobid ' . $doc->row[$i]->jobid . "\n";
    } else {
        echo 'reject billnum ' . $doc->row[$i]->billnum
            . ' error ' . utf8_decode ($doc->row[$i]->error) . "\n";
    }
}

