<?php
namespace App\Views\Pages\Outlet;
use App\Utils\Helpers;
use Pure\Utils\DynamicHtml;
use Pure\Utils\Res;
?>

<div class="container" style="margin-top: 100px;">
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<h4 class="alert-heading">
			Olá, <?= $user_name ?>!
		</h4>
		<p>Aqui você pode adicionar, remover ou atualizar pontos de vendas e usuários. </p>
	</div>
	<br />
	<h2>Pontos de vendas e usuários</h2>
	<br />
	<div class="row">
		<a href="<?= DynamicHtml::link_to('outlet/insert') ?>" class="btn btn-primary">
			<i class="fa fa-plus" aria-hidden="true"></i>
			Novo ponto de venda
		</a>
	</div>
	<br />
	<div class="row">
		<?php if(isset($list)): ?>
		<table class="table table-responsive">
			<thead>
				<tr>
					<th>Nome</th>
					<th>E-mail</th>
					<th>CPF</th><?php /*
					<th>Tipo</th> */?>
					<th>Opções</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($list as $user): ?>
				<tr class="<?= $user->self ? 'table-success' : ''; ?>">
					<td>
						<?= $user->name; ?>
					</td>
					<td>
						<?= $user->email; ?>
					</td>
					<?php /*<td>
						<?= $user->cpf; ?>
					</td>*/?>

					<td>
						<?php if($user->is_admin): ?>
						<?= 'Administrador' ?>
						<?php else: ?>
						<?= 'Ponto de venda' ?>
						<?php endif; ?>
					</td>
					<td>
						<a href="<?= DynamicHtml::link_to('outlet/update/' . $user->id) ?>" class="btn btn-primary btn-sm">
							Editar
							<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
						</a>
						<?php if(!$user->self): ?>
						<a href="<?= DynamicHtml::link_to('outlet/delete/' . $user->id) ?>" class="btn btn-danger btn-sm">
							Desativar
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
</div>