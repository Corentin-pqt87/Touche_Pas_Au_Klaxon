<?php
// Core/logout.php

session_start();

/**
 * vide la session
 */
$_SESSION = array();

/**
 * supprime la session
 */
session_destroy();

/**
 * retour a la page d'accueil
 */
header("Location: ../index.php");
exit();