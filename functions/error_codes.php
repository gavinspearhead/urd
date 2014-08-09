<?php

/*
 *  This file is part of Urd.
 *  vim:ts=4:expandtab:cindent
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
 * $LastChangedDate: 2014-06-22 00:25:41 +0200 (zo, 22 jun 2014) $
 * $Rev: 3106 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: error_codes.php 3106 2014-06-21 22:25:41Z gavinspearhead@gmail.com $
 */

// urdd error codes -- start at 128
define ('ERR_THREAD_NOT_FOUND', 128);
define ('ERR_NO_SLOT_AVAILABLE', 129);
define ('ERR_NO_NNTPSLOT_AVAILABLE', 130);
define ('ERR_SERVER_EXISTS', 131);
define ('ERR_NO_SUCH_SERVER', 132);
define ('ERR_SERVER_IN_USE', 133);
define ('ERR_NO_ACTIVE_SERVER', 134);
define ('ERR_INVALID_COMMAND', 135); // command run through URDD not valid
define ('ERR_PID_NOT_FOUND', 136);
define ('ERR_ACCESS_DENIED', 137); // insufficient rights
define ('ERR_QUEUE_FAILED', 138);
define ('ERR_QUEUE_FULL', 139);
define ('ERR_ITEM_NOT_FOUND', 140);
define ('ERR_QUEUE_EMPTY', 141);
define ('ERR_CONFIG_ERROR', 142);
define ('ERR_DROP_PRIVS_FAILED', 143);
define ('ERR_GID_NOT_FOUND', 144);
define ('ERR_UID_NOT_FOUND', 145);
define ('ERR_UNKNOWN_ACTION', 146);
define ('ERR_PIPE_ERROR', 147);
define ('ERR_COMMANDLINE_ERROR', 148); // error while running an external command ??
define ('ERR_SOCKET_FAILURE', 149);
define ('ERR_NNTP_CONNECT_FAILED', 150);
define ('ERR_INVALID_STATUS', 151 );
define ('ERR_INVALID_USERID', 152);
define ('ERR_GROUP_NOT_FOUND', 153); // Usenet group not found
define ('ERR_INVALID_ARGUMENT', 154); // argument to RUDD command invalid
define ('ERR_FILE_NOT_FOUND', 155); // file not found on disk
define ('ERR_DOWNLOAD_NOT_FOUND', 156);  // download cannot be found in database
define ('ERR_INVALID_OPTION', 157); // URDD options in database not found
define ('ERR_PATH_NOT_FOUND', 158);
define ('ERR_INVALID_USERNAME', 159);
define ('ERR_INVALID_EMAIL', 160);
define ('ERR_NOT_LOGGED_IN', 161);
define ('ERR_INVALID_RESPONSE', 162);
define ('ERR_WAITED_TOO_LONG', 163);
define ('ERR_SEND_FAILED', 164); // sending email message failed
define ('ERR_ARTICLE_NOT_FOUND', 165);
define ('ERR_INVALID_TIMESTAMP', 166);
define ('ERR_INVALID_NNTPCOMMAND', 167);
define ('ERR_UNKNOWN_ENCODING', 168);
define ('ERR_INVALID_PHP_VERSION', 169);
define ('ERR_NO_USERS', 170);
define ('ERR_ALREADY_RUNNING', 171);
define ('ERR_PATH_NOT_WRITABLE', 172);
define ('ERR_RSS_NOT_FOUND', 173);
define ('ERR_RSS_FEED_FAILED', 174);
define ('ERR_MAGPIE_FAILED', 178);
define ('ERR_GENERIC_DB_ERROR', 179);
define ('ERR_RESTART_REQUESTED', 180);
define ('ERR_POST_NOT_FOUND', 181);
define ('ERR_NNTP_AUTH_FAILED', 182);
define ('ERR_MESSAGE_NOT_FOUND', 183);
define ('ERR_INVALID_SIGNATURE', 184);
define ('ERR_SERVER_INACTIVE', 185);
define ('ERR_SPOT_NOT_FOUND', 186);
define ('ERR_NO_SUCH_USER', 187);
define ('ERR_NNTP_TOO_MANY_CONNECTIONS', 188);
define ('ERR_INTERNAL_ERROR', 1000001);
