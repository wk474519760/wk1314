<?php
function index(){
    $res=findALL('category','*',"type='news'");
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
        echo json_encode($res);
        die;
    }
    view('',array('date'=>$res));
}

function addnews(){
    $erro='';
    if(!empty($_POST['title'])){     
        $_POST['time']=time(); 
        $_POST['type']='news';
        $res=dbAdd('category',$_POST);
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
        
function edintnews(){
        $erro="";
        if(!empty($_GET['id'])){
            $id=$_GET['id'];
            $category=findOne('category','*',"id='$id'");
            if($category){
                if(!empty($_POST['title'])){                
                    $i=dbUpdate('category', "id='$id'");
                    if($i){
                        $url=url('index');
                        header("Location:$url");
                    }else{
                        $erro='修改失败';
                    }                 
                }                
                view('',array('category'=>$category,'erro'=>$erro));
            }else{
                $erro='没有找到该条数据';
            }
        }else{
            $erro='此ID不存在';
        }
    }
    
    function delnews(){
        if(!empty($_POST['id'])){   //判断ID是否为空
            $id=$_POST['id'];        //ID不为空则定义ID
            //print_r($_POST['id']);
            //die;
            if(is_numeric($id)){    //判断获取的ID是否位数字（避免“‘’or1”删除数据库）
                $i=dbDelete('category',"id='$id'");    //调用函数删除该ID数据
                if($i){                              //判断函数是否执行成功
                    echo 'Y';
                    die;//跳转文件位置
                }
            }
        }
        echo '删除失败';
    }

    function copynews(){
        if(!empty($_POST['id'])){
            $id=$_POST['id'];
            $res=findOne('category','*',"`id`='$id'");
            if($res){
                unset($res['id']);  //要把id字段清除
                $res['time']=time();
                $result=dbAdd('category',$res);  //添加
                if($result){
                    echo 'Y';
                }else{
                    echo '复制失败';
                }
    
    
            }else{
                echo '数据不存在，可能已经删除';
            }
        }else{
            echo 'ID不合法';
        }
    }