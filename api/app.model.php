<?php

/**
 * model.php
 * Modelo
 *
 * @author     Carlos Guzmán
 * @copyright  Privatour
 * @version    1.0
 * @example    http://url/something.php?op=1
 */
class App
{
    protected $sql_con;
    protected $datos = [];

    public function __construct()
    {
        require('database.conf.php');
    }

    protected function set_conexion($host, $user, $pass, $bd)
    {
        $this->sql_con = new mysqli($host, $user, $pass, $bd);
        $this->sql_con->set_charset('utf8');
    }

    public function getTours()
    {
        $hosteo = new Host(1);
        $this->set_conexion($hosteo->datos['host'], $hosteo->datos['user'], $hosteo->datos['pass'], $hosteo->datos['bd']);
        $consulta = "SELECT * FROM tour";
        $result = $this->sql_con->query($consulta);
        $dato['success'] = false;
        $this->datos['desc'] = 'Database error';
        if (!$result) {
            trigger_error('Ha  ocurrido un error');
            $this->sql_con->close();
        } else {
            $this->datos['success'] = true;
            $this->datos['desc'] = 'Query properly';
            $this->datos['tours'] = array();
            while ($row = mysqli_fetch_array($result)) {
                $fila['tour_id'] = $row['tour_id'];
                $fila['tour_nombre'] = $row['tour_nombre'];
                $fila['tour_descripcion_corta'] = $row['tour_descripcion_corta'];
                $fila['tour_descripcion_corta_ingles'] = $row['tour_descripcion_corta_ingles'];
                $fila['tour_corta_portugues'] = $row['tour_corta_portugues'];
                $fila['tour_larga'] = $row['tour_larga'];
                $fila['tour_larga_ingles'] = $row['tour_larga_ingles'];
                $fila['tour_larga_portugues'] = $row['tour_larga_portugues'];
                $fila['tour_duracion'] = $row['tour_duracion'];
                $fila['tour_cantidad_maxima'] = $row['tour_cantidad_maxima'];
                $fila['tour_precio'] = $row['tour_precio'];
                array_push($this->datos['tours'], $fila);
            }
            $this->sql_con->close();

        }
    }
    public function setTour($nombre, $descripcion_corta, $descripcion_corta_ingles, $corta_portugues, $larga, $larga_ingles, $larga_portugues, $duracion, $cantidad_maxima, $precio)
    {
        if ($this->emailValidation($correo)){
            if ($this->rutValidation($rut)){
                $hosteo = new Host(1);
                $this->set_conexion($hosteo->datos['host'], $hosteo->datos['user'], $hosteo->datos['pass'], $hosteo->datos['bd']);
                $consulta = "INSERT INTO tour VALUES (null,'$nombre','$descripcion_corta','$descripcion_corta_ingles','$corta_portugues','$larga','$larga_ingles','$larga_portugues',$duracion,$cantidad_maxima,$precio)";
                $result = $this->sql_con->query($consulta);
                $this->datos['success'] = false;
                $this->datos['desc'] = 'Error';
                if (!$result) {
                    trigger_error('Ha  ocurrido un error');
                    $this->sql_con->close();
                } else {
                    if ($this->sql_con->affected_rows == 0) {
                        $this->datos['success'] = false;
                        $this->datos['desc'] = 'Error';
                    } else {
                        $this->datos['success'] = true;
                        $this->datos['desc'] = 'Almacenado correctamente';
                    }
                    $this->sql_con->close();
                }
            }
            else {
                $this->datos['success'] = false;
                $this->datos['desc'] = 'RUT ya existe';
            }
        }
        else {
            $this->datos['success'] = false;
            $this->datos['desc'] = 'Correo ya existe';
        }
    }
    public function rutValidation($rut)
    {
        $hosteo = new Host(1);
        $this->set_conexion($hosteo->datos['host'], $hosteo->datos['user'], $hosteo->datos['pass'], $hosteo->datos['bd']);
        $consulta = "SELECT * FROM persona WHERE p_rut='$rut'";
        $result = $this->sql_con->query($consulta);
        $total = $result->num_rows;
        $this->sql_con->close();
        return ($total == 0) ? true : false;
    }
    public function emailValidation($email)
    {
        $hosteo = new Host(1);
        $this->set_conexion($hosteo->datos['host'], $hosteo->datos['user'], $hosteo->datos['pass'], $hosteo->datos['bd']);
        $consulta = "SELECT * FROM persona WHERE p_email='$email'";
        $result = $this->sql_con->query($consulta);
        $total = $result->num_rows;
        $this->sql_con->close();
        return ($total == 0) ? true : false;
    }
    public function getAvisosUser($u)
    {
        $hosteo = new Host(1);
        $this->set_conexion($hosteo->datos['host'], $hosteo->datos['user'], $hosteo->datos['pass'], $hosteo->datos['bd']);
        $consulta = "SELECT * FROM aviso a INNER JOIN persona p ON p.p_id = a.p_id WHERE a.p_id=$u ORDER BY a.av_fecha DESC";
        $result = $this->sql_con->query($consulta);
        $dato['success'] = false;
        $dato['desc'] = 'Database error';
        if (!$result) {
            trigger_error('Ha  ocurrido un error');
            $this->sql_con->close();
        } else {
            $dato['success'] = true;
            $dato['desc'] = 'Query properly';
            $dato['avisos'] = array();
            while ($row = mysqli_fetch_array($result)) {
                $fila['id'] = $row['av_id'];
                $fila['titulo'] = $row['av_titulo'];
                $fila['desc'] = $row['av_desc'];
                $fila['price'] = $row['av_price'];
                $fila['fecha'] = $row['av_fecha'];
                $fila['estado'] = $row['av_estado_id'];
                $fila['nombre'] = $row['p_nombre'];
                $fila['apellido'] = $row['p_apellido'];
                $fila['email'] = $row['p_email'];
                $fila['telefono'] = $row['p_celular'];
                $fila['rut'] = $row['p_rut'];

                array_push($dato['avisos'], $fila);
            }
            $this->sql_con->close();

        }
        array_push($this->datos, $dato);
    }
    public function getAvisosUserStats($u)
    {
        $hosteo = new Host(1);
        $this->set_conexion($hosteo->datos['host'], $hosteo->datos['user'], $hosteo->datos['pass'], $hosteo->datos['bd']);
        $consulta = "SELECT * FROM aviso a INNER JOIN persona p ON p.p_id = a.p_id WHERE a.p_id=$u ORDER BY a.av_fecha DESC";
        $result = $this->sql_con->query($consulta);
        $dato['success'] = false;
        $dato['desc'] = 'Database error';
        if (!$result) {
            trigger_error('Ha  ocurrido un error');
            $this->sql_con->close();
        } else {
            $dato['success'] = true;
            $dato['desc'] = 'Query properly';
            $dato['avisos'] = array();
            while ($row = mysqli_fetch_array($result)) {
                $fila['id'] = $row['av_id'];
                $fila['titulo'] = $row['av_titulo'];
                $fila['desc'] = $row['av_desc'];
                $fila['price'] = $row['av_price'];
                $fila['fecha'] = $row['av_fecha'];
                $fila['estado'] = $row['av_estado_id'];
                $fila['nombre'] = $row['p_nombre'];
                $fila['apellido'] = $row['p_apellido'];
                $fila['email'] = $row['p_email'];
                $fila['telefono'] = $row['p_celular'];
                $fila['rut'] = $row['p_rut'];

                array_push($dato['avisos'], $fila);
            }
            $this->sql_con->close();

        }
        array_push($this->datos, $dato);
    }
    public function getAvisosSolicitadosTerceros($u)
    {
        $hosteo = new Host(1);
        $this->set_conexion($hosteo->datos['host'], $hosteo->datos['user'], $hosteo->datos['pass'], $hosteo->datos['bd']);
        $consulta = "SELECT s.sol_fecha as fecha, a.av_titulo as titulo, s.sol_state as estado, p.p_id as id_solicitante, p.p_nombre as nombre_solicitante, p.p_apellido as apellido_solicitante,  p.p_celular as celular  FROM solicitud s INNER JOIN aviso a ON a.av_id=s.av_id INNER JOIN persona p ON p.p_id=s.p_id WHERE s.p_id!=$u AND a.p_id=$u ORDER BY s.sol_fecha DESC";
        $result = $this->sql_con->query($consulta);
        $dato['success'] = false;
        $dato['desc'] = 'Database error';
        if (!$result) {
            trigger_error('Ha  ocurrido un error');
            $this->sql_con->close();
        } else {
            $dato['success'] = true;
            $dato['desc'] = 'Query properly';
            $dato['avisos'] = array();
            while ($row = mysqli_fetch_array($result)) {
                $fila['fecha'] = $row['fecha'];
                $fila['titulo'] = $row['titulo'];
                $fila['estado'] = $row['estado'];
                $fila['id_solicitante'] = $row['id_solicitante'];
                $fila['nombre_solicitante'] = $row['nombre_solicitante'];
                $fila['apellido_solicitante'] = $row['apellido_solicitante'];
                $fila['celular'] = $row['celular'];

                array_push($dato['avisos'], $fila);
            }
            $this->sql_con->close();

        }
        array_push($this->datos, $dato);
    }
    public function getAvisosSolicitadosPorMi($u)
    {
        $hosteo = new Host(1);
        $this->set_conexion($hosteo->datos['host'], $hosteo->datos['user'], $hosteo->datos['pass'], $hosteo->datos['bd']);
        $consulta = "SELECT s.sol_fecha as fecha, a.av_titulo as titulo, s.sol_state as estado, p.p_id as id_solicitante, p.p_nombre as nombre_solicitante, p.p_apellido as apellido_solicitante,  p.p_celular as celular  FROM solicitud s INNER JOIN aviso a ON a.av_id=s.av_id INNER JOIN persona p ON p.p_id=a.p_id WHERE s.p_id=$u AND a.p_id!=$u ORDER BY s.sol_fecha DESC";
        $result = $this->sql_con->query($consulta);
        $dato['success'] = false;
        $dato['desc'] = 'Database error';
        if (!$result) {
            trigger_error('Ha  ocurrido un error');
            $this->sql_con->close();
        } else {
            $dato['success'] = true;
            $dato['desc'] = 'Query properly';
            $dato['avisos'] = array();
            while ($row = mysqli_fetch_array($result)) {
                $fila['fecha'] = $row['fecha'];
                $fila['titulo'] = $row['titulo'];
                $fila['estado'] = $row['estado'];
                $fila['id_solicitante'] = $row['id_solicitante'];
                $fila['nombre_solicitante'] = $row['nombre_solicitante'];
                $fila['apellido_solicitante'] = $row['apellido_solicitante'];
                $fila['celular'] = $row['celular'];

                array_push($dato['avisos'], $fila);
            }
            $this->sql_con->close();

        }
        array_push($this->datos, $dato);
    }public function getAvisosSolicitadosPorMiStats($u)
{
    $hosteo = new Host(1);
    $this->set_conexion($hosteo->datos['host'], $hosteo->datos['user'], $hosteo->datos['pass'], $hosteo->datos['bd']);
    $consulta = "SELECT s.sol_fecha as fecha, a.av_titulo as titulo, s.sol_state as estado, p.p_id as id_solicitante, p.p_nombre as nombre_solicitante, p.p_apellido as apellido_solicitante,  p.p_celular as celular  FROM solicitud s INNER JOIN aviso a ON a.av_id=s.av_id INNER JOIN persona p ON p.p_id=a.p_id WHERE s.p_id=$u AND a.p_id!=$u ORDER BY s.sol_fecha DESC";
    $result = $this->sql_con->query($consulta);
    $dato['success'] = false;
    $dato['desc'] = 'Database error';
    if (!$result) {
        trigger_error('Ha  ocurrido un error');
        $this->sql_con->close();
    } else {
        $dato['success'] = true;
        $dato['desc'] = 'Query properly';
        $dato['avisos'] = array();
        while ($row = mysqli_fetch_array($result)) {
            $fila['fecha'] = $row['fecha'];
            $fila['titulo'] = $row['titulo'];
            $fila['estado'] = $row['estado'];
            $fila['id_solicitante'] = $row['id_solicitante'];
            $fila['nombre_solicitante'] = $row['nombre_solicitante'];
            $fila['apellido_solicitante'] = $row['apellido_solicitante'];
            $fila['celular'] = $row['celular'];

            array_push($dato['avisos'], $fila);
        }
        $this->sql_con->close();

    }
    array_push($this->datos, $dato);
}
    public function saveAntecedentes($a_cliente, $a_direccion, $a_fecha, $a_proyecto, $a_tipo, $a_hora_inicio, $a_hora_fin, $a_encargado_gestsol, $a_encargado_electrico, $a_encargado_cliente, $a_patente, $a_marca, $a_carroceria, $a_numero_vehiculo, $a_modelo, $a_flota)
    {
        $hosteo = new Host(4);
        $this->set_conexion($hosteo->datos['host'], $hosteo->datos['user'], $hosteo->datos['pass'], $hosteo->datos['bd']);
        $consulta = "INSERT INTO antecedentes(a_cliente, a_direccion, a_fecha, a_proyecto, a_tipo, a_hora_inicio, a_hora_fin, a_encargado_gestsol, a_encargado_electrico, a_encargado_cliente, a_patente, a_marca, a_carroceria, a_numero_vehiculo, a_modelo, a_flota) VALUES ('$a_cliente', '$a_direccion', '$a_fecha', '$a_proyecto', '$a_tipo', '$a_hora_inicio', '$a_hora_fin', '$a_encargado_gestsol', '$a_encargado_electrico', '$a_encargado_cliente', '$a_patente', '$a_marca', '$a_carroceria', '$a_numero_vehiculo', '$a_modelo', '$a_flota')";
        $result = $this->sql_con->query($consulta);
        $dato['success'] = false;
        $dato['desc'] = 'Database error';
        if (!$result) {
            trigger_error('Ha  ocurrido un error');
            $this->sql_con->close();
        } else {
            if ($this->sql_con->affected_rows == 0) {
                $dato['success'] = false;
                $dato['desc'] = 'Query error';
            } else {
                $dato['success'] = true;
                $dato['desc'] = 'Stored properly';

            }

            $this->sql_con->close();
        }
        array_push($this->datos, $dato);
    }
    public function setServicio($u,$t,$c,$d,$p,$lat,$lon)
    {
        $hosteo = new Host(1);
        $this->set_conexion($hosteo->datos['host'], $hosteo->datos['user'], $hosteo->datos['pass'], $hosteo->datos['bd']);
        $consulta = "INSERT INTO aviso(av_id, av_titulo, av_desc, av_price, av_fecha, av_estado_id, cat_id, p_id,av_lat,av_lon) VALUES (NULL,'$t','$d',$p,NOW(),1,$c,$u,$lat,$lon)";
        $result = $this->sql_con->query($consulta);
        $dato['success'] = false;
        $dato['desc'] = 'Database error';
        $dato['query'] = $consulta;
        if (!$result) {
            trigger_error('Ha  ocurrido un error');
            $this->sql_con->close();
        } else {
            if ($this->sql_con->affected_rows == 0) {
                $dato['success'] = false;
                $dato['desc'] = 'Query error';
            } else {
                $dato['success'] = true;
                $dato['desc'] = 'Stored properly';

            }

            $this->sql_con->close();
        }
        array_push($this->datos, $dato);
    }
    public function setServicioStats($u,$t,$c,$d,$p,$lat,$lon)
    {
        $hosteo = new Host(1);
        $this->set_conexion($hosteo->datos['host'], $hosteo->datos['user'], $hosteo->datos['pass'], $hosteo->datos['bd']);
        $consulta = "INSERT INTO aviso(av_id, av_titulo, av_desc, av_price, av_fecha, av_estado_id, cat_id, p_id,av_lat,av_lon) VALUES (NULL,'$t','$d',$p,NOW(),1,$c,$u,$lat,$lon)";
        $result = $this->sql_con->query($consulta);
        $dato['success'] = false;
        $dato['desc'] = 'Database error';
        $dato['query'] = $consulta;
        if (!$result) {
            trigger_error('Ha  ocurrido un error');
            $this->sql_con->close();
        } else {
            if ($this->sql_con->affected_rows == 0) {
                $dato['success'] = false;
                $dato['desc'] = 'Query error';
            } else {
                $dato['success'] = true;
                $dato['desc'] = 'Stored properly';

            }

            $this->sql_con->close();
        }
        array_push($this->datos, $dato);
    }
    public function setChat($ori,$dest,$msg)
    {
        $hosteo = new Host(1);
        $this->set_conexion($hosteo->datos['host'], $hosteo->datos['user'], $hosteo->datos['pass'], $hosteo->datos['bd']);
        $consulta = "INSERT INTO mensaje(m_id, p_id_ori, p_id_dest, p_mensaje, p_fecha) VALUES (NULL,$ori,$dest,'$msg',NOW())";
        $result = $this->sql_con->query($consulta);
        $dato['success'] = false;
        $dato['desc'] = 'Database error';
        $dato['query'] = $consulta;
        if (!$result) {
            trigger_error('Ha  ocurrido un error');
            $this->sql_con->close();
        } else {
            if ($this->sql_con->affected_rows == 0) {
                $dato['success'] = false;
                $dato['desc'] = 'Query error';
            } else {
                $dato['success'] = true;
                $dato['desc'] = 'Stored properly';

            }

            $this->sql_con->close();
        }
        array_push($this->datos, $dato);
    }
    public function setSolicitud($pid,$avid)
    {
        $hosteo = new Host(1);
        $this->set_conexion($hosteo->datos['host'], $hosteo->datos['user'], $hosteo->datos['pass'], $hosteo->datos['bd']);
        $consulta = "INSERT INTO solicitud(sol_id, sol_fecha, p_id, av_id) VALUES (NULL, NOW(), $pid, $avid)";
        $result = $this->sql_con->query($consulta);
        $dato['success'] = false;
        $dato['desc'] = 'Database error';
        $dato['query'] = $consulta;
        if (!$result) {
            trigger_error('Ha  ocurrido un error');
            $this->sql_con->close();
        } else {
            if ($this->sql_con->affected_rows == 0) {
                $dato['success'] = false;
                $dato['desc'] = 'Query error';
            } else {
                $dato['success'] = true;
                $dato['desc'] = 'Stored properly';

            }

            $this->sql_con->close();
        }
        array_push($this->datos, $dato);
    }
    public function deleteAviso($avid)
    {
        $hosteo = new Host(1);
        $this->set_conexion($hosteo->datos['host'], $hosteo->datos['user'], $hosteo->datos['pass'], $hosteo->datos['bd']);
        $consulta = "DELETE FROM aviso WHERE av_id=$avid";
        $result = $this->sql_con->query($consulta);
        $dato['success'] = false;
        $dato['desc'] = 'Database error';
        $dato['query'] = $consulta;
        if (!$result) {
            trigger_error('Ha  ocurrido un error');
            $this->sql_con->close();
        } else {
            if ($this->sql_con->affected_rows == 0) {
                $dato['success'] = false;
                $dato['desc'] = 'Query error';
            } else {
                $dato['success'] = true;
                $dato['desc'] = 'Stored properly';

            }

            $this->sql_con->close();
        }
        array_push($this->datos, $dato);
    }
    public function getCategorias()
    {
        $hosteo = new Host(1);
        $this->set_conexion($hosteo->datos['host'], $hosteo->datos['user'], $hosteo->datos['pass'], $hosteo->datos['bd']);
        $consulta = 'SELECT * FROM categoria ORDER BY cat_desc ASC';
        $result = $this->sql_con->query($consulta);
        $dato['success'] = false;
        $dato['desc'] = 'Database error';
        if (!$result) {
            trigger_error('Ha  ocurrido un error');
            $this->sql_con->close();
        } else {
            $dato['success'] = true;
            $dato['desc'] = 'Query properly';
            $dato['categorias'] = array();
            while ($row = mysqli_fetch_array($result)) {
                $fila['id'] = $row['cat_id'];
                $fila['titulo'] = $row['cat_desc'];
                array_push($dato['categorias'], $fila);
            }
            $this->sql_con->close();

        }
        array_push($this->datos, $dato);
    }
    public function getState($u)
    {
        $hosteo = new Host(1);
        $this->set_conexion($hosteo->datos['host'], $hosteo->datos['user'], $hosteo->datos['pass'], $hosteo->datos['bd']);
        $consulta = "SELECT COALESCE(p_estado,1) as state FROM persona WHERE p_id=$u";
        $result = $this->sql_con->query($consulta);
        $dato['success'] = false;
        $dato['desc'] = 'Database error';
        $dato['state'] = 0;
        if (!$result) {
            trigger_error('Ha  ocurrido un error');
            $this->sql_con->close();
        } else {
            $dato['success'] = true;
            $dato['desc'] = 'Query properly';
            $dato['data'] = array();
            while ($row = mysqli_fetch_array($result)) {
                $dato['state'] = $row['state'];
            }
            $this->sql_con->close();

        }
        array_push($this->datos, $dato);
    }
    public function setState($u,$state)
    {
        $hosteo = new Host(1);
        $this->set_conexion($hosteo->datos['host'], $hosteo->datos['user'], $hosteo->datos['pass'], $hosteo->datos['bd']);
        $consulta = "UPDATE persona SET p_estado=$state WHERE p_id=$u";
        $result = $this->sql_con->query($consulta);
        $dato['success'] = false;
        $dato['desc'] = 'Database error';
        if (!$result) {
            trigger_error('Ha  ocurrido un error');
            $this->sql_con->close();
        } else {
            $dato['success'] = true;
            $dato['desc'] = 'Query properly';
            $dato['data'] = array();

            if ($this->sql_con->affected_rows == 0) {
                $dato['success'] = false;
                $dato['desc'] = 'Query error';
            } else {
                $dato['success'] = true;
                $dato['desc'] = 'Stored properly';

            }
            $this->sql_con->close();

        }
        array_push($this->datos, $dato);
    }

