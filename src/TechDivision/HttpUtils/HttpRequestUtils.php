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
class TechDivision_HttpUtils_HttpRequestUtils {

	/**
	 * Holds the key for the query string in the result array
	 * of the parse_url method.
	 * @var string
	 */
	protected static $QUERY = 'query';

	/**
	 * This private constructor marks this class as utility class.
	 * @var void
	 */
	protected function __construct()
	{
		// marks the class as util
	}

	/**
	 * This static method returns the query string
	 * from the passed url.
	 *
	 * @param string $url Holds the url to extract the query string from
	 * @return string Holds the extracted query string
	 */
	public static function getQueryString($url)
	{
		$parsedUrl = parse_url($url);
		if (array_key_exists(self::$QUERY, $parsedUrl)) {
			return $parsedUrl[self::$QUERY];
		}
	}

	/**
	 * This method returns the parameter with the passed
	 * name from the also passed query string.
	 *
	 * @param string $query Holds the query string to return the parameter from
	 * @param string $name Holds the name of the requested parameter
	 * @return mixed Holds the requested parameter as string or array
	 */
	public static function getParameter($query, $name)
	{
		$parameter = array();
		parse_str($query, $parameter);
		if (array_key_exists($name, $parameter)) {
			return $parameter[$name];
		}
	}
}