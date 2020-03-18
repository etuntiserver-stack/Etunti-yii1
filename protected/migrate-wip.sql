/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

OPTIMIZE TABLE sivex_tvuoro;
OPTIMIZE TABLE toistuvat_tyovuorot;

SET @start_date = DATE_ADD(CURDATE(), INTERVAL 7 - (WEEKDAY(CURDATE())) DAY);

/* STEP 1: PUHDISTUS */

/* Poista tyhjät ketjut */
DELETE FROM toistuvat_tyovuorot WHERE id NOT IN (
    SELECT DISTINCT toistuva_id FROM sivex_tvuoro WHERE toistuva_id != 0
);

/* Korjaa tids (ketjun tid != työvuoron tid, kun työpari on tyhjä) */
UPDATE toistuvat_tyovuorot t
    INNER JOIN sivex_tvuoro s ON s.toistuva_id = t.id
    SET t.tid = s.tid
    WHERE ( t.tyopaari IS NULL or t.tyopaari IN ('', '[]') )
    AND t.tid != s.tid;

/* Muutetaan menneet toistuvat työvuorot tavallisiksi. */
UPDATE sivex_tvuoro SET toistuva_id = 0 WHERE
    DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) < @start_date;


/* STEP 2: Poistetaan tiedot, jota ei enää tarvita */

/* Poistetaan tulevaisuuden työvuorot jotka ovat osana ketjua. */
DELETE FROM sivex_tvuoro
    WHERE toistuva_id != 0
    AND DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) >= @start_date;

/* Poistetaan menneet ketjut. */
DELETE FROM toistuvat_tyovuorot WHERE
    DATE(STR_TO_DATE(pto, '%d.%m.%Y')) < @start_date;

/* Ei toimii kunnolla
UPDATE toistuvat_tyovuorot SET pfrom = (
    SELECT IF(
        viikkoja - FLOOR(DATEDIFF(@start_date, DATE(STR_TO_DATE(pfrom, '%d.%m.%Y'))) / 7) % viikkoja = viikkoja,
        @start_date,
        DATE_ADD(@start_date, INTERVAL 7 - (WEEKDAY(CURDATE())) + ((viikkoja - FLOOR(DATEDIFF(CURDATE(), DATE(STR_TO_DATE(pfrom, '%d.%m.%Y'))) / 7) % viikkoja) * 7) DAY)
        )
    FROM toistuvat_tyovuorot WHERE DATE(STR_TO_DATE(t.pfrom, '%d.%m.%Y')) < @start_date
)
*/


/*

SELECT id, viikkoja, pfrom,
    IF(viikkoja - FLOOR(DATEDIFF(@start_date, DATE(STR_TO_DATE(pfrom, '%d.%m.%Y'))) / 7) % viikkoja = viikkoja,
        @start_date,
        DATE_ADD(@start_date, INTERVAL 7 - (WEEKDAY(CURDATE())) + ((viikkoja - FLOOR(DATEDIFF(CURDATE(), DATE(STR_TO_DATE(pfrom, '%d.%m.%Y'))) / 7) % viikkoja) * 7) DAY)
    ) as dd FROM toistuvat_tyovuorot t WHERE DATE(STR_TO_DATE(t.pfrom, '%d.%m.%Y')) < '2019-12-30' AND viikkoja!=1 LIMIT 40;
*/




/* 

SELECT id FROM sivex_tvuoro WHERE
    DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) < CURDATE()
    AND toistuva_id IN (
        SELECT id FROM toistuvat_tyovuorot t WHERE
            DATE(STR_TO_DATE(t.pfrom, '%d.%m.%Y')) < CURDATE()
            AND DATE(STR_TO_DATE(t.pto, '%d.%m.%Y')) > CURDATE()
    ); */
