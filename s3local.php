<?php
#将当前服务器的图片上传到s3【服务端】
$orgImg=$_FILES['orgImg'] ??""; #图片路径
$saveFile=$_POST['saveFile'] ??""; #图片要保存的扇区

if( empty($orgImg) || empty($saveFile)){
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

file_get_contents($orgImg['tmp_name']);
COPY($orgImg['tmp_name'],$saveFile);
echo $saveFile;die;