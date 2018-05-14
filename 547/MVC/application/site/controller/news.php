<?php
function index(){
    $page=page('news','*','','time desc','7','4');
   
    foreach($page['res'] as &$v){
        //$v['time']=date("Y-m-d,H:i:s",$v['time']);
        $v['Y']=date("Y-m",$v['time']);
        $v['D']=date("d",$v['time']);
        $v['C']=mb_substr(strip_tags($v['content']),0,50,'utf8').'......';
    }
   
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
        echo json_encode($page);
        die;
    }
    
    view('',array('date'=>$page));
}