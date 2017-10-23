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
	<a href="<?= DynamicHtml::link_to('lot/insert') ?>" class="btn btn-primary">
		<i class="fa fa-plus" aria-hidden="true"></i>
		Novo lote
	</a>
	<br />
	<hr class="my-12" />
	<div class="row">
		<?php for($i = 0; $i < count($list); $i++): ?>
		<div class="col-md-4">
			<div class="jumbotron">
				<h1 class="display-4">
					<?= $i + 1 ?>º lote
				</h1>
				<p class="lead">
					Valor do ingresso:
					<span class="badge badge-secondary">
						R$ <?= number_format((float)$list[$i]->valuation, 2, ',', ''); ?>
					</span>
					<br />Total de ingressos:
					<span class="badge badge-secondary">
						<?= $list[$i]->amount ?> ingressos
					</span>
                    <br />
					<span class="badge badge-dark">
						<?= $list[$i]->remain ?> ingressos restantes
					</span>
				</p>
				<hr class="my-4" />
				<p class="lead">
					Data de venda:
					<br /><?= Helpers::date_format($list[$i]->start) ?> a <?= Helpers::date_format($list[$i]->end) ?>
				</p>
				<p class="lead">
					<a class="btn btn-primary btn-sm" href="<?= DynamicHtml::link_to('lot/update/' . $list[$i]->id); ?>" role="button">
						<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
						Editar
					</a>
					<?php if ($list[$i]->amount == $list[$i]->remain): ?>
						<a class="btn btn-danger btn-sm" href="<?= DynamicHtml::link_to('lot/delete/' . $list[$i]->id); ?>" role="button">
							<i class="fa fa-trash-o" aria-hidden="true"></i>
							Excluir
						</a>
					<?php else: ?>
						<a class="btn btn-danger btn-sm disabled" href="<?= DynamicHtml::link_to('lot/delete/' . $list[$i]->id); ?>" role="button">
							<i class="fa fa-trash-o" aria-hidden="true"></i>
							Excluir
						</a>
						<p style="color: red;">
							O lote já começou a ser vedido.
							<br /> 
							Não é mais possível excluí-lo.
						</p>
					<?php endif; ?>
				</p>
			</div>
		</div><?php endfor; ?>
	</div>
</div>