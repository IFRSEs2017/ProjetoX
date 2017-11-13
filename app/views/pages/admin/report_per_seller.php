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
		Relatório de vendas por vendedor
	</h3>
	<hr class="my-12" />
	<button class="btn btn-primary btn-sm button-to-report-report-seller" role="button">
		<i class="fa fa-file-pdf-o" aria-hidden="true"></i>
		Download
	</button>
	<br />
    <br /><?php if($list): ?>
    <table class="table">
        <thead>
            <tr>
				<th class="columns-to-print" scope="col">#</th>
				<th class="columns-to-print" scope="col">Nome</th>
				<th class="columns-to-print" scope="col">E-mail</th>
				<th class="columns-to-print" scope="col">Ingressos vendidos</th>
                <th class="columns-to-print" scope="col">Valor arrecadado</th>
            </tr>
        </thead>
        <tbody><?php foreach($list as $item): ?>
			<tr class="row-to-print">
				<td>
					<?= $item->id ?>
				</td>
				<td>
					<?= $item->name ?>
				</td>
				<td>
					<?= $item->email ?>
				</td>
				<td>
					<?= $item->count ?>
				</td>
				<td>					
					R$ <?= number_format((float)$item->sold, 2, ',', ''); ?>
				</td>
			</tr><?php endforeach; ?>
        </tbody>
    </table><?php else: ?>
    <div class="alert alert-danger" role="alert">
        <strong>Ops!</strong> Não temos dados para mostrar.
    </div><?php endif ?>
</div>