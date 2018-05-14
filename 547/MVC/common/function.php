<?php
function view($url='',$var=array(),$boors=true){
    foreach($var as $k=>$v){
        $$k=$v;
    }
//$file=APP.$m.'/controller/'.$c.'.php'
if(empty($url)){
    $file=APP."{$GLOBALS['m']}/view/{$GLOBALS['c']}/{$GLOBALS['a']}.html";
    }else{
    $arr=explode('/',$url);
        if(count($arr)==1){
            $file=APP."{$GLOBALS['m']}/view/{$GLOBALS['c']}/{$arr['0']}.html";
            }elseif(count($arr)==2){
                $file=APP."{$GLOBALS['m']}/view/{$arr['0']}/{$arr['1']}.html";
            }else{
                $file=APP."{$arr['0']}/view/{$arr['1']}/{$arr['2']}.html";
            }
  
}
$header_file=RES.'header.html';
$footer_file=RES.'footer.html';
if(is_file($file)){
    if($boors){
    include $header_file;
    }
    include $file;
    if($boors){
    include $footer_file;
    }
}else{
    die("视图文件{$file}不存在");
}                    
}

function url($url=''){
    //print_r($_SERVER);
    if(empty($url)){
      return  "{$_SERVER['PHP_SELF']}?m={$GLOBALS['m']}&c={$GLOBALS['c']}&a={$GLOBALS['a']}";
    }else{
        $arr=explode('/',$url);
        if(count($arr)==1){
            return  "{$_SERVER['PHP_SELF']}?m={$GLOBALS['m']}&c={$GLOBALS['c']}&a={$arr['0']}";
        }elseif(count($arr)==2){
            return  "{$_SERVER['PHP_SELF']}?m={$GLOBALS['m']}&c={$arr['0']}&a={$arr['1']}";
        }else{
            return  "{$_SERVER['PHP_SELF']}?m={$arr['0']}&c={$arr['1']}&a={$arr['2']}";
        }
    
}
}

function upload($name='image',$maxSize=1048576,$dir="uploda/",$check="check"){
    $image=$_FILES['image'];
    $imageType=array('image/jpeg','image/png','image/jpg','image/gif');
    //$maxSize=1024*1024;
    $count=count($image['name']);
    $dir=$dir.'/'.$GLOBALS['c'].'/'.date("Y-m-d").'/'; //检查保存的目录
    if(!is_dir($dir)){   //判断目录是否存在
        mkdir($dir,777,true);  //创建目录
    }
    $f_arr=array();  //上传的文件保存的数组
    $erro='';  //保存错误信息
    for($i=0;$i<$count;$i++){
        if(in_array($i,$_POST[$check])){
            if(in_array($image['type'][$i],$imageType)){
                if($image['size'][$i]<$maxSize){
                    $exname=strrchr($image['name'][$i],'.'); //获取文件扩展名
                    $file=time().rand(1000,9999).$exname; //创建随机数以防多文件上传文件重名；
                    $file=$dir.$file;
                    move_uploaded_file($image['tmp_name'][$i],$file);
                    $f_arr[]=$file; //上传文件所保存的位置
                }else{
                    $erro="图片格式过大";
                }
            }else{
                $erro='文件格式错误';
            }
        }
    }
    return array(
        'uploadImages'=>$f_arr,
        'erro'=>$erro
    );
}

function getMenu(){
    $menu=$GLOBALS['m'];
    $level=findOne('user','level',"username='{$_SESSION['username']}'");
    //print_r($level);
    //die;
    $mainMenu=findALL('menu','*',"pid=0 and m='$menu'"); //获取当前主菜单
    
    $date=array();
    foreach($mainMenu as $v){
        
        $submenu=findALL('menu','*',"pid='{$v['id']}'");
        $date[$v['title']]=$v;
        
       foreach($submenu as $subv){
           $date[$v['title']]['submenu'][]=$subv;
           
       }
       
       
    }
    return $date;
    //print_r($date);   
}