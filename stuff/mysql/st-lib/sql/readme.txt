--------------------------------------------------------------------
MySQL Dump / Restore Utility v1.4 - februari 4th, 2005

By Erik van Geene <info@virogo.com>
--------------------------------------------------------------------

This package contains:

sql_backup				<EMPTY DIR>
sql_process				<EMPTY DIR>
include				<DIR>
	db_configSQL.php
db_backupSQL.php
db_restoreSQL.php
readme.txt

---

v1.4 Finetuning

   - Progress printed on screen during backup
   - Tested linebuffering up to 615000 bytes for e.g. vBulletin

v1.3 Bugfixes

   - Error when backing up and restoring SQL-files which contain
     records which are bigger than 4 Kb. This was because the line-
     buffer for each line to be read, was set to 4096. Now it can be
     set in the Config-file and is default set to 16384 (16 Kb/line)

v1.2 Bugfixes

   - Made things configurable with the configuration file

v1.1 Bugfixes

   - Don't know anymore. Version Control started since v1.2 ;-)

v1.0 Initial release

--------------------------------------------------------------------
db_backupSQL.php
--------------------------------------------------------------------

With db_backupSQL.php you can easily backup an entire MySQL database,
store it on the server as an SQL-dumpfile. You can save it as ASCII
or as a GZIP compressed file (if your environment supports GZIP).
Furthermore, it can mail a GZIPped dump to multiple addresses.

Safe_mode or not, you are able to specify any lenght of time for
execution of this script.

---

MySQL versions 3.23.3x and higher
PHP 4.0.x and higher

---

Based upon Dump Utility from Transatlantic-Web http://www.taw24.de
Modified by Benjamin Klatt <benjamin.klatt@raytion.com>
Heavily modified / re-progged by Erik van Geene <info@virogo.com>


--------------------------------------------------------------------
db_restoreSQL.php
--------------------------------------------------------------------

With db_restoreSQL.php you can easily restore your entire database
using a single SQL-dump. You can select a dump made by
db_backupSQL.php or upload one.
You can upload either an ASCII .sql file or a GZIP compressed file.
You can restore the file in one piece, or if you run into time-out
problems, chop it in pieces.

---

Some figures during production tests:

a) 1 table of 9 MB with more than 27.000 records uploaded as .gz,
   processed in one piece in 17 seconds.

b) 61 tables 7 MB total with more than 50.000 records, splitted
   into 14 parts, setting max_execution_time on the server at 15
   seconds. Using refresh-option after each piece.
   Database was succesfully restored within 1 minute.

---

