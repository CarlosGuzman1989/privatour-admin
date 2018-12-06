<?php
class Host{
    public $hosting;
    public $datos = array();
    public function __construct($server){
        $this->hosting = $server;
        $this->obtenerConexion();
    }

    public function obtenerConexion(){
        switch($this->hosting){
            case '1':
              $this->datos['host'] = 'localhost';
              $this->datos['user'] = 'root';
              $this->datos['pass'] = 'NticPlatform2016';
              $this->datos['bd'] = 'privatour_admin';
              break;
            default:
              $this->datos['host'] = 'localhost';
              $this->datos['user'] = 'root';
              $this->datos['pass'] = 'NticPlatform2016';
              $this->datos['bd'] = 'privatour_admin';
            break;
            
        }
    }
}
?>