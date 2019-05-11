<?php

class Logy_Query {

	protected $logy;
	protected $wpdb;
	protected $users_table;

	function __construct() {

		global $Logy, $wpdb, $Logy_users_table;

		// Init Variables.
    	$this->logy = &$Logy;
    	$this->wpdb = &$wpdb;
    	$this->users_table = &$Logy_users_table;

    	// Delete User Data.
		add_action( 'delete_user', array( $this, 'delete_stored_user_data' ) );

	}

	/**
	 * Get User By Provider And ID.
	 */
	public function get_user_by_provider_and_uid( $provider, $uid ) {
		// Get SQL.
		$sql = "SELECT user_id FROM $this->users_table WHERE provider = %s AND identifier = %s";
		// Get Result
		$result = $this->wpdb->get_var( $this->wpdb->prepare( $sql, $provider, $uid ) );
		// Return Result.
		return $result;
	}

	/**
	 * Get User ID by Verified Email.
	 */
	function get_user_verified_email( $email ) {
		// Get SQL.
		$sql = "SELECT user_id FROM $this->users_table WHERE emailverified = %s";
		// Get Result
		$result = $this->wpdb->get_var( $this->wpdb->prepare( $sql, $email ) );
		// Return Result.
		return $result;
	}

	/**
	 * Store User Data Into Database.
	 */
	public function store_user_data( $user_id, $provider, $profile ) {

		// Update User Avatar.
		if ( $profile->photoURL ) {
			update_user_meta( $user_id, 'logy_avatar', $profile->photoURL );
		}

		// Get Profile Hash
		$new_hash = sha1( serialize( $profile ) );		

		// Get User Old Profile Data.
		$old_profile = $this->get_user_profile( $user_id, $provider, $profile->identifier );
 		
		// Check if user data changed since last login.
		if ( ! empty( $old_profile ) && $old_profile[0]->profile_hash == $new_hash ) {
			return false;	
		}

		// Get Table ID.
		$table_id = ! empty( $old_profile ) ? $old_profile[0]->id : null;

		// Get Table Data.
		$table_data = array(
			'id' => $table_id,
			'user_id' => $user_id,
			'provider' => $provider,
			'profile_hash' => $new_hash
		);

		// Get Table Fields.
		$fields = array( 
			'identifier', 
			'profileurl', 
			'websiteurl', 
			'photourl', 
			'displayname', 
			'description', 
			'firstname', 
			'lastname', 
			'gender', 
			'language', 
			'age', 
			'birthday', 
			'birthmonth', 
			'birthyear', 
			'email', 
			'emailverified', 
			'phone', 
			'address', 
			'country', 
			'region', 
			'city', 
			'zip'
		);

		foreach( $profile as $key => $value ) {
			// Transform Key To LowerCase.
			$key = strtolower( $key );
			// Get Table Data.
			if ( in_array( $key, $fields ) ) {
				$table_data[ $key ] = (string) $value;
			}
		}

		// Replace Data.
		$this->wpdb->replace( $this->users_table, $table_data ); 

		return false;
	}

	/**
	 * Delete Stored User Data form Database.
	 */
	public function delete_stored_user_data( $user_id ) {

		// Delete Data.
		$this->wpdb->query(
			$this->wpdb->prepare( "DELETE FROM $this->users_table where user_id = %d", $user_id )
		);

		// Delete User Meta
		delete_user_meta( $user_id, 'logy_avatar' );

	}
	
	/**
	 * Get User Profile Data.
	 */
	public function get_user_profile( $user_id, $provider, $uid ) {

		// Get SQL Request.
		$sql = "SELECT * FROM $this->users_table WHERE user_id = %d AND provider = %s AND identifier = %s";

		// Get Result.		
		$result = $this->wpdb->get_results( $this->wpdb->prepare( $sql, $user_id, $provider, $uid ) );

		return $result;
	}

}
		