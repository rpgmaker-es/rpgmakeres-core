<?php defined('RPGMAKERES') OR exit();
/*
 * This file is part of RPG Maker ES Core
 * (c) RPG Maker ES community.
 * This code is licensed under MIT license (see LICENSE for details)
*/

/**
 * Ajustes
 */
?>
<div class="cpanel">
    <figure>
        <img src="/pics/av_base.jpg" alt="Avatar" />
        <figcaption>Hasta13Caracteres</figcaption>
    </figure>
    <form>
        <label>Nombre de usuario (dato inamovible)</label>
        <input type="text" placeholder="Nickname" disabled />
        <label>Contraseña (puedes modificarlo aquí)</label>
        <input type="password" value="Tu contraseña" />
        <input type="file" id="file" for="noav" />
        <label for="file" class="btn-3 bg_verde borde_verde"><i class="cargavatar"></i>Cargar Avatar</label>
        <input type="checkbox" id="c1" name="cc" />
        <label for="c1"><span></span>Eliminar avatar</label>
        <button type="submit" class="bg_azul borde_azul flotar_d">
            <i class="actualizar"></i>Actualizar Perfil
        </button>
    </form>
</div>
<div class="cpanel">
    <h2><i class="estadisticas"></i>Información de NombreUsuario</h2>
    <ul>
        <li><i class="rdescargados"></i>Recursos cargados<br /><span>1 mil.</span></li>
        <li><i class="jtotales"></i>Juegos aportados<br /><span>0</span></li>
        <li><i class="resena"></i>Reseñas realizadas<br /><span>10M</span></li>
        <li><i class="tutos"></i>Tutoriales aportados<br /><span>40</span></li>
    </ul>
    <ul>
        <li><i class="altavoz"></i>Perfil en red social 1<br /><span><a href="">Visitar</a></span></li>
        <li><i class="altavoz"></i>Perfil en red social 2<br /><span><a href="">Visitar</a></span></li>
        <li><i class="altavoz"></i>Perfil en red social 3<br />--</li>
        <li><i class="mundo"></i>Sitio web propio<br /><span><a href="">Visitar</a></span></li>
    </ul>
    <form>
        <button type="submit" class="bg_rojo borde_rojo flotar_d">
            <i class="anular"></i>Anular cuenta
        </button>
        <p class="infoelimincuen">Se reactiva al logearse</p>
    </form>
</div>
