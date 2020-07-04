if (sessionStorage.getItem("rpgmakeres_sesion") !== null) {
    document.getElementById("menu_user_acceso").style.display = "none";
} else {
    document.getElementById("menu_user_ajustes").style.display = "none";
    document.getElementById("menu_user_salir").style.display = "none";
}