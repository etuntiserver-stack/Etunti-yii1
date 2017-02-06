<?php
include_once 'lomake/lomake_class.php';

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
    'osoite' => 'tuki@etunti.fi',
    'kopio' => '',
    'otsikko' => 'tarjouspyynto: [Nimi]',
    'lahettaja' => '',
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
'NAME' => 'Modulit',
'TYPE' => 'hidden',
'FIELD_CLASS' => 'valitut_modulit',
);
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
'VALUE' => 'Lähetä',
'TYPE' => 'submit',
'FIELD_CLASS' => 'col-sm-offset-9 col-sm-3',
'INPUT_CLASS' => 'btn btn-primary btn-lg',
);

$tarjouspyynto = new lomake($kentat, $asetukset);
//$tarjouspyynto->form();
