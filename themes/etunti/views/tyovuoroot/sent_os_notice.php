<?php

/**
 * @var $error whether or not the email was sent successfully
 * @var $message error message
 */
?>

<div class="row">
    <div class="col-sm-12">
        <?php if ($error) : ?>
            <h3 class="text-danger">Virhe ilmoitusta tehdessä</h3>
            <p><?= $message ?></p>
        <?php endif; ?>

        <?php if (!$error) : ?>
            <h3 class="text-success">Sähköpostin lähetys onnistui!</h3>
            <p>
                Tuttusiistijä ilmoitus on merkattu lähetetyksi työvuoroon.
                <br>
                Näet päivitetyt tiedot työvuoron lomakkeella vasta kun avaat sen uudestaan.
                <br>
                Voit sulkea tämän välilehden.
            </p>
        <?php endif; ?>
    </div>
</div>