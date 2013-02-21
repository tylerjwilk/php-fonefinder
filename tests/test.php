<?php

// fonefinder lib
require('../src/fonefinder.php');

// create new fonefinder
$fonefinder = new FoneFinder();

// get result
$data = $fonefinder->find(555,555,5555); // replace these with real cell number

// output
print_r($data);


