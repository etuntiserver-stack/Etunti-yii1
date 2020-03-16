/* NEW */
SELECT
    aloitan, DATE_FORMAT(DATE_ADD(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'), INTERVAL 2 HOUR), '%d.%m.%Y %H:%i:%s') as aloitan_mod,
    loppui, IF(TIME(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')) < '08:33:00', DATE_FORMAT(DATE_ADD(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s'), INTERVAL 2 HOUR), '%d.%m.%Y %H:%i:%s'), loppui) as loppui_mod
FROM sivexkuitti
WHERE aloitan!=''
    AND loppui!=''
    AND STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s') > '2020-03-16'
    AND STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s') BETWEEN DATE_SUB(time, INTERVAL 1 MINUTE) AND DATE_ADD(time, INTERVAL 1 MINUTE)
ORDER BY time DESC;

/* ONE LINE */
SELECT aloitan, DATE_FORMAT(DATE_ADD(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'), INTERVAL 2 HOUR), '%d.%m.%Y %H:%i:%s') as aloitan_new, loppui, IF(TIME(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')) < '08:33:00', DATE_FORMAT(DATE_ADD(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s'), INTERVAL 2 HOUR), '%d.%m.%Y %H:%i:%s'), loppui) as loppui_new FROM sivexkuitti WHERE aloitan!='' AND loppui!='' AND STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s') > '2020-03-16' AND STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s') BETWEEN DATE_SUB(time, INTERVAL 1 MINUTE) AND DATE_ADD(time, INTERVAL 1 MINUTE) ORDER BY time DESC;

/* NEW UPDATE */
UPDATE sivexkuitti SET
    aloitan = DATE_FORMAT(DATE_ADD(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'), INTERVAL 2 HOUR), '%d.%m.%Y %H:%i:%s'),
    loppui = IF(TIME(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')) < '08:33:00', DATE_FORMAT(DATE_ADD(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s'), INTERVAL 2 HOUR), '%d.%m.%Y %H:%i:%s'), loppui)
WHERE aloitan!='' AND loppui!='' AND STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s') > '2020-03-16' AND STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s') BETWEEN DATE_SUB(time, INTERVAL 1 MINUTE) AND DATE_ADD(time, INTERVAL 1 MINUTE);