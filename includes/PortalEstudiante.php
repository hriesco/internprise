<?php

namespace es\ucm\aw\internprise;


class PortalEstudiante extends Portal
{

    public function __construct()
    {
        parent::__construct("Estudiante");
    }

    /**
     * Función que genera un menú lateral.
     */
    public function generaMenu()
    {
        $bloqueEstudianteSideBar = <<<EOF
        <!-- Fragmento para definir el menú de estudiante-->
        <div id="estudiante-sidebar" class="sidebar">
            <div id="estudiante-menu-avatar" class="menu-avatar">
                <img src="img/estudiante-avatar.png" alt="Avatar image" width="100%"></img>
            </div>
                <ul>
                    <li><a onclick="return loadContent('PERFIL', 'Perfil')" href="#">PERFIL</a></li>
                    <li><a onclick="return loadContent('OFERTAS', 'Ofertas')" href="#">OFERTAS</a></li>
                    <li><a onclick="return loadContent('BUZON', 'Buzon')" href="#">BUZÓN</a></li>
                </ul>
        </div>
EOF;
        return $bloqueEstudianteSideBar;
    }

    /**
     * Función que genera los encabezados de la página.
     */
    public function generaHead()
    {
        $titulo = "Internprise - Portal Estudiante";
        $imagen = "img/favicon-estudiante.png";
        return parent::generaHeadParam($titulo, $imagen);
    }

    /**
     * Función que genera el contenido de la página principal del portal.
     * El resto de contenido debe generarse por medio de peticiones AJAX.
     */
 public function generaDashboard()
    {
       $widgets="";
       $buscador = <<<EOF
       <div class="dashboard-content">
           <!-- INI Contenedor Widgets superior -->
           <div class="widget-content">
EOF;

        //TIPOS DE ICONOS PARA LOS WIDGETS: envelope-o,check-circle,caret-square-o-down,commenting-o (VER FONTAWESOME ICONS)

        /*Generar contenido widget Ofertas */
        $widgets .= "<!-- INI Widget Ofertas activos -->";
        $ofertas = OfertaDAO::cargasOfertasEstudiante(null);
        $listaOfertas = array();
        foreach ( $ofertas as $oferta) {
            $titleItem = $oferta->getEmpresa();
            $subtitleItem = $oferta->getPuesto();
            $dias = $oferta->getDiasDesdeCreacion();
            if ($dias == 0) {$description = "Hoy";} else if ($dias == 1) {$description = "Ayer";} else {$description ="Hace " . $dias . " dias";};
            $item = array($titleItem,$subtitleItem,$description);
            array_push($listaOfertas,$item);
        }
        $widgets .= parent::generarWidget("Nuevas ofertas", $listaOfertas,"envelope-o","blue");
        $widgets .= "<!-- FIN Widget Ofertas activos -->";

        /*Generar contenido widget Novedades */
        $widgets .= "<!-- INI Widget Contratos activos -->";
        //TODO:Implementar Contrato model & ContratoDAO
        //$contratos = ContratoDAO::cargaTodosContratosActivos();
        $novedades = array();
        $novedades = array();
        foreach ( $novedades as $contrato) {

        }
        $widgets .= parent::generarWidget("Novedades", $novedades,"check-circle","green");
        $widgets .= "<!-- FIN Widget Contratos activos -->\n<!-- FIN Contenedor widgets superior -->";

        $content = $buscador . $widgets;
        $content .= "</div>";
        $content .= "</div>";
       return $content;
    }
    

    public function generaTitlebar()
    {
        return parent::generaTitlebarParam("Internprise Estudiante");

    }

