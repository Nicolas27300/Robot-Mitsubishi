<?php

include_once 'class/LireJoint.php';

class RobotArm {

    private $bconnectTcp = false;

    function __construct() {
        
    }

    /**
     * initialise le serveur en ce connectant au robot en tcp
     * 
     * @return string avec le code d'erreur si il y en a sinon retourne le caractere "1"
     */
    public function init() {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        $ok = socket_connect($socket, "192.168.0.1", 10001);
        $instruction = array("1;1;OPEN=NARCUS",
            "1;1;SRVOFF",
            "1;1;LOAD=ETUJ",
            "1;1;LISTL<",
            "1;1;NEW",
            "1;1;LOAD=ETUX",
            "1;1;LISTL<",
            "1;1;NEW",
            "1;1;LOAD=ETUP",
            "1;1;LISTL<",
            "1;1;NEW",
            "1;1;CNTLON",
            "1;1;STOP",
            "1;1;RSTPRG",
            "1;1;CNTLOFF",
            "1;1;SRVOFF",
            "1;1;CNTLON",
            "1;1;SRVON",
            "1;1;CNTLOFF",
            "1;1;CNTLON",
            "1;1;RUNETUJ",
            "1;1;CNTLOFF");
//for ($i = 0; $i < 22; $i++){
        $i = 0;
        $bool = TRUE;
        while ($i < 22 && $bool == TRUE) {
            $sresult = socket_write($socket, $instruction[$i], strlen($instruction[$i]));
            $sreponse = socket_read($socket, 500);
            //substr($buffer, $i, 8)
            $sok = substr($sreponse, 0, 3);
            if ($sok == "QoK") {// si la requete c'est bien passer
                $i++;
                usleep(600000);
            } else {
                $bool = FALSE;
            }
        }
        $serreur = array();
        if (!$bool) {// si il y a eu une erreur
            $serreur = 'erreur de connexion : <br>';
            $serreur .= $sreponse;
        } else { // pas d'erreur
            $serreur = $this->recupData();
        }
        return $serreur;
    }

    public function deconnexion() {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        $ok = socket_connect($socket, "192.168.0.1", 10001);
        $instruction = array("1;1;OPEN=NARCUS",
            "1;1;SRVOFF");
        for ($i = 0; $i < 2; $i++) {
            $sresult = socket_write($socket, $instruction[$i], strlen($instruction[$i]));
            $sreponse = socket_read($socket, 500);
        }
    }

    /**
     * arrete l'alarm si il y en a une 
     * 
     * @return type le code d'alarm
     */
    public function stopAlarm() {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        $bok = socket_connect($socket, "192.168.0.1", 10001);
        $instruction = array("1;1;ERRORLOGTOP",
            "1;1;RSTALRM",
            "1;1;CNTLON",
            "1;1;STOP",
            "1;1;RSTPRG");

        $sresponsefinal = '';

        for ($i = 0; $i < 5; $i++) {
            $sresult = socket_write($socket, $instruction[$i], strlen($instruction[$i]));
            $sreponse = socket_read($socket, 500);
            $sresponsefinal .=$sreponse;
            usleep(600000);
        }
        return $sresponsefinal;
    }

    /**
     * attention il faut une initialisation avec une requete tcp avant ( fonction init())
     * @return  type retourne un tableau avec les positions de chaque axes 
     */
    public function recupData() {
        $socketUDP = $this->connectUDP();
        $hexa = "00000200020000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000";
        $buffer = $this->envoiReponseUDP($socketUDP, hex2bin($hexa), 304);
        $buffer = bin2hex($buffer);

        $positionsAff = array();

        for ($i = 16; $i < 8 * 8; $i += 8) {
            $buf = substr($buffer, $i, 8);
            $data = pack('H*', $buf);
            $data = unpack("f", $data);
            $data = round($data[1] * 180 / 3.14159265359, 2);
            $positionsAff[] .= $data;
        }
        return $positionsAff;

        /* for ($i = 1; $i < 7; $i++) {
          echo 'J' . $i . ' : ' . $positionsAff[$i - 1] . '<br>';
          } */
    }

    private function getPosition() {
        $positions = array();
        $socketUDP = $this->connectUDP();
        $hexa = "00000200020000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000";
        $buffer = $this->envoiReponseUDP($socketUDP, hex2bin($hexa), 304);
        $buffer = bin2hex($buffer);

        for ($i = 16; $i < 8 * 8; $i += 8) {
            $buf = substr($buffer, $i, 8);
            $data = pack('H*', $buf);
            $data = unpack("f", $data);
            $positions[] .= $data[1];
        }
        return $positions;
    }

    /**
     * 
     * @param type $iAxe axe choisie (EX : 1)
     * @param type $sOperation operation choisie : "+" ou "-"
     * @return type
     */
    public function moveJoint($iAxe, $sOperation , $pas) {
        for ($i = 0; $i < 30; $i++) {
            $positions = array();
            $socketUDP = $this->connectUDP();
            $hexa = '0100020002000000';
            $positions = $this->getPosition();
            if ($sOperation == "-") {
                $positions[$iAxe - 1]-= $pas; //pas =0.005
            } else {
                $positions[$iAxe - 1]+= $pas;
            }

            for ($j = 0; $j < 6; $j++) {
                $positions[$j] = pack("f", $positions[$j]);
                $positions[$j] = unpack("H*", $positions[$j])[1];
                $hexa .= $positions[$j];
                $positions[$j] = pack('H*', $positions[$j]);
                $positions[$j] = unpack("f", $positions[$j])[1];
            }
            $ssend = $this->envoiReponseUDP($socketUDP, hex2bin($hexa), 64);
            usleep(10000);
        }
        return $this->recupData();
    }

    public function getPas($iAxe){
         switch ($iAxe) {
                case 1:
                    $pas = 0.0035;
                    break;
                case 2:
                    $pas = 0.0020;
                    break;
                case 3:
                    $pas = 0.0035;
                    break;
                case 5:
                    $pas = 0.0035;
                    break;
                case 6:
                    $pas = 0.005;
                    break;
            }
            return $pas;
    }
    private function connectUDP() {
        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        socket_bind($socket, "192.168.0.59");
        $bok = socket_connect($socket, "192.168.0.1", 10000);
        return $socket;
    }

    private function envoiReponseUDP($socket, $buf, $len) {
        $send = socket_sendto($socket, $buf, $len, 0, "192.168.0.1", 10000);
        $from = '';
        $buffer = '';
        $recv = socket_recvfrom($socket, $buffer, 196, 0, $from, $port);
        return $buffer;
    }

    public function resetPos() {

        $positions = array();
        $positions = $this->recupData();
        for ($iAxe = 0; $iAxe < 5; $iAxe++) {
            
            if ($iAxe != 3) {
                
                while ($positions[$iAxe] > 4.3 || $positions[$iAxe] < -4.3) {
                   
                    if ($positions[$iAxe] < 0) {
                        $this->moveJoint($iAxe + 1, "+",  $this->getPas($iAxe+1));
                    } else if ($positions[$iAxe] > 0) {
                        $this->moveJoint($iAxe + 1, "-", $this->getPas($iAxe+1));
                    }
                     $positions = $this->recupData();
                }
                if($positions[$iAxe] < -2.15) {
                     $this->moveJoint($iAxe + 1, "+",  0.0020);
                }elseif ($positions[$iAxe] > 2.15) {
                       $this->moveJoint($iAxe + 1, "-", 0.0020);
                }
             
            }
        }
        return $this->recupData();
    }

}
