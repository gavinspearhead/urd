<?php

/*
 *  This file is part of Urd.
 *
 *  Urd is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *  Urd is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program. See the file "COPYING". If it does not
 *  exist, see <http://www.gnu.org/licenses/>.
 *
 * $LastChangedDate: 2011-05-15 23:15:22 +0200 (Sun, 15 May 2011) $
 * $Rev: 2158 $
 * $Author: gavinspearhead $
 * $Id: exception.php 2158 2011-05-15 21:15:22Z gavinspearhead $
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

class exception_nntp_auth extends exception
{
    public function __construct($msg)
    {
        parent::__construct($msg, ERR_NNTP_AUTH_FAILED);
    }
}

class exception_nntp_connect extends exception
{
    public function __construct($msg)
    {
        parent::__construct($msg, NNTP_NOT_CONNECTED_ERROR);
    }
}

class exception_db extends exception
{
    public function __construct($msg)
    {
        parent::__construct($msg, ERR_GENERIC_DB_ERROR);
    }
}

class exception_db_version extends exception
{
    public function __construct($msg)
    {
        parent::__construct($msg, ERR_GENERIC_DB_ERROR);
    }
}

class exception_internal extends exception
{
    public function __construct($msg)
    {
        parent::__construct($msg, ERR_INTERNAL_ERROR);
    }
}

class exception_invalid_command extends exception
{
    public function __construct($msg)
    {
        parent::__construct($msg, ERR_INVALID_COMMAND);
    }
}

class exception_queue_failed extends exception
{
    public function __construct($msg)
    {
        parent::__construct($msg, ERR_QUEUE_FAILED);
    }
}
