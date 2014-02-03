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

require_once "TechDivision/Lang/Object.php";
require_once "TechDivision/Collections/HashMap.php";
require_once "TechDivision/HttpUtils/Interfaces/Request.php";
require_once "TechDivision/HttpUtils/HttpSessionHandler.php";

/**
 * This class is the abstract base class for all
 * Actions.
 *
 * @category TechDivision
 * @package TechDivision_HttpUtils
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
class TechDivision_HttpUtils_HttpRequest
	extends TechDivision_Lang_Object
    implements TechDivision_HttpUtils_Interfaces_Request {

	/**
	 * Holds the class name of the default session handler.
	 * @var string
	 */
	const HTTP_SESSION_HANDLER = "TechDivision_HttpUtils_HttpSessionHandler";

	/**
	 * Holds the class name of the MySQLi based session handler.
	 * @var string
	 */
	const HTTP_MYSQLI_SESSION_HANDLER = "TechDivision_HttpUtils_HttpMySQLiSessionHandler";

	/**
	 * The class name for the session handler, defaults to HttpSessionHandler.
	 * @var string
	 */
	protected $sessionHandler = TechDivision_HttpUtils_HttpRequest::HTTP_SESSION_HANDLER;

	/**
	 * The arguments for the constructor of the selected session handler.
	 * @var array
	 */
	protected $sessionArgs = array();

	/**
	 * Holds the actual session object.
	 * @var Session
	 */
	protected $session = null;

	/**
	 * Holds the attributes of the actual request.
	 * @var array
	 */
	protected $attributes = array();

	/**
	 * Holds a list with available filters.
	 * @var array
	 */
	protected $filterList = array();

	/**
	 * Holds the actual HttpRequest instance.
	 * @var HttpRequest
	 */
	protected static $INSTANCE = null;

	/**
	 * Holds the key for a get request.
	 * @var string
	 */
	public static $REQUEST_METHOD_GET = "GET";

	/**
	 * Holds the key for a post request.
	 * @var string
	 */
	public static $REQUEST_METHOD_POST = "POST";

	/**
	 * Constructor to initialize the internal list
	 * with filters available in the system.
	 *
	 * @return void
	 */
	public function __construct()
	{
		// get the list with registered filters
		foreach (filter_list() as $filterName) {
			$this->filterList[filter_id($filterName)] = $filterName;
		}
	}

	/**
	 * This method returns the acutal HttpRequest
	 * instance as a singleton.
	 *
	 * @return HttpRequest Holds the acutal HttpRequest instance
	 */
	public function singleton()
	{
		// check it the HttpRequest is already initialized
		if(self::$INSTANCE == null) {
			// if not, initialize it
			self::$INSTANCE = new TechDivision_HttpUtils_HttpRequest();
		}
		// return the actual HttpRequest instance
		return self::$INSTANCE;
	}

	/**
	 * @see Request::getAttribute($name)
	 */
	public function getAttribute($name)
	{
		// check if a attribute exists
		if (array_key_exists($name, $this->attributes)) {
			// if yes, return it
			return $this->attributes[$name];
		}
		// else return nothing
		return;
	}

	/**
	 * @see Request::setAttribute($name, $attribute)
	 */
	public function setAttribute($name, $attribute)
	{
		$this->attributes[$name] = $attribute;
	}

	/**
	 * Sets the session handler to use.
	 *
	 * @param string $sessionHandler The class name of the session handler to use
	 * @return void
	 */
	public function setSessionHandler($sessionHandler)
	{
		$this->sessionHandler = $sessionHandler;
	}

	/**
	 * Sets the arguments passed to the constructor of
	 * the session handler to use.
	 *
	 * @param array $sessionArgs The arguments passed to the constructor of the session handler
	 * @return void
	 */
	public function setSessionArgs($sessionArgs)
	{
		$this->sessionArgs = $sessionArgs;
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Request::getSession()
	 */
	public function getSession($create = true)
	{
		// check if the Session is already initialized
		if ($this->session == null && $create) {
			// if not, initialized it by reflection
			$reflectionClass = new ReflectionClass($this->sessionHandler);
			$this->session = $reflectionClass->newInstanceArgs($this->sessionArgs);
		}
		// return the initialized Session
		return $this->session;
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Request::removeAttribute()
	 */
	public function removeAttribute($name)
	{
		unset($this->attributes[$name]);
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Request::getQueryString()
	 */
	public function getQueryString()
	{
		return getenv("QUERY_STRING");
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Request::getRequestURI()
	 */
	public function getRequestURI()
	{
		return getenv("REQUEST_URI");
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Request::getRequestURL()
	 */
	public function getRequestURL()
	{
		return getenv("SCRIPT_NAME");
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Request::getServerName()
	 */
	public function getServerName()
	{
		return getenv("SERVER_NAME");
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Request::getServerAddr()
	 */
	public function getServerAddr()
	{
		return getenv("SERVER_ADDR");
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Request::getServerPort()
	 */
	public function getServerPort()
	{
		return getenv("SERVER_PORT");
	}

	/**
	 *
	 * Enter description here ...
	 * @return string
	 */
	public function getRedirectUrl()
	{
		return getenv("REDIRECT_URL");
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Request::getRequestMethod()
	 */
	public function getRequestMethod()
	{
		return getenv("REQUEST_METHOD");
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Request::getRemoteHost()
	 */
	public function getRemoteHost()
	{
		// @todo Has to be implemented
		return null;
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Request::getRemoteAddr()
	 */
	public function getRemoteAddr()
	{
		return getenv("REMOTE_ADDR");
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Request::getScriptFilename()
	 */
	public function getScriptFilename()
	{
		return getenv("SCRIPT_FILENAME");
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Request::getScriptName()
	 */
	public function getScriptName()
	{
		return getenv("SCRIPT_NAME");
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Request::getUserAgent()
	 */
	public function getUserAgent()
	{
		return getenv("HTTP_USER_AGENT");
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Request::getParameter()
	 */
	public function getParameter(
		$name,
		$filter = null,
		$filterOptions = null)
	{
		// globalize the request
		global $_REQUEST;
		// get the value
		if (array_key_exists($name, $_REQUEST)) {
			if (!is_array($_REQUEST[$name])) {
				// return the filtered / sanitized value if it is not an array
				return $this->_filter($name, $filter);
			}
		}
		// else return nothing
		return;
	}

	/**
	 * This method filters the passed value with the PHP filter_var
	 * function and the filter specified as parameter.
	 *
	 * If the specified filter is null, the original untouched
	 * value is returned. If the filter fails, the method returns
	 * false.
	 *
	 * @param string $name Holds the name of the value that should be filtered
	 * @param integer $filter Holds the filter to apply to the value
	 * @param mixed Holds the filter options if available
	 * @throws Exception Is thrown if an invalid filter was specified or if the specified filter can't be applied on the passed value
	 */
	protected function _filter(
		$name,
		$filter = null,
		$filterOptions = null)
	{
		// globalize the request
		global $_REQUEST;
		// return the filtered value
		if (!empty($filter) && !empty($_REQUEST[$name])) {
			// check if a valid filter was specified
			if (!array_key_exists($filter, $this->filterList)) {
				throw new Exception(
						"Try to apply not existing filter $filter on value " .
				$_REQUEST[$name]
				);
			}
			// filter the value
			$filteredValue = filter_var(
			$_REQUEST[$name],
			$filter,
			$filterOptions
			);
			// return the filtered / sanitized value if it is not an array
			if ($filteredValue != false) {
				// return the filtered value
				return $filteredValue;
			}
			// throw an exception if the specified filter can not
			// be applied on the passed value
			throw new Exception(
					"Error when invoking filter " . $this->filterList[$filter] .
					" on value " . $_REQUEST[$name]
			);
		}
		// else return the value untouched
		return $_REQUEST[$name];
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Request::getParameterMap()
	 */
	public function getParameterMap()
	{
		// globalize the request
		global $_REQUEST;
		// return a HashMap with the request parameters
		return new TechDivision_Collections_HashMap($_REQUEST);
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Request::getParameterNames()
	 */
	public function getParameterNames()
	{
		// globalize the request
		global $_REQUEST;
		// return the keys as array
		return array_keys($_REQUEST);
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Request::getParameterValues()
	 */
	public function getParameterValues($name)
	{
		// globalize the request
		global $_REQUEST;
		// get the value
		if (array_key_exists($name, $_REQUEST)) {
			if (is_array($_REQUEST[$name])) {
				// get the value if it is an array
				return $_REQUEST[$name];
			}
		}
		// check if it is a upload, then get the values
		if (array_key_exists($name, $_FILES)) {
			return $_FILES[$name];
		}
		// else return nothing
		return;
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Request::getReferer()
	 */
	public function getReferer()
	{
		return getenv("HTTP_REFERER");
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Request::getRequestedSessionId()
	 */
	public function getRequestedSessionId()
	{
		// @todo Still to implement
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Request::isRequestedSessionIdValid()
	 */
	public function isRequestedSessionIdValid()
	{
		// @todo Still to implement
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Request::isRequestedSessionIdFromURL()
	 */
	public function isRequestedSessionIdFromURL()
	{
		// @todo Still to implement
	}

	/**
	 * (non-PHPdoc)
	 * @see TechDivision_HttpUtils_Interfaces_Request::isRequestedSessionIdFromCookie()
	 */
	public function isRequestedSessionIdFromCookie()
	{
		// @todo Still to implement
	}

	/**
	 * This method sets all items of the internal array
	 * in the global $_REQUEST variable.
	 *
	 * @return void
	 */
	public function toRequest()
	{
		// globalize the request
		global $_REQUEST;
		// add all attributes back to the request
		foreach ($this->attributes as $key => $attribute) {
			$_REQUEST[$key] = $attribute;
		}
	}
}