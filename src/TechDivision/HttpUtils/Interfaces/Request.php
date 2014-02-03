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

/**
 * Defines an object to provide client request information to a script.
 * The server creates a Request object and passes it as an argument to
 * the scripts service method.
 *
 * A Request object provides data including parameter name and values,
 * attributes, and an input stream. Interfaces that extend Request can
 * provide additional protocol-specific data (for example, HTTP data
 * is provided by HttpRequest.
 *
 * @category TechDivision
 * @package TechDivision_HttpUtils
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
interface TechDivision_HttpUtils_Interfaces_Request {

	/**
	 * Returns the query string that is contained
	 * in the request URL after the path.
	 *
	 * @return string Returns the query string
	 */
	public function getQueryString();

	/**
	 * Returns the part of this request's URL from
	 * the protocol name up to the query string in
	 * the first line of the HTTP request.
	 *
	 * @return string Returns the URL from the protocol up to the query string
	 */
	public function getRequestURI();

	/**
	 * Reconstructs the URL the client used to make the request.
	 *
	 * @return string Returns the reconstructed URL
	 */
	public function getRequestURL();

	/**
	 * Returns the host name of the server that received the request,
	 * that is the same as the value of the CGI variable SERVER_NAME.
	 *
	 * @return string Retuns the name of the server to which the request was sent
	 */
	public function getServerName();

	/**
	 * Returns the Internet Protocol (IP) address of the server that
	 * gets the request, that is the same as the value of the CGI
	 * variable SERVER_ADDR.
	 *
	 * @return string Returns a string containing the IP address of the server that gets the request
	 */
	public function getServerAddr();

	/**
	 * Returns the port number on which this request was received,
	 * that is the same as the value of the CGI variable SERVER_PORT.
	 *
	 * @return integer An integer specifying the port number
	 */
	public function getServerPort();

	/**
	 * Returns the request method of the actual request,
	 * that is the same as the value of the CGI variable REQUEST_METHOD.
	 *
	 * @return string Returns a string with the actual request method
	 */
	public function getRequestMethod();

	/**
	 * Returns the fully qualified name of the client that sent the
	 * request. If the engine cannot or chooses not to resolve the
	 * hostname (to improve performance), this method returns the
	 * dotted-string form of the IP address, that is the same as
	 * the value of the CGI variable REMOTE_HOST.
	 *
	 * @return string Returns a string containing the fully qualified name of the client
	 */
	public function getRemoteHost();

	/**
	 * Returns the Internet Protocol (IP) address of the client that
	 * sent the request, that is the same as the value of the CGI
	 * variable REMOTE_ADDR.
	 *
	 * @return string Returns a string containing the IP address of the client that sent the request
	 */
	public function getRemoteAddr();

	/**
	 * Holds the absolute path of the acutal script. That is the
	 * same as the value of the CGI variable SCRIPT_FILENAME.
	 *
	 * @return string The script filename with root path
	 */
	public function getScriptFilename();

	/**
	 * Holds the path of the acutal script. This can be useful for
	 * referencing the script by itself. That is the same as the
	 * value of the CGI variable SCRIPT_NAME.
	 *
	 * @return string The script name
	 */
	public function getScriptName();

	/**
	 * Returns the user agent string of the browser
	 * the user with the actual request uses.
	 *
	 * @return string The user agent string
	 */
	public function getUserAgent();

	/**
	 * Returns the value of a request parameter as a String, or
	 * null if the parameter does not exist. Request parameters
	 * are extra information sent with the request. Parameters
	 * are contained in the query string or posted form data.
	 *
	 * You should only use this method when you are sure the
	 * parameter has only one value. If the parameter might have
	 * more than one value, use getParameterValues(string).
	 *
	 * If you use this method with a multivalued parameter,
	 * the value returned is equal to the first value in the
	 * array returned by getParameterValues.
	 *
	 * @param string $name A String specifying the name of the parameter
	 * @param integer $filter Holds the filter to apply to the value
	 * @param mixed Holds the filter options if available
	 * @return string Returns a String representing the single value of the parameter
	 * @throws Exception Is thrown if an invalid filter was specified or if the specified filter can't be applied on the passed value
	 */
	public function getParameter($name, $filter = null, $filterOptions = null);

	/**
	 * Returns a HashMap of the parameters of this request.
	 * Request parameters are extra information sent with
	 * the request. Parameters are contained in the query
	 * string or posted form data.
	 *
	 * @return HashMap Returns an immutable HashMap containing parameter names as keys and parameter values as map values. The keys in the parameter map are of type string. The values in the parameter map are of type array.
	 */
	public function getParameterMap();

	/**
	 * Returns an Enumeration of strings containing the names of
	 * the parameters contained in this request. If the request
	 * has no parameters, the method returns an empty Enumeration.
	 *
	 * @return Enumeration Returns an an Enumeration of strings, each string containing the name of a request parameter; or an empty Enumeration if the request has no parameters
	 */
	public function getParameterNames();

	/**
	 * Returns an array of strings containing all of the values
	 * the given request parameter has, or null if the parameter
	 * does not exist.
	 *
	 * If the parameter has a single value, the array has a
	 * length of 1.
	 *
	 * @param string $name A atring containing the name of the parameter whose value is requested
	 * @return array An array of strings containing the parameter's values
	 * @see Request::getParameter($name)
	 */
	public function getParameterValues($name);

	/**
	 * This method returns the attribute specified
	 * by the key passed as a parameter.
	 *
	 * @param string $name Holds the key of the requested attribute
	 * @return mixed Returns the requested attribute or null if the attribute is not in the request
	 */
	public function getAttribute($name);

	/**
	 * This method sets the attribute passed as a
	 * parameter in the internal array under the
	 * key passed as a parameter too.
	 *
	 * @param string $name Holds the key under that the attribute will be registered
	 * @param mixed $attribute Holds a reference to the attribute that will be added
	 */
	public function setAttribute($name, $attribute);

	/**
	 * Returns the current Session associated with this
	 * request or, if if there is no current session and
	 * create is true, returns a new session.
	 *
	 * If create is false and the request has no valid
	 * Session, this method returns null.
	 *
	 * To make sure the session is properly maintained,
	 * you must call this method before the response is
	 * committed.
	 *
	 * @param boolean $create Has to be true to create a new session for this request if necessary; false to return null if there's no current session
	 * @return Session Returns the Session associated with this request or null if create is false  and the request has no valid session
	 * @throws IllegalStateException If the server is using cookies to maintain session integrity and is asked to create a new session when the response is committed
	 */
	public function getSession($create = true);

	/**
	 * This method removes the attribute specified by the
	 * key passed as parameter from the request.
	 *
	 * @param string $name Holds the key of the attribute to be removed from the request
	 */
	public function removeAttribute($name);

	/**
	 * Returns the session ID specified by the client.
	 *
	 * @return string Returns the session ID
	 * @see Request::isRequestedSessionIdValid()
	 */
	public function getRequestedSessionId();

	/**
	 * Checks whether the requested session ID is still valid.
	 *
	 * @return boolean Returns true if this request has an id for a valid session; false otherwise
	 * @see Request::getRequestedSessionId()
	 * @see Request::getSession()
	 */
	public function isRequestedSessionIdValid();

	/**
	 * Checks whether the requested session ID came
	 * in as part of the request URL.
	 *
	 * @return boolean Returns true if the session ID came in as part of a URL; otherwise, false
	 * @see Request::getSession()
	 */
	public function isRequestedSessionIdFromURL();

	/**
	 * Checks whether the requested session ID came
	 * in as a cookie.
	 *
	 * @return boolean Returns true if the session ID came in as a cookie; otherwise, false
	 * @see Request::getSession()
	 */
	public function isRequestedSessionIdFromCookie();

	/**
	 * The address of the page (if any) which referred the user agent to the current page.
	 * This is set by the user agent. Not all user agents will set this, and some provide
	 * the ability to modify the referer as a feature. In short, it cannot really be
	 * trusted.
	 *
	 * @return string The referer which the user agent referred to the current page
	 */
	public function getReferer();

	/**
	 * Retrns the redirect URL if the request has been
	 * redirected before.
	 *
	 * @return The URL that has been redirected from
	 */
	public function getRedirectUrl();
}