    public function generaPerfil($id_estudiante){
        //TODO: Implementar funcionalidad Avatar

        $app = Aplicacion::getSingleton();

        $user = UsuarioDAO::cargaEstudiante($id_estudiante);

        $nombre = $user->getNombre() . " " . $user->getApellidos();
        $email = $user->getEmail();
        $descripcion = $user->getDescripcion();
        $localizacion = $user->getLocalizacion();
        $experiencia = $user->getExperiencia();
        $estudios = $user->getEstudios();
        $idiomas = $user->getIdiomas();
        $cursos = $user->getCursos();
        $telefono_fijo = $user->getTelefonoFijo();
        $telefono_movil = $user->getTelefonoMovil();
        $redesSociales = $user->getRedesSociales();
        $web = $user->getWeb();
        $avatar = $user->getAvatar();

        //Bloque contacto
        $bloqueContacto ="<div class='col-md-4'>".
                         "<h2><i class='fa fa-envelope'></i> <a href='mailto:$email'> Email</a></h2>";
        if(!empty(trim($web))) {
            $bloqueContacto .="<h2><a href='$web' class='web'><i class='fa fa-globe'></i> Website</a></h2>";
        }
        $bloqueContacto .="</div>";
        $bloqueContacto .=" <div class='col-md-4 contact-email'>";
        if(!empty(trim($telefono_movil))) {
            $bloqueContacto .="<h3><i class='fa fa-mobile'></i> $telefono_movil</h3>";
        }
        if(!empty(trim($telefono_movil))) {
            $bloqueContacto .="<h3><i class='fa fa-phone'></i> $telefono_fijo</h3>";
        }
        $bloqueContacto .="</div>".
                          "<div class='col-md-4'>";
        $socialCounter = 0;
        for($x = 0;$x < 4; $x++){
            if(!empty(trim($redesSociales[$x]))){
                if($socialCounter==0) {
                    $bloqueContacto .= "<div class='row contact row-first'>";
                }
                elseif ($socialCounter==2){
                    $bloqueContacto .= "<div class='row contact row-second'>";
                }
                $bloqueContacto .="<div class='col-md-6'>";
                switch($x){
                    case 0: {
                        $bloqueContacto .= "<a href='skype:$redesSociales[$x]?call' class='skype'><i class='fa fa-skype'></i>Skype</a>";
                    }break;
                    case 1: {
                        $bloqueContacto .= "<a href='$redesSociales[$x]' class='google'><i class='fa fa-google-plus'></i> Google+</a>";
                    }break;
                    case 2: {
                        $bloqueContacto .= "<a href='$redesSociales[$x]' class='linkedin'><i class='fa fa-linkedin'></i> LinkedIn</a>";
                    }break;
                    case 3: {
                        $bloqueContacto .= "<a href='$redesSociales[$x]' class='twitter'><i class='fa fa-twitter'></i> Twitter</a>";
                    }
                }
                $bloqueContacto .="</div>";

                if ($socialCounter==1) {
                    $bloqueContacto .= "</div>";
                } elseif ($socialCounter==3) {
                    $bloqueContacto .= "</div>";
                }
                else if($x == 3){
                    $bloqueContacto .= "</div>";
                }
                $socialCounter++;
            }
        }


        $content = <<<EOF
        <div class="container">
        <div class="row">
            <div id="imagen-estudiante" class="col-sm-3">
                <IMG SRC="img/estudiante-avatar.png" class="img-rounded" alt="Avatar" width="200" height="200">
            </div>      
            <div class="col-sm-8">;       
                <h1 ><strong>$nombre</strong></h1>
                <h3>$descripcion</h3>
                <p>$localizacion</p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
            <div class="text-center"><h1>Experiencia</h1></div>                          
                <table class="table table-hover ">
                    <tr><td><strong>Puesto</strong></td><td><strong>Duración (meses) </strong></td></tr>
EOF;
        foreach ($experiencia as $row){
            if(!empty(trim($row[0]))){
                $content .= "<tr><td>$row[0]</td><td>$row[1]</td></tr>";
            }
        }
        $content .= <<<EOF
                </table>
            </div>
            <div class="col-sm-6">
            <div class="text-center""><h1>Estudios</h1></div>
                <table class="table table-hover ">
                <tr><td><strong>Título</strong></td><td><strong>Centro de impartición</strong></td></tr>
EOF;
        foreach ($estudios as $row){
            if(!empty(trim($row[0]))){
                $content .= "<tr><td>$row[0]</td><td>$row[1]</td></tr>";
            }
        }
        $content .= <<<EOF
                </table>
             </div>
         </div>
         <div class="row">
            <div class="col-sm-6">
                <div class="text-center""><h1>Idiomas</h1></div>
                     <table class="table table-hover ">
                     <tr><td><strong>Idioma</strong></td><td><strong>Nivel</strong></td></tr>
                     
EOF;
        foreach ($idiomas as $row){
            if(!empty(trim($row[0]))){
                $content .= "<tr><td>$row[0]</td><td>$row[1]</td></tr>";
            }
        }
        $content .= <<<EOF
                </table>
             </div>
                     <div class="col-sm-6">
            <div class="text-center""><h1>Cursos</h1></div>
                <table class="table table-hover ">
                <tr><td><strong>Título</strong></td><td><strong>Duración (horas) </strong></td></tr>
EOF;
        foreach ($cursos as $row){
            if(!empty(trim($row[0]))){
                $content .= "<tr><td>$row[0]</td><td>$row[1]</td></tr>";
            }
        }
        //TODO:Implementar funcionalidad aptitudes
        $content .= <<<EOF
                </table>
             </div>
         </div> 
        <div class="row">
            <div class="text-left"><h1>Aptitudes</h1>
                <div class="aptitudes">
                    <button class="btn btn-primary">SQL Server 2008</button>
                    <button class="btn btn-primary">PL/SQL</button>
                    <button class="btn btn-primary">R</button>
                    <button class="btn btn-primary">Python</button>
                    <button class="btn btn-primary">Microsoft Excel</button>
                    <button class="btn btn-primary">Hadoop</button>
                    <button class="btn btn-primary">SAS</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="text-left"><h1>Contacto</h1></div>      
        </div>
        <div class="well well-sm quick-contact">
        <div class="row">
            $bloqueContacto
         </div>
        </div>
    </div>              
EOF;
        return $content;
    }

