

CREATE TABLE `asetukset` (
  `id` int(1) NOT NULL,
  `syntyrin_emails` text NOT NULL,
  `paivan_uutinen` varchar(500) NOT NULL,
  `logon_polkku` varchar(500) NOT NULL,
  `logon_korkeus` int(4) NOT NULL,
  `johtaja` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;






CREATE TABLE `asiakkaat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `yrityksen_nimi` varchar(100) NOT NULL,
  `y_tunnus` varchar(50) NOT NULL,
  `yhteyshenkilo` varchar(100) NOT NULL,
  `osoite` varchar(255) NOT NULL,
  `kaupunki` varchar(100) NOT NULL,
  `postinumero` varchar(100) NOT NULL,
  `puhelin` varchar(100) NOT NULL,
  `sahkoposti` varchar(100) NOT NULL,
  `ryhma` int(2) NOT NULL,
  `aktiivinen` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=614 DEFAULT CHARSET=latin1;






CREATE TABLE `chatbox` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kuka` varchar(100) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `text` varchar(500) CHARACTER SET utf8 NOT NULL,
  `kenelle` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=latin1;






CREATE TABLE `chatbox_kirjoittaja` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(50) NOT NULL,
  `time` int(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;






CREATE TABLE `laskun_rivit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lid` int(11) NOT NULL,
  `rivi` int(11) NOT NULL,
  `tkoodi` int(11) NOT NULL,
  `nimike` varchar(100) NOT NULL,
  `kpl` int(11) NOT NULL,
  `yksikko` varchar(20) NOT NULL,
  `hinta` varchar(20) NOT NULL,
  `alv` varchar(20) NOT NULL,
  `hinta_alv` varchar(20) NOT NULL,
  `ale` varchar(20) NOT NULL,
  `veroton` varchar(20) NOT NULL,
  `yhteensa_alv` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=240 DEFAULT CHARSET=latin1;






CREATE TABLE `laskut` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lid` int(11) NOT NULL,
  `yid` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tyyppi` varchar(100) NOT NULL,
  `yritys` varchar(100) NOT NULL,
  `y_tunnus` varchar(50) NOT NULL,
  `nimi` varchar(100) NOT NULL,
  `as_nro` int(11) NOT NULL,
  `osoite` varchar(255) NOT NULL,
  `postinumero` varchar(10) NOT NULL,
  `toimipaikka` varchar(50) NOT NULL,
  `laskutus` varchar(50) NOT NULL,
  `sahkoposti` varchar(100) NOT NULL,
  `verkkolaskuosoite` varchar(255) NOT NULL,
  `v_tunnus` varchar(100) NOT NULL,
  `yhteyshenkilo` varchar(100) NOT NULL,
  `nimitarkenne` varchar(100) NOT NULL,
  `puhelin` varchar(50) NOT NULL,
  `t_yritys` varchar(100) NOT NULL,
  `t_y_tunnus` varchar(50) NOT NULL,
  `t_nimi` varchar(100) NOT NULL,
  `t_osoite` varchar(100) NOT NULL,
  `t_postinumero` varchar(10) NOT NULL,
  `t_toimipaikka` varchar(100) NOT NULL,
  `t_puhelin` varchar(50) NOT NULL,
  `t_sahkoposti` varchar(100) NOT NULL,
  `toimitusosoite` varchar(100) NOT NULL,
  `paivays` varchar(20) NOT NULL,
  `erapaiva` varchar(20) NOT NULL,
  `toimituspaiva` varchar(20) NOT NULL,
  `maksuehto` varchar(20) NOT NULL,
  `viitenumero` varchar(100) NOT NULL,
  `viivastyskorko` varchar(50) NOT NULL,
  `yhteensa_total_verot` varchar(20) NOT NULL,
  `yhteensa_total_veroton` varchar(20) NOT NULL,
  `yhteensa_total` varchar(20) NOT NULL,
  `saaja_iban` varchar(100) NOT NULL,
  `saaja_virtualkoodi` varchar(255) NOT NULL,
  `tilanne` varchar(50) NOT NULL,
  `maksettu_euro` varchar(100) NOT NULL,
  `hyvityslasku` varchar(20) NOT NULL,
  `laskun_nimetys` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;






CREATE TABLE `laskutus_tuotteet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tuotenimi` varchar(100) NOT NULL,
  `hinta_alv_0` varchar(20) NOT NULL,
  `hinta_alv_sis` varchar(20) NOT NULL,
  `alv` varchar(10) NOT NULL,
  `yksikko` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;






CREATE TABLE `laskutus_uusi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` varchar(100) NOT NULL DEFAULT '0',
  `erapaiva` varchar(50) NOT NULL,
  `korkoprosentti` varchar(50) NOT NULL,
  `asiakkaan_id` int(11) NOT NULL,
  `asiakkaan_tiedot` text NOT NULL,
  `viitenumero` varchar(50) NOT NULL,
  `laskun_paivays` varchar(50) NOT NULL,
  `tilinumero` varchar(100) NOT NULL,
  `iban` varchar(50) NOT NULL,
  `bic` varchar(20) NOT NULL,
  `saaja` text NOT NULL,
  `euro` varchar(10) NOT NULL,
  `tuote` text NOT NULL,
  `logon_polkku` varchar(255) NOT NULL,
  `logon_korkeus` int(3) NOT NULL,
  `tilanne` varchar(100) NOT NULL,
  `ajanjakso` varchar(100) NOT NULL,
  `maaramuoto` varchar(100) NOT NULL,
  `maksettu` varchar(100) NOT NULL,
  `y_tunnus` varchar(100) NOT NULL,
  `saajan_puh` varchar(100) NOT NULL,
  `saajan_email` varchar(100) NOT NULL,
  `viivkorko_pvm` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=latin1;






CREATE TABLE `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `text` text NOT NULL,
  `kuka` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35351 DEFAULT CHARSET=latin1;






CREATE TABLE `omat_muistutukset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ratkaisija` int(11) NOT NULL,
  `luoja` varchar(100) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `text` text NOT NULL,
  `status` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;






CREATE TABLE `omat_muistutukset_roskis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ratkaisija` int(11) NOT NULL,
  `luoja` varchar(100) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `text` text NOT NULL,
  `status` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;






CREATE TABLE `pvk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `kohdenID` int(11) NOT NULL,
  `tarjous` text NOT NULL,
  `hyvaksyn_koodi` varchar(255) NOT NULL,
  `asiakkaan_sahkoposti` varchar(100) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;






CREATE TABLE `pvk_rivit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `col_1` text NOT NULL,
  `col_2` text NOT NULL,
  `col_3` text NOT NULL,
  `col_4` text NOT NULL,
  `col_5` text NOT NULL,
  `col_6` text NOT NULL,
  `col_7` text NOT NULL,
  `col_8` text NOT NULL,
  `col_9` text NOT NULL,
  `col_10` text NOT NULL,
  `col_11` text NOT NULL,
  `col_12` text NOT NULL,
  `col_13` text NOT NULL,
  `col_14` text NOT NULL,
  `col_15` text NOT NULL,
  `col_16` text NOT NULL,
  `col_17` text NOT NULL,
  `col_18` text NOT NULL,
  `col_19` text NOT NULL,
  `col_20` text NOT NULL,
  `col_21` text NOT NULL,
  `col_22` text NOT NULL,
  `col_23` text NOT NULL,
  `col_24` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;






CREATE TABLE `sivex_administrators` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `adm_login` varchar(100) NOT NULL,
  `adm_salasana` varchar(100) NOT NULL,
  `adm_email` varchar(100) NOT NULL,
  `adm_nimi` varchar(100) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;






CREATE TABLE `sivex_ennakko` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pvm` varchar(20) NOT NULL,
  `syy` varchar(100) NOT NULL,
  `ennakko` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;






CREATE TABLE `sivex_kirjallinen_varoitus` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `key` int(1) NOT NULL DEFAULT '2',
  `tyonantaja` varchar(70) NOT NULL,
  `osoite` varchar(255) NOT NULL,
  `postinumero` varchar(7) NOT NULL,
  `postitoimipaikka` varchar(100) NOT NULL,
  `puhelin` varchar(50) NOT NULL,
  `y_tunnus` varchar(50) NOT NULL,
  `sahkoposti` varchar(100) NOT NULL,
  `tekijan_email` varchar(100) NOT NULL,
  `tid` int(7) NOT NULL,
  `tekijan_nimi` varchar(70) NOT NULL,
  `tekijan_katuosoite` varchar(100) NOT NULL,
  `tekijan_pnumero` varchar(7) NOT NULL,
  `tekijan_ptoimipaikka` varchar(50) NOT NULL,
  `tekijan_puh` varchar(50) NOT NULL,
  `tekijan_henkilotunnus` varchar(50) NOT NULL,
  `kirjallisen_varoituksen` text NOT NULL,
  `Paivays` varchar(50) NOT NULL,
  `Paikka` varchar(100) NOT NULL,
  `TyonantajanEdustaja` varchar(100) NOT NULL,
  `NimikeTehtava` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;






CREATE TABLE `sivex_kohdet` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `asiakas_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tag_id` varchar(20) NOT NULL,
  `gps_sijainti` varchar(50) NOT NULL,
  `lyhenne` varchar(46) DEFAULT NULL,
  `osoite` varchar(50) DEFAULT NULL,
  `katuosoite` varchar(50) DEFAULT NULL,
  `kaupunki` varchar(20) DEFAULT NULL,
  `toimipaikka` varchar(20) DEFAULT NULL,
  `pnumero` varchar(7) DEFAULT NULL,
  `email` varchar(72) DEFAULT NULL,
  `aikataulu` text,
  `hinnoittelu` text,
  `muut` text,
  `toimenpiteet` longtext,
  `tietoja` text,
  `tyoryhma` varchar(20) DEFAULT NULL,
  `ryhma` varchar(10) DEFAULT NULL,
  `aktiivinen` int(1) DEFAULT NULL,
  `avain` varchar(255) NOT NULL,
  `kenella_on_avain` varchar(50) NOT NULL,
  `puh_nro` varchar(50) NOT NULL,
  `siivous` varchar(100) NOT NULL,
  `etu_suku_nimet` varchar(100) NOT NULL,
  `maksuehto_paiva` int(2) NOT NULL,
  `viivastyskorko` varchar(10) NOT NULL,
  `lasku_tiedot` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `osoite` (`osoite`),
  KEY `avain` (`avain`)
) ENGINE=InnoDB AUTO_INCREMENT=31130 DEFAULT CHARSET=utf8;






CREATE TABLE `sivex_kohdet_notuse` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tag_id` varchar(20) NOT NULL,
  `osoite` varchar(42) DEFAULT NULL,
  `pnumero` varchar(7) NOT NULL,
  `kaupunki` varchar(19) DEFAULT NULL,
  `gps` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `aikataulu` text NOT NULL,
  `hinnoittelu` text NOT NULL,
  `muut` text NOT NULL,
  `toimenpiteet` text NOT NULL,
  `tietoja` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=926 DEFAULT CHARSET=utf8;






CREATE TABLE `sivex_korvaukset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pvm` varchar(20) NOT NULL,
  `syy` varchar(255) NOT NULL,
  `korvaus` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;






CREATE TABLE `sivex_lahetaminen` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `yhden_paivan` varchar(50) NOT NULL,
  `viikko` varchar(7) NOT NULL,
  `tid` int(7) NOT NULL,
  `lahettaja` varchar(50) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;






CREATE TABLE `sivex_laskut` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `kohdenID` int(11) NOT NULL,
  `trtd` longtext NOT NULL,
  `trtd7` longtext NOT NULL,
  `mista` varchar(20) NOT NULL,
  `mihin` varchar(20) NOT NULL,
  `yhteensa` varchar(20) NOT NULL,
  `muoto` varchar(10) NOT NULL,
  `asiakkaan_koodi` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;






CREATE TABLE `sivex_lisatyot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pvm` varchar(20) NOT NULL,
  `syy` varchar(255) NOT NULL,
  `prosentti` varchar(10) NOT NULL,
  `tunnimaara` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;






CREATE TABLE `sivex_palautteet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pvm` varchar(20) NOT NULL,
  `admin` varchar(50) NOT NULL,
  `asiakas_etusuku` varchar(100) NOT NULL,
  `asiakas_email` varchar(100) NOT NULL,
  `asiakas_puh` varchar(100) NOT NULL,
  `asiakas_palaute` text NOT NULL,
  `tehtty` int(1) NOT NULL,
  `asiakas_osoite` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;






CREATE TABLE `sivex_selects` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `value` varchar(255) NOT NULL,
  `select_type` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=latin1;






CREATE TABLE `sivex_suhteen_paattaminen` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `key` int(1) NOT NULL DEFAULT '2',
  `tyonantaja` varchar(70) NOT NULL,
  `osoite` varchar(255) NOT NULL,
  `postinumero` varchar(7) NOT NULL,
  `postitoimipaikka` varchar(100) NOT NULL,
  `puhelin` varchar(50) NOT NULL,
  `y_tunnus` varchar(50) NOT NULL,
  `sahkoposti` varchar(100) NOT NULL,
  `tekijan_email` varchar(100) NOT NULL,
  `tid` int(7) NOT NULL,
  `tekijan_nimi` varchar(70) NOT NULL,
  `tekijan_katuosoite` varchar(100) NOT NULL,
  `tekijan_pnumero` varchar(7) NOT NULL,
  `tekijan_ptoimipaikka` varchar(50) NOT NULL,
  `tekijan_puh` varchar(50) NOT NULL,
  `tekijan_henkilotunnus` varchar(50) NOT NULL,
  `Paivays` varchar(50) NOT NULL,
  `Paikka` varchar(100) NOT NULL,
  `TyonantajanEdustaja` varchar(100) NOT NULL,
  `NimikeTehtava` varchar(100) NOT NULL,
  `TyosuhteenAlkamispaiva` varchar(50) NOT NULL,
  `NoudatettavaIrtisanomisaika` varchar(100) NOT NULL,
  `TyontekijaaOn` varchar(50) NOT NULL,
  `tilaisuus` varchar(50) NOT NULL,
  `olevasta` varchar(100) NOT NULL,
  `TyontekijalleOn` varchar(50) NOT NULL,
  `Selite` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;






CREATE TABLE `sivex_syntarit` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `tid` int(7) NOT NULL,
  `date` varchar(20) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=latin1;






CREATE TABLE `sivex_tarjoukset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `kohdenID` int(11) NOT NULL,
  `tarjous` text NOT NULL,
  `hyvaksyn_koodi` varchar(255) NOT NULL,
  `asiakkaan_sahkoposti` varchar(100) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;






CREATE TABLE `sivex_tehtavat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pvm` varchar(20) NOT NULL,
  `admin` varchar(50) NOT NULL,
  `tehtty` int(1) NOT NULL,
  `etu_suku_nimet` varchar(100) NOT NULL,
  `lahiosoitee` varchar(255) NOT NULL,
  `postinumero` varchar(5) NOT NULL,
  `postitoimipaikka` varchar(255) NOT NULL,
  `puhelin` varchar(255) NOT NULL,
  `sahkoposti` varchar(255) NOT NULL,
  `sopimus_alkaa` varchar(255) NOT NULL,
  `hinta_yllapitasiivous` varchar(255) NOT NULL,
  `avain` varchar(255) NOT NULL,
  `ovikoodi` varchar(255) NOT NULL,
  `siivouspaiva` varchar(255) NOT NULL,
  `tiheys` varchar(255) NOT NULL,
  `aika_siivoukselle` varchar(255) NOT NULL,
  `keittio` varchar(255) NOT NULL,
  `kylpyhuone` varchar(255) NOT NULL,
  `olohuone` varchar(255) NOT NULL,
  `makuuhuone` varchar(255) NOT NULL,
  `eteinen` varchar(255) NOT NULL,
  `muut_toivoukset` varchar(255) NOT NULL,
  `neliomaara` int(5) NOT NULL,
  `huonemaara` int(5) NOT NULL,
  `wcmaara` int(5) NOT NULL,
  `ikkunamaara` int(5) NOT NULL,
  `muut` text NOT NULL,
  `perussiivoushinta` varchar(20) NOT NULL,
  `hinnannuosu` varchar(100) NOT NULL,
  `mitoitus_aika` varchar(100) NOT NULL,
  `suositukset_kpl` varchar(100) NOT NULL,
  `aineet_valineet` varchar(100) NOT NULL,
  `aineet_hinta` varchar(50) NOT NULL,
  `palauteet` text NOT NULL,
  `puhelin2` varchar(50) NOT NULL,
  `y_nimi` varchar(100) NOT NULL,
  `y_tunnus` varchar(50) NOT NULL,
  `sopimus_paattyy` varchar(50) NOT NULL,
  `syy_sop_paattyy` varchar(100) NOT NULL,
  `perussiivous` varchar(100) NOT NULL,
  `perussiivous_milloin` varchar(100) NOT NULL,
  `keneen_valineet` varchar(100) NOT NULL,
  `siivousvaunut` varchar(10) NOT NULL,
  `imurit` varchar(10) NOT NULL,
  `yhdistelmakoneet` varchar(10) NOT NULL,
  `pesukoneet` varchar(10) NOT NULL,
  `kuivauskoneet` varchar(10) NOT NULL,
  `lattiamopit` varchar(10) NOT NULL,
  `lattipyyheet` varchar(10) NOT NULL,
  `kuivaimet` varchar(10) NOT NULL,
  `lattiaharjat` varchar(10) NOT NULL,
  `kasiharjat` varchar(20) NOT NULL,
  `ikkunanpesusetti` varchar(20) NOT NULL,
  `siivouspyyheet` varchar(20) NOT NULL,
  `sangot` varchar(20) NOT NULL,
  `muut_valineet` varchar(255) NOT NULL,
  `yleispudistuskemikaalit` varchar(20) NOT NULL,
  `lattianpuhdistuskemikaalit` varchar(20) NOT NULL,
  `san_kemikaalit` varchar(20) NOT NULL,
  `ikkunanpuhdistuskemikaalit` varchar(20) NOT NULL,
  `muut_kemikaalit` varchar(255) NOT NULL,
  `wc_paperi` varchar(20) NOT NULL,
  `kasipaperi` varchar(20) NOT NULL,
  `jatesakit` varchar(20) NOT NULL,
  `saippuat` varchar(20) NOT NULL,
  `tiskijauheet` varchar(20) NOT NULL,
  `muut_hankit` varchar(100) NOT NULL,
  `kaytavat_tuulikaappi` varchar(512) NOT NULL,
  `toimisto` varchar(512) NOT NULL,
  `neuvotteluhuone` varchar(512) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;






CREATE TABLE `sivex_ttekijat` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `imei` varchar(30) NOT NULL,
  `laiten_puh` varchar(100) NOT NULL,
  `tekijan_nimi` varchar(100) NOT NULL,
  `tekijan_henkilotunnus` varchar(20) NOT NULL,
  `tekijan_puh` varchar(20) NOT NULL,
  `tekijan_email` varchar(50) NOT NULL,
  `tekijan_lanka_puh` varchar(20) NOT NULL,
  `tekijan_katuosoite` varchar(100) NOT NULL,
  `tekijan_pnumero` varchar(7) NOT NULL,
  `tekijan_ptoimipaikka` varchar(50) NOT NULL,
  `tyoryhma` varchar(20) NOT NULL,
  `tyoehtosopimus` varchar(50) NOT NULL,
  `tekijan_kulunvalvonta` varchar(50) NOT NULL,
  `tekijan_pankkitili` varchar(100) NOT NULL,
  `tekijan_konttori` varchar(50) NOT NULL,
  `aktiivinen` varchar(50) NOT NULL,
  `tekijan_tietoja` text NOT NULL,
  `tekijan_muisti` text NOT NULL,
  `salasana` varchar(100) NOT NULL,
  `online_varauksen_valmina` int(1) NOT NULL DEFAULT '0',
  `kortit` text NOT NULL,
  `ayjasenyys` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tekijan_nimi` (`tekijan_nimi`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=latin1;






CREATE TABLE `sivex_tvuoro` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `tid` int(7) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `kohde` varchar(255) NOT NULL,
  `pvm` varchar(20) NOT NULL,
  `alku` varchar(10) NOT NULL,
  `loppu` varchar(10) NOT NULL,
  `pituus` varchar(10) NOT NULL,
  `ruokatauko` varchar(50) NOT NULL,
  `alku_r` varchar(10) NOT NULL,
  `kesto` varchar(10) NOT NULL,
  `tyoajanlaatu` varchar(50) NOT NULL,
  `tyoajanmerkinta` varchar(50) NOT NULL,
  `tietoja` text NOT NULL,
  `osoiteOnline` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`),
  KEY `kohde` (`kohde`),
  KEY `pvm` (`pvm`),
  KEY `alku` (`alku`),
  KEY `loppu` (`loppu`),
  KEY `pituus` (`pituus`)
) ENGINE=InnoDB AUTO_INCREMENT=7183 DEFAULT CHARSET=latin1;






