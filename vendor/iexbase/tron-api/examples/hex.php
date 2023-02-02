<?php
include_once '../../../autoload.php';

$tron = new \IEXBase\TronAPI\Tron();

$x = $tron->toHex('TBczHFVgnsE6tEfTQi7Gpp5pd7b9FnBZbt');
//result: 41BBC8C05F1B09839E72DB044A6AA57E2A5D414A10
echo $x . "<br/>";
$tron->fromHex('41BBC8C05F1B09839E72DB044A6AA57E2A5D414A10');

$t = $tron->fromHex('0x56a9148b5c5619a6bdfbf29dfc97b614bf69f458');
echo $t;
//result: TT67rPNwgmpeimvHUMVzFfKsjL9GZ1wGw8


//a9059cbb000000000000000000000041fe1bd26b318c7690abab5b4a4de43afa9959f947000000000000000000000000000000000000000000000000000000000bd4aa0d