<?php

/* Käytettävä palvelin */
global $TRUSTPOINT;
$TRUSTPOINT = "https://beta2.trustpoint.fi"; // Kehitys
#$TRUSTPOINT = "https://www.trustpoint.fi"; // Tuotanto

/**
* Hae TrustPoint siirtoavain.
*
* Esimerkki:
*
*     // Hae siirtoavain asiakkaalle 123456 salanalla abcdefghij
*     $transferkey = getTransferKey ('123456', 'abcdefghij');
*
* @param string $cid Asiakasnumero
* @param string $apicode Salasana
* @param string $ownref Tiedonsiirron tunniste (valinnainen)
* @return string
*/
function getTransferKey ($cid, $apicode, $ownref = '') {
    global $TRUSTPOINT;
    $text = httpPost ($TRUSTPOINT . '/API/requirekey.php',
        array(
            'cid' => $cid,
            'apicode' => $apicode,
            'ownref' => $ownref
        ));
    return $text;
}

/**
* Lähetä XML-muotoinen lasku TrustPoint-palveluun.
*
* Esimerkki:
*
*     // Lähetä XML-lasku
*     $res = commitTransfer ($xml);
*
*     // Tulkitse palvelimen palauttama XML-dokumentti
*     $doc = parseXml ($res);
*
*     // Tulosta hyväksyttyjen laskujen lasku- ja tehtävänumerot
*     for ($i = 0; $i < count ($doc->row); $i++) {
*         if ($doc->row[$i]->accepted == '1') {
*             echo 'accept billnum ' . $doc->row[$i]->billnum
*                 . ' jobid ' . $doc->row[$i]->jobid . "\n";
*         } else {
*             echo 'reject billnum ' . $doc->row[$i]->billnum
*                 . ' error ' . utf8_decode ($doc->row[$i]->error) . "\n";
*         }
*     }
*
* @param string $xml Lasku XML-muodossa
* @return string Palvelimen vastaus XML-muodossa
*/
function commitTransfer ($xml) {
    global $TRUSTPOINT;
header('Content-Type: text/html; charset=iso-8859-1');
    /* Lisää merkistökoodaus dokumentin alkuun */
    if (substr ($xml, 0, 5) != '<?xml') {
        $xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n" . $xml;
    }

    /* Lähetä ja siirrä dataa */
    $res = httpPost ($TRUSTPOINT . '/API/committransfer.php',
        array(
            'datastream' => $xml
        ));
    return $res;
}

/**
* Lähetä HTTP POST pyyntö palvelimelle.
*
* Esimerkki:
*
*     // Lähetä cid ja apicode muuttujat ja palauta serverin antama data
*     $text = httpPost ('https://www.trustpoint.fi/API/requirekey.php',
*         array(
*             'cid' => $cid,
*             'apicode' => $apicode
*         ));
*
* @param string $url Palvelimen URL-osoite
* @param array $fields Lähetettävät argumenttit
* @return string Palvelimen lähettämä käsittelemätön data
*/
function httpPost ($url, $args) {
    /* Alusta istunto */
    $ch = curl_init ($url);

    /* Lähetä dataa HTTP POST muodossa */
    curl_setopt ($ch, CURLOPT_POST, 1);

    /*
    * Välitä kentät palvelimelle.  Huomaa, että @-merkki arvon alussa
    * tarkoittaa, että @-merkkiä seuraava tiedostonimi siirretään
    * palvelimelle!  Jos tämä ei ole tarkoitus, pidä huoli siitä, että
    * yksikään argumentti ei ala @-merkillä.
    */
    curl_setopt ($ch, CURLOPT_POSTFIELDS, $args);

    /* curl_exec() palauttaa vain palvelimen lähettämän datan */
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_HEADER, 0);

    /* Lähetä ja vastaanota dataa */
    $output = curl_exec ($ch);
    curl_close ($ch);
    return $output;
}

/**
* Tulkitse XML-data.
*
* @param string $xml XML-sanoma
* @return SimpleXMLElement
*/
function parseXml ($xml) {
    libxml_use_internal_errors (true);
    $doc = simplexml_load_string ($xml);
    if (!$doc) {
        die ('Cannot parse XML');
    }
    return $doc;
}

