<?php
namespace App\Views\Pages\Outlet;
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
	<h2>Atualizar lote de ingressos</h2>
	<br />
	<form action="<?= DynamicHtml::link_to('lot/update/' . $lot->id) ?>" method="POST">
		<div class='row'>
			<div class="form-group col-md-6">
				<label for="amount">
					<legend>
						<?= Res::str('lot_amount') ?>
					</legend>
				</label>
				


				<input type="number" class="form-control" min="0" name="lot_amount" placeholder="" maxlength="255" value="<?= $lot->amount ?>"/>
			</div>

			<div class="form-group col-md-6">
				<label for="valuation">
					<legend>
						<?= Res::str('lot_valuation') ?>
					</legend>
				</label>
				<input type="" class="form-control" min="0" name="lot_valuation" placeholder="" maxlength="255" value="<?= $lot->valuation ?>"/>
			</div>
			<div class="form-group">
					<input type="text" class="form-control" name="lot_id" placeholder="" value="<?= $lot->id ?>" readonly hidden/ />
				</div>
		</div>

		<div class='row'>
			<div class='col-md-6'>
				<div class="form-group ">
					<label for="start">
						<legend>
							<?= Res::str('lot_start') ?>
						</legend>
					</label>

					<div class="input-group date datepicker" data-provide="datepicker">
						<input type="text" class="form-control" name="start" value="<?= $lot->start ?>"/>
						<div class="input-group-addon">
							<span class="glyphicon glyphicon-th">
								<i class="fa fa-calendar" aria-hidden="true"></i>
							</span>
						</div>
					</div>
				</div>
			</div>

			<div class='col-md-6'>
				<div class="form-group ">
					<label for="end">
						<legend>
							<?= Res::str('lot_end') ?>
						</legend>
					</label>

					<div class="input-group date datepicker" data-provide="datepicker">
						<input type="text" class="form-control" name="end" value="<?= $lot->end ?>"/>
						<div class="input-group-addon">
							<span class="glyphicon glyphicon-th">
								<i class="fa fa-calendar" aria-hidden="true"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>


		<a href="<?= DynamicHtml::link_to('lot/list/') ?>" class="btn btn-primary">
			Voltar
		</a>
		<button type="submit" class="btn btn-success"> Atualizar </button>

	</form>

</div>