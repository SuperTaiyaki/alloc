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


define("NO_REDIRECT",1);
require_once("../alloc.php");

$tasks = search::by_fulltext("task", "taskName", $_GET["taskName"], sprintf("projectID = %d",$_GET["projectID"])." AND ");
foreach ($tasks as $task) {
  $str.= "<div style='padding-bottom:3px'>";
  $str.= "<a href=\"".$TPL["url_alloc_task"]."taskID=".$task["taskID"]."\">".$task["taskID"]." ".$task["taskName"]."</a>";
  $status = "";
  $task["taskStatus"] == "closed" and $status = "[Closed ".$task["dateActualCompletion"]."]";
  $str.= " ".$status;
  $str.= "</div>";
}

if ($str) {
  echo $str;
}


?>