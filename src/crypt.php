<?php

/**
 * @package cms
 * @author hacktor
 * @date 07.08.2016 12:32
 *
 * This file exists in order to provide password cryptography.
 */

/**
 * MySQL "PASSWORD()" AKA MySQLSHA1 HASH FUNCTION
 * Aceasta este funcția pentru password hash folosit în MySQL 4.1.1+
 *
 * By Rev. Dustin Fineout 10/9/2009 9:36:20 AM
 *
 * @param   string  $input      Parola pentru calculare
 * @param   bool    $hex        Dacă se folosește cod hex sau nu
 * @return  string              Returnează parola cu hash
 */
function mysql_password_hash($input, $hex = true)
{
    $sha1_stage1 = sha1($input, true);
    $output = sha1($sha1_stage1, !$hex);
    return '*'.strtoupper($output);
} //END function mysql_password_hash

/**
 * Calculează fiecare pereche hexadecimală în bit-ul corespunzător.
 * Similar cu funcția hex2octet din mysql
 *
 * By Rev. Dustin Fineout 10/9/2009 9:36:20 AM
 *
 * @param   string    $hex      Codul hex
 * @return  string              Codul binar
 */
function hex_hash_to_bin($hex)
{
    $bin = "";
    $len = strlen($hex);
    for ($i = 0; $i < $len; $i += 2) {
        $byte_hex = substr($hex, $i, 2);
        $byte_dec = hexdec($byte_hex);
        $byte_char = chr($byte_dec);
        $bin .= $byte_char;
    }
    return $bin;
} //END function hex_hash_to_bin