CREATE TABLE `sivex_tyonantaja` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `tyonantaja` varchar(50) NOT NULL,
  `osoite` varchar(100) NOT NULL,
  `postinumero` varchar(50) NOT NULL,
  `postitoimipaikka` varchar(50) NOT NULL,
  `puhelin` varchar(100) NOT NULL,
  `y_tunnus` varchar(50) NOT NULL,
  `sahkoposti` varchar(100) NOT NULL,
  `tilinumero` varchar(100) NOT NULL,
  `iban` varchar(100) NOT NULL,
  `bic` varchar(20) NOT NULL,
  `johtaja` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;






CREATE TABLE `sivex_tyosopimukset` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `key` int(1) NOT NULL DEFAULT '1',
  `tyonantaja` varchar(70) NOT NULL,
  `osoite` varchar(255) NOT NULL,
  `postinumero` varchar(7) NOT NULL,
  `postitoimipaikka` varchar(100) NOT NULL,
  `puhelin` varchar(50) NOT NULL,
  `y_tunnus` varchar(50) NOT NULL,
  `sahkoposti` varchar(100) NOT NULL,
  `tekijan_email` varchar(100) NOT NULL,
  `tid` int(7) NOT NULL,
  `tekijan_nimi` varchar(70) NOT NULL,
  `tekijan_katuosoite` varchar(100) NOT NULL,
  `tekijan_pnumero` varchar(7) NOT NULL,
  `tekijan_ptoimipaikka` varchar(50) NOT NULL,
  `tekijan_puh` varchar(50) NOT NULL,
  `tekijan_henkilotunnus` varchar(50) NOT NULL,
  `sopimus` varchar(50) NOT NULL,
  `ToistaVoimaSopimus` varchar(100) NOT NULL,
  `MaaraVoimaSopimusAlkaa` varchar(100) NOT NULL,
  `MaaraVoimaSopimusPaattyy` varchar(100) NOT NULL,
  `peruste` text NOT NULL,
  `koeaika` varchar(100) NOT NULL,
  `SoveltavaSopimus` varchar(100) NOT NULL,
  `Tyotehtavat` text NOT NULL,
  `tyonSuorittamisPaikka` text NOT NULL,
  `PalkanMaaraytymisperuste` varchar(50) NOT NULL,
  `PalkanMaaraytymisperusteMuu` varchar(100) NOT NULL,
  `TyokokemusVuotta` varchar(20) NOT NULL,
  `TyokokemusKuu` varchar(20) NOT NULL,
  `palkka_kk` varchar(20) NOT NULL,
  `Palkkaluokka` varchar(50) NOT NULL,
  `palkka_h` varchar(20) NOT NULL,
  `Luontaiseudut` text NOT NULL,
  `Raha_arvo` varchar(70) NOT NULL,
  `Verotusarvo` varchar(70) NOT NULL,
  `palkka_muu2` varchar(70) NOT NULL,
  `Palkanmaksukausi` varchar(50) NOT NULL,
  `Palkanmaksupaivat` varchar(50) NOT NULL,
  `Palkka_tilille` varchar(100) NOT NULL,
  `tyoaika_hvrk` varchar(50) NOT NULL,
  `tyoaika_hvko` varchar(50) NOT NULL,
  `tyoaika_h_jakso` varchar(50) NOT NULL,
  `tyoaika_vko_jaksossa` varchar(50) NOT NULL,
  `RuokataukonPituus` varchar(50) NOT NULL,
  `Muu_tyoaika` text NOT NULL,
  `lomasta_sovittu` text NOT NULL,
  `Salassapito` text NOT NULL,
  `IrtisanomisaikaM` varchar(50) NOT NULL,
  `Muut_sopimusehdot` text NOT NULL,
  `Muutospaiva` varchar(50) NOT NULL,
  `LisayksetSopimukseen` text NOT NULL,
  `Paivays` varchar(50) NOT NULL,
  `Paikka` varchar(100) NOT NULL,
  `TyonantajanEdustaja` varchar(100) NOT NULL,
  `NimikeTehtava` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;






