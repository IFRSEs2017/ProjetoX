<?php
namespace App\Views\Ticket;
use Pure\Utils\DynamicHtml;
use Pure\Utils\Res;
use App\Utils\Helpers;
?>
<br />
<br />
<br />

<div class="text-center" style="margin-top: 6vh">
    <?php if($view): ?>
        <h1 style="margin-top: 60px;"></h1>
        <h1>
        <?php if($ticket->validated): ?>
            Esse ingresso já foi consumido!
        <?php else: ?>
            Esse ingresso é válido!
        <?php endif; ?>
        </h1>
        <br>
        <?= DynamicHtml::img('validated.png') ?>
        <br>
        <br>
        <h3>
            Ingresso registrado para
		    <span class="badge badge-pill badge-dark" style="margin: 5px">
            <?= $ticket->owner_name ?>
            </span>
            <br>
            portador do CPF
		    <span class="badge badge-pill badge-dark"  style="margin: 5px">
            <?= Helpers::format_cpf($ticket->owner_cpf) ?>
            </span>
            <br>
            <br>
            <?php if(!$ticket->validated): ?>
                <form action="<?= DynamicHtml::link_to('ticket/validate') ?>" method="POST">
                    <input type="text" name="secret" value="<?= $secret ?>" hidden/>
                    <input type="text" name="ticket" value="<?= $ticket->id ?>" hidden/>
                    <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fa fa-id-card" aria-hidden="true"></i> 
                    Consumir</button>
                </form>
            <?php endif; ?>
        </h3>
    <?php else: ?>
        <h1 style="margin-top: 60px;"></h1>
        <h1>
            Seu ingresso é válido!
        </h1>
        <br>
        <?= DynamicHtml::img('validated.png') ?>
        <br>
        <br>
        <h3>
            Ingresso registrado para 
		    <span class="badge badge-pill badge-dark" style="margin: 5px">
            <?= $ticket->owner_name ?>
            </span>
            <br>
            portador do CPF 
		    <span class="badge badge-pill badge-dark"  style="margin: 5px">
            <?= substr($ticket->owner_cpf, 0, 3) . '.***.***-' . substr($ticket->owner_cpf, 9, 2) ?>
            </span>
        </h3>
    <?php endif; ?>
</div>