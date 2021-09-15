
<html>
    <head>
        <title>수정</title>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    </head>
    <body>
        <!-- <form method="POST" action="/Board/updateData/<?=$data['SNO']?>"> -->
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
                    <td ><input type="hidden" id="SNO" value="<?=$data['SNO']?>"><?=$data['SNO']?></td>
                    <td><input type="text" id="TITLE" name="TITLE" value="<?=$data['TITLE']?>"></td>
                    <td><input type="text" id="AUTHOR" name="AUTHOR" value="<?=$data['AUTHOR']?>"></td>
                    <td><input type="text" id="CONTENT" name="CONTENT" value="<?=$data['CONTENT']?>"></td>
                    <td><?=$data['V_COUNT'];?></td>
                    <td><?=$data['WRITE_DT']?></td>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align: right;">
                            <input type="button" id="update_btn" value="수정" />
                        </td>
                    </tr>
                </tfoot>
            </table>
        <!-- </form> -->

        <script type="text/javascript">
                $(function(){
                    $("#update_btn").on( 'click' , update );
                });

                function update(){

                    if(confirm("수정하시겠습니까?")){

                        var parameter = {
                            'SNO' : $("#SNO").val(),
                            'TITLE' : $("#TITLE").val(),
                            'AUTHOR' : $("#AUTHOR").val(),
                            'CONTENT' : $("#CONTENT").val()
                        }

                        $.ajax({
                            method : 'POST',
                            url : '/board/updateAjax',
                            data : parameter,
                            success : function(result){

                                var r = JSON.parse(result);

                                if(r.code == '200'){
                                    location.href="/board/list?page=0";
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