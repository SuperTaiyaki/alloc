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

class token extends db_entity {
  var $classname = "token";
  var $data_table = "token";

  function token() {
    $this->db_entity(); 
    $this->key_field = new db_field("tokenID");
    $this->data_fields = array("tokenHash"=>new db_field("tokenHash")
                              ,"tokenEntity"=>new db_field("tokenEntity")
                              ,"tokenEntityID"=>new db_field("tokenEntityID")
                              ,"tokenActionID"=>new db_field("tokenActionID")
                              ,"tokenExpirationDate"=>new db_field("tokenExpirationDate")
                              ,"tokenUsed"=>new db_field("tokenUsed")
                              ,"tokenMaxUsed"=>new db_field("tokenMaxUsed")
                              ,"tokenActive"=>new db_field("tokenActive")
                              ,"tokenCreatedBy"=>new db_field("tokenCreatedBy")
                              ,"tokenCreatedDate"=>new db_field("tokenCreatedDate")
                              );

  }

  function set_hash($hash,$validate=true) {
    
    $validate and $extra = " AND tokenActive = 1";
    $validate and $extra.= " AND (tokenUsed < tokenMaxUsed OR tokenMaxUsed IS NULL OR tokenMaxUsed = 0)";
    $validate and $extra.= sprintf(" AND (tokenExpirationDate > '%s' OR tokenExpirationDate IS NULL)",date("Y-m-d H:i:s"));
    

    $q = sprintf("SELECT * FROM token 
                   WHERE tokenHash = '%s'
                  $extra
                 ",db_esc($hash));
    #echo "<br><br>".$q;
    $db = new db_alloc();
    $db->query($q);
    if ($db->next_record()) {
      $this->set_id($db->f("tokenID"));
      $this->select();
      return true;
    }
  }

  function execute($email) {

    if ($this->get_id()) {

      if ($this->get_value("tokenActionID")) {
        $tokenAction = new tokenAction;
        $tokenAction->set_id($this->get_value("tokenActionID"));    
        $tokenAction->select();
      }

      if ($this->get_value("tokenEntity")) {
        $class = $this->get_value("tokenEntity");
        $entity = new $class;
        if ($this->get_value("tokenEntityID")) {
          $entity->set_id($this->get_value("tokenEntityID"));
          $entity->select();
        }
        $method = $tokenAction->get_value("tokenActionMethod");
        $entity->{$method}($email);
      }

      $this->increment_tokenUsed(); 
    }
  }

  function increment_tokenUsed() {
    $q = sprintf("UPDATE token SET tokenUsed = coalesce(tokenUsed,0) + 1 WHERE tokenID = %d",$this->get_id());
    $db = new db_alloc();
    $db->query($q);
  }

  function get_hash_str() {
    list($usec, $sec) = explode(' ', microtime());
    $seed = $sec + ($usec * 100000);
    mt_srand($seed);
    $randval = mt_rand(1,99999999); // get a random 8 digit number
    $randval = sprintf("%-08d",$randval);
    $randval = base_convert($randval,10,36);
    return $randval;
  }

  function generate_hash() {
    // Make an eight character base 36 garbage fds3ys79 / also check that we haven't used this ID already
    $randval = $this->get_hash_str();
    while (strlen($randval) < 8 || $this->set_hash($randval,false)) {
      $randval.= $this->get_hash_str();
      $randval = substr($randval, -8);
    }
    return $randval;
  }

  function select_token_by_entity($entity,$entityID) {
    $q = sprintf("SELECT * FROM token WHERE tokenEntity = '%s' AND tokenEntityID = %d",$entity,$entityID);
    $db = new db_alloc();
    $db->query($q);
    if ($db->next_record()) {
      $this->set_id($db->f("tokenID"));
      $this->select();
      return true;
    }
  }



}



?>