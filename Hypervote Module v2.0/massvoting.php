<?php
namespace Plugins\MassVoting;

const IDNAME = 'massvoting';

// Disable direct access
if ( ! defined( 'APP_VERSION' ) ) {
	die( "Yo, what's up?" );
}


/**
 * Event: plugin.install
 *
 * @version 1.4
 * @author AmazCode.ooo
 *
 */
function install( $Plugin ) {
	if ( $Plugin->get( 'idname' ) != IDNAME ) {
		return false;
	}

	// Create tables for plugin
	$sql  = 'DROP TABLE IF EXISTS `' . TABLE_PREFIX . 'hypervote_schedule`;';
	$sql .= 'CREATE TABLE `' . TABLE_PREFIX . 'hypervote_schedule` (
                `id` INT NOT NULL AUTO_INCREMENT ,
                `user_id` INT NOT NULL ,
                `account_id` INT NOT NULL ,
                `target` TEXT NOT NULL COLLATE utf8mb4_general_ci,
				`answers_pk` TEXT NOT NULL COLLATE utf8mb4_general_ci,
				`poll_answer_option` CHAR(1) NOT NULL,
				`login_logout_option` CHAR(3) NOT NULL,
                `speed` VARCHAR(20) NOT NULL ,
                `daily_pause` BOOLEAN NOT NULL ,
                `daily_pause_from` TIME NOT NULL ,
                `daily_pause_to` TIME NOT NULL ,
                `is_active` BOOLEAN NOT NULL ,
				`is_poll_active` BOOLEAN NOT NULL,
				`is_question_active` BOOLEAN NOT NULL,
				`is_slider_active` BOOLEAN NOT NULL,
				`is_quiz_active` BOOLEAN NOT NULL,
				`is_mass_story_view_active` BOOLEAN NOT NULL,
				`slider_min` INT NOT NULL,
				`slider_max` INT NOT NULL,
                `is_running` BOOLEAN NOT NULL ,
                `is_executed` BOOLEAN NOT NULL ,
                `data` TEXT NOT NULL COLLATE utf8mb4_general_ci,
                `process_id` INT NOT NULL ,
                `schedule_date` DATETIME NOT NULL ,
                `end_date` DATETIME NOT NULL ,
                `last_action_date` DATETIME NOT NULL ,
                PRIMARY KEY (`id`),
                INDEX (`user_id`),
                INDEX (`account_id`)
            ) ENGINE = InnoDB;';

	$sql .= 'DROP TABLE IF EXISTS `' . TABLE_PREFIX . 'hypervote_log`;';
	$sql .= 'CREATE TABLE `' . TABLE_PREFIX . 'hypervote_log` (
                `id` INT NOT NULL AUTO_INCREMENT ,
                `user_id` INT NOT NULL ,
                `account_id` INT NOT NULL ,
                `status` VARCHAR(20) NOT NULL,
                `data` TEXT NOT NULL ,
                `date` DATETIME NOT NULL ,
                PRIMARY KEY (`id`),
                INDEX (`user_id`),
                INDEX (`account_id`)
            ) ENGINE = InnoDB;';

	$sql .= 'DROP TABLE IF EXISTS `' . TABLE_PREFIX . 'hypervote_stats`;';
	$sql .= 'CREATE TABLE `' . TABLE_PREFIX . 'hypervote_stats` (
                `id` INT NOT NULL AUTO_INCREMENT ,
                `user_id` INT NOT NULL ,
                `account_id` INT NOT NULL ,
                `target` VARCHAR(50) NOT NULL COLLATE utf8mb4_general_ci,
                `type` VARCHAR(20) NOT NULL COLLATE utf8mb4_general_ci,
                `view_count` INT NOT NULL ,
                `voted_poll_count` INT ,
                `sliders_count` INT ,
                `question_answers_count` INT ,
                `quiz_answers_count` INT ,
                `mass_story_view_count` INT ,
                `date` DATETIME NOT NULL ,
                PRIMARY KEY (`id`),
                INDEX (`user_id`),
                INDEX (`account_id`)
            ) ENGINE = InnoDB;';

	$sql .= 'ALTER TABLE `' . TABLE_PREFIX . 'hypervote_schedule`
                ADD CONSTRAINT `' . uniqid( 'ibfk_' ) . '` FOREIGN KEY (`user_id`)
                REFERENCES `' . TABLE_PREFIX . 'users`(`id`)
                ON DELETE CASCADE ON UPDATE CASCADE;';

	$sql .= 'ALTER TABLE `' . TABLE_PREFIX . 'hypervote_schedule`
                ADD CONSTRAINT `' . uniqid( 'ibfk_' ) . '` FOREIGN KEY (`account_id`)
                REFERENCES `' . TABLE_PREFIX . 'accounts`(`id`)
				ON DELETE CASCADE ON UPDATE CASCADE;';

	$sql .= 'ALTER TABLE `' . TABLE_PREFIX . 'hypervote_schedule`
				ALTER login_logout_option SET DEFAULT "180"';
	$sql .= 'ALTER TABLE `' . TABLE_PREFIX . 'hypervote_schedule`
	ALTER slider_min SET DEFAULT "75"';
	$sql .= 'ALTER TABLE `' . TABLE_PREFIX . 'hypervote_schedule`
	ALTER slider_max SET DEFAULT "100"';

	$sql .= 'ALTER TABLE `' . TABLE_PREFIX . 'hypervote_log`
                ADD CONSTRAINT `' . uniqid( 'ibfk_' ) . '` FOREIGN KEY (`user_id`)
                REFERENCES `' . TABLE_PREFIX . 'users`(`id`)
                ON DELETE CASCADE ON UPDATE CASCADE;';

	$sql .= 'ALTER TABLE `' . TABLE_PREFIX . 'hypervote_log`
                ADD CONSTRAINT `' . uniqid( 'ibfk_' ) . '` FOREIGN KEY (`account_id`)
                REFERENCES `' . TABLE_PREFIX . 'accounts`(`id`)
                ON DELETE CASCADE ON UPDATE CASCADE;';

	$sql .= 'ALTER TABLE `' . TABLE_PREFIX . 'hypervote_stats`
                ADD CONSTRAINT `' . uniqid( 'ibfk_' ) . '` FOREIGN KEY (`user_id`)
                REFERENCES `' . TABLE_PREFIX . 'users`(`id`)
                ON DELETE CASCADE ON UPDATE CASCADE;';

	$sql .= 'ALTER TABLE `' . TABLE_PREFIX . 'hypervote_stats`
                ADD CONSTRAINT `' . uniqid( 'ibfk_' ) . '` FOREIGN KEY (`account_id`)
                REFERENCES `' . TABLE_PREFIX . 'accounts`(`id`)
                ON DELETE CASCADE ON UPDATE CASCADE;';

	$pdo  = \DB::pdo();
	$stmt = $pdo->prepare( $sql );
	$stmt->execute();
}
\Event::bind( 'plugin.install', __NAMESPACE__ . '\install' );



