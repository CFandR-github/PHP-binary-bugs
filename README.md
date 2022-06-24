# Advisory of Exploits AI POP Builder

Collection of PHP binary bugs advisory
### Unfixed GMP Type confusion in unserialize

Idea: bypass delayed \_\_wakeup and exploit unfixed GMP type confusion bug in PHP <= 5.6.40

POC source: [GMP_type_conf_POC.php](./GMP_type_conf_unserialize/GMP_type_conf_POC.php)

[Advisory](./GMP_type_conf_unserialize/GMP_type_conf_advisory.md)

### CVE-2022-31626 analysis

Idea: heap buffer overflow in mysqlnd, PHP <= 7.4.29

POC source: [./cve_2022_31626_remote_exploit/exploit_poc.py](./cve_2022_31626_remote_exploit/exploit_poc.py)

[Advisory](./cve_2022_31626_remote_exploit/cve_writeup.md)

# Contacts
Project channel in Telegram:
- [https://t.me/CFandR_project](https://t.me/CFandR_project)