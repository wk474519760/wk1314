<?php
function index(){
     /* if(!empty($_SESSION['username'])){
        $url=url('news/index');
        header("location:$url");
    }                              */                       //如果SESSION内存在username，则跳转新闻页
    $user='';
    $erro='';
    if(!empty($_POST["user"])){
        if(strtoupper($_SESSION["VerifyCode"])==strtoupper($_POST['code'])){
        $user=$_POST['user'];        
        $pwd=md5($_POST['pwd']);      
        $res = findOne('user','*',"`username`='$user' and `pwd`='$pwd'");
        //var_dump ($res);
        if($res){
            $_SESSION['username']=$user;
            if($_POST['remeber']==1){
                setcookie('username',$user,time()+8*60*60); //当勾选时 给cookie添加账号密码；
            }
            $url=url('news/index');
            header("location:$url");
        }else{
            $erro='账号或密码错误，请重新输入';
        }
        }else{
            $erro='验证码错误';
        }
        
    }
    view('',array('username'=>$user,'erro'=>$erro),false);
}


function getCode(){
    vCode();
}
    

function logout(){
    unset($_SESSION['username']);
    setcookie('username','',3600); //获取过去时间戳删除cookie
    $url=url('index');
    header("location:$url");
}