CREATE TABLE `sivex_tyosuhdet` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `tid` int(7) NOT NULL,
  `alku` varchar(20) NOT NULL,
  `loppu` varchar(20) NOT NULL,
  `vktyoaika` varchar(10) NOT NULL,
  `nimike` varchar(40) NOT NULL,
  `palkkausmuoto` varchar(30) NOT NULL,
  `tuntihinta` varchar(10) NOT NULL,
  `matka_thinta` varchar(10) NOT NULL,
  `lippu_kuumaks` varchar(10) NOT NULL,
  `koe_loppu` varchar(20) NOT NULL,
  `koe_hinta` varchar(10) NOT NULL,
  `tuloraja_ajalle` varchar(100) NOT NULL,
  `perusprosentti` varchar(10) NOT NULL,
  `lisaprosentti` varchar(10) NOT NULL,
  `kuukaudessa` varchar(10) NOT NULL,
  `kahdessa_viikossa` varchar(10) NOT NULL,
  `viikossa` varchar(10) NOT NULL,
  `paivassa` varchar(10) NOT NULL,
  `atk_varten` varchar(10) NOT NULL,
  `yksi_tuloraja` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=latin1;






CREATE TABLE `sivex_tyotodistukset` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `key` int(1) NOT NULL DEFAULT '2',
  `tyonantaja` varchar(70) NOT NULL,
  `osoite` varchar(255) NOT NULL,
  `postinumero` varchar(7) NOT NULL,
  `postitoimipaikka` varchar(100) NOT NULL,
  `puhelin` varchar(50) NOT NULL,
  `y_tunnus` varchar(50) NOT NULL,
  `sahkoposti` varchar(100) NOT NULL,
  `tekijan_email` varchar(100) NOT NULL,
  `tid` int(7) NOT NULL,
  `tekijan_nimi` varchar(70) NOT NULL,
  `tekijan_katuosoite` varchar(100) NOT NULL,
  `tekijan_pnumero` varchar(7) NOT NULL,
  `tekijan_ptoimipaikka` varchar(50) NOT NULL,
  `tekijan_puh` varchar(50) NOT NULL,
  `tekijan_henkilotunnus` varchar(50) NOT NULL,
  `Alku` varchar(50) NOT NULL,
  `Loppu` varchar(50) NOT NULL,
  `Tyokohde` text NOT NULL,
  `Tyotehtavat` text NOT NULL,
  `TyosuhteenPaattamisenSyy` text NOT NULL,
  `Tyotaito` text NOT NULL,
  `Kaytos` text NOT NULL,
  `Arvio` text NOT NULL,
  `Paivays` varchar(50) NOT NULL,
  `Paikka` varchar(100) NOT NULL,
  `TyonantajanEdustaja` varchar(100) NOT NULL,
  `NimikeTehtava` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;






