<?php defined('RPGMAKERES') OR exit();
/*
 * This file is part of RPG Maker ES Core
 * (c) RPG Maker ES community.
 * This code is licensed under MIT license (see LICENSE for details)
*/

/**
 * Admin usuario
 */
?>

<h2> Lista de usuarios</h2>

<a href="">Crear usuario</a>
<table>
    <tr>
        <th>UID</th>
        <th>Usuario</th>
        <th>Correo</th>
        <th>Activo</th>
        <th>Suspendido</th>
        <th>Verificado</th>
        <th>Privilegio</th>
        <th>Acciones</th>
    </tr>
    <?php foreach($users as $user) {
        ?>
        <tr>
            <td><?=$user["uid"]?></td>
            <td><?=$user["username"]?></td>
            <td><?=$user["email"]?></td>
            <td><?=$user["active"]=="1"?"Activo":"Inactivo"?></td>
            <td><?=$user["suspended"]=="1"?"Suspendido":""?></td>
            <td><?=$user["verified"]=="1"?"OK":"No"?></td>
            <td><?php
                switch($user["permissions"]) {
                    case "0":
                        echo "Usuario";
                        break;
                    case "4":
                        echo "Administrador";
                        break;
                    default:
                        echo "Desconocido (" . $user["permissions"] . ")";
                }
                ?></td>
            <td>
                <a href="">Editar</a>
                <a href="">Suspender</a>
                <a href="">Reiniciar clave</a>
                <a href="/admin/userdelete?uid=<?=$user["uid"]?>&csrf=<?=$csrf?>">Eliminar</a>
            </td>
        </tr>
    <?php
    }
    ?>
</table>