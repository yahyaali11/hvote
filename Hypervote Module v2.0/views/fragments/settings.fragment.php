<?php if (!defined('APP_VERSION')) die("Yo, what's up?"); ?>

<div class='skeleton' id="account">
    <form class="js-ajax-form"
          action="<?= APPURL . "/e/" . $idname . "/settings" ?>"
          method="POST">
        <input type="hidden" name="action" value="save">

        <div class="container-1200">
            <div class="row clearfix">
                <div class="form-result">
                </div>

                <div class="col s12 m8 l4 box-list-item mb-20">
                    <section class="section">
                        <div class="section-header clearfix">
                            <h2 class="section-title"><?= __("Settings") ?></h2>
                        </div>

                        <div class="section-content border-after">
                            <div class="mb-10 clearfix">
                                <div class="col s12 m12 l12">
                                    <label class="form-label"><?= __("Maximum speed") ?></label>

                                    <select name="maximum-speed" class="input">
                                        <?php
                                            $s = $Settings->get("data.maximum_speed");
                                        ?>
                                            <option value="maximum" <?= "maximum" == $s ? "selected" : "" ?>>
                                                <?= __("Maximum") ?>
                                            </option>
                                        <?php for ($i=10000; $i<=800000; $i=($i+10000)): ?>
                                            <option value="<?= $i ?>" <?= $i == $s ? "selected" : "" ?>>
                                                <?php $f_number = number_format($i, 0, '.', ' '); ?>
                                                <?= __("%s react/day", $f_number) ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>

                            <ul class="field-tips">
                                <li><?= __("These values indicates maximum amount of the story views per day. They are not exact values. Depending on the server overload and delays between the requests, actual number of the view requests might be less than these values.") ?></li>
                                <li><?= __("Developers are not responsible for any issues related to the Instagram accounts.") ?></li>
                            </ul>
                        </div>

                        <div class="section-header clearfix">
                            <h2 class="section-title"><?= __("Extra Settings") ?></h2>
                        </div>

                        <div class="section-content border-after">
                            <div class="mb-10">
                                <label>
                                    <input type="checkbox"
                                           class="checkbox"
                                           name="hide_pause_settings"
                                           value="1"
                                           <?= $Settings->get("data.hide_pause_settings") ? "checked" : "" ?>>
                                    <span>
                                        <span class="icon unchecked">
                                            <span class="mdi mdi-check"></span>
                                        </span>
                                        <?= __('Hide pause settings on schedule page') ?>
                                    </span>
                                </label>
                                <label>
                                    <input type="checkbox"
                                           class="checkbox"
                                           name="mass_story_view"
                                           value="1"
                                           <?= $Settings->get("data.mass_story_view") ? "checked" : "" ?>>
                                    <span>
                                        <span class="icon unchecked">
                                            <span class="mdi mdi-check"></span>
                                        </span>
                                        <?= __('Hide Mass Story View on schedule page') ?>
                                    </span>
                                </label>
                                <label>
                                    <input type="checkbox"
                                           class="checkbox"
                                           name="question_answers"
                                           value="1"
                                           <?= $Settings->get("data.question_answers") ? "checked" : "" ?>>
                                    <span>
                                        <span class="icon unchecked">
                                            <span class="mdi mdi-check"></span>
                                        </span>
                                        <?= __('Hide Mass Question Answers on schedule page') ?>
                                    </span>
                                </label>
                                <label>
                                    <input type="checkbox"
                                           class="checkbox"
                                           name="mass_poll_votes"
                                           value="1"
                                           <?= $Settings->get("data.mass_poll_votes") ? "checked" : "" ?>>
                                    <span>
                                        <span class="icon unchecked">
                                            <span class="mdi mdi-check"></span>
                                        </span>
                                        <?= __('Hide Mass Poll Votes on schedule page') ?>
                                    </span>
                                </label>
                                <label>
                                    <input type="checkbox"
                                           class="checkbox"
                                           name="mass_slide_points"
                                           value="1"
                                           <?= $Settings->get("data.mass_slide_points") ? "checked" : "" ?>>
                                    <span>
                                        <span class="icon unchecked">
                                            <span class="mdi mdi-check"></span>
                                        </span>
                                        <?= __('Hide Mass Slider Points on schedule page') ?>
                                    </span>
                                </label>
                                <label>
                                    <input type="checkbox"
                                           class="checkbox"
                                           name="mass_quiz_answers"
                                           value="1"
                                           <?= $Settings->get("data.mass_quiz_answers") ? "checked" : "" ?>>
                                    <span>
                                        <span class="icon unchecked">
                                            <span class="mdi mdi-check"></span>
                                        </span>
                                        <?= __('Hide Mass Quiz Answers on schedule page') ?>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <input class="fluid button button--footer" type="submit" value="<?= __("Save") ?>">
                    </section>
                </div>

                <div class="col s12 m8 l8 mr-0 hypervote-section box-list-item mb-20">
                    <section class="section">
                        <div class="section-header clearfix">
                            <h2 class="section-title"><?= __("Task Manager") ?></h2>

                            <a class="mdi mdi-reload button small button--light-outline js-hypervote-bulk-restart" data-url="<?= APPURL."/e/".$idname."/settings" ?>">
                                <?= __('Bulk restart'); ?>
                            </a>
                        </div>
                        <div class="section-content hypervote-overflow">
                            <?php
                                use dgr\nohup\Process;

                                require_once PLUGINS_PATH."/".$idname."/vendor/autoload.php";

                                $active_task = "<span class='status color-green'><span class='mdi mdi-circle mr-2'></span>" . __('Active') . "</span>";
                                $deactive_task = "<span class='status'><span class='mdi mdi-circle-outline mr-2'></span>" . __('Deactive') . "</span>";
                                $invalid_task = "<span class='status color-red'><span class='mdi mdi-circle-outline mr-2'></span>" . __('Invalid') . "</span>";
                                $scheduled_task = "<span class='status'><span class='mdi mdi-clock mr-2'></span>" . __('Scheduled') . "</span>";
                                $paused_task = "<span class='status'><span class='mdi mdi-clock mr-2'></span>" . __('Paused') . "</span>";
                            ?>
                            <?php if ($Schedules->getTotalCount() > 0): ?>
                                <table class="datatable-hypervote mb-0" id="dataTableHypervote">
                                    <thead>
                                        <tr>
                                            <td class="tm-hypervote-id"><?=__('ID'); ?></td>
                                            <td class="tm-hypervote-account"><?=__('Account'); ?></td>
                                            <td class="tm-hypervote-task"><?=__('Task'); ?></td>
                                            <td class="tm-hypervote-pid"><?=__('PID'); ?></td>
                                            <td class="tm-hypervote-last-action"><?=__('Last Action'); ?></td>
                                            <td class="tm-hypervote-action"><?=__('Actions'); ?></td>
                                        </tr>
                                    </thead>
                                    <tbody class="js-loadmore-content" data-loadmore-id="1">
                                        <?php foreach ($Schedules->getDataAs($Schedule) as $sc): ?>
                                            <?php
                                                $Account = \Controller::model("Account", $sc->get("account_id"));
                                                $pid_status = $deactive_task;
                                                if ($sc->get("is_active") && !$sc->get("is_running") && !$sc->get("is_executed") && $sc->get("schedule_date") > date("Y-m-d H:i:s", time() + 60)) {
                                                    $pid_status = $paused_task;
                                                } elseif ($sc->get("is_active") && !$sc->get("is_running") && !$sc->get("is_executed")) {
                                                    $pid_status = $scheduled_task;
                                                }
                                                $pid = $sc->get("process_id");
                                                if ($pid) {
                                                    $process = Process::loadFromPid($pid);
                                                    if ($process->isRunning()) {
                                                        $pid_status = $active_task;
                                                    } else {
                                                        $pid_status = $invalid_task;
                                                    }
                                                }

                                                // Get Last Log Data
                                                $LL_D = null;
                                                $LL_D_F = null;
                                                if (isset($LL_Ds[$sc->get("account_id")])) {
                                                    $LL_D = new \Moment\Moment($LL_Ds[$sc->get("account_id")], date_default_timezone_get());
                                                    $LL_D->setTimezone($AuthUser->get("preferences.timezone"));
                                                    $LL_D_F = $LL_D->format($AuthUser->get("preferences.timeformat") == "12" ? "h:i:sA d.m.Y" : "H:i:s d.m.Y");
                                                    $LL_D = $LL_D->format($AuthUser->get("preferences.timeformat") == "12" ? "h:i:sA" : "H:i:s");
                                                }
                                            ?>
                                            <tr>
                                                <td class="tm-hypervote-id"><?= htmlchars($sc->get("account_id")); ?></td>
                                                <td class="tm-hypervote-account"><?= "<a href='https://instagram.com/" . $Account->get("username") . "' target='_blank'>@" . htmlchars($Account->get("username")); ?></a></td>
                                                <td class="tm-hypervote-task" data-id="<?= htmlchars($sc->get("account_id")); ?>">
                                                    <?php if ($sc->get("is_active")): ?>
                                                        <?php if ($sc->get("data.estimated_speed")): ?>
                                                            <span class="tooltip tippy color-green"
                                                                data-position="top"
                                                                data-size="small"
                                                                title="<?= __('Estimated speed (react/day):') . " " . htmlchars($sc->get("data.estimated_speed")); ?>">
                                                            <?= $active_task ?>
                                                        <?php else: ?>
                                                            <?= $active_task ?>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <?= $deactive_task ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="tm-hypervote-pid" data-id="<?= htmlchars($sc->get("account_id")); ?>">
                                                    <?php if ($sc->get("process_id")): ?>
                                                        <span class="tooltip tippy color-green"
                                                            data-position="top"
                                                            data-size="small"
                                                            title="<?= __('Process ID: ') . htmlchars($sc->get("process_id")); ?>">
                                                            <?= $pid_status ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <?= $pid_status ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="tm-hypervote-last-action">
                                                    <span class="tooltip tippy"
                                                            data-position="top"
                                                            data-size="small"
                                                            title="<?= $LL_D_F ? $LL_D_F : __('Not set') ?>">
                                                    <?= $LL_D ? $LL_D : "" ?>
                                                    </span>
                                                </td>
                                                <td class="tm-hypervote-action">
                                                    <a class="button small button--light-outline js-hypervote-restart" data-id="<?= htmlchars($sc->get("account_id")); ?>" data-url="<?= APPURL."/e/".$idname."/settings" ?>">
                                                        <?= __('Restart'); ?>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="no-data-hypervote">
                                    <p class="pb-20"><?= __("You are not schedule any hypervote task.") ?></p>
                                    <a class="small button" href="<?= APPURL."/e/" . $idname . "/" ?>">
                                        <span class="mdi mdi-cellphone-iphone"></span>
                                        <?= __("Setup Hypervote Tasks") ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="section-content pt-0 pb-0">
                            <?php if ($Schedules->getPage() < $Schedules->getPageCount() && $Schedules->getTotalCount() > 0): ?>
                                <div class="loadmore mt-25 mb-25">
                                    <?php
                                        $url = parse_url($_SERVER["REQUEST_URI"]);
                                        $path = $url["path"];
                                        if(isset($url["query"])){
                                            $qs = parse_str($url["query"], $qsarray);
                                            unset($qsarray["page"]);

                                            $url = $path."?".(count($qsarray) > 0 ? http_build_query($qsarray)."&" : "")."page=";
                                        }else{
                                            $url = $path."?page=";
                                        }
                                    ?>
                                    <a class="fluid button button--light-outline js-loadmore-btn js-loadmore-btn-task-manager" data-loadmore-id="1" href="<?= $url.($Schedules->getPage()+1) ?>">
                                        <span class="icon sli sli-refresh"></span>
                                        <?= __("Load More") ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="section-content server-info">
                            <?php
                                echo '<b>PHP version:</b> ' . phpversion() . '</br>';

                                echo '<b>Server usage:</b></br>';
                                echo $server_info[1] . '</br>';
                                echo $server_info[2] . '</br>';
                                echo $server_info[3] . '</br>';
                                echo $server_info[4];
                            ?>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </form>
</div>