    public function doLogin($correo, $pass)
    {
        $hosteo = new Host(1);
        $this->set_conexion($hosteo->datos['host'], $hosteo->datos['user'], $hosteo->datos['pass'], $hosteo->datos['bd']);
        $consulta = "SELECT * FROM persona WHERE p_email='$correo' AND p_clave=md5('$pass')";
        $result = $this->sql_con->query($consulta);
        $dato['success'] = false;
        $dato['desc'] = 'Database error';
        if (!$result) {
            trigger_error('Ha  ocurrido un error');
            $this->sql_con->close();
        } else {
            $dato['success'] = false;
            $dato['desc'] = 'No existe el usuario';
            while ($row = mysqli_fetch_array($result)) {
                $dato['id'] = $row['p_id'];
                $dato['nombre'] = $row['p_nombre'];
                $dato['apellido'] = $row['p_apellido'];
                $dato['mail'] = $row['p_email'];
                $dato['celular'] = $row['p_celular'];
                $dato['rut'] = $row['p_rut'];
                $dato['numero_calle'] = $row['p_numero_calle'];
                $dato['calle'] = $row['p_calle'];
                $dato['localidad'] = $row['p_localidad'];
                $dato['region'] = $row['p_region'];
                $dato['pais'] = $row['p_pais'];
                $dato['success'] = true;
                $dato['desc'] = 'Usuario encontrado';

            }
            $this->sql_con->close();

        }
        array_push($this->datos, $dato);
    }

