<?php
namespace App\Views\Pages\Admin;
use App\Utils\Helpers;
?>

<br />
<br />
<br />
<br />
<div class="row">
	<div class="col-md">
		<div id="last_mount_ticket_count" style="height: 350px"> </div>
	</div>
    <div class="col-md">
        <div id="last_mount_ticket_fature" style="height: 350px"></div>
    </div>
</div>

<script>
var dataFromLastMonthCount = [
    {
        x: <?= Helpers::keys_to_js_array($last_month_count); ?>,
        y: <?= Helpers::values_to_js_array($last_month_count); ?>,
        type: 'scatter',
		line: {
			color: 'rgb(55, 128, 191)',
			width: 3
		}
    }
];

var layoutFromLastMonthCount = {
	yaxis: {title: 'Número de ingressos'},
    title: 'Ingressos vendidos no último mês'
};

Plotly.newPlot(
	'last_mount_ticket_count',
	dataFromLastMonthCount,
	layoutFromLastMonthCount
);

var dataFromLastMonthFature = [
    {
        x: <?= Helpers::keys_to_js_array($last_month_fature); ?>,
        y: <?= Helpers::values_to_js_array($last_month_fature); ?>,
        type: 'scatter',
		line: {
			color: 'rgb(219, 64, 82)',
			width: 3
		}
    }
];

var layoutFromLastMonthFature = {
	yaxis: {title: 'Valor arrecadado'},
    title: 'Valor arrecadado no último mês'
};

Plotly.newPlot(
	'last_mount_ticket_fature',
	dataFromLastMonthFature,
	layoutFromLastMonthFature
);
</script>