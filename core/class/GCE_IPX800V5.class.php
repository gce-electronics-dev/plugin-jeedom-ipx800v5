<?php
/* SUMMARY
*
* Includes
* Constante
	* Preset Tab
		* Preset Tab IPX
		* Preset Tab OBJ
		* Preset Tab EXT
	* Type Data Tab
		* Type Data IPX
		* Type Data OBJ
			* Pwatch
			* Thermostat
			* Counter
		* Type Data EXT
			* X4VR
			* X4FP
			* X010V
			* X200
			* X24D
			* X8D
			* X8R
			* XDIMMER
			* XDMX
			* XPWM
			* XTHL
			* X400
		* Type Data VAR
	* FUNCTIONS
		* Name: event
		* Name: pull
		* Name: presetCmd_IPX
		* Name: presetCmd_EXT
		* Name: clearCmd
		* Name: postSave
		* Name: getIPX
		* Name: get
		* Name: put
	* Command Execute
		* Refresh
		* Command Param
		* IO Command
		* Ana Command
*/

/* * *************************** Includes ********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class GCE_IPX800V5 extends eqLogic {
	/* Preset Tab */

	/* Preset Tab IPX */
	const PRESET_IPX = array('R_cmd', 'R_state', 'Din_state', 'Ain', 'Oout_cmd', 'Cin_cmd', 'Cin_state');
	/***/

	/* Preset Tab IPX v4 */
	const PRESET_IPXV4 = array('V4_R_cmd', 'V4_R_state', 'V4_Din_state', 'V4_Ain_state');
	/***/

	/* Preset Tab OBJ */
	const PRESET_OBJ = array(
		'Pwatch' 	=> array('Ping Watchdog' ,  32,  1,  0, ''),
		'Thermo' 	=> array('Thermostat'    ,  16,  1,  0, ''),
		'Count' 	=> array('Counter'       ,  64,  1,  0, '')
	);
	/***/

	/* Preset Tab EXT */
	const PRESET_EXT = array(
		'X4VR'  	=> array('X4VR'			,  8,  4,  0, 'Canal'),
		'X4FP'  	=> array('X4FP'			,  4,  4,  1, 'Canal'),
		'X010V' 	=> array('X010V'		,  4,  4,  1, 'Canal'),
		'X200'  	=> array('X200' 		,  2,  1,  0, ''),
		'X24D'  	=> array('X24D' 		,  4,  24, 0, 'R'),
		'X8D'   	=> array('X8D'  		,  4,  8,  0, 'R'),
		'X8R'     => array('X8R'  		,  10, 8,  0, 'R'),
		'XDIMMER' => array('XDIMMER'  ,  6,  4,  1, 'Canal'),
		'XPWM' 		=> array('XPWM'     ,  2,  12, 0, 'Canal'),
		'XTHL' 		=> array('XTHL'     ,  16, 1,  0, ''),
		'X400' 		=> array('X400'     ,  4,  4,  0, 'Canal')
		//'XDMX' 		=> array('XDMX'     ,  64, 8,  0, 'Canal')
	);
	/***/

	/* Type Data Tab */
	/* Cmd_name                Cmd_display   Nb_Instance_Max   Type     SubType   Obj           Api             Key_Name            Key_Name_id           Cmd_Type */

	/* Type Data IPX*/
	const TYPE_DATA_IPX = array(
		'R_cmd' => 			  array('Relays Command', 					8, 'action', 	'other',		'R',    '/api/system/ipx', 'ioRelays', 				  'ioRelays_id',					'IO'),
		'R_state' => 		  array('Relays State', 						8, 'info', 		'binary',		'R',    '/api/system/ipx', 'ioRelayState', 		  'ioRelayState_id',			'IO'),
		'Din_state' => 		array('Digital input', 						8, 'info', 		'binary',		"Din",  '/api/system/ipx', 'ioDInput', 			    'ioDInput_id',					'IO'),
		'Ain' => 					array('Analog input', 						4, 'info', 		'numeric',	"Ain",  '/api/system/ipx', 'ana_IPX_Input', 	  'ana_IPX_Input',				'Ana'),
		'Oout_cmd' => 		array('Sortie Optoisolé State', 	4, 'info', 		'binary',		"Cout", '/api/system/ipx', 'ioCollInput', 		  'ioCollInput_id',				'IO'),
		'Cin_cmd' => 			array('Open collecteur Command',	4, 'action', 	'other',		"Cin",  '/api/system/ipx', 'ioCollOutput', 		  'ioCollOutput_id',			'IO'),
		'Cin_state' => 		array('Open collecteur State', 		4, 'info', 		'binary',		"Cin",  '/api/system/ipx', 'ioCollOutputState', 'ioCollOutputState_id',	'IO')
	);
	/***/

	/* Type Data IPX800 V4 */
	const TYPE_DATA_IPXV4 = array(
		'V4_R_cmd' => 	 		  array('V4 Relay command', 	  8, 'action', 	'other',		"IPX800V4", '/api/plugin/ipx800v4',   '',  'ioRelays_id',	    'IO'),
		'V4_R_state' => 		  array('V4 Relay state', 			8, 'info', 	  'binary',		"IPX800V4", '/api/plugin/ipx800v4',   '',  'ioRelays_id',	    'IO'),
		'V4_Din_state' => 		array('V4 Digital input', 		8, 'info', 	  'binary',	 	"IPX800V4", '/api/plugin/ipx800v4',   '',  'ioDInput_id',	    'IO'),
		'V4_Ain_state' => 	 	array('V4 Analog input', 			4, 'info', 	  'numeric',	"IPX800V4", '/api/plugin/ipx800v4',   '',  'anaInput_id',	    'Ana')
	);
	/***/

	/* Type Data OBJ */
		/* Cmd_name                Cmd_display   Nb_Instance_Max  Type    SubType    Obj            Api         Key_Name_All  Key_Name   Cmd_Type   Nb_Canal*/
	const TYPE_DATA_OBJ = array(
		/* Pwatch */
		'Pwatch_cmd' =>   array('Ping Watchdog command', 	32, 'action', 	'other',		"Pwatch",  '/api/object/pingwd',    '',  	'ioInput_id',	'IO',  1),
		'Pwatch_state' => array('Ping Watchdog state', 	  32, 'info', 	  'binary',		"Pwatch",  '/api/object/pingwd',    '',  	'ioFault_id',	'IO',  1),
		/***/

		/* Thermostat */
		'Thermo_state' => array('Thermostat state', 	  		16, 'info',   	'binary',		"Thermo",  '/api/object/thermostat',    '',  	'ioOutput_id',				'IO',   1),
		'Thermo_cmd'	 => array('Thermostat command', 			16, 'action', 	'other',		"Thermo",  '/api/object/thermostat',    '',  	'ioOnOff_id',					'IO',   1),
		'Thermo_com' 	 => array('Thermostat Comfort', 			16, 'action', 	'other',		"Thermo",  '/api/object/thermostat',    '',  	'ioComfort_id',				'IO',   1),
		'Thermo_eco' 	 => array('Thermostat Eco', 					16, 'action', 	'other',		"Thermo",  '/api/object/thermostat',    '',  	'ioEco_id',						'IO',   1),
		'Thermo_fre' 	 => array('Thermostat Anti Freeze', 	16, 'action', 	'other',		"Thermo",  '/api/object/thermostat',    '',  	'ioNoFrost_id',				'IO',   1),
		'Thermo_set' 	 => array('Thermostat Set command', 	16, 'action', 	'slider',		"Thermo",  '/api/object/thermostat',    '',  	'anaCurrSetPoint_id',	'Ana',  1),
		'Thermo_mea' 	 => array('Thermostat Measure', 			16, 'info', 	  'numeric',	"Thermo",  '/api/object/thermostat',    '',  	'anaMeasure_id',			'Ana',  1),
		'Thermo_fau' 	 => array('Thermostat Fault', 				16, 'info', 	  'binary',		"Thermo",  '/api/object/thermostat',    '',  	'ioFault_id',					'IO',   1),
		/***/

		/* Counter */
		'Count_add' 			=> array('Counter Plus', 					64, 'action', 	'other',		"Count",  '/api/object/counter',    '',  	'ioAdd_id',					'IO',   1),
		'Count_sub' 			=> array('Counter Minus', 				64, 'action', 	'other',		"Count",  '/api/object/counter',    '',  	'ioSub_id',					'IO',   1),
		'Count_set_cmd' 	=> array('Counter Set', 					64, 'action', 	'other',		"Count",  '/api/object/counter',    '',  	'ioSet_id',					'IO',   1),
		'Count_reset' 		=> array('Counter Reset', 				64, 'action', 	'other',		"Count",  '/api/object/counter',    '',  	'ioReset_id',				'IO',   1),
		'Count_pace' 			=> array('Counter Pace', 					64, 'action', 	'slider',		"Count",  '/api/object/counter',    '',  	'anaPulseValue_id',	'Ana',  1),
		'Count_set_ana' 	=> array('Counter Set Analog', 		64, 'action', 	'slider',		"Count",  '/api/object/counter',    '',  	'anaSetValue_id',		'Ana',  1),
		'Count_ana_state' => array('Counter Analog State', 	64, 'info',   	'numeric',  "Count",  '/api/object/counter',    '',  	'anaOut_id',				'Ana',  1)
		/***/
	);
	/***/

	/* Type Data EXT */
	/* Cmd_name                Cmd_display     Nb_Instance_Max  Type    SubType    Obj            Api         Key_Name_All          Key_Name       Cmd_Type   Nb_Canal*/
	const TYPE_DATA_EXT = array(
		/* X4VR */
		'X4vr_up' => 	 		array('X4vr Up command', 					8, 'action', 	'other',		"X4VR", '/api/ebx/x4vr',   '',       				'ioCommandUp_id',	      'IO',  4),
		'X4vr_down' => 		array('X4vr Down command', 			  8, 'action', 	'other',		"X4VR", '/api/ebx/x4vr',   '',     					'ioCommandDown_id',	    'IO',  4),
		'X4vr_stop' => 		array('X4vr Stop Command', 			  8, 'action', 	'other',		"X4VR", '/api/ebx/x4vr',   '',     					'ioCommandStop_id',	    'IO',  4),
		'X4vr_B_up' => 	 	array('X4vr BSO_Up command', 			8, 'action', 	'other',		"X4VR", '/api/ebx/x4vr',   '',    					'ioCommandBsoUp_id',	  'IO',  4),
		'X4vr_B_down' => 	array('X4vr BSO_Down command', 		8, 'action', 	'other',		"X4VR", '/api/ebx/x4vr',   '',  						'ioCommandBsoDown_id',	'IO',  4),
		'X4vr_a_cmd' => 	array('X4vr Ana command', 		  	8, 'action', 	'slider',		"X4VR", '/api/ebx/x4vr',   '',        			'anaCommand_id',	      'Ana', 4),
		'X4vr_a_state' => array('X4vr Ana state', 		      8, 'info', 	  'numeric',	"X4VR", '/api/ebx/x4vr',   '',       				'anaPosition_id',	      'Ana', 4),
		/***/

		/* X4FP */
		'X4fp_com' => 	 	array('X4fp Comfort command', 		4, 'action', 	'other',		"X4FP", '/api/ebx/x4fp',   'ioAllComfort_id',     'ioComfort_id',	      'IO',  4),
		'X4fp_eco' => 		array('X4fp Eco command', 			  4, 'action', 	'other',		"X4FP", '/api/ebx/x4fp',   'ioAllEco_id',     		'ioEco_id',	    			'IO',  4),
		'X4fp_fre' => 		array('X4fp AntiFreeze Command', 	4, 'action', 	'other',		"X4FP", '/api/ebx/x4fp',   'ioAllAntiFreeze_id',  'ioAntiFreeze_id',	  'IO',  4),
		'X4fp_com1' => 		array('X4fp Comfort1 command', 		4, 'action', 	'other',		"X4FP", '/api/ebx/x4fp',   'ioAllStop_id',      	'ioComfort_1_id',	    'IO',  4),
		'X4fp_com2' => 		array('X4fp Comfort2 Command', 		4, 'action', 	'other',		"X4FP", '/api/ebx/x4fp',   'ioAllComfort_1_id',   'ioComfort_2_id',	    'IO',  4),
		'X4fp_stop' => 		array('X4fp stop command', 			  4, 'action', 	'other',		"X4FP", '/api/ebx/x4fp',   'ioAllComfort_2_id',   'ioStop_id',	    		'IO',  4),
		/***/

		/* X010V */
		'X010v_on' => 	 	 array('X010v on', 								4, 'action', 	'other',		"X010V", '/api/ebx/x010v',   'ioOnAll_id',     'ioOn_id',	      'IO',  4),
		'X010v_a_cmd' => 	 array('X010v Analog command', 		4, 'action', 	'slider',		"X010V", '/api/ebx/x010v',   '',     					 'anaCommand_id',	'Ana',  4),
		'X010v_a_state' => array('X010v Analog State', 			4, 'info', 	  'numeric',	"X010V", '/api/ebx/x010v',   '',  						 'anaLevel_id',	  'Ana',  4),
		/***/

		/* X200 */
		'X200_ph' => 	 	 array('X200 PH State', 						2, 'info', 	'numeric',		"X200",  '/api/ebx/x200',    '',     'anaPhVal_id',	      'Ana',  1),
		'X200_orp' => 	 array('X200 ORP State', 		        2, 'info', 	'numeric',		"X200",  '/api/ebx/x200',    '',     'anaOrpVal_id',	    'Ana',  1),
		/***/

		/* X24D */
		'X24D_in' => 	 	 array('X24D relay State', 					4, 'info', 	'binary',		"X24D",  '/api/ebx/x24d',      '',     'ioInput_id',	      'IO',  24),
		/***/

		/* X8D */
		'X8D_in' => 	 	 array('X8D relay State', 					4, 'info', 	'binary',		"X8D",  '/api/ebx/x8d',      '',     'ioInput_id',	      'IO',  8),
		/***/

		/* X8R */
		'X8R_out_cmd' => 	  array('X8R relay Command', 				10, 'action', 	'other',		"X8R",  '/api/ebx/x8r',      '',     'ioOutput_id',	      'IO',  8),
		'X8R_out_state' =>  array('X8R relay State', 					10, 'info', 	  'binary',		"X8R",  '/api/ebx/x8r',      '',     'ioOutputState_id',	'IO',  8),
		'X8R_long_state' => array('X8R Long push State', 		  10, 'info', 	  'binary',		"X8R",  '/api/ebx/x8r',      '',     'ioLongPush_id',	    'IO',  8),
		/***/

		/* XDIMMER */
		'XDIMMER_on_cmd' =>   array('XDIMMER relay Command', 			  6, 'action', 	'other',		"XDIMMER",  '/api/ebx/xdimmer',    'ioOnAll_id',  'ioOn_id',	      				'IO',   4),
		'XDIMMER_on_state' => array('XDIMMER relay State', 			    6, 'info', 	  'binary',		"XDIMMER",  '/api/ebx/xdimmer',    'ioOnAll_id',  'ioOn_id',	      				'IO',   4),
		'XDIMMER_a_cmd' =>   	array('XDIMMER Analog Command', 	  	6, 'action', 	'slider',		"XDIMMER",  '/api/ebx/xdimmer',    '',            'anaCommand_id',					'Ana',  4),
		'XDIMMER_a_state' => 	array('XDIMMER Analog State', 		    6, 'info', 	  'numeric',	"XDIMMER",  '/api/ebx/xdimmer',    '',            'anaPosition_id',	    		'Ana',  4),
		'XDIMMER_speed' =>  	array('XDIMMER Speed Transition', 		6, 'action', 	'slider',		"XDIMMER",  '/api/ebx/xdimmer',    '',            'anaSpeedTransition_id',	'Ana',  1),
		/***/

		/* XDMX
		'XDMX_cmd' => 	array('XDMX Command', 					64, 'action', 	'slider',		"XDMX",  '/api/ext/xdmx',    '',  	'anaInputCommand_id',	      'Ana',  8),
		'XDMX_speed' => array('XDMX Speed Transition', 	64, 'action', 	'slider',		"XDMX",  '/api/ext/xdmx',    '',    'anaSpeedTrans_id',					'Ana',  8),
		/***/

		/* XPWM */
		'XPWM_cmd' => 	array('XPWM Command', 					2, 'action', 	'slider',		"XPWM",  '/api/ebx/xpwm',    '',  	'anaCommand_id',	        'Ana',  12),
		'XPWM_speed' => array('XPWM Speed Transition', 	2, 'action', 	'slider',		"XPWM",  '/api/ebx/xpwm',    '',    'anaSpeedTransition_id',	'Ana',  1),
		/***/

		/* XTHL */
		'XTHL_temp' => array('XTHL Temperature', 	16, 'info', 	'numeric',		"XTHL",  '/api/ebx/xthl',    '',  	'anaTemp_id',	'Ana',  1),
		'XTHL_hum' =>  array('XTHL Humidity', 	  16, 'info', 	'numeric',		"XTHL",  '/api/ebx/xthl',    '',    'anaHum_id',	'Ana',  1),
		'XTHL_lum' =>  array('XTHL Luminosite', 	16, 'info', 	'numeric',		"XTHL",  '/api/ebx/xthl',    '',    'anaLum_id',	'Ana',  1),
		/***/

		/* X400 */
		'X400_state' => array('X400 State', 	4, 'info', 	'numeric',		"X400",  '/api/ebx/x400',    '',  	'anaOutputVal_id',	'Ana',  4)
		/***/
	);
	/***/

	/* Type Data VAR */
	const TYPE_DATA_VAR = array(
		'IO_cmd' 	=> 			array('Analog value Command', 		0, 'action', 	'other',		"IO",   '/api/core/io',    '', 	'',	 'IO'),
		'IO_state' => 		array('Analog value State', 			0, 'info', 		'binary',		"IO",   '/api/core/io',    '',  '',  'IO'),
		'Ana_cmd' 	=> 		array('Analog value Command', 		0, 'action', 	'other',		"Ana",  '/api/core/ana',   '', 	'',	 'Ana'),
		'Ana_state' => 		array('Analog value State', 			0, 'info', 		'numeric',	"Ana",  '/api/core/ana',   '', 	'',	 'Ana')
	);
	/***/
	const DATA_UNITAIRE_REGEX = '/^([A-Z]+)(\d{1,3})$/';

	private static $_eqLogics = null;

	/* FUNCTIONS */

	/*
	* Name: event
	* Descr: Init value
	*/
	public static function event() {
		if (init('onvent') == 1) { //D'origine dans la classe
			$cache = array();
			foreach (self::searchConfiguration('"ip":"' . init('ip') . '"', 'GCE_IPX800V5') as $GCE_IPX800V5) {
				if (!isset($cache[$GCE_IPX800V5->getConfiguration('ip')])) {
					$cache[$GCE_IPX800V5->getConfiguration('ip')] = $GCE_IPX800V5->getIPX();
				}
				GCE_IPX800V5::pull($GCE_IPX800V5->getId(), $cache);
			}
			return;
		}
		$cmd = GCE_IPX800V5Cmd::byId(init('id'));
		if (!is_object($cmd) || $cmd->getEqType() != 'GCE_IPX800V5') {
			throw new Exception(__('Commande ID GCE_IPX800V5 inconnue, ou la commande n\'est pas de type GCE_IPX800V5 : ', __FILE__) . init('id').', Valeur: '.init('value'));
		}
		$cmd->event(init('value'));
	}

	public static function deamon_info() {
		$return = array();
		$return['log'] = '';
		$return['state'] = 'nok';
		$cron = cron::byClassAndFunction('GCE_IPX800V5', 'pull');
		if (is_object($cron) && $cron->running()) {
			$return['state'] = 'ok';
		}
		$return['launchable'] = 'ok';
		return $return;
	}

	public static function deamon_start() {
		self::deamon_stop();
		$deamon_info = self::deamon_info();
		if ($deamon_info['launchable'] != 'ok') {
			throw new Exception(__('Veuillez vérifier la configuration', __FILE__));
		}
		$cron = cron::byClassAndFunction('GCE_IPX800V5', 'pull');
		if (!is_object($cron)) {
			throw new Exception(__('Tâche cron introuvable', __FILE__));
		}
		$cron->setDeamonSleepTime(config::byKey('api::frequency', 'GCE_IPX800V5', 1));
		$cron->save();
		$cron->run();
	}

	public static function deamon_stop() {
		$cron = cron::byClassAndFunction('GCE_IPX800V5', 'pull');
		if (!is_object($cron)) {
			throw new Exception(__('Tâche cron introuvable', __FILE__));
		}
		$cron->halt();
	}

	public static function deamon_changeAutoMode($_mode) {
		$cron = cron::byClassAndFunction('GCE_IPX800V5', 'pull');
		if (!is_object($cron)) {
			throw new Exception(__('Tâche cron introuvable', __FILE__));
		}
		$cron->setEnable($_mode);
		$cron->save();
	}

	public static function cronDaily() {

	}

	/*
	* Name: pull
	* Descr: polling on Ipx
	*/
	public static function pull($_eqLogic_id = null, $_cache = null) {
		if (self::$_eqLogics == null) {
			self::$_eqLogics = self::byType('GCE_IPX800V5',true);
		}
		log::add('GCE_IPX800V5', 'debug', 'PULL');
		$cache = array();
		foreach (self::$_eqLogics as &$GCE_IPX800V5) { //pour chaque Equipement IPX800 V5
			$_eqLogic_id = $GCE_IPX800V5->getId();

			$cmds = cmd::byEqLogicId($_eqLogic_id); //get all cmd de l'equipement
			$refreshIo  = array();
			$refreshAna = array();

			$io = 0;
			$ana = 0;

			for ($i=0; $i < sizeof($cmds); $i++) { //pour chaque cmd de l'equipement
				$cmd = $cmds[$i];
				log::add('GCE_IPX800V5', 'debug', 'Command: '. $cmd);
				$arg = $cmd->getConfiguration('actionArgument');
				if ($cmd->getType() == "info") {
					$id = $cmd->getConfiguration('infoParameter'.$arg);
				} else {
					$id = $cmd->getConfiguration('actionParameter'.$arg);
				}

				if ($id != '') { // si la cmd a un ID en param
					log::add('GCE_IPX800V5', 'debug', 'arg: ' . $arg . ' id: ' . $id . ' type: '. $cmd->getType());
					if ($cmd->getType() == "info") {
						if ($arg == "IO") {	//si c'est un IO
							array_push($refreshIo, array($cmd, $id)); //ajouter la cmd et l'id au tab de refresh IO
							$io = $io + 1;
						}
						if ($arg == "Ana") { //si c'est une Ana
							array_push($refreshAna, array($cmd, $id)); //ajouter la cmd et l'id au tab de refresh Ana
							$ana = $ana + 1;
						}
					}
				}
			}
			if ($io > 0) { //si on a au moins une IO a refresh
				if (!isset($cache[$GCE_IPX800V5->getConfiguration('ip')]["io"])) { //si on a pas encore GET io collection
					$urlGet = 'http://' . $GCE_IPX800V5->getConfiguration('ip') . '/api/core/io?ApiKey=' . $GCE_IPX800V5->getConfiguration('apikey');
					$cache[$GCE_IPX800V5->getConfiguration('ip')]["io"] = GCE_IPX800V5::get($urlGet, 1); //GET Io collection
				}
				$ios = $cache[$GCE_IPX800V5->getConfiguration('ip')]["io"];
				for ($i=0; $i < sizeof($refreshIo); $i++) { // pour chaque IO à refresh
					for ($j=0; $j < sizeof($ios); $j++) { // pour chaque IO de Io collection
						if ($refreshIo[$i][1] == $ios[$j]["_id"]) { // si les Id correspondent
							$GCE_IPX800V5->checkAndUpdateCmd($refreshIo[$i][0], $ios[$j]["on"], false); //update cmd value
							break;
						}
					}
				}
				usleep(config::byKey('api::frequency', 'GCE_IPX800V5', 1) * 1000000 / 2); //Sleep 1/2 frequency pour ne pas faire
			}
			if ($ana > 0) { //si on a au moins une Ana a refresh
				if (!isset($cache[$GCE_IPX800V5->getConfiguration('ip')]["ana"])) { //si on a pas encore GET ana collection
					$urlGet = 'http://' . $GCE_IPX800V5->getConfiguration('ip') . '/api/core/ana?ApiKey=' . $GCE_IPX800V5->getConfiguration('apikey');
					$cache[$GCE_IPX800V5->getConfiguration('ip')]["ana"] = GCE_IPX800V5::get($urlGet, 1); //GET Ana collection
				}
				$anas = $cache[$GCE_IPX800V5->getConfiguration('ip')]["ana"];
				for ($i=0; $i < sizeof($refreshAna); $i++) { // pour chaque Ana à refresh
					log::add('GCE_IPX800V5', 'debug', 'Ana to refres id: ' . $refreshAna[$i][1]);
					for ($j=0; $j < sizeof($anas); $j++) { // pour chaque Ana de Ana collection
						if ($refreshAna[$i][1] == $anas[$j]["_id"]) { // si les Id correspondent
							log::add('GCE_IPX800V5', 'debug', ' id: ' . $id . ' value: ' . $anas[$j]["value"]);
							$GCE_IPX800V5->checkAndUpdateCmd($refreshAna[$i][0], $anas[$j]["value"], false); //update cmd value
							break;
						}
					}
				}
				usleep(config::byKey('api::frequency', 'GCE_IPX800V5', 1) * 1000000 / 2);
			}
		}
	}
	/***/

	/*
	* Name: presetCmd_IPX
	* Descr: create or delete command from the table config
	*/
	public function presetCmd_IPX($name, $nb, $type, $subType, $action, $api, $key, $key_id, $cmdType) {
		if ($this->getConfiguration($name) == 1) {
			$urlGet = 'http://' . $this->getConfiguration('ip') . $api.'?ApiKey=' . $this->getConfiguration('apikey');
			$obj = $this->get($urlGet, 0);

			for ($i=1; $i <= $nb; $i++) {
				$id = $obj[$key_id][$i - 1];

				if ($id) {
					if ($cmdType == "IO") {
						$urlGet = 'http://' . $this->getConfiguration('ip') .'/api/core/io/'. $id . '?ApiKey=' . $this->getConfiguration('apikey');
					} else {
						$urlGet = 'http://' . $this->getConfiguration('ip') .'/api/core/ana/'. $id . '?ApiKey=' . $this->getConfiguration('apikey');
					}
					$dispName = $this->get($urlGet, 0)["name"];

					$cmd = $this->getCmd(null, $name.'_'.$i);
					if (!is_object($cmd)) {
						$cmd = new GCE_IPX800V5Cmd();
					}
					$cmd->setName(__($dispName.'_'.$i, __FILE__));
					$cmd->setEqLogic_id($this->getId());
					$cmd->setLogicalId($name.'_'.$i);
					$cmd->setConfiguration('actionArgument', $cmdType);
					if ($type == "action") {
						$cmd->setConfiguration('actionParameter'.$cmdType , $id);
					} else {
						$cmd->setConfiguration('infoType', $cmdType);
						$cmd->setConfiguration('infoParameter'.$cmdType , $id);
					}
					$cmd->setType($type);
					$cmd->setSubType($subType);
					$cmd->save();
				} else {
						$cmd = $this->getCmd(null, $name.'_'.$i);
						if (is_object($cmd)) $cmd->remove();
				}
			}
		} else {
			for ($i=1; $i <= $nb; $i++) {
				$cmd = $this->getCmd(null, $name.'_'.$i);
				if (is_object($cmd)) $cmd->remove();
			}
		}
	}
	/***/

	/*
	* Name: presetCmd_IPX
	* Descr: create or delete command from the table config
	*/
	public function presetCmd_IPXV4($name, $nb, $type, $subType, $action, $api, $key, $key_id, $cmdType) {
		for ($i=0; $i < 4; $i++) {
			if ($this->getConfiguration("V4".$i) == 1) {
				$urlGet = 'http://' . $this->getConfiguration('ip') . $api.'?ApiKey=' . $this->getConfiguration('apikey');
				$obj = $this->get($urlGet, 0);

				if ($this->getConfiguration("V4".$i.$name) == 1) {
					for ($j=1; $j <= $nb; $j++) {
						$id = $obj[$i][$key_id][$j - 1];
						if ($id) {
							if ($cmdType == "IO") {
								$urlGet = 'http://' . $this->getConfiguration('ip') .'/api/core/io/'. $id . '?ApiKey=' . $this->getConfiguration('apikey');
							} else {
								$urlGet = 'http://' . $this->getConfiguration('ip') .'/api/core/ana/'. $id . '?ApiKey=' . $this->getConfiguration('apikey');
							}
							$dispName = $this->get($urlGet, 0)["name"];
							if ($type == "info") { $dispName .= "_state"; }

							$cmd = $this->getCmd(null, $name.'_'.$i.'_'.$j);
							if (!is_object($cmd)) {
								$cmd = new GCE_IPX800V5Cmd();
							}
							$cmd->setName(__($dispName.'_'.$i.'_'.$j, __FILE__));
							$cmd->setEqLogic_id($this->getId());
							$cmd->setLogicalId($name.'_'.$i.'_'.$j);
							$cmd->setConfiguration('actionArgument', $cmdType);
							if ($type == "action") {
								$cmd->setConfiguration('actionParameter'.$cmdType , $id);
							} else {
								$cmd->setConfiguration('infoType', $cmdType);
								$cmd->setConfiguration('infoParameter'.$cmdType , $id);
							}
							$cmd->setType($type);
							$cmd->setSubType($subType);
							$cmd->save();
						} else {
								$cmd = $this->getCmd(null, $name.'_'.$i.'_'.$j);
								if (is_object($cmd)) $cmd->remove();
						}
					}
				} else {
					for ($j=1; $j <= $nb; $j++) {
						$cmd = $this->getCmd(null, $name.'_'.$i.'_'.$j);
						if (is_object($cmd)) $cmd->remove();
					}
				}
			} else {
				for ($j=1; $j <= $nb; $j++) {
					$cmd = $this->getCmd(null, $name.'_'.$i.'_'.$j);
					if (is_object($cmd)) $cmd->remove();
				}
			}
		}
	}
	/***/

	/*
	* Name: presetCmd_EXT
	* Descr: create or delete command from the table config
	*/
	public function presetCmd_EXT($name, $nb, $type, $subType, $action, $api, $key_All, $key_id, $cmdType, $canal) {

		if ($this->getConfiguration($action) == 1) {
			$urlGet = 'http://' . $this->getConfiguration('ip') . $api.'?ApiKey=' . $this->getConfiguration('apikey');
			$obj = $this->get($urlGet, 0);

			for ($i=0; $i < $nb; $i++) { //pour chaque instance de l'extension
				if ($this->getConfiguration($action . $i) == 1) { //si elle est sélectionner
					for ($j=0; $j < $canal; $j++) { //pour chaque canaux de l'extension
						if ($this->getConfiguration('Canal' . $action . $i . '_' . $j) == 1 || $canal == 1) { //si le canal est selectionné
							if ($canal > 1) $id = $obj[$i][$key_id][$j];
							else $id = $obj[$i][$key_id];

							if ($id) {
								if ($cmdType == "IO") {
									$urlGet = 'http://' . $this->getConfiguration('ip') .'/api/core/io/'. $id . '?ApiKey=' . $this->getConfiguration('apikey');
								} else {
									$urlGet = 'http://' . $this->getConfiguration('ip') .'/api/core/ana/'. $id . '?ApiKey=' . $this->getConfiguration('apikey');
								}
								$dispName = $this->get($urlGet, 0)["name"];
								if ($type == "info") { $dispName .= "_state"; }

								$cmd = $this->getCmd(null, $name . $i.'_'.$j);
								if (!is_object($cmd)) {
									$cmd = new GCE_IPX800V5Cmd();
								}
								$cmd->setName(__($dispName.'_'.$j, __FILE__));
								$cmd->setEqLogic_id($this->getId());
								$cmd->setLogicalId($name . $i.'_'.$j);
								$cmd->setType($type);
								$cmd->setSubType($subType);

								$cmd->setConfiguration('actionArgument', $cmdType);
								if ($type == "action") {
									if ($cmdType == "IO") $cmd->setConfiguration('actionTypeCmd'.$cmdType , "toggle");
									$cmd->setConfiguration('actionParameter'.$cmdType , $id);
								} else {
									$cmd->setConfiguration('infoType', $cmdType);
									$cmd->setConfiguration('infoParameter'.$cmdType , $id);
								}
								$cmd->save();
							} else {
								$this->clearCmd($name, $i, $j);
							}
						} else {
							$this->clearCmd($name, $i, $j);
						}
					}
					if ($this->getConfiguration('Canal' . $action . $i . '_All') == 1 && $key_All != '') {
						$id = $obj[$i][$key_All];

						if ($id) {
							if ($cmdType == "IO") {
								$urlGet = 'http://' . $this->getConfiguration('ip') .'/api/core/io/'. $id . '?ApiKey=' . $this->getConfiguration('apikey');
							} else {
								$urlGet = 'http://' . $this->getConfiguration('ip') .'/api/core/ana/'. $id . '?ApiKey=' . $this->getConfiguration('apikey');
							}
							$dispName = $this->get($urlGet, 0)["name"];
							if ($type == "info") { $dispName .= "_state"; }

							$cmd = $this->getCmd(null, $name . $i.'_All');
							if (!is_object($cmd)) {
								$cmd = new GCE_IPX800V5Cmd();
							}
							$cmd->setName(__($dispName.'_All', __FILE__));
							$cmd->setEqLogic_id($this->getId());
							$cmd->setLogicalId($name . $i.'_All');
							$cmd->setType($type);
							$cmd->setSubType($subType);

							$cmd->setConfiguration('actionArgument', $cmdType);
							if ($type == "action") {
								if ($cmdType == "IO") $cmd->setConfiguration('actionTypeCmd'.$cmdType , "toggle");
								$cmd->setConfiguration('actionParameter'.$cmdType , $id);
							} else {
								$cmd->setConfiguration('infoType', $cmdType);
								$cmd->setConfiguration('infoParameter'.$cmdType , $id);
							}
							$cmd->save();
						} else {
							$this->clearCmd($name, $i, -2);
						}
					} else {
						$this->clearCmd($name, $i, -2);
					}
				} else {
					for ($j=0; $j < $canal; $j++) {
						$this->clearCmd($name, $i, $j);
					}
					$this->clearCmd($name, $i, -2);
				}
			}
		} else {
			for ($i=0; $i < $nb; $i++) {
				for ($j=0; $j < $canal; $j++) {
					$this->clearCmd($name, $i, $j);
				}
				$this->clearCmd($name, $i, -2);
			}
		}
	}
	/***/

	/*
	* Name: clearCmd
	* Descr: delete command
	*/
	public function clearCmd($name, $nb, $canal) {
		if ($canal >= 0) {
			$cmd = $this->getCmd(null, $name. $nb .'_'.$canal);
			if (is_object($cmd)) $cmd->remove();
		} else if ($canal == -2){
			$cmd = $this->getCmd(null, $name. $nb .'_All');
			if (is_object($cmd)) $cmd->remove();
		} else {
			$cmd = $this->getCmd(null, $name.'_'.$nb);
			if (is_object($cmd)) $cmd->remove();
		}
	}
	/***/

	/*
	* Name: postSave
	* Descr: launch the presetCmd functions
	*/
	public function postSave() {
		$refresh = $this->getCmd(null, 'refresh');
		if (!is_object($refresh)) {
			$refresh = new GCE_IPX800V5Cmd();
		}
		$refresh->setName(__('Rafraîchir', __FILE__));
		$refresh->setEqLogic_id($this->getId());
		$refresh->setLogicalId('refresh');
		$refresh->setType('action');
		$refresh->setSubType('other');
		$refresh->save();

		foreach (GCE_IPX800V5::TYPE_DATA_IPX as $key => $value) {
			$this->presetCmd_IPX($key, $value[1], $value[2], $value[3], $value[4], $value[5], $value[6], $value[7], $value[8]);
		}

		foreach (GCE_IPX800V5::TYPE_DATA_IPXV4 as $key => $value) {
			$this->presetCmd_IPXV4($key, $value[1], $value[2], $value[3], $value[4], $value[5], $value[6], $value[7], $value[8]);
		}

		foreach (GCE_IPX800V5::TYPE_DATA_EXT as $key => $value) {
			$this->presetCmd_EXT($key, $value[1], $value[2], $value[3], $value[4], $value[5], $value[6], $value[7], $value[8], $value[9]);
		}

		foreach (GCE_IPX800V5::TYPE_DATA_OBJ as $key => $value) {
			$this->presetCmd_EXT($key, $value[1], $value[2], $value[3], $value[4], $value[5], $value[6], $value[7], $value[8], $value[9]);
		}
	}
	/***/

	/*
	* Name: getIPX
	* Descr: get on api system ipx
	*/
	public function getIPX($opt = null) {
		$return = array();
		$url = 'http://' . $this->getConfiguration('ip') . '/api/system/ipx?ApiKey=' . $this->getConfiguration('apikey');
		if ($opt != 1) $url .= '&option=filter_id';
		$request_http = new com_http($url);
		try {
			$return = array_merge($return, is_json($request_http->exec(), array()));
		} catch (Exception $e) {}
		return $return;
	}
	/***/

	/*
	* Name: get
	* Descr: get on url
	* Arg: opt = 1 set the query option=filter_id
 	*/
	public function get($url, $opt) {
		$return = array();

		if ($opt != 1) $url .= '&option=filter_id';
		$request_http = new com_http($url);
		try {
			$return = array_merge($return, is_json($request_http->exec(), array()));
		} catch (Exception $e) {}
		//log::add('GCE_IPX800V5', 'debug', $url . ' GET : ' . json_encode($return));
		return $return;
	}
	/***/

	/*
	* Name: put
	* Descr: put on url with body = data
	*/
	public function put($url, $data) {
		$curl = curl_init($url);

		$headers = array(
			"X-CSRFToken:". $csrfToken,
			"Referer: http://".$this->getConfiguration('ip'),
		  "Content-Type: application/json"
		);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST , "PUT");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

		$resp = curl_exec($curl);
		curl_close($curl);
		//log::add('GCE_IPX800V5', 'debug', $url . ' PUT : ' . json_encode($resp));
		return $resp;
	}
	/***/
}