    public function generaOfertas(){
        $ofertas = OfertaDAO::cargasOfertasEstudiante(20);
        $listaOfertas = array();
        $listaIds = array();
        foreach ( $ofertas as $oferta) {
            $empresa = $oferta ->getEmpresa();
            $puesto = $oferta->getPuesto();
            $sueldo = $oferta->getSueldo();
            $horas = $oferta->getHoras();
            $plazas = $oferta->getPlazas();
            $fila = array($empresa,$puesto,$sueldo, $horas, $plazas);
            array_push($listaOfertas,$fila);
            array_push($listaIds, $oferta->getIdOferta());
        }

        $titulosColumnas = array("Empresa","Puesto", "Sueldo", "Horas", "Plazas");
        $content = self::generaTabla("tabla-ofertas", "estudiante-table",
            "Ofertas disponibles", $titulosColumnas, $listaOfertas, $listaIds, 'oferta');

        return $content;
    }

    public function generaDialogoOferta($idOferta){
        $oferta = OfertaDAO::cargaOferta($idOferta);
        if($oferta) {
            $id = $oferta->getIdOferta();
            $empresa = $oferta->getEmpresa();
            $puesto = $oferta->getPuesto();
            $sueldo = $oferta->getSueldo();
            $fecha_inicio = $oferta->getFechaInicio();
            $fecha_fin = $oferta->getFechaFin();
            $horas = $oferta->getHoras();
            $plazas = $oferta->getPlazas();
            $descripcion = $oferta->getDescripcion();
            $aptitudes = $oferta->getAptitudes();
            $reqMinimos = $oferta->getReqMinimos();
            $idiomas = $oferta->getIdiomas();
            $reqDeseables = $oferta->getReqDeseables();
            $estado = $oferta->getEstado();
            $diasDesdeCreacion = $oferta->getDiasDesdeCreacion();
            $content = <<<EOF
    <!-- Modal dialog oferta -->
        <div id='estudiante-modal-content' class="dialogo-modal-content">
            <div id='estudiante-modal-header' class="dialogo-modal-header">
                <span class="close">×</span>
                <h2>Oferta</h2>
            </div>
            <div class="dialogo-modal-body">
                <p>Id: $id</p>
                <p>Empresa: $empresa</p>
                <p>Puesto: $puesto</p>
                <p>Sueldo: $sueldo</p>
                <p>Fecha inicio: $fecha_inicio</p>
                <p>Fecha fin: $fecha_fin </p>
                <p>Horas: $horas</p>
                <p>Plazas: $plazas</p>
                <p>Descripción: $descripcion</p>
                <p>Aptitudes: $aptitudes</p>
                <p>Requisitos minimos: $reqMinimos</p>
                <p>Idiomas: $idiomas</p>
                <p>Requisitos deseables: $reqDeseables</p>
                <p>Estado: $estado</p>
                <p>Días desde la creación: $diasDesdeCreacion</p>
            </div>
            <div id='estudiante-modal-footer' class="dialogo-modal-footer">
                <button id='aceptar-btn' type="button" class="btn btn-info">Solicitar</button>
            </div>
        </div>
EOF;
        }
        else{
            $content = <<<EOF
            <h1 style="color:red">Fallo al cargar la oferta</h1>
EOF;

        }

        return $content;
    }

    public function generaBuzon(){
        // TODO: Implement generaBuzon() method.
    }

    public function generaSettings(){

        $formAdmin =  new \es\ucm\aw\internprise\FormularioSettings('estudiante');
        $formAdmin->gestiona();
    }
    
    

}



