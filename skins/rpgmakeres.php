<?php defined('RPGMAKERES') OR exit();
/*
 * This file is part of RPG Maker ES Core
 * (c) RPG Maker ES community.
 * This code is licensed under MIT license (see LICENSE for details)
*/
?>
<!DOCTYPE html>
<html lang="es"><head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="description" content="RPG Maker en español es una página dedicada a RPG Maker 2000, 2003, XP, VX, VX Ace y MV con ayuda, descargar juegos y material para crear videojuegos.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/rpgmaker.css">
    <link rel="preload" href="/rpgmaker.css">
    <link rel="preload" href="/fonts/fontawesome-webfont.woff2">
    <link rel="preload" href="/fonts/MavenPro-VF.woff2">
    <link rel="preload" href="/pics/rpgmaker.png">
    <link rel="preload" href="/favicon.ico">
    <title><?=$title!=""?$title . " - ":""?>RPG Maker en español</title>
</head>
<body>
<nav>
    <div class="contenedor">
        <h1><a href="/">RPG Maker en español</a></h1><input type="checkbox" id="burger" /><label for="burger"><i class="burger"></i></label>
        <ul class="menu-general menu">
            <li><a href=""><i class="recursos"></i>Recursos</a></li><li><a href=""><i class="juegos"></i>Juegos</a></li><li><a href="/login/debugsessioncheck"><i class="ayuda"></i>Ayuda</a></li>
        </ul>
        <ul class="menu-usuario menu">
            <li id="menu_user_ajustes"><a target="_parent" href="/login/debugsessioncheck"><i class="ajustes"></i>Ajustes</a></li>
            <li id="menu_user_salir"><a target="_parent" href="/login/logout"><i class="salir"></i>Salir</a></li>
            <li id="menu_user_acceso"><a target="_parent" href="/login"><i class="acceso"></i>Acceso</a></li>
        </ul>
    </div>
</nav>
<section>
    <article class="contenedor">
        <?=$_output?>
    </article>
</section>
<footer>
    <div class="contene-footer">
        <div>
            <h3>Enlaces de ayuda</h3>
            <ul>
                <li><a href="">Wiki RPG Maker (ES)</a></li>
                <li><a href="">Comunidad RPG Maker</a></li>
                <li><a href="">Soporte On-line</a></li>
            </ul>
        </div>
        <div>
            <h3>Enlaces de ayuda</h3>
            <ul>
                <li><a href="">Wiki RPG Maker (ES)</a></li>
                <li><a href="">Comunidad RPG Maker</a></li>
                <li><a href="">Soporte On-line</a></li>
            </ul>
        </div>
        <div>
            <h3>Enlaces de ayuda</h3>
            <ul>
                <li><a href="">Wiki RPG Maker (ES)</a></li>
                <li><a href="">Comunidad RPG Maker</a></li>
                <li><a href="">Soporte On-line</a></li>
            </ul>
        </div>
    </div>
    <p><span class="copy-left">©</span>RPGMaker.es</p>
</footer>
<script>
    if (sessionStorage.getItem("rpgmakeres_sesion") !== null) {
        document.getElementById("menu_user_acceso").style.display = "none";
    } else {
        document.getElementById("menu_user_ajustes").style.display = "none";
        document.getElementById("menu_user_salir").style.display = "none";
    }
</script>
</body>
</html>