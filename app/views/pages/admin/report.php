<?php
namespace App\Views\Pages\Admin;
use App\Utils\Helpers;
use Pure\Utils\DynamicHtml;
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
			<div class="card" style="width: 20rem; margin: 20px;">
				<div class="card-body">
					<h4 class="card-title">Lista de ingressos</h4>
					<h6 class="card-subtitle mb-2 text-muted">Lista de convivados da festa</h6>
					<p class="card-text">Gera uma lista que poderá ser utilizada no dia da festa</p>
					<p class="lead">
						<a class="btn btn-success btn-lg" href="<?= DynamicHtml::link_to('admin/list_ticket'); ?>" role="button">Gerar lista</a>
					</p>
				</div>
			</div>
			<div class="card" style="width: 20rem; margin: 20px;">
				<div class="card-body">
					<h4 class="card-title">Receita</h4>
					<h6 class="card-subtitle mb-2 text-muted">Valor total das vendas</h6>
					<p class="card-text">Soma dos valores de todos os ingressos vendidos</p>
					<h1>
						<span class="badge badge-dark">
							R$ <?= number_format($total_sold, 2, ',', '.') ?>
						</span>
					</h1>
				</div>
			</div>
			<div class="card" style="width: 20rem;  margin: 20px;">
				<div class="card-body">
					<h4 class="card-title">Vendas</h4>
					<h6 class="card-subtitle mb-2 text-muted">Total de venda de ingressos</h6>
					<p class="card-text">
						Ao todo
						<br />foram venvidos:
					</p>
					<h1>
						<span class="badge badge-dark">
							<?= $total_count ?> ingressos
						</span>
					</h1>
				</div>
			</div>
		</div>
	</div>
	<br />
	<h3>Relatórios</h3>
	<hr class="my-12" />
	<div class="container">
		<div class="row">
			<div class="card" style="width: 20rem; margin: 20px;">
				<div class="card-body">
					<h4 class="card-title">Vendas por dia</h4>
					<h6 class="card-subtitle mb-2 text-muted">Relatório de vendas por dia</h6>
					<p class="card-text">Gera relatório de vendas com listagem por dia</p>
					<p class="lead">
						<a class="btn btn-primary btn-lg" href="<?= DynamicHtml::link_to('admin/report_per_day'); ?>" role="button">Gerar relatório</a>
					</p>

				</div>
			</div>
			<div class="card" style="width: 20rem; margin: 20px;">
				<div class="card-body">
					<h4 class="card-title">Vendas por lote</h4>
					<h6 class="card-subtitle mb-2 text-muted">Relatório de vendas por lote</h6>
					<p class="card-text">Gera relatório de vendas com listagem por lote</p>
					<p class="lead">
						<a class="btn btn-primary btn-lg" href="<?= DynamicHtml::link_to('admin/report_per_lot'); ?>" role="button">Gerar relatório</a>
					</p>

				</div>
			</div>
			<div class="card" style="width: 20rem; margin: 20px;">
				<div class="card-body">
					<h4 class="card-title">Vendas por vendedor</h4>
					<h6 class="card-subtitle mb-2 text-muted">Relatório de vendas por vendedor</h6>
					<p class="card-text">Gera relatório de vendas com listagem por vendedor</p>
					<p class="lead">
						<a class="btn btn-primary btn-lg" href="<?= DynamicHtml::link_to('admin/report_per_seller'); ?>" role="button">Gerar relatório</a>
					</p>

				</div>
			</div>
		</div>
	</div>
	<br />
	<h3>Gráficos do mês</h3>
	<hr class="my-12" />
	<div class="row" style=" margin: 20px;">
		<div class="col-md">
			<div id="last_mount_ticket_count"></div>
		</div>
		<div class="col-md">
			<div id="last_mount_ticket_fature"></div>
		</div>
	</div>
	<br />
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