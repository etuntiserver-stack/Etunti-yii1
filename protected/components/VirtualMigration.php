<?php

/**
 * Worker for migration to virtual shifts.
 *
 * @property int $verbosity_level 1 most significant, 4 least significant. (primary, notice, info, debug)
 * @property int $step
 * @property CDbTransaction $transaction
 * @property array $output
 * @property array $errors
 * @property int $cycle
 * @property int $cycle_start_time
 * @property array $cycle_output
 * @property array $cycle_errors
 */
class VirtualMigration extends CComponent
{
	private static $transaction;

	private $verbosity_level;
	private $step;
	private $output;
	private $errors;

	private $cycle;
	private $cycle_start_time;
	private $cycle_output;
	private $cycle_errors;

	public function __construct(int $verbosity_level = 4)
	{
		static::$transaction = Yii::app()->db1->getCurrentTransaction();
		$this->verbosity_level = $verbosity_level;
		$this->step = static::getSessionVar("step", 0);
		$this->output = [];
		$this->errors = [];
	}


	//****************************************************************************
	//* Static Helpers
	//****************************************************************************
	#region Static Helpers

	private static function getKey(string $key, ?int $step = null)
	{
		$domain = strtolower(Yii::app()->user->domain);
		$stepstr = ($step !== null) ? "_step_{$step}_" : "_";
		return "vmigrate_{$domain}{$stepstr}{$key}";
	}

	// public static function getCacheItem(string $key, $default = false, ?int $step = null, bool $set_default = true)
	// {
	// 	if ($item = Yii::app()->cache->get(static::getKey($key, $step)) || $default === false)
	// 		return $item;
	// 	return ($set_default) ? static::setCacheItem($key, $default, $step) : $default;
	// }

	// private static function setCacheItem(string $key, $value, ?int $step = null)
	// {
	// 	Yii::app()->cache->set(static::getKey($key, $step), $value);
	// 	return $value;
	// }

	// private static function clearCacheItem(string $key, ?int $step = null)
	// {
	// 	Yii::app()->cache->delete(static::getKey($key, $step));
	// }

	public static function getSessionVar(string $key, $default = false, ?int $step = null, bool $set_default = true)
	{
		$final_key = static::getKey($key, $step);
		if (isset(Yii::app()->session[$final_key]))
			return Yii::app()->session[$final_key];
		return ($default !== false && $set_default) ?
			static::setSessionVar($key, $default, $step) : $default;
	}

	public static function setSessionVar(string $key, $value, ?int $step = null)
	{
		$final_key = static::getKey($key, $step);
		Yii::app()->session[$final_key] = $value;

		$vars_key = static::getKey("vars");
		$vars = json_decode(Yii::app()->session[$vars_key] ?? [], true);
		if (!in_array($final_key, $vars)) $vars[] = $final_key;
		Yii::app()->session[$vars_key] = json_encode($vars);

		return $value;
	}

	public static function clearSessionVars()
	{
		// $key = static::getKey("");
		// foreach (array_keys(Yii::app()->session) as $session_key) {
			// if (strpos($session_key, $key) === 0) {
				// unset(Yii::app()->session[$session_key]);
				// Yii::app()->session[$session_key] = 0;
			// }
		// }
		$vars_key = static::getKey("vars");
		$vars = json_decode(Yii::app()->session[$vars_key] ?? [], true);
		foreach($vars as $var_key) {
			Yii::app()->session[$var_key] = 0;
		}
	}

