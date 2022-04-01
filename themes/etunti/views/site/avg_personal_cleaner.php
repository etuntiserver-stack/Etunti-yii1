<?php
/**
 * @var $results
 * @var $workGroupMap Map of Valikkoot 'tyoryhma' where id points to the value [6 => "Uusimaa", ...] for example.
 */

function grpName($grpId, $grps) {
    if(isset($grps[$grpId])) {
        return $grps[$grpId];
    }
    return "Ei työryhmää";
}
?>

<?php foreach ($results as $grpId => $avg): ?>
<p>
    <?= grpName($grpId, $workGroupMap); ?>
    = <?=$avg?>
</p>
<?php endforeach;?>