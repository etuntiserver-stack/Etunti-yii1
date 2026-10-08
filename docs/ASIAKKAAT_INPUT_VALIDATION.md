# Asiakkaat — customer input validation and Netvisor errors

Implemented 2026-10-08. Scope: only the Asiakkaat model and its customer update form/controller. Other models and controllers are unchanged.

- protected/models/Asiakkaat.php: beforeValidate() trims leading/trailing whitespace in an explicit allowlist of one-line customer fields. It includes ordinary spaces, NBSP, zero-width spaces and BOM. Inner spaces are retained. Passwords, tokens, notes, JSON and multiline fields are excluded.
- Calls that bypass Yii validation, for example updateByPk() and save(false), do not run beforeValidate().
- protected/controllers/AsiakkaatController.php: a failed Netvisor update keeps the form open without retrying the API request. The original backend failure reason stays in the server log. A rejected invoice operator ID is associated with valittajan_tunnus.
- protected/views/asiakkaat/_form.php: a Bootstrap alert displays human-readable save errors, and the operator input is highlighted on a matching Netvisor error. Technical response details are not shown to users.
- A Netvisor failure happens after the customer is saved to the eTunti database; the alert says the local save succeeded but Netvisor failed.
- The customer creation Netvisor follow-up flow is not changed.

This normalization is intentionally limited to Asiakkaat; no global Yii base model hook was added.
