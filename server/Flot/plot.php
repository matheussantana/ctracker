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
function getjsplot($type, $field, $placeholder, $updateinterval, $token){

echo '

	<script type="text/javascript">

	$(function() {

		// We use an inline data source in the example, usually data would
		// be fetched from a server

		var data = [], totalPoints = 300;

		function getRandomData() {

		     var result = null;
		//     var scriptUrl = "http://localhost/test";
			var scriptUrl = "dump.php?itoken='.$token.'&type='.$type.'&field='.$field.'";

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
			//alert(result);
			var res = JSON.parse(result);
		     	return res;

		}

		// Set up the control widget

		var updateInterval = 30;
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

		var plot = $.plot("#'.$placeholder.'", [ getRandomData() ], {
			series: {
				shadowSize: 0	// Drawing is faster without shadows
			},
            xaxis: {
                mode: "time",
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

			plot.setData([getRandomData()]);

			// Since the axes don\'t change, we don\'t need to call plot.setupGrid() - chamei aqui pq eu os eixos devem ser desenhados dinamicamente.
			plot.setupGrid();
			plot.draw();
			setTimeout(update, updateInterval);
		}

		update();

		// Add the Flot version string to the footer

		//$("#footer").prepend("Flot " + $.plot.version + " &ndash; ");
	});

	</script>';

}
