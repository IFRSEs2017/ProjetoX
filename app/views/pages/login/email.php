<?php
namespace App\Views\Pages;
use Pure\Utils\DynamicHtml;
use Pure\Utils\Res;
?>
<div style="margin-top: 30vh;"></div>
<form class="form-horizontal" role="form" method="POST" action="<?= DynamicHtml::link_to('login/forgot') ?>">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <h2><?= 'Informe o e-mail cadastrado' ?></h2>
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
            <div class="form-group has-danger">
                <label class="sr-only" for="email"><?= Res::str('email_label') ?>
                </label>
                <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                    <div class="input-group-addon" style="width: 2.6rem">
                        <i class="fa fa-at"></i>
                    </div>
					<input name="email" class="form-control" id="email" placeholder="<?= Res::str('email_sample') ?>" required="" autofocus="" type="email" value="<?= isset($old_email) ? $old_email : ""?>"/>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="padding-top: 1rem">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <button type="submit" class="btn btn-success">
				</i><?= 'Redefinir' ?>
            </button>
        </div>
    </div>
</form>