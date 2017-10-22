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
		</strong>
		<br />
		<?= $message ?>
	</div>
	<?php endif; ?>
</div>