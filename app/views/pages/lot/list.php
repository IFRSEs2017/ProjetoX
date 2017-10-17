<?php
namespace App\Views\Pages\Lot;
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
		<p>Aqui você pode adicionar, remover ou editar lotes de ingressos. </p>
	</div>
	<br />
	<h2>Lotes e ingressos</h2>
	<br />
	<div class="row">
		<a href="<?= DynamicHtml::link_to('ticket/insert') ?>" class="btn btn-primary">
			<i class="fa fa-plus" aria-hidden="true"></i>
			Novo lote
		</a>
	</div>
	<br />
	<div class="container">
		<div class="row">
			<?php foreach($list as $item): ?>
			<div class="col-md-4">
				<div class="jumbotron">
					<h1 class="display-4">Lotes e ingressos</h1>
					<p class="lead">Crie e edite lotes de ingressos para sua festa.</p>
					<hr class="my-4" />
					<p>
						Cadastre os
						<b>lotes de ingresso</b>da sua festa e defina o valor, data e preço.
					</p>
					<p class="lead">
						<a class="btn btn-success btn-lg" href="<?= DynamicHtml::link_to('ticket/list'); ?>" role="button">Começar</a>
					</p>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>