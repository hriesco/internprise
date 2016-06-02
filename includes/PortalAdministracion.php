<?php


namespace es\ucm\aw\internprise;


class PortalAdministracion extends Portal
{
    public function __construct() {
        parent::__construct("Admin");
    }

    public function generaMenu()
    {
        $bloqueAdminSideBar = <<<EOF
        <!-- Fragmento para definir el menú de administrador-->
        <div id="admin-sidebar" class="sidebar">
                <ul>
                    <li onmouseenter="subMenu(true, 'sub-menu-ofertas')" onmouseleave="subMenu(false, 'sub-menu-ofertas')">
                        <a onclick="subMenu(true, 'sub-menu-ofertas')" href="#">OFERTAS</a>
                        <div id="sub-menu-ofertas" class="sub-menu">
                            <ul>
                                <li><a onclick="return loadContent('OFERTAS_CLASIFICADAS', 'Ofertas clasificadas')" href="#">Clasificadas</a></li>
                                <li><a onclick="return loadContent('OFERTAS_NO_CLASIFICADAS', 'Ofertas no clasificadas')" href="#">No clasificadas</a></li>
                            </ul>
                        </div>
                    </li>
                    <li onmouseenter="subMenu(true, 'sub-menu-demandas')" onmouseleave="subMenu(false, 'sub-menu-demandas')">
                        <a onclick="subMenu(true, 'sub-menu-demandas')" href="#">DEMANDAS</a>
                        <div id="sub-menu-demandas" class="sub-menu">
                            <ul>
                                <li><a onclick="return loadContent('DEMANDAS_CLASIFICADAS', 'Demandas clasificadas')" href="#">Clasificadas</a></li>
                                <li><a onclick="return loadContent('DEMANDAS_NO_CLASIFICADAS', 'Demandas no clasfificadas')" href="#">No clasificadas</a></li>
                            </ul>
                        </div>
                    </li>
                    <li><a onclick="return loadContent('CONTRATOS', 'Contratos')" href="#">VER CONTRATOS</a></li>
                    <li><a onclick="return loadContent('HISTORIAL', 'Historial')" href="#">HISTORIAL</a></li>
                    <li><a onclick="return loadContent('ENCUESTAS', 'Encuestas')" href="#">ENCUESTAS</a></li>
                    <li><a onclick="return loadContent('BUZON', 'Buzon')" href="#">BUZÓN</a></li>
                </ul>
        </div>
EOF;
        return $bloqueAdminSideBar;
    }
    
    /**
     * Función que genera los encabezados de la página.
     */
    public function generaHead()
    {
        $titulo = "Internprise - Portal Administracion";
        $imagen = "img/favicon-admin.png";
        return parent::generaHeadParam($titulo,$imagen);
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
       		
           <!-- INI Contenedor busqueda dashboard -->
           <div class="btn-search">
               <a class="icon-search" href="#">
                   <i class="fa fa-search fa-2x" style="color:#444;"></i>
               </a>
               <div class="txt-search">
                   <form method="post" action="#" accept-charset="utf-8">
                       <input id="busqueda" name="busqueda" value="" autocomplete="off" maxlength="30" class="txt-search" type="text" placeholder="Buscador de estudiantes / empresas">
                   </form>
               </div>
           </div>
           <!-- FIN Contenedor busqueda dashboard -->

           <!-- INI Contenedor Widgets superior -->
           <div class="widget-content">
EOF;

        //TIPOS DE ICONOS PARA LOS WIDGETS: envelope-o,check-circle,caret-square-o-down,commenting-o (VER FONTAWESOME ICONS)

        /*Generar contenido widget Ofertas */
        $widgets .= "<!-- INI Widget Ofertas activos -->";
        $ofertas = OfertaDAO::cargaOfertasNoClasificadas(null,null);
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

        /*Generar contenido widget Contratos */
        $widgets .= "<!-- INI Widget Contratos activos -->";
        //TODO:Implementar Contrato model & ContratoDAO
        //$contratos = ContratoDAO::cargaTodosContratosActivos();
        $contratos = array();
        $listaContratos = array();
        foreach ( $contratos as $contrato) {
        }
        $widgets .= parent::generarWidget("Contratos", $listaContratos,"check-circle","green");
        $widgets .= "<!-- FIN Widget Contratos activos -->\n<!-- FIN Contenedor widgets superior -->";

        $widgets .= "<!-- INI Contenedor widgets inferior -->\n<!-- INI Contenedor widgets inferior -->";

        /*Generar contenido widget Demandas */
        $widgets .= "<!-- INI Widget Nuevas demandas -->";
        //TODO:Implementar Demanda model & DemandaDAO
        //$demandas = DemandaDAO::cargaTodasDemandas();
        $demandas = array();
        $listaDemandas = array();
        foreach ( $demandas as $demanda) {

        }
        $widgets .= parent::generarWidget("Demandas", $listaContratos," fa-caret-square-o-down","#FF800D");
        $widgets .= "<!-- FIN Widget Nuevas demandas -->";

        /*Generar contenido widget Dudas y sugerencias */
        $widgets .= "<!-- INI Widget Dudas y sugerencias -->";
        //TODO:Implementar Sugerencia model & SugerenciaDAO
        //$sugerencias = SugerenciaDAO::cargaTodasSugerencias();
        $sugerencias = array();
        $listaSugerencias = array();
        foreach ( $sugerencias as $sugerencia) {

        }
        $widgets .= parent::generarWidget("Dudas y sugerencias", $listaContratos," fa-commenting-o","#B9264F");
        $widgets .= "<!-- FIN Widget Nuevas demandas -->";

        $content = $buscador . $widgets;
        $content .= "</div>";
        $content .= "</div>";
       return $content;
    }

