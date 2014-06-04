<?php


session_start();

require "../../jsonwrapper/jsonwrapper.php";
include "../../mongoconnection.php";
include "../../mysqlconnection.php";
include_once "../../Archive/functions.php";
//$itoken = safe($_GET["itoken"]);
if (isset($_SESSION["email"]) == false) {//check if user is login;
//    echo "<script>window.location='../Archive/index.php';</script>";
        //die();
}





echo'
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-us">
<head>
        <title>Process</title>
';
//css original:
//        <link rel="stylesheet" href="../../grid_js/css/jq.css" type="text/css" media="print, projection, screen" />

echo '       <link rel="stylesheet" href="../../Archive/controlpanel/css/styles.css" type="text/css" media="print, projection, screen" />

        <link rel="stylesheet" href="../../grid_js/themes/blue/style.css" type="text/css" media="print, projection, screen" />
<script src="../../Flot/jquery.js" type="text/javascript"></script>
        <script type="text/javascript" src="../../grid_js/jquery.tablesorter.min.js"></script>

        <script type="text/javascript" src="../../grid_js/js/chili/chili-1.8b.js"></script>
        <script type="text/javascript" src="../../grid_js/js/docs.js"></script>
        <script type="text/javascript">
        $(function() {          
                $("#tablesorter-demo").tablesorter({sortList:[[0,0],[2,1]], widgets: ["zebra"]});
                $("#options").tablesorter({sortList: [[0,0]], headers: { 3:{sorter: false}, 4:{sorter: false}}});
        });     
        </script>




<script type="text/javascript">
    $(document).ready(function(){

var v;
var cnt = 0;
var ts = 0;
var maxDisplaySize = 10;
 function getTable(){
   $.ajax({
	url: "dump_top.php",
	type: "get",
	dataType: "json",
     }) .done(function( msg ) {
//	cnt++;
	drawTable(msg);
});

}
		// Set up the control widget
function main(){
	getTable();
	setTimeout(main, 2000);
}

main();

function deleterow(tableID, amount) {
	var table = document.getElementById(tableID);
	var rowCount = table.rows.length;

	table.deleteRow(rowCount - amount);

}
function HandleDisplaySize(data){//remove rows from table to avoid it to grow forever.


	var table = document.getElementById("tablesorter-demo");
	var rowCount = table.rows.length-1;
	if(rowCount > maxDisplaySize){
		var amount = rowCount - maxDisplaySize;
		var index = 0;
alert(rowCount + " " + maxDisplaySize + " " + amount);
		while(index != amount){
			var del_index = rowCount - index;
			table.deleteRow(del_index);
			rowCount = del_index-1;
			index++;
		}


	}


/*	if(countDisplaySize == 0){
		countDisplaySize = data.length;}
	else{
		if(countDisplaySize >= maxDisplaySize){
			if(countDisplaySize > maxDisplaySize)
				deleterow("tablesorter-demo", countDisplaySize-maxDisplaySize);
			else
				deleterow("tablesorter-demo",data.length-1);
		}else{
			countDisplaySize = countDisplaySize + (data.length-1);
		}
	}*/

}
function drawTable(data) {
//http://jsfiddle.net/mjaric/sEwM6/
	if(data != null && data[data.length-1] != null){//check if no data is returned.


				drawRow(data);
	}
}


function convertTimestamp(timestamp) {
	//https://gist.github.com/kmaida/6045266
    	var d = new Date(timestamp * 1000), // Convert to milliseconds
        yyyy = d.getFullYear(),
        mm = ("0" + (d.getMonth() + 1)).slice(-2),  // Months are zero based. Add leading 0.
        dd = ("0" + d.getDate()).slice(-2),         // Add leading 0.
        hh = d.getHours(),
        h = hh,
        min = ("0" + d.getMinutes()).slice(-2),     // Add leading 0.
        ampm = "AM",
        time;
        sec = ("0" + d.getSeconds()).slice(-2);     // Add leading 0. 
    if (hh > 12) {
        h = hh - 12;
        ampm = "PM";
    } else if (hh == 0) {
        h = 12;
    }
     
    // ie: 2013-02-18, 8:35 AM 
    time = yyyy + "-" + mm + "-" + dd + ", " + h + ":" + min +":"+sec+ " " + ampm;
         
    return time;
}

';
echo 'function getInstanceAlias(itoken){';
$inst_query = mysql_query("SELECT * FROM instance WHERE email='".$_SESSION["email"]."'");
echo '
	var stack_itoken = [];
	var stack_alias = [];';
while ($inst= mysql_fetch_array($inst_query)) {

echo '	stack_itoken.push("'.$inst['instanceID'].'");';
echo '	stack_alias.push("'.$inst['Alias'].'");';
	
}

echo '
	return stack_alias[stack_itoken.indexOf(itoken)];}';

echo '

function defineColor(txt){

	if(txt == "Running")
		txt = "<p style=\"color:green\">"+txt+"</p>";
	else if(txt == "Stopped")
		txt = "<p style=\"color:red\">"+txt+"</p>";

	return txt
}


var rowAlternator = false;
function drawRow(rowData) {
	var rowStyle;
var tr;
var content="<tbody id=\"aaa\">";
    for(var i=0;i<rowData.length;i++){

//rowAlternator is used to control the style - commented here beucase is causing blink like effect
//		if(rowAlternator == false)//Used to alternate the CSS style for each row;
//			rowStyle="odd";
//		else
			rowStyle="even";
//		rowAlternator = !rowAlternator;

		var time = convertTimestamp(rowData[i].timestamp.sec);
		for(var j=0;j<rowData[i].process.plist.length;j++){
			content = content+"<tr class=\""+rowStyle+"\"> <td>"+time+"</td> <td><a href=\"../../Flot/index.php?itoken="+rowData[i].InstanceToken+"&interval=def\">"+ getInstanceAlias(rowData[i].InstanceToken) + "</a></td><td>"+rowData[i].process.plist[j].name+"</td><td>"+rowData[i].process.plist[j].pid+"</td><td>"+defineColor(rowData[i].process.plist[j].status)+"</td><td>"+rowData[i].process.plist[j].cpu+"</td><td>"+rowData[i].process.plist[j].mem+"</td><td>"+rowData[i].process.plist[j].time+"</td> </tr>";
		}
    }
	content = content+"</tbody>";
	tr = $(content);
	$("#aaa").replaceWith(tr);
}



});
</script>

';


  // execute query
  // retrieve all documents
//$criteria = array(
//"InstanceToken" => $itoken);



//  $cursor = $collection->find($criteria);
//$cursor->sort(array("time" => 1));
echo '</head>
<body class="bg">
<div style="padding: 10px;">
        <a name="Demo"></a>

	<table id="tablesorter-demo" class="tablesorter" border="0" cellpadding="0" cellspacing="1">
                <thead>
			<tr>
				<th>Time</th>
				<th>Alias</th>
				<th>Process</th>
				<th>PID</th>
				<th>Status</th>
				<th>CPU(%)</th>
				<th>Memory</th>
				<th>Time running</th>
			</tr>
                </thead>
		<tbody id="aaa">
<tr></tr>
		</tbody>


        </table>
</div>
<br><br>
<div style="position:fixed;
   left:0px;
   bottom:0px;
   height:30px;
   width:100%;
   background:black;">  <a href="../../index.php"><img s style="position: fixed; bottom: 3px; left: 10px;" src="../../pic/home.png" alt="Home"></a>
</div>

</body>
</html>';
?>
