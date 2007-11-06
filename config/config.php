<?php

/*
 *
 * Copyright 2006, Alex Lance, Clancy Malcolm, Cybersource Pty. Ltd.
 * 
 * This file is part of allocPSA <info@cyber.com.au>.
 * 
 * allocPSA is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 * 
 * allocPSA is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License along with
 * allocPSA; if not, write to the Free Software Foundation, Inc., 51 Franklin
 * St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 */

require_once("../alloc.php");

if (!have_entity_perm("config", PERM_UPDATE, $current_user, true)) {
  die("Permission denied.");
}

$config = new config;

$db = new db_alloc;
$db->query("SELECT name,value,type FROM config");
while ($db->next_record()) {
  $fields_to_save[] = $db->f("name");
  $types[$db->f("name")] = $db->f("type");

  if ($db->f("type") == "text") {
    $TPL[$db->f("name")] = htmlentities($db->f("value"));

  } else if ($db->f("type") == "array") {
    $TPL[$db->f("name")] = unserialize($db->f("value"));
  }
}


#echo "<pre>".print_r($_POST,1)."</pre>";

if ($_POST["save"]) {

  if ($_POST["hoursInDay"]) {
    $db = new db_alloc;
    $day = $_POST["hoursInDay"]*60*60;
    $q = sprintf("UPDATE timeUnit SET timeUnitSeconds = '%d' WHERE timeUnitName = 'day'",$day);
    $db->query($q);
    $q = sprintf("UPDATE timeUnit SET timeUnitSeconds = '%d' WHERE timeUnitName = 'week'",($day*5));
    $db->query($q);
    $q = sprintf("UPDATE timeUnit SET timeUnitSeconds = '%d' WHERE timeUnitName = 'month'",(($day*5)*4));
    $db->query($q);
  }

  // remove bracketed [Alex Lance <]alla@cyber.com.au[>] bits, leaving just alla@cyber.com.au
  if ($_POST["AllocFromEmailAddress"]) {
    $_POST["AllocFromEmailAddress"] = preg_replace("/^.*</","",$_POST["AllocFromEmailAddress"]);
    $_POST["AllocFromEmailAddress"] = str_replace(">","",$_POST["AllocFromEmailAddress"]);
  }

  foreach ($_POST as $name => $value) {

    if (in_array($name,$fields_to_save)) {

      $id = $config->get_config_item_id($name);
      $c = new config;
      $c->set_id($id);
      $c->select();

      if ($types[$name] == "text") {
        $c->set_value("value",$_POST[$name]);
        $TPL[$name] = htmlentities($_POST[$name]);
      } else if ($types[$name] == "array") {
        $c->set_value("value",serialize($_POST[$name]));
        $TPL[$name] = $_POST[$name];
      }
      $c->save();
      $TPL["message_good"] = "Saved configuration.";
    }
  }
}

$config = new config;
get_cached_table("config",true); // flush cache

$db->query("SELECT * FROM tf ORDER BY tfName");
$options[""] = "";
while($row = $db->row()) {
  $options[$row["tfID"]] = $row["tfName"];
}
$TPL["tfOptions"] = get_select_options($options, $config->get_config_item("cybersourceTfID"));
$TPL["taxTfOptions"] = get_select_options($options, $config->get_config_item("taxTfID"));

$db = new db_alloc;
$display = array("", "username", ", ", "emailAddress");

$db->query("SELECT * FROM person ORDER BY username");
$TPL["timeSheetAdminEmailOptions"] = get_option("Time Sheet Admin (email)", "0", false)."\n";
$TPL["timeSheetAdminEmailOptions"].= get_options_from_db($db, $display, "personID", $config->get_config_item("timeSheetAdminEmail"));

$days =  array("Sun"=>"Sun","Mon"=>"Mon","Tue"=>"Tue","Wed"=>"Wed","Thu"=>"Thu","Fri"=>"Fri","Sat"=>"Sat");
$TPL["calendarFirstDayOptions"] = get_select_options($days,$config->get_config_item("calendarFirstDay"));

$TPL["timeSheetPrintOptions"] = get_select_options($TPL["timeSheetPrintOptions"],$TPL["timeSheetPrint"]);


include_template("templates/configM.tpl");



?>
