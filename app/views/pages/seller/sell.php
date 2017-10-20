<?php
namespace App\Views\Pages\Admin;
use App\Utils\Helpers;
use Pure\Utils\DynamicHtml;
use Pure\Utils\Res;
?>
<br />
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
    <br/>
    <div class="card">
    <div class="card-body">
        VALOR e LOTE
    </div>
  </div>
  <br>
	<form action="<?= DynamicHtml::link_to('outlet/insert') ?>" method="POST">
		<div class="form-group">
			<label for="name">
				<?= Res::str('form_name') ?>
			</label>
			<input type="text" class="form-control" name="form_name_user" placeholder="" maxlength="255" value="<?= $user->name ?>"/>
		</div>
        <div class="form-group">
            <label for="cpf">
                <?= Res::str('form_cpf') ?>
                <small style="color: gray;"><br>Somente números</small>
            </label>
            <input type="text" class="form-control" name="form_cpf_user" placeholder="" maxlength="255" value="<?= $user->name ?>"/>
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
		<button type="submit" class="btn btn-success">Cadastrar</button>
	</form>
</div>