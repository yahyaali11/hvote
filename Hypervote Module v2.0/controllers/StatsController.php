<?php
namespace Plugins\MassVoting;

// Disable direct access
if (!defined('APP_VERSION'))
    die("Yo, what's up?");

/**
 * Stats Controller
 *
 * @version 1.7
 * @author AmazCode.ooo (https://AmazCode.ooo)
 *
 */
class StatsController extends \Controller
{
    /**
     * idname of the plugin for internal use
     */
    const IDNAME = 'massvoting';


    /**
     * Process
     */
    public function process()
    {

        $AuthUser = $this->getVariable("AuthUser");
        $Route = $this->getVariable("Route");
        $this->setVariable("idname", self::IDNAME);

        // Auth
        if (!$AuthUser){
            header("Location: ".APPURL."/login");
            exit;
        } else if ($AuthUser->isExpired()) {
            header("Location: ".APPURL."/expired");
            exit;
        }

        $user_modules = $AuthUser->get("settings.modules");
        if (!is_array($user_modules) || !in_array(self::IDNAME, $user_modules)) {
            // Module is not accessible to this user
            header("Location: ".APPURL."/post");
            exit;
        }


        // Get Account
        $Account = \Controller::model("Account", $Route->params->id);
        if (!$Account->isAvailable() ||
            $Account->get("user_id") != $AuthUser->get("id"))
        {
            header("Location: ".APPURL."/e/".self::IDNAME);
            exit;
        }
        $this->setVariable("Account", $Account);

        // Get Schedule
        require_once PLUGINS_PATH."/".$this->getVariable("idname")."/models/ScheduleModel.php";
        $Schedule = new ScheduleModel([
            "account_id" => $Account->get("id"),
            "user_id" => $Account->get("user_id")
        ]);
        $this->setVariable("Schedule", $Schedule);

        // Count schedule targets
        // And set this value in page size of DB
        $targets = $Schedule->isAvailable() ? json_decode($Schedule->get("target")) : [];
        $targets_count = count($targets);

        if (is_null($targets_count)) {
            $targets_count = 0;
        } elseif ($targets_count <= 30) {
            $targets_count = 30;
        }
        $this->setVariable("targets_count", $targets_count);

        $now = new \Moment\Moment("now", $AuthUser->get("preferences.timezone"));
        $now = $now->format("Y-m-d");

        // Get Today Stats
        require_once PLUGINS_PATH."/".self::IDNAME."/models/StatsModel.php";
        $TodayStats = new StatsModel;
        $TodayStats->setPageSize($targets_count)
                   ->setPage(1)
                   ->where("user_id", "=", $AuthUser->get("id"))
                   ->where("account_id", "=", $Account->get("id"))
                   ->where("date", "=", $now)
                   ->orderBy("view_count","DESC")
                   ->fetchData();

        $Today = [];
        $tds = [PLUGINS_PATH."/".self::IDNAME."/models/StatModel.php",
                __NAMESPACE__."\StatModel"];
        foreach ($TodayStats->getDataAs($tds) as $l) {
            $Today[] = $l;
        }

        // Weely Stats - 1 day ago
        require_once PLUGINS_PATH."/".self::IDNAME."/models/StatsModel.php";
        $day1stats = new StatsModel;
        $day1stats ->setPageSize($targets_count)
                   ->setPage(1)
                   ->where("user_id", "=", $AuthUser->get("id"))
                   ->where("account_id", "=", $Account->get("id"))
                   ->where("date", "=", date("Y-m-d", strtotime($now) - 86400))
                   ->orderBy("view_count","DESC")
                   ->fetchData();

        $day1 = [];
        $d1 = [PLUGINS_PATH."/".self::IDNAME."/models/StatModel.php",
                __NAMESPACE__."\StatModel"];
        foreach ($day1stats->getDataAs($d1) as $l) {
            $day1[] = $l;
        }

        // Weely Stats - 2 days ago
        require_once PLUGINS_PATH."/".self::IDNAME."/models/StatsModel.php";
        $day2stats = new StatsModel;
        $day2stats ->setPageSize($targets_count)
                   ->setPage(1)
                   ->where("user_id", "=", $AuthUser->get("id"))
                   ->where("account_id", "=", $Account->get("id"))
                   ->where("date", "=", date("Y-m-d", strtotime($now) - 2*86400))
                   ->orderBy("view_count","DESC")
                   ->fetchData();

        $day2 = [];
        $d2 = [PLUGINS_PATH."/".self::IDNAME."/models/StatModel.php",
                __NAMESPACE__."\StatModel"];
        foreach ($day2stats->getDataAs($d2) as $l) {
            $day2[] = $l;
        }

        // Weely Stats - 3 days ago
        require_once PLUGINS_PATH."/".self::IDNAME."/models/StatsModel.php";
        $day3stats = new StatsModel;
        $day3stats ->setPageSize($targets_count)
                   ->setPage(1)
                   ->where("user_id", "=", $AuthUser->get("id"))
                   ->where("account_id", "=", $Account->get("id"))
                   ->where("date", "=", date("Y-m-d", strtotime($now) - 3*86400))
                   ->orderBy("view_count","DESC")
                   ->fetchData();

        $day3 = [];
        $d3 = [PLUGINS_PATH."/".self::IDNAME."/models/StatModel.php",
                __NAMESPACE__."\StatModel"];
        foreach ($day3stats->getDataAs($d3) as $l) {
            $day3[] = $l;
        }

        // Weely Stats - 4 days ago
        require_once PLUGINS_PATH."/".self::IDNAME."/models/StatsModel.php";
        $day4stats = new StatsModel;
        $day4stats ->setPageSize($targets_count)
                   ->setPage(1)
                   ->where("user_id", "=", $AuthUser->get("id"))
                   ->where("account_id", "=", $Account->get("id"))
                   ->where("date", "=", date("Y-m-d", strtotime($now) - 4*86400))
                   ->orderBy("view_count","DESC")
                   ->fetchData();

        $day4 = [];
        $d4 = [PLUGINS_PATH."/".self::IDNAME."/models/StatModel.php",
                __NAMESPACE__."\StatModel"];
        foreach ($day4stats->getDataAs($d4) as $l) {
            $day4[] = $l;
        }

        // Weely Stats - 5 days ago
        require_once PLUGINS_PATH."/".self::IDNAME."/models/StatsModel.php";
        $day5stats = new StatsModel;
        $day5stats ->setPageSize($targets_count)
                   ->setPage(1)
                   ->where("user_id", "=", $AuthUser->get("id"))
                   ->where("account_id", "=", $Account->get("id"))
                   ->where("date", "=", date("Y-m-d", strtotime($now) - 5*86400))
                   ->orderBy("view_count","DESC")
                   ->fetchData();

        $day5 = [];
        $d5 = [PLUGINS_PATH."/".self::IDNAME."/models/StatModel.php",
                __NAMESPACE__."\StatModel"];
        foreach ($day5stats->getDataAs($d5) as $l) {
            $day5[] = $l;
        }

        // Weely Stats - 6 days ago
        require_once PLUGINS_PATH."/".self::IDNAME."/models/StatsModel.php";
        $day6stats = new StatsModel;
        $day6stats ->setPageSize($targets_count)
                   ->setPage(1)
                   ->where("user_id", "=", $AuthUser->get("id"))
                   ->where("account_id", "=", $Account->get("id"))
                   ->where("date", "=", date("Y-m-d", strtotime($now)- 6*86400))
                   ->orderBy("view_count","DESC")
                   ->fetchData();

        $day6 = [];
        $d6 = [PLUGINS_PATH."/".self::IDNAME."/models/StatModel.php",
                __NAMESPACE__."\StatModel"];
        foreach ($day6stats->getDataAs($d6) as $l) {
            $day6[] = $l;
        }

        $this->setVariable("TodayStats", $TodayStats)
             ->setVariable("Today", $Today)
             ->setVariable("day6stats", $day6stats)
             ->setVariable("day6", $day6)
             ->setVariable("day5stats", $day5stats)
             ->setVariable("day5", $day5)
             ->setVariable("day4stats", $day4stats)
             ->setVariable("day4", $day4)
             ->setVariable("day3stats", $day3stats)
             ->setVariable("day3", $day3)
             ->setVariable("day2stats", $day2stats)
             ->setVariable("day2", $day2)
             ->setVariable("day1stats", $day1stats)
             ->setVariable("day1", $day1);

        if (\Input::post("action") == "get-weekly-report") {
            $this->getWeeklyReport();
        }

        // View
        $this->view(PLUGINS_PATH."/".self::IDNAME."/views/stats.php", null);
    }

