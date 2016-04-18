<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style>
        th{padding:5px 5px}
        td{padding:2px 5px}
        .head{text-align:left;background-color:lightgrey;color:black;font-family:微软雅黑;font-size:14px}
        .result{text-align:right;font-family:arial;font-size:13px}
        .server{text-align:left;background-color:lightblue}
        .warn{background-color:yellow}

        .error{background-color:orange}
        .fatal{background-color:red}
        .fail{background-color:lightgrey}
        .security{background-color:gray}
        .increase{color:green}
        .reduce {color:red}
        .line {color:gold}
        caption{font:bold 16px; text-align:left}
        td span{color:slateblue;width:60px;margin-left:2px}
    </style>
    <title>数据日报</title>
</head>
<body>
<div class="index_content clearfix">

    <div class="row">
        <div>
            <h2><?php echo $app_info["name"];?>  数据日报 (<?php echo $t;?>)</h2>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>1 app排名变化</h3>
                </div>
                <div class="panel-body">
                    <table class="result" >
                        <thead>
                        <tr class="head">
                            <th>类别</th>
                            <th>今日</th>
                            <th>与昨日比</th>
                            <th>与上周同日比</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ( $rank_result as $line ) { ?>
                        <tr>
                            <td><?php echo $line[0]?></td> <!-- 词 -->
                            <td><?php echo $line[1]?></td> <!--今天的数据-->

                            <td>
                            <!--昨天天的数据-->
                            <?php
                                if ( ($line[1]-$line[2])>0 )
                                {
                                    echo "<span class='reduce'>▼".(string)($line[1]-$line[2]) ."</span> (". (string)$line[2] .")";
                                }
                                elseif( ($line[1]-$line[2])<0 )
                                {
                                    echo "<span class='increase'>▲".(string)($line[2]-$line[1]) ."</span> (". (string)$line[2] .")";
                                }
                                else // == 0
                                {
                                    echo "<span class='line'>-".(string)($line[2]-$line[1]) ."</span> (". (string)$line[2] .")";
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                    if ( ($line[1]-$line[3])>0 )
                                    {
                                    echo "<span class='reduce'>▼".(string)($line[1]-$line[3]) ."</span> (". (string)$line[3] .")";
                                    }
                                    elseif( ($line[1]-$line[3])<0 )
                                    {
                                    echo "<span class='increase'>▲".(string)($line[3]-$line[1]) ."</span> (". (string)$line[3] .")";
                                    }
                                    else // == 0
                                    {
                                    echo "<span class='line'>-".(string)($line[3]-$line[1]) ."</span> (". (string)$line[3] .")";
                                    }
                                ?>
                            </td> <!--一周期的数据-->
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>2 关键词搜索排名变化</h3>
                </div>
                <div class="panel-body">
                    <table class="result" >
                        <thead>
                        <tr class="head">
                            <th>关键词</th>
                            <th>今日</th>
                            <th>与昨日比</th>
                            <th>与上周同日比</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ( $pos_result as $line ) { ?>
                            <tr>
                                <td><?php echo $line[0]?></td> <!-- 词 -->
                                <td><?php echo $line[1]?></td> <!--今天的数据-->

                                <td>
                                    <!--昨天天的数据-->
                                    <?php
                                    if ( ($line[1]-$line[2])>0 )
                                    {
                                        echo "<span class='reduce'>▼".(string)($line[1]-$line[2]) ."</span> (". (string)$line[2] .")";
                                    }
                                    elseif( ($line[1]-$line[2])<0 )
                                    {
                                        echo "<span class='increase'>▲".(string)($line[2]-$line[1]) ."</span> (". (string)$line[2] .")";
                                    }
                                    else // == 0
                                    {
                                        echo "<span class='line'>-".(string)($line[2]-$line[1]) ."</span> (". (string)$line[2] .")";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if ( ($line[1]-$line[3])>0 )
                                    {
                                        echo "<span class='reduce'>▼".(string)($line[1]-$line[3]) ."</span> (". (string)$line[3] .")";
                                    }
                                    elseif( ($line[1]-$line[3])<0 )
                                    {
                                        echo "<span class='increase'>▲".(string)($line[3]-$line[1]) ."</span> (". (string)$line[3] .")";
                                    }
                                    else // == 0
                                    {
                                        echo "<span class='line'>-".(string)($line[3]-$line[1]) ."</span> (". (string)$line[3] .")";
                                    }
                                    ?>
                                </td> <!--一周期的数据-->
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

 </div>

</body>
</html>

