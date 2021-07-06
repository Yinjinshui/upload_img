<?php
/*
【客户端】
使用postman测试
curl --location --request POST 'pdm-image.gw-ec.com/s3.php' \
--header 'Cookie: XDEBUG_SESSION=PHPSTORM' \
--form 'pdmDomain="http://image.vevor-local.com"' \
--form 'orgImg="/cache/imagecache/soa/vevor/pdm-product-pic/Electronic/2020/07/24/goods_thumb-v1/20200724150457_44347.jpg"' \
--form 'saveFile="soas/vevor/pdm-product-pic/Electronic/2020/07/24/goods_thumb-v1/20200724150457_44347.jpg"'

参数demo post请求:
pdmDomain:http://image.vevor-local.com
orgImg:/cache/imagecache/soa/vevor/pdm-product-pic/Electronic/2020/07/24/goods_thumb-v1/20200724150457_44347.jpg
saveFile:soas/vevor/pdm-product-pic/Electronic/2020/07/24/goods_thumb-v1/20200724150457_44347.jpg
*/
#将远程图片上传到s3【服务端】
$pdmDomain=$_POST['pdmDomain'] ??""; #域名【图片资源所在地址】
$orgImg=$_POST['orgImg'] ??""; #图片路径
$saveFile=$_POST['saveFile'] ??""; #图片要保存的扇区


if(empty($pdmDomain) || empty($orgImg) || empty($saveFile)){
    echo false;die;
}

/**
 * @param string $path
 * @return bool|string
 * 创建路径
 */
function mk_dir($path)
{
    //第1种情况，该目录已经存在
    if (is_dir($path)) {
        return;
    }
    //第2种情况，父目录存在，本身不存在
    if (is_dir(dirname($path))) {
        mkdir($path);
    }
    //第3种情况，父目录不存在
    if (!is_dir(dirname($path))) {
        mk_dir(dirname($path));//创建父目录
        mkdir($path);
    }
}

if (!is_dir(dirname($saveFile))) {
    mk_dir(dirname($saveFile));
}

if (strpos($pdmDomain, 'https://') !== false) {
    $options = [
        'ssl' => [
            'allow_self_signed' => true,
            'verify_peer' => false,
        ]
    ];
    $source = file_get_contents($pdmDomain . $orgImg, false, stream_context_create($options));
} else {
    $source = file_get_contents($pdmDomain . $orgImg);
}

if ((bool) $source) {
     file_put_contents($saveFile, $source);
}
echo $saveFile;die;