<?php
namespace Plugins\MassVoting;

// Disable direct access
if (!defined('APP_VERSION'))
    die("Yo, what's up?");

/**
 * Stats model
 *
 * @version 1.0
 * @author AmazCode.ooo (https://AmazCode.ooo)
 *
 */
class StatsModel extends \DataList
{
	/**
	 * Initialize
	 */
	public function __construct()
	{
		$this->setQuery(\DB::table(TABLE_PREFIX."hypervote_stats"));
	}
}
