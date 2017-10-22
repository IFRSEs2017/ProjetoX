<?php
namespace App\Views\Pages\Outlet;
use App\Utils\Helpers;
use Pure\Utils\DynamicHtml;
use Pure\Utils\Res;
?>

<div class="container" style="margin-top: 100px;">
	<?php if(isset($message)): ?>
	<div class="alert alert-danger" role="alert">
		<strong>Ops!</strong> <?= $message ?>
	</div>
    <a type="button" href="<?= DynamicHtml::link_to('lot/list') ?>" class="btn btn-primary">Voltar</a>
	<?php else: ?>
	<div class="alert alert-warning" role="alert">
        <h4 class="alert-heading">Desativar Lote</h4>
		<p>
			<strong>Cuidado!</strong> Você tem certeza que deseja desativar este Lote?
		</p>
		<form action="<?= DynamicHtml::link_to('lot/delete/' . $lot->id) ?>" method="POST">
			<input type="number" value="<?= $lot->id ?>" name="id_to_delete" hidden/>
			<div class="form-group" style="text-align: right">
				<a type="button" href="<?= DynamicHtml::link_to('lot/list') ?>" class="btn btn-primary">Voltar</a>
				<button type="submit" class="btn btn-danger" style="cursor: pointer;">Desativar</button>
			</div>
		</form>
	</div>
	<?php endif; ?>
</div>