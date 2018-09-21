<?php

return array(
    0 =>
        array(
            'name'    => 'qq',
            'title'   => 'QQ',
            'type'    => 'array',
            'content' =>
                array(
                    'app_id'     => '',
                    'app_secret' => '',
                    'scope'      => 'get_user_info',
                ),
            'value'   =>
                array(
                    'app_id'     => '100000000',
                    'app_secret' => '123456',
                    'scope'      => 'get_user_info',
                ),
            'rule'    => 'required',
            'msg'     => '',
            'tip'     => '',
            'ok'      => '',
            'extend'  => '',
        ),
    1 =>
        array(
            'name'    => 'wechat',
            'title'   => '微信',
            'type'    => 'array',
            'content' =>
                array(
                    'app_id'     => '',
                    'app_secret' => '',
                    'callback'   => '',
                    'scope'      => 'snsapi_base',
                ),
            'value'   =>
                array(
                    'app_id'     => '100000000',
                    'app_secret' => '123456',
                    'scope'      => 'snsapi_base',
                ),
            'rule'    => 'required',
            'msg'     => '',
            'tip'     => '',
            'ok'      => '',
            'extend'  => '',
        ),
    2 =>
        array(
            'name'    => 'weibo',
            'title'   => '微博',
            'type'    => 'array',
            'content' =>
                array(
                    'app_id'     => '',
                    'app_secret' => '',
                    'scope'      => 'get_user_info',
                ),
            'value'   =>
                array(
                    'app_id'     => '2018728792',
                    'app_secret' => 'e2dadd7a6b1f39dccf859e34038d4637',
                    'scope'      => 'get_user_info',
                ),
            'rule'    => 'required',
            'msg'     => '',
            'tip'     => '',
            'ok'      => '',
            'extend'  => '',
        ),
    4 =>
        array(
            'name'    => 'google',
            'title'   => '谷歌',
            'type'    => 'array',
            'content' =>
                array(
                    'app_id'     => '',
                    'app_secret' => '',
                    'scope'      => 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email',
                ),
            'value'   =>
                array(
                    'app_id'     => '974658843640-ban0u40f04qahk5lkl9kl1sj06cfmvi4.apps.googleusercontent.com',
                    'app_secret' => 'M0qG1HkI5p89uMn24DNOCkc_',
                    'scope'      => 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email',
                ),
            'rule'    => 'required',
            'msg'     => '',
            'tip'     => '',
            'ok'      => '',
            'extend'  => '',
        ),
    3 =>
        array(
            'name'    => 'rewrite',
            'title'   => '伪静态',
            'type'    => 'array',
            'content' =>
                array(),
            'value'   =>
                array(
                    'index/index'    => '/third$',
                    'index/connect'  => '/third/connect/[:platform]',
                    'index/callback' => '/third/callback/[:platform]',
                ),
            'rule'    => 'required',
            'msg'     => '',
            'tip'     => '',
            'ok'      => '',
            'extend'  => '',
        ),
);
