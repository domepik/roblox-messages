<?php

/**
 * messages.class.php
 * 
 * This script was made to make managing your private-messages on ROBLOX a lot easier.
 * 
 * Current features:
 * 	- Message count
 *  - Message extraction
 *  - JSON formatting
 * 
 * Future features:
 *  - Filtering options.
 *  - Clear all messages.
 * 
 * 
 * This was fully written by Dominik Penner (domepik@gmail.com)
 * If you have any questions, contact me.
 *
 */

require 'simple_html_dom.php';

class Messages extends simple_html_dom {

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
	 * @param int $message_count
	 * @param string $cookie_file
	 * @param string $response
	 */
	public function get_raw_messages($message_count = false, $cookie_file = false) {

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

	/**
	 * Format the raw message list with JSON
	 * 
	 * This function uses the simple_html_dom library to analyze the DOM and return what we need.
	 * It stores all the results into arrays, then maps it into a master array which is, in the end, json_encoded.
	 * 
	 * @param string $raw_messages
	 * @param string $cookie_file
	 * @return string $formatted_messages
	 */
	public function get_json_messages($raw_messages = false, $cookie_file = false) {

		if(!$raw_messages) {
			/*
			Please be efficient and store the raw messages in a variable instead of making this send another request.
			If you have a lot of  private messages, this will most likely take longer than a few seconds - be smart.
			*/
			$raw_messages = $this->get_raw_messages($this->cookie_file);
		}

		if(!$cookie_file) {
			$cookie_file = $this->cookie_file;
		}

		$simple_html_dom = $this->load(str_replace('<span></span>', '', $raw_messages));

		$message_id_search = $simple_html_dom->find('div[class=sub-divider-bottom messageDivider read]');
		$message_sender_search = $simple_html_dom->find('span[class=positionAboveLink]');
		$message_subject_search = $simple_html_dom->find('span[class=subject notranslate]');
		$message_content_search = $simple_html_dom->find('span[!class]');
		$message_date_search = $simple_html_dom->find('span[class=messageDate read]');

		foreach($message_id_search as $x) {
			$messages['id'][] = $x->{'data-messageid'};
		}

		foreach($message_sender_search as $x) {
			$messages['sender'][] = $x->innertext;
		}

		foreach($message_subject_search as $x) {
			$messages['subject'][] = $x->innertext;
		}


		foreach($message_content_search as $x) {

			if($x->innertext == ' ') {
				$messages['content'][] = 'NULL';
			}

			$messages['content'][] = trim($x->innertext);

		}

		foreach($message_date_search as $x) {
			$messages['date'][] = trim($x->innertext, " ");
		}

		$formatted_messages = array_map(function ($id, $sender, $subject, $content, $date) {
			return compact('id', 'sender', 'subject', 'content', 'date');
		}, $messages['id'], $messages['sender'], $messages['subject'], $messages['content'], $messages['date']);

		return json_encode($formatted_messages);

	}


}
