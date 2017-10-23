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
	<h2>Venda de ingressos</h2>
	<br />
	<div class="card text-center">
		<h3 class="card-header">
			<?= $lot_number ?>º lote de ingressos
		</h3>
		<div class="card-body">
			<h6 class="card-text text-muted">
				De <?= Helpers::date_format($lot->start) ?> a <?= Helpers::date_format($lot->end) ?>
			</h6>
			<p class="card-text">
				<h2>
					<span class="badge badge-pill badge-dark">
						Valor: R$ <?= number_format($lot->valuation, 2, ',', '.') ?>
					</span>
				</h2>
				<h4>
					<span class="badge badge-pill badge-secondary">
						Resta(m) <?= $remain ?> ingresso(s).
					</span>
				</h4>
			</p>
		</div>
	</div>
	<br />
	<form action="<?= DynamicHtml::link_to('seller/sell') ?>" method="POST">
		<div class="form-group">
			<label for="name">
				<?= Res::str('form_name') ?>
			</label>
			<input type="text" class="form-control" name="form_name" placeholder="" maxlength="255" value="<?= $user->name ?>" />
		</div>
		<div class="form-group">
			<label for="cpf">
				<?= Res::str('form_cpf') ?>
				<small style="color: gray;">
					<br />Somente números
				</small>
			</label>
			<input type="text" class="form-control" name="form_cpf" placeholder="" maxlength="255" value="<?= $user->name ?>" />
		</div>
		<div class="form-group">
			<label for="email">
				<?= Res::str('form_email') ?>
			</label>
			<input type="email" class="form-control" name="form_email" placeholder="" maxlength="255" value="<?= $user->email ?>" />
		</div>
		<br />
		<a href="<?= DynamicHtml::link_to('outlet/list/') ?>" class="btn btn-primary">
			Voltar
		</a>
		<button type="submit" class="btn btn-success">Vender</button>
		<br />
		<br />
		<br />
	</form>
</div>