CREATE TABLE `sivex_viestinta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pvm` varchar(20) NOT NULL,
  `tekija` varchar(255) NOT NULL,
  `viesti` text NOT NULL,
  `admin` varchar(50) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;






CREATE TABLE `sivex_virhet` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `tekijan_nimi` varchar(50) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `virhe` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2752 DEFAULT CHARSET=latin1;






CREATE TABLE `sivexkuitti` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `asiakas_num` varchar(50) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `requests` int(7) NOT NULL DEFAULT '1',
  `puh_numero` varchar(50) NOT NULL,
  `imei` varchar(100) NOT NULL,
  `bluetooth_name` varchar(50) NOT NULL,
  `sim_serial_number` varchar(100) NOT NULL,
  `subscriber_id` varchar(50) NOT NULL,
  `my_location` varchar(1000) NOT NULL,
  `osoite` varchar(255) NOT NULL,
  `kohde_kannasta` varchar(100) NOT NULL,
  `kohdenID` int(7) NOT NULL,
  `aloitan` varchar(20) NOT NULL,
  `loppui` varchar(20) NOT NULL,
  `viesti` varchar(250) NOT NULL,
  `tekijan_nimi` varchar(50) NOT NULL,
  `tid` int(7) NOT NULL,
  `etaisyys` varchar(20) NOT NULL,
  `status` int(1) NOT NULL,
  `tietoja` text NOT NULL,
  `admin` int(1) NOT NULL DEFAULT '0',
  `hyvaksytty` varchar(100) NOT NULL,
  `domain` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `asiakas_num` (`asiakas_num`),
  KEY `time` (`time`),
  KEY `my_location` (`my_location`(767)),
  KEY `kohde_kannasta` (`kohde_kannasta`),
  KEY `kohdenID` (`kohdenID`),
  KEY `aloitan` (`aloitan`),
  KEY `loppui` (`loppui`),
  KEY `viesti` (`viesti`),
  KEY `tekijan_nimi` (`tekijan_nimi`),
  KEY `tid` (`tid`),
  KEY `etaisyys` (`etaisyys`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=16401 DEFAULT CHARSET=latin1;






CREATE TABLE `sivexkuitti_repaired` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `kid` int(7) NOT NULL,
  `asiakas_num` varchar(50) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `requests` int(7) NOT NULL DEFAULT '1',
  `puh_numero` varchar(50) NOT NULL,
  `imei` varchar(100) NOT NULL,
  `bluetooth_name` varchar(50) NOT NULL,
  `sim_serial_number` varchar(100) NOT NULL,
  `subscriber_id` varchar(50) NOT NULL,
  `my_location` varchar(1000) NOT NULL,
  `osoite` varchar(255) NOT NULL,
  `kohde_kannasta` varchar(100) NOT NULL,
  `kohdenID` int(7) NOT NULL,
  `aloitan` varchar(20) NOT NULL,
  `loppui` varchar(20) NOT NULL,
  `viesti` varchar(250) NOT NULL,
  `tekijan_nimi` varchar(50) NOT NULL,
  `tid` int(7) NOT NULL,
  `etaisyys` varchar(20) NOT NULL,
  `status` int(1) NOT NULL,
  `tietoja` text NOT NULL,
  `admin` int(1) NOT NULL DEFAULT '0',
  `tyoajanlaatu` varchar(100) NOT NULL,
  `tyoajanmerkinta` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6225 DEFAULT CHARSET=latin1;






CREATE TABLE `soitot_sihterille` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `puh_nro` varchar(30) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=984 DEFAULT CHARSET=latin1;






CREATE TABLE `tarjouksen_rivit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `yllareuna` varchar(255) NOT NULL,
  `tarjous` text NOT NULL,
  `toteutus` text NOT NULL,
  `tyonsisalto` text NOT NULL,
  `alueet` text NOT NULL,
  `siivousbudjetti` text NOT NULL,
  `peruutus` text NOT NULL,
  `maksuehto` text NOT NULL,
  `ennakkoperintarekisteri` text NOT NULL,
  `palvelukriteerit` text NOT NULL,
  `luotettavuus` text NOT NULL,
  `tekninen_laatu` text NOT NULL,
  `laadukkaita` text NOT NULL,
  `kilpailukyky` text NOT NULL,
  `ylivoimainen_este` text NOT NULL,
  `yhteyshenkilot` text NOT NULL,
  `voimassaolo` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;






CREATE TABLE `tehtavat_palvelukuvaus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `otsikko_id` varchar(100) NOT NULL,
  `kuvaus` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=latin1;






CREATE TABLE `users_online` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ip` varchar(50) NOT NULL,
  `session` varchar(150) NOT NULL,
  `time` int(11) NOT NULL,
  `user` varchar(100) NOT NULL,
  `url` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8365 DEFAULT CHARSET=latin1;






CREATE TABLE `vuosilomat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `pvm` varchar(50) NOT NULL,
  `status` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pvm` (`pvm`,`status`,`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=555 DEFAULT CHARSET=latin1;




