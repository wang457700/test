<?php

//上傳配置
return [
    /**
     * 上傳地址,默认是本地上傳
     */
    'uploadurl' => 'ajax/upload',
    /**
     * CDN地址
     */
    'cdnurl'    => '',
    /**
     * 文件保存格式
     */
    'savekey'   => '/uploads/{year}{mon}{day}/{filemd5}{.suffix}',
    /**
     * 最大可上傳大小
     */
    'maxsize'   => '10mb',
    /**
     * 可上傳的文件類型
     */
    'mimetype'  => 'jpg,png,bmp,jpeg,gif,zip,rar,xls,xlsx',
    /**
     * 是否支持批量上傳
     */
    'multiple'  => false,
];
