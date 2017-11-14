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
    <a type="button" href="<?= DynamicHtml::link_to('ticket/list') ?>" class="btn btn-primary">Voltar</a>
	<?php else: ?>
	<div class="alert alert-warning" role="alert">
        <h4 class="alert-heading">Excluir ingresso</h4>
		<p>
			<strong>Cuidado!</strong> Você tem certeza que deseja excluir o ingresso de  "<?= $ticket->owner_name ?>"?
		</p>
		<form action="<?= DynamicHtml::link_to('ticket/delete/' . $ticket->id) ?>" method="POST">
			<input type="number" value="<?= $ticket->id ?>" name="id_to_delete" hidden/>
			<div class="form-group" style="text-align: right">
				<a type="button" href="<?= DynamicHtml::link_to('ticket/list') ?>" class="btn btn-primary">Voltar</a>
				<button type="submit" class="btn btn-danger" style="cursor: pointer;">Excluir</button>
			</div>
		</form>
	</div>
	<?php endif; ?>
</div>