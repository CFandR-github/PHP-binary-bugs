# New exploitation method For CVE 2022-31626

## Bug summary

This technique uses huge chunks in PHP 7.4.30

We can exploit PHP bug [#81719](https://bugs.php.net/bug.php?id=81719) in PHP 7.4.29 with this technique.\
Bug #81719 is Heap Overflow Bug which allows to overwrite 4 bytes.\
This script demonstrates how to get code execution with overwriting 4 bytes.