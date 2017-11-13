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
	<div class="alert alert-info" role="alert">
        <h4 class="alert-heading">Gerar novo ingresso</h4>
		<p>
			<strong>Cuidado!</strong> Ao gerar um novo ingresso, um e-mail com um <strong>novo QRCode</strong> será enviado para  "<?= $ticket->owner_email ?>".
            <br>
            Deseja gerar um novo ingresso?
        </p>
		<form action="<?= DynamicHtml::link_to('ticket/send_again/' . $ticket->id) ?>" method="POST">
			<input type="number" value="<?= $ticket->id ?>" name="id_resend" hidden/>
			<div class="form-group" style="text-align: right">
				<a type="button" href="<?= DynamicHtml::link_to('ticket/list') ?>" class="btn btn-primary">Voltar</a>
				<button type="submit" class="btn btn-success" style="cursor: pointer;">Gerar</button>
			</div>
		</form>
	</div>
	<?php endif; ?>
</div>