/**
 * Event: plugin.remove
 *
 * @version 1.0
 *@author Hypervote.com
 *
 */
function uninstall( $Plugin ) {
	if ( $Plugin->get( 'idname' ) != IDNAME ) {
		return false;
	}

	// Remove plugin settings
	$settings = namespace\settings();

	$settings->remove();

	$sql  = 'DROP TABLE `' . TABLE_PREFIX . 'hypervote_schedule`;';
	$sql .= 'DROP TABLE `' . TABLE_PREFIX . 'hypervote_log`;';
	$sql .= 'DROP TABLE `' . TABLE_PREFIX . 'hypervote_stats`;';

	$pdo  = \DB::pdo();
	$stmt = $pdo->prepare( $sql );
	$stmt->execute();
}
\Event::bind( 'plugin.remove', __NAMESPACE__ . '\uninstall' );


/**
 * Add module as a package options
 * Only users with granted permission
 * Will be able to use module
 *
 * @param array $package_modules An array of currently active
 *                               modules of the package
 */
function add_module_option( $package_modules ) {
	$config = include __DIR__ . '/config.php';
	?>
		<label class="mt-30 form-label form-label--secondary"><?php echo __( 'Massvoting' ); ?></label>
		<div class="mt-15">
			<label>
				<input type="checkbox"
					   class="checkbox"
					   name="modules[]"
					   value="<?php echo IDNAME; ?>"
					   <?php echo in_array( IDNAME, $package_modules ) ? 'checked' : ''; ?>>
				<span>
					<span class="icon unchecked">
						<span class="mdi mdi-check"></span>
					</span>
					<?php echo __( 'Enable' ); ?>
				</span>
			</label>
		</div>
	<?php
}
\Event::bind( 'package.add_module_option', __NAMESPACE__ . '\add_module_option' );




