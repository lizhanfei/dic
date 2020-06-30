
# 高性能词语匹配和词语联想
[![Build Status](https://travis-ci.com/lizhanfei/dic.svg?branch=master)](https://travis-ci.com/lizhanfei/dic)
[![codecov](https://codecov.io/gh/lizhanfei/dic/branch/master/graph/badge.svg)](https://codecov.io/gh/lizhanfei/dic)
<a href="https://github.com/swoole/swoole-src"><img src="https://img.shields.io/badge/swoole-%3E=4.4-brightgreen.svg?maxAge=2592000" alt="Swoole Version"></a>
 <a href="https://secure.php.net/"><img src="https://img.shields.io/badge/php-%3E=7.2-brightgreen.svg?maxAge=2592000" alt="Php Version"></a>
### 应用场景
    1. 可以输入的句子中是否存下词库中的词，可以用于敏感词、优质词匹配
    2. 可以通过输入的词语查找所有词库中相关的词，可以用于关键词联想
    3. 系统是基于一个业务系统需求自己开发，没有完整的功能测试
### 性能说明
    1. 采用内存缓存的方式将词典数据在进程启动时初始化到内存中，极大的提高处理速度
    2. 词库2W+的基础上，wrk测试600并发，30S压测；
        19款macos，8核心；框架开启8进程；qps可以接近7W/s
    
### 压测数据
    程序宿主机：6C16G 普通pc机  i5 8400
    网络：局域网
    worker num: 6
command: wrk -t 8 -c 600 -d 30s -s dic_sentence_matych.lua  http://192.168.8.52:9501/dic/sentence/match
```shell
Running 1m test @ http://192.168.8.52:9501/dic/sentence/match
  8 threads and 600 connections
  Thread Stats   Avg      Stdev     Max   +/- Stdev
    Latency    12.56ms   54.38ms 951.98ms   98.91%
    Req/Sec     4.01k     1.42k   11.10k    63.21%
  1916781 requests in 1.00m, 424.09MB read
  Socket errors: connect 355, read 0, write 0, timeout 0
Requests/sec:  31891.52
Transfer/sec:      7.06MB
```
    
### 实现说明
    1. 核心词典匹配字典树，借助[abelzhou/php-trie-tree](https://packagist.org/packages/abelzhou/php-trie-tree)实现。
    2. 每个worker进程启动时，将词典从数据库中加载到内存里，建立一个字典树，做内存级词典缓存。
    3. 每隔指定时间，或者默认10分钟，程序会定时从数据库更新词典到内存。

### 接口
    1. 词典维护-新增词语
    2. 词典维护-移除词语
    3. 语句匹配
    4. 词语联想

### sql
    数据库字典在./sql 目录下。导入数据库即可。
   
### 注意
    1. 词典加载可能会暂用比较多的内存，尤其是在字典重建时，内存峰值会是平时的两倍，请注意内存的分配
    2. 峰值内存在系统加载完之后，在日志中有记录
    3. 默认二~~~~十分钟会重置词典，建议不要设置过高频率，词典重置时会导致性能下降
    4. 初始启动时，会有一段服务初始化时间，这段时间服务不可用，时间长短根据数据量大小决定，可以结合日志确定