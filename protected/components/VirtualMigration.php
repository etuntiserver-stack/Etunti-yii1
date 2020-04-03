<?php

/**
 * Worker for migration to virtual shifts.
 *
 * @property CDbTransaction $transaction
 * @property int $step Step number.
 * @property array $output Output for one cycle.
 * @property int $cycle Cycle number.
 */
class VirtualMigration extends CComponent
{
	// private $transaction;
	private $step;
	private $output;
	private $cycle;
	private $log_path;
	private $startday;

	public function __construct(bool $reset = false)
	{
		// $this->transaction = static::getSessionVar("transaction");
		$this->step = static::getSessionVar("step", 0);
		$this->output = [];
		$this->log_path = Yii::app()->user->domain . '_migration.log';
		$this->startday = date("Y-m-d", strtotime("next monday"));
	}

	public function doNextStep()
	{
		// Clear previous session variables when starting fresh.
		if ($this->step === 0)
			static::clearSessionVars();

		// Init cycle.
		$this->output = [];
		$this->cycle = static::getSessionVar("cycle", 1, $this->step);

		// Check that transaction is still there.
		// if ($this->step !== 0 && !$this->transaction && !($this->transaction = Yii::app()->db1->getCurrentTransaction()))
		// 	return $this->cdone(-3, [1, "The transaction object has disappeared during migration."]);

		// Do step.
		switch ($this->step) {

			case 0: // Step 0: Initialization.
				// Table optimization: disabled. Tables in our database don't support
				// optimize; the tables are re-created and analyzed instead.
				// optimization is almost never worth doing on InnoDB tables.
				// Yii::app()->db1->createCommand("OPTIMIZE TABLE sivex_tvuoro")->execute();
				// Yii::app()->db1->createCommand("OPTIMIZE TABLE toistuvat_tyovuorot")->execute();

				$this->cout("Cleared previous migration session variables.");

				// Init transaction (transaction disabled for now, until caching works.)
				// if ($this->transaction || Yii::app()->db1->getCurrentTransaction())
				// 	return $this->cdone(-2, [2, "Transaction object already exists."]);

				// $this->cout(4, "Creating main transaction object.");
				// static::setSessionVar("transaction", $this->transaction = Yii::app()->db1->beginTransaction());
				// $this->transaction->active = true;
				return $this->cdone(2);

			case 1: // Clone tables to temporary table with suffix: _migrate

				// Do changes to main table for now, for testing on staging.
				return $this->cdone(2, "Cloning tables is disabled for now. Changes will be made to main tables.");

				$tables = ['sivex_tvuoro', 'toistuvat_tyovuorot'];
				$cmd_existing_table_check = Yii::app()->db->createCommand("SHOW TABLES LIKE ':tn'");
				$cmd_table_clone = Yii::app()->db->createCommand("CREATE TABLE :tn LIKE :sn");
				$cmd_table_insert = Yii::app()->db->createCommand("INSERT :tn SELECT * FROM :sn");

				$tables_exist = false;
				foreach ($tables as $table_name) {
					$target_name = "{$table_name}_migrate";
					if (!empty($cmd_existing_table_check->query([':tn' => $target_name]))) {
						$this->cout(2, sprintf("Target table %s already exists.", $target_name));
						$tables_exist = true;
					}
				}

				if ($tables_exist) {
					return $this->cdone(-3, "Target tables exist and must be dropped.");
				}

				foreach ($tables as $table_name) {
					$target_name = "{$table_name}_migrate";
					$params = [':tn' => $target_name, ':sn' => $table_name];

					try {
						$this->cout(4, "Cloning $table_name table structure to $target_name");
						$cmd_table_clone->query($params);
						$this->cout(4, "Inserting data to $target_name from $table_name");
						$cmd_table_insert->query($params);
					} catch (\Exception $ex) {
						$this->cout(1, "Error: " . $ex->getMessage());
						return $this->cdone();
					}
				}

				return $this->cdone(2, "Clone tables: sivex_tvuoro_migrate, toistuvat_tyovuorot_migrate");

			case 2:
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

				$toistuvat = Yii::app()->db1->createCommand()
					->select("id,tid,tyopaari")
					->from("toistuvat_tyovuorot")
					//->where()
					->queryAll();

				$toist_arr = [];
				foreach ($toistuvat as $item)
					$toist_arr[$item['id']] = $item;

				$korjattu = 0;
				$stop = false;
				$this->cout(4, "Total: " . count($tvr));
				foreach ($tvr as $item) {
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
								$this->cout(2, "Ketju " . $toistuva_id . ", Uusi aloituspäivä: " . date("d.m.Y", strtotime($item['pvm'] . " this week monday")));
								$this->updateAndDelete($toistuva_id, $item);
							}
						} else {

							ToistuvatTyovuorot::model()->updatebypk($toistuva_id, array('pfrom' => date("d.m.Y", strtotime($item['pvm'] . " this week monday"))));
							$this->cout(2, "Ketju " . $toistuva_id . ", Uusi aloituspäivä: " . date("d.m.Y", strtotime($item['pvm'] . " this week monday")));
							$this->updateAndDelete($toistuva_id, $item);
						}
					} else {
						$this->cout(2, 'Ketjussa: ' . $toistuva_id . ' ONGELMA');
						$stop = true;
					}
				}

				if ($stop)
					return $this->cdone(null, [1, "-"]);
				else
					return $this->cdone(3, "Fixed $korjattu kpl");


			case 3:
				$tvr = Yii::app()->db1->createCommand()
					->select("id, tid, pvm, toistuva_id")
					->from("sivex_tvuoro")
					->group("toistuva_id")
					->order("DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) DESC")
					->andWhere("toistuva_id!=0")
					->queryAll();

				$toistuvat = Yii::app()->db1->createCommand()
					->select("id,tid,tyopaari, pfrom, pto")
					->from("toistuvat_tyovuorot")
					//->where()
					->queryAll();

				$toist_arr = [];
				foreach ($toistuvat as $item)
					$toist_arr[$item['id']] = $item;

				$korjattu = 0;
				$this->cout(4, 'Yhteensä ' . count($tvr));
				foreach ($tvr as $item) {
					$toistuva_id = $item['toistuva_id'];
					if (isset($toist_arr[$toistuva_id])) {

						if (date("Ymd", strtotime($toist_arr[$toistuva_id]['pto'])) < date("Ymd", strtotime($this->startday))) {

							ToistuvatTyovuorot::model()->deletebypk($toistuva_id);
							$this->cout(4, "Ketju " . $toistuva_id . ", POISTETAAN, koska ketjun lopetuspäivä ajemmin kun " . date("d.m.Y", strtotime($this->startday)));

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

							// Toistuva pto on > startday
							if (date("Ymd", strtotime($item['pvm'])) < date("Ymd", strtotime($this->startday))) {

								ToistuvatTyovuorot::model()->deletebypk($toistuva_id);

								$this->cout(4, "Ketju " . $toistuva_id . ", POISTETAAN, koska viimeinen työvuoro oli ajemmin kun " . date("d.m.Y", strtotime($this->startday)));

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

								$this->cout(4, 'Ei selkeä ongelma ' . $toistuva_id);
							}
						}
					} else {

						// Update
						$criteria = new CDbCriteria;
						$criteria->select = "id";
						$criteria->condition = " toistuva_id!=0 AND toistuva_id='" . $toistuva_id . "' ";
						$tvupd = Tyovuoroot::model()->findAll($criteria);

						$this->cout(4, "MUOKATAAN Työvuorot jolla toistuva_id=" . $toistuva_id . " --> toistuva_id=0");

						foreach ($tvupd as $v) {
							Tyovuoroot::model()->updatebypk($v->id, array('toistuva_id' => '0'));
						}
					}
				}

				return $this->cdone(4);


			case 4:
				$tvr = Yii::app()->db1->createCommand()
					->select("poistettu_pvm,id,tyopaari,tid")
					->from("toistuvat_tyovuorot")
					->where("poistettu_pvm!='' AND new_poistettu_pvm IS NULL")
					->queryAll();
				$i = 0;
				foreach ($tvr as $arvo) {
					$i++;
					$poistetut_pvms = json_decode($arvo['poistettu_pvm'], true);
					if (count($poistetut_pvms) == 0)
						continue;

					// <-- Tids
					$tids = [];
					if (!empty($arvo['tyopaari'])) {
						foreach (json_decode($arvo['tyopaari'], true) as $tid) {
							$tids[$tid] = $tid;
						}
						$tids[$arvo['tid']] = $arvo['tid'];
					} else {
						$tids[$arvo['tid']] = $arvo['tid'];
					}
					$new_poistettu_pvm = [];
					foreach ($tids as $tid) {
						foreach ($poistetut_pvms as $k => $v) {
							if (date("Ymd", strtotime($v)) > date("Ymd")) // Oikein
								$new_poistettu_pvm[$tid][$v] = [
									'tid' => $tid,
									'pvm' => $v,
									'syy' => [
										'text' => '',
										'user' => '',
										'date' => ''
									]
								];
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
					ToistuvatTyovuorot::model()->updatebypk($arvo['id'], array('poistettu_pvm' => '', 'new_poistettu_pvm' => $new_poistettu_pvm_arvo));
				}

				return $this->cdone(5);

			case 5:
				Yii::app()->db1->createCommand(
					"DELETE FROM sivex_tvuoro WHERE toistuva_id!='0' AND DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) >= '" . date("Y-m-d", strtotime($this->startday)) . "'"
				)
					->execute();

				Yii::app()->db1->createCommand("UPDATE sivex_tvuoro SET toistuva_id='0' WHERE toistuva_id!='0'")
					->execute();

				return $this->cdone(99);


			// TRANSACTIONS DISABLED DUE TO MALFUNCTION -_-

			// case 6:
			// 	$this->cout(3, "Commiting transaction");
			// 	$this->transaction->commit();
			// 	return $this->cdone(99, "Success");


			// case -2: // Starting migration but transaction already exists.
			// 	// Rollback and clear old transaction and return to step 0.
			// 	$this->cout(3, "Clearing old transaction object.");
			// 	if (!$this->transaction && !($this->transaction = Yii::app()->db1->getCurrentTransaction())) {
			// 		$this->cout(4, "Transaction no longer exists; no action required.");
			// 	} else {
			// 		$this->cout("Rolling back existing transaction object.");
			// 		$this->transaction->rollback();
			// 		unset($this->transaction);
			// 	}
			// 	return $this->cdone(0);


			// case -3: // Migration in progress but no transaction.
			// 	// Return to step 0 to restart transaction.
			// 	$this->cout("Transaction object disappeared during previous cycle. Was the database surely locked for the migration?");
			// 	$this->cout("The migration has to be started again from where the transaction is first needed.");
			// 	return $this->cdone(0);


			default:
				return $this->cdone(99);
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
					'type' => max(1, min(5, ($item['type'] ?? 3)))
				];
			}
		} elseif (is_numeric($type_or_data) && is_string($other_one)) {
			$this->output[] = [
				'time' => $time,
				'text' => $other_one,
				'type' => max(1, min(5, ($type_or_data ?? 3)))
			];
		} else {
			$this->output[] = [
				'time' => $time,
				'text' => $type_or_data,
				'type' => max(1, min(5, ($other_one ?? 3)))
			];
		}
	}

	private function couts(array ...$entries)
	{
		foreach ($entries as $entry)
			$this->cout(max(1, min(5, ($entry[0]))), $entry[1]);
	}

	private function cdone(?int $next_step = null, ...$entries)
	{
		$stop = $next_step < 0 || $next_step != $this->step;
		foreach ($entries as $entry) {
			if (is_array($entry)) {
				$l = max(1, min(5, ($entry[0])));
				$this->cout($l, $entry[1]);
				if ($l <= 2) $stop = true;
			} else {
				$this->cout($entry, ($next_step < 0) ? 1 : 3);
			}
		}

		$log = null;
		$log_text = "";

		foreach ($this->output as $entry) {
			$log_text .= sprintf("%s (%s): %s\n", $entry['time'], $entry['type'], $entry['text']);
			if ($entry['type'] <= 2) $stop = true;
		}

		try {
			$log = fopen($this->log_path, "a");
			fwrite($log, $log_text);
		} catch (\Exception $ex) {
			$this->cout(1, sprintf("Unable to write log file %s: %s", $this->log_path, $ex->getMessage()));
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
			'stop' => $stop
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
