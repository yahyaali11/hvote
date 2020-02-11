<?php
namespace Plugins\MassVoting;

// Disable direct access
if (!defined('APP_VERSION'))
    die("Yo, what's up?");

/**
 * Logs model
 *
 * @version 1.5
 * @author AmazCode.ooo (https://AmazCode.ooo)
 *
 */
class LogsModel extends \DataList
{
	/**
	 * Initialize
	 */
	public function __construct()
	{
		$this->setQuery(\DB::table(TABLE_PREFIX."hypervote_log"));
	}
}
