<?php if (!defined('APP_VERSION')) die("Yo, what's up?"); ?>

<div class="skeleton skeleton--full">
    <div class="clearfix">
        <aside class="skeleton-aside">
            <?php
                $form_action = APPURL . "/e/" . $idname;
                include PLUGINS_PATH . "/" . $idname ."/views/fragments/aside-search-form.fragment.php";
            ?>

            <div class="js-search-results">
                <?php if ($Accounts->getTotalCount() > 0): ?>
                    <?php
                        $active_item_id = Input::get("aid");
                    ?>
                    <div class="aside-list js-loadmore-content" data-loadmore-id="1">
                        <?php foreach ($Accounts->getDataAs("Account") as $a): ?>
                            <div class="aside-list-item js-list-item <?= $active_item_id == $a->get("id") ? "active" : "" ?>" data-id="<?= $a->get("id") ?>" data-url="<?= APPURL . "/e/" . $idname ?>">
                                <div class="clearfix">
                                    <?php $title = htmlchars($a->get("username")); ?>

                                    <?php if (file_exists(ROOTPATH."/assets/uploads/".$a->get("user_id")."/"."profile-pic-".$a->get("username").".jpg")): ?>
                                        <img class="circle" src="<?= APPURL."/assets/uploads/".$a->get("user_id")."/"."profile-pic-".$a->get("username").".jpg" ?>" alt="<?= $a->get("username") ?>">
                                    <?php else: ?>
                                        <span class="circle"><span><?= textInitials($title, 2); ?></span></span>
                                    <?php endif ?>

                                    <div class="inner">
                                        <div class="title"><?= $title ?></div>
                                        <div class="sub">
                                            <?php if ($sc_data[$a->get("id")]["is_active"] == "1"): ?>
                                                <span class="status color-green">
                                                    <span class="mdi mdi-circle mr-2"></span><?= __("Active") ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="status">
                                                    <span class="mdi mdi-circle-outline mr-2"></span><?= __("Deactive") ?>
                                                </span>
                                            <?php endif ?>

                                            <?php if (!empty($sc_data[$a->get("id")]["estimated_speed"])): ?>
                                                <span class="speed">
                                                    <span class="mdi mdi mdi-speedometer ml-5 mr-2"></span><span class="speed-value"><?= $sc_data[$a->get("id")]["estimated_speed"] ?></span><?= " " . __("react/day") ?>
                                                </span>
                                            <?php endif ?>

                                            <?php if ($a->get("login_required")): ?>
                                                <span class="color-danger">
                                                    <span class="mdi mdi-information"></span>
                                                    <?= __("Re-login required!") ?>
                                                </span>
                                            <?php endif ?>
                                        </div>
                                    </div>

                                    <?php
                                        $url = APPURL."/e/".$idname."/".$a->get("id");
                                        switch (\Input::get("ref")) {
                                            case "log":
                                                $url .= "/log";
                                                break;

                                            default:
                                                break;
                                        }
                                    ?>
                                    <a class="full-link" href="<?= $url ?>"></a>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>

                    
                <?php else: ?>
                    <?php if (\Input::get("q")): ?>
                        <p class="search-no-result">
                            <?= __("Couldn't find any result for your search query.") ?>
                        </p>
                    <?php else: ?>
                        <div class="no-data">
                            <?php if ($AuthUser->get("settings.max_accounts") == -1 || $AuthUser->get("settings.max_accounts") > 0): ?>
                                <p><?= __("You haven't add any Instagram account yet. Click the button below to add your first account.") ?></p>
                                <a class="small button" href="<?= APPURL."/accounts/new" ?>">
                                    <span class="sli sli-user-follow"></span>
                                    <?= __("New Account") ?>
                                </a>
                            <?php else: ?>
                                <p><?= __("You don't have a permission to add any Instagram account.") ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif ?>
                <?php endif ?>
            </div>
        </aside>

        <section class="skeleton-content hide-on-medium-and-down">
            <div class="no-data">
                <span class="no-data-icon sli sli-social-instagram"></span>
                <p><?= __("Please select an account from left side list.") ?></p>
            </div>
        </section>
    </div>
</div>