<?php
$content = file_get_contents('index.php');
$content = str_replace('href="/"', 'href="index.php"', $content);
$content = str_replace('href="/', 'href="', $content);
$content = str_replace('src="/', 'src="', $content);
file_put_contents('index.php', $content);
echo "Fixed index.php paths\n";
