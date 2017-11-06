<?php
namespace App\Views\Layout;
use Pure\Utils\DynamicHtml;
use Pure\Utils\Res;
use Pure\Utils\Auth;
use Pure\Utils\Request;
use Pure\Routes\Route;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="Content-Language" content="pt-br" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<title>
		<?= Res::str('app_title') ?>
	</title>
	<?= DynamicHtml::link_css('bootstrap.min.css') ?>
	<?= DynamicHtml::link_css('font-awesome.min.css') ?>
	<?= DynamicHtml::link_css('bootstrap-datepicker.css') ?>
	<?= DynamicHtml::link_script('plotly-latest.min.js') ?>

	<script src='https://www.google.com/recaptcha/api.js?hl=pt-BR'></script>
</head>
<body>
	<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
		<a class="navbar-brand" href="<?= DynamicHtml::link_to('site/index') ?>">
			<?= Res::str('app_title')?>
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarCollapse">
			<ul class="navbar-nav mr-auto">
				<?php if(isset($is_admin)): ?>
				<?php if($is_admin): ?>
				<li class="nav-item">
					<a class="nav-link <?= Request::is_to([new Route('admin', 'index')]) ? 'active' : '' ?>" href="<?= DynamicHtml::link_to('site/index') ?>">
						Home
						<span class="sr-only">(current)</span>
					</a>
				</li>
				<li class="nav-item <?= Request::is_to([new Route('outlet', 'list')]) ? 'active' : '' ?>">
					<a class="nav-link" href="<?= DynamicHtml::link_to('outlet/list') ?>">
						<?= Res::str('menu_outlets') ?>
					</a>
				</li>
				<li class="nav-item <?= Request::is_to([new Route('lot', 'list')]) ? 'active' : '' ?>">
					<a class="nav-link" href="<?= DynamicHtml::link_to('lot/list') ?>">
						<?= 'Lotes e ingressos' ?>
					</a>
				</li>
				<?php else: ?>
				<li class="nav-item <?= Request::is_to([new Route('seller', 'index')]) ? 'active' : '' ?>">
					<a class="nav-link" href="<?= DynamicHtml::link_to('seller/index') ?>">
						<?= Res::str('menu_dashboard') ?>
					</a>
				</li>
				<li class="nav-item <?= Request::is_to([new Route('seller', 'sell')]) ? 'active' : '' ?>">
					<a class="nav-link" href="<?= DynamicHtml::link_to('seller/sell') ?>">
						<?= Res::str('menu_sell') ?>
					</a>
				</li>
				<?php endif; ?>
				<?php endif; ?>
			</ul>
			<?php if(Auth::is_authenticated()): ?>
			<ul class="nav navbar-nav">
				<li class="nav-item">
					<span class="display-6" style="margin-right: 20px; color: #999999;">
						<?= $user_name ?>
					</span>
				</li>
			</ul>
			<ul class="nav navbar-nav">
				<li class="nav-item">
					<a class="nav-link" style="color: white;" href="<?= DynamicHtml::link_to('login/exit') ?>">
						<?= Res::str('logout_label') ?>
					</a>
				</li>
			</ul>
			<?php endif; ?>
		</div>
	</nav>
	<div class="container">
		<?= $this->content(); ?>
	</div><!-- /.container -->
	<?= DynamicHtml::link_script('jquery-3.2.1.min.js') ?>
	<?= DynamicHtml::link_script('popper.min.js') ?>
	<?= DynamicHtml::link_script('bootstrap.min.js') ?>
	<?= DynamicHtml::link_script('bootstrap-datepicker.min.js') ?>
	<?= DynamicHtml::link_script('bootstrap-datepicker.pt-BR.min.js') ?>
	<?= DynamicHtml::link_script('default.js') ?>


</body>
</html>
