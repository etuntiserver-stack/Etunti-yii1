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
	private $transaction;
	private $step;
	private $output;
	private $cycle;

	public function __construct(bool $reset = false)
	{
		$this->transaction = static::getSessionVar("transaction");
		$this->step = static::getSessionVar("step", 0);
		$this->output = [];
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
		if ($this->step !== 0 && !$this->transaction && !($this->transaction = Yii::app()->db1->getCurrentTransaction())) {
			$this->cout(1, "The transaction object has disappeared during migration.");
			return $this->cdone(-3);
		}

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
				// 	return $this->cdone(-2, "Transaction already exists.", "Active transaction found when trying to start migration.");
				// $this->cout("Creating main transaction object.");
				// $this->transaction = Yii::app()->db1->beginTransaction();

			case 1: // Step 1: Scan for problems

				// Same code from Roman, without deletions or performed actions, just to see if there are any problems.
				$count = static::getSessionVar("count", 1, 1);
				static::setSessionVar("count", ++$count, 1);
				if ($count >= 10) {
					static::setSessionVar("count", 0, 1);
					return $this->cdone(2, "Done counting to 10, going to S2.");
				}
				return $this->cdone();


			case 2:
				$count = static::getSessionVar("count", 1, 2);
				static::setSessionVar("count", ++$count, 2);
				$this->cout("Count is at $count");
				if ($count == 12)
					$this->cerr("Error at count $count");
				if ($count >= 25) {
					static::setSessionVar("count", 0, 2);
					return $this->cdone(1, "Done counting to 25, going back to S1.");
				}
				return $this->cdone();


				//* ---- Errors ----


			// case -2: // Starting migration but transaction already exists. Rollback and clear old transaction and return to step 0.
			// 	$this->cout(3, "Clearing old transaction object.");

			// 	if (!$this->transaction && !($this->transaction = Yii::app()->db1->getCurrentTransaction())) {
			// 		$this->cout(4, "Transaction no longer exists; no action required.");
			// 	} else {
			// 		$this->transaction->rollback();
			// 		unset($this->transaction);
			// 		$this->cout("Rolled back and cleared old transaction object.");
			// 	}

			// 	return $this->cdone(0);


			// case -3: // Migration in progress but no transaction. Return to step 0 to restart transaction.
			// 	$this->cout("Transaction object disappeared during previous cycle. Was the database surely locked for the migration?");
			// 	$this->cout("The migration has to be started again from where the transaction is first needed.");
			// 	return $this->cdone(0);


			default:
				$this->cerr("Encountered unknown " . ($this->step >= 0 ? "step number" : "error code") . " {$this->step}. Returning to step 0.");
				return $this->cdone(0);
		}
	}

	private function cout($type_or_data = null, $other_one = null)
	{
		$time = date('H:i:s', time());
		if (is_array($type_or_data)) {
			foreach($type_or_data as $item) {
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
		$time = date('H:i:s', time());
		foreach ($entries as $entry) {
			if (is_array($entry[0])) {
				foreach ($entry[0] as $sube) {
					$this->output[] = [
						'time' => $time,
						'type' => max(1, min(5, ($sube[0]))),
						'text' => $sube[1],
					];
				}
			} else {
				$this->output[] = [
					'time' => $time,
					'type' => max(1, min(5, ($entry[0]))),
					'text' => $entry[1],
				];
			}
		}
	}

	private function cdone(?int $next_step = null, array ...$moreout)
	{
		$this->couts($moreout);

		static::setSessionVar("step", $next_step);
		static::setSessionVar("cycle", $this->cycle + 1, $this->step);

		return [
			'step' => $this->step,
			'cycle' => $this->cycle,
			'next' => $next_step ?: $this->step,
			'output' => $this->output,
			'time' => date('H:i:s', time())
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
		if (!isset(Yii::app()->session[$key]) || !is_array(Yii::app()->session[$key]))
			Yii::app()->session[$key] = [];
		if (!in_array($final_key, Yii::app()->session[$key]))
			Yii::app()->session[$key][] = $final_key;
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

		switch (($step && is_numeric($step) ? $step : $this->step)) {

				// Steps
			case 0:
				$result['label'] = 'Initialization';
				$result['action'] = 'Clear previous session variables and initialize transaction.';
			case 1:
				$result['label'] = 'Problem Scan';
				$result['action'] = 'Scan for general problems in all chains.';
			case 999:
				$result['label'] = 'Migration Finish';
				$result['action'] = 'Commit transaction and finish.';

				// Errors
			case -2:
				$result['problem'] = 'Migration was started but a transaction object already exists.';
				$result['action'] = 'Rollback and clear old transaction.';
			case -3:
				$result['problem'] = 'Migration in progress but no transaction.';
				$result['action'] = 'Restart migration due to missing transaction object.';
		}

		return $result;
	}
}
