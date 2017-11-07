<?php
namespace App\Views\Pages\Admin;
use App\Utils\Helpers;
?>

<br />
<br />
<br />
<br />
<div class="container">
	<h3>Informações</h3>
	<hr class="my-12" />
	<div class="container">
		<div class="row">
			<div class="card" style="width: 20rem; margin-right: 10px;">
				<div class="card-body">
					<h4 class="card-title">Total</h4>
					<h6 class="card-subtitle mb-2 text-muted">Valor total das vendas</h6>
					<p class="card-text">Soma dos valores de todos os ingressos vendidos</p>
					<h1>
						<span class="badge badge-dark">
							R$ <?= number_format($total_sold, 2, ',', '.') ?>
						</span>
					</h1>
				</div>
			</div>
			<!--
            <div class="card" style="width: 20rem;">
                <div class="card-body">
                    <h4 class="card-title">Total</h4>
                    <h6 class="card-subtitle mb-2 text-muted">Valor total das vendas</h6>
                    <p class="card-text">Soma dos valores de todos os ingressos vendidos</p>
                    <h1>
                        <span class="badge badge-dark">
                            R$ <?= number_format($total_sold, 2, ',', '.') ?>
                        </span>
                    </h1>
                </div>
            </div>
			-->
		</div>
	</div>
	<br />
	<h3>Gráficos do mês</h3>
	<hr class="my-12" />
	<div class="row">
		<div class="col-md">
			<div id="last_mount_ticket_count"></div>
		</div>
		<div class="col-md">
			<div id="last_mount_ticket_fature"></div>
		</div>
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