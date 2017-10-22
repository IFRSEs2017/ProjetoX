<?php
namespace App\Views\Pages\Admin;
use Pure\Utils\DynamicHtml;
?>

<div class="container" style="margin-top: 90px;">
	<div class="row">
		<div class="col-md">
			<div class="jumbotron">
				<h1 class="display-4">Venda de ingressos</h1>
				<p class="lead">Venda de ingressos para a festa.</p>
				<hr class="my-4" />
				<p>Aqui você pode gerenciar a venda de ingressos no seu ponto de venda.</p>
				<p class="lead">
					<a class="btn btn-success btn-lg" href="<?= DynamicHtml::link_to('seller/sell'); ?>" role="button">
						Vender
					</a>
				</p>
			</div>
		</div>
	</div>
</div>