    public function getLastMensajes($dest)
    {
        $hosteo = new Host(1);
        $this->set_conexion($hosteo->datos['host'], $hosteo->datos['user'], $hosteo->datos['pass'], $hosteo->datos['bd']);
        $consulta = "SELECT a.p_id_ori as origen_id, po.p_nombre as ori_nombre , po.p_apellido as ori_apellido, a.p_mensaje as mensaje,a.p_fecha as fecha
                        FROM mensaje a
                        INNER JOIN
                        (
                            SELECT p_id_ori, p_id_dest, MAX(p_fecha) AS MaxSentDate
                            FROM mensaje
                            GROUP BY p_id_ori, p_id_dest
                        ) b
                        ON a.p_id_ori = b.p_id_ori
                        AND a.p_id_dest = b.p_id_dest
                        AND a.p_fecha = b.MaxSentDate
                        INNER JOIN persona po on po.p_id=a.p_id_ori
                        INNER JOIN persona pd on pd.p_id=a.p_id_dest
                        WHERE a.p_id_dest=$dest
                        ORDER BY a.p_fecha DESC";
        $result = $this->sql_con->query($consulta);
        $dato['success'] = false;
        $dato['dest'] = $dest;
        $dato['desc'] = 'Database error';
        if (!$result) {
            trigger_error('Ha  ocurrido un error');
            $this->sql_con->close();
        } else {
            $dato['success'] = true;
            $dato['desc'] = 'Query properly';
            $dato['mensajes'] = array();
            while ($row = mysqli_fetch_array($result)) {
                $fila['id'] = $row['origen_id'];
                $fila['nombre'] = $row['ori_nombre'];
                $fila['apellido'] = $row['ori_apellido'];
                $fila['mensaje'] = $row['mensaje'];
                $fila['fecha'] = $row['fecha'];

                array_push($dato['mensajes'], $fila);
            }
            $this->sql_con->close();

        }
        array_push($this->datos, $dato);
    }
    public function getChat($ori, $dest)
    {
        $hosteo = new Host(1);
        $this->set_conexion($hosteo->datos['host'], $hosteo->datos['user'], $hosteo->datos['pass'], $hosteo->datos['bd']);
        $consulta = "SELECT * FROM `mensaje` WHERE (p_id_ori=$ori OR p_id_dest=$ori) AND (p_id_ori=$dest OR p_id_dest=$dest) ORDER BY p_fecha ASC";
        $result = $this->sql_con->query($consulta);
        $dato['success'] = false;
        $dato['desc'] = 'Database error';
        if (!$result) {
            trigger_error('Ha  ocurrido un error');
            $this->sql_con->close();
        } else {
            $dato['success'] = true;
            $dato['desc'] = 'Query properly';
            $dato['mensajes'] = array();
            while ($row = mysqli_fetch_array($result)) {
                $fila['ori'] = $row['p_id_ori'];
                $fila['dest'] = $row['p_id_dest'];
                $fila['msg'] = $row['p_mensaje'];
                $fila['fecha'] = $row['p_fecha'];
                array_push($dato['mensajes'], $fila);
            }
            $this->sql_con->close();

        }
        array_push($this->datos, $dato);
    }
    public function __destruct()
    {
        echo json_encode($this->datos, JSON_NUMERIC_CHECK);
    }
}

?>