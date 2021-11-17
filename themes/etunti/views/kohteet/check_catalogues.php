<?php
/* @var $this KohteetController */
/* @var $properties Array of Kohteet models */
/* @var $catalogue_names Array of catalogue names (only ID 3 and 4) */



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
        <tr>
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
        </tr>

        <?php endforeach; ?>
    </table>
</div>