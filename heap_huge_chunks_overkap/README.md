# New exploitation method For CVE 2022-31626

## Bug summary

This technique uses huge chunks in PHP 7.4.30

We can exploit PHP bug #81719 in PHP 7.4.29.
Bug #81719 is Heap Overflow Bug which allowes to overwrite 4 bytes.
This script demonstrates how to get code execution with overwriting 4 bytes

We will present another techique for this bug in the next article.