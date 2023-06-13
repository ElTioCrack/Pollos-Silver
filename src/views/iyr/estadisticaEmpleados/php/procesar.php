<?php
	include_once('conexion.php');
	class Procesar extends Model{

		public function __construct(){ 
     	 	parent::__construct(); 
    	}

	    public function build_report(){
	    	$total = array();
	    	for($i=0; $i<4; $i++){
	    		$month = $i+1;
	    		$sql = $this->db->query("SELECT SUM(nombre) AS total FROM empleados WHERE id_sucursal = '$month'");	
	    		$total[$i] = 0;
	    		foreach ($sql as $key){ $total[$i] = ($key['total'] == null)? 0 : $key['total']; }
	    	}			 
			return $total;
	    }

	}

	if($_POST['year']){
		$class = new Procesar;
		$run = $class->build_report($_POST['year']);
		exit(json_encode($run));
	}
?>