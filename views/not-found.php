<?php $this->header('HTTP/1.1 404 Not Found'); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Not Found</title>
</head>
<body>
    <h1>Not Found</h1>
    <p><?= $this->esc($url_path); ?></p>
</body>
</html>