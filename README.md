# 安装

~~~bash
composer require lishun/enums
~~~



# 简述

提供两种枚举的应用，一种是对常规枚举类的加强和扩展，即枚举类扩展，继承原枚举类原生功能的基础上拓展了注解属性，出发点源自于一些枚举的值确实一致但意义却不一样的场景：

```shell
tb_user.gender:
0 未知
1 男
2 女

tb_user.type:
0 普通
1 特殊类型1
2 特殊类型2

```

此时值确实一致，但需要的解释和描述却完全不一致，这是通过 const 常量的注解已经无法实现这个功能，或者只能将原 const 值 变为数组，这并不优雅，这时通过对无值枚举类的注解，可以实现值一致，并且附带其余解释和分组与多重分组功能。

另一种是对业务中常见的错误码进行收纳和管理，并且提供前缀功能。让错误码几乎不可能出现重复，并且抛出时变的更加简单。

# 枚举类扩展

~~~php
use Lishun\Enums\Annotations\EnumCase;
use Lishun\Enums\Interfaces\EnumCaseInterface;
use Lishun\Enums\Traits\EnumCaseGet;

/**
 * @method getTest()
 */
enum DemoEnum implements EnumCaseInterface
{
    use EnumCaseGet;

    #[EnumCase(msg: '系统错误', data: 1, group: 'sys', ext: ['test'=>1,'type'=>2])]
    case SYSTEM_ERROR;

    #[EnumCase(msg: '系统错误2', data: 2, group: ['sys', 'sys2'])]
    case SYSTEM_ERROR2;

    #[EnumCase('系统错误3', 2)]
    case SYSTEM_ERROR3;
}

~~~

## 枚举函数

### 获取枚举解释信息

~~~php

// 获取解释信息
DemoEnum::SYSTEM_ERROR->getMsg(); // msg:系统错误

~~~



### 获取枚举拓展数据

~~~php

// 获取枚举拓展数据
DemoEnum::SYSTEM_ERROR->getExt(); // ext: ['test'=>1,'type'=>2]

~~~



#### 获取枚举拓展数据的某个值

~~~php

// 获取枚举拓展数据的某个值
DemoEnum::SYSTEM_ERROR->getExt('test'); // 1

// 这个方法需要你在原类上加上注释 @method getTest()
DemoEnum::SYSTEM_ERROR->getTest(); // 1

// 这个方法需要你在原类上加上注释 @method test()
DemoEnum::SYSTEM_ERROR->test(); // 1

~~~



### 获取枚举注解数据

~~~php
// 获取枚举注解数据
DemoEnum::SYSTEM_ERROR->getData();

~~~



### 获取枚举分组名

~~~php
// 获取枚举附属数据
DemoEnum::SYSTEM_ERROR->getGroup();

~~~



### 获取枚举的分组数据

~~~php

// 获取枚举分组，将返回一个数组，如果传入值为单个的情况仅返回单个分组，传入值为数组的情况下会返回以分组名为key的多维数组
DemoEnum::getGroupEnums('sys');
DemoEnum::getGroupEnums(['sys','sys2']);
DemoEnum::getGroupEnums(DemoEnum::SYSTEM_ERROR->getGroup())

//数据结构如下：
DemoEnum::getGroupEnums('sys');
{
    "SYSTEM_ERROR": {
        "name": "SYSTEM_ERROR",
        "value": null,
        "msg": "系统错误",
        "data": 1,
        "group": "sys",
        "ext": [
            1,
            2,
            3
        ]
    },
    "SYSTEM_ERROR2": {
        "name": "SYSTEM_ERROR2",
        "value": null,
        "msg": "系统错误2",
        "data": 2,
        "group": [
            "sys",
            "sys2"
        ],
        "ext": null
    }
}

DemoEnum::getGroupEnums(['sys','sys2']);
"sys": {
    "SYSTEM_ERROR": {
        "name": "SYSTEM_ERROR",
        "value": null,
        "msg": "系统错误",
        "data": 1,
        "group": "sys",
        "ext": [
            1,
            2,
            3
        ]
    },
    "SYSTEM_ERROR2": {
        "name": "SYSTEM_ERROR2",
        "value": null,
        "msg": "系统错误2",
        "data": 2,
        "group": [
            "sys",
            "sys2"
        ],
        "ext": null
    }
},
"sys2": {
    "SYSTEM_ERROR2": {
        "name": "SYSTEM_ERROR2",
        "value": null,
        "msg": "系统错误2",
        "data": 2,
        "group": [
            "sys",
            "sys2"
        ],
        "ext": null
    }
}

    
~~~



### 将枚举转换为数组

~~~php

// 将枚举转换为数组
DemoEnum::SYSTEM_ERROR2->toArray(); 
{
    "name": "SYSTEM_ERROR2",
    "value": null,
    "msg": "系统错误2",
    "data": 2,
    "group": [
        "sys",
        "sys2"
    ],
    "ext": null
}

~~~



## 错误码

~~~php

use Lishun\Enums\Annotations\EnumCode;
use Lishun\Enums\Annotations\EnumCodePrefix;
use Lishun\Enums\Interfaces\EnumCodeInterface;
use Lishun\Enums\Traits\EnumCodeGet;

#[EnumCodePrefix(10, '系统错误码')]
enum DemoCode: int implements EnumCodeInterface
{
    use EnumCodeGet;

    // 错误码: 10500, 错误信息: 系统错误
    #[EnumCode('系统错误')]
    case SYSTEM_ERROR = 500;


    #[EnumCode(msg:'系统错误1')]
    case SYSTEM_ERROR1 = 501;
    
     #[EnumCode(msg:'系统错误2',ext:['test'=1])]
    case SYSTEM_ERROR2 = 502;
}
~~~



### 获取错误码

~~~php

// 获取错误码
DemoCode::SYSTEM_ERROR->getCode(); // 10500

~~~



### 获取错误码解释

~~~php

// 获取错误码解释
DemoCode::SYSTEM_ERROR->getMsg(); // 系统错误

~~~



### 获取错误码前缀

~~~php

// 获取错误码前缀
DemoCode::SYSTEM_ERROR->getPrefixCode(); // 10

~~~



### 获取错误码前缀注释

~~~php

// 获取错误码前缀注释
DemoCode::SYSTEM_ERROR->getPrefixMsg(); // 系统错误码
~~~



### 获取枚举拓展数据的某个值

~~~php
// 获取枚举拓展数据的某个值
DemoCode::SYSTEM_ERROR->getExt('test'); // 1

// 这个方法需要你在原类上加上注释 @method getTest()
DemoCode::SYSTEM_ERROR->getTest(); // 1

// 这个方法需要你在原类上加上注释 @method test()
DemoCode::SYSTEM_ERROR->test(); // 1
~~~


### 将错误码转换为数组

~~~php

// 将枚举转换为数组
DemoEnum::SYSTEM_ERROR2->toArray(); 
{
    "name": "SYSTEM_ERROR",
    "value": 500,
    "msg": "系统错误",
    "code": 10500,
    "ext": null,
    "pre": {
    "prefixCode": 10,
    "prefixMsg": "系统错误码"
}


~~~


# 鸣谢
- 参照了朋友杰哥的包：https://github.com/duncanxia97/enum
- 参照的开源包：https://github.com/Elao/PhpEnums





