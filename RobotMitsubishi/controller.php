

<?php

ini_set('display_errors', 1);
include_once 'class/RobotArm.php';



if (isset($_GET["deconnexion"])) {
    $robotArm = new RobotArm();


    echo $robotArm->deconnexion();
}

if (isset($_GET["connexion"])) {
    $robotArm = new RobotArm();

    $positions = array();
    $positions = $robotArm->init();
    if (is_array($positions)) {
        for ($i = 0; $i < 6; $i++) {
            if (!($i == 3)) {
                $j = $i + 1;
                echo 'J' . $j . ' : ' . $positions[$i] . "<br>";
            }
        }
    } else {
        echo $positions;
    }
}

if (isset($_GET["alarm"])) {
    $robotArm = new RobotArm();
    echo $robotArm->stopAlarm();
}
if (isset($_GET["position"])) {
    $robotArm = new RobotArm();
    $positions = array();
    $positions = $robotArm->recupData();

    for ($i = 0; $i < 6; $i++) {
        echo $positions[$i] . "<br>";
    }
}
if (isset($_GET["up"])) {
    $positions = array();
    $robotArm = new RobotArm(); 
    $positions = $robotArm->moveJoint($_GET['up'], "+" ,$robotArm->getPas($_GET['up']));
    for ($i = 0; $i < 6; $i++) {
        if (!($i == 3)) {
            $j = $i + 1;
            echo 'J' . $j . ' : ' . $positions[$i] . "<br>";
        }
    }
}
if (isset($_GET["down"])) {

    $positions = array();
    $robotArm = new RobotArm();
    $positions = $robotArm->moveJoint($_GET['down'], "-" , $robotArm->getPas($_GET['down']));
    for ($i = 0; $i < 6; $i++) {
        if (!($i == 3)) {
            $j = $i + 1;
            echo 'J' . $j . ' : ' . $positions[$i] . "<br>";
        }
    }
}if (isset($_GET["resetPos"])) {

    $robotArm = new RobotArm();
    $positions = array();
    $positions = $robotArm->resetPos();

    if (is_array($positions)) {
        for ($i = 0; $i < 6; $i++) {
            if (!($i == 3)) {
                $j = $i + 1;
                echo 'J' . $j . ' : ' . $positions[$i] . "<br>";
            }
        }
    }
    //echo $positions;
}


//$robotArm= new RobotArm();
//$sreponse= $robotArm->init();
//echo $sreponse;
//$robotArm->recupData();


/*
  ini_set('display_errors', 1);
  include_once 'class/LireJoint.php';

  $lireJoint = new LireJoint();
  $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
  socket_bind($socket, "192.168.0.59");
  $ok = socket_connect($socket, "192.168.0.1", 10000);
  $hexa = "00000200020000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000";
  //var_dump(hex2bin($hexa));
  $send = socket_sendto($socket, hex2bin($hexa), 304, 0, "192.168.0.1", 10000);
  $from = '';
  $buffer = '';
  $recv = socket_recvfrom($socket, $buffer, 196, 0, $from, $port);
  $buffer = bin2hex($buffer);

  $positionsAff = array();
  $positions = array();
  for ($i = 16; $i < 8 * 8; $i += 8) {
  $buf = substr($buffer, $i, 8);
  $data = pack('H*', $buf);
  $data = unpack("f", $data);
  $positions[] .= $data[1];
  //var_dump($data[1]);
  $data = round($data[1] * 180 / 3.14159265359, 2);
  $positionsAff[] .= $data;
  }
  for ($i = 1; $i < 7; $i++) {
  echo 'J' . $i . ' : ' . $positionsAff[$i - 1] . '<br>';
  }
  $pas = 0.005;
  for ($i = 0; $i < 50; $i++) {
  $hexa = '0100020002000000';
  $positions[1] -= $pas;
  for ($j = 0; $j < 6; $j++) {
  $positions[$j] = pack("f", $positions[$j]);
  $positions[$j] = unpack("H*", $positions[$j])[1];
  $hexa .= $positions[$j];
  $positions[$j] = pack('H*', $positions[$j]);
  $positions[$j] = unpack("f", $positions[$j])[1];
  }
  $send = socket_sendto($socket, hex2bin($hexa), 64, 0, "192.168.0.1", 10000);
  usleep(70000);
  } */
?>

