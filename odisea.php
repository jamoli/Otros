<?php
 /**
 * REST API Odisea. 
 * Impelmentación de los métodos de iteracción con Odisea
 *
 * @category   Odisea
 * @package    api
 * @author     Juan Antonio Latorre Molina <ja.latorre@cotronic.es>
 * @copyright  2014 Sielte - Cotronic
 * @version    1.0.1
 */
class Odisea extends Api
{
	/**
 	* Cabeceras a enviar	
 	* var string
 	*/
        public $header;
	
	
	/**
	* Constructor de la clase. Inicializa los datos de login de eagora
	* @param  null        
        *
	**/
	public function __construct()
	{
		parent::$this->headers  = $this->headerOdisea;  //Incializar los headers para Odisea
        }
	
	
	/**
	* Descarga el fichero xml de las actuaciones en Odisea de la $provincia en el $sistema indicado entre las fechas $desde y $hasta
	* 
	* @param  $sistema  	MA(03): VisorD, A0(04): AtlasPA, UI(02): Winest, M1(05): Sam     
	* @param  $provinica	Codigo de dos dígitos de la provincia a descargar
	* @param  $desde 	Fecha desque que se quiere realizar la consulta
	* @param  $hasta 	Fecha hasta que se quiere realizar la consulta  
	* @return ObjectREST	Objeto en json, xml con los datos de la respuesta y la url donde se ha descarga el fichero
	* @access public
	**/
	public function consulta_actividad_odisea($user,$pass,$sistema,$provincia,$desde,$hasta)
	{
		$servicio         = "ConsultaActividad"; 
		$this->UserEagora = $user;
		$this->PassEagora = $pass;
		$this->app   	  = "odisea";
		$this->login 	  = $user.":".$pass;
		$this->base_url   = $this->server_addr;
		$this->url	  = "https://eagora.telefonica.es/od/servlet/ControlerEjecutor";
		$this->file 	  = $this->dirFiles.time()."-".$servicio.".xml";
		$this->httpdata   = 'nombre=&validar=true&accion=373&servicio='.$servicio.'&xml=<?xml version="1.0" encoding="ISO-8859-1"?>
		<SERVICIO_XML_ODISEA>
			<CABECERA>
				<ID_SERVICIO nom_servicio="'.$servicio.'" version_servicio="100.001"/>
				<ID_LLAMANTE nombre="'.$this->UserEagora.$this->ec.'"/>
			</CABECERA>
			<MENSAJE>
				<SOLICITUD numero="1">
					<PARAMETRO nombre="EC" valor="'.$this->ec.'"/>
					<PARAMETRO multiple="1" nombre="PR" valor="'.$provincia.'"/>
					<PARAMETRO nombre="FD" valor="'.$desde.'"/>
					<PARAMETRO nombre="FH" valor="'.$hasta.'"/>
					<PARAMETRO nombre="SI" valor="'.$sistema.'"/>
				</SOLICITUD>
			</MENSAJE>
		</SERVICIO_XML_ODISEA>';
		$this->http_rsp = $this->post(); 
		$this->responseData = (file_get_contents($this->rspFile));
		$xml = simplexml_load_string($this->responseData);
		$retorno = $xml->CABECERA->RESULTADO->attributes();
		if($retorno['retorno']!='00'){ //Si hay error en los datos de respuesta
		   $this->error = $retorno['mensaje'];
		   $this->responseData = $this->error($retorno['retorno']);
		}else{
		   $this->error = "";
		}
		echo $this->response();
	}
	
