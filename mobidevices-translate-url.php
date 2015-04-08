<?php
/*
Plugin Name: MobiDevices Translate URL
Plugin URI: http://mobidevices.ru
Description: Плагин для автоматического перевода русских ярлыков (URL) на английский язык, разработанный порталом <a href="http://mobidevices.ru">MobiDevices</a>.
Version: 3.5
Author: MobiDevices Soft
Author URI: http://mobidevices.ru
Author Email: md@mobidevices.ru
*/

function md_url($title){
    $curlHandle = curl_init();
    $postData=array();
    $postData['client']= 't';
    $postData['text']= $title;
    $postData['hl'] = 'en';
    $postData['sl'] = get_locale();
    $postData['tl'] = 'en';
    curl_setopt($curlHandle, CURLOPT_URL, 'http://translate.google.com/translate_a/t');
    curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array(
        'User-Agent: Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.1.4) Gecko/20091016 Firefox/3.5.4',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Accept-Language: ru,en-us;q=0.7,en;q=0.3',
        'Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7',
        'Keep-Alive: 300',
        'Connection: keep-alive'
    ));
    curl_setopt($curlHandle, CURLOPT_HEADER, 0);
    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curlHandle, CURLOPT_TIMEOUT, 10);
    curl_setopt($curlHandle, CURLOPT_POST, 0);
    if ( $postData!==false ) {
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, http_build_query($postData));
    }
    $content = curl_exec($curlHandle);
    curl_close($curlHandle);
    $content = str_replace(',,',',"",',$content);
    $content = str_replace(',,',',"",',$content);
    $result = json_decode($content);
    return $result[0][0][0];
}
function md_name($title){
    $curlHandle = curl_init();
    $postData=array();
    $postData['client']= 't';
    $file = substr(strrchr($title,'.'),1);
    $title = str_replace('.'.$file,'',$title);
    $postData['text']= $title;
    $postData['hl'] = 'en';
    $postData['sl'] = get_locale();
    $postData['tl'] = 'en';
    curl_setopt($curlHandle, CURLOPT_URL, 'http://translate.google.com/translate_a/t');
    curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array(
        'User-Agent: Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.1.4) Gecko/20091016 Firefox/3.5.4',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Accept-Language: ru,en-us;q=0.7,en;q=0.3',
        'Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7',
        'Keep-Alive: 300',
        'Connection: keep-alive'
    ));
    curl_setopt($curlHandle, CURLOPT_HEADER, 0);
    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curlHandle, CURLOPT_TIMEOUT, 10);
    curl_setopt($curlHandle, CURLOPT_POST, 0);
    if ( $postData!==false ) {
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, http_build_query($postData));
    }
    $content = curl_exec($curlHandle);
    curl_close($curlHandle);
    $content = str_replace(',,',',"",',$content);
    $content = str_replace(',,',',"",',$content);
    $result = json_decode($content);
    $text = $result[0][0][0].'.'.$file;
    $name = str_replace(' ','',strtolower($text));
    return $name;
}
if(!empty($_POST)||!empty($_GET['action'])&&$_GET['action']=='edit' || defined('XMLRPC_REQUEST') && XMLRPC_REQUEST ){
    add_action('sanitize_title','md_url',0);
    add_action('sanitize_file_name','md_name');
}