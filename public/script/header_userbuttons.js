if (sessionStorage.getItem("rpgmakeres_sesion") !== null) {
    document.getElementById("menu_user_acceso").style.display = "none";
    document.getElementById("menu_user_ajustes").style.display = "block";
    document.getElementById("menu_user_salir").style.display = "block";
} else {
    document.getElementById("menu_user_acceso").style.display = "block";
    document.getElementById("menu_user_ajustes").style.display = "none";
    document.getElementById("menu_user_salir").style.display = "none";
}