    public function generaTitlebar()
    {
        return parent::generaTitlebarParam("Internprise Administración");
    }

    public function generaOfertas($clasificadas){
        if ($clasificadas)
            $ofertas = OfertaDAO::cargaOfertasClasificadas(30, null);
        else
            $ofertas = OfertaDAO::cargaOfertasNoClasificadas(30, null);
        $listaOfertas = array();
        $listaIds = array();
        foreach ( $ofertas as $oferta) {
            $id = $oferta->getIdOferta();
            $empresa = $oferta ->getEmpresa();
            $puesto = $oferta->getPuesto();
            $sueldo = $oferta->getSueldo();
            $horas = $oferta->getHoras();
            $plazas = $oferta->getPlazas();
            $estado = $oferta->getEstado();
            $fila = array($empresa, $puesto,$sueldo, $horas, $plazas, $estado);
            array_push($listaOfertas,$fila);
            array_push($listaIds, $id);
        }
        $titulosColumnas = array("Empresa", "Puesto", "Sueldo", "Horas", "Plazas", "Estado");
        $content = self::generaTabla("tabla-oferta","admin-table" ,
            "Ofertas" . (($clasificadas)? ' ' : ' no ') . 'clasificadas',
                $titulosColumnas, $listaOfertas, $listaIds, 'oferta');
        return $content;

    }

    public function generaDemandas(){
        // TODO: Implement generaDemandas() method.
    }

    public function generaContratos(){
        // TODO: Implement generaContratos() method.
    }

    public function generaHistorial(){
        // TODO: Implement generaHistorial() method.
    }

    public function generaEncuestas(){
        // TODO: Implement generaEncuestas() method.
    }

    public function generaBuzon(){
        // TODO: Implement generaBuzon() method.
    }

    public function generaDialogoOferta($idOferta){
        $oferta = OfertaDAO::cargaOferta($idOferta);
        if($oferta) {
            $id = $oferta->getIdOferta();
            $empresa = $oferta->getEmpresa();
            $puesto = $oferta->getPuesto();
            $sueldo = $oferta->getSueldo();
            $fecha_inicio = $oferta->getFechaIncio();
            $fecha_fin = $oferta->getFechaFin();
            $horas = $oferta->getHoras();
            $plazas = $oferta->getPlazas();
            $descripcion = $oferta->getDescripcion();
            $estado = $oferta->getEstado();
            $diasDesdeCreacion = $oferta->getDiasDesdeCreacion();
            $content = <<<EOF
    <!-- Modal dialog oferta -->
        <div id= 'oferta-model-content' class="dialogo-modal-content">
            <div class="dialogo-modal-header">
                <span class="close">×</span>
                <h2>Modal Header</h2>
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
                <p>Estado: $estado</p>
                <p>Días desde la creación: $diasDesdeCreacion</p>
            </div>
            <div class="dialogo-modal-footer">
                <h3>Modal Footer</h3>
            </div>
        </div>
EOF;
        }
        else{
            $content = <<<EOF
            <h1>Fallo al cargar la oferta</h1>
EOF;

        }

    return $content;
    }

    public function generaSettings(){
        $formAdmin =  new \es\ucm\aw\internprise\FormularioSettings('admin');
        $formAdmin->gestiona();
    }
}