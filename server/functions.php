<?php
/******************************************************************************
 *
 * functions.php - Library with general purpose functions.
 *
 * Program: ctracker
 * License: GPL
 *
 * First Written:   2012
 * Copyright (C) 2012-2013 - Author: Matheus SantAna Lima <matheusslima@yahoo.com.br>
 *
 * Description:
 *
 * License:
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.

 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.

 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *****************************************************************************/

function verifyUser($id_user, $type) {

    $query = mysql_query("SELECT * FROM $type WHERE id_$type='$id_user'");

    if (mysql_num_rows($query) == 1)
        return mysql_fetch_array($query);
    else
        return false;
}

function validatesAsInt($number)
{
    $number = filter_var($number, FILTER_VALIDATE_INT);
    return ($number !== FALSE);
}

function getAlias($itoken) {

	$itoken = safe($itoken);

    $query = mysql_query("SELECT Alias FROM instance WHERE instanceID='$itoken'");

    if (mysql_num_rows($query) == 1){
        mysql_fetch_array($query);
	return $query['Alias'];}
    else
        return false;
}

function formatData($string) {

    $string = str_replace('"', "\"", $string); // replace " with \"
    $string = str_replace("'", "\'", $string); // replace ' with \'

    return $string;
}

function formatNewLine($string) {

    $string = preg_replace("/(\\r)?\\n/i", "<br />", $string);

    return $string;
}

function formatDate($date) {

    $date = str_replace("-", "/", $date);
    $date = substr($date, 0, 10) . " at " . substr($date, 11, 5);

    return $date;
}

function stringToLink($string){

    $string = preg_replace("([[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/])","<a href=\"\\0\" rel=\"nofollow\" target=\"_blank\">\\0</a>", $string);

    return $string;

}

function encrypt($string)
{
    for($i = 0; $i <= 3; $i++)
        $string = base64_encode ($string);
    return $string;
}

function decrypt($string)
{
    for($i = 0; $i <= 3; $i++)
        $string = base64_decode ($string);
    return $string;
}

function encodeArray($string)
{
    return base64_encode(serialize($string));
}

function decodeArray($string)
{
    return unserialize(base64_decode($string));
}

function arraySliceAssoc ($array, $key, $length, $preserve_keys = true)
{
   $offset = array_search($key, array_keys($array));

   if (is_string($length))
      $length = array_search($length, array_keys($array)) - $offset;

   return array_slice($array, $offset, $length, $preserve_keys);
}

function inMultiarray($elem, $array){
    foreach($array as $item){
        if($item == $elem){
            return true;
            exit;
        }elseif(is_array($item)){
            if(inMultiarray($elem, $item)){
                return true;
                exit;
            }
        }
    }
    return false;
}

function hasPrivilege($operation, $type, $id_user_type){
    $query = mysql_query("SELECT privileges FROM user_type WHERE id_user_type='$id_user_type'");
    $result = decodeArray(mysql_result($query, 0));
    if(inMultiarray($type, arraySliceAssoc($result, $operation, 1, true)))
        return true;
    else
        return false;
}

function just_clean($string)

{

// Replace other special chars

$specialCharacters = array(

'#' => '',

'$' => '',

'%' => '',

'&' => '',

'@' => '',

'€' => '',

'+' => '',

'=' => '',

'§' => '',

'\\' => '',

'/' => '',

);

 

while (list($character, $replacement) = each($specialCharacters)) {

$string = str_replace($character, '-' . $replacement . '-', $string);

}

 

$string = strtr($string,

"ÀÁÂÃÄÅ� áâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ",

"AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn"

);

 

// Remove all remaining other unknown characters

//$string = preg_replace('/[^a-zA-Z0-9\-]/', ' ', $string);

//$string = preg_replace('/^[\-]+/', '', $string);

//$string = preg_replace('/[\-]+$/', '', $string);

//$string = preg_replace('/[\-]{2,}/', ' ', $string);

 
$string = preg_replace('/\s/', '_', $string);
return $string;

}

function safe($value){
   return mysql_real_escape_string($value);
} 
?>
