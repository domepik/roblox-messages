<?php

/**
 * messages.class.php
 * 
 * This script was made to make managing your private-messages on ROBLOX a lot easier.
 * 
 * Current features:
 *  - Message count
 *  - Message extraction
 * 
 * Future features:
 *  - Formatted message extraction (JSON, XML)
 *  - Filtering options
 *  - Clear all messages
 * 
 * 
 * This was fully written by Dominik Penner (domepik@gmail.com)
 * If you have any questions, contact me.
 *
 */

class Messages {


	public $cookie_file;


	/**
	 * Set the cookie_file value with a file containing a cookie
	 * 
	 * @param string $cookie_file
	 * @return bool
	 */
	public function set_cookie_file($cookie_file) {

		try {

			if(file_exists($cookie_file)) {
				$this->cookie_file = $cookie_file;
				return true;
			}

		}

		catch(Exception $e) {
			echo 'Error: ', $e->getMessage();
			return false;
		}

	}

	/**
	 * Get the number of messages you have
	 * 
	 * @param string $cookie_file
	 * @return int
	 */
	public function get_message_count($cookie_file = false) {

		if(!$cookie_file) {
			$cookie_file = $this->cookie_file;
		}

		if(!$this->cookie_file) {
			die('Your cookie_file is invalid.');
		}

		try {

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, 'http://www.roblox.com/getmessages');
			curl_setopt($curl, CURLOPT_COOKIE, file_get_contents($cookie_file));
			curl_setopt($curl, CURLOPT_POST, 1);
			// With all POST vars set to 0, it'll return the message count
			curl_setopt($curl, CURLOPT_POSTFIELDS, 'tab=0&startIndex=0&maxRows=0');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$response =  curl_exec($curl);
			curl_close($curl);
			// Search the response for x value (value="x").
			preg_match_all('#value="(.*?)"/>#', $response, $matches);

			return $matches[1][0];

		}

		catch(Exception $e) {
			die('Error: ' . $e->getMessage());
			return false;
		}

	}

	/**
	 * Returns the raw response when the getmessages POST request is sent. For JSON formatting, consider using get_json_messages
	 * 
	 * @param string $cookie_file
	 * @param int $message_count
	 * @param string $response
	 */
	public function get_raw_messages($cookie_file = false, $message_count = false) {

		if(!$cookie_file) {
			$cookie_file = $this->cookie_file;
		}

		if(!$message_count) {
			$message_count = $this->get_message_count();
		}

		try {

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, 'http://www.roblox.com/getmessages');
			curl_setopt($curl, CURLOPT_COOKIE, file_get_contents($cookie_file));
			curl_setopt($curl, CURLOPT_POST, 1);
			// maxRows is the number of messages you want returned
			curl_setopt($curl, CURLOPT_POSTFIELDS, 'tab=0&startIndex=0&maxRows=' . $message_count);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$response = curl_exec($curl);
			curl_close($curl);

			return $response;

		}

		catch(Exception $e) {
			die('Error: ' . $e->getMessage());
			return false;
		}

	}
