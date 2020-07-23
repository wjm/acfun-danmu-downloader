<!DOCTYPE html> 
<html> 
<head><meta name="google-site-verification" content="Ko62Rg1pXmAaOfL04rINnmsw97vN-lqJQmZGIloO_fM" />
</head>
<title>ACFUN 弹幕下载</title>
<body> 

<form method="post" action="acfun.php">
    <input type="text" name="acid" id="acid" placeholder="AC号或者网址...">
    <input type="submit" value="click" name="submit"> <!-- assign a name for the button -->
</form>
<form method="post" action="acfun.php">
    <input type="text" name="vidid" id="vidid" placeholder="视频ID">
    <input type="submit" value="click" name="submit"> <!-- assign a name for the button -->
</form>
<button type="button" onclick="getdanmu()">Submit</button>
<p id="download"></p>
<?php 
//echo "Hello World!<br>";
function getdanmu($id){
    //echo $id;
    $url = 'https://www.acfun.cn/rest/pc-direct/new-danmaku/poll';
    $data = array('videoId' => $id);
    
    // use key 'http' even if you send the request to https://...
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\ncookie: _did=3\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) { /* Handle error */ }
    $json = json_decode($result);
    if(isset($json->{'error_msg'})) {
        echo $json->{'error_msg'};
    }
    else{
        echo "弹幕数量： ", $json->{'addCount'}."<br>";
        echo "弹幕获取成功，点击按钮下载<br>";
        echo "<a id='down' target='_blank' href='#' download=$id.json>下载</a>";
        echo "<script>var json = JSON.stringify($result);document.getElementById('down').onclick = function(event){
            blob = new Blob([json], {type: 'octet/stream'}),
            url = window.URL.createObjectURL(blob); 
            this.href = url;
        }</script>";
    }
    
    //echo $result;
}
function getid($id){
    $url = 'https://www.acfun.cn/v/'.$id;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_FAILONERROR,true);
    $data = curl_exec($ch);

    if($data!==false){ 
        preg_match('/"currentVideoId":([0-9]{3,}),"isLike"/', $data, $matches, PREG_OFFSET_CAPTURE);
        echo $matches[1][0];
        getdanmu($matches[1][0]);
    }else{
       echo "出了点问题，可能是视频没了";
    }
    curl_close($ch);
}
if(isset($_POST['acid'])){
    if($_POST['acid'] != NULL){
        getid($_POST['acid']);
    }
}
if(isset($_POST['vidid'])){
    if($_POST['vidid'] != NULL){
        getdanmu($_POST['vidid']);
    }
    
}

?> 

</body> 
</html>