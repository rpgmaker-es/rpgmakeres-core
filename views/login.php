<?php defined('RPGMAKERES') or exit();
/*
 * This file is part of RPG Maker ES Core
 * (c) RPG Maker ES community.
 * This code is licensed under MIT license (see LICENSE for details)
*/

/**
 * Login de RPG Maker . es por CesarRP
 */
?>

<form class="form_acceso" method="post">
    <fieldset <?=$errorLogin?"class='borde_rojo'":""?> >
        <legend>Hey, ya soy usuario</legend>
        <label>Nombre de usuario</label><br/>
        <input type="text" name="user" placeholder="Nickname" maxlength="12"/><br/>
        <div class="cofreMisterioso">
            <label>E-mail</label><br/>
            <input type="text" name="email" placeholder="cuenta@cuenta.com" maxlength="15"/><br/>
        </div>
        <label>Contraseña</label><br/>
        <input type="password" name="pass" placeholder="Tu contraseña" maxlength="255"/><br/>
        <div class="minibloque">
            <input type="checkbox" id="c1" name="cc"/>
            <label class="flotar_d" for="c1"><span></span>Recuérdame</label>
            <?php if ($errorLogin) { ?><p>Lo siento, no conozco esas credenciales.</p> <?php } ?>
            <button type="submit" name="submit" class="bg_azul borde_azul">
                <i class="acceso"></i>Iniciar Sesión
            </button>
        </div>
        <p><a href="">He olvidado mi contraseña, soy así de
                <del>PokaDPR</del>
                listo</a></p>
    </fieldset>
</form>
<form class="form_acceso">
    <fieldset>
        <legend>Registre su cuenta</legend>
        <label>Nombre de usuario</label><br/>
        <input type="text" placeholder="Nickname"/><br/>
        <label>Contraseña</label><br/>
        <input type="password" value=""/><br/>
        <label>E-mail</label><br/>
        <input type="email" placeholder="cuenta@cuenta.com"/><br/>
        <button type="submit" class="bg_verde borde_verde">
            <i class="nuser"></i>Registrarse
        </button>
    </fieldset>
</form>