	private static function toArray($item)
	{
		$result = [];
		if (!empty($item)) {
			if (is_array($item))
				$result = $item;
			elseif (is_string($item))
				$result[] = $item;
			else
				$result[] = print_r($item, true);
		}
		return $result;
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

	#endregion


	//****************************************************************************
	//* Script
	//*
	//* New migration script, based on actionBeta (per stage mass modifications),
	//* modified to loop per chain and do all work on a single chain at once.
	//* Called by AJAX from actionVirtual_migration() and associated view.
	//****************************************************************************
	#region Script

	public function doNextStep()
	{
		$start_time_sync = time();

		// Init cycle output.
		$this->cycle_output = [];
		$this->cycle_errors = [];

		if ($this->step === 0) {
			// Unset session variables from possible previous run, when starting
			// from step 0. This happens only when pressing 'BEGIN'.
			static::clearSessionVars();
			$this->cout("Cleared previous migration session variables.");
		}

		// Init cycle.
		$this->cycle = static::getSessionVar("cycle", 1, $this->step);
		$this->cycle_start_time = $start_time_sync;

		// Use getSessionVar with default value to set time only if not already exists.
		static::getSessionVar("start_time", $start_time_sync, $this->step);
		static::getSessionVar("start_time", $start_time_sync);

		// Do cycle.
		$result = $this->doStep($this->step);
		static::setSessionVar("cycle", ++$this->cycle, $this->step);
		return $result;
	}

	private function doStep(int $step)
	{
		// Check that transaction is still there.
		// if ($this->step !== 0 && !static::$transaction && !(static::$transaction = Yii::app()->db1->getCurrentTransaction()))
		// 	return $this->cdone(-3, "Transaction has been lost.", "The transaction object has disappeared during migration.");

		// Do step.
		switch ($this->step) {

			case 0: // Step 0: Initialization.
				// Table optimization: disabled. Tables in our database don't support
				// optimize; the tables are re-created and analyzed instead.
				// optimization is almost never worth doing on InnoDB tables.
				// Yii::app()->db1->createCommand("OPTIMIZE TABLE sivex_tvuoro")->execute();
				// Yii::app()->db1->createCommand("OPTIMIZE TABLE toistuvat_tyovuorot")->execute();
	
				$this->cout("Clearing previous migration session variables.");
	
				// Init transaction (transaction disabled for now, until caching works.)
				// if (static::$transaction || Yii::app()->db1->getCurrentTransaction())
				// 	return $this->cdone(-2, "Transaction already exists.", "Active transaction found when trying to start migration.");
				// $this->cout("Creating main transaction object.");
				// static::$transaction = Yii::app()->db1->beginTransaction();

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

			// 	if (!static::$transaction && !(static::$transaction = Yii::app()->db1->getCurrentTransaction())) {
			// 		$this->cout(4, "Transaction no longer exists; no action required.");
			// 	} else {
			// 		static::$transaction->rollback();
			// 		unset(static::$transaction);
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

	private function cout($verbosity_or_text = null, $other_one = null)
	{
		$entry = ['time' => date('H:i:s', time())];
		if (is_numeric($verbosity_or_text) && is_string($other_one)) {
			$entry['text'] = print_r($other_one, true);
			$entry['verbosity'] = (int) $verbosity_or_text;
		} else {
			$entry['text'] = print_r($verbosity_or_text, true);
			$entry['verbosity'] = (is_numeric($other_one) ? (int) $other_one : 1);
		}
		$this->output[] = $entry;
		if ($entry['verbosity'] <= $this->verbosity_level)
			$this->cycle_output[] = $entry;
	}

	private function cerr(string $text)
	{
		$entry = ['time' => date('H:i:s', time()), 'text' => $text];
		$this->errors[] = $entry;;
		$this->cycle_errors[] = $entry;
	}

	private function cdone(?int $next_step = null, $moreout = null, $moreerr = null)
	{
		foreach (static::toArray($moreout) as $m)
			$this->cout(1, $m);
		foreach (static::toArray($moreerr) as $e)
			$this->cerr($e);

		$time_sync = time();
		$duration = $time_sync - $this->cycle_start_time;
		$elapsed = static::getSessionVar("elapsed", 0, $this->step) + $duration;
		$elapsed_total = static::getSessionVar("elapsed_total", 0, null, true) + $duration;
		static::setSessionVar("elapsed", $elapsed, $this->step);
		static::setSessionVar("elapsed_total", $elapsed_total);

		$next_step = $next_step ?: $this->step;
		if ($next_step != $this->step)
			static::setSessionVar("step", $next_step);

		return [
			'step' => $this->step,
			'cycle' => $this->cycle,
			'next' => $next_step ?: $this->step,
			'output' => $this->cycle_output,
			'errors' => $this->cycle_errors,
			'time' => date('H:i:s', $time_sync),
			'duration' => $duration,
			'elapsed_step' => $elapsed,
			'elapsed_total' => $elapsed_total
		];
	}

	#endregion
}
