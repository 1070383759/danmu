<?php  
  
//创建websocket服务器对象，监听0.0.0.0:9502端口  
$ws = new swoole_websocket_server("0.0.0.0",9507);  
  
//监听WebSocket连接打开事件  
$ws->on('open', function ($ws, $request) {  
    var_dump($request->fd."号用户已连接", $request->get, $request->server);  
    $ws->push($request->fd, '{"data":"欢迎'.$request->fd.'号用户"}');  
});  
  
//监听WebSocket消息事件  
$ws->on('message', function ($ws, $frame) {  
    //echo "Message: {$frame->data}\n";  
    echo "<pre>";  
    print_r($frame);
    $u_id = "用户id:".$frame->fd.",";
    $data = "评论内容：".$frame->data;
    file_put_contents("./log.txt",$u_id.$data.PHP_EOL,FILE_APPEND);

    //遍历所有连接,将接到的消息广播出去  
    foreach($ws->connections as $fd){  
        $ws->push($fd, "{$frame->data}");  
    }  
    //$ws->push($frame->fd, "{$frame->data}");  
});  
  
//监听WebSocket连接关闭事件  
$ws->on('close', function ($ws, $fd) {  
    echo "{$fd}号用户已退出 \n";  
});  
  
$ws->start();  
  
?>  
