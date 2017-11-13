<?php
namespace App\Views\Pages\Admin;
use App\Utils\Helpers;
?>

<br />
<br />
<br />
<br />
<br />
<div class="container" id="report-to-print">
	<h3>
		Relatório de lotes
	</h3>
	<hr class="my-12" />
	<button class="btn btn-primary btn-sm button-to-report-report-lot" role="button">
		<i class="fa fa-file-pdf-o" aria-hidden="true"></i>
		Download
	</button>
	<br />
    <br /><?php if($list): ?>
    <table class="table">
        <thead>
            <tr>
				<th class="columns-to-print" scope="col">#</th>
				<th class="columns-to-print" scope="col">Lote</th>
				<th class="columns-to-print" scope="col">Data de inicio</th>
				<th class="columns-to-print" scope="col">Data de fim</th>
				<th class="columns-to-print" scope="col">Disponibilizados</th>
                <th class="columns-to-print" scope="col">Vendidos</th>
				<th class="columns-to-print" scope="col">Preço da un.</th>
                <th class="columns-to-print" scope="col">Arrecadado</th>
            </tr>
        </thead>
        <tbody><?php foreach($list as $item): ?>
			<tr class="row-to-print">
				<td>
					<?= $item->id ?>
				</td>
				<td>
					<?= $item->number ?>
				</td>
				<td>
					<?= Helpers::date_format($item->start) ?>
				</td>
				<td>
					<?= Helpers::date_format($item->end) ?>
				</td>
				<td>
					<?= $item->amount ?>
				</td>
				<td>
					<?= $item->sold ?>
				</td>
				<td>					
					R$ <?= number_format((float)$item->valuation, 2, ',', ''); ?>
				</td>
				<td>					
					R$ <?= number_format((float)$item->price, 2, ',', ''); ?>
				</td>
			</tr><?php endforeach; ?>
        </tbody>
    </table><?php else: ?>
    <div class="alert alert-danger" role="alert">
        <strong>Ops!</strong> Não temos dados para mostrar.
    </div><?php endif ?>
</div>