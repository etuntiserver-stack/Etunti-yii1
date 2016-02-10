<?php


$asetukset = array(
    'nimi' => 'tarjouspyynto',
    'kiitos' => 'Kiitos',
    'error' => 'Huom! Tarkista, että täytit kaikki kentät oikein.',
    'AJAX' => TRUE,
    'LABEL_CLASS' => 'col-sm-5 control-label',
    'FIELD_CLASS' => 'col-sm-7',
    'GROUP_CLASS' => 'form-group',
    'FORM_CLASS' => 'form-horizontal',
    'URL' => __FILE__,
);
$asetukset['email'][] = array(
    'osoite' => 'info@etunti.fi',//veiko.poldkivi@etunti.fi //
    'kopio' => '',
    'otsikko' => 'tarjouspyynto: [Nimi]',
    'lahettaja' => 'info@etunti.fi',
    'viesti' => '[kentat]',
);
// $asetukset['email'][] = array(
//     'osoite' => '',
//     'kopio' => '',
//     'otsikko' => '',
//     'lahettaja' => '',
//     'viesti' => 'Nimi2: [Nimi]
//     ',
// );
$kentat = array();

$kentat[] = array(
'NAME' => 'Nimi',
'REQUIRED' => true,
'TYPE' => 'text',
);
$kentat[] = array(
'NAME' => 'Yritys',
'REQUIRED' => true,
'TYPE' => 'text',
);
$kentat[] = array(
'NAME' => 'Sähköposti',
'REQUIRED' => true,
'TYPE' => 'email',
);
$kentat[] = array(
'NAME' => 'Puhelin',
'REQUIRED' => true,
'TYPE' => 'text',
);
$kentat[] = array(
'NAME' => 'Mitä Siivousta',
'REQUIRED' => true,
'TYPE' => 'text',
'PLACEHOLDER' => 'Kotisiivous, Toimistosiivous, jne.',
);
$kentat[] = array(
'NAME' => 'Työntekijöiden määrä',
'REQUIRED' => true,
'TYPE' => 'text',
);
$kentat[] = array(
'NAME' => 'Liikevaihto vuodessa',
'REQUIRED' => true,
'TYPE' => 'select',
'OPTIONS' => array("alle 500 000 €", "500 000 - 800 000 €", "800 000 - 1 000 000 €", "1 000 000 - 1 500 000 €", "1 500 000 € - 2 000 000 €", "Yli 2 000 000 €"),
);

$kentat[] = array(
'VALUE' => 'Lähetä',
'TYPE' => 'submit',
'FIELD_CLASS' => 'col-sm-offset-9 col-sm-3',
'INPUT_CLASS' => 'btn btn-primary btn-lg',
);

$testiryhma = new lomake($kentat, $asetukset);
$testiryhma->form();
