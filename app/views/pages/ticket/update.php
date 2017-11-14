<?php
namespace App\Views\Pages\Admin;
use App\Utils\Helpers;
use Pure\Utils\DynamicHtml;
use Pure\Utils\Res;
?>
<br /> 
<br />
<br />

<div>
	<?php if(isset($message)): ?>
	<div <?= $class ?> role="alert">
		<strong>
			<?= $title ?>
		</strong><?= $message ?>
	</div>
	<?php endif; ?>
</div>

<?php if(isset($errors)): ?>
<?php foreach($errors as $value): ?>
<div>
	<div <?= $class ?> role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<strong>
			<?= $title ?>
		</strong><?= $value  ?>
		<br />
	</div>
</div>
<?php endforeach; ?>
<?php endif; ?>


<div class='container'>
	<br />
	<h2>Atualizar ingresso</h2>
	<br />
	<form action="<?= DynamicHtml::link_to('ticket/update/' . $ticket->id) ?>" method="POST">
		<div class="form-group">
			<input type="text" class="form-control" name="ticket_id" placeholder="" value="<?= $ticket->id ?>" readonly hidden/ />
		</div>

		<div class="form-group">
			<label for="owner_name">
				Nome
			</label>
			<input type="text" class="form-control" name="owner_name" placeholder="" maxlength="255" value="<?= $ticket->owner_name ?>" />
		</div>

		<div class="form-group">
			<label for="owner_email">
				E-mail
			</label>
			<input type="text" class="form-control" name="owner_email" placeholder="" maxlength="255" value="<?= $ticket->owner_email ?>" />
		</div>

		<div class="form-group">
			<label for="owner_cpf">
				CPF
			</label>
			<input type="text" class="form-control" name="owner_cpf" placeholder="" maxlength="11" value="<?= $ticket->owner_cpf ?>" />
		</div>

		<br />
		<a href="<?= DynamicHtml::link_to('ticket/list/') ?>" class="btn btn-primary ">
			Voltar
		</a>
		<button type="submit" class="btn btn-success">Atualizar</button>

	</form>
</div>

