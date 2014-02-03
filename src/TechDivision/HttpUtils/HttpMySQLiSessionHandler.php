<?php

/**
 * License: GNU General Public License
 *
 * Copyright (c) 2009 TechDivision GmbH.  All rights reserved.
 * Note: Original work copyright to respective authors
 *
 * This file is part of TechDivision_HttpUtils.
 *
 * TechDivision_HttpUtils free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * TechDivision_HttpUtils distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307,
 * USA.
 *
 * @package TechDivision_HttpUtils
 */

require_once "TechDivision/Util/XMLDataSource.php";
require_once "TechDivision/HttpUtils/HttpSessionHandler.php";

/**
 * This class replaces the PHP session handler and stores
 * the session data in a MySQL database.
 *
 * To store the data create to following table:
 *
 * CREATE TABLE  `epb4php_session`.`web_session` (
 *	  `web_session_id` varchar(255) collate utf8_unicode_ci NOT NULL,
 *	  `session_data` longtext collate utf8_unicode_ci NOT NULL,
 *	  `modtime` int(10) NOT NULL,
 *	  PRIMARY KEY  (`web_session_id`),
 *	  KEY `web_session_idx_01` (`modtime`)
 *	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
 *
 * The connection will be established by invoking the
 * XMLDataSource::createByName($name, $file) method.
 * The XML file passed as second parameter must have
 * expected structure therefore.
 *
 * @category TechDivision
 * @package TechDivision_HttpUtils
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
class TechDivision_HttpUtils_HttpMySQLiSessionHandler
	extends TechDivision_HttpUtils_HttpSessionHandler {

	/**
	 * The database connection used for storing the session data
	 * @var MySQLi
	 */
	protected $db = null;

	/**
	 * Initializes the Session and connects to the
	 * database specified by the passed parameters.
	 *
	 * The connection uses the XMLDataSource class
	 * to read the connection parameters from the
	 * XML file specified by the passed parameters.
	 *
	 * @param string $name The name of the connection to use in the connection configuration XML file
	 * @param string $file The path inclucing the filename of the configuration file to use
	 * @return void
	 * @see XMLDataSource::createByName($name, $file)
	 * @see HttpSessionHandler::__construct()
	 */
	public function __construct($name, $file)
	{
		// set the probability for the garbage collector
		ini_set("session.gc_probability", 1);
		// initialize the data source to use for database connection
		$ds = TechDivision_Util_XMLDataSource::createByName($name, $file);
		// connect to the database
		$this->db = new MySQLi(
			$ds->getHost(),
			$ds->getUser(),
			$ds->getPassword(),
			$ds->getDatabase()
		);
		// always check if there was an error
		if(($errorNumber = mysqli_connect_errno())) {
			trigger_error(mysqli_connect_error(), E_USER_ERROR);
		}
		// turn autocommit on
		$this->db->autocommit($ds->getAutocommit());
		// set the default connection encoding
		$this->db->set_charset($ds->getEncoding());
		// register this class as session handler
		session_set_save_handler(
		array($this, 'open'),
		array($this, 'close'),
		array($this, 'read'),
		array($this, 'write'),
		array($this, 'destroy'),
		array($this, 'gc'));
		// start the session
		TechDivision_HttpUtils_HttpSessionHandler::__construct();
	}

	/**
	 * Writes the session data to the database
	 * and closes the database connection.
	 *
	 * @return void
	 * @see HttpSessionHandler::__destruct()
	 */
	public function __destruct()
	{
		// save the session data back to the database
		TechDivision_HttpUtils_HttpSessionHandler::__destruct();
		// close the database connection expicitly
		$this->db->close();
	}

	/**
	 * This method is invoked when the session handling
	 * opens the file with the session data to trying to
	 * load it from the file.
	 *
	 * Because we don't use a file, the function always
	 * returns true.
	 *
	 * @param string $path The path to the file with the session data
	 * @param string $name The session id with the filename to use
	 * @return boolean Is always true, because we don't use files
	 */
	public function open($path, $name) {
		return true;
	}

	/**
	 * This method is invoked when the session handling
	 * tries to write the session data to the file.
	 *
	 * Because we don't use a file, the function always
	 * returns true.
	 *
	 * @return boolean Is always true, because we don't use files
	 */
	public function close() {
		return true;
	}

	/**
	 * This method reads the session data for the session
	 * with the passed id from the database and returns it.
	 *
	 * @param string $sessionId Holds the session id of the requested data
	 * @return string Holds the session data
	 */
	public function read($sessionId)
	{
		// run the query and check that result is not an error
		if (($result = $this->db->query($sql = "SELECT session_data FROM web_session WHERE web_session_id = '" . $this->db->real_escape_string($sessionId) . "'", MYSQLI_STORE_RESULT)) == false) {
			trigger_error($this->db->error, E_USER_ERROR);
		}
		// assemble the result
		while ($row = $result->fetch_assoc()){
			return $row["session_data"];
		}
		// return an empty string if no session data was found
		return "";
	}

	/**
	 * This method writes the session data for the session
	 * with the passed id back to the database.
	 *
	 * @param string $sessionId Holds the session id of the requested data
	 * @param string $data The data of the session to write back to the database
	 * @return boolean Returns true if the data was successfully written to the database
	 */
	public function write($sessionId, $data)
	{
		// run the query and check that result is not an error
		if (($session = $this->db->query($sql = "SELECT modtime FROM web_session WHERE web_session_id = '" . $this->db->real_escape_string($sessionId) . "'", MYSQLI_STORE_RESULT)) == false) {
			trigger_error($this->db->error, E_USER_ERROR);
		}
		// check if a session with the passed id already exists
		if (($rowCount = $session->num_rows) == 0) {
			// run the query and check that result is not an error
			if (($result = $this->db->query($sql = "INSERT INTO web_session (web_session_id, modtime, session_data) VALUES('" . $this->db->real_escape_string($sessionId) . "', " . time() .", '" . $this->db->real_escape_string($data) . "')", MYSQLI_STORE_RESULT)) == false) {
				trigger_error($this->db->error, E_USER_ERROR);
			}
		}
		else {
			// run the query and check that result is not an error
			if(($result = $this->db->query($sql = "UPDATE web_session SET modtime = " . time() .", session_data = '" . $this->db->real_escape_string($data) . "' WHERE web_session_id = '" . $this->db->real_escape_string($sessionId) . "'", MYSQLI_STORE_RESULT)) == false) {
				trigger_error($this->db->error, E_USER_ERROR);
			}
		}
		// return true if the session was successfully written to the database
		return true;
	}

	/**
	 * This method deletes the session data for the
	 * session with the passed id from the database.
	 *
	 * @param string $sessionId The id of the session to delete the session data for
	 * @return boolean Return true if the session data was successfully deleted
	 */
	public function destroy($sessionId)
	{
		// run the query and check that result is not an error
		if (($result = $this->db->query($sql = "DELETE FROM web_session WHERE web_session_id = '" . $this->db->real_escape_string($sessionId) . "'", MYSQLI_STORE_RESULT)) == false) {
			trigger_error($this->db->error, E_USER_ERROR);
		}
		// return true if the session was successfully destroyed
		return true;
	}

	/**
	 * The garbage collector is automatically invoked by the system
	 * and deletes the overaged sessions fromt the database.
	 *
	 * @param integer $life The maximum lifetime for the sessions
	 * @return boolean True if the garbage collector run succuessfully
	 */
	public function gc($life)
	{
		// initialize the session lifetime
		$sessionLife = time() - $life;
		// run the query and check that result is not an error
		if (($result = $this->db->query($sql = "DELETE FROM web_session WHERE modtime < ".$sessionLife, MYSQLI_STORE_RESULT)) == false) {
			trigger_error($this->db->error, E_USER_ERROR);
		}
		// return true if the garbage collector succeeds
		return true;
	}
}