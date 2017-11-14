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
		Lista de ingressos
	</h3>
	<hr class="my-12" />
	<button class="btn btn-primary btn-sm button-to-report-ticket-list" role="button">
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
				<th class="columns-to-print" scope="col">Nome</th>
				<th class="columns-to-print" scope="col">CPF</th>
				<th class="columns-to-print" scope="col">E-mail</th>
                <th class="columns-to-print" scope="col">Data da venda</th>
            </tr>
        </thead>
        <tbody><?php foreach($list as $item): ?>
			<tr class="row-to-print">
				<td>
					<?= $item->id ?>
				</td>
				<td>
					<?= $item->lot_number ?>
				</td>
				<td>
					<?= $item->owner_name ?>
				</td>
				<td>
					<?= Helpers::format_cpf($item->owner_cpf) ?>
				</td>
				<td>
					<?= $item->owner_email ?>
				</td>
				<td>
					<?= Helpers::date_format($item->created) ?>
				</td>
			</tr><?php endforeach; ?>
        </tbody>
    </table><?php else: ?>
    <div class="alert alert-danger" role="alert">
        <strong>Ops!</strong> Não temos dados para mostrar.
    </div><?php endif ?>
</div>