	/**
	* Descarga el fichero xml de las actuaciones Pendientes en Odisea de la $provincia en el $sistema indicado entre las fechas $desde y $hasta
	* 
	* @param  $sistema  	MA(03): VisorD, A0(04): AtlasPA, UI(02): Winest, M1(05): Sam     
	* @param  $provinica	Codigo de dos dígitos de la provincia a descargar
	* @param  $desde 	Fecha desque que se quiere realizar la consulta
	* @param  $hasta 	Fecha hasta que se quiere realizar la consulta  
	* @return ObjectREST	Objeto en json, xml con los datos de la respuesta y la url donde se ha descarga el fichero
	* @access public
	**/
	public function consulta_actividad_pendiente_odisea($user,$pass,$sistema,$provincia,$desde,$hasta)
	{
		$servicio         = "ConsultaActividad";
		$this->UserEagora = $user;
		$this->PassEagora = $pass;
		$this->app   	  = "odisea";
		$this->login 	  = $user.":".$pass;
		$this->base_url   = $this->server_addr;
		$this->url        = "https://eagora.telefonica.es/od/servlet/ControlerEjecutor";
		$this->file 	  = $this->dirFiles.time()."-".$servicio.".xml";
		$this->httpdata   = 'nombre=&validar=true&accion=373&servicio='.$servicio.'&xml=<?xml version="1.0" encoding="ISO-8859-1"?>
		<SERVICIO_XML_ODISEA>
			<CABECERA>
				<ID_SERVICIO nom_servicio="'.$servicio.'" version_servicio="100.001"/>
				<ID_LLAMANTE nombre="'.$this->UserEagora.$this->ec.'"/>
			</CABECERA>
			<MENSAJE>
				<SOLICITUD numero="1">
					<PARAMETRO nombre="EC" valor="'.$this->ec.'"/>
					<PARAMETRO multiple="1" nombre="PR" valor="'.$provincia.'"/>
					<PARAMETRO nombre="FD" valor="'.$desde.'"/>
					<PARAMETRO nombre="FH" valor="'.$hasta.'"/>
					<PARAMETRO nombre="SI" valor="'.$sistema.'"/>
					<PARAMETRO multiple="1" nombre="ES" valor="PT"/>
					<PARAMETRO multiple="1" nombre="ES" valor="AS"/>
					<PARAMETRO multiple="1" nombre="ES" valor="PL"/>
					<PARAMETRO multiple="1" nombre="ES" valor="PR"/>
					<PARAMETRO multiple="1" nombre="ES" valor="CR"/>
					<PARAMETRO multiple="1" nombre="ES" valor="CF"/>
				</SOLICITUD>
			</MENSAJE>
		</SERVICIO_XML_ODISEA>';
		$this->http_rsp = $this->post(); 
		$this->responseData = (file_get_contents($this->rspFile));
		$xml = simplexml_load_string($this->responseData);
		$retorno = $xml->CABECERA->RESULTADO->attributes();
		if($retorno['retorno']!='00'){ //Si hay error en los datos de respuesta
		   $this->error = $retorno['mensaje'];
		   $this->responseData = $this->error($retorno['retorno']);
		}else{
		   $this->error = "";
		}
		echo $this->response();
	}
	
		
	/**
	* Descarga el fichero xml de las actuaciones pendietnes de mantenimiento en Odisea
	* 
	* @param  $idOdisea  	ID_Odisea, identificador clave de la actividad en Odisea    
	* @return ObjectREST	Objeto en json, xml con los datos de la respuesta y la url donde se ha descarga el fichero
	* @access public
	**/
	public function consulta_boletin_odisea($user,$pass,$idOdisea)
	{
		$servicio         = "ConsultaBoletines";
		$this->UserEagora = $user;
		$this->PassEagora = $pass;
		$this->app   	  = "odisea";
		$this->login 	  = $user.":".$pass;
		$this->headers 	  = $this->headerOdisea; //Es necedsario crear una nueva session
		$this->base_url   = $this->server_addr;
		$this->url        = "https://eagora.telefonica.es/od/servlet/ControlerEjecutor";
		$this->file 	  = $this->dirFiles.time()."-".$servicio.".xml";
		$xml= '<?xml version="1.0" encoding="ISO-8859-1"?>
		<SERVICIO_XML_ODISEA>
			<CABECERA>
				<ID_SERVICIO nom_servicio="'.$servicio.'" version_servicio="100.001"/>
				<ID_LLAMANTE nombre="'.$user.$this->ec.'"/>
			</CABECERA>
			<MENSAJE>
				<SOLICITUD numero="1">
					<PARAMETRO nombre="NU" valor="'.$idOdisea.'"/>
					<PARAMETRO nombre="ACGA" valor="1"/>
					<PARAMETRO nombre="ASIG" valor="1"/>
					<PARAMETRO nombre="ETIQ" valor="1"/>
					<PARAMETRO nombre="FRAQ" valor="1"/>
					<PARAMETRO nombre="UMTS" valor="1"/>
					<PARAMETRO nombre="PROD" valor="1"/>
					<PARAMETRO nombre="XML" valor="1"/>
					<PARAMETRO nombre="ACOR" valor="1"/>
					<PARAMETRO nombre="PREL" valor="1"/>
					<PARAMETRO nombre="AD" valor="1"/>
					<PARAMETRO nombre="AS" valor="1"/>
					<PARAMETRO nombre="BOL" valor="1"/>
					<PARAMETRO nombre="CIAD" valor="1"/>
					<PARAMETRO nombre="BOL" valor="1"/>
					<PARAMETRO nombre="DBB" valor="1"/>
					<PARAMETRO nombre="ASG" valor="1"/>
					<PARAMETRO nombre="AVR" valor="1"/>
					<PARAMETRO nombre="BOL" valor="1"/>
					<PARAMETRO nombre="CIR" valor="1"/>
					<PARAMETRO nombre="CON" valor="1"/>
					<PARAMETRO nombre="FRAN" valor="1"/>
					<PARAMETRO nombre="FREX" valor="1"/>
					<PARAMETRO nombre="IND" valor="1"/>
					<PARAMETRO nombre="ORD" valor="1"/>
					<PARAMETRO nombre="PLT" valor="1"/>
					<PARAMETRO nombre="PLTD" valor="1"/>
					<PARAMETRO nombre="PRBA" valor="1"/>
					<PARAMETRO nombre="PRS" valor="1"/>
					<PARAMETRO nombre="PSLR" valor="1"/>
					<PARAMETRO nombre="TIT" valor="1"/>
					<PARAMETRO nombre="DARE" valor="1"/>
					<PARAMETRO nombre="ASG" valor="1"/>
					<PARAMETRO nombre="AVR" valor="1"/>
					<PARAMETRO nombre="BOLE" valor="1"/>
				</SOLICITUD>
			</MENSAJE>
		</SERVICIO_XML_ODISEA>';
		$this->httpdata = 'origen=378&nombre=&validar=true&accion=373&servicio='.$servicio.'&volver=servicios&xml='.$xml;
		return $this->response();
	}
	
