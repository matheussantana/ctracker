<?
/******************************************************************************
 *
 * plot.php - get JSON via AJAX.
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
function getjsplot($type, $field, $placeholder, $updateinterval, $token, $interval, $elem, $host){

echo '

	<script type="text/javascript">

	$(function() {

var datasets = [];

		var data = [], totalPoints = 300;
';

echo 'var cnt =  {';

$count = 0;
foreach ($host as $value) {

	echo '"'.$value["instanceID"].'":0';
	if($count < count($host)-1)
		echo ',';
	$count++;
}
echo '	};
';

echo 'var res = {';

$count = 0;
foreach ($host as $value) {

        echo '"'.$value["instanceID"].'":[]';
        if($count < count($host)-1)
                echo ',';
	$count++;
}
echo '  };
';


echo 'var ts=  {';

$count = 0;
foreach ($host as $value) {

        echo '"'.$value["instanceID"].'":0';
        if($count < (count($host)-1))
                echo ',';
	$count++;
}


echo '};
		function getRandomData(host_index) {
		     var result = null;
		//     var scriptUrl = "http://localhost/test";
$updateFilter = $("input#filterby_'.$elem.'").val();
if($updateFilter == "true"){

			 $("input#filterby_'.$elem.'").val("false");
			cnt[host_index] = 1;
			res[host_index] = [];
			ts[host_index] = 0;

$type_ts = $("#parent_selection_'.$elem.'").find(":selected").text();
$freq_ts = $("#child_selection_'.$elem.'").find(":selected").text();


                        var base = "dump.php?load=";
                        var base2 = base.concat(cnt[host_index]).concat("&ts=");
                        var base3 = base2.concat(ts[host_index]);
                        var scriptUrl = base3.concat("&itoken="+host_index+"&type='.$type.'&field='.$field.'&interval=filter&type_ts="+$type_ts+"&freq_ts="+$freq_ts);
			


}else{
			var base = "dump.php?load=";
			var base2 = base.concat(cnt[host_index]).concat("&ts=");
			var base3 = base2.concat(ts[host_index]);
			var scriptUrl = base3.concat("&itoken="+host_index+"&type='.$type.'&field='.$field.'&interval='.$interval.'");
}
cnt[host_index]++;
		     $.ajax({
			url: scriptUrl,
			type: \'get\',
			dataType: \'html\',
			async: false,
			success: function(data) {
			    result = data;
			//alert(data);
			} 
		     });

			if($.trim(result) != "null" && $.trim(result) != "[0,0]"){//No data found on db.
					//mount the json string;
					var j = "[";
					j = j.concat(result);
					result  = j.concat("]");

					var tmp_array_res = JSON.parse(result);
					var index = 0;
					for (index = 0; index < tmp_array_res.length; ++index) {//push the new values to the res stack used by the plot engine.
						res[host_index].push(tmp_array_res[index]);
					}
					ts[host_index] = res[host_index][res[host_index].length-1][0];//stores timestamp from the last update.

					//If res[index] element of res is older the filter defined, then remove element.
					index = 0;
					var tmp_res = [];
					var f = $("input#filterts_'.$elem.'").val();

					if(cnt[host_index] > 2 && f != 0){
						for (index = 0; index < res[host_index].length; ++index){
							if(tmp_array_res[length][0] - res[host_index][index][0] < f){
								tmp_res.push(res[host_index][index]);
						}
					}
					res[host_index] = tmp_res;
					}

			     		return res[host_index];
				
			}else{
				return res[host_index];
			}

		}

		// Set up the control widget

		var updateInterval = 5000;
		$("#'.$updateinterval.'").val(updateInterval).change(function () {
			var v = $(this).val();
			if (v && !isNaN(+v)) {
				updateInterval = +v;
				if (updateInterval < 1) {
					updateInterval = 1;
				} else if (updateInterval > 2000) {
					updateInterval = 2000;
				}
				$(this).val("" + updateInterval);
			}
		});


function getDataset(){
';


$count = 0;
foreach ($host as $value) {

        echo '
	if ($("#'.$value['alias'].'_'.$elem.'").is(":checked"))
		var dt'.$count.'=getRandomData("'.$value["instanceID"].'");
	else
                var dt'.$count.'="[0,0]";


';
$count++;
}

	echo 'datasets = {';

	$count = 0;
	foreach ($host as $value) {
		echo '"'.$value['alias'].'": {
			label: "'.$value['alias'].'",
			data: dt'.$count.',
			color: '.$count.'
			}
';

		if($count < count($host)-1)
			echo ',';
		$count++;
	}
	echo '};';


echo '
//alert(JSON.stringify(datasets));
	//return datasets["serverA"];
var pilha = [];';



        $count = 0;
        foreach ($host as $value) {


		echo 'if ($("#'.$value['alias'].'_'.$elem.'").is(":checked"))
		        pilha.push(datasets["'.$value['alias'].'"]);
';

        }

echo '

	return pilha;
}

		var plot = $.plot("#'.$placeholder.'",  getDataset() , {
			series: {
				shadowSize: 0	// Drawing is faster without shadows
			},
            xaxis: {
                mode: "time",
		timezone: "browser",		// "browser" for local to the client or timezone for timezone-js
timeformat: "%Y/%m/%d %H:%M:%S",
                //minTickSize: [1, "month"],
                //min: (new Date(2013, 1, 1)).getTime(),
                //max: (new Date(2013, 1, 1)).getTime()
            }
,
//			yaxis: {
//				min: 0,
//				max: 100
//			},
//			xaxis: {
//				show: false
//			}
		});

		function update() {
			plot.setData(getDataset());



			// Since the axes don\'t change, we don\'t need to call plot.setupGrid() - chamei aqui pq eu os eixos devem ser desenhados dinamicamente.
			plot.setupGrid();
			plot.draw();

			setTimeout(update, updateInterval);
		}


		update();


		// Add the Flot version string to the footer

		//$("#footer").prepend("Flot " + $.plot.version + " &ndash; ");

	});

	</script>

<script language="javascript" type="text/javascript">  
$(document).ready(function(){

//let"s create arrays
var minutes = [
{display: "1", value: "1" },
{display: "2", value: "2" },
{display: "3", value: "3" },
{display: "4", value: "4" },
{display: "5", value: "5" },
{display: "6", value: "6" },
{display: "7", value: "7" },
{display: "8", value: "8" },
{display: "9", value: "9" },
{display: "10", value: "10" },
{display: "11", value: "11" },
{display: "12", value: "12" },
{display: "13", value: "13" },
{display: "14", value: "14" },
{display: "15", value: "15" },
{display: "16", value: "16" },
{display: "17", value: "17" },
{display: "18", value: "18" },
{display: "19", value: "19" },
{display: "20", value: "20" },
{display: "21", value: "21" },
{display: "22", value: "22" },
{display: "23", value: "23" },
{display: "24", value: "24" },
{display: "25", value: "25" },
{display: "26", value: "26" },
{display: "27", value: "27" },
{display: "28", value: "28" },
{display: "29", value: "29" },
{display: "30", value: "30" },
{display: "31", value: "31" },
{display: "32", value: "32" },
{display: "33", value: "33" },
{display: "34", value: "34" },
{display: "35", value: "35" },
{display: "36", value: "36" },
{display: "37", value: "37" },
{display: "38", value: "38" },
{display: "39", value: "39" },
{display: "40", value: "40" },
{display: "41", value: "41" },
{display: "42", value: "42" },
{display: "43", value: "43" },
{display: "44", value: "44" },
{display: "45", value: "45" },
{display: "46", value: "46" },
{display: "47", value: "47" },
{display: "48", value: "48" },
{display: "49", value: "49" },
{display: "50", value: "50" },
{display: "51", value: "51" },
{display: "52", value: "52" },
{display: "53", value: "53" },
{display: "54", value: "54" },
{display: "55", value: "55" },
{display: "56", value: "56" },
{display: "57", value: "57" },
{display: "58", value: "58" },
{display: "59", value: "59" },
{display: "60", value: "60" }
];
   
var hours = [
{display: "1", value: "1" },
{display: "2", value: "2" },
{display: "3", value: "3" },
{display: "4", value: "4" },
{display: "5", value: "5" },
{display: "6", value: "6" },
{display: "7", value: "7" },
{display: "8", value: "8" },
{display: "9", value: "9" },
{display: "10", value: "10" },
{display: "11", value: "11" },
{display: "12", value: "12" },
{display: "13", value: "13" },
{display: "14", value: "14" },
{display: "15", value: "15" },
{display: "16", value: "16" },
{display: "17", value: "17" },
{display: "18", value: "18" },
{display: "19", value: "19" },
{display: "20", value: "20" },
{display: "21", value: "21" },
{display: "22", value: "22" },
{display: "23", value: "23" },
{display: "24", value: "24" }]
;
   
var days = [

{display: "1", value: "1" },
{display: "2", value: "2" },
{display: "3", value: "3" },
{display: "4", value: "4" },
{display: "5", value: "5" },
{display: "6", value: "6" },
{display: "7", value: "7" },
{display: "8", value: "8" },
{display: "9", value: "9" },
{display: "10", value: "10" },
{display: "11", value: "11" },
{display: "12", value: "12" },
{display: "13", value: "13" },
{display: "14", value: "14" },
{display: "15", value: "15" },
{display: "16", value: "16" },
{display: "17", value: "17" },
{display: "18", value: "18" },
{display: "19", value: "19" },
{display: "20", value: "20" },
{display: "21", value: "21" },
{display: "22", value: "22" },
{display: "23", value: "23" },
{display: "24", value: "24" },
{display: "25", value: "25" },
{display: "26", value: "26" },
{display: "27", value: "27" },
{display: "28", value: "28" },
{display: "29", value: "29" },
{display: "30", value: "30" },
{display: "31", value: "31" }
];

//If parent option is changed
$("#parent_selection_'.$elem.'").change(function() {
        var parent = $(this).val(); //get option value from parent
       
        switch(parent){ //using switch compare selected option and populate child
              case "mins":
                list_'.$elem.'(minutes);
		filter_rules_'.$elem.'();
                break;
              case "hours":
                list_'.$elem.'(hours);
		filter_rules_'.$elem.'();
                break;             
              case "days":
                list_'.$elem.'(days);
		filter_rules_'.$elem.'();
                break; 
            default: //default child option is blank
		$("#child_selection_'.$elem.'").prop("disabled", "disabled");
                $("#child_selection_'.$elem.'").html("");  
	        $("input#filterts_'.$elem.'").val(0);
                break;
           }

});

function filter_rules_'.$elem.'(){

	$type = $("#parent_selection_'.$elem.'").find(":selected").text();
	$freq = $("#child_selection_'.$elem.'").find(":selected").text();

	var filter = 0;
	switch($type){
		case "Minutes":
			$filter = $freq*60*1000;
			break;
		case "Hours":
			$filter = $freq*3600*1000;
			break;
		case "Days":
			$filter = $freq*86400*1000;
			break;
		default:
			$filter = 5*60*1000;
			break;
	}
	$("input#filterby_'.$elem.'").val("true");
	$("input#filterts_'.$elem.'").val($filter);


}
//function to populate child select box
function list_'.$elem.'(array_list)
{
    $("#child_selection_'.$elem.'").prop("disabled", false);
    $("#child_selection_'.$elem.'").html(""); //reset child options
    $(array_list).each(function (i) { //populate child options
        $("#child_selection_'.$elem.'").append("<option value=\""+array_list[i].value+"\">"+array_list[i].display+"</option>");
    });
}

});
</script>

<script type="text/javascript">
    function updateFilter_'.$elem.'() {
       $("input#filterby_'.$elem.'").val("true");
    }
</script>
';

}


function getFilter($elem, $host, $itoken){
echo '                <div>
                <table style="width:300px">
                <tr>
                        <td>
<select name="parent_selection_'.$elem.'" id="parent_selection_'.$elem.'">
    <option value="">-- Please Select --</option>
    <option value="mins">Minutes</option>
    <option value="hours">Hours</option>
    <option value="days">Days</option>
</select>
                       </td>
                        <td>
<select name="child_selection_'.$elem.'" id="child_selection_'.$elem.'" disabled=true onChange="updateFilter_'.$elem.'()">
</select>
<input type=hidden name="filterby_'.$elem.'" id="filterby_'.$elem.'" value="false">
<input type=hidden name="filterts_'.$elem.'" id="filterts_'.$elem.'" value="0">
</td>
<td>
<a href="#" data-reveal-id="myModal_'.$elem.'"> Server\'s Host </a>
</td>
</table>
';
//http://zurb.com/playground/reveal-modal-plugin

echo '		<div id="myModal_'.$elem.'" class="reveal-modal">
			<h1>Server\'s Host</h1>
			<!--<p>This is a default modal in all its glory, but any of the styles here can easily be changed in the CSS.</p>-->
			<table>';

        $count = 0;
        foreach ($host as $value) {
				echo '<tr><td><input id="'.$value['alias'].'_'.$elem.'" type="checkbox"';
				if($value['instanceID'] == $itoken)
					echo ' checked="checked"';
				echo ' name="'.$value['alias'].'_'.$elem.'">'.$value['alias'].'</input></td></tr><tr>';
	}
echo '
		</table>
		<a class="close-reveal-modal">&#215;</a>
		</div>

                </tr>
                </table>
                </div>';

}
