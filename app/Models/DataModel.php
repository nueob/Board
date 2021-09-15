<?php
namespace App\Models;

use CodeIgniter\Model ;

class DataModel extends Model{

    protected $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    function insertTable($data){

        $builder = $this->db->table('post');
        return $builder->insert($data);
    }

    function showTable(){
        $query = $this->db->query('SELECT * FROM post');
        $results = $query->getResultArray();
        
        return $results;
    }

    function updateTable($data,$code){

        $builder = $this->db->table('post');
        $where = array(
            'SNO'=>$data['SNO']
        );
        //return $builder->replace($data);
        if($code == 1){

            return $builder->update($data,$where);

        } else if($code == 2){

            $builder->update($data,$where);

            $q = "SELECT * FROM post WHERE SNO = '" .$data['SNO']. "' ";
            $query = $this->db->query( $q );
    
            $result = $query->getRowArray();
    
            return $result;

        }

    }

    function deleteTable($data){
        $builder = $this->db->table('post');
        return $builder->delete($data);
    }

    function findData($params) {
        $q = "SELECT * FROM post WHERE SNO = '" .$params['SNO']. "' ";
        // $q = "SELECT * FROM post WHERE SNO = $params['SNO'] ";
        $query = $this->db->query( $q );

        $result = $query->getRowArray();

        return $result;
    }
    
    function dividePage($pages, $size){
        
        $count = $pages*$size;
        
        $sql = "SELECT * FROM post ORDER BY WRITE_DT desc LIMIT $count, $size";
        $query = $this->db->query($sql);
               
        $results = $query->getResultArray();

        return $results;
    }

    // 전체 행 갯수 가져오기
    //SELECT COUNT(*) FROM 테이블;
    //  컬럼 데이터 갯수 가져오기
    // SELECT COUNT(컬럼) FROM 테이블;
    function dividePageCount() {
        $sql = "SELECT COUNT(*) as CNT FROM post ";
        $query = $this->db->query( $sql );

        return $query->getRowArray()['CNT'];
    }


    // function paging(){

    //     $builder = $this->db->table('post');

    //     $page_set = 10; //한 페이지 줄 수
    //     $block_set = 5; // 한 페이지 블럭수


    //     $query = "SELECT count(no) as total FROM post";
    //     $result = mysqli_query($builder,$query);
    //     $row = mysqli_fetch_array($result);

    //     $total = sizeof($result); // 전체 글 수
        
    //     $total_page = ceil($total/ $page_set);// 홈페이지수(올림함수:ceil)
    //     $total_block = ceil($total_page / $block_set); //총 블럭수 (올림함수:ceil)

    //     if(!$page) $page = 1; //현재페이지(넘어온 값)
    //     $block = ceil($page / $block_set); //현재블럭(올림함수)


    // }

}