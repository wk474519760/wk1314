<?php
function index(){
    
      $page=page('user','*');
      //$page=findALL('user','*');
       //print_r($page);
      // die;
  
     if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
        echo json_encode($page);
        die;
    } 
    view('',array('date'=>$page));
}

function add(){  
    $menu=getMenu();
   // print_r($menu);
   // die;
    $erro='';
    if(isset($_POST['username'])){
        $_POST['level']=implode('|',$_POST['level']);
        $_POST['pwd']=md5($_POST['password']);
        $res=dbAdd('user');
        if($res){
            $url=url('index');
            header("location:$url");
            die;
        }else{
            $erro="添加失败";
        }   
}
view('',array('erro'=>$erro));
}

function edint(){
    
    
    
}