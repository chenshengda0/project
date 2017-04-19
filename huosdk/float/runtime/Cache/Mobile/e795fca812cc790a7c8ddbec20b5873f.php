<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head lang="en">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <meta charset="UTF-8">
    <title><?php echo ($postdata["post_title"]); ?></title>
    <link rel="stylesheet" href="/public/mobile/css/css_reset.css"/>
    <link rel="stylesheet" href="/public/mobile/css/info_detail.css"/>
</head>
<body>
<header class="header">
    <h3 class="news_title"><?php echo ($postdata["post_title"]); ?></h3>
    <p><span><?php echo (date('Y-m-d  H:i:s',$postdata["post_modified"])); ?></span><a href="#"><?php echo ($gamename); ?></a></p>
</header>
<div class="text_intro">
<?php echo ($postdata["post_content"]); ?>
</div>

</body>
</html>