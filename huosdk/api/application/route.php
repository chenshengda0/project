<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
return [
    '__pattern__' => [
        'name' => '\w+', 
        'id' => '\d+' 
    ], 
    // '__rest__' => [
    // ':version/game' => 'api/:version.Game',
    // ':version/ads' => 'api/:version.ads'
    // ],
    
    // 轮播图
    ':version/slide/list' => 'api/:version.Ads/index', /* 轮播图列表 */
    
    // 游戏
    ':version/game/list' => 'api/:version.Game/index', 
    ':version/game/agent_list' => 'api/:version.Game/agent_list', 
    ':version/game/detail' => 'api/:version.Game/read', 
    ':version/game/game_shot' => 'api/:version.Game/readShot',  /* 获取游戏截图  */
    ':version/game/type_list' => 'api/:version.Gametype/index', 
    ':version/game/down' => 'api/:version.Game/down', 
    
    // 游戏评论
    ':version/game/comment_list' => 'api/:version.Gamecomment/index', 
    ':version/game/comment_add' => 'api/:version.Gamecomment/save', 
    
    // 搜索
    ':version/search/list' => 'api/:version.Search/index',  // 搜索列表
    ':version/search/recommend_list' => 'api/:version.Search/recommend',  // 搜索推荐词
    ':version/search/hotword_list' => 'api/:version.Search/hotword',  // 搜索热门词
                                                                     
    // 资讯
    ':version/news/list' => 'api/:version.News/index',  // 资讯列表
    ':version/news/getdetail' => 'api/:version.News/read',  // 获取资讯详情
    ':version/news/webdetail/:id' => 'http://www.shouyoucun.cn/float.php/Mobile/news/index/id/:id',  // 资讯详情页
                                                                                                
    // 礼包
    ':version/gift/list' => 'api/:version.Gift/index',  // 礼包列表
    ':version/gift/detail' => 'api/:version.Gift/read',  // 礼包详细信息
    ':version/game/gift_list' => 'api/:version.Gift/gameindex',  // 游戏相关礼包列表
    ':version/user/gift/add' => 'api/:version.Gift/save',  // 玩家领取礼包
    ':version/user/gift/list' => 'api/:version.Gift/userindex',  // 玩家礼包列表
                                                                
    // 激活码
    ':version/cdkey/list' => 'api/:version.Cdkey/index',  // 礼包列表
    ':version/cdkey/detail' => 'api/:version.Cdkey/read',  // 礼包详细信息
    ':version/game/cdkey_list' => 'api/:version.Cdkey/gameindex',  // 游戏相关礼包列表
    ':version/user/cdkey/add' => 'api/:version.Cdkey/save',  // 玩家领取礼包
    ':version/user/cdkey/list' => 'api/:version.Cdkey/userindex',  // 玩家礼包列表
                                                                  
    // 系统
    ':version/system/openapp$' => 'api/:version.Startup/save',  // 打开app
    ':version/system/has_new_version$' => 'api/:version.Startup/readNewVer',  // 获取是否有新版本
    ':version/system/get_version_info$' => 'api/:version.Startup/readVerinfo',  // 获取版本信息
    ':version/system/get_server_time$' => 'api/:version.Startup/readTime',  // 获取服务器时间
    ':version/system/get_splash$' => 'api/:version.Startup/readSplash',  // 获取开机闪屏图
    ':version/system/get_help_info$' => 'api/:version.Startup/readHelp',  // 获取客服信息
                                                                         
    // 短信
    ':version/smscode/send$' => 'api/:version.Smscode/create',  // 发送短信验证码
    ':version/smscode/check$' => 'api/:version.Smscode/read',  // 验证短信验证码
                                                              
    // 用户
    ':version/user/add$' => 'api/:version.User/insert',  // 用户注册
    ':version/user/login$' => 'api/:version.User/login',  // 用户登录
    ':version/user/check_exist' => 'api/:version.User/check',  // 检查用户是否存在
    ':version/user/get_user_info$' => 'api/:version.User/read',  // 读取用户信息
    ':version/user/update$' => 'api/:version.User/update',  // 更新用户信息
    ':version/user/game/like_add$' => 'api/:version.Usergame/like',  // 游戏点赞
    ':version/user/game/like_del$' => 'api/:version.Usergame/dislike',  // 游戏取消点赞
    ':version/user/game/like_list$' => 'api/:version.Usergame/likelist',  // 用户收藏的游戏列表
    ':version/user/game/play_list$' => 'api/:version.Usergame/playlist',  // 用户收藏的游戏列表
                                                                         
    // 用户钱包
    ':version/user/wallet/get_balance$' => 'api/:version.Wallet/read',  // 获取钱包余额
                                                                       
    // 网页处理
    /*
     * ':version/user/check_exist' => 'api/:version.User/check', // 检查用户是否存在
     * ':version/user/get_user_info$' => 'api/:version.User/read', // 读取用户信息
     * ':versi on/user/update$' => 'api/:version.User/update'
     */
    ':version/user/passwd/find$' => 'http://www.shouyoucun.cn/float.php/Mobile/Forgetpwd/index',  // 找回用户密码
    // ':version/user/passwd/update$' => 'http://www.shouyoucun.cn/float.php/Mobile/Forgetpwd/index',  // 找回用户密码
    ':version/user/passwd/update$' => 'http://www.shouyoucun.cn/float.php/Mobile/Password/uppwd',  // 修改密码
    ':version/user/phone/add$' => 'http://www.shouyoucun.cn/float.php/Mobile/Security/mobile',  // 密保手机
    ':version/user/email/add$' => 'http://www.shouyoucun.cn/float.php/Mobile/Security/email',  // 密保手机
    ':version/user/wallet/add$' => 'http://www.shouyoucun.cn/float.php/Mobile/Wallet/charge',  // 钱包充值
    ':version/user/wallet/add_list$' => 'http://www.shouyoucun.cn/float.php/Mobile/Wallet/charge_detail',  // 用户钱包充值记录
    ':version/user/wallet/pay_list$' => 'http://www.shouyoucun.cn/float.php/Mobile/Wallet/pay_detail', 
    ':version/user/wallet/result$' => 'http://www.shouyoucun.cn/float.php/Mobile/Wallet/payresult' 
];


 




