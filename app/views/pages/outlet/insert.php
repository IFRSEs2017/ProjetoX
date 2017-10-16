<?php
namespace App\Views\Pages\Outlet;
use App\Utils\Helpers;
use Pure\Utils\DynamicHtml;
use Pure\Utils\Res;
?>
<br><br><br>

<div>
    <?php if(isset($message)): ?>
        <div <?= $class ?> role="alert">
            <strong><?= $title ?></strong> <?= $message ?>
        </div>
    <?php endif; ?>
</div>


<?php if(isset($errors)): ?>
    <?php foreach($errors as $value): ?>
        <div>        
            <div <?= $class ?> role="alert">   
                <strong><?= $title ?></strong> <?= $value  ?><br>
            </div>  
        </div>
    <?php endforeach; ?>   
<?php endif; ?>



<div class='container'>
    <form action="<?= DynamicHtml::link_to('outlet/insert') ?>" method="POST">
        <div class="form-group">
            <label for="name"><?= Res::str('form_name') ?></label>
            <input type="text" class="form-control" name="form_name_user"  placeholder="" maxlength="255">
        </div>

        <div class="form-group">
        <label for="rg"><?= Res::str('form_rg') ?></label>
            <input type="text" class="form-control" name="form_rg" placeholder="Apenas Numeros" maxlength="20">
            
            <label for="cpf"><?= Res::str('form_cpf') ?></label>
            <input type="text" class="form-control" name="form_cpf" placeholder="Apenas Numeros" maxlength="11">
        </div>


        <div class="form-group">
            <label for="email"><?= Res::str('form_email') ?></label>
            <input type="email" class="form-control" name="form_email" placeholder="" maxlength="255">
        </div>
        
        <fieldset class="form-group">
            <legend>Privilégios</legend>
            <div class="form-check">
            <label class="form-check-label">
                <input type="radio" class="form-check-input" name="form_privilege"  value="1" >
                Administrador
            </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                <input type="radio" class="form-check-input" name="form_privilege"  value="0">
                Vendedor
                </label>
            </div>
            
        </fieldset>
        <button type="submit" class="btn btn-primary">Cadastrar</button>
        <a href="<?= DynamicHtml::link_to('outlet/list/') ?>" class="btn btn-success">
		Voltar
		</a>
    </form>

</div>