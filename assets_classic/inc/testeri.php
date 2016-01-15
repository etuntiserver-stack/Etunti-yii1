<?php
$kysymykset = array();

$kysymykset[] = array(
'kysymys' => 'Tiedätkö tarkkaan työntekijöidesi sijainnin?',
'haluttu_valinta' => 'ei',
'vastaus' => 'Kiitos antamistasi vastauksistasi, joiden perusteella yrityksesi liiketoimintaa on mahdollista kehittää. Tarjoamme liiketoimintaasi tukemaan ratkaisuksi Etunti -työajanseurantatyökalua, jossa on liikkuva työmobiilisovellus. Tutustu tarkemmin palveluun www.etunti.fi tai lähetä sähköpostia info@etunti.fi  tai soita Veiko Põldkivi p. 040 761 4366. Kiitämme antamistasi vastauksistasi ja toivotamme oikein hyvää päivän jatkoa!',
'moduli' => '',
);
$kysymykset[] = array(
'kysymys' => 'Joudutko syöttämään työntekijä- ja asiakastietoja sekä työnkuvauksia useaan paikkaan?',
'haluttu_valinta' => 'kylla',
'vastaus' => 'Onneksi olkoon, yrityksesi liiketoiminta on hyvällä mallilla. Ehdotamme toimintojen tehostamista ja ratkaisuksi tarjoamme Etunti – työajanseurantatyökalua, jossa on liikkuva työmobiilisovellus. Tämän työkalun rinnalle ehdotamme Etunti Manager –työkalua, jonka avulla saat nopean ja selkeän tilannekuvan toteutuneista työ- ja matkatunneista, liikevaihdosta, työn ja työntekijän sekä asiakkaiden tuottavuudesta. Tutustu tarjoamaamme palveluun: www.etunti.fi tai lähetä sähköpostia info@etunti.fi tai soita p. 040 761 4366 – Veiko Põldkivi. Kiitämme antamistasi vastauksistasi ja toivotamme oikein hyvää päivän jatkoa!',
'moduli' => '',
);
$kysymykset[] = array(
'kysymys' => 'Syövätkö työpisteiden väliset siirtymäajat mielestäsi liikaa liiketoimintasi kannattavuutta?',
'haluttu_valinta' => 'kylla',
'vastaus' => 'Hienoa! Liiketoimintasi ovat hyvällä mallilla. Rahavirtojen nopeuteen ja sujuvuuteen on hyvä kiinnittää huomiota. Ratkaisuksi tähän tarjoamme avuksi Etunti Laskutus työkalua perustyökalun Etunti – työajanseurantatyökalun rinnalle. Tämän avulla lähetät ketterästi toteutuneista työtunneista muodostettavat laskut suoraan asiakkaille verkkolaskuina tai sähköpostiin. Pääset tarkistamaan avoimia saatavia ja tekemään maksumuistutuksia helpommin kuin koskaan aikaisemmin. Tutustu tarkemmin palveluihimme www.etunti.fi. Kiinnostuitko, ota yhteyttä info@etunti.fi tai soita p. 040 761 4366 – Veiko Põldkivi.  Kiitämme antamistasi vastauksistasi ja toivotamme oikein hyvää päivän jatkoa!',
'moduli' => '',
);
$kysymykset[] = array(
'kysymys' => 'Tiedätkö myynnin kannattavuus- ja talousluvut reaaliaikaisesti?',
'haluttu_valinta' => 'ei',
'vastaus' => 'Onneksi olkoon! Liiketoimintasi on kunnossa. Siitä on hyvä jatkaa. Mikäli kuitenkin haluat liiketoimintaasi lisää kasvua, vinkkejä ja työhösi aikaa niin ota yhteyttä info@etunti.fi tai soita p. 040 761 4366 – Veiko Põldkivi. Voit myös ennen sitä tutustua tarkemmin palveluihimme www.etunti.fi. Kiitämme antamistasi vastauksistasi ja toivotamme oikein hyvää päivän jatkoa!',
'moduli' => '',
);
$kysymykset[] = array(
'kysymys' => 'Pystytkö lähettämään asiakkaallesi helposti ja vaivattomasti markkinointikirjeitä ja sopimuksia?',
'haluttu_valinta' => 'ei',
'vastaus' => 'Erinomaista. Haluat liiketoimintaasi lisää kasvua. Online – siivousvarauskalenterin avulla saat suoraa kassavirtaa ja kasvatat liikevaihtoa.  Etunti Online -työkalulla olet nopeampi kuin kilpailijasi. Tutustu tarkemmin palveluihimme www.etunti.fi tai ota yhteyttä info@etunti.fi tai soita p. 040 761 4366 – Veiko Põldkivi.
Kiitämme antamistasi vastauksistasi. Toivotamme oikein hyvää päivän jatkoa.
',
'moduli' => '',
);
$kysymykset[] = array(
'kysymys' => 'Viekö laskujen muodostaminen ja lähettäminen suhteettomasti aikaa?',
'haluttu_valinta' => 'kylla',
'vastaus' => 'Vastaus puuttuu',
'moduli' => '',
);
$kysymykset[] = array(
'kysymys' => 'Pystyykö asiakkaasi varaamaan vaivattomasti kalenterista siivouspalveluja?',
'haluttu_valinta' => 'ei',
'vastaus' => 'Vastaus puuttuu',
'moduli' => '',
);

?>

<div class="modal fade testeri-modal">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3>Etunti testeri</h3>
      </div>
      <div class="modal-body">
        <div class="kylla otsikko"><span>KYLLÄ</span><span>EI</span></div>
        <ul class="kysymykset">
        <?php
            foreach ($kysymykset as $key => $value) {
                echo "<li class=\"{$value['moduli']} {$value['haluttu_valinta']}\">
                {$value['kysymys']}
                <div class=\"radio\">
                    <div class=\"checkbox kylla\"><i class=\"fa fa-check\"></i></div>
                    <div class=\"checkbox ei\"><i class=\"fa fa-check\"></i></div>
                </div>
                <div class=\"vastaus\">{$value['vastaus']}</div>
                </li>";
            }
         ?>

        </ul>
      </div>
      <div class="modal-footer">
        <div class="testerintulos"></div>
        <button type="button" class="btn btn-lg btn-primary">Näytä tulos</button>
        <a href="#" class="btn btn-lg btn-default tarjouspyynto" data-toggle="modal" data-target=".pyydatarjous_lomake">Pyydä tarjous</a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->