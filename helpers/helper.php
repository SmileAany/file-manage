<?php

if(!function_exists('custom_file_size_trans')){
    /**
     * @describe
     * 转换文件大小
     * @param int $size
     * @return string
     * @auth Smbear
     * @email ywjmylove@163.com
     * @date 2020-10-12 11:19
     */
    function custom_file_size_trans(int $size){

        if(!empty($size)){

            $units = array('B', 'KB', 'MB', 'GB', 'TB');

            for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;

            return round($size, 2).$units[$i];
        }

        return '0KB';
    }
}