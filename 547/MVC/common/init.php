<?php
header('content-type:text/html;charset=utf-8');
date_default_timezone_set("PRC");
include COMMON.'config.php';
include COMMON.'db.fun.php';
include COMMON.'function.php';
include COMMON.'img.fun.php';

$m=empty($_GET['m'])?'site':$_GET['m'];
$c=empty($_GET['c'])?'index':$_GET['c'];
$a=empty($_GET['a'])?'index':$_GET['a'];

//获取当前项目的目录
$self=$_SERVER['PHP_SELF']; //通过$_SERVER获取绝对路径地址
$i=strpos($self,'index.php');//通过一个字符串寻找另一个字符串的位置
$root=substr($self,0,$i);//  截取需要的路径
define('ADDRESS',$root);
//登录验证
if($m=='admin'){
    if(!empty($_SESSION['username'])){
        if($c=='login' &&  $a=='index'){
            $url=url('news/index');
            header("location:$url");
        }
    }else{
        if(isset($_COOKIE['username'])){
            $_SESSION['username']=$_COOKIE['username'];
            if($c=='login' && $a=='index'){
                $url=url('news/index');
                header("location:$url");
            }
        }else{ //没有登录的情况
            
            if($c!='login'){
            $url=url('login/index');
            header("location:$url");
            }
        }
    }
}




$res=APP.$m.'/view/public/';
define('RES',$res);
//echo $res;
$file=APP.$m.'/controller/'.$c.'.php';
//echo $file;
if(is_file($file)){
    include $file;
    $a();
}else{
    die("$c 控制器不存在");
}