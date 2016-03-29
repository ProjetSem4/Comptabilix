<html>
    <head>
        <title>{{%PAGE_TITLE%}} - Fortitudo</title>
        <!-- Compiled and minified bootstrap CSS -->
        <link rel="stylesheet" href="template/bootstrap/3.3.6/css/bootstrap.min.css" />

        <!-- Optional bootstrap theme -->
        <link rel="stylesheet" href="template/bootstrap/3.3.6/css/bootstrap-theme.min.css" />

        <!-- Additionnal changes to bootstrap theme -->
        <link rel="stylesheet" href="template/template.css">

        <!-- jQuery ( -->
        <script src="template/jquery/1.11.3/jquery.min.js"></script>

        <!-- Latest compiled and minified JavaScript -->
        <script src="template/bootstrap/3.3.6/js/bootstrap.min.js"></script>

        <meta charset="utf-8" />

        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <!-- Application header & navbar -->
        <nav class="navbar navbar-fixed-top navbar-custom">

            <!-- Container for centering content -->
            <div class="container">

                <!-- Application title -->
                <div class="navbar-header"><a class="navbar-brand logo" href="/">Fortitudo</a></div>

                <!-- Association name -->
                <div class="navbar-middle navbar-brand miseenpage2">{{%ASSOCIATION_NAME%}}</div>

                <!-- Menu at the right of the header -->
                <ul class="nav navbar-nav navbar-right">{{%LOGGED_IN_MENU%}}</ul>
            </div>
        </nav>

        <!-- Application content -->
        <div class="container">
            <div class="row">
                <!-- Including the sidebar -->
                {{%PAGE_SIDEBAR%}}

                <!-- Content container-->
                <div class="col-lg-9">
                    {{%PAGE_CONTENT%}}