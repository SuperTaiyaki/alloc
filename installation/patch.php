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

define("NO_AUTH",1);
require_once("../alloc.php");

$db = new db_alloc;

// Get list of patch files in order
$files = get_patch_file_list();

// Get the most recently applied patch
$applied_patches = get_applied_patches();

// This script can potentially be called via the livealloc patch system via GET
$apply_patches = $_POST["apply_patches"] or $apply_patches = $_GET["apply_patches"];

$_POST["patches_to_apply"] or $_POST["patches_to_apply"] = array();

if ($apply_patches) {


  foreach ($files as $file) {
    $comments or $comments = array();
    $f = ALLOC_MOD_DIR."patches/".$file;

      
    $go = false;
    $go2 = false;

    if (!in_array($file,$applied_patches)) {
      $go = true;
    }

    // The livealloc patch system doesn't use the interactive patching mechanism, so by default apply all patches that haven't been applied
    if (in_array($file,$_POST["patches_to_apply"]) || $_GET["apply_patches"]) {
      $go2 = true;
    }

    if ($go && $go2) {

      #$msg[$f][] = "<b>Attempting:</b> ".$file."<br/>";

      // Try for sql file
      if (strtolower(substr($file,-4)) == ".sql") {

        list($sql,$comments) = parse_sql_file($f);
        foreach ($sql as $query) {
          if (!$db->query($query)) {
            $TPL["message"][] = "<b style=\"color:red\">Error:</b> ".$f."<br/>".$db->get_error();
            $failed[$f] = true;
          }
        }
        if (!$failed[$f]) {
          $TPL["message_good"][] = "Successfully Applied: ".$f;
        }

      // Try for php file
      } else if (strtolower(substr($file,-4)) == ".php") {
        ob_start();
        include("../patches/".$file);
        $str = ob_get_contents();
        if ($str) { 
          $TPL["message"][] = "<b style=\"color:red\">Error:</b> ".$f."<br/>".$str;
          $failed[$f] = true;
          ob_end_clean();
        } else {
          $TPL["message_good"][] = "Successfully Applied: ".$f;
        }
      }


      if (!$failed[$f]) {
        $q = sprintf("INSERT INTO patchLog (patchName, patchDesc, patchDate) 
                      VALUES ('%s','%s','%s')",db_esc($file), db_esc(implode(" ",$comments)), date("Y-m-d H:i:s"));
        $db->query($q);
      } 
    }
  }
}


$applied_patches = get_applied_patches();
foreach ($files as $file) {
  if (!in_array($file,$applied_patches)) {
    $incomplete = true;
  }
}


if (!$incomplete) {
  header("Location: ".$TPL["url_alloc_login"]);
} 


include_template("templates/patch.tpl");

?>
