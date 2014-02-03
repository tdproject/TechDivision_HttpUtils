<?PHP

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

require_once 'TechDivision/Lang/Object.php';
require_once 'TechDivision/Collections/Enum.php';
require_once 'TechDivision/HttpUtils/Interfaces/Session.php';

/**
 * This class is a wrapper for the PHP internal array $_SESSION.
 *
 * @category TechDivision
 * @package TechDivision_HttpUtils
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
class TechDivision_HttpUtils_HttpSessionHandler
	extends TechDivision_Lang_Object
	implements TechDivision_HttpUtils_Interfaces_Session
{

	/**
	 * The constructor initializes the internal array
	 * with the global $_SESSION array.
	 *
	 * @return void
	 */
	public function __construct()
	{
		// initialize the session
		session_start();
	}

	/**
	 * The destructor unsets the internal members.
	 *
	 * @return void
	 */
	public function __destruct()
	{
		// write and close the session
		session_write_close();
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Session::getAttribute()
	 */
	public function getAttribute($name)
	{
		global $_SESSION;
		// initialize the attribute to return
		$attribute = null;
		// get the value
		if(array_key_exists($name, $_SESSION)) {
			$attribute = $_SESSION[$name];
		}
		// return the value
		return $attribute;
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Session::setAttribute()
	 */
	public function setAttribute($name, $attribute)
	{
		global $_SESSION;
		$_SESSION[$name] = $attribute;
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Session::getId()
	 */
	public function getId()
	{
		return session_id();
	}

	/**
	 * This method returns the name of the session.
	 *
	 * @return string Holds the name of the session
	 */
	public function getName(){
		return session_name();
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Session::getAttributeNames()
	 */
	public function getAttributeNames() {
		global $_SESSION;
		return new Enum(array_keys($_SESSION));
	}

	/**
	 * This method returns the number of attributes
	 * found in the session.
	 *
	 * @return integer Holds the number of attributes found in the session
	 */
	public function count()
	{
		global $_SESSION;
		return sizeof($_SESSION);
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Session::invalidate()
	 */
    public function invalidate()
    {
		// delete the cookie with the session id
		setcookie(session_name(), false);
		// destroy the session data itself
        session_destroy();
    }

    /**
     * (non-PHPdoc)
     * @see TechDivision_HttpUtils_Interfaces_Session::removeAttribute()
     */
    public function removeAttribute($name)
    {
		global $_SESSION;
        unset($_SESSION[$name]);
    }

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Session::getCreationTime()
	 */
	public function getCreationTime()
	{
		// @todo Still to implement
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Session::getLastAccessedTime()
	 */
	public function getLastAccessedTime()
	{
		// @todo Still to implement
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Session::setMaxInactiveInterval()
	 */
	public function setMaxInactiveInterval($interval)
	{
		// @todo Still to implement
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Session::getMaxInactiveInterval()
	 */
	public function getMaxInactiveInterval()
	{
		// @todo Still to implement
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Session::isNew()
	 */
	public function isNew()
	{
		// @todo Still to implement
	}
}