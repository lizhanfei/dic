
# 高性能词语匹配和词语联想
[![Build Status](https://travis-ci.com/lizhanfei/dic.svg?branch=master)](https://travis-ci.com/lizhanfei/dic)
[![codecov](https://codecov.io/gh/lizhanfei/dic/branch/master/graph/badge.svg)](https://codecov.io/gh/lizhanfei/dic)
### 应用场景
    1. 可以输入的句子中是否存下词库中的词，可以用于敏感词、优质词匹配
    2. 可以通过输入的词语查找所有词库中相关的词，可以用于关键词联想
    
### 性能说明
    1. 采用内存缓存的方式将词典数据在进程启动时初始化到内存中，极大的提高处理速度
    2. 词库2W+的基础上，wrk测试600并发，30S压测；
        19款macos，8核心；框架开启8进程；qps可以接近7W/s
    
### 接口
    1. 词典维护-新增词语
    2. 词典维护-移除词语
    3. 语句匹配
    4. 词语联想

### sql
    数据库字典在./sql 目录下。导入数据库即可。
### 未完待续