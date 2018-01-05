/**
 * Recibe un id y un string de la siguiente forma 1,2,3,4
 * Añade el id al final
 * @param string $id
 * @param string $carritoActual
 * @return string
 */

function iniciarCarrito() {
    //eliminar en caso de querer reiniciar con el login
    if (!getCookie("carrito")) {
        setCookie("carrito", "", 1);
    }

}
function addCarrito(id, carritoActual) {
    var var_string = id;
    alert("Valor del id producto"+var_string);
    var valueofCarrito = getCookie("carrito");
    alert("Valor actual del carrito"+valueofCarrito);
    if (valueofCarrito== "") {
        setCookie("carrito", var_string, 1);
    } else {
        
        var array = getCookie("carrito").split(",");
        array.push(id);
        var string = array.join();
        var cookie_name = "carrito";
        setCookie(cookie_name, string, 1);

    }

}
/**
 * Recibe un id y un string de la siguiente forma 1,2,3,4
 * Elimina el id
 * @param string $id
 * @param string $carritoActual
 * @return string
 */
function removeCarrito(id, carritoActual) {
    array = carritoActual.split(",");
    var i = array.indexOf(id);
    if (i != -1) {
        array.splice(i, 1);
    }
    var string = array.join();
    return string;


}
function setCookie(cname, cvalue, exdays) {

    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toGMTString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function checkCookie() {
    var valueofCarrito = getCookie("carrito");
    if (valueofCarrito != "") {

    } else {

    }
}