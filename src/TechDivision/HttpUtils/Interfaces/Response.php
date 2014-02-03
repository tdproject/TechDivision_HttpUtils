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
interface TechDivision_HttpUtils_Interfaces_Response {
}