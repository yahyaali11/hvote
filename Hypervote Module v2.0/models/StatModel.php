<?php
namespace Plugins\MassVoting;

// Disable direct access
if ( ! defined( 'APP_VERSION' ) ) {
	die( "Yo, what's up?" );
}

/**
 * Stat Model
 *
 * @version 1.5
 * @author AmazCode.ooo
 *
 */

class StatModel extends \DataEntry {


	private $table;

	/**
	 * Extend parents constructor and select entry
	 * @param mixed $uniqid Value of the unique identifier
	 */
	public function __construct( $uniqid = 0 ) {
		parent::__construct();
		$this->table = TABLE_PREFIX . 'hypervote_stats';
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
			'user_id'                => 0,
			'account_id'             => 0,
			'target'                 => '',
			'type'                   => '',
			'view_count'             => '0',
			'voted_poll_count'       => '0',
			'sliders_count'          => '0',
			'question_answers_count' => '0',
			'quiz_answers_count'     => '0',
			'quiz_answers_count'     => '0',
			'mass_story_view_count'     => '0',
			'date'                   => date( 'Y-m-d H:i:s' ),
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
					'id'                     => null,
					'user_id'                => $this->get( 'user_id' ),
					'account_id'             => $this->get( 'account_id' ),
					'target'                 => $this->get( 'target' ),
					'type'                   => $this->get( 'type' ),
					'view_count'             => $this->get( 'view_count' ),
					'voted_poll_count'       => $this->get( 'voted_poll_count' ),
					'sliders_count'          => $this->get( 'sliders_count' ),
					'question_answers_count' => $this->get( 'question_answers_count' ),
					'quiz_answers_count'     => $this->get( 'quiz_answers_count' ),
					'mass_story_view_count'  => $this->get( 'mass_story_view_count' ),
					'date'                   => $this->get( 'date' ),
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
					'user_id'                => $this->get( 'user_id' ),
					'account_id'             => $this->get( 'account_id' ),
					'target'                 => $this->get( 'target' ),
					'type'                   => $this->get( 'type' ),
					'view_count'             => $this->get( 'view_count' ),
					'voted_poll_count'       => $this->get( 'voted_poll_count' ),
					'sliders_count'          => $this->get( 'sliders_count' ),
					'question_answers_count' => $this->get( 'question_answers_count' ),
					'quiz_answers_count'     => $this->get( 'quiz_answers_count' ),
					'mass_story_view_count'  => $this->get( 'mass_story_view_count' ),
					'date'                   => $this->get( 'date' ),
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
