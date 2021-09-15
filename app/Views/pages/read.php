<html>
    <head>
        <title>읽기</title>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    </head>
    <body>
        <table border="1">
            <thead>
                <tr>
                    <th>번호</th>
                    <th>제목</th>
                    <th>작성자</th>
                    <th width="200">내용</th>
                    <th>조회수</th>
                    <th>작성일</th>
                </tr>
            </thead>
            <tbody>
                <td><input type="hidden" id="SNO" value="<?=$data['SNO']?>"><?=$data['SNO']?></td>
                <td><?=$data['TITLE']?></td>
                <td><?=$data['AUTHOR']?></td>
                <td><?=$data['CONTENT']?></td>
                <td><?=$data['V_COUNT'];?></td>
                <td><?=$data['WRITE_DT']?></td>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: right;">
                        <a href="/board/pageLoadupdate/<?=$data['SNO']?>" class="enrol_btn">수정</a>
                        <!-- <a href="/Board/deleteData/<?=$data['SNO']?>" class="enrol_btn">삭제</a> -->
                        <!-- <a href="javascript:deleteCheck()" class="enrol_btn">삭제</a> -->
                        <input type="button" id="delete_btn" value="삭제"/>
                        <a href="/Board/index" class="enrol_btn">뒤로가기</a>
                    </td>
                </tr>
            </tfoot>
        </table>

        <script type="text/javascript">
            $(function(){
                $("#delete_btn").on( 'click' , deleteCheck );
            });

            function deleteCheck(){

              if(confirm("삭제하시겠습니까?")){
                // alert("삭제되었습니다");
                // location.href = "/Board/deleteData/<?=$data['SNO']?>";

                var parameter = {
                    'SNO' : $("#SNO").val()
                }

                $.ajax({
                    method : 'POST',
                    url : '/board/deleteAjax',
                    data : parameter,
                    success : function(result){

                        var r = JSON.parse(result);

                        if(r.code =='200'){
                            location.href= "/board/list?page=0";
                        }
                        alert(r.msg);
                    },
                    error :function(){
                        alret("에러가 발생했습니다.")
                    }
                });
              }
            }
        </script> 
    </body>
</html> 
