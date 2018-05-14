<?php
function index(){
  
    
    
    $page=page('news','*','','time desc');
   /*  print_r ($page);
    die;  */
    foreach($page['res'] as &$v){
        $v['time']=date("Y-m-d,H:i:s",$v['time']);
        $images=explode('|', $v['images']);
        $v['images']='';
        if($images){
            $v['images'].="<img src='".ADDRESS."{$images[0]}' height='60px'/>";
        }else{
            $v['images']='';
            
        } //当仅需要展示一张图片时
        //$v['images']='';
       /*  foreach($images as $value){
            $v['images'].="<img src='".ADDRESS."$value' height='60px' style='margin-top:3px;'  />";                        
        }  */ //当需要展示多张图片时
        /* print_r ($v);
        die; */
    }
     if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
        echo json_encode($page);
        die;
    } 
    view('',array('date'=>$page));
}

function addnews(){
   $erro='';
    if(!empty($_POST['title'])){
        
        //print_r($_FILES);
        //die;
        //1.判断类型  IMG/JPEG IMG/PNG IMG/JPG IMG/GIF
        //2.上传 文件大小；3.确定文件的名字（获取文件的扩展名）4.给上传文件随机取名（以防别人利用图片原名）；
        //5.确定保存位置，将临时文件移动到指定位置，并重新命名为新的名字
        //6.文件名保存到数据库
        /* $image=$_FILES['image'];
        $imageType=array('image/jpeg','image/png','image/jpg','image/gif');
        $imageSize=1024*1024;
        $count=count($image['name']);
        $dir='uplodas'.'/'.$GLOBALS['c'].'/'.date("Y-m-d").'/';
        if(!is_dir($dir)){
            mkdir($dir,777,true);
        }
        for($i=0;$i<$count;$i++){
            if(in_array($i,$_POST['check'])){             
                if(in_array($image['type'][$i],$imageType)){
                    if($image['size'][$i]<$imageSize){
                        $exname=strrchr($image['name'][$i],'.'); //获取文件扩展名
                        $file=time().rand(1000,9999).$exname; //创建随机数以防多文件上传文件重名；
                        $file=$dir.$file;
                        move_uploaded_file($image['tmp_name'][$i],$file);
                        $f_arr[]=$file; //上传文件所保存的位置
                    }else{
                    $err="图片格式过大";
                }
                }else{
                $err='文件格式错误';                   
                }
            }
        } */
        $uploadData=upload('image',1048576,'uplodas/','check');//调用函数
        $_POST['time']=time();
        $_POST['images']=implode('|',$uploadData['uploadImages']);
        $res=dbAdd('news',$_POST);
        if($res){
            $url=url('index');
            header("location:$url");
            die;
        }else{
            $erro="添加失败";
        }
            
        }
       /*  if(in_array($image['type'],$imageType)){
            if($image['size']<$imageSize){
               $exname=strrchr($image['name'],'.');
               $dir='uplodas'.'/'.$GLOBALS['c'].'/'.date("Y-m-d").'/';
                if(!is_dir($dir)){
                    mkdir($dir,777,true);
                }
                $file=time().rand(1000,9999).$exname; //创建随机数以防多文件上传文件重名；
                $file=$dir.$file;
                move_uploaded_file($image['tmp_name'],$file); //将文件从临时文件夹移动到新的指定文件夹
                $_POST['time']=time();
                $_POST['images']=$file;
                $res=dbAdd('news',$_POST);
                if($res){
                    $url=url('index');
                    header("location:$url");
                    die;
                }else{
                    $err="添加失败";
                }
            }else{
                $err="图片格式过大";
            }
                     
        }else{
            $err='文件格式错误';
        }
        
        
        
        
        
        
        
      
    } */
    $category=findALL('category','*',"type='news'");
    view('',array('erro'=>$erro,'category'=>$category));
}
//上传图片



function edintnews(){
    $erro="";
   if(!empty($_GET['id'])){
   $id=$_GET['id']; 
   $res=findOne('news','*',"id='$id'");
       if($res){
           if(!empty($_POST['title'])){
               //print_r ($_POST);
               //die;
               //当有用户删除了全部图片，$_POST['checked']为一个空数组
               $_POST['checked']=empty($_POST['checked'])?array():$_POST['checked'];
               //开始检测已上传图片是否要删除
               $imgs=explode('|',$res['images']); //获取当前操作的已上传的图片地址，并转化为数组
               if($imgs){    //如果之前有上传图片
                   foreach($imgs as $k=>$v){      //遍历数组
                       //print_r ($k);
                     // die;
                       //$_POST ['checked']保存的是已上传的图片，用户没有删除的图片序号
                       if(!in_array($k,$_POST['checked'])){ //如果图片的序号不存在$_POST['checked']
                           //则删除图片
                           unlink($v); 
                           //print_r($k);
                          // die;
                           //删除数组$imgs的$k
                           unset($imgs[$k]);
                           
                       }
                   }
               }
               //开始上传新图片
               $uploadData=upload();
               //print_r($uploadData);die;
               $imgs=array_merge($imgs,$uploadData['uploadImages']);  //合并数组，合并关联数组时会替换原数组的序号关联数 合并索引数组时会将其合并到数组后面
              //print_r ($imgs);
              //die;
               if($imgs){
                   $_POST['images']=implode('|',$imgs);
               }else{
                   $_POST['images']='';
               }
               $_POST['utime']=time();
               $i=dbUpdate('news', "id='$id'");
               if($i){
                   $url=url('index');
                   header("Location:$url");
               }else{
                   $erro='修改失败';
               }
               
           } 
        $category=findALL('category','*',"type='news'");
      
       view('',array('res'=>$res,'erro'=>$erro,'category'=>$category));
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
           if(is_numeric($id)){    //判断获取的ID是否位数字（避免“‘’or1”删除数据库）
               $i=dbDelete('news',"id='$id'");    //调用函数删除该ID数据
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
		$res=findOne('news','*',"`id`='$id'");
		if($res){
			unset($res['id']);  //要把id字段清除
			$res['time']=time();
			$result=dbAdd('news',$res);  //添加
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
        