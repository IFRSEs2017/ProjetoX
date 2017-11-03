<?php
namespace App\Views\Pages\Admin;
use Pure\Utils\DynamicHtml;
?>
<div class="container" style="margin-top: 90px;">
	<div class="row">
		<div class="col-md">
			<div class="jumbotron">
				<h1 class="display-4">Pontos de vendas </h1>
				<p class="lead">Cadastre pontos de vendas que poderão vender ingressos.</p>
				<hr class="my-4" />
				<p> Você pode inserir, editar e excluir os <b>pontos de vendas</b> que distribuirão os ingressos ou <b>administradores</b> para o sistema.</p>
				<p class="lead">
					<a class="btn btn-success btn-lg" href="<?= DynamicHtml::link_to('outlet/list'); ?>" role="button">Começar</a>
				</p>
			</div>
		</div>
		<div class="col-md">
			<div class="jumbotron">
				<h1 class="display-4">Lotes e ingressos</h1>
				<p class="lead">Crie e edite lotes de ingressos para sua festa.</p>
				<hr class="my-4" />
				<p> Cadastre os <b>lotes de ingresso</b> da sua festa e defina o valor, data e preço. </p>
				<p class="lead">
					<a class="btn btn-success btn-lg" href="<?= DynamicHtml::link_to('lot/list'); ?>" role="button">Começar</a>
				</p>
				<p>Alterar dados de ingressos vendidos.</p>
				<p class="lead">
					<a class="btn btn-success btn-lg" href="<?= DynamicHtml::link_to('ticket/list'); ?>" role="button">Editar</a>
				</p>
			</div>
		</div>
		<div class="col-md">
			<div class="jumbotron">
				<h1 class="display-4">Relatórios</h1>
				<p class="lead">Gere relatórios de vendas e controle os ingressos da sua festa.</p>
				<hr class="my-4" />
				<p> Essa funcionalidade será liberada em breve.</p>
				<p class="lead">
					<a class="btn btn-secondary btn-lg" href="#" style="pointer-events: none; cursor: default;"
						role="button">
						Em breve
					</a>
				</p>
			</div>
		</div>
	</div>
</div>
