<?php if (!defined('APP_VERSION')) die("Yo, what's up?"); ?>

<div class="skeleton skeleton--full">
    <div class="clearfix">
        <aside class="skeleton-aside hide-on-medium-and-down">
            <?php
                $form_action = APPURL . "/e/" . $idname;
                include PLUGINS_PATH . "/" . $idname ."/views/fragments/aside-search-form.fragment.php";
            ?>

            <div class="js-search-results">
                <div class="aside-list js-loadmore-content" data-loadmore-id="1"></div>
            </div>

            <div class="loadmore pt-20 mb-20 none">
                <a class="fluid button button--light-outline js-loadmore-btn js-autoloadmore-btn" data-loadmore-id="1" href="<?= APPURL."/e/".$idname."?aid=".$Account->get("id")."&ref=log" ?>">
                    <span class="icon sli sli-refresh"></span>
                    <?= __("Load More") ?>
                </a>
            </div>
        </aside>

        <section class="skeleton-content">
            <div class="section-header back-button-wh none">
                <a href="<?= APPURL."/e/".$idname."/" ?>">
            	    <span class="mdi mdi-reply"></span><?= __("Back") ?>
                </a>
            </div>

            <div class="section-header clearfix">
                <h2 class="section-title">
                    <?= "@" . htmlchars($Account->get("username")) ?>
                    <?php if ($Account->get("login_required")): ?>
                        <small class="color-danger ml-15">
                            <span class="mdi mdi-information"></span>
                            <?= __("Re-login required!") ?>
                        </small>
                    <?php endif ?>
                </h2>
            </div>

            <div class="svp-tab-heads pb-15 clearfix">
                <a href="<?= APPURL."/e/".$idname."/".$Account->get("id") ?>"><?= __("Settings") ?></a>
                <a href="<?= APPURL."/e/".$idname."/".$Account->get("id")."/log" ?>" class="active"><?= __("Activity Log") ?></a>
                <a href="<?= APPURL."/e/".$idname."/".$Account->get("id")."/stats" ?>"><?= __("Stats") ?></a>
            </div>

            <?php if ($ActivityLog->getTotalCount() > 0): ?>
                <div class="svp-log-list js-loadmore-content" data-loadmore-id="2">
                    <?php if ($ActivityLog->getPage() == 1 && $Schedule->get("is_active")): ?>
                        <?php
                            $nextdate = new \Moment\Moment($Schedule->get("schedule_date"), date_default_timezone_get());
                            $nextdate->setTimezone($AuthUser->get("preferences.timezone"));

                            $diff = $nextdate->fromNow();
                            $nexttime = $nextdate->format($AuthUser->get("preferences.timeformat") == "12" ? "h:iA (d.m.Y)" : "H:i (d.m.Y)");
                        ?>
                        <?php if ($diff->getDirection() == "future"): ?>
                            <div class="svp-next-schedule">
                                <?= __("Next schedule will be at %s", $nexttime) ?>
                            </div>
                        <?php elseif (abs($diff->getSeconds()) < 60): ?>
                            <div class="svp-next-schedule">
                                <?= __("Massvoting scheduled...") ?>
                            </div>
                        <?php else: ?>
                            <div class="svp-next-schedule">
                                <?= __("Massvoting in progress...") ?>
                            </div>
                        <?php endif ?>
                    <?php endif ?>

                    <?php foreach ($Logs as $l): ?>
                        <div class="svp-log-list-item <?= $l->get("status") ?>">
                            <div class="clearfix">
                                <span class="circle">
                                    <?php if ($l->get("status") == "success"): ?>
                                        <?php
                                            $img = $l->get("data.viewed.media_thumb");
                                            $story_react_img = $l->get("data.react.media_thumb");
                                        ?>
                                        <?php if ($img): ?>
                                            <span class="img" style="<?= $img ? "background-image: url('".htmlchars($img)."');" : "" ?>"></span>
                                        <?php elseif ($story_react_img): ?>
                                            <span class="img" style="<?= $story_react_img ? "background-image: url('".htmlchars($story_react_img)."');" : "" ?>"></span>
                                        <?php elseif ($l->get("data.collected.followers_count") || $l->get("data.collected.next_followers_count")): ?>
                                            <span class="text log-followers"></span>
                                        <?php elseif ($l->get("data.collected.following_count") || $l->get("data.collected.next_following_count")): ?>
                                            <span class="text log-following"></span>
                                        <?php else: ?>
                                            <span class="text log-notice"></span>
                                        <?php endif ?>
                                    <?php else: ?>
                                        <span class="text log-notice"></span>
                                    <?php endif ?>
                                </span>

                                <div class="inner clearfix">
                                    <?php
                                        $date = new \Moment\Moment($l->get("date"), date_default_timezone_get());
                                        $date->setTimezone($AuthUser->get("preferences.timezone"));

                                        $fulldate = $date->format($AuthUser->get("preferences.dateformat")) . " "
                                                . $date->format($AuthUser->get("preferences.timeformat") == "12" ? "h:iA" : "H:i");
                                    ?>

                                    <div class="action">
                                        <?php if ($l->get("status") == "success"): ?>
                                            <?php if ($l->get("data.viewed.stories_count")): ?>
                                                    <?php
                                                        $stories_count = htmlchars($l->get("data.viewed.stories_count"));
                                                        echo __("{stories_count} stories marked as seen", [
                                                            "{stories_count}" => $stories_count
                                                        ]);
                                                    ?>
                                                <span class="date" title="<?= $fulldate ?>"><?= $date->format($AuthUser->get("preferences.timeformat") == "12" ? "h:iA:s" : "H:i:s") ?></span>
                                                <?php if ($l->get("data.viewed.total_stories_count")): ?>
                                                    <div class="error-details">
                                                        <?php
                                                            $total_count = htmlchars($l->get("data.viewed.total_stories_count"));
                                                            echo __("Total: {count} stories seen.", [
                                                                "{count}" => $total_count
                                                            ]);
                                                        ?>
                                                    </div>
                                                <?php endif ?>
                                            <?php endif ?>

                                            <?php if ($l->get("data.react")): ?>
                                                <?php if ($l->get("data.react.is_poll")): ?>
                                                    <?php
                                                        $question_text = htmlchars($l->get("data.react.poll.question_text"));
                                                        $vote_text = htmlchars($l->get("data.react.poll.vote_text"));
                                                        $status = $l->get('status');
                                                        if($status == 'error') {
                                                            echo $question_text;
                                                        } else {
                                                            if (!empty($question_text)) {
                                                                echo __("Answered '{vote}' to poll with question '{question_text}'.", [
                                                                    "{question_text}" => $question_text,
                                                                    "{vote}" => $vote_text,
                                                                ]);
                                                            } else {
                                                                echo __("Answered '{vote}' to poll.", [
                                                                    "{vote}" => $vote_text,
                                                                ]);
                                                            }
                                                        }

                                                    ?>
                                                <?php elseif ($l->get("data.react.is_slider")): ?>
                                                    <?php
                                                        $question_text = htmlchars($l->get("data.react.slider.question_text"));
                                                        $vote_value = htmlchars($l->get("data.react.slider.vote_value"));

                                                        $status = $l->get('status');
                                                        if($status == 'error') {
                                                            echo $question_text;
                                                        } else {
                                                            if (!empty($question_text)) {
                                                                echo __("Answered {vote}% to slide poll with question '{question_text}'.", [
                                                                    "{question_text}" => $question_text,
                                                                    "{vote}" => $vote_value,
                                                                ]);
                                                            } else {
                                                                echo __("Answered {vote}% to slide poll.", [
                                                                    "{vote}" => $vote_value,
                                                                ]);
                                                            }
                                                        }
                                                    ?>
                                                <?php elseif ($l->get("data.react.is_question")): ?>
                                                    <?php
                                                        $question_text = htmlchars($l->get("data.react.question.question_text"));
                                                        $vote_text = htmlchars($l->get("data.react.question.vote_text"));
                                                        $status = $l->get('status');
                                                        if($status == 'error') {
                                                            echo $question_text;
                                                        } else {
                                                            if (!empty($question_text)) {
                                                                echo __("Answered '{vote}' to question '{question_text}'.", [
                                                                    "{question_text}" => $question_text,
                                                                    "{vote}" => $vote_text,
                                                                ]);
                                                            } else {
                                                                echo __("Answered '{vote}' to question.", [
                                                                    "{vote}" => $vote_text,
                                                                ]);
                                                            }
                                                        }


                                                    ?>
                                                <?php elseif ($l->get("data.react.is_quiz")): ?>
                                                    <?php
                                                        $question_text = htmlchars($l->get("data.react.quiz.question_text"));
                                                        $vote_text = htmlchars($l->get("data.react.quiz.vote_text"));

                                                        $status = $l->get('status');
                                                        if($status == 'error') {
                                                            echo $question_text;
                                                        } else {
                                                            if (!empty($question_text)) {
                                                                echo __("Answered '{vote}' to question '{question_text}'.", [
                                                                    "{question_text}" => $question_text,
                                                                    "{vote}" => $vote_text,
                                                                ]);
                                                            } else {
                                                                echo __("Answered '{vote}' to question.", [
                                                                    "{vote}" => $vote_text,
                                                                ]);
                                                            }

                                                        }
                                                    ?>
                                                <?php endif ?>
                                                <span class="date" title="<?= $fulldate ?>"><?= $date->format($AuthUser->get("preferences.timeformat") == "12" ? "h:iA:s" : "H:i:s") ?></span>
                                                <?php if ($l->get("data.react.total_count")): ?>
                                                        <div class="error-details">
                                                            <?php
                                                                $total_count = htmlchars($l->get("data.react.total_count"));
                                                                echo __("Total: {count} actions.", [
                                                                    "{count}" => $total_count
                                                                ]);
                                                            ?>
                                                        </div>
                                                <?php endif ?>
                                            <?php endif ?>
                                        <?php else: ?>
                                            <?php if ($l->get("data.error.msg")): ?>
                                                <div class="error-msg">
                                                    <?= __($l->get("data.error.msg")) ?>
                                                </div>
                                            <?php endif ?>
                                            <?php if ($l->get("data.error.details")): ?>
                                                <div class="error-details"><?= __($l->get("data.error.details")) ?></div>
                                            <?php endif ?>
                                        <?php endif ?>
                                    </div>

                                    <?php if ($l->get("data.pid")): ?>
                                        <a class="meta mr-10">
                                            <span class="icon mdi mdi-account-convert"></span>
                                            <?= __("PID: ") . htmlchars($l->get("data.pid")) ?>
                                        </a>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="svp-amount-of-action">
                    <?= __("Total %s notes in logs.", $ActivityLog->getTotalCount()) ?>
                </div>

                <?php if($ActivityLog->getPage() < $ActivityLog->getPageCount()): ?>
                    <div class="loadmore mt-20 mb-20">
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
                        <a class="fluid button button--light-outline js-loadmore-btn" data-loadmore-id="2" href="<?= $url.($ActivityLog->getPage()+1) ?>">
                            <span class="icon sli sli-refresh"></span>
                            <?= __("Load More") ?>
                        </a>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="no-data">
                    <p><?= __("Activity log for %s is empty.",
                    "<a href='https://www.instagram.com/".htmlchars($Account->get("username"))."' target='_blank'>@".htmlchars($Account->get("username"))."</a>") ?></p>
                </div>
            <?php endif ?>
        </section>
    </div>
</div>