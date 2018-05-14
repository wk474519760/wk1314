<?php
function index(){
    if(!empty($_GET['id'])){
        $id=$_GET['id'];   
        $content=findOne('news','*',"id='$id'");
        $content['time']=date("Y-m-d",$content['time']);
        view('news_detalited',array('date'=>$content));
    }else{       
        //跳转404页面
        echo '未找到指定页面';               
    }
    //print_r ($content);            
        //print_r ($v['time']);            
}