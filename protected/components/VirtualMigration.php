<?php

/**
 * Worker for migration to virtual shifts.
 *
 * @property int $step Step number.
 * @property int $cycle Cycle number.
 * @property array $output Output for one cycle.
 * @property string $logpath
 * @property string $startday
 */
class VirtualMigration extends CComponent
{
	private $step;
	private $cycle;
	private $output;
	private $logpath;
	private $startday;

	public function __construct(?int $step = null)
	{
		$this->step = ($step !== null) ? $step : static::getSessionVar("step", 0);
		$this->logpath = Yii::app()->user->domain . '_migration.log';
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

		if ($this->step > 0 && $this->cycle == 1) {
			$this->out([0, 4], "Aloitettiin vaihe %s.", $this->step);
			return $this->finishCycle();
		}

		// Do step.
		switch ($this->step) {

			case 0: // Step 0: Initialization.

				// Table optimization: disabled. Tables in our database don't support optimize; the tables
				// are re-created and analyzed instead. Optimization is almost never worth doing on InnoDB tables.
				// Yii::app()->db1->createCommand("OPTIMIZE TABLE sivex_tvuoro")->execute();
				// Yii::app()->db1->createCommand("OPTIMIZE TABLE toistuvat_tyovuorot")->execute();

				$this->out(4, "Puhdistettiin aikaisemmat sessiomuuttujat.");
				$this->out(4, "Aloitetaan migraatio.");

				$toistuvat = Yii::app()->db1->createCommand()
					->select("id,tid, tyopaari, pfrom, pto")
					->from("toistuvat_tyovuorot")
					//->where()
					->queryAll();
				$toist_arr = [];
				foreach ($toistuvat as $item)
					$toist_arr[$item['id']] = $item;
				static::setSessionVar("toist_arr", $toist_arr);

				return $this->finishCycle(2);

			// case 1: // Clone tables to temporary table with suffix: _migrate
			// 	$tables = [
			// 		'sivex_tvuoro' => 'sivex_tvuoro_migrate',
			// 		'toistuvat_tyovuorot' => 'toistuvat_tyovuorot_migrate'
			// 	];
			// 	foreach ($tables as $table_name => $target_name) {
			// 		$params = [':src' => $table_name, ':tgt' => $target_name];
			// 		try {
			// 			if (!empty(Yii::app()->db->createCommand("SHOW TABLES LIKE ':tgt'")->query($params))) {
			// 				$this->out(2, sprintf("Kohde taulu %s on jo olemassa; pudotetaan taulu.", $target_name));
			// 				Yii::app()->db->createCommand("DROP TABLE IF EXISTS ':tgt'")->query($params);
			// 			}
			// 			$this->out(5, "Kloonataan $table_name rakenne tauluun $target_name");
			// 			Yii::app()->db->createCommand("CREATE TABLE :tgt LIKE :src")->query($params);
			// 			$this->out(5, "Siiretään tiedot taulusta $target_name tauluun $table_name");
			// 			Yii::app()->db->createCommand("INSERT :tgt SELECT * FROM :src")->query($params);
			// 		} catch (\Exception $ex) {
			// 			$this->out(1, "Virhe: " . $ex->getMessage());
			// 			return $this->cdone();
			// 		}
			// 	}
			// 	return $this->cdone(2, "Uudet taulut luotu.");

			case 2:

				$next = $this->dataItemHelper("tvr", 5, function() {
					return Yii::app()->db1->createCommand()
						//->limit("100")
						->select("id, tid, pvm, toistuva_id")
						->from("sivex_tvuoro")
						->group("toistuva_id")
						->order("DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) ASC")
						// <-- Etsitään aktiivisiä ketjua
						->where("DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) BETWEEN '{$this->startday}' AND '" . date("Y-m-d", strtotime($this->startday . " +1 month")) . "'")
						->andWhere("toistuva_id!=0")
						->queryAll();
				});

				if ($next === true) {
					return $this->finishCycle(3);
				} elseif ($next === false) {
					return $this->finishCycle();
				}

				$toist_arr = static::getSessionVar("toist_arr");

				foreach ($next as $item) {
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
								$this->out(2, "TID ongelma, Ketju " . $toistuva_id . ", Uusi TID on - " . $item['tid'] . ", Uusi aloituspäivä: " . date("d.m.Y", strtotime($item['pvm'] . " this week monday")));
								$this->updateAndDelete($toistuva_id, $item);
							} else {
	
								ToistuvatTyovuorot::model()->updatebypk($toistuva_id, array('pfrom' => date("d.m.Y", strtotime($item['pvm'] . " this week monday"))));
								$this->out(5, "Ketju " . $toistuva_id . ", Uusi aloituspäivä: " . date("d.m.Y", strtotime($item['pvm'] . " this week monday")));
								$this->updateAndDelete($toistuva_id, $item);
							}
						} else {
	
							ToistuvatTyovuorot::model()->updatebypk($toistuva_id, array('pfrom' => date("d.m.Y", strtotime($item['pvm'] . " this week monday"))));
							$this->out(5, "Ketju " . $toistuva_id . ", Uusi aloituspäivä: " . date("d.m.Y", strtotime($item['pvm'] . " this week monday")));
							$this->updateAndDelete($toistuva_id, $item);
						}
					} else {
						$this->out(2, 'Ketjussa: ' . $toistuva_id . ' ONGELMA');
					}
				}

				return $this->finishCycle();

			case 3:

				$next = $this->dataItemHelper("tvr", 5, function() {
					return Yii::app()->db1->createCommand()
						->select("id, tid, pvm, toistuva_id")
						->from("sivex_tvuoro")
						->group("toistuva_id")
						->order("DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) DESC")
						->andWhere("toistuva_id!=0")
						->queryAll();
				});

				if ($next === true) {
					return $this->finishCycle(4);
				} elseif ($next === false) {
					return $this->finishCycle();
				}

				$toist_arr = static::getSessionVar("toist_arr");

				foreach ($next as $item) {
					$toistuva_id = $item['toistuva_id'];
	
					if (isset($toist_arr[$toistuva_id])) {
	
						if (date("Ymd", strtotime($toist_arr[$toistuva_id]['pfrom'])) > date("Ymd", strtotime($this->startday))) {
	
							$this->out(5, "POISTETAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " ja PVM >= kun ketjun alkamispäivä - " . date("Y-m-d", strtotime($toist_arr[$toistuva_id]['pfrom'])));
	
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
	
							$this->out(5, "MUOKATAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " --> toistuva_id=0");
	
							foreach ($tvupd as $v)
								Tyovuoroot::model()->updatebypk($v->id, array('toistuva_id' => '0'));
						}
	
						if (date("Ymd", strtotime($toist_arr[$toistuva_id]['pto'])) < date("Ymd", strtotime($this->startday))) {
	
							ToistuvatTyovuorot::model()->deletebypk($toistuva_id);
							$this->out(5, "Ketju " . $toistuva_id . ", POISTETAAN, koska ketjun lopetuspäivä ajemmin kun " . date("d.m.Y", strtotime($this->startday)));
	
							// Update
							$criteria = new CDbCriteria;
							$criteria->select = "id";
							$criteria->condition = " toistuva_id!=0 AND toistuva_id='" . $toistuva_id . "' ";
							$tvupd = Tyovuoroot::model()->findAll($criteria);
	
							$this->out(5, "MUOKATAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " --> toistuva_id=0");
	
							foreach ($tvupd as $v)
								Tyovuoroot::model()->updatebypk($v->id, array('toistuva_id' => '0'));
						} else {
	
							// Toistuva pto on > startday
							if (date("Ymd", strtotime($item['pvm'])) < date("Ymd", strtotime($this->startday))) {
	
								$this->out(5, "Ketju " . $toistuva_id . ", POISTETAAN, koska viimeinen työvuoro oli ajemmin kun " . date("d.m.Y", strtotime($this->startday)));
								ToistuvatTyovuorot::model()->deletebypk($toistuva_id);
	
								// Update
								$criteria = new CDbCriteria;
								$criteria->select = "id";
								$criteria->condition = " toistuva_id!=0 AND toistuva_id='" . $toistuva_id . "' ";
								$tvupd = Tyovuoroot::model()->findAll($criteria);
	
								$this->out(5, "MUOKATAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " --> toistuva_id=0");
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
	
									$this->out(5, "POISTETAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " ja PVM >= kun ketjun alkamispäivä - " . date("Y-m-d", strtotime($toist_arr[$toistuva_id]['pfrom'])));
									foreach ($tvdel as $v)
										Tyovuoroot::model()->deletebypk($v->id);
	
									// Update
									$criteria = new CDbCriteria;
									$criteria->select = "id";
									$criteria->condition = " toistuva_id!=0 AND toistuva_id='" . $toistuva_id . "' ";
									$tvupd = Tyovuoroot::model()->findAll($criteria);
	
									$this->out(5, "MUOKATAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " --> toistuva_id=0");
									foreach ($tvupd as $v)
										Tyovuoroot::model()->updatebypk($v->id, array('toistuva_id' => '0'));
									$this->out(6, '&nbsp;&nbsp; ' . $tvm->pvm);
								}
							}
						}
					} else {
	
						// Update
						$criteria = new CDbCriteria;
						$criteria->select = "id";
						$criteria->condition = " toistuva_id!=0 AND toistuva_id='" . $toistuva_id . "' ";
						$tvupd = Tyovuoroot::model()->findAll($criteria);
	
						$this->out(5, "MUOKATAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " --> toistuva_id=0");
						foreach ($tvupd as $v)
							Tyovuoroot::model()->updatebypk($v->id, array('toistuva_id' => '0'));
					}
				}

				return $this->finishCycle();


			case 4:
				if (!static::getSessionVar("cdone", null, $this->step)) {

					$next = $this->dataItemHelper("tstv", 25, function() {
						return Yii::app()->db1->createCommand()
							->select("id, pfrom, pto")
							->from("toistuvat_tyovuorot")
							->where("DATE(STR_TO_DATE(pfrom, '%d.%m.%Y')) < '{$this->startday}' AND DATE(STR_TO_DATE(pto, '%d.%m.%Y')) < '{$this->startday}'")
							->queryAll();
					});

					if ($next === true) {
						static::setSessionVar("cdone", true, $this->step);
						return $this->finishCycle();
					} elseif ($next === false) {
						return $this->finishCycle();
					}

					foreach ($next as $item) {
						$toistuva_id = $item['id'];
	
						//echo 'Ketju: '.$item['id'].', Pfrom: '.$item['pfrom'].', Pto: '.$item['pto'].'<br>';
	
						ToistuvatTyovuorot::model()->deletebypk($toistuva_id);
						$this->out(5, "Ketju " . $toistuva_id . ", POISTETAAN, koska ketjun lopetuspäivä ajemmin kun " . date("d.m.Y", strtotime($this->startday)));
	
						// Update
						$criteria = new CDbCriteria;
						$criteria->select = "id";
						$criteria->condition = " toistuva_id!=0 AND toistuva_id='" . $toistuva_id . "' ";
						$tvupd = Tyovuoroot::model()->findAll($criteria);
	
						$this->out(5, "MUOKATAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " --> toistuva_id=0");
						foreach ($tvupd as $v)
							Tyovuoroot::model()->updatebypk($v->id, array('toistuva_id' => '0'));
					}

					return $this->finishCycle();
				} else {

					$next = $this->dataItemHelper("tstv2", 25, function() {
						return Yii::app()->db1->createCommand()
							->select("id, pfrom, pto")
							->from("toistuvat_tyovuorot")
							->where("DATE(STR_TO_DATE(pfrom, '%d.%m.%Y')) < '{$this->startday}'")
							->andWhere("id NOT IN(SELECT toistuva_id FROM sivex_tvuoro)")
							->queryAll();
					});

					if ($next === true) {
						return $this->finishCycle(5);
					} elseif ($next === false) {
						return $this->finishCycle();
					}

					foreach ($next as $item) {
						$toistuva_id = $item['id'];
						//echo 'Ketju: '.$item['id'].', Pfrom: '.$item['pfrom'].', Pto: '.$item['pto'].'<br>';
						ToistuvatTyovuorot::model()->deletebypk($toistuva_id);
						$this->out(5, "Ketju " . $toistuva_id . ", POISTETAAN, koska ketjusta ei löytyi yhtään työvuoroa");
					}

					return $this->finishCycle();
				}

			case 5:

				$next = $this->dataItemHelper("tvr", 25, function() {
					return Yii::app()->db1->createCommand()
						->select("poistettu_pvm,id,tyopaari,tid")
						->from("toistuvat_tyovuorot")
						->where("poistettu_pvm!='' AND new_poistettu_pvm IS NULL")
						->queryAll();
				});

				if ($next === true) {
					return $this->finishCycle(6);
				} elseif ($next === false) {
					return $this->finishCycle();
				}

				foreach ($next as $item) {
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
				}

				return $this->finishCycle();

			case 6:

				switch (static::getSessionVar("istep", 0, $this->step)) {
					case 0:
						$this->out(4, "Poistetaan tulevaisuuden työvuorot joilla toistuva_id != 0.");
						$this->out(4, "Kaikkien menneiden työvuorojen toistuva_id asetetaan = 0.");
						static::setSessionVar("istep", 1, $this->step);
						return $this->finishCycle();
					case 1:
						$count = static::getSessionVar("count", 0, $this->step);
						$date_ymd = date("Y-m-d", strtotime($this->startday));
						if (!Yii::app()->db1->createCommand("DELETE FROM sivex_tvuoro WHERE toistuva_id != '0' AND DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) >= '$date_ymd' LIMIT 25")->execute()) {
							static::setSessionVar("istep", 2, $this->step);
							$this->out(4, "Tulevaisuuden ketjuihin kuuluvat työvuorot poistettu.");
							$this->out(5, "Päivitetään toistuva_id=0 kaikille työvuoroille.");
						} else {
							static::setSessionVar("count", $count, $this->step);
							$this->out(4, "Poistettu: " . ($count += 25) . " kpl");
						}
						return $this->finishCycle();
					case 2:
						Yii::app()->db1->createCommand("UPDATE sivex_tvuoro SET toistuva_id='0' WHERE toistuva_id!='0'")->execute();
						return $this->finishCycle(99);
				}

			default:
				return $this->finishCycle(99);
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

		$this->out(5, "POISTETAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " ja PVM >= " . date("Y-m-d", strtotime($item['pvm'] . " this week monday")));

		foreach ($tvdel as $v)
			Tyovuoroot::model()->deletebypk($v->id);

		// Update
		$criteria = new CDbCriteria;
		$criteria->select = "id";
		$criteria->condition = " toistuva_id!=0 AND toistuva_id='" . $toistuva_id . "' ";
		$tvupd = Tyovuoroot::model()->findAll($criteria);

		$this->out(5, "MUOKATAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " --> toistuva_id=0");

		foreach ($tvupd as $v)
			Tyovuoroot::model()->updatebypk($v->id, array('toistuva_id' => '0'));
	}

	/**
	 * Create data for step and return next item for cycle.
	 */
	private function dataItemHelper(string $name, int $count = 5, callable $initializer)
	{
		$key_data = "data_{$name}";
		$key_current = "{$key_data}_current";

		$data = static::getSessionVar($key_data, null, $this->step);
		$current = static::getSessionVar($key_current, 0, $this->step);

		if ($data == null) {
			$data = array_values($initializer());
			static::setSessionVar($key_data, $data, $this->step);
			static::setSessionVar($key_current, 0, $this->step);
			$this->out(0, "Haettu tiedot vaihetta varten (yht: %s)", count($data));
			$this->out(4, "Haettu tiedot vaihetta varten (yht: %s)", count($data));
		}

		if ($current >= count($data)) {
			$this->out(0, "Jäljellä: 0");
			static::setSessionVar($key_data, [], $this->step);
			static::setSessionVar($key_current, 0, $this->step);
			return true;
		} elseif (!isset($data[$current])) {
			$this->out(1, "Koodissa virhe; $name array muuttunut ajon aikana.");
			return false;
		} else {
			$this->out(0, "Jäljellä: %d", (count($data) - $current));
			$next = array_slice($data, $current, $count);
			static::setSessionVar($key_current, ($current + $count), $this->step);
			return $next;
		}
	}

	/**
	 * Continue to next cycle or step.
	 */
	private function finishCycle(?int $next_step = null)
	{
		$next_step = $next_step ?: $this->step;
		$errors = false;
		$log_text = "";

		foreach ($this->output as $entry) {
			if ($entry['type'] == 0)
				continue;
			elseif ($entry['type'] == 1)
				$errors = true;
			$log_text .= sprintf("%s (%s): %s\n", $entry['time'], abs($entry['type']), $entry['text']);
		}

		try {
			$log = fopen($this->logpath, "a");
			fwrite($log, $log_text);
		} catch (\Exception $ex) {
			$this->out(1, sprintf("Lokitiedoston (%s) kirjoittaminen epäonnistui: %s", $this->logpath, $ex->getMessage()));
		} finally {
			fclose($log ?? null);
		}

		static::setSessionVar("step", $next_step);
		static::setSessionVar("cycle", $this->cycle + 1, $this->step);

		return [
			'time' => date('H:i:s', time()),
			'step' => $this->step,
			'cycle' => $this->cycle,
			'next' => $next_step,
			'output' => $this->output,
			'errors' => $errors
		];
	}

	/**
	 * Append to current cycle output.
	 *
	 * @param mixed $types
	 * Can be int or array with multiple types.
	 * 0: status/progress (top bar)
	 * 1: errors/exceptions, stops execution after cycle
	 * 2: warnings that dont stop execution
	 * 3: command success
	 * 4: primary info/progress
	 * 5: general info/listing
	 * 6: debug info, barely used, spam a lot of stuff
	 * @param string $fmt
	 * Format for sprintf.
	 * @param mixed ...$args
	 * Arguments passed to sprintf. Arrays/objects are automatically encoded.
	 */
	private function out($types, string $fmt, ...$args)
	{
		$time = date('H:i:s', time());
		$entry = ['time' => $time, 'text' => ''];
		$types = preg_grep('/^[0-6]$/', (is_array($types) ? $types : [$types]));

		if (empty($fmt) || empty($types))
			return;

		foreach($args as $k => $v)
			if (is_array($v) || is_object($v))
				$args[$k] = json_encode($v);

		try {
			array_unshift($args, $fmt);
			$entry['text'] = call_user_func_array('sprintf', $args);
		} catch (\Exception $ex) {
			$this->output[] = ['time' => $time, 'type' => 1, 'text' => 'Error while formatting: ' . $ex->getMessage()];
		} finally {
			if (empty($entry['text']))
				$entry['text'] = sprintf("(failed to format) format: %s, params: %s", $fmt, join(', ', $args));
			foreach (array_unique($types) as $type)
				$this->output[] = ($entry + ['type' => $type]);
		}
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

	private static function getSessionVar(string $key, $default = null, ?int $step = null, bool $set_default = true)
	{
		$final_key = static::getKey($key, $step);
		if (isset(Yii::app()->session[$final_key]))
			return Yii::app()->session[$final_key];
		if ($default !== null && $set_default)
			static::setSessionVar($key, $default, $step);
		return $default;
	}

	private static function setSessionVar(string $key, $value, ?int $step = null)
	{
		$final_key = static::getKey($key, $step);
		Yii::app()->session[$final_key] = $value;

		$session_keys_key = static::getKey("keys");
		$session_keys = Yii::app()->session[$session_keys_key] ?? [];
		if (!is_array($session_keys))
			$session_keys = [$final_key];
		elseif (!in_array($final_key, $session_keys))
			$session_keys[] = $final_key;
		Yii::app()->session[$session_keys_key] = $session_keys;

		return $value;
	}

	public static function clearSessionVars()
	{
		$key = static::getKey("keys");
		foreach (Yii::app()->session[$key] ?? [] as $k)
			Yii::app()->session[$k] = 0;
		Yii::app()->session[$key] = [];

		// Manual
		static $manual_keys = ['step' => null, 'toist_arr' => null, 'cdone' => 4, 'istep' => 6, 'count' => 6];
		foreach ($manual_keys as $key_temp => $step_temp) {
			$key_temp_final = static::getKey($key_temp, $step_temp);
			if (isset(Yii::app()->session[$key_temp_final]))
				Yii::app()->session[$key_temp_final] = null;
		}
		for ($i = 0; $i < 8; $i++) {
			$key_temp = static::getKey("cycle", $i);
			if (isset(Yii::app()->session[$key_temp]))
				Yii::app()->session[$key_temp] = 1;
		}
	}
}
