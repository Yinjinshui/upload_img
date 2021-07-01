<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js">
    </script>

    <link rel="shortcut icon" type="favicon.ico" href="favicon.ico">
<title>多图上传</title>
</head>

<body>
<div class="container">
    <h2>
        上传源图片资源
    </h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="exampleInputEmail1">站点：</label>
            <select class="form-control" name="website">
                <option value="soa/vevor">soa_vevor</option>
                <option value="vevor">vevor</option>
                <option value="soa/gb">soa_gb</option>
            </select>
        </div>

        <div class="imagepic">
            <div class="form-groups">
                <label for="exampleInputEmail1">图片1：</label>
                <input type="file" class="form-control" name="img[]" value=""/>
            </div>
        </div>

        <br/>
        <button type="button" class="btn btn-default" onclick="addimages()">添加上传图片</button>
        <input type="submit" class="btn btn-success" value="提交"/>
    </form>
    <div id="content">

    </div>
</div>
</body>
</html>
<script>
    function addimages() {
        var nums=$(".imagepic").children(".form-groups").size()+1;
        if(nums>10){
            alert("最大仅支持10张图片上传");
        }
        $(".imagepic").append('<div class="form-groups delimage'+nums+'" >' +
            '<label for="exampleInputEmail1">图片'+nums+'：</label>' +
            '<input type="file" class="form-control" name="img[]" value=""/>' +
            '<span onclick="delimage('+nums+')">[-]</span>' +
            ' </div>');
    }

    function delimage(nums) {
        $(".delimage"+nums).remove();
    }
</script>

