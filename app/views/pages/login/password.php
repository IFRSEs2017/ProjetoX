<?php
namespace App\Views\Pages;
use Pure\Utils\DynamicHtml;
use Pure\Utils\Res;
?>
<div style="margin-top: 30vh;"></div>
<form class="form-horizontal" role="form" method="POST" action="<?= DynamicHtml::link_to('login/reset') ?>">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <h2><?= 'Redefinir sua senha' ?></h2>
            <hr />
        </div>
    </div>
    <div class="row">
		<?php if(isset($error_message)): ?>
        <div class="col-md-3">
		</div>
			<div class="col-md-6">
				<div class="form-control-feedback">
					<span class="text-danger align-middle"><?= $error_message ?>
					</span>
				</div>
				<br />
			</div>

		<?php endif; ?>
    </div>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="sr-only" for="password"><?= Res::str('password_label') ?>
                </label>
                <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                    <div class="input-group-addon" style="width: 2.6rem">
                        <i class="fa fa-key"></i>
                    </div>
                    <input name="password" class="form-control" id="password" placeholder="<?= Res::str('password_sample') ?>"
						required="" type="password" />
                </div>
            </div>
        </div>
    </div>
	<div class="row">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<div class="form-group">
				<label class="sr-only" for="re-password">
					<?= Res::str('password_label') ?>
				</label>
				<div class="input-group mb-2 mr-sm-2 mb-sm-0">
					<div class="input-group-addon" style="width: 2.6rem">
						<i class="fa fa-key"></i>
					</div>
					<input name="re-password" class="form-control" id="password" placeholder="<?= 'Digite sua senha novamente' ?>"
						required="" type="password" />
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<?php if(isset($show_captcha)): ?>
				<div class="g-recaptcha" data-sitekey="6LeN6zIUAAAAAPfwcPd-6TjJy8uhIWcH-EmtNxA8"></div>
			<?php endif ?>
		</div>
	</div>
    <div class="row" style="padding-top: 1rem">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <button type="submit" class="btn btn-success">
				<?= 'Redefinir' ?>
            </button>
        </div>
    </div>
</form>