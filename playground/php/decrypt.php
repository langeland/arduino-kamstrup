#!/usr/bin/env php
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use AESGCM\AESGCM;

require __DIR__ . '/vendor/autoload.php';

$encryption_key = '5AD84121D9D20B364B7A11F3C1B5827F';
$authentication_key = 'AFB3F93E3E7204EDB3C27F96DBD51AE0';

$header = str_replace(' ','', '7E A1 E9 41 03 13 C6 37 E6 E7 00');

$cipher_text = 'DB 08 4B 41 4D 45 01 A4 DC 52 82 01 D0 30 00 07 88 E1 A0 39 B2 D1 4C 71 2D D4 D8 C8 44 0D 53 68 E4 33 BD 70 B7 36 81 E9 A9 EF FE 38 F1 75 A3 7D E9 CD E6 4E 8F 78 0D 8F 18 B4 3F C0 59 D8 79 02 F3 D7 47 B8 14 BC D0 6A 47 00 68 78 01 BD 5D 06 61 20 54 50 7D 44 E7 66 98 CC 3E 35 CC 9D E6 2C 28 4C 0D EE A6 35 B9 BF C5 6C E1 FE 5A 3A 1E 5E 27 0B 0C 18 1A CF 02 15 1F CC 59 21 34 D0 4F 02 92 B5 A3 53 38 D7 B7 81 BB 1F 2A 7E 40 71 81 5C EE D5 D4 BA C6 EF AA AF F9 79 EF 96 9D 0D B4 6F 51 E5 E0 FC 00 F5 AD 10 5F BE F9 5F C5 F8 85 46 0B 56 32 55 4A C0 5D 9E B4 F6 5F F2 23 97 2A 47 CF D4 34 B5 F5 E2 D8 53 EA 4C 14 72 75 86 F0 E2 1C 6E E1 25 26 8C B4 DC 7E C5 B1 0F 84 83 C0 10 C8 E6 88 DF 86 58 4D 7C 29 D8 17 31 A4 E0 96 91 41 B6 AD D9 42 E4 0A 96 E3 E2 DC 2F 90 20 BF 9D 58 02 A1 8D C9 85 BB 54 22 F4 70 C0 62 9D 22 DB 6F 16 B6 64 7D B3 C9 F7 27 C1 70 C2 DB 4C 9A 23 0F 82 83 46 E8 6F 56 D3 47 B6 2B FE 28 A5 07 AE 2A 21 9A CC 63 AD 5E E0 6E CB 94 9D C4 FD C2 D2 F8 08 02 7A 4A 4C 67 7C 93 C4 C8 90 9D 73 36 D8 B8 B3 79 18 36 C5 55 5E 74 E2 54 82 A4 4A 5F 6D 35 8D CE 84 50 47 8F 8B 2C 5D 56 66 C2 CF 8B D1 90 D4 87 4E 70 7F 5D 4B CD E9 4E 91 35 5A 81 39 C3 E4 1C D5 F0 88 99 B6 5E E9 B5 9F C6 03 72 E4 F9 2B C9 98 26 B8 C1 47 F2 09 5F B3 8A 89 14 09 AA 81 E2 27 07 6C 21 CB 7C AF 73 B3 E8 A4 E2 56 C7 0D 95 47 A9 14 F3 9C 16 93 76 BF 92 2B 08 06 4E C4 FB 31 D4 4E CD 72 1D 1A 15 1E 4E 68 4F 0B 26 85 C4 B6 9D 96 F5 FB 52 D0 B8 12 79';
$cipher_text = str_replace(' ','', $cipher_text);

$text = '0f000000000c07e40107020e2f14ff80000002410a0e4b616d73747275705f563030303109060101010800ff06000d394c09060101020800ff060000000009060101030800ff060000452c09060101040800ff060000000009060101000001ff0601a4dc5209060101010700ff060000000009060101020700ff060000000009060101030700ff060000000009060101040700ff060000000009060001010000ff090c07e40107020e2f14ff80000009060101200700ff1200e009060101340700ff1200df09060101480700ff1200df090601011f0700ff060000000009060101330700ff060000000009060101470700ff060000000009060101150700ff060000000009060101290700ff0600000000090601013d0700ff060000000009060101210700ff12006409060101350700ff12006409060101490700ff120064090601010d0700ff12006409060101160700ff0600000000090601012a0700ff0600000000090601013e0700ff060000000009060101160800ff0600000000090601012a0800ff0600000000090601013e0800ff060000000009060101150800ff06000468a409060101290800ff060004678c090601013d0800ff060004691b
';

$footer = str_replace(' ','', '36 24 7E');

$system_title = substr ( $cipher_text, 2*2, 8*2);

$initialization_vector = $system_title . substr($cipher_text, 14*2, (14+4)*2);
$additional_authenticated_data = substr($cipher_text, 13*2, (13+1)*2) . $authentication_key;
$authentication_tag = substr($cipher_text, strlen($cipher_text)-12*2, strlen($cipher_text));

print_r(
	[
		'encryption_key' => $encryption_key,
		'initialization_vector' => $initialization_vector,
		'cipher_text' => $cipher_text,
		'additional_authenticated_data' => $additional_authenticated_data,
		'authentication_tag' => $authentication_tag
	]
);

$cipher = AESGCM::decrypt(
	hex2bin($encryption_key),
	hex2bin($initialization_vector),
	hex2bin($cipher_text),
	hex2bin($additional_authenticated_data),
	hex2bin($authentication_tag)
);


if($cipher === $text){
	echo 'Now thats cool !! \n' . $cipher;
} else {
	echo 'Now thats NOT cool !! \n' . $cipher . PHP_EOL . PHP_EOL;
	echo $text . PHP_EOL;
}

echo PHP_EOL;


/*
// The Key Encryption Key
$K = hex2bin('feffe9928665731c6d6a8f9467308308feffe9928665731c');

// The data to encrypt (can be null for authentication)
$P = hex2bin('d9313225f88406e5a55909c5aff5269a86a7a9531534f7da2e4c303d8a318a721c3c0c95956809532fcf0e2449a6b525b16aedf5aa0de657ba637b39');

// Additional Authenticated Data
$A = hex2bin('feedfacedeadbeeffeedfacedeadbeefabaddad2');

// Initialization Vector
$IV = hex2bin('cafebabefacedbaddecaf888');

// $C is the encrypted data ($C is null if $P is null)
// $T is the associated tag
list($C, $T) = AESGCM::encrypt($K, $IV, $P, $A);
// The value of $C should be hex2bin('3980ca0b3c00e841eb06fac4872a2757859e1ceaa6efd984628593b40ca1e19c7d773d00c144c525ac619d18c84a3f4718e2448b2fe324d9ccda2710')
// The value of $T should be hex2bin('2519498e80f1478f37ba55bd6d27618c')

$P = AESGCM::decrypt($K, $IV, $C, $A, $T);
// The value of $P should be hex2bin('d9313225f88406e5a55909c5aff5269a86a7a9531534f7da2e4c303d8a318a721c3c0c95956809532fcf0e2449a6b525b16aedf5aa0de657ba637b39')


*/