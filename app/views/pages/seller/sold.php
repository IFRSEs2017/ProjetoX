<?php
namespace App\Views\Pages;
use Pure\Utils\DynamicHtml;
use Pure\Utils\Res;
?>
<br />
<br />
<br />

<div class="text-center" style="margin-top: 6vh">
	<img src="<?= $qrcode ?>" class="img-thumbnail" style="max-width: 300px" />
	<h1 style="margin-top: 60px;"></h1>
	<h4>
		<?= $message ?>
	</h4>
	<h1>
		<a href="mailto:<?= $email ?>">
			<?= $email ?>
		</a>
	</h1>
	<?php if($send): ?>
	<h5>
		Não esqueça de verificar sua caixa de entrada e spam.
	</h5>
	<?php endif; ?>
	<br />
	<a href="<?= DynamicHtml::link_to('site/index/') ?>" class="btn btn-primary">
		Voltar
	</a>
</div>