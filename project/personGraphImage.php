<?php

/*
 *
 * Copyright 2006, Alex Lance, Clancy Malcolm, Cybersource Pty. Ltd.
 * 
 * This file is part of AllocPSA <info@cyber.com.au>.
 * 
 * AllocPSA is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 * 
 * AllocPSA is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License along with
 * AllocPSA; if not, write to the Free Software Foundation, Inc., 51 Franklin
 * St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 */

require_once("alloc.inc");
include("lib/task_graph.inc.php");

if ($projectID) {
  $options["projectIDs"][] = $projectID;
}

$options["personIDonly"] = $personID;
$options["taskView"] = "prioritised";
$options["return"] = "objects";
$options["taskStatus"] = "in_progress";

if ($graph_type == "phases") {
  $options["taskTypeID"] = TT_PHASE;
}

$task_graph = new task_graph;
$task_graph->bottom_margin = 20;

$top_tasks = task::get_task_list($options);
$task_graph->init($options,$top_tasks);
$task_graph->draw_grid();

reset($top_tasks);
while (list(, $task) = each($top_tasks)) {
  $task_graph->draw_task($task, false);
}

$task_graph->draw_milestones();
$task_graph->draw_today();

$task_graph->output();

page_close();



?>
