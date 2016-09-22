#Economy
一个记账在线应用，同时也是一个微PHP框架（暂未剥离)

###注意：正在开发中...

#部署
##数据库
配置文件 Config/Database.php
暂时只有Mysql

##SQL
```sql

--Money records
CREATE TABLE IF NOT EXISTS `money` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `title` text,
  `type` int(11) DEFAULT '0',
  `number` decimal(30,2) DEFAULT '0.00',
  `time` int(10) unsigned zerofill DEFAULT NULL,
  `details` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--User
CREATE TABLE IF NOT EXISTS `user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `user` text,
  `pwd` text,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

```

#Demo
http://booteco.carp.mopaasapp.com