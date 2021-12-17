<?php
/* @var $this KohteetController */
/* @var $properties Array of Kohteet models */
/* @var $catalogue_names Array of catalogue names (only ID 3 and 4) */
/* @var $already_checked Array of already checked propertyies [some_prop_id => CheckedCatalogue, ...]*/

function printProperty($checked_row) {
    if(isset($checked_row->property)) {
        return $checked_row->property->osoite;
    }
    return "???";
}

function printUser($checked_row) {
    if(isset($checked_row->user)) {
        return $checked_row->user->adm_nimi;
    }
    return "???";
}

function printCatalogue($checked_row, $catalogues) {
    if(isset($checked_row->property)) {
        return $catalogues[$checked_row->property->hinnasto_id];
    }
    return "???";
}

function editId($checked_row) {
    if(isset($checked_row->property)) {
        return $checked_row->property->id;
    }
    return 0;
}


?>

<div class="tray-center">

    <table class="table">
        <tr>
            <th>Luonti pvm</th>
            <th>Hinnasto</th>
            <th>Osoite</th>
            <th></th>
        </tr>

        <?php foreach ($properties as $property): ?>
        <tr id="property-<?= $property->id ?>">
            <td>
                <?= $property->time ?>
            </td>
            <td>
                <?= $catalogue_names[$property->hinnasto_id] ?>
            </td>
            <td>
                <?= $property->osoite ?>
            </td>
            <td>
                <a href="/index.php/kohteet/update?id=<?= $property->id?>" target="_blank">Muokkaamaan</a>
            </td>
            <td>
                <button class="btn btn-primary approve-error" data-property="<?=$property->id?>">Merkkaa hyväksytyksi
                </button>
            </td>
        </tr>

        <?php endforeach; ?>
    </table>

    <h3>Hyväksytyt</h3>
    <table class="table">
        <tr>
            <th>
                Kohteen luonti pvm
            </th>
            <th>
                Hyväksytty
            </th>
            <th>
                Hyväksyjä
            </th>
            <th>
                Hinnasto
            </th>
            <th>
                Osoite
            </th>
            <th></th>
            <th></th>
        </tr>
        <?php foreach($already_checked as $checked) : ?>
        <tr id="checked-<?= $checked->id ?>">
            <td>
                <?= $checked->property->time ?>
            </td>
            <td>
                <?= $checked->created_at ?>
            </td>
            <td>
                <?= printUser($checked) ?>
            </td>
            <td>
                <?= printCatalogue($checked, $catalogue_names) ?>
            </td>
            <td>
                <?= printProperty($checked) ?>
            </td>
            <td>
                <a href="/index.php/kohteet/update?id=<?= editId($checked) ?>" target="_blank">Muokkaamaan</a>
            </td>
            <td>
                <button class="btn btn-primary disapprove-error" data-id="<?= $checked->id ?>">Poista
                    hyväksyntä</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<script>
$(".approve-error").click((e) => {
    const target = $(e.target);
    const data = target.data();
    $.ajax({
        url: "/index.php/kohteet/markcheckedcatalogue?property_id=" + data.property,
        type: "POST",
        success: (response) => {
            $("#property-" + data.property).hide();
        },
        error: (err) => {
            console.warn("Error while marking", err);
        }
    })
});

$(".disapprove-error").click(e => {
    const target = $(e.target);
    const data = target.data();
    $.ajax({
        url: "/index.php/kohteet/unmarkcheckedcatalogue?checked_id=" + data.id,
        type: "POST",
        success: (response) => {
            $("#checked-" + data.id).hide();
        },
        error: (err) => {
            console.warn("Error while unmarking", err);
        }
    })
});
</script>