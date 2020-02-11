<?php
namespace Plugins\MassVoting;

// Disable direct access
if ( ! defined( 'APP_VERSION' ) ) {
	die( "Yo, what's up?" );
}

/**
 * Schedule Model
 *
 * @version 1.5
 * @author AmazCode.ooo (https://AmazCode.ooo)
 *
 */

class ScheduleModel extends \DataEntry {


	private $table;

	/**
	 * Extend parents constructor and select entry
	 * @param mixed $uniqid Value of the unique identifier
	 */
	public function __construct( $uniqid = 0 ) {
		parent::__construct();
		$this->table = TABLE_PREFIX . 'hypervote_schedule';
		$this->select( $uniqid );
	}



	/**
	 * Select entry with uniqid
	 * @param  int|string $uniqid Value of the any unique field
	 * @return self
	 */
	public function select( $uniqid ) {
		 $where = [];
		if ( is_array( $uniqid ) ) {
			$where = $uniqid;
		} if ( is_int( $uniqid ) || ctype_digit( $uniqid ) ) {
			if ( $uniqid > 0 ) {
				$where['id'] = $uniqid;
			}
		}

		if ( $where ) {
			$query = \DB::table( $this->table );

			foreach ( $where as $k => $v ) {
				$query->where( $k, '=', $v );
			}

			$query->limit( 1 )->select( '*' );
			if ( $query->count() > 0 ) {
				$resp = $query->get();
				$r    = $resp[0];

				foreach ( $r as $field => $value ) {
					$this->set( $field, $value );
				}

				$this->is_available = true;
			} else {
				$this->data         = array();
				$this->is_available = false;
			}
		}

		return $this;
	}


	/**
	 * Extend default values
	 * @return self
	 */
	public function extendDefaults() {
		$defaults = array(
			'user_id'                   => 0,
			'account_id'                => 0,
			'target'                    => '[]',
			'answers_pk'                => '',
			'poll_answer_option'        => '0',
			'login_logout_option'       => '0',
			'speed'                     => '400000',
			'daily_pause'               => false,
			'daily_pause_from'          => '00:00:00',
			'daily_pause_to'            => '00:00:00',
			'is_active'                 => '0',
			'is_running'                => '0',
			'is_executed'               => '0',
			'is_poll_active'            => '1',
			'is_question_active'        => '0',
			'is_slider_active'          => '0',
			'is_quiz_active'            => '0',
			'is_mass_story_view_active' => '0',
			'slider_min'                => '0',
			'slider_max'                => '100',
			'data'                      => '',
			'process_id'                => 0,
			'schedule_date'             => date( 'Y-m-d H:i:s' ),
			'end_date'                  => date( 'Y-m-d H:i:s' ),
			'last_action_date'          => date( 'Y-m-d H:i:s' ),
		);

		foreach ( $defaults as $field => $value ) {
			if ( is_null( $this->get( $field ) ) ) {
				$this->set( $field, $value );
			}
		}
	}


	/**
	 * Insert Data as new entry
	 */
	public function insert() {
		if ( $this->isAvailable() ) {
			return false;
		}

		$this->extendDefaults();

		$id = \DB::table( $this->table )
			->insert(
				array(
					'id'                        => null,
					'user_id'                   => $this->get( 'user_id' ),
					'account_id'                => $this->get( 'account_id' ),
					'target'                    => $this->get( 'target' ),
					'answers_pk'                => $this->get( 'answers_pk' ),
					'poll_answer_option'        => $this->get( 'poll_answer_option' ),
					'login_logout_option'       => $this->get( 'login_logout_option' ),
					'speed'                     => $this->get( 'speed' ),
					'daily_pause'               => $this->get( 'daily_pause' ),
					'daily_pause_from'          => $this->get( 'daily_pause_from' ),
					'daily_pause_to'            => $this->get( 'daily_pause_to' ),
					'is_active'                 => $this->get( 'is_active' ),
					'is_running'                => $this->get( 'is_running' ),
					'is_executed'               => $this->get( 'is_executed' ),
					'is_poll_active'            => $this->get( 'is_poll_active' ),
					'is_question_active'        => $this->get( 'is_question_active' ),
					'is_slider_active'          => $this->get( 'is_slider_active' ),
					'is_quiz_active'            => $this->get( 'is_quiz_active' ),
					'is_mass_story_view_active' => $this->get( 'is_mass_story_view_active' ),
					'slider_min'                => $this->get( 'slider_min' ),
					'slider_max'                => $this->get( 'slider_max' ),
					'data'                      => $this->get( 'data' ),
					'process_id'                => $this->get( 'process_id' ),
					'schedule_date'             => $this->get( 'schedule_date' ),
					'end_date'                  => $this->get( 'end_date' ),
					'last_action_date'          => $this->get( 'last_action_date' ),
				)
			);

		$this->set( 'id', $id );
		$this->markAsAvailable();
		return $this->get( 'id' );
	}


	/**
	 * Update selected entry with Data
	 */
	public function update() {
		if ( ! $this->isAvailable() ) {
			return false;
		}

		$this->extendDefaults();

		$id = \DB::table( $this->table )
			->where( 'id', '=', $this->get( 'id' ) )
			->update(
				array(
					'user_id'                   => $this->get( 'user_id' ),
					'account_id'                => $this->get( 'account_id' ),
					'target'                    => $this->get( 'target' ),
					'answers_pk'                => $this->get( 'answers_pk' ),
					'poll_answer_option'        => $this->get( 'poll_answer_option' ),
					'login_logout_option'       => $this->get( 'login_logout_option' ),
					'speed'                     => $this->get( 'speed' ),
					'daily_pause'               => $this->get( 'daily_pause' ),
					'daily_pause_from'          => $this->get( 'daily_pause_from' ),
					'daily_pause_to'            => $this->get( 'daily_pause_to' ),
					'is_active'                 => $this->get( 'is_active' ),
					'is_running'                => $this->get( 'is_running' ),
					'is_executed'               => $this->get( 'is_executed' ),
					'is_poll_active'            => $this->get( 'is_poll_active' ),
					'is_question_active'        => $this->get( 'is_question_active' ),
					'is_slider_active'          => $this->get( 'is_slider_active' ),
					'is_quiz_active'            => $this->get( 'is_quiz_active' ),
					'is_mass_story_view_active' => $this->get( 'is_mass_story_view_active' ),
					'slider_min'                => $this->get( 'slider_min' ),
					'slider_max'                => $this->get( 'slider_max' ),
					'data'                      => $this->get( 'data' ),
					'process_id'                => $this->get( 'process_id' ),
					'schedule_date'             => $this->get( 'schedule_date' ),
					'end_date'                  => $this->get( 'end_date' ),
					'last_action_date'          => $this->get( 'last_action_date' ),
				)
			);

		return $this;
	}


	/**
	 * Remove selected entry from database
	 */
	public function delete() {
		if ( ! $this->isAvailable() ) {
			return false;
		}

		\DB::table( $this->table )->where( 'id', '=', $this->get( 'id' ) )->delete();
		$this->is_available = false;
		return true;
	}
}