class GCE_IPX800V5Cmd extends cmd {
	/* Command Execute */
	public function execute($_options = array()) {
		$eqLogic = $this->getEqLogic();

		/* Refresh */
		if ($this->getLogicalId() == 'refresh') {
			GCE_IPX800V5::pull($this->getEqLogic_Id());
			return;
		}
		/***/

		/* Command Param */
		$act = $this->getConfiguration('actionArgument');
		$id = $this->getConfiguration('actionParameter'.$act);

		$subType = $this->getSubType();
		/***/

		/* IO Command */
		if ($act == "IO") { //si c est un IO Cmd
			$actCmd = $this->getConfiguration('actionTypeCmd'.$act);
			$req;
			switch ($actCmd) {
				case 'toggle': $req = json_encode(array('toggle' => true)); break;
				case 'setOn': $req = json_encode(array('on' => true)); break;
				case 'setOff': $req = json_encode(array('on' => false)); break;
			}
			$urlPut = 'http://' . $eqLogic->getConfiguration('ip') . '/api/core/io/'.$id.'?ApiKey=' . $eqLogic->getConfiguration('apikey');
			$request_http = $eqLogic->put($urlPut, $req);
			return;
		}
		/***/

		/* Ana Command */
		if ($act == "Ana") { //si c est un ana Cmd
			$urlPut = 'http://' . $eqLogic->getConfiguration('ip') . '/api/core/ana/'.$id.'?ApiKey=' . $eqLogic->getConfiguration('apikey');

			/* BODY Ana Command */
			if ($subType == "other") {
				$body = json_encode(array('value' =>  intval($this->getConfiguration('actionOption'.$act))));
			} else if ($subType == "slider") {
				$body = json_encode(array('value' =>  intval($_options["slider"])));
			}
			/***/
			$request_http = $eqLogic->put($urlPut, $body);
			return;
		}
		/***/

		usleep(10000);
	}

	/* * **********************Getteur Setteur*************************** */
}

?>
