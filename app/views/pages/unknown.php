<?php
namespace App\Views\Pages;
use App\Utils\Helpers;
use Pure\Utils\DynamicHtml;
use Pure\Utils\Res;
?>
<div class="container" style="margin-top: 100px;">
	<div class="row">
		<div class="col-md-12">
			<div class="error-template">
				<h1>
					Oops!
				</h1>
				<h2>
					Alguma coisa de errado aconteceu.
				</h2>
				<div>
					Entre em contato com o administrador do sistema.
				</div>
				<br />
				<div>
					<a href="<?= DynamicHtml::link_to('site/index') ?>" class="btn btn-primary btn-lg">
						<span class="fa fa-home"></span>
						Tire-me daqui
					</a>
				</div>
			</div>
		</div>
	</div>
</div>