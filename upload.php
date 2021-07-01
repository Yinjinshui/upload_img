<?php
//上传图片

header("Content-type:text/html;charset=utf-8");
/*
*@param $file_post上传的图片(数组)
*
*/

function reArrayFiles($file_post)
{
    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i = 0; $i < $file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    //只获取上传图片的
    $file_ary_list = array();
    foreach ($file_ary as $key => $item) {
        if (!empty($item['name'])) {
            $file_ary_list[$key] = $key;
        }
    }

    $new_ary = array();
    foreach ($file_ary_list as $key => $item) {
        $new_ary[$key] = $file_ary[$key];
    }
    //print_r($new_ary);
    //exit();
    return $new_ary;
}


/*
*@param $img 上传的图片(数组)
*@param $paths  上传图片的路径
*
*
*/

function more_upload($img, $paths)
{
    //1.获取上传的图片
    if ($img) {
        //获取图片
        //$img=$_FILES['img'];
        $file_ary = reArrayFiles($img);
    }

    //2.图片上传类型(判断图片的上传类型)
    $upload_arr = array("image/jpg", "image/jpeg", "image/png", "image/gif");


    foreach ($file_ary as $key => $item) {
        if (!in_array($item['type'], $upload_arr)) {
            $type_arr[] = $key + 1;
        }
    }

    if (!empty($type_arr)) {
        $str = '';
        foreach ($type_arr as $key => $item) {
            $str .= "图片" . $item . ',';
        }

        write_json("格式不正确",1);
    }


    //3.图片上传大小控制
    $max_size = 190000;
    $size_arr = array();
    foreach ($file_ary as $key => $item) {
        if ($item['size'] > $max_size) {
            $size_arr[] = $key + 1;
        }
    }

    if (!empty($size_arr)) {
        $str = '';
        foreach ($size_arr as $key => $item) {
            $str .= "图片" . $item . ',';
        }

        write_json($str . "小大超出最大限制" . $max_size,1);
    }


    //4.上传错误代码
    $error_arr = array();
    foreach ($file_ary as $key => $item) {
        if ($item['error'] > 0) {
            //判断错误了代码类型
            switch ($item['error']) {
                case 1:
                    $error_arr[$key] = "上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值";
                    break;

                case 2:
                    $error_arr[$key] = "其值为 2，上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值";
                    break;

                case 3:
                    $error_arr[$key] = "其值为 3，文件只有部分被上传。";
                    break;

                case 4:
                    $error_arr[$key] = "没有文件被上传";
                    break;

                case 6:
                    $error_arr[$key] = "找不到临时文件夹。PHP 4.3.10 和 PHP 5.0.3 引进";
                    break;

                case 7:
                    $error_arr[$key] = "文件写入失败。PHP 5.1.0 引进。";
                    break;
            }
        }
    }

    if (!empty($error_arr)) {
        $str = '';
        foreach ($error_arr as $key => $item) {
            $str .= "图片" . $key . $item . ',';
        }
        write_json($str,1);
    }


    //5.上传后的文件名定义(随机获取一个文件名(保持后缀名不变))
    $path = $paths; //图片上传路径
    foreach ($file_ary as $key => $item) {
        $picimg[$key] = pathinfo($item["name"]);//解析上传文件名字

        do {
            $newimg[$key] = date("YmdHis") . rand(1000, 9999) . "." . $picimg[$key]["extension"];
        } while (file_exists($path . $newimg[$key]));

    }

    //6. 执行文件上传
    $success = array();
    foreach ($file_ary as $key => $item) {

        //判断文件是否是通过 HTTP POST 上传的
        if (is_uploaded_file($item['tmp_name'])) {

            //将上传的文件移动到新位置
            if (move_uploaded_file($item['tmp_name'], $path . $newimg[$key])) {
                $success[$key] = $path . $newimg[$key]; //上传成功的图片
                //print_r($success);
            } else {
                write_json("文件上传失败！",1);
            }
        } else {
            write_json("上传的不是文件！",1);
        }
    }


    //上传成功的图片路径
    //print_r($success);
    return $success;
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

/**
 * 返回数据函数
 */
function write_json($data=[],$code=0,$message='success')
{
    header('Content-Type:application/json; charset=utf-8');
    $data = ['code' => $code, 'message' => $message, 'data' =>$data];
    echo json_encode($data);
    die;
}

//===========================================================================
#判断是否有图片资源
if(empty($_FILES['img'])){
    write_json("",1,"图片资源不能为空");
}

//获取上传站点
//图片地址参考：图片路径：soa/gb/pdm-product-pic/Electronic/2020/07/24/source-img/20200724150457_44347.jpg
$website = isset($_POST['website']) ? $_POST['website'] : '';
$paths = "./images"; //默认
if (!is_dir($paths)) {
    mk_dir($paths);
}

if (!empty($website)) {
    $paths = $website . '/pdm-product-pic/Electronic/' . date('Y/m/d') . '/source-img/';
    //判断路径是否存在
    if (!is_dir($paths)) {
        mk_dir($paths);
    }
}

//上传图片
$img = more_upload($_FILES['img'], $paths);
write_json($img);


?>