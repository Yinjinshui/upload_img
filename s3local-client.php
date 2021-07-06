<?php
#将当前服务器的图片上传到s3[客户端]

/**
 * 模拟图片上传s3[模拟服务]
 * @param $orgImg  图片再当前服务的路径（绝对路径）
 * @param $saveFile 上传到s3的图片路径以及新名词
 * 参数：demo
 * orgImg:/data/www/image-service/htdocs/cache/imagecache/soa/vevor/pdm-product-pic/Electronic/2020/07/24/goods_thumb-v1/20200724150457_44347.jpg
 * saveFile:soa/vevor/pdm-product-pic/Electronic/2020/07/24/goods_thumb-v1/20200724150457_44347.jpg
 */
function fileSimulationServiceS3($localDesImgFilePath,$saveFile)
{
    $saveFile=ltrim($saveFile,DIRECTORY_SEPARATOR);
    $post_data = [
        'saveFile'=>$saveFile,
        'orgImg'=>'@'.$localDesImgFilePath
    ];
    $s3Url="http://10.12.0.2:8082/s3local.php";
    $ch = curl_init();
    if (class_exists('\CURLFile')) {// 这里用特性检测判断php版本

        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
        //>=5.5
        $post_data = [
            'saveFile'=>$saveFile,
            'orgImg'=> new \CURLFile(realpath($localDesImgFilePath))
        ];

    } else {

        if (defined('CURLOPT_SAFE_UPLOAD')) {
            curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
        }
        //<=5.5
        $post_data = [
            'saveFile'=>$saveFile,
            'orgImg'=> '@' . realpath($localDesImgFilePath)
        ];
    }
    curl_setopt($ch, CURLOPT_URL, $s3Url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

$localDesImgFilePath="/data/www/image-service/htdocs/cache/imagecache/soa/vevor/pdm-product-pic/Electronic/2020/07/24/goods_thumb-v1/20200724150457_44347.jpg";
$s3FilePath="soa/vevor/pdm-product-pic/Electronic/2020/07/24/goods_thumb-v1/20200724150457_44347.jpg";
fileSimulationServiceS3($localDesImgFilePath,$s3FilePath);