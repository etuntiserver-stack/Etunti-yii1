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

		// Do step.
		switch ($this->step) {

			case 0: // Step 0: Initialization.

				// Table optimization: disabled. Tables in our database don't support optimize; the tables
				// are re-created and analyzed instead. Optimization is almost never worth doing on InnoDB tables.
				// Yii::app()->db1->createCommand("OPTIMIZE TABLE sivex_tvuoro")->execute();
				// Yii::app()->db1->createCommand("OPTIMIZE TABLE toistuvat_tyovuorot")->execute();

				$this->out_primary("Puhdistettiin aikaisemmat sessiomuuttujat.");

				$toistuvat = Yii::app()->db1->createCommand()
					->select("id,tid, tyopaari, pfrom, pto")
					->from("toistuvat_tyovuorot")
					//->where()
					->queryAll();
				$toist_arr = [];
				foreach ($toistuvat as $item)
					$toist_arr[$item['id']] = $item;
				static::setSessionVar("toist_arr", $toist_arr);

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
			// 				$this->out_warning(sprintf("Kohde taulu %s on jo olemassa; pudotetaan taulu.", $target_name));
			// 				Yii::app()->db->createCommand("DROP TABLE IF EXISTS ':tgt'")->query($params);
			// 			}
			// 			$this->out_info("Kloonataan $table_name rakenne tauluun $target_name");
			// 			Yii::app()->db->createCommand("CREATE TABLE :tgt LIKE :src")->query($params);
			// 			$this->out_info("Siiretään tiedot taulusta $target_name tauluun $table_name");
			// 			Yii::app()->db->createCommand("INSERT :tgt SELECT * FROM :src")->query($params);
			// 		} catch (\Exception $ex) {
			// 			$this->out_error("Virhe: " . $ex->getMessage());
			// 			return $this->cdone();
			// 		}
			// 	}
			// 	return $this->cdone(2, "Uudet taulut luotu.");

			case 2:

				$item = $this->dataItemHelper("tvr", function() {
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

				if ($item === true) {
					return $this->continue(3);
				} elseif ($item === false) {
					return $this->continue();
				}

				$toist_arr = static::getSessionVar("toist_arr");
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
							$this->out_warning("TID ongelma, Ketju " . $toistuva_id . ", Uusi TID on - " . $item['tid'] . ", Uusi aloituspäivä: " . date("d.m.Y", strtotime($item['pvm'] . " this week monday")));
							$this->updateAndDelete($toistuva_id, $item);
						} else {

							ToistuvatTyovuorot::model()->updatebypk($toistuva_id, array('pfrom' => date("d.m.Y", strtotime($item['pvm'] . " this week monday"))));
							$this->out_info("Ketju " . $toistuva_id . ", Uusi aloituspäivä: " . date("d.m.Y", strtotime($item['pvm'] . " this week monday")));
							$this->updateAndDelete($toistuva_id, $item);
						}
					} else {

						ToistuvatTyovuorot::model()->updatebypk($toistuva_id, array('pfrom' => date("d.m.Y", strtotime($item['pvm'] . " this week monday"))));
						$this->out_info("Ketju " . $toistuva_id . ", Uusi aloituspäivä: " . date("d.m.Y", strtotime($item['pvm'] . " this week monday")));
						$this->updateAndDelete($toistuva_id, $item);
					}
				} else {
					$this->out_warning('Ketjussa: ' . $toistuva_id . ' ONGELMA');
				}

				return $this->continue();

			case 3:

				$item = $this->dataItemHelper("tvr", function() {
					return Yii::app()->db1->createCommand()
						->select("id, tid, pvm, toistuva_id")
						->from("sivex_tvuoro")
						->group("toistuva_id")
						->order("DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) DESC")
						->andWhere("toistuva_id!=0")
						->queryAll();
				});

				if ($item === true) {
					return $this->continue(4);
				} elseif ($item === false) {
					return $this->continue();
				}

				$toist_arr = static::getSessionVar("toist_arr");
				$toistuva_id = $item['toistuva_id'];

				if (isset($toist_arr[$toistuva_id])) {

					if (date("Ymd", strtotime($toist_arr[$toistuva_id]['pfrom'])) > date("Ymd", strtotime($this->startday))) {

						$this->out_info("POISTETAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " ja PVM >= kun ketjun alkamispäivä - " . date("Y-m-d", strtotime($toist_arr[$toistuva_id]['pfrom'])));

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

						$this->out_info("MUOKATAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " --> toistuva_id=0");

						foreach ($tvupd as $v)
							Tyovuoroot::model()->updatebypk($v->id, array('toistuva_id' => '0'));
					}

					if (date("Ymd", strtotime($toist_arr[$toistuva_id]['pto'])) < date("Ymd", strtotime($this->startday))) {

						ToistuvatTyovuorot::model()->deletebypk($toistuva_id);
						$this->out_info("Ketju " . $toistuva_id . ", POISTETAAN, koska ketjun lopetuspäivä ajemmin kun " . date("d.m.Y", strtotime($this->startday)));

						// Update
						$criteria = new CDbCriteria;
						$criteria->select = "id";
						$criteria->condition = " toistuva_id!=0 AND toistuva_id='" . $toistuva_id . "' ";
						$tvupd = Tyovuoroot::model()->findAll($criteria);

						$this->out_info("MUOKATAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " --> toistuva_id=0");

						foreach ($tvupd as $v)
							Tyovuoroot::model()->updatebypk($v->id, array('toistuva_id' => '0'));
					} else {

						// Toistuva pto on > startday
						if (date("Ymd", strtotime($item['pvm'])) < date("Ymd", strtotime($this->startday))) {

							$this->out_info("Ketju " . $toistuva_id . ", POISTETAAN, koska viimeinen työvuoro oli ajemmin kun " . date("d.m.Y", strtotime($this->startday)));
							ToistuvatTyovuorot::model()->deletebypk($toistuva_id);

							// Update
							$criteria = new CDbCriteria;
							$criteria->select = "id";
							$criteria->condition = " toistuva_id!=0 AND toistuva_id='" . $toistuva_id . "' ";
							$tvupd = Tyovuoroot::model()->findAll($criteria);

							$this->out_info("MUOKATAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " --> toistuva_id=0");
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

								$this->out_info("POISTETAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " ja PVM >= kun ketjun alkamispäivä - " . date("Y-m-d", strtotime($toist_arr[$toistuva_id]['pfrom'])));
								foreach ($tvdel as $v)
									Tyovuoroot::model()->deletebypk($v->id);

								// Update
								$criteria = new CDbCriteria;
								$criteria->select = "id";
								$criteria->condition = " toistuva_id!=0 AND toistuva_id='" . $toistuva_id . "' ";
								$tvupd = Tyovuoroot::model()->findAll($criteria);

								$this->out_info("MUOKATAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " --> toistuva_id=0");
								foreach ($tvupd as $v)
									Tyovuoroot::model()->updatebypk($v->id, array('toistuva_id' => '0'));
								$this->out_debug('&nbsp;&nbsp; ' . $tvm->pvm);
							}
						}
					}
				} else {

					// Update
					$criteria = new CDbCriteria;
					$criteria->select = "id";
					$criteria->condition = " toistuva_id!=0 AND toistuva_id='" . $toistuva_id . "' ";
					$tvupd = Tyovuoroot::model()->findAll($criteria);

					$this->out_info("MUOKATAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " --> toistuva_id=0");
					foreach ($tvupd as $v)
						Tyovuoroot::model()->updatebypk($v->id, array('toistuva_id' => '0'));
				}

				return $this->continue();


			case 4:
				if (!static::getSessionVar("cdone", null, $this->step)) {

					$item = $this->dataItemHelper("tstv", function() {
						return Yii::app()->db1->createCommand()
							->select("id, pfrom, pto")
							->from("toistuvat_tyovuorot")
							->where("DATE(STR_TO_DATE(pfrom, '%d.%m.%Y')) < '{$this->startday}' AND DATE(STR_TO_DATE(pto, '%d.%m.%Y')) < '{$this->startday}'")
							->queryAll();
					});

					if ($item === true) {
						static::setSessionVar("cdone", true, $this->step);
						return $this->continue();
					} elseif ($item === false) {
						return $this->continue();
					}

					$toistuva_id = $item['id'];

					//echo 'Ketju: '.$item['id'].', Pfrom: '.$item['pfrom'].', Pto: '.$item['pto'].'<br>';

					ToistuvatTyovuorot::model()->deletebypk($toistuva_id);
					$this->out_info("Ketju " . $toistuva_id . ", POISTETAAN, koska ketjun lopetuspäivä ajemmin kun " . date("d.m.Y", strtotime($this->startday)));

					// Update
					$criteria = new CDbCriteria;
					$criteria->select = "id";
					$criteria->condition = " toistuva_id!=0 AND toistuva_id='" . $toistuva_id . "' ";
					$tvupd = Tyovuoroot::model()->findAll($criteria);

					$this->out_info("MUOKATAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " --> toistuva_id=0");
					foreach ($tvupd as $v)
						Tyovuoroot::model()->updatebypk($v->id, array('toistuva_id' => '0'));
					return $this->continue();
				} else {

					$item = $this->dataItemHelper("tstv", function() {
						return Yii::app()->db1->createCommand()
							->select("id, pfrom, pto")
							->from("toistuvat_tyovuorot")
							->where("DATE(STR_TO_DATE(pfrom, '%d.%m.%Y')) < '{$this->startday}'")
							->andWhere("id NOT IN(SELECT toistuva_id FROM sivex_tvuoro)")
							->queryAll();
					});

					if ($item === true) {
						return $this->continue(5);
					} elseif ($item === false) {
						return $this->continue();
					}

					$toistuva_id = $item['id'];
					//echo 'Ketju: '.$item['id'].', Pfrom: '.$item['pfrom'].', Pto: '.$item['pto'].'<br>';
					ToistuvatTyovuorot::model()->deletebypk($toistuva_id);
					$this->out_info("Ketju " . $toistuva_id . ", POISTETAAN, koska ketjusta ei löytyi yhtään työvuoroa");
					return $this->continue();
				}

			case 5:

				$item = $this->dataItemHelper("tvr", function() {
					return Yii::app()->db1->createCommand()
						->select("poistettu_pvm,id,tyopaari,tid")
						->from("toistuvat_tyovuorot")
						->where("poistettu_pvm!='' AND new_poistettu_pvm IS NULL")
						->queryAll();
				});

				if ($item === true) {
					return $this->continue(6);
				} elseif ($item === false) {
					return $this->continue();
				}

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

				return $this->continue();

			case 6:

				switch (static::getSessionVar("istep", 0, $this->step)) {
					case 0:
						$this->out_primary("Poistetaan tulevaisuuden työvuorot joilla toistuva_id != 0.");
						$this->out_primary("Kaikkien menneiden työvuorojen toistuva_id asetetaan = 0.");
						static::setSessionVar("istep", 1, $this->step);
						return $this->continue();
					case 1:
						$count = static::getSessionVar("count", 0, $this->step);
						$date_ymd = date("Y-m-d", strtotime($this->startday));
						if (!Yii::app()->db1->createCommand("DELETE FROM sivex_tvuoro WHERE toistuva_id != '0' AND DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) >= '$date_ymd' LIMIT 25")->execute()) {
							static::setSessionVar("istep", 2, $this->step);
							$this->out_primary("Tulevaisuuden ketjuihin kuuluvat työvuorot poistettu.");
							$this->out_info("Päivitetään toistuva_id=0 kaikille työvuoroille.");
						} else {
							static::setSessionVar("count", $count, $this->step);
							$this->out_primary("Poistettu: " . ($count += 25) . " kpl");
						}
						return $this->continue();
					case 2:
						Yii::app()->db1->createCommand("UPDATE sivex_tvuoro SET toistuva_id='0' WHERE toistuva_id!='0'")->execute();
						return $this->continue(99);
				}

			default:
				return $this->continue(99);
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

		$this->out_info("POISTETAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " ja PVM >= " . date("Y-m-d", strtotime($item['pvm'] . " this week monday")));

		foreach ($tvdel as $v)
			Tyovuoroot::model()->deletebypk($v->id);

		// Update
		$criteria = new CDbCriteria;
		$criteria->select = "id";
		$criteria->condition = " toistuva_id!=0 AND toistuva_id='" . $toistuva_id . "' ";
		$tvupd = Tyovuoroot::model()->findAll($criteria);

		$this->out_info("MUOKATAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " --> toistuva_id=0");

		foreach ($tvupd as $v)
			Tyovuoroot::model()->updatebypk($v->id, array('toistuva_id' => '0'));
	}

	/**
	 * Create data for step and return next item for cycle.
	 */
	private function dataItemHelper(string $name, callable $initializer)
	{
		$key_data = "data_{$name}";
		$key_current = "{$key_data}_current";

		$data = static::getSessionVar($key_data, null, $this->step);
		$current = static::getSessionVar($key_current, 0, $this->step);

		if ($data == null) {
			$this->out_debug("Initializing data: $name");
			$data = array_values($initializer());
			$current = 0;
			static::setSessionVar($key_data, $data, $this->step);
			$this->out_info("Haettu %s (yht: %s)", $name, count($data));
		}
		if ($current >= count($data))
			return true;
		elseif (!isset($data[$current])) {
			$this->out_error("Koodissa virhe; $name array muuttunut ajon aikana.");
			return false;
		}

		$this->out_progress("Jäljellä: %d", (count($data) - $current));
		$next = $data[$current];
		static::setSessionVar($key_current, ++$current, $this->step);
		return $next;
	}

	// private function transaction(callable $action)
	// {
	// 	$this->out_info("TRANSACTION: Begin");
	// 	$transaction = Yii::app()->db1->beginTransaction();
	// 	$commit = false;
	// 	try {
	// 		$commit = $action();
	// 	} catch (\Exception $ex) {
	// 		$this->out_error("Error during transaction: " . $ex->getMessage());
	// 	} finally {
	// 		if ($commit) {
	// 			$this->out_info("TRANSACTION: Commit");
	// 			$transaction->commit();
	// 		} else {
	// 			$this->out_info("TRANSACTION: Rollback");
	// 			$transaction->rollback();
	// 		}
	// 	}
	// }

	/**
	 * Continue to next cycle or step.
	 */
	private function continue(?int $next_step = null)
	{
		$next_step = $next_step ?: $this->step;
		$errors = false;
		$log_text = "";

		foreach ($this->output as $entry) {
			$log_text .= sprintf("%s (%s): %s\n", $entry['time'], abs($entry['type']), $entry['text']);
			if (abs($entry['type']) == 1) $errors = true;
		}

		try {
			$log = fopen($this->logpath, "a");
			fwrite($log, $log_text);
		} catch (\Exception $ex) {
			$this->out_error(sprintf("Lokitiedoston (%s) kirjoittaminen epäonnistui: %s", $this->logpath, $ex->getMessage()));
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
	 * Append to output (status: primary header, not output to console.) Type:
	 *   0    status (progress)
	 *   1    manually or automatically fixable errors/exceptions; if status: step or operation has failed, requires intervention
	 *   2    warnings that dont stop execution; if status: warning status (require verification to continue)
	 *   3    command success; if status: success status (generally step finished)
	 *   4    highlighted progress; if status: progress status, e.g. remaining items
	 *   5    significant information, but not primary
	 *   6    debug info, barely used, spam a lot of stuff
	 */
	private function out(int $type, string $fmt, ...$args)
	{
		$time = time();
		$text = '';

		foreach($args as $k => $v)
			if (is_array($v) || is_object($v))
				$args[$k] = json_encode($v);

		try {
			array_unshift($args, $fmt);
			if (!empty($text = call_user_func_array('sprintf', $args))) {
				$text = $text;
			} else {
				$text = sprintf("(unable to format) %s, params: %s", $fmt, join(', ', $args));
				$type = min(2, $type);
			}
		} catch (\Exception $ex) {
			$text = sprintf("(error on format) %s, params: %s, error: %s", $fmt, join(', ', $args), $ex->getMessage());
			$type = min(2, $type);
		}

		$this->output[] = [
			'time' => date('H:i:s', $time),
			'type' => max(0, min(6, $type)),
			'text' => $text
		];
	}

	private function out_progress(string $fmt, ...$args) { array_unshift($args, 0, $fmt); call_user_func_array([$this, 'out'], $args); }
	private function out_error(string $fmt, ...$args)    { array_unshift($args, 1, $fmt); call_user_func_array([$this, 'out'], $args); }
	private function out_warning(string $fmt, ...$args)  { array_unshift($args, 2, $fmt); call_user_func_array([$this, 'out'], $args); }
	private function out_success(string $fmt, ...$args)  { array_unshift($args, 3, $fmt); call_user_func_array([$this, 'out'], $args); }
	private function out_primary(string $fmt, ...$args)  { array_unshift($args, 4, $fmt); call_user_func_array([$this, 'out'], $args); }
	private function out_info(string $fmt, ...$args)     { array_unshift($args, 5, $fmt); call_user_func_array([$this, 'out'], $args); }
	private function out_debug(string $fmt, ...$args)    { array_unshift($args, 6, $fmt); call_user_func_array([$this, 'out'], $args); }


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
