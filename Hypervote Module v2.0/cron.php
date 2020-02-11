<?php
namespace Plugins\MassVoting;
use dgr\nohup\Nohup;

// Disable direct access
if (!defined('APP_VERSION'))
    die("Yo, what's up?");

/**
 * Add cron task to start massvoting
 *
 * @version 1.0
 * @author AmazCode.ooo
 *
 */
function addCronTask() {
    // Clean old database stats
    $sql = "DELETE FROM `".TABLE_PREFIX."hypervote_stats` WHERE date < \"".date("Y-m-d H:i:s", time() - 604800)."\" ";
    $pdo = \DB::pdo();
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    require_once __DIR__."/models/SchedulesModel.php";
    require_once __DIR__."/models/LogModel.php";
    require_once __DIR__."/models/StatModel.php";
    require_once PLUGINS_PATH . "/" . IDNAME . "/vendor/autoload.php";

    // Get hypervote schedules
    $Schedules = new SchedulesModel;
    $Schedules->where("is_active", "=", 1)
              ->where("is_running", "=", 0)
              ->where("schedule_date", "<=", date("Y-m-d H:i:s"))
              ->where("end_date", ">=", date("Y-m-d H:i:s"))
              ->orderBy("last_action_date", "ASC")
              ->setPageSize(15) // required to prevent server overload
              ->setPage(1)
              ->fetchData();

    if ($Schedules->getTotalCount() < 1) {
        // There is not any active schedule
        return false;
    }

    $as = [__DIR__."/models/ScheduleModel.php", __NAMESPACE__."\ScheduleModel"];

    foreach ($Schedules->getDataAs($as) as $sc) {
        // Double cron task prevention
        $sc->set("is_running", 1)
           ->save();
    }

    foreach ($Schedules->getDataAs($as) as $sc) {
        $Log = new LogModel;
        $Account = \Controller::model("Account", $sc->get("account_id"));
        $User = \Controller::model("User", $sc->get("user_id"));

        // Set default values for the log (not save yet)...
        $Log->set("user_id", $User->get("id"))
            ->set("account_id", $Account->get("id"))
            ->set("status", "error");

        // Check the account
        if (!$Account->isAvailable() || $Account->get("login_required")) {
            // Account is either removed (unexected, external factors)
            // Or login reqiured for this account
            // Deactivate schedule
            $sc->set("is_active", 0)
               ->set("is_running", 0)
               ->save();

            // Log data
            $Log->set("data.error.msg", __("Activity has been stopped"))
                ->set("data.error.details", __("Re-login is required for the account."))
                ->save();
            continue;
        }

        // Check the user
        if (!$User->isAvailable() || !$User->get("is_active") || $User->isExpired()) {
            // User is not valid
            // Deactivate schedule
            $sc->set("is_active", 0)
               ->set("is_running", 0)
               ->save();

            // Log data
            $Log->set("data.error.msg", __("Activity has been stopped"))
                ->set("data.error.details", __("User account is either disabled or expired."))
                ->save();
            continue;
        }

        if ($User->get("id") != $Account->get("user_id")) {
            // Unexpected, data modified by external factors
            // Deactivate schedule
            $sc->set("is_active", 0)
               ->set("is_running", 0)
               ->save();
            continue;
        }

        // Check user access to the module
        $user_modules = $User->get("settings.modules");
        if (!is_array($user_modules) || !in_array(IDNAME, $user_modules)) {
            // Module is not accessible to this user
            // Deactivate schedule
            $sc->set("is_active", 0)
               ->set("is_running", 0)
               ->save();

            // Log data
            $Log->set("data.error.msg", __("Activity has been stopped"))
                ->set("data.error.details", __("Module is not accessible for your account."))
                ->save();
            continue;
        }

        // Parse targets
        $targets = @json_decode($sc->get("target"));

        if (is_null($targets)) {
            // Couldn't find any target
            // Log data
            $Log->set("data.error.msg", __("Couldn't find any target for massvoting"))
                ->save();
            $sc->set("is_active", 0)
               ->set("is_running", 0)
               ->save();
            continue;
        }

        if (count($targets) < 1) {
            // Couldn't find any target
            // Log data
            $Log->set("data.error.msg", __("Couldn't find any target for massvoting"))
                ->save();
            $sc->set("is_active", 0)
               ->set("is_running", 0)
               ->save();
               continue;
        }

        $cmd = "wget --quiet -O /dev/null " . APPURL . "/e/" . IDNAME . "/massvoting/" . $sc->get("account_id") . "." . $sc->get("user_id") . "/";

        try {
            $process = Nohup::run($cmd);
        } catch (\Exception $e) {
            $Log->set("data.error.msg", __("MassVoting loop failed"))
                ->set("data.error.details", __("Nohup process not started.") . $e->getMessage())
                ->save();
            $sc->set("is_active", 0)
                ->set("is_running", 0)
                ->save();
             continue;
        }

        if ($process->isRunning()) {
            $sc->set("process_id", $process->getPid())
               ->save();
        } else {
            $Log->set("data.error.msg", __("MassVoting loop failed"))
                ->set("data.error.details", __("Nohup process not started."))
                ->save();
            $sc->set("is_active", 0)
               ->set("is_running", 0)
               ->save();
            continue;
        }
    }
}
\Event::bind("cron.add", __NAMESPACE__."\addCronTask");