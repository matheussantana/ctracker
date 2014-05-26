<?
/******************************************************************************
 *
 * message.php - Display simple messages to user's.
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

$msg = formatData($_GET['msg']);

if(strcmp($msg, "error-login") == 0){ // login error

    echo "<label>E-mail or password incorrect. <br>Try again or sign-up.<img alt='E-mail or password incorrect' style='vertical-align:middle;' src='../img/sad.png'/></label>";

}
elseif(strcmp($msg, "error-page-not-found") == 0){ // page not find

    echo "<p>The page cannot be found.</p>";

}
elseif(strcmp($msg, "error-user-not-found") == 0){ // user not find

    echo "<p>The user cannot be found.</p>";

}
elseif(strcmp($msg, "error-add") == 0){ // cannot add

    echo "<p>Cannot add.</p>";

}
elseif(strcmp($msg, "error-edit") == 0){ // cannot add

    echo "<p>Cannot edit.</p>";

}
elseif(strcmp($msg, "error-array") == 0){ // cannot add
    $error = formatData(decodeArray($_GET['ar']));
    echo "<h3>Error(s):</h3>
        <ul>";
        foreach ($error as $er) {
            echo "<li>" . $er . "</li>";
        }
    echo "</ul>";
}
elseif(strcmp($msg, "confirm-edit") == 0){ // confirm edit

    echo "<p>Edition succeed.</p>";

}
elseif(strcmp($msg, "confirm-add") == 0){ // confirm add

    echo "<p>Add succeed!<img alt='Add succeed!' style='vertical-align:middle;' src='../img/act-up.png'/></p>";

}
elseif(strcmp($msg, "confirm-add-docs") == 0){ // confirm add

    echo "<p>Add succeed! Your file will be available for reader the soon as possible.</p>";

}

elseif(strcmp($msg, "error-delete") == 0){ // cannot delete

    echo "<p>Cannot delete! <img alt='Cannot delete' style='vertical-align:middle;' src='../img/bad.png'/></p>";

}
elseif(strcmp($msg, "error-query") == 0){ // cannot query

    echo "<p>Error.</p>";

}
elseif(strcmp($msg, "confirm-delete") == 0){ // confirm delete

    echo "<p>Delete succeed! <img alt='Add succeed!' style='vertical-align:middle;' src='../img/good.png'/></p>";

}
elseif(strcmp($msg, "confirm-friend-request") == 0){ // confirm friend request

    echo "<p>Your request was send with success.</p>";

}

echo "<script type=\"text/JavaScript\">
<!--
setTimeout(\"location.href = 'index.php';\",3500);
-->
</script>";

?>
