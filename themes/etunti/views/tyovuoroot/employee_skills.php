<?php
// renders employees name with their language skills as icons under the name
// @var $employees Array of employees
?>

<div>
    <?php foreach($employees as $employee) : ?>
        <?php 
            $cards = [];
            if(isset($employee["kortit"])) {
                $cards = explode("##***", $employee["kortit"]);
            }
            // we're only really interested in the language skills here
            $cards = array_intersect($cards, ["kortti_Suomi", "kortti_Englanti"]);
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
        <div>
            <p style="margin-bottom: 0"><?= $employee->tekijan_nimi . " " . $employee->sukunimi ?></p>
            <?php if(count($cards) === 0) : ?>
                <p>Ei merkattuja kielitaitoja</p>
            <?php endif; ?>
            <?php foreach($cards as $card) : ?>
                <?php
                    // every card is prefixed with kortti_ 
                    $cardName = substr($card, 7); 
                ?>
                <span data-container="body" class="skill-tooltip" title="" data-original-title="<?=$cardName ?>" 
                    data-toggle="tooltip">
                
                    <?php if(isset($iconMap[$card])) {
                        echo CHtml::image($iconMap[$card]);
                    } else {
                        // if there's no icon defined, show the first letter in 
                        // uppercase instead
                        echo strtoupper(substr($cardName, 0, 1)); 
                    }
                    ?>
                </span>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?> 
    <script>
        // init tooltips
        $(function() {
            $(".skill-tooltip").tooltip();
        });
    </script>
</div>