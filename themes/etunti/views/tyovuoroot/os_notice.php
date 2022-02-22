<?php

/** 
 * @var $property Property (Kohteet) model
 * @var $client Client (Asiakkaat) model
 * @var $shift Shift (tyovuoroot) model
 * @var $date Date of the shift (d.m.Y format)
 * @var $starting_time Starting time of the shift (H:i format)
 * @var $ending_time Ending time of the shift (H:i format)
 * @var $email_subject Subject of the email template
 * @var $email_body Body of the email template
 */
$clientHasEmail = $client->no_email;
$client_email = trim($client->sahkoposti);
?>

<div class="row">
    <div class="col-sm-12">
        <h2>Tuttusiistijä ilmoitus</h2>
        <p>
            Olet ilmoittamassa estyneistä tuttusiistijöistä työvuorolle:<br>
            <strong>Asiakas:</strong> <span><?= $client->Etusukunimi ?></span><br>
            <strong>Sähköposti:</strong> <span><?= $client_email ?></span><br>
            <strong>Kohde:</strong> <span><?= $property->osoite ?> (<?= $property->pnumero ?>)<br>
            <strong>Aika:</strong> <span><?= $date ?> <?= $starting_time ?>-<?= $ending_time ?></span><br><br>
            <strong>Sähköposti:</strong>
        </p>

        <form action="/index.php/tyovuoroot/sendosnotice" method="post">
            <input type="hidden" name="shift_id" value="<?=$shift->id?>" />
            <?php 
            // I'll just an array of $to emails so we can more easily expand it later
            // if needed. YiiMailer also accepts arrays in ->setTo() method.
            ?>
            <input type="hidden" name="to[]" value="<?= $client_email ?>">
            <p><?= $email_subject ?></p>
            <input type="hidden" name="email-subject" value="<?= $email_subject?>" />
            <textarea name="email-body" style="width: 100%; height: 275px"><?=$email_body?></textarea>
            <p>Voit muokata yllä olevaa sähköposti pohjaa. 
                Sähköpostiin on automaattisesti lisätty seuraavan siivouskäynnin päivämäärä ja aika.<br>
                <i>Sähköpostiteksti tulee olla HTML -muodossa. Käytä &lt;br&gt; rivien lopussa rivivaihtona. 
                    Normaalit rivivaihdot tekstissä eivät vaikuta lopulliseen sähköpostiin.</i>
            </p>

            <p>
                Tämä teksti lisätään työvuoron "tietoja mobiilisovellukseen" kentän alkuun:
                <br>
                (Jos et halua lisätä mitään tekstiä, jätä alla oleva kenttä tyhjäksi)
            </p>
            <textarea style="width: 275px; heigth: 50px" name="additional-info-prefix">Tuttusiistijä ilmoitus tehty <?= date("d.m.Y")?></textarea>

            <br>
            <?php if(!$clientHasEmail) :?>
                <input type="submit" class="btn btn-primary" value="Lähetä ilmoitus">
            <?php endif; ?>
            <?php if($clientHasEmail) : ?>
                <p>Asiakkaalle ei ole määritetty sähköpostia
            <?php endif; ?>

        </form>

    </div>
</div>