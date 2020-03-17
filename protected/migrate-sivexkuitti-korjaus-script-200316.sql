/*
 * SELECT SCRIPT(S):
 *
 * Select the current value, and to-be-modified value for aloitan and loppui columns.
 * This has been run, and the output is saved on the new server, in case it has to be reverted.
 * The modifications are saved in my home directory (~dxo/...).
 */

/*
SELECT
    aloitan, DATE_FORMAT(DATE_ADD(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'), INTERVAL 2 HOUR), '%d.%m.%Y %H:%i:%s') as aloitan_mod,
    loppui, IF(TIME(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')) < '08:33:00', DATE_FORMAT(DATE_ADD(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s'), INTERVAL 2 HOUR), '%d.%m.%Y %H:%i:%s'), loppui) as loppui_mod
FROM sivexkuitti
WHERE aloitan!=''
    AND loppui!=''
    AND STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s') > '2020-03-16'
    AND STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s') BETWEEN DATE_SUB(time, INTERVAL 1 MINUTE) AND DATE_ADD(time, INTERVAL 1 MINUTE)
ORDER BY time DESC;
*/


/*
 * SELECT SCRIPT: One line:
 * Same query as before, but in one line for easier copy-pasta into a terminal.
 */

/*
SELECT aloitan, DATE_FORMAT(DATE_ADD(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'), INTERVAL 2 HOUR), '%d.%m.%Y %H:%i:%s') as aloitan_new, loppui, IF(TIME(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')) < '08:33:00', DATE_FORMAT(DATE_ADD(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s'), INTERVAL 2 HOUR), '%d.%m.%Y %H:%i:%s'), loppui) as loppui_new FROM sivexkuitti WHERE aloitan!='' AND loppui!='' AND STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s') > '2020-03-16' AND STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s') BETWEEN DATE_SUB(time, INTERVAL 1 MINUTE) AND DATE_ADD(time, INTERVAL 1 MINUTE) ORDER BY time DESC;
*/


/*
 * UPDATE SCRIPT: Caution with this.
 *
 * This has been ran on all domains on 16.03.2020 at approx 14pm.
 * See previous select queries for what is to be changed, should this script be run.
 * There is no longer any point in running this, it changes 0 rows, but it's here for safekeeping.
 */

/*
UPDATE sivexkuitti SET
    aloitan = DATE_FORMAT(DATE_ADD(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'), INTERVAL 2 HOUR), '%d.%m.%Y %H:%i:%s'),
    loppui = IF(TIME(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')) < '08:33:00', DATE_FORMAT(DATE_ADD(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s'), INTERVAL 2 HOUR), '%d.%m.%Y %H:%i:%s'), loppui)
WHERE aloitan!=''
    AND loppui!=''
    AND STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s') > '2020-03-16'
    AND STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s') BETWEEN DATE_SUB(time, INTERVAL 1 MINUTE) AND DATE_ADD(time, INTERVAL 1 MINUTE);
*/