/**
* Tee muuttujasta XML-dokumentin palanen.
*
* Esimerkki:
*
*     // Tulostaa: <datastream><transferkey>1234</transferkey></datastream>
*     echo encodeXml (array(
*         'datastream' => array(
*             'transferkey' => 123456,
*         )
*     ));
*
* @param any $value Merkkijono, numero tai taulukko
* @param int $indent Sisennyksen määrä (valinnainen)
* @return string Muuttujan arvo XML-merkkijonona
*/
function encodeXml ($value, $indent = 0) {
    if (is_null ($value)) {
        /* Tyhjä arvo */
        $text = "";
    } else if (is_string ($value)) {
        /* Merkkijono */
        $text = encodeXmlString ($value);
    } else if (isAssoc ($value)) {
        /* Assosiatiivinen taulukko */
        $text = encodeXmlAssoc ($value, $indent);
    } else if (isArray ($value)) {
        /* Indeksoitu taulukko */
        $text = encodeXmlArray ($value, $indent);
    } else if (is_bool ($value)) {
        /* Tosi/epätosi arvo */
        $text = (($value) ? "true" : "false");
    } else if (is_int ($value)  ||  is_double ($value)) {
        /* Kokonais- tai liukuluku */
        assert ('is_numeric ($value)');
        $text = '' . $value;
    } else {
        /* Olio, resurssikahva tai muu */
        fail ("Invalid argument $value");
    }
    return $text;
}


/**
* Muuta assosiatiivinen taulukko XML-muotoon.
*
* @param array $arr
* @param int $indent Sisennyksen määrä (valinnainen)
* @return string Muuttujan arvo XML-merkkijonona
*/
function encodeXmlAssoc ($arr, $indent = 0) {
    $text = '';
    foreach ($arr as $key => $value) {
        if (isAssoc ($value)) {
            $text .= str_repeat ('  ', $indent) . "<$key>\n";
            $text .= encodeXml ($value, $indent + 1);
            $text .= str_repeat ('  ', $indent) . "</$key>\n";
        } else if (isArray ($value)) {
            for ($i = 0; $i < count ($value); $i++) {
                $text .= str_repeat ('  ', $indent) . "<$key>\n";
                $text .= encodeXml ($value[$i], $indent + 1);
                $text .= str_repeat ('  ', $indent) . "</$key>\n";
            }
        } else {
            $text .= str_repeat ('  ', $indent) . "<$key>";
            $text .= encodeXml ($value, $indent + 1);
            $text .= "</$key>\n";
        }
    }
    return $text;
}

/**
* Muuta indeksoitu taulukko XML-muotoon.
*
* @param array $arr
* @param int $indent Sisennyksen määrä (valinnainen)
* @return string Muuttujan arvo XML-merkkijonona
*/
function encodeXmlArray ($arr, $indent = 0) {
    $text = '';
    foreach ($arr as $value) {
        $text .= encodeXml ($value, $indent);
    }
    return $text;
}

/**
* Muuta merkkijono XML-muotoon.
*
* @param array $arr
* @param int $indent Sisennyksen määrä (valinnainen)
* @return string Muuttujan arvo XML-merkkijonona
*/
function encodeXmlString ($value) {
    $text = "";

    /* Muotoile arvo merkkijonoksi */
    $i = 0;
    $n = strlen ($value);
    while ($i < $n) {
        /* Kopioi teksti seuraavaan erikoismerkkiin asti */
        if (preg_match ("/^[^<>&]+/", substr ($value, $i), $match)) {
            $len = strlen ($match[0]);
            $text .= substr ($value, $i, $len);
            $i += $len;
        }

        /* Muunna erikoismerkki XML-entiteetiksi, esim & => &#38; */
        if ($i < $n) {
            $c = substr ($value, $i++, 1);
            $text .= sprintf ('&#%d;', ord ($c));
        }
    }

    /* Muuta merkkijono UTF-8 muotoon */
    return utf8_encode ($text);
}

/**
* Tutki, onko argumentti assosiatiivinen taulukko.
*
* @param mixed $arr
* @return boolen Tosi, jos $arr on assosiatiivinen
*/
function isAssoc ($arr) {
    if (is_array ($arr)) {
        return array_keys ($arr) !== range (0, sizeof ($arr) - 1)
                &&  count ($arr) > 0;
    } else {
        /* Ei taulukko */
        return false;
    }
}

/**
* Tutki, onko argumentti indeksoitu taulukko.
*
* @param mixed $arr
* @return boolen Tosi, jos $arr on indeksoitu taulukko
*/
function isArray ($arr) {
    if (is_array ($arr)) {
        return !isAssoc ($arr);
    } else {
        /* Ei taulukko */
        return false;
    }
}
