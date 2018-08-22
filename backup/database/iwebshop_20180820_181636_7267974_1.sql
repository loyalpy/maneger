DROP TABLE IF EXISTS `iwebshop_account_log`;
CREATE TABLE `iwebshop_account_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) unsigned DEFAULT '0' COMMENT '管理员ID',
  `user_id` int(11) unsigned DEFAULT NULL COMMENT '用户id',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0增加,1减少',
  `event` tinyint(3) NOT NULL COMMENT '操作类型，意义请看accountLog类',
  `time` datetime NOT NULL COMMENT '发生时间',
  `amount` decimal(15,2) NOT NULL COMMENT '金额',
  `amount_log` decimal(15,2) NOT NULL COMMENT '每次增减后面的金额记录',
  `note` text COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `admin_id` (`admin_id`),
  CONSTRAINT `iwebshop_account_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `iwebshop_user` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='账户余额日志表';

INSERT INTO `iwebshop_account_log` VALUES
('1','1','1','0','1','2018-08-20 17:47:36','100.00','100.00','管理员[admin]给用户[15838281830]充值，金额：100元'),
('2','1','1','0','1','2018-08-20 17:47:46','10000000.00','10000100.00','管理员[admin]给用户[15838281830]充值，金额：10000000元'),
('3','0','1','1','3','2018-08-20 17:47:52','-1019.00','9999081.00','用户[15838281830]使用余额支付购买，订单[20180820174649693728]，金额：-1019元'),
('4','1','1','1','2','2018-08-20 17:49:21','-1.00','9999080.00','管理员[admin]给用户[15838281830]提现，金额：-1元'),
('5','1','1','1','2','2018-08-20 17:51:12','-9999080.00','0.00','管理员[admin]给用户[15838281830]提现，金额：-9999080元'),
('6','1','1','0','1','2018-08-20 17:51:21','10000000.00','10000000.00','管理员[admin]给用户[15838281830]充值，金额：10000000元'),
('7','0','1','1','3','2018-08-20 17:51:44','-1019.00','9998981.00','用户[15838281830]使用余额支付购买，订单[20180820175142699359]，金额：-1019元'),
('8','1','1','1','2','2018-08-20 17:57:22','-1000.00','9997981.00','管理员[admin]给用户[15838281830]提现，金额：-1000元');

