<?php
/**
 * Created by PhpStorm.
 * User: amir
 * Date: 30/05/2020
 * Time: 15:03
 */

//Utility functions

// ** DEBUGGING ** //

/**
 * metodo che effettua il log di una variabile per il debug
 *
 * @param mixed $var variabile da stampare
 * @param boolean $die flag indicante se il programma va arrestato
 */

function _debug_log($var,$die = true) {

    print('<pre>' . print_r($var,true) . '</pre>');

    if($die)

        die;

}

/**
 * metodo che effettua l'output di un testo per il debug
 *
 * @param string $var testo da stampare
 * @param boolean $die flag indicante se il programma va arrestato
 */

function _debug_echo($var,$die = true) {

    echo $var . PHP_EOL;

    if($die)

        die;

}

/**
 * metodo che effettua il dump di una variabile
 *
 * @param mixed $var variabile su cui fare il dumping
 * @param boolean $die flag indicante se il programma va arrestato
 */

function _debug_dump($var,$die = true) {

    var_dump($var);

    if($die)

        die;

}