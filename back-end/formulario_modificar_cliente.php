<?php

require_once '../back-end/conexion_db.php';
require_once '../back-end/funciones.php';

$correo = $_SESSION['cuenta'];




if (isset($_POST['cambiarDatos'])) {
    if (!isset($_POST["correo"])) {
        $error[] = "Debes introducir correo";
    }
    if (!isset($_POST["nombre_cliente"])) {
        $error[] = "Debes introducir un nombre";
    }
    if (!isset($_POST["apellidos_cliente"])) {
        $error[] = "Debes introducir los apellidos";
    }
    if (!isset($_POST["comunidad_autonoma"])) {
        $error[] = "Debes introducir una comunidad aut&oacute;noma";
    }

    //RESTRICCION: Para evitar el cambio de una comunidad autonoma
    if (!array_key_exists($_POST['comunidad_autonoma'], $arrayComunidades)) {
        $error[] = 'Has trampeado las comunidades aut&oacute;nomas, campe&oacute;n';
    }
    //RESTRICCIONES Para evitar el cambio de afinidad
    $contador = 0;
    foreach ($arrayCategorias as $categoria => $val) {
        if (isset($_POST[$categoria])) {
            if (!array_key_exists($_POST[$categoria], $arrayCategorias)) {
                $error[] = "No existe la categoria";
            } else {
                $contador++;
            }
        }
    }
    if ($contador == 0) {
        $error[] = "Tiene que seleccionar una categoria";
    }
    //RESTRICCION: SI EL CORREO INTRODUCIDO ES NUEVO, DEBE CHECKEARSE QUE NO EXISTA YA.
    if ($correo !== $_POST['correo']) {
        if (existeCorreo($_POST['correo'])) {
            $error[] = 'Ese correo ya existe';
        }
    }
    //Checkeo de entradas correctas
    //Depuracion de entradas (sanitize)
    if (!isset($error)) {
        $correoNuevo = sanitarString($_POST['correo']);
        $nombre = sanitarString($_POST['nombre_cliente']);
        $apellidos = sanitarString($_POST['apellidos_cliente']);
        $nombre_ca = $_POST['comunidad_autonoma'];
        $sql = "UPDATE CUENTA SET CORREO='$correoNuevo', NOMBRE_CA='$nombre_ca' WHERE CORREO='$correo'";
        realizarQuery($esquema, $sql);
        $sql = "UPDATE CLIENTE SET NOMBRE_CLIENTE='$nombre', APELLIDOS_CLIENTE='$apellidos' WHERE CORREO='$correoNuevo'";
        realizarQuery($esquema, $sql);
        $sql = "DELETE FROM AFINIDADES WHERE CORREO='$correoNuevo'";
        realizarQuery($esquema, $sql);
        foreach ($arrayCategorias as $categoria => $val) {
            if (isset($_POST[$categoria])) {
                $sql = "INSERT INTO AFINIDADES VALUES('$correoNuevo','$categoria')";
                realizarQuery($esquema, $sql);
            }
        }
        $_SESSION['cuenta'] = $correoNuevo;
        $_SESSION['nombre'] = $nombre;
        header('Location: modificar_cuenta_cliente.php');
    }
}
if (isset($_POST['cambiarPwd'])) {
    if (!isset($_POST['pwd_old'])) {
        $error[] = 'No has introducido la contrase&ntilde;a actual';
    }
    if (!isset($_POST['pwd_new'])) {
        $error[] = 'No has introducido la nueva contrase&ntilde;a';
    }
    if (!isset($_POST['pwd_new_confirm'])) {
        $error[] = 'No has introducido la confirmaci&oacute;n de la contrase&ntilde;a';
    }
    //RESTRICCIÓN 1: la contraseña actual debe ser correcta.
    $sql = "SELECT * FROM CUENTA WHERE CORREO='$correo'";
    $resultado = realizarQuery($esquema, $sql);
    $fila = mysqli_fetch_array($resultado);
    if (!password_verify($_POST['pwd_old'], $fila['pwd'])) {
        $error[] = 'Contrase&ntilde;a incorrecta';
    }
    //RESTRICCIÓN 2: la nueva contraseña debe coincidir con su confirmación:
    if ($_POST['pwd_new'] !== $_POST['pwd_new_confirm']) {
        $error[] = 'Las contrase&ntilde;s no coinciden';
    }
    //Checkeo de entradas correctas (no vacias, min caracteres,regexp....)
   
   

    
    
    
    //Depuracion de entradas (sanitize)
    if (!isset($error)) {
        $pwdNew = sanitarString($_POST['pwd_new']);
        $hash = $hash = password_hash($pwdNew, PASSWORD_BCRYPT);
        $sql = "UPDATE CUENTA SET PWD='$hash' WHERE CORREO='$correo'";
        realizarQuery($esquema, $sql);
    }
}

if (isset($error)) {
    echo muestraErrores($error);
} else {
    echo muestraFormularioDatos();
    echo muestraFormularioPwd();
}

function muestraFormularioDatos() {
    global $esquema;
    global $correo;
    //Primero traemos los datos del cliente de la DB
    $sql = "SELECT * FROM CUENTA,CLIENTE WHERE CUENTA.CORREO='$correo' AND CLIENTE.CORREO='$correo'";

    $resultado = realizarQuery($esquema, $sql);
    $fila = mysqli_fetch_array($resultado);

    $nombre = $fila['nombre_cliente'];
    $apellidos = $fila['apellidos_cliente'];
    $ca = $fila['nombre_ca'];

//Después obtengo los datos de sus afinidades.
    $sql = "SELECT * FROM AFINIDADES WHERE CORREO='$correo'";
    $resultado = realizarQuery($esquema, $sql);
    while ($fila = mysqli_fetch_array($resultado)) {
        $afinidades[] = $fila['nombre_categoria'];
    }

    $form = '<form action="" method="post">' .
            'Correo: <input type="text" name="correo" value="' . $correo . '"/><br/>' .
            'Nombre: <input type="text" name="nombre_cliente" value="' . $nombre . '"/><br/>' .
            'Apellidos: <input type="text" name="apellidos_cliente" value="' . $apellidos . '"/> <br/>' .
            'Comunidad Aut&oacute;noma: <select name="comunidad_autonoma">' . opcionesComunidadSeleccionada($ca) .
            '</select><br>Afinidades:<br/>' . checkboxesCategoriasSeleccionadas($afinidades) . '<input type="submit" name="cambiarDatos" value="Guardar"/>' .
            '</form>';
    return $form;
}

function muestraFormularioPwd() {
    $form = '<form action="" method="post">' .
            'Contrase&ntilde;a actual: <input type="password" name="pwd_old"/><br/>' .
            'Nueva contrase&ntilde;a: <input type="password" name="pwd_new" /><br/>' .
            'Confirmar nueva contrase&ntilde;a: <input type="password" name="pwd_new_confirm" /><br/>' .
            '<input type="submit" name="cambiarPwd" value="Cambiar"/>' .
            '</form>';
    return $form;
}

?>