/**
 * Map routes
 */
function route_maps( $global_variable_name ) {
	// Settings (admin only)
	$GLOBALS[ $global_variable_name ]->map(
		'GET|POST',
		'/e/' . IDNAME . '/settings/?',
		[
			PLUGINS_PATH . '/' . IDNAME . '/controllers/SettingsController.php',
			__NAMESPACE__ . '\SettingsController',
		]
	);

	// Index
	$GLOBALS[ $global_variable_name ]->map(
		'GET|POST',
		'/e/' . IDNAME . '/?',
		[
			PLUGINS_PATH . '/' . IDNAME . '/controllers/IndexController.php',
			__NAMESPACE__ . '\IndexController',
		]
	);

	// Schedule
	$GLOBALS[ $global_variable_name ]->map(
		'GET|POST',
		'/e/' . IDNAME . '/[i:id]/?',
		[
			PLUGINS_PATH . '/' . IDNAME . '/controllers/ScheduleController.php',
			__NAMESPACE__ . '\ScheduleController',
		]
	);

	// Log
	$GLOBALS[ $global_variable_name ]->map(
		'GET|POST',
		'/e/' . IDNAME . '/[i:id]/log/?',
		[
			PLUGINS_PATH . '/' . IDNAME . '/controllers/LogController.php',
			__NAMESPACE__ . '\LogController',
		]
	);

	// Stats
	$GLOBALS[ $global_variable_name ]->map(
		'GET|POST',
		'/e/' . IDNAME . '/[i:id]/stats/?',
		[
			PLUGINS_PATH . '/' . IDNAME . '/controllers/StatsController.php',
			__NAMESPACE__ . '\StatsController',
		]
	);

	// MassVoting
	$GLOBALS[ $global_variable_name ]->map(
		'GET|POST',
		'/e/' . IDNAME . '/massvoting/[i:account_id].[i:user_id]/?',
		[
			PLUGINS_PATH . '/' . IDNAME . '/controllers/MassVotingController.php',
			__NAMESPACE__ . '\HypervoteController',
		]
	);
}
\Event::bind( 'router.map', __NAMESPACE__ . '\route_maps' );



/**
 * Event: navigation.add_special_menu
 */
function navigation( $Nav, $AuthUser ) {
	$idname = IDNAME;
	include __DIR__ . '/views/fragments/navigation.fragment.php';
}
\Event::bind( 'navigation.add_special_menu', __NAMESPACE__ . '\navigation' );



/**
 * Get Plugin Settings
 * @return \GeneralDataModel
 */
function settings() {
	$settings = \Controller::model( 'GeneralData', 'plugin-' . IDNAME . '-settings' );
	return $settings;
}

/**
 * Include Cron Task functions
 */
require_once __DIR__ . '/cron.php';
