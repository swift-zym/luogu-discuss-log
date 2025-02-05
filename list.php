<?php

$pgsiz = 20;

require_once('config.php');
session_start();

$cnt = 0;
$pg = intval($_GET['page']);
if ($pg == 0) $pg = 1;

$jmp = ($pg - 1) * $pgsiz;

$q = $link->query("select * from discuss_count where click > 0 and title != 'None' and title != '' order by thread desc limit $jmp,$pgsiz");

$arr = [];
while ($assoc = $q->fetch_assoc()) array_push($arr, $assoc);

?>

<!DOCTYPE html>

<head>
    <title>讨论列表</title>
    <link rel="shortcut icon" type="image/x-icon" href="//www.luogu.com.cn/favicon.ico" media="screen" />
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/css/bootstrap.min.css" />
    <script src="https://cdn.staticfile.org/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.staticfile.org/popper.js/1.15.0/umd/popper.min.js"></script>
    <script src="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="/dist/main.css" />
</head>

<body>
    <nav class="navbar navbar-expand-sm bg-light navbar-light fixed-top" style="box-shadow: 0px 1px 3px 0px black;">
        <a class="navbar-brand" href="/">洛谷帖子</a>
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link active" href="/list.php">列表</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/rank.php">排行</a>
            </li>
        </ul>
    </nav>
    <div class="container" style="margin-top:80px">
        <div class="row">
            <div class="col-sm-12">
                <div class="lg-content-table-left">
                    <?php foreach ($arr as $va) { ?>
                        <div class="am-g lg-table-bg0 lg-table-row">
                            <div class="am-u-md-6">
                                <?php echo $va['thread'];
                                ++$cnt; ?>
                                <a href="/show.php?url=https://www.luogu.com.cn/discuss/show/<?php echo $va['thread']; ?>"><?php echo $va['title']; ?></a>
                                <br />
                                <span class="lg-small">访问: <?php echo $va['click']; ?> </span>
                                <?php if (isset($_SESSION['admin'])) { ?>
                                    <a class="lg-small" href="javascript: del(<?php echo $va['thread'] ?>)">删除</a>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="pagination-centered">
                        <ul class="am-pagination am-pagination-centered">
                            <?php if ($pg > 1) { ?>
                                <li><a href="/list.php?page=<?php echo $pg - 1; ?>">&lt;</a>
                                </li>
                            <?php }
                            if ($cnt == $pgsiz) { ?>
                                <li><a href="/list.php?page=<?php echo $pg + 1; ?>">&gt;</a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if (isset($_SESSION['admin'])) { ?>
        <script>
            function del(thread) {
                $.get(`/delete.php?thread=${thread}`).then((u) => swal(u))
            }
        </script>
    <?php } ?>
    <?php require_once('footer.php'); ?>
</body>