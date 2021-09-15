<html>
    <head>
        <title>글 생성</title>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <style>
            * {
                color: #aaa;
            }

            /*
                * : 전체 엘리먼트
                #아이디 : id (유일)
                .클래스 : class (복수 허용)

                자식 노드 ">"
                모든 하위 자식 노드 " "
            */

            .first { border: 1px solid red; }
            .second { border: 1px solid blue; }
            .third { border: 1px solid green;}

            .first, .second, .third { width: 30%; padding: 50px; }

            .first .second { border: 1px solid orange; }
        </style>
    </head>
    <body>
         <!-- <form method="POST" action="/Board/create"> -->
         <form id="frm">
            <table id="firstTable" border="1" style="width: 25%;">
                <thead>
                    <tr>
                        <th class="firstTh" >제목</th>
                        <th class="firstTh">작성자</th>
                        <th class="firstTh">작성내용</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" id="title" name="title"/></td>
                        <td><input type="text" id="author" name="author"/></td>
                        <td>
                            <textarea id="content" name="content"></textarea>
                            <input type="checkbox" name="checkbox" value="1" />
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align: right;">
                            <input id="enrol_btn" type="button" value="등록"/>
                            <!-- <input type="submit" value="뒤로가기" formaction="/Board/index"/> -->
                            <!-- <a href="#" onclick="history.back()" id="back_btn" class="enrol_btn">뒤로가기</a> -->
                            <a href="#" id="back_btn" class="back_btn">뒤로가기</a>
                        </td>  
                    </tr>
                </tfoot>
            </table>
        </form>

        <script type="text/javascript">
            $(function() {
                // var back_btn = document.getElementById( 'back_btn' );

                // console.log( back_btn );

                // back_btn.onclick = function() {
                //     alert( 'back' );
                //     history.back();
                // } 

                //$("#id값").on('click',실행할 함수);
                $("#back_btn").on( 'click', historyBack );
                $("#enrol_btn").on( 'click' , create);
            });

            function historyBack() {
                alert( 'history back' );
                history.back();
            }

            /*
                EventListener : 이벤트 청취자
                Event Binding : 이벤트 부여 (묶다)

                이벤트의 종류 : 클릭, 입력(키보드), 재생, 마우스가 올라왔을때, Submit 했을 때
                => ~했을 때
                => on + 이벤트
                    ex1) 클릭 : onclick
                    ex2) 입력 : onkeyup, onkeypress, onkeydown ..
                
                Event Binding 방법
                    1) onXXXXXX attribute 추가 (뒤로가기버튼)
                    2) javascript에서 바인딩
                    3) jQuery에서 바인딩
            */


            /*
                Ajax

                // 기본 문법
                $.ajax({
                    type: 'post', // 'get' or 'post' or etc.
                    url: '/board/create',
                    data: {

                    },
                    success: function( callback ) {

                    },
                    error: function() {

                    }
                })
            */

            function create() {

                /*
                    Validation check : 유효성 검사

                    let) 제목이 없을 경우
                    let) 작성자가 없을 경우
                    let) 작성내용이 없을 경우

                    오류(Bug) 방지 목적
                     - ex) DB Column -> Not Null => 값이 없으면, DB Error
                    
                    필수값일 경우 체크
                */

                /*
                
                if( $("#title").val().trim() == "" ) {
                    alert( "제목을 입력해주세요." );
                } else if( $("#author").val().trim() == "" ) {
                    alert( "작성자를 입력해주세요." );
                } else if( $("#content").val().trim() == "" ) {
                    alert( "내용을 입력해주세요." );
                } else {
                    if( confirm( "등록하시겠습니까?" ) ) {
                        // var parameter = {
                        //     'title': document.getElementById( 'title' ).value,
                        //     'content': document.getElementById( 'content' ).value,
                        //     'author': document.getElementById( 'author' ).value
                        // };

                        // jQuery.val()
                        //  1) $("#선택자").val() => #선택자 의 값을 return
                        //  2) $("#선택자").val(값) => #선택자의 값을 '값' 으로 Set              
                                                    
                        var parameter = {
                            'title': $("#title").val(),
                            'content': $("#content").val(),
                            'author': $("#author").val()
                        }
                

                        $.ajax({
                            method: 'POST',
                            url: '/board/createAjax',
                            data: parameter,
                            success: function( result ) {

                                var r = JSON.parse( result );

                                if( r.code == 200 ) {
                                    location.href="/board/list?page=0";
                                } 

                                alert( r.msg );
                            },
                            error: function() {
                                alert( "에러가 발생하였습니다." );
                            }
                        });
                    }
                }

                */

                var serialized = $("#frm").serializeArray();

                var data_names = {
                    'title': '제목',
                    'author': '작성자',
                    'content': '내용',
                    'checkbox': '체크박스'
                };

                console.log( serialized );

                var isRight = true;

                //each() 매개변수로 받은 것을 사용해 반복문과 같이 배열이나 객체의 요소 검사
                //each(배열(or 유사배열 객체),콜백함수(인덱스,값))
                $.each( serialized, function( index, item ) {
                    if( item.value == "" && isRight ) {
                        alert( data_names[item.name] + " 을/를 입력해주세요." );
                        isRight = false;
                    }
                });

                if( isRight ) {
                    if( confirm( "등록하시겠습니까?" ) ) {          
                        var parameter = {
                            'title': $("#title").val(),
                            'content': $("#content").val(),
                            'author': $("#author").val()
                        }
                
                        $.ajax({
                            method: 'POST',
                            url: '/board/createAjax',
                            data: parameter,
                            success: function( result ) {

                                var r = JSON.parse( result );

                                if( r.code == 200 ) {
                                    location.href="/board/list?page=0";
                                } 

                                alert( r.msg );
                            },
                            error: function() {
                                alert( "에러가 발생하였습니다." );
                            }
                        });
                    }
                }
                

                // var isRight = true;
                /*
                    each(function(index,item))

                    1)function : 반복문 마다 실행될 익명함수
                    2)index : 반복문이 몇번 째 인지 알 수 있는 인덱스
                    3)item : 반복문에서 셀렉트되는 엘리먼트
                             익명함수 안에서 $(item)로 셀렉트 가능
                */
                
                // $("#frm").find("input[type='text']").each(function(index, item) {

                    // (*) 
                    // $("input[type=text]")[index] => item

                    // 아무값없이 띄어쓰기만 있을 때도 빈 값으로 체크되도록 trim() 함수 호출
                    // trim() : 공백제거 함수 , 공백 제거한 문자열 추출


                    /*
                    break & continue 
                        Use )
                         - Switch
                         - While, do while, for, foreach

                        1. break : 반복문 종료
                        2. continue : 다음 반복문 실행
                    */

                //     if ($(this).val().trim() == '') {
                //         isRight = false;
                //         break;
                //     } 
                // });

                    /*
                if (isRight == true) {

                    if( confirm( "등록하시겠습니까?" ) ) {
                        // var parameter = {
                        //     'title': document.getElementById( 'title' ).value,
                        //     'content': document.getElementById( 'content' ).value,
                        //     'author': document.getElementById( 'author' ).value
                        // };

                        // jQuery.val()
                        //  1) $("#선택자").val() => #선택자 의 값을 return
                        //  2) $("#선택자").val(값) => #선택자의 값을 '값' 으로 Set              
                                                    
                        var parameter = {
                            'title': $("#title").val(),
                            'content': $("#content").val(),
                            'author': $("#author").val()
                        }
                

                        $.ajax({
                            method: 'POST',
                            url: '/board/createAjax',
                            data: parameter,
                            success: function( result ) {

                                var r = JSON.parse( result );

                                if( r.code == 200 ) {
                                    location.href="/board/list?page=0";
                                } 

                                alert( r.msg );
                            },
                            error: function() {
                                alert( "에러가 발생하였습니다." );
                            }
                        });
                    }
                } 
                */
            }
            
           </script>
    </body>
</html>