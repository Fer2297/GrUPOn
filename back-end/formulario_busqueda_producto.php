<?php
require_once '../back-end/conexion_db.php';
require_once '../back-end/funciones.php';

if (isset($_GET['busqueda'])) {
    if(isset($_SESSION['cuenta'])){
        $correo = $_SESSION["cuenta"];
    }
    if (!isset($_GET["nombre"])) {
        $error[] = "Nombre no introducido";
    }
    if (!isset($error)) {
        //BÚSQUEDA NACIONAL
        if (!isset($_SESSION['cuenta']) || isset($_GET['nacional'])) {
            //BÚSQUEDA CON CATEGORIA
            if ($_COOKIE['categoria'] != 'general') {
                $nombre = $_GET['nombre'];
                $sql = 'SELECT * FROM PRODUCTO WHERE nombre_categoria LIKE "' . $_COOKIE['categoria'] . '" AND (nombre LIKE "%' . $nombre . '%" OR descripcion LIKE "%' . $nombre . '%") AND cantidad_disponible > 0';
                $result = realizarQuery($esquema, $sql);
                echo '<table border="1">';
                while ($fila = mysqli_fetch_row($result)) {
                    $cookie_name="productoVisitado";
                    echo '<tr><td><a href="producto.php" onclick="setCookie(\''.$cookie_name.'\',\''.$fila[0].'\',1)">' . $fila[4] . '</td></a><td>' . $fila[6] . '</tr>';
                }
                echo '</table>';
            }
            //BÚSQUEDA SIN CATEGORIA
            else {
                $nombre = $_GET['nombre'];
                $sql = 'SELECT * FROM PRODUCTO WHERE (nombre LIKE "%' . $nombre . '%" OR descripcion LIKE "%' . $nombre . '%") AND cantidad_disponible > 0';
                $result = realizarQuery($esquema, $sql);
                echo '<table border="1">';
                while ($fila = mysqli_fetch_row($result)) {
                    $cookie_name="productoVisitado";
                    echo '<tr><td><a href="producto.php" onclick="setCookie(\''.$cookie_name.'\',\''.$fila[0].'\',1)" >' . $fila[4] . '</td></a><td>' . $fila[6] . '</tr>';
                }
                echo '</table>';
            }
        }
        //BÚSQUEDA LOCAL
        else {
            $sql = "SELECT * FROM CUENTA WHERE correo ='" . $_SESSION['cuenta'] . "'";
            $result = realizarQuery($esquema, $sql);
            $ca = mysqli_fetch_row($result)[1];
            //BÚSQUEDA CON CATEGORIA
            if ($_COOKIE['categoria'] != 'general') {
                $nombre = $_GET['nombre'];
                $sql = 'SELECT * FROM PRODUCTO WHERE nombre_categoria LIKE "' . $_COOKIE['categoria'] . '" AND (nombre LIKE "%' . $nombre . '%" OR descripcion LIKE "%' . $nombre . '%") AND nombre_ca LIKE "' . $ca . '" AND cantidad_disponible > 0';
                $result = realizarQuery($esquema, $sql);
                echo '<table border="1">';
                while ($fila = mysqli_fetch_row($result)) {
                    $cookie_name="productoVisitado";
                    echo '<tr><td><a href="producto.php" onclick="setCookie(\''.$cookie_name.'\',\''.$fila[0].'\',1)">' . $fila[4] . '</td></a><td>' . $fila[6] . '</tr>';
                }
                echo '</table>';
            }
            //BÚSQUEDA SIN CATEGORIA
            else {
                $nombre = $_GET['nombre'];
                $sql = 'SELECT * FROM PRODUCTO WHERE (nombre LIKE "%' . $nombre . '%" OR descripcion LIKE "%' . $nombre . '%") AND nombre_ca LIKE "' . $ca . '" AND cantidad_disponible > 0';
                $result = realizarQuery($esquema, $sql);
                echo '<table border="1">';
                while ($fila = mysqli_fetch_row($result)) {
                    $cookie_name="productoVisitado";
                    echo '<tr><td><a href="producto.php" onclick="setCookie(\''.$cookie_name.'\',\''.$fila[0].'\',1)">' . $fila[4] . '</td></a><td>' . $fila[6] . '</tr>';
                }
                echo '</table>';
            }
        }
    }
}

/*

  if (isset($_GET['busqueda'])) {
  if (!isset($_GET['nombre'])) {
  $errores[] = 'Debes introducir nombre';
  }
  if (!isset($errores)) {
  $nombre = $_GET['nombre'];
  $sql = 'SELECT * FROM PRODUCTO WHERE nombre LIKE "%' . $nombre . '%" OR descripcion LIKE "%' . $nombre . '%"';
  $result = realizarQuery("grupon", $sql);
  echo '<table border=1>';
  while ($fila = mysqli_fetch_row($result)) {
  echo '<tr><td>' . $fila[4] . '</td><td>' . $fila[6] . '</tr>';
  }
  echo '</table>';
  }
  }

 */
if (isset($error)) {
   echo muestraErrores($error);
}

/**LA FUNCION QUE GENERA ESTE FORMULARIO ESTÁ EN FUNCIONES.PHP POR RESTRICCION DE DISEÑO DE LA WEB**/
