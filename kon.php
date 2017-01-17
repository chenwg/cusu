<?php
（个人总结）
1.用 clone 可以复制一个全新的副本对象，新对象和老对象互不干扰

2.在存储字符串时可以对字符串进行压缩，使用 gzcompress()，解压使用 gzuncompress(),
压缩可以将其大小减少40%，一些php框架在生成文件缓存时就是这么做的

3.elseif 的效率比较低，可以使用多个if 或者 switch代替

4.可以使用多个 && 来直接判断执行最后一个语句

5.PHP工厂模式就是用一个工厂方法来替换掉直接new对象的操作。就是把很多对象放到一个对象里，
然后通过调用这个对象的方法来调用其他对象。

6.单例模式就是一个对象只负责一个特定的任务，不能让外部使用new 操作符操作对象

7.需要严格限制参数的方法可以统一写在抽象类或者接口里，统一管理

8.使用 1 == $intNum 的判断方式比较好

9.jquery的pjax和NProgress搭配效果还不错

10.对于条件很多的sql查询，我们可以分多个条件组简化查询，然后使用 array_intersect()
或者 array_intersect_assoc() 去多个结果的交集。

11.sql 的limit越到后面越慢，因为每次取数据都需要整个排序，一个比较简单的解决办法是获取最后取到的id，
然后去id>这个的再进行limit，不过这样去取只能用户id排序取值，对其他的字段排序不行.

12.array_column($array,$in)返回输入数组中某个单一列值，这个挺实用的

13.小知识 PHP_EOL替代换行符

14.mysql全文索引 contains 查询住址有北京的地址 select * from address where contains(address,'beijing')
单词的多态查询 select address from where contains(address,'formsof (inflectional,street) ')
查询将返回包含'street','streets'等字样的地址，这个比like效率要高些

15.用链式操作代替各种情况的判断和逻辑，一个方法最好不要超过20行，循环不要超过三重，if else不要超过4个，
超过4个可以考虑用设计模式代替

16.各个模块的目录一定要独立和清晰，一个模块用一个目录，一个功能用一个目录。这是泪的教训啊

17.命名用英文驼峰法,不能用拼音或者各种随性的简写

18.在涉及的文件读写操作时最好先 if(ob_get_contents()){ob_end_clean();}清除下输出缓存

19.合理使用 protected static private public，调用类静态方法的效率要好些，不过也要合理使用

20.金额存储最好用int型以分为单位存储，数据库只是存数据的地方，能少点数据库操作就少点，操作数据库是比较耗时的
能用到缓存就尽量用缓存，文件缓存 redis memcached等缓存方式合理使用,nosql也可以用












1
