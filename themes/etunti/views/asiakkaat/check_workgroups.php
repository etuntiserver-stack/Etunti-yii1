<?php

/* @var $this KohteetController */
/* @var $missingDataClients Array of Asiakkaat models */
/* @var $wrongDataClients Array of Asiakkaat models */
/* @var $workgroupNames Map of work group names [groupId => groupName, ...] */

?>

<div class="tray-center">
    <h1>Asiakkaat joilta puuttuu työryhmä tai kustannuspaikka</h1>
    <table class="table">
        <tr>
            <th>Nimi</th>
            <th>Asiakasnumero</th>
            <th>Työryhmä</th>
            <th>Kustannuspaikka</th>
            <th></th>
        </tr>
        <?php foreach ($missingDataClients as $client) : ?>
            <tr>
                <td><?= $client->Etusukunimi ?></td>
                <td><?= $client->asiakasnumero ?></td>
                <td><?= $workgroupNames[$client->tyoryhma] ?? "?" ?></td>
                <td><?= $client->netvisor_dimension_item ?></td>
                <td><a target="_blank" href="/index.php/asiakkaat/update?id=<?= $client->id ?>">Muokkaamaan</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <h1>Asiakkaat joilla väärä työryhmä kustannuspaikkaan verrattuna</h1>
    <table class="table">
        <tr>
            <th>Nimi</th>
            <th>Asiakasnumero</th>
            <th>Työryhmä</th>
            <th>Kustannuspaikka</th>
            <th></th>
        </tr>
        <?php foreach ($wrongDataClients as $client) : ?>
            <tr>
                <td><?= $client->Etusukunimi ?></td>
                <td><?= $client->asiakasnumero ?></td>
                <td><?= $workgroupNames[$client->tyoryhma] ?? "?" ?></td>
                <td><?= $client->netvisor_dimension_item ?></td>
                <td><a target="_blank" href="/index.php/asiakkaat/update?id=<?= $client->id ?>">Muokkaamaan</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>