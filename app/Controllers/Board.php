<?php

namespace App\Controllers;

// @RequestMapping
// @pesponseBody


use App\Models\DataModel;
use CodeIgniter\Controller;
use App\Controllers\Bd_controller;


class Board extends Controller{

    public function __construct() {

        helper( 'url' );
    }

    public function index()
    {
        // $page_set = 10; // 한 페이지 줄 수 
        // $block_set = 5; // 한 페이지 블럭 수 

        // $dataModel = new DataModel;
        
        // $size = sizeof($dataModel->showTable());
        // $total_size = ceil($size/$page_set); // 페이지 블럭 수 

        return redirect()->to( "/board/list" );
    }

    function pageLoadwrite(){ //글쓰기
        echo view('pages/write');
    }

    function pageLoadupdate($id){

        $dataModel = new DataModel;
        $params = array();
        $params['SNO'] = $id;
        $data = $dataModel->findData($params);
  
        $result = array();
        $result['data'] = $data;
 
        echo view( 'pages/update', $result );
    }

    public function list() {

        // $dataModel = new DataModel;
        // $data = $dataModel->showTable();
        
        // $result = array();
        // $result['data'] = $data;

        // echo view( 'pages/list', $result );
        $page_num=($_GET['page']) ? $_GET['page'] : 0; //페이지 번호
        $page_set=10; //한 페이지 글 수
        $block_set = 5; //한 페이지 블럭수
        $start_num= (int)($page_num / 5)*5 + 1 ;
        $end_num= $start_num + 4;

        $dataModel = new DataModel;

        $data = $dataModel->dividePage($page_num, $page_set);
        // $size = count($dataModel->showTable());
        $size = $dataModel->dividePageCount();

        $total_page = ceil($size/ $page_set);// 총 페이지수 (올림함수:ceil)
        // size = 52, page_set = 10 => ceil=>6 -> total_page
        // $total_block = ceil($total_page / $block_set); // 한 페이지당 보이는 블럭 수 (올림함수:ceil)

        
        $result['end_num']=$end_num;
        $result['start_num']=$start_num;    
        $result['page_num']=$page_num;
        $result['total_page']=$total_page;
        // $result['total_block'] = $total_block;
        $result['data'] = $data;
        $result['page_set'] = $page_set;
        $result['block_set'] = $block_set;

        echo view('pages/list',$result);

    }
    function content_view($id){
    //     $dataModel = new DataModel;
    //     $params = array();
    //     $params['SNO'] = $id;
    //     $data = $dataModel->findData( $params );

    //     $result = array();
    //     $result['data'] = $data;
 
    //     var_dump($result);

    //   echo view( 'pages/read', $result );

         $dataModel = new DataModel;
            
         $params = array();
         $params['SNO'] = $id;
        
         $data = $dataModel->findData( $params );

         $count = array(
             'SNO' => $id,
             'TITLE' => $data['TITLE'],
             'AUTHOR' => $data['AUTHOR'],
             'CONTENT' => $data['CONTENT'],
             'WRITE_DT' => $data['WRITE_DT'],
             'V_COUNT' => $data['V_COUNT']+1
         );

         $count_update = $dataModel->updateTable($count,2);

         $result = array();
         $result['data'] = $count_update;
        
       echo view( 'pages/read', $result );
    }

 
    function createAjax() {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $author = $_POST['author'];

        if( strlen( $title ) > 0 && strlen( $content ) > 0 && strlen( $author ) > 0 ) {
            $result = array();

            $params = array(
                'TITLE'=>$title,
                'CONTENT'=>$content,
                'AUTHOR'=>$author
            );

            $dataModel = new DataModel;
            $insert_result = $dataModel->insertTable( $params );

            if( $insert_result ) {
                $result['code'] = 200;
                $result['msg'] = "등록되었습니다.";
            } else {
                $result['code'] = 400;
                $result['msg'] = "실패하였습니다.";
            }
        } else {
            $result['code'] = 401;
            $result['msg'] = '입력값을 확인해주세요.';
        }

        // $this->response->setJSON( json_encode( $result ) );
        // 전달받은 값을 JSON 형식으로 문자열로 반환하여 반환
        echo( json_encode( $result ) );
    }

    function updateAjax(){

        // $sno = $_POST['SNO'];
        // $title = $_POST['TITLE'];
        // $author = $_POST['AUTHOR'];
        // $content = $_POST['CONTENT'];

        $update = array(
            'SNO' =>  $_POST['SNO'],
            'TITLE' => $_POST['TITLE'],
            'AUTHOR' => $_POST['AUTHOR'],
            'CONTENT' => $_POST['CONTENT'],
            'WRITE_DT' => date("Y-m-d H;i;s")            
        );

        $result= array();

        $dataModel = new DataModel;
        $update_result = $dataModel->updateTable($update,1);

        if($update_result){
            $result['code'] = 200;
            $result['msg'] = "수정되었습니다.";
        }else{
            $result['code'] = 400;
            $result['msg'] = "실패하였습니다.";
        }

        echo( json_encode($result) );
    }

    function deleteAjax(){
        
        $data = array(
            'SNO' => $_POST['SNO']
        );

        $dataModel = new DataModel;
        $delete_result = $dataModel->deleteTable($data);

        if($delete_result){
            $result['code'] = 200;
            $result['msg'] = "삭제되었습니다.";
        }else{
            $result['code'] = 400;
            $result['msg'] = "실패하였습니다.";
        }

        echo( json_encode($result) );
        
    }

}

// ajax 없는 버전 
// function create() {

//     $title = $_POST['title'];
//     $author = $_POST['author'];
//     $content = $_POST['content'];

//     $data = array(
//         'TITLE' => $title,
//         'CONTENT' => $content,
//         'AUTHOR'=> $author,
//         'WRITE_DT'=> date("Y-m-d H;i;s")
//     );
// //       print_r($data);

//     $dataModel = new DataModel;
//     $insert_result = $dataModel->insertTable($data);
    
//     if( $insert_result ) {
//         return redirect()->to( "/board/list" );
//     } else {
//         echo( "<script>alert('실패하였습니다.');</script>" );
//     }

// }

// function updateData($id=NULL)
// {
    
//     $update = array(
//         'SNO' => $id,
//         'TITLE' => $_POST['TITLE'],
//         'AUTHOR' => $_POST['AUTHOR'],
//         'CONTENT' => $_POST['CONTENT'],
//         'WRITE_DT' => date("Y-m-d H;i;s")
//     );

//     $dataModel = new DataModel;
//     $update_result = $dataModel->updateTable($update,1);
    
//     if($update_result){
//         return redirect()->to( "/board/list" );
//     }else{
//         echo( "<script>alert('실패하였습니다.');</script>" );
//     }

// }

// function deleteData($id=NULL){

//     $data = array(
//         'SNO'=> $id
//     );

//     $dataModel = new DataModel;
//     $delete_result = $dataModel->deleteTable($data);

//     if($delete_result){
//         return redirect()->to( "/board/list" );
//     }else{
//         echo( "<script>alert('실패하였습니다.');</script>" );
//     }
    
// }

