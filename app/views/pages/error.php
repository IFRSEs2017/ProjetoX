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
					Página não encontrada.
				</h2>
				<div>
					Não conseguimos encontrar a página que você procura.
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