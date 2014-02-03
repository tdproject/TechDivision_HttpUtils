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
 * Provides a way to identify a user across more than one page request or
 * visit to a Web site and to store information about that user.
 *
 * The server uses this interface to create a session between an HTTP
 * client and an HTTP server. The session persists for a specified time
 * period, across more than one connection or page request from the user.
 * A session usually corresponds to one user, who may visit a site many
 * times. The server can maintain a session in many ways such as using
 * cookies or rewriting URLs.
 *
 * This interface allows servlets to
 * - View and manipulate information about a session, such as the session
 *   identifier, creation time, and last accessed time
 * - Bind objects to sessions, allowing user information to persist
 *   across multiple user connections
 *
 * When an application stores an object in or removes an object
 * from a session, the session checks whether the object implements
 * HttpSessionBindingListener. If it does, the servlet notifies the object
 * that it has been bound to or unbound from the session. Notifications
 * are sent after the binding methods complete. For session that are
 * invalidated or expire, notifications are sent after the session has
 * been invalidatd or expired.
 *
 * When container migrates a session between VMs in a distributed
 * container setting, all session atributes implementing the
 * HttpSessionActivationListener interface are notified.
 *
 * A servlet should be able to handle cases in which the client does not
 * choose to join a session, such as when cookies are intentionally turned
 * off. Until the client joins the session, isNew returns true. If the
 * client chooses not to join the session, getSession will return a
 * different session on each request, and isNew will always return true.
 *
 * Session information is scoped only to the current web application
 * (ServletContext), so information stored in one context will not be
 * directly visible in another.
 *
 * @category TechDivision
 * @package TechDivision_HttpUtils
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
interface TechDivision_HttpUtils_Interfaces_Session
{

	/**
	 * Returns a string containing the unique identifier
	 * assigned to this session.
	 *
	 * @return string Returns a string specifying the identifier assigned to this session
	 */
	public function getId();

	/**
	 * Returns the time when this session was created, measured
	 * in milliseconds since midnight January 1, 1970 GMT.
	 *
	 * @return long Returns a long specifying when this session was created, expressed in milliseconds since 1/1/1970 GMT
	 * @throws IllegalStateException If this method is called on an invalidated session
	 */
	public function getCreationTime();

	/**
	 * Returns the last time the client sent a request associated
	 * with this session, as the number of milliseconds since
	 * midnight January 1, 1970 GMT, and marked by the time the
	 * server recieved the request.
	 *
	 * Actions that your application takes, such as getting or
	 * setting a value associated with the session, do not affect
	 * the access time.
	 *
	 * @return long Returns a long representing the last time the client sent a request associated with this session, expressed in milliseconds since 1/1/1970 GMT
	 */
	public function getLastAccessedTime();

	/**
	 * Specifies the time, in seconds, between client requests
	 * before the server will invalidate this session. A negative
	 * time indicates the session should never timeout.
	 *
	 * @param integer $interval An integer Specifying the number of seconds
	 * @return void
	 */
	public function setMaxInactiveInterval($interval);

	/**
	 * Returns the maximum time interval, in seconds, that
	 * the server will keep this session open between client
	 * accesses. After this interval, the server will
	 * invalidate the session. The maximum time interval can
	 * be set with the setMaxInactiveInterval method. A
	 * negative time indicates the session should never timeout.
	 *
	 * @return integer Return an integer specifying the number of seconds this session remains open between client requests
	 * @see Session::setMaxInactiveInterval($interval)
	 */
	public function getMaxInactiveInterval();

	/**
	 * This method returns the attribute specified
	 * by the key passed as a parameter.
	 *
	 * @param string $name Holds the key of the requested attribute
	 * @return mixed Returns the requested attribute or null if the attribute is not in the request
	 * @throws IllegalStateException If this method is called on an invalidated session
	 */
	public function getAttribute($name);

	/**
	 * Returns an Enumeration of strings containing the names of
	 * all the objects bound to this session.
	 *
	 * @return Enumeration Returns an Enumeration of strings specifying the names of all the objects bound to this session
	 * @throws IllegalStateException If this method is called on an invalidated session
	 */
	public function getAttributeNames();

	/**
	 * Binds an object to this session, using the name specified.
	 * If an object of the same name is already bound to the
	 * session, the object is replaced.
	 *
	 * If the value passed in is null, this has the same effect
	 * as calling removeAttribute().
	 *
	 * @param string $name Holds the key under that the attribute will be registered
	 * @param mixed $attribute Holds a reference to the attribute that will be added
	 * @return void
	 * @throws IllegalStateException If this method is called on an invalidated session
	 */
	public function setAttribute($name, $attribute);

	/**
	 * This method removes the attribute specified by the
     * key passed as parameter from the request.
     *
     * @param string $name Holds the key of the attribute to be removed from the request
     * @throws IllegalStateException If this method is called on an invalidated session
     */
    public function removeAttribute($name);

	/**
	 * Invalidates this session then unbinds any objects
	 * bound to it.
	 *
	 * @return void
	 * @throws IllegalStateException If this method is called on an already invalidated session
	 */
	public function invalidate();

	/**
	 * Returns true if the client does not yet know about the
	 * session or if the client chooses not to join the
	 * session.
	 *
	 * For example, if the server used only cookie-based sessions,
	 * and the client had disabled the use of cookies, then a
	 * session would be new on each request.
	 *
	 * @return boolean Returns true if the server has created a session, but the client has not yet joined
	 */
	public function isNew();
}