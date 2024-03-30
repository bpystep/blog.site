[client]
port		              = 3306
socket		            = /var/run/mysqld/mysqld.sock
default-character-set = utf8mb4

[mysql]
default-character-set = utf8mb4

[mysqld_safe]
socket = /var/run/mysqld/mysqld.sock
nice	 = 0

[mysqld]
user		     = mysql
pid-file	   = /var/run/mysqld/mysqld.pid
socket		   = /var/run/mysqld/mysqld.sock
port		     = 3306
#basedir     = /usr
datadir		   = /var/lib/mysql
tmpdir		   = /tmp
bind-address = 127.0.0.1
mysqlx-bind-address = 127.0.0.1

character-set-client-handshake = FALSE
character-set-server           = utf8mb4
collation-server               = utf8mb4_unicode_ci

skip-external-locking

default_authentication_plugin = mysql_native_password

sql_mode = STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION

max_allowed_packet = 64M
key_buffer_size	   = 32M

innodb_buffer_pool_size        = 65M
innodb_log_buffer_size         = 3M
innodb_log_file_size           = 32M
innodb_file_per_table          = 1
innodb_flush_method            = O_DIRECT
innodb_flush_log_at_trx_commit = 0

max_connections = 132

slow_query_log   = 1
long_query_time  = 1
expire_logs_days = 10
max_binlog_size  = 100M

[mysqldump]
quick
quote-names
max_allowed_packet	= 64M
