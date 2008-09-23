<?php

/*
 * Copyright (C) 2006, 2007, 2008 Alex Lance, Clancy Malcolm, Cybersource
 * Pty. Ltd.
 * 
 * This file is part of the allocPSA application <info@cyber.com.au>.
 * 
 * allocPSA is free software: you can redistribute it and/or modify it
 * under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or (at
 * your option) any later version.
 * 
 * allocPSA is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU Affero General Public
 * License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with allocPSA. If not, see <http://www.gnu.org/licenses/>.
*/


require_once(dirname(__FILE__)."/page.inc.php");
require_once(dirname(__FILE__)."/template.inc.php");
require_once(dirname(__FILE__)."/class_db.inc.php");
require_once(dirname(__FILE__)."/class_db_alloc.inc.php");
require_once(dirname(__FILE__)."/class_session.inc.php");
require_once(dirname(__FILE__)."/class_db_field.inc.php");
require_once(dirname(__FILE__)."/class_db_entity.inc.php");
require_once(dirname(__FILE__)."/class_module.inc.php");
require_once(dirname(__FILE__)."/class_alloc_cache.inc.php");
require_once(dirname(__FILE__)."/class_history.inc.php");
require_once(dirname(__FILE__)."/class_PasswordHash.php");
require_once(dirname(__FILE__)."/class_interestedParty.inc.php");

class shared_module extends module {
  var $db_entities = array("sentEmailLog");
}




?>