    /**
     * Get stories weekly report
     * @return self
     */
    private function getWeeklyReport()
    {
        $this->resp->result = 0;
        $AuthUser = $this->getVariable("AuthUser");
        $Account = $this->getVariable("Account");
        $targets_count = $this->getVariable("targets_count");

        $now = new \Moment\Moment("now", $AuthUser->get("preferences.timezone"));
        $now = $now->format("Y-m-d");

        // Get Today Stats
        require_once PLUGINS_PATH."/".self::IDNAME."/models/StatsModel.php";
        $TodayStats = new StatsModel;
        $TodayStats->setPageSize($targets_count)
                   ->setPage(1)
                   ->where("user_id", "=", $AuthUser->get("id"))
                   ->where("account_id", "=", $Account->get("id"))
                   ->where("date", "=", $now)
                   ->orderBy("view_count","DESC")
                   ->fetchData();

        $Today = [];
        $tds = [PLUGINS_PATH."/".self::IDNAME."/models/StatModel.php",
                __NAMESPACE__."\StatModel"];
        foreach ($TodayStats->getDataAs($tds) as $l) {
            $Today[] = $l;
        }


        // Weely Stats - 1 day ago
        require_once PLUGINS_PATH."/".self::IDNAME."/models/StatsModel.php";
        $day1stats = new StatsModel;
        $day1stats ->setPageSize($targets_count)
                   ->setPage(1)
                   ->where("user_id", "=", $AuthUser->get("id"))
                   ->where("account_id", "=", $Account->get("id"))
                   ->where("date", "=", date("Y-m-d", strtotime($now) - 86400))
                   ->orderBy("view_count","DESC")
                   ->fetchData();

        $day1 = [];
        $d1 = [PLUGINS_PATH."/".self::IDNAME."/models/StatModel.php",
                __NAMESPACE__."\StatModel"];
        foreach ($day1stats->getDataAs($d1) as $l) {
            $day1[] = $l;
        }

        // Weely Stats - 2 days ago
        require_once PLUGINS_PATH."/".self::IDNAME."/models/StatsModel.php";
        $day2stats = new StatsModel;
        $day2stats ->setPageSize($targets_count)
                   ->setPage(1)
                   ->where("user_id", "=", $AuthUser->get("id"))
                   ->where("account_id", "=", $Account->get("id"))
                   ->where("date", "=", date("Y-m-d", strtotime($now) - 2*86400))
                   ->orderBy("view_count","DESC")
                   ->fetchData();

        $day2 = [];
        $d2 = [PLUGINS_PATH."/".self::IDNAME."/models/StatModel.php",
                __NAMESPACE__."\StatModel"];
        foreach ($day2stats->getDataAs($d2) as $l) {
            $day2[] = $l;
        }

        // Weely Stats - 3 days ago
        require_once PLUGINS_PATH."/".self::IDNAME."/models/StatsModel.php";
        $day3stats = new StatsModel;
        $day3stats ->setPageSize($targets_count)
                   ->setPage(1)
                   ->where("user_id", "=", $AuthUser->get("id"))
                   ->where("account_id", "=", $Account->get("id"))
                   ->where("date", "=", date("Y-m-d", strtotime($now) - 3*86400))
                   ->orderBy("view_count","DESC")
                   ->fetchData();

        $day3 = [];
        $d3 = [PLUGINS_PATH."/".self::IDNAME."/models/StatModel.php",
                __NAMESPACE__."\StatModel"];
        foreach ($day3stats->getDataAs($d3) as $l) {
            $day3[] = $l;
        }

        // Weely Stats - 4 days ago
        require_once PLUGINS_PATH."/".self::IDNAME."/models/StatsModel.php";
        $day4stats = new StatsModel;
        $day4stats ->setPageSize($targets_count)
                   ->setPage(1)
                   ->where("user_id", "=", $AuthUser->get("id"))
                   ->where("account_id", "=", $Account->get("id"))
                   ->where("date", "=", date("Y-m-d", strtotime($now) - 4*86400))
                   ->orderBy("view_count","DESC")
                   ->fetchData();

        $day4 = [];
        $d4 = [PLUGINS_PATH."/".self::IDNAME."/models/StatModel.php",
                __NAMESPACE__."\StatModel"];
        foreach ($day4stats->getDataAs($d4) as $l) {
            $day4[] = $l;
        }

        // Weely Stats - 5 days ago
        require_once PLUGINS_PATH."/".self::IDNAME."/models/StatsModel.php";
        $day5stats = new StatsModel;
        $day5stats ->setPageSize($targets_count)
                   ->setPage(1)
                   ->where("user_id", "=", $AuthUser->get("id"))
                   ->where("account_id", "=", $Account->get("id"))
                   ->where("date", "=", date("Y-m-d", strtotime($now) - 5*86400))
                   ->orderBy("view_count","DESC")
                   ->fetchData();

        $day5 = [];
        $d5 = [PLUGINS_PATH."/".self::IDNAME."/models/StatModel.php",
                __NAMESPACE__."\StatModel"];
        foreach ($day5stats->getDataAs($d5) as $l) {
            $day5[] = $l;
        }

        // Weely Stats - 6 days ago
        require_once PLUGINS_PATH."/".self::IDNAME."/models/StatsModel.php";
        $day6stats = new StatsModel;
        $day6stats ->setPageSize($targets_count)
                   ->setPage(1)
                   ->where("user_id", "=", $AuthUser->get("id"))
                   ->where("account_id", "=", $Account->get("id"))
                   ->where("date", "=", date("Y-m-d", strtotime($now)- 6*86400))
                   ->orderBy("view_count","DESC")
                   ->fetchData();

        $day6 = [];
        $d6 = [PLUGINS_PATH."/".self::IDNAME."/models/StatModel.php",
                __NAMESPACE__."\StatModel"];
        foreach ($day6stats->getDataAs($d6) as $l) {
            $day6[] = $l;
        }

        function views($days) {
            $views = 0;
            foreach ($days as $d) {
                $views = $views + $d->get("view_count");
            }
            return $views;
        }

        function day_name($days) {
            $day = "";
            $data = $days[0]->get("date");
            if (!empty($data)) {
               $day =  __(date("l", strtotime($data)));
            }
            return $day;
        }

        ($day6stats->getTotalCount() > 0) ? $d6 = views($day6) : $d6 = 0;
        ($day5stats->getTotalCount() > 0) ? $d5 = views($day5) : $d5 = 0;
        ($day4stats->getTotalCount() > 0) ? $d4 = views($day4) : $d4 = 0;
        ($day3stats->getTotalCount() > 0) ? $d3 = views($day3) : $d3 = 0;
        ($day2stats->getTotalCount() > 0) ? $d2 = views($day2) : $d2 = 0;
        ($day1stats->getTotalCount() > 0) ? $d1 = views($day1) : $d1 = 0;

        $today_views = 0;
        $view_count = null;

        foreach ($Today as $tds):
            $view_count == $tds->get("view_count");
            $today_views = $today_views + $view_count;
        endforeach;

        ($TodayStats->getTotalCount() > 0) ? $today = $today_views : $today = 0;

        ($day6stats->getTotalCount() > 0) ? $d6_n = htmlchars(day_name($day6)) : $d6_n = "";
        ($day5stats->getTotalCount() > 0) ? $d5_n = htmlchars(day_name($day5)) : $d5_n = "";
        ($day4stats->getTotalCount() > 0) ? $d4_n = htmlchars(day_name($day4)) : $d4_n = "";
        ($day3stats->getTotalCount() > 0) ? $d3_n = htmlchars(day_name($day3)) : $d3_n = "";
        ($day2stats->getTotalCount() > 0) ? $d2_n = htmlchars(day_name($day2)) : $d2_n = "";
        ($day1stats->getTotalCount() > 0) ? $d1_n = htmlchars(day_name($day1)) : $d1_n = "";

        $this->resp->result = 1;
        $this->resp->data = [
            "d6" => $d6,
            "d5" => $d5,
            "d4" => $d4,
            "d3" => $d3,
            "d2" => $d2,
            "d1" => $d1,
            "today" =>  $today_views,
            "d6_n" => $d6_n,
            "d5_n" => $d5_n,
            "d4_n" => $d4_n,
            "d3_n" => $d3_n,
            "d2_n" => $d2_n,
            "d1_n" => $d1_n,
            "today_n" => __("Today")
        ];
        $this->jsonecho();
    }
}
