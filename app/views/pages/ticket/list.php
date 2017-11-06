<?php
namespace App\Views\Pages\Ticket;
use App\Utils\Helpers;
use Pure\Utils\DynamicHtml;
use Pure\Utils\Res;
?>

<div class="container" style="margin-top: 100px;">
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		
		<p>Aqui você pode remover ou editar um ingressos. </p>
	</div>
	<br />
	<h2>Ingressos</h2>
	<br />

	
	<?php if(isset($list)): ?>
	<table class="table table-responsive">
		<thead>
			<tr>
				<th>Nome</th>
				<th>E-mail</th>
				<th>CPF</th>
				<th>Valor da venda</th>
				<th>Opções</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($list as $ticket): ?>
			<tr class="<?= $ticket->self ? 'table-success' : ''; ?>">
				<td>
					<?= $ticket->owner_name; ?>
				</td>

				<td>
					<?= $ticket->owner_email; ?>
				</td>

				<td>
					<?= Helpers::format_cpf($ticket->owner_cpf); ?>
				</td>
				<td>
					R$ <?= number_format((float)$ticket->price, 2, ',', ''); ?> 
				</td>
				<td>
					<a href="<?= DynamicHtml::link_to('ticket/update/' . $ticket->id) ?>" class="btn btn-primary btn-sm">
						Editar
						<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
					</a>
					<?php if(!$user->self): ?>
					<a href="<?= DynamicHtml::link_to('ticket/delete/' . $ticket->id) ?>" class="btn btn-danger btn-sm">
						Excluir
						<i class="fa fa-trash-o" aria-hidden="true"></i>
					</a>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>
	
</div>