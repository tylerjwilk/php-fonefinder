<?php

// fonefinder lib
require('../src/fonefinder.php');

// create new fonefinder
$fonefinder = new FoneFinder();

// get result
$data = $fonefinder->find(555,555,5555); // replace these with real cell number

// output
print_r($data);
/*
Array
(
    [npa] => 555
    [nxx] => 555
    [thoublock] => 5555
    [full] => 5555555555
    [carrier] => verizon
    [gateway] => vtext.com
    [email] => 5555555555@vtext.com
)
*/

