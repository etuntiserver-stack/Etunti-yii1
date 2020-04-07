<?php

/**
 * Worker for migration to virtual shifts.
 *
 * @property int $step Step number.
 * @property int $cycle Cycle number.
 * @property array $output Output for one cycle.
 * @property string $log_path
 * @property string $startday
 */
class VirtualMigration extends CComponent
{
	private $step;
	private $cycle;
	private $output;
	private $log_path;
	private $startday;

	public function __construct(bool $reset = false)
	{
		$this->step = static::getSessionVar("step", 0);
		$this->log_path = Yii::app()->user->domain . '_migration.log';
		$this->startday = date("Y-m-d", strtotime("next monday"));
	}

	public function doNextStep()
	{
		// Clear previous session variables when starting fresh.
		if ($this->step === 0)
			static::clearSessionVars();

		// Init cycle.
		$this->cycle = static::getSessionVar("cycle", 1, $this->step);
		$this->output = [];

		// Do step.
		switch ($this->step) {

			case 0: // Step 0: Initialization.

				// Table optimization: disabled. Tables in our database don't support optimize; the tables
				// are re-created and analyzed instead. Optimization is almost never worth doing on InnoDB tables.
				// Yii::app()->db1->createCommand("OPTIMIZE TABLE sivex_tvuoro")->execute();
				// Yii::app()->db1->createCommand("OPTIMIZE TABLE toistuvat_tyovuorot")->execute();

				$this->cout("Puhdistettiin aikaisemmat sessiomuuttujat.");

				$toistuvat = Yii::app()->db1->createCommand()
					->select("id,tid, tyopaari, pfrom, pto")
					->from("toistuvat_tyovuorot")
					//->where()
					->queryAll();
				$toist_arr = [];
				foreach ($toistuvat as $item)
					$toist_arr[$item['id']] = $item;
				static::setSessionVar("toist_arr", $toist_arr);

				// Skip stage 1; Do changes to main table for now, for testing on staging.
				return $this->continue(2);

			// case 1: // Clone tables to temporary table with suffix: _migrate
			// 	$tables = [
			// 		'sivex_tvuoro' => 'sivex_tvuoro_migrate',
			// 		'toistuvat_tyovuorot' => 'toistuvat_tyovuorot_migrate'
			// 	];
			// 	foreach ($tables as $table_name => $target_name) {
			// 		$params = [':src' => $table_name, ':tgt' => $target_name];
			// 		try {
			// 			if (!empty(Yii::app()->db->createCommand("SHOW TABLES LIKE ':tgt'")->query($params))) {
			// 				$this->cout(2, sprintf("Kohde taulu %s on jo olemassa; pudotetaan taulu.", $target_name));
			// 				Yii::app()->db->createCommand("DROP TABLE IF EXISTS ':tgt'")->query($params);
			// 			}
			// 			$this->cout(4, "Kloonataan $table_name rakenne tauluun $target_name");
			// 			Yii::app()->db->createCommand("CREATE TABLE :tgt LIKE :src")->query($params);
			// 			$this->cout(4, "Siiretään tiedot taulusta $target_name tauluun $table_name");
			// 			Yii::app()->db->createCommand("INSERT :tgt SELECT * FROM :src")->query($params);
			// 		} catch (\Exception $ex) {
			// 			$this->cout(1, "Virhe: " . $ex->getMessage());
			// 			return $this->cdone();
			// 		}
			// 	}
			// 	return $this->cdone(2, "Uudet taulut luotu.");

			case 2:
				if (!$tvr = static::getSessionVar("tvr", null, $this->step)) {
					$tvr = Yii::app()->db1->createCommand()
						//->limit("100")
						->select("id, tid, pvm, toistuva_id")
						->from("sivex_tvuoro")
						->group("toistuva_id")
						->order("DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) ASC")
						// <-- Etsitään aktiivisiä ketjua
						->where("DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) BETWEEN '{$this->startday}' AND '" . date("Y-m-d", strtotime($this->startday . " +1 month")) . "'")
						->andWhere("toistuva_id!=0")
						->queryAll();

					static::setSessionVar("tvr", array_values($tvr), $this->step);
					$this->cout(3, "Total: " . count($tvr));
					return $this->continue();
				}

				$current = static::getSessionVar("current", 0, $this->step);
				$toist_arr = static::getSessionVar("toist_arr");

				if (!isset($tvr[$current])) {
					$this->cout(1, "Koodissa virhe; tvr array muuttunut ajon aikana.");
					return $this->continue();
				}

				$item = $tvr[$current];
				$toistuva_id = $item['toistuva_id'];

				if (isset($toist_arr[$toistuva_id])) {
					$tids = [];
					$tids[$toist_arr[$toistuva_id]['tid']] = $toist_arr[$toistuva_id]['tid'];
					foreach (json_decode($toist_arr[$toistuva_id]['tyopaari'], true) as $tid)
						$tids[$tid] = $tid;

					if (!in_array($item['tid'], $tids)) {

						// <-- Jos EI työparia
						if (empty($toist_arr[$toistuva_id]['tyopaari'])) {
							// ONGELMA 

							ToistuvatTyovuorot::model()->updatebypk($toistuva_id, array('tid' => $item['tid'], 'pfrom' => date("d.m.Y", strtotime($item['pvm'] . " this week monday"))));
							$this->cout(2, "TID ongelma, Ketju " . $toistuva_id . ", Uusi TID on - " . $item['tid'] . ", Uusi aloituspäivä: " . date("d.m.Y", strtotime($item['pvm'] . " this week monday")));
							$this->updateAndDelete($toistuva_id, $item);
						} else {

							ToistuvatTyovuorot::model()->updatebypk($toistuva_id, array('pfrom' => date("d.m.Y", strtotime($item['pvm'] . " this week monday"))));
							$this->cout(4, "Ketju " . $toistuva_id . ", Uusi aloituspäivä: " . date("d.m.Y", strtotime($item['pvm'] . " this week monday")));
							$this->updateAndDelete($toistuva_id, $item);
						}
					} else {

						ToistuvatTyovuorot::model()->updatebypk($toistuva_id, array('pfrom' => date("d.m.Y", strtotime($item['pvm'] . " this week monday"))));
						$this->cout(4, "Ketju " . $toistuva_id . ", Uusi aloituspäivä: " . date("d.m.Y", strtotime($item['pvm'] . " this week monday")));
						$this->updateAndDelete($toistuva_id, $item);
					}
				} else {
					$this->cout(2, 'Ketjussa: ' . $toistuva_id . ' ONGELMA');
				}

				static::setSessionVar("current", ++$current, $this->step);
				if ($current >= count($tvr)) {
					return $this->continue(3);
				} else {
					$this->cout(0, "Jäljellä: " . (count($tvr) - $current));
					return $this->continue();
				}

			case 3:
				if (!$tvr = static::getSessionVar("tvr", null, $this->step)) {
					$tvr = Yii::app()->db1->createCommand()
						->select("id, tid, pvm, toistuva_id")
						->from("sivex_tvuoro")
						->group("toistuva_id")
						->order("DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) DESC")
						->andWhere("toistuva_id!=0")
						->queryAll();

					static::setSessionVar("tvr", array_values($tvr), $this->step);
					$this->cout(3, "Total: " . count($tvr));
					return $this->continue();
				}

				$current = static::getSessionVar("current", 0, $this->step);
				$toist_arr = static::getSessionVar("toist_arr");

				if (!isset($tvr[$current])) {
					$this->cout(1, "Koodissa virhe; tvr array muuttunut ajon aikana.");
					return $this->continue();
				}

				$item = $tvr[$current];
				$toistuva_id = $item['toistuva_id'];

				if (isset($toist_arr[$toistuva_id])) {

					if (date("Ymd", strtotime($toist_arr[$toistuva_id]['pfrom'])) > date("Ymd", strtotime($this->startday))) {

						$this->cout(4, "POISTETAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " ja PVM >= kun ketjun alkamispäivä - " . date("Y-m-d", strtotime($toist_arr[$toistuva_id]['pfrom'])));

						// Delete
						$criteria = new CDbCriteria;
						$criteria->select = "id";
						$criteria->condition = "
							DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) >= '" . date("Y-m-d", strtotime($toist_arr[$toistuva_id]['pfrom'])) . "' 
							AND toistuva_id='" . $toistuva_id . "' 
						";
						$tvdel = Tyovuoroot::model()->findAll($criteria);

						foreach ($tvdel as $v)
							Tyovuoroot::model()->deletebypk($v->id);

						// Update
						$criteria = new CDbCriteria;
						$criteria->select = "id";
						$criteria->condition = " toistuva_id!=0 AND toistuva_id='" . $toistuva_id . "' ";
						$tvupd = Tyovuoroot::model()->findAll($criteria);

						$this->cout(4, "MUOKATAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " --> toistuva_id=0");

						foreach ($tvupd as $v)
							Tyovuoroot::model()->updatebypk($v->id, array('toistuva_id' => '0'));
					}

					if (date("Ymd", strtotime($toist_arr[$toistuva_id]['pto'])) < date("Ymd", strtotime($this->startday))) {

						ToistuvatTyovuorot::model()->deletebypk($toistuva_id);
						$this->cout(4, "Ketju " . $toistuva_id . ", POISTETAAN, koska ketjun lopetuspäivä ajemmin kun " . date("d.m.Y", strtotime($this->startday)));

						// Update
						$criteria = new CDbCriteria;
						$criteria->select = "id";
						$criteria->condition = " toistuva_id!=0 AND toistuva_id='" . $toistuva_id . "' ";
						$tvupd = Tyovuoroot::model()->findAll($criteria);

						$this->cout(4, "MUOKATAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " --> toistuva_id=0");

						foreach ($tvupd as $v)
							Tyovuoroot::model()->updatebypk($v->id, array('toistuva_id' => '0'));
					} else {

						// Toistuva pto on > startday
						if (date("Ymd", strtotime($item['pvm'])) < date("Ymd", strtotime($this->startday))) {

							$this->cout(4, "Ketju " . $toistuva_id . ", POISTETAAN, koska viimeinen työvuoro oli ajemmin kun " . date("d.m.Y", strtotime($this->startday)));
							ToistuvatTyovuorot::model()->deletebypk($toistuva_id);

							// Update
							$criteria = new CDbCriteria;
							$criteria->select = "id";
							$criteria->condition = " toistuva_id!=0 AND toistuva_id='" . $toistuva_id . "' ";
							$tvupd = Tyovuoroot::model()->findAll($criteria);

							$this->cout(4, "MUOKATAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " --> toistuva_id=0");
							foreach ($tvupd as $v) {
								Tyovuoroot::model()->updatebypk($v->id, array('toistuva_id' => '0'));
							}
						} else {

							$criteria = new CDbCriteria;
							$criteria->order = "DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) ASC";
							$criteria->select = "pvm";
							$criteria->limit = "1";
							$criteria->condition = "toistuva_id='$toistuva_id'";
							$tvm = Tyovuoroot::model()->find($criteria);
							if (isset($tvm->pvm) and date("Ymd", strtotime($tvm->pvm)) > date("Ymd", strtotime($this->startday))) {

								ToistuvatTyovuorot::model()->updatebypk($toistuva_id, array('pfrom' => $tvm->pvm));

								// Delete
								$criteria = new CDbCriteria;
								$criteria->select = "id";
								$criteria->condition = " 
									DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) >= '" . date("Y-m-d", strtotime($tvm->pvm)) . "' 
									AND toistuva_id='" . $toistuva_id . "' 
								";
								$tvdel = Tyovuoroot::model()->findAll($criteria);

								$this->cout(4, "POISTETAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " ja PVM >= kun ketjun alkamispäivä - " . date("Y-m-d", strtotime($toist_arr[$toistuva_id]['pfrom'])));
								foreach ($tvdel as $v)
									Tyovuoroot::model()->deletebypk($v->id);

								// Update
								$criteria = new CDbCriteria;
								$criteria->select = "id";
								$criteria->condition = " toistuva_id!=0 AND toistuva_id='" . $toistuva_id . "' ";
								$tvupd = Tyovuoroot::model()->findAll($criteria);

								$this->cout(4, "MUOKATAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " --> toistuva_id=0");
								foreach ($tvupd as $v)
									Tyovuoroot::model()->updatebypk($v->id, array('toistuva_id' => '0'));
								$this->cout(5, '&nbsp;&nbsp; ' . $tvm->pvm);
							}
						}
					}
				} else {

					// Update
					$criteria = new CDbCriteria;
					$criteria->select = "id";
					$criteria->condition = " toistuva_id!=0 AND toistuva_id='" . $toistuva_id . "' ";
					$tvupd = Tyovuoroot::model()->findAll($criteria);

					$this->cout(4, "MUOKATAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " --> toistuva_id=0");
					foreach ($tvupd as $v)
						Tyovuoroot::model()->updatebypk($v->id, array('toistuva_id' => '0'));
				}

				static::setSessionVar("current", ++$current, $this->step);
				if ($current >= count($tvr)) {
					return $this->continue(4);
				} else {
					$this->cout(0, "Jäljellä: " . (count($tvr) - $current));
					return $this->continue();
				}


			case 4:
				if (!static::getSessionVar("cdone", null, $this->step)) {
					if (!$tstv = static::getSessionVar("tstv", null, $this->step)) {
						$tstv = Yii::app()->db1->createCommand()
							->select("id, pfrom, pto")
							->from("toistuvat_tyovuorot")
							->where("DATE(STR_TO_DATE(pfrom, '%d.%m.%Y')) < '{$this->startday}' AND DATE(STR_TO_DATE(pto, '%d.%m.%Y')) < '{$this->startday}'")
							->queryAll();

						static::setSessionVar("tstv", array_values($tstv), $this->step);
						$this->cout(3, "Total: " . count($tstv));
						return $this->continue();
					}

					$current = static::getSessionVar("current", 0, $this->step);

					if (!isset($tstv[$current])) {
						$this->cout(1, "Koodissa virhe; tstv array muuttunut ajon aikana.");
						return $this->continue();
					}

					$item = $tstv[$current];
					$toistuva_id = $item['id'];

					//echo 'Ketju: '.$item['id'].', Pfrom: '.$item['pfrom'].', Pto: '.$item['pto'].'<br>';

					ToistuvatTyovuorot::model()->deletebypk($toistuva_id);
					$this->cout(4, "Ketju " . $toistuva_id . ", POISTETAAN, koska ketjun lopetuspäivä ajemmin kun " . date("d.m.Y", strtotime($this->startday)));

					// Update
					$criteria = new CDbCriteria;
					$criteria->select = "id";
					$criteria->condition = " toistuva_id!=0 AND toistuva_id='" . $toistuva_id . "' ";
					$tvupd = Tyovuoroot::model()->findAll($criteria);

					$this->cout(4, "MUOKATAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " --> toistuva_id=0");
					foreach ($tvupd as $v)
						Tyovuoroot::model()->updatebypk($v->id, array('toistuva_id' => '0'));

					static::setSessionVar("current", ++$current, $this->step);
					if ($current >= count($tstv)) {
						static::setSessionVar("current", 0, $this->step);
						static::setSessionVar("cdone", true, $this->step);
						return $this->continue();
					} else {
						$this->cout(4, "Jäljellä: " . (count($tvr) - $current));
						return $this->continue();
					}
				} else {
					if (!$tstv = static::getSessionVar("tstv", null, $this->step)) {
						$tstv = Yii::app()->db1->createCommand()
							->select("id, pfrom, pto")
							->from("toistuvat_tyovuorot")
							->where("DATE(STR_TO_DATE(pfrom, '%d.%m.%Y')) < '{$this->startday}'")
							->andWhere("id NOT IN(SELECT toistuva_id FROM sivex_tvuoro)")
							->queryAll();

						static::setSessionVar("tstv", array_values($tstv), $this->step);
						$this->cout(3, "Total: " . count($tstv));
						return $this->continue();
					}

					$current = static::getSessionVar("current", 0, $this->step);

					if (!isset($tstv[$current])) {
						$this->cout(1, "Koodissa virhe; tstv array muuttunut ajon aikana.");
						return $this->continue();
					}

					$item = $tstv[$current];
					$toistuva_id = $item['id'];
					//echo 'Ketju: '.$item['id'].', Pfrom: '.$item['pfrom'].', Pto: '.$item['pto'].'<br>';
					ToistuvatTyovuorot::model()->deletebypk($toistuva_id);
					$this->cout(4, "Ketju " . $toistuva_id . ", POISTETAAN, koska ketjusta ei löytyi yhtään työvuoroa");

					static::setSessionVar("current", ++$current, $this->step);
					if ($current >= count($tstv)) {
						return $this->continue(5);
					} else {
						$this->cout(0, "Jäljellä: " . (count($tstv) - $current));
						return $this->continue();
					}
				}

			case 5:

				if (!$tvr = static::getSessionVar("tvr", null, $this->step)) {
					$tvr = Yii::app()->db1->createCommand()
						->select("poistettu_pvm,id,tyopaari,tid")
						->from("toistuvat_tyovuorot")
						->where("poistettu_pvm!='' AND new_poistettu_pvm IS NULL")
						->queryAll();

					static::setSessionVar("tvr", array_values($tvr), $this->step);
					$this->cout(3, "Total: " . count($tvr));
					return $this->continue();
				}

				$current = static::getSessionVar("current", 0, $this->step);

				if (!isset($tvr[$current])) {
					$this->cout(1, "Koodissa virhe; tvr array muuttunut ajon aikana.");
					return $this->continue();
				}

				$item = $tvr[$current];

				$poistetut_pvms = json_decode($item['poistettu_pvm'], true);
				if (count($poistetut_pvms) != 0) {
					// <-- Tids
					$tids = [];
					if (!empty($item['tyopaari'])) {
						foreach (json_decode($item['tyopaari'], true) as $tid) {
							$tids[$tid] = $tid;
						}
						$tids[$item['tid']] = $item['tid'];
					} else {
						$tids[$item['tid']] = $item['tid'];
					}
					$new_poistettu_pvm = [];
					foreach ($tids as $tid) {
						foreach ($poistetut_pvms as $k => $v) {
							if (date("Ymd", strtotime($v)) > date("Ymd", strtotime($this->startday))) // Oikein
								$new_poistettu_pvm[$tid][$v] = ['tid' => $tid, 'pvm' => $v, 'syy' => ['text' => '', 'user' => '', 'date' => '']];
						}
					}
					$result = [];
					foreach ($new_poistettu_pvm as $k => $v)
						foreach ($v as $k2 => $v2)
							$result[] = $v2;
					$clearing = [];
					foreach ($result as $key => $value) {
						if (!in_array($value, $clearing))
							$clearing[] = $value;
					}
					$new_poistettu_pvm_arvo = (count($clearing) > 0) ? json_encode(array_values($clearing)) : '';
					//echo 'Clearning: '.$new_poistettu_pvm_arvo.'<br>';
					ToistuvatTyovuorot::model()->updatebypk($item['id'], array('poistettu_pvm' => '', 'new_poistettu_pvm' => $new_poistettu_pvm_arvo));
				}

				static::setSessionVar("current", ++$current, $this->step);
				if ($current >= count($tvr)) {
					return $this->continue(6);
				} else {
					$this->cout(4, "Jäljellä: " . (count($tvr) - $current));
					return $this->continue();
				}

			case 6:

				switch (static::getSessionVar("istep", 0, $this->step)) {
					case 0:
						$this->cout(3, "Poistetaan tulevaisuuden työvuorot joilla toistuva_id != 0.");
						$this->cout(3, "Kaikkien menneiden työvuorojen toistuva_id asetetaan = 0.");
						static::setSessionVar("istep", 1, $this->step);
						return $this->continue();
					case 1:
						$count = static::getSessionVar("count", 0, $this->step);
						$date_ymd = date("Y-m-d", strtotime($this->startday));
						if (!Yii::app()->db1->createCommand("DELETE FROM sivex_tvuoro WHERE toistuva_id != '0' AND DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) >= '$date_ymd' LIMIT 25")->execute()) {
							static::setSessionVar("istep", 2, $this->step);
							$this->cout(0, "Tulevaisuuden ketjuihin kuuluvat työvuorot poistettu.");
							$this->cout(4, "Päivitetään toistuva_id=0 kaikille työvuoroille.");
						} else {
							static::setSessionVar("count", $count, $this->step);
							$this->cout(0, "Poistettu: " . ($count += 25) . " kpl");
						}
						return $this->continue();
					case 2:
						Yii::app()->db1->createCommand("UPDATE sivex_tvuoro SET toistuva_id='0' WHERE toistuva_id!='0'")->execute();
						return $this->continue(7);
				}

			default:
				return $this->continue(99);
		}
	}

	private function transaction(callable $action)
	{
		$this->cout(4, "TRANSACTION: Begin");
		$transaction = Yii::app()->db1->beginTransaction();
		$commit = false;
		try {
			$commit = $action();
		} catch (\Exception $ex) {
			$this->cout(1, "Error during transaction: " . $ex->getMessage());
		} finally {
			if ($commit) {
				$this->cout(4, "TRANSACTION: Commit");
				$transaction->commit();
			} else {
				$this->cout(4, "TRANSACTION: Rollback");
				$transaction->rollback();
			}
		}
	}

	private function updateAndDelete($toistuva_id, $item)
	{
		// Delete
		$criteria = new CDbCriteria;
		$criteria->select = "id";
		$criteria->condition = " 
			DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) >= '" . date("Y-m-d", strtotime($item['pvm'] . " this week monday")) . "' 
			AND toistuva_id='" . $toistuva_id . "' 
		";
		$tvdel = Tyovuoroot::model()->findAll($criteria);

		$this->cout(4, "POISTETAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " ja PVM >= " . date("Y-m-d", strtotime($item['pvm'] . " this week monday")));

		foreach ($tvdel as $v)
			Tyovuoroot::model()->deletebypk($v->id);

		// Update
		$criteria = new CDbCriteria;
		$criteria->select = "id";
		$criteria->condition = " toistuva_id!=0 AND toistuva_id='" . $toistuva_id . "' ";
		$tvupd = Tyovuoroot::model()->findAll($criteria);

		$this->cout(4, "MUOKATAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " --> toistuva_id=0");

		foreach ($tvupd as $v)
			Tyovuoroot::model()->updatebypk($v->id, array('toistuva_id' => '0'));
	}

	private function cout($type_or_data = null, $other_one = null)
	{
		$time = date('H:i:s', time());
		if (is_array($type_or_data)) {
			foreach ($type_or_data as $item) {
				$this->output[] = [
					'time' => $time,
					'text' => $item['text'],
					'type' => max(-2, min(5, ($item['type'] ?? 3)))
				];
			}
		} elseif (is_numeric($type_or_data) && is_string($other_one)) {
			$this->output[] = [
				'time' => $time,
				'text' => $other_one,
				'type' => max(-2, min(5, ($type_or_data ?? 3)))
			];
		} else {
			$this->output[] = [
				'time' => $time,
				'text' => $type_or_data,
				'type' => max(-2, min(5, ($other_one ?? 3)))
			];
		}
	}

	private function continue(?int $next_step = null, ...$entries)
	{
		$next_step = $next_step ?: $this->step;
		$errors = false;
		foreach ($entries as $entry) {
			if (is_array($entry)) {
				$l = max(-2, min(5, ($entry[0])));
				$this->cout($l, $entry[1]);
				if (abs($l) == 1) $errors = true;
			} else {
				$this->cout($entry, ($next_step < 0) ? 1 : 3);
			}
		}

		$log = null;
		$log_text = "";

		foreach ($this->output as $entry) {
			$log_text .= sprintf("%s (%s): %s\n", $entry['time'], abs($entry['type']), $entry['text']);
			if (abs($entry['type']) == 1) $errors = true;
		}

		try {
			$log = fopen($this->log_path, "a");
			fwrite($log, $log_text);
		} catch (\Exception $ex) {
			$this->cout(1, sprintf("Lokitiedoston (%s) kirjoittaminen epäonnistui: %s", $this->log_path, $ex->getMessage()));
		} finally {
			fclose($log);
		}

		static::setSessionVar("step", $next_step);
		static::setSessionVar("cycle", $this->cycle + 1, $this->step);

		return [
			'time' => date('H:i:s', time()),
			'step' => $this->step,
			'cycle' => $this->cycle,
			'next' => $next_step ?: $this->step,
			'output' => $this->output,
			'errors' => $errors
		];
	}

	//****************************************************************************
	//* Static Helpers
	//****************************************************************************

	private static function getKey(string $key, ?int $step = null)
	{
		$domain = strtolower(Yii::app()->user->domain);
		$stepstr = ($step !== null) ? "_step_{$step}_" : "_";
		return "vmigrate_{$domain}{$stepstr}{$key}";
	}

	private static function addSessionKey(string $final_key)
	{
		$key = static::getKey("keys");
		$session_keys = Yii::app()->session[$key] ?? [];
		if (!is_array($session_keys))
			$session_keys = [$final_key];
		elseif (!in_array($final_key, $session_keys))
			$session_keys[] = $final_key;
		Yii::app()->session[$key] = $session_keys;
	}

	private static function removeSessionKey(string $final_key)
	{
		$key = static::getKey("keys");
		if (!isset(Yii::app()->session[$key]) || !is_array(Yii::app()->session[$key]))
			Yii::app()->session[$key] = [];
		if ($index = array_search($final_key, Yii::app()->session[$key]))
			unset(Yii::app()->session[$key][$index]);
	}

	public static function getSessionVar(string $key, $default = null, ?int $step = null, bool $set_default = true)
	{
		$final_key = static::getKey($key, $step);
		if (isset(Yii::app()->session[$final_key]))
			return Yii::app()->session[$final_key];
		if ($default !== null && $set_default)
			static::setSessionVar($key, $default, $step);
		return $default;
	}

	public static function setSessionVar(string $key, $value, ?int $step = null)
	{
		$final_key = static::getKey($key, $step);
		Yii::app()->session[$final_key] = $value;
		static::addSessionKey($final_key);
		return $value;
	}

	public static function clearSessionVars()
	{
		foreach (Yii::app()->session[static::getKey("keys")] as $k) {
			Yii::app()->session[$k] = null;
			static::removeSessionKey($k);
		}
	}

	public static function stepTexts(int $step = 0)
	{
		if ($step >= 0) {
			$result = [
				'label' => 'Undefined',
				'action' => '',
				'problem' => '',
			];
		} else {
			$result = [
				'label' => "Unexpected Problems Fix",
				'action' => "Attempt to fix previously encountered errors/problems.",
				'problem' => '',
			];
		}

		switch ($step) {

				// Steps
			case 0:
				$result['label'] = 'Initialization';
				$result['action'] = 'Clear previous session variables and initialize transaction.';
				break;
			case 1:
				$result['label'] = 'Clone Tables';
				$result['action'] = 'Create tables sivex_tvuoro_migrate and toistuvat_tyovuorot_migrate with identical data.';
				break;
			case 2:
				$result['label'] = 'Roman Stage 2';
				$result['action'] = 'Muutetaan toistuvien työvuorojen ketjut alkamaan tästä päivästä.';
				break;
			case 3:
				$result['label'] = 'Roman Stage 3';
				$result['action'] = '';
				break;
			case 4:
				$result['label'] = 'Roman Stage 4';
				$result['action'] = '';
				break;
			case 5:
				$result['label'] = 'Roman Stage 5';
				$result['action'] = '';
				break;
			case 6:
				$result['label'] = 'Transaction Commit';
				$result['action'] = 'Commit transaction and finish.';
				break;
			case 99:
				$result['label'] = 'Migration Finish';
				$result['action'] = 'Finished.';
				break;

				// Errors
			case -2:
				$result['problem'] = 'Migration was started but a transaction object already exists.';
				$result['action'] = 'Rollback and clear old transaction.';
				break;
			case -3:
				$result['problem'] = 'Migration in progress but no transaction.';
				$result['action'] = 'Restart migration due to missing transaction object.';
				break;
		}

		return $result;
	}
}