	/**
	* Consulta las notas de una actuación en Odisea
	* 
	* @param  string      $user  	    Usuario de Odisea   
	* @param  string      $pass  	    Password de Odisea   
	* @param  string      $idOdisea     ID_Odisea, identificador clave de la actividad en Odisea    
	* @return ObjectREST  Objeto en json, xml con los datos de la respuesta y la url donde se ha descarga el fichero
	* @access public
	**/
	public function consultar_nota_odisea($user, $pass, $idOdisea)
	{
		$servicio 	  = "ServicioNotas";
		$this->UserEagora = $user;
		$this->PassEagora = $pass;
		$this->app   	  = "odisea";
		$this->login 	  = $user.":".$pass;
		$this->headers 	  = $this->headerOdisea; //Es necedsario crear una nueva session
		$this->base_url   = $this->server_addr;
		$this->url	  = "https://eagora.telefonica.es/od/servlet/ControlerEjecutor";
		$this->file 	  = $this->dirFiles.time()."-".$servicio.".xml";
		$xml = '<?xml version="1.0" encoding="ISO-8859-1"?>
		<SERVICIO_XML_ODISEA>
			<CABECERA>
				<ID_SERVICIO nom_servicio="'.$servicio.'" version_servicio="100.001"/>
				<ID_LLAMANTE nombre="'.$user.$this->ec.'"/>
			</CABECERA>
			<MENSAJE>
			<SOLICITUD numero="1">
				<PARAMETRO nombre="OP" valor="CO"/>
            		 	<PARAMETRO nombre="COOD" valor="'.$idOdisea.'"/>
			</SOLICITUD>
			</MENSAJE>
		</SERVICIO_XML_ODISEA>';
		$this->httpdata = 'origen=378&nombre=&validar=true&accion=373&servicio='.$servicio.'&volver=servicios&xml='.$xml;
		return $this->response();
	}
	
