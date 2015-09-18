<html ng-app="app">
<head>
    <base href="<?php echo env('SUBFOLDER_INSTALLATION', '/') ?>">
    <?php
        echo Assets::style('style');
    ?>

    <meta name="access_token" content="<?php echo Session::get('oauth.access_token')?>">
    <meta name="refresh_token" content="<?php echo Session::get('oauth.refresh_token')?>">
    <meta name="token_type" content="<?php echo Session::get('oauth.token_type')?>">
    <config name="username" content="<?php echo Auth::user()->username; ?>"></config>
</head>

<body>
    <div ui-view></div>

    <?php
        echo Assets::javascript('javascript-core');
        echo Assets::javascript('javascript-app');
    ?>
</body>
</html>