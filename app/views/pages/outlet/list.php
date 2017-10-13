<?php
namespace App\Views\Pages\Outlet;
use App\Utils\Helpers;
use Pure\Utils\DynamicHtml;
use Pure\Utils\Res;
?>

<div class="container" style="margin-top: 100px;">
	<?php if(isset($list)): ?>
		<?php foreach($list as $user): ?>
		<?php var_dump($user); ?>
		<?php endforeach; ?>
	<?php endif; ?>
</div>