	/**
	* Añade una nota a una actuación en Odisea. ej 05-14-017465474
	* 
	* @param  string      $user  	    Usuario de Odisea   
	* @param  string      $pass  	    Password de Odisea   
	* @param  string      $idOdisea     ID_Odisea, identificador clave de la actividad en Odisea   
	* @param  string      $nota  	    Texto con la nota a insertar en la actuación definida por el IdOdisea    
	* @return ObjectREST  Objeto en json, xml con los datos de la respuesta y la url donde se ha descarga el fichero
	* @access public
	**/
	public function asignar_nota_odisea($user, $pass, $idOdisea, $nota)
	{
		$servicio         = "ServicioNotas";
		$this->UserEagora = $user;
		$this->PassEagora = $pass;
		$this->app   	  = "odisea";
		$this->login 	  = $user.":".$pass;
		$this->headers 	  = $this->headerOdisea; //Es necedsario crear una nueva session
		$nota = urlencode(base64_decode($nota));
		$this->base_url   = $this->server_addr;
		$this->url        = "https://eagora.telefonica.es/od/servlet/ControlerEjecutor";
		$this->file 	  = $this->dirFiles.time()."-".$servicio.".xml";
		//<!-- indicador de confidencialidad: P-publico,C-confidencial,R-restringido -->
		$xml= '<?xml version="1.0" encoding="ISO-8859-1"?>
		<SERVICIO_XML_ODISEA>
			<CABECERA>
				<ID_SERVICIO nom_servicio="'.$servicio.'" version_servicio="100.001"/>
				<ID_LLAMANTE nombre="'.$user.$this->ec.'"/>
			</CABECERA>
			<MENSAJE>
			<SOLICITUD numero="1">
				<PARAMETRO nombre="OP" valor="CR"/>
				<PARAMETRO nombre="COOD" valor="'.$idOdisea.'"/>
				<PARAMETRO nombre="MSG" valor="'.$nota.'"/>
				<PARAMETRO nombre="IC" valor="P"/> 
        		</SOLICITUD>
			</MENSAJE>
		</SERVICIO_XML_ODISEA>';
		$this->httpdata = 'origen=378&nombre=&validar=true&accion=373&servicio='.$servicio.'&volver=servicios&xml='.$xml;		
		return $this->response();
	}
	
	/**
	* Cambia el estado de una actuación al estado pasado como parámetro: Preasignar, Asignar, Liberar
	* 
	* @param  string      $user  	    	Usuario de Odisea   
	* @param  string      $pass  	    	Password de Odisea   
	* @param  string      $idOdisea  	ID_Odisea, identificador clave de la actividad en Odisea    
	* @param  string      $estado  	    	AS(Asignar), PR(Preasignar), LI(Liberar). NOTA AS: No se puede deshacer una vez asignado
	* @param  string      $matricula  	Matricula de Odisea del técnico que se desea liberar
	* @param  string      $observaciones  	Necesarias en caso de una liberación
	* @return ObjectREST  Objeto en json, xml con los datos de la respuesta y la url donde se ha descarga el fichero
	* @access public
	**/
	public function cambiar_estado($user, $pass, $idOdisea, $estado, $matricula='', $observaciones='')
	{
		$servicio         = "CambiarEstado";
		$this->UserEagora = $user;
		$this->PassEagora = $pass;
		$this->app   	  = "odisea";
		$this->login 	  = $user.":".$pass;
		$this->headers 	  = $this->headerOdisea; //Es necedsario crear una nueva session
		$observaciones    = ($estado=='LI') ? 'Motivos tecnicos' : '';
		$this->base_url   = $this->server_addr;
		$this->url        = "https://eagora.telefonica.es/od/servlet/ControlerEjecutor";
		$this->file 	  = $this->dirFiles.time()."-".$servicio.".xml";
		$xml= '<?xml version="1.0" encoding="ISO-8859-1"?>
		<SERVICIO_XML_ODISEA>
			<CABECERA>
				<ID_SERVICIO nom_servicio="'.$servicio.'" version_servicio="100.001"/>
				<ID_LLAMANTE nombre="'.$user.$this->ec.'"/>
			</CABECERA>
			<MENSAJE>
			<SOLICITUD numero="1">
            			<PARAMETRO nombre="ES" valor="'.$estado.'"/>
            			<PARAMETRO nombre="COOD" valor="'.$idOdisea.'"/>
            			<PARAMETRO nombre="EC" valor="'.$this->ec.'"/>
            			<PARAMETRO nombre="MT" valor="'.$matricula.'"/>
            			<PARAMETRO nombre="OB" valor="'.utf8_encode($observaciones).'"/>
        		</SOLICITUD>
			</MENSAJE>
		</SERVICIO_XML_ODISEA>';
		$this->httpdata = 'origen=378&nombre=&validar=true&accion=373&servicio='.$servicio.'&volver=servicios&xml='.$xml;
		return $this->response();
	}
}
?>
