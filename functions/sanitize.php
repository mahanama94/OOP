<?php

/**
 * escape the given string, converts double and single quotes
 * character encoding utf-8 
 * @param unknown $string
 */
function escape($string){
	return htmlentities($string, ENT_QUOTES, 'UTF-8');
}