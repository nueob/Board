<html>
    <head>
        <title>글 생성</title>
    </head>
    <body>
        <table border="1">
            <thead>
                <tr>
                    <th>번호</th>
                    <th>제목</th>
                    <th>작성자</th>
                    <th>조회수</th>
                </tr>
            </thead>
            <tbody>
                <!-- <tr>
                    <td>1</td>
                    <td>제목1</td>
                    <td>작성자1</td>
                    <td>11</td>
                </tr> -->
                <?php
                    // var_dump($data);
                    $article_idx = $page_num*$page_set; 
                    foreach( $data as $d ) {
                        $article_idx++;
                ?>
                    <tr onclick="location.href='/board/content_view/<?=$d['SNO']?>'">
                        <td><?=$article_idx?></td>
                        <td><?=$d['TITLE']?></td>
                        <td><?=$d['AUTHOR']?></td>
                        <td><?=$d['V_COUNT']?></td>
                    </tr>
                <?php
                     $d['SNO'] = $article_idx;
                     }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: right;">
                        <a href="/Board/pageLoadwrite" class="enrol_btn">글쓰기</a>
                    </td>    
                </tr>
                <tr>
                    <td colspan="4" style="text-align: center;">
                        <?php
                            if( $page_num > 0 ) {
                        ?>
                            <a href="javascript:prevPage()">&lt;</a>
                        <?php
                            }
                        ?>
                        <?php   
                            // $start_num = 11
                            // $end_num = 15
                            // $page_num = 11-1
                            // $total_page = 11
                          for($i=$start_num; $i<=$end_num; $i++){ 
                              
                              if( $i <=  $total_page  ){ ?>
                                <a href="/board/list?page=<?=$i-1?>"><?=$i?></a>
                        <?php } 
                            }
                        ?>


                        <?php
                            if( $page_num < ( $total_page - 1 ) ) {
                        ?>
                            <a href="javascript:nextPage()">&gt;</a>
                        <?php
                            }
                        ?>
                        <!--
                            페이지 링크
                            이전페이지
                            다음페이지
                        -->
                    </td>
                </tr>
            </tfoot>
        </table>

        <input type="hidden" id="page_num" value="<?=$page_num?>">
        <input type="hidden" id="total_page" value="<?=$total_page?>">
        
        <script type="text/javascript">
            function nextPage(){
                // $page_num = $page_num + 1;
                var page_num = document.getElementById( 'page_num' ).value;

                if( page_num > document.getElementById( 'total_page' ) ) {
                    page_num = document.getElementById( 'total_page' ) - 1;
                }
                location.href='/board/list?page=' + ( Number( page_num ) + 1 );
            }
            function prevPage(){
                var page_num = document.getElementById( 'page_num' ).value;
                
                if( page_num > document.getElementById( 'total_page' ) ) {
                    page_num = document.getElementById( 'total_page' ) + 1;
                }
                location.href='/board/list?page=' + ( Number( page_num ) - 1 );
            }
        </script>
    </body>
</html>
