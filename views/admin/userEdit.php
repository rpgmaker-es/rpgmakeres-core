<?php defined('RPGMAKERES') or exit();
/*
 * This file is part of RPG Maker ES Core
 * (c) RPG Maker ES community.
 * This code is licensed under MIT license (see LICENSE for details)
*/

/**
 * Add/edit user
 */
?>


<form id="user" enctype="multipart/form-data" method="post" action="">
    <h2><?=FormsService::isFilled($user)?"Editar usuario":"Agregar usuario"?></h2>

    <?php if (ValidationService::hasErrors()) {
        echo ValidationService::printErrors();
    }
    ?>

    <ul>

        <li id="l_uid">
            <label for="uid" class="<?=ValidationService::addClassIfError("uid")?>" >UID </label>
            <div>
                <input id="uid" name="uid" type="text" maxlength="10" value="<?=FormsService::fill($user,"uid")?>" disabled/>
            </div>
        </li>
        <li id="l_username">
            <label for="username" class="<?=ValidationService::addClassIfError("username")?>">Nombre de usuario </label>
            <div>
                <input id="username" name="username" type="text" maxlength="15"
                       value="<?=FormsService::fill($user,"username")?>"/>
            </div>
        </li>
        <li id="l_email">
            <label for="email" class="<?=ValidationService::addClassIfError("email")?>">Email </label>
            <div>
                <input id="email" name="email" type="email" maxlength="50"
                       value="<?=FormsService::fill($user,"email")?>"/>
            </div>
        </li>
        <li id="l_password">
            <label for="password" class="<?=ValidationService::addClassIfError("password")?>">Contraseña (dejar en blanco para no cambiar)</label>
            <div>
                <input id="password" name="password" type="password" maxlength="50"
                       value=""/>
            </div>
        </li>
        <li id="l_validation_settings">
            <label>Validacion </label>
            <span>
			    <input id="active" name="active" value="1" type="checkbox" <?=FormsService::fillChecked($user, "active")?>/>
            <label  for="active" class="<?=ValidationService::addClassIfError("active")?>">Activo</label>
                <input id="suspended" name="suspended" value="1" type="checkbox"  <?=FormsService::fillChecked($user, "suspended")?>/>
            <label for="suspended" class="<?=ValidationService::addClassIfError("suspended")?>">Suspendido</label>
                <input id="verified" name="verified" value="1" type="checkbox" <?=FormsService::fillChecked($user, "verified")?>/>
            <label for="verified" class="<?=ValidationService::addClassIfError("verified")?>">Verificado</label>
		</span>
        </li>
        <li id="l_permissions">
            <label for="permissions" class="<?=ValidationService::addClassIfError("permissions")?>">Privilegios</label>
            <div>
                <select id="permissions" name="permissions">
                    <option value="0" <?=FormsService::fillSelect($user, "permissions", "0")?> >Usuario</option>
                    <!--TODO-->
                    <option value="4" <?=FormsService::fillSelect($user, "permissions", "4")?>  >Administrador</option>
                </select>
            </div>
        </li>
        <li id="l_avatar">
            <label for="avatar" class="<?=ValidationService::addClassIfError("avatar")?>">Avatar </label>
            <div>
                <input id="avatar" name="avatar" type="file"/>
            </div>
        </li>
        <li id="l_url1">
            <label for="url1" class="<?=ValidationService::addClassIfError("url1")?>">URL 1 </label>
            <div>
                <input id="url1" name="url1" type="text" maxlength="50"
                       value="<?=FormsService::fill($user,"url1")?>"/>
            </div>
        </li>
        <li id="l_url2">
            <label for="url2" class="<?=ValidationService::addClassIfError("url2")?>">URL 2 </label>
            <div>
                <input id="url2" name="url2" type="text" maxlength="50"
                       value="<?=FormsService::fill($user,"url2")?>"/>
            </div>
        </li>
        <li id="l_url3">
            <label for="url3" class="<?=ValidationService::addClassIfError("url3")?>">URL 3 </label>
            <div>
                <input id="url3" name="url3" type="text" maxlength="50"
                       value="<?=FormsService::fill($user,"url3")?>"/>
            </div>
        </li>
        <li id="l_url4">
            <label for="url4" class="<?=ValidationService::addClassIfError("url4")?>">URL 4 </label>
            <div>
                <input id="url4" name="url4" type="text" maxlength="50"
                       value="<?=FormsService::fill($user,"url4")?>"/>
            </div>
        </li>

        <li class="buttons">
            <input type="hidden" name="csrf" value="<?=$csrf?>"/>
            <input id="submit" type="submit" name="submit" value="Enviar"/>
        </li>
    </ul>
</form>

<?php ValidationService::cleanError();?>