<?php 
/** @var $employee employee model as an array, expects and uses only the "kortit" field. */ 
// kortit separator from actionUpdate ##***
// example of a kortit field with all selections in kotipuhtaaksi domain
// kortti_Ajokortti##***kortti_Auto##***kortti_Hygieniapassi##***kortti_EA-passi##***kortti_Englanti##***kortti_Suomi
$cards = [];
if(isset($employee["kortit"])) {
    $cards = explode("##***", $employee["kortit"]);
}

$iconBasePath = Yii::app()->request->baseUrl . "/assets_etunti/assets/img/icons/";

// all known icons for kotipuhtaaksi
$iconMap = [
    "kortti_Auto" => $iconBasePath .  "have-car.svg",
    "kortti_Ajokortti" => $iconBasePath . "driving-license.svg",
    "kortti_Englanti" => $iconBasePath . "english.svg",
    "kortti_Suomi" => $iconBasePath . "finnish.svg",
    "kortti_Hygieniapassi" => $iconBasePath . "hygiene-passport.svg",
    "kortti_EA-passi" => $iconBasePath . "first-aid.svg"
];

?>


<?php foreach($cards as $card) : ?>
    <?php 
        // every card is prefixed with kortti_
        $cardName = substr($card, 7);
    ?>

    <span data-container="body" title="" data-original-title="<?=$cardName ?>" 
        data-toggle="tooltip">
    <?php 
        // if an icon is defined for this card, use that
        if(isset($iconMap[$card])) {
            echo CHtml::image($iconMap[$card]);
        } else {
            // no icon defined, use first letter of the card name
            // converted to uppercase
            echo strtoupper(substr($cardName, 0, 1));
        }
    ?>
</span>

<?php endforeach; ?>