<?php
/*
Plugin Name: MobiDevices Translate URL
Plugin URI: http://mobidevices.ru
Description: Плагин для автоматического перевода русских ярлыков (URL) на английский язык, разработанный порталом <a href="http://mobidevices.ru">MobiDevices</a>.
Version: 3.2.5
Author: MobiDevices Soft
Author URI: http://mobidevices.ru
Author Email: md@mobidevices.ru
*/

function md_url($title){
    $url = $title;
    $locale = preg_replace('/([a-z]*)_[A-Z]*/ ','\\1',get_locale());
    $google = 'http://translate.google.ru/translate_a/t?client=t&text='.urlencode($title).'&hl='.$locale.'&tl=en&ie=UTF-8&oe=UTF-8&multires=1&oc=6&prev=btn&ssel=0&tsel=0&sc=1';
    $args = array('User-Agent' => 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.56 Safari/536.5','Referer' => 'http://translate.google.ru');
    $http = new WP_Http();
    $result = $http->get($google, $args);
    if (isset($result['response']['code']) && $result['response']['code'] == 200 && isset($result['body']) && !empty($result['body'])) {
        $url = sanitize_user(explode('"',str_replace('\\"','',$result['body']),3)[1],true);
        $place = array(
            ' on the '=>'-',
            'on the '=>'-',
            ' on the'=>'-',
            ' of the '=>'-',
            ' will '=>'-',
            ' the '=>'-',
            'the '=>'',
            ' be '=>'-',
            ' on '=>'-',
            ' of '=>'-',
            ' in '=>'-',
            ' is '=>'-',
            ' to '=>'-',
            ' a '=>'-',
        );
        return str_replace(array_keys($place),$place,$url);
    }
    return $url;
}
function md_name($title){
    $url = $title;
    $locale = preg_replace('/([a-z]*)_[A-Z]*/ ','\\1',get_locale());
    $file = substr(strrchr($title,'.'),1);
    $title = str_replace('.'.$file,'',$title);
    $google = 'http://translate.google.ru/translate_a/t?client=t&text='.urlencode($title).'&hl='.$locale.'&tl=en&ie=UTF-8&oe=UTF-8&multires=1&oc=6&prev=btn&ssel=0&tsel=0&sc=1';
    $args = array('User-Agent' => 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.56 Safari/536.5','Referer' => 'http://translate.google.ru');
    $http = new WP_Http();
    $result = $http->get($google, $args);
    if (isset($result['response']['code']) && $result['response']['code'] == 200 && isset($result['body']) && !empty($result['body'])) {
        $title = sanitize_user(explode('"',str_replace('\\"','',$result['body']),3)[1],true);
        $text = str_replace(' ','',strtolower($title));
        $url = $text.'.'.$file;
    }
    return $url;
}
if(!empty($_POST)||!empty($_GET['action'])&&$_GET['action']=='edit' || defined('XMLRPC_REQUEST') && XMLRPC_REQUEST ){
    add_action('sanitize_title','md_url',0);
    add_action('sanitize_file_name','md_name');
}