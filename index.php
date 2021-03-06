<?
$id = NULL;
$project = NULL;
$parent = NULL;
$url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

if(!empty($_GET["project"])) {
    $project = $_GET["project"];
} else {
    $project = "";
    for($i = 0; $i < 16; $i++) {
        $b52_chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $project .= $b52_chars[mt_rand(0, 51)];
    }
}
$html = <<<HTML
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Web Page</title>
        <link rel="stylesheet" href="style.css" />
        <script src="main.js" defer></script>
    </head>
    <body>
        <div class="wrapper">
            <header class="header">Header</header>
            <article class="main">
                <p>
                    Pellentesque habitant morbi tristique senectus et netus et 
                    malesuada fames ac turpis egestas. Vestibulum tortor quam, 
                    feugiat vitae, ultricies eget, tempor sit amet, ante. Donec
                    eu libero sit amet quam egestas semper. Aenean ultricies mi 
                    vitae est. Mauris placerat eleifend leo.
                </p>  
            </article>
            <aside class="aside aside-1">Aside 1</aside>
            <aside class="aside aside-2">Aside 2</aside>
            <footer class="footer">Footer</footer>
        </div>
    </body>
</html>
HTML;
$js = <<<javascript
function helloWorld() {
    console.log("Hello world");
}
helloWorld();
javascript;
$css = <<<css
.wrapper {
  display: -webkit-box;
  display: -moz-box;
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;  
  
  -webkit-flex-flow: row wrap;
  flex-flow: row wrap;
  
  font-weight: bold;
  text-align: center;
}

.wrapper > * {
  padding: 10px;
  flex: 1 100%;
}

.header {
  background: tomato;
}
css;

?>
<html>
    <head>
        <script src="jquery.js"></script>
        <script src="ace/src-noconflict/ace.js"></script>
        <script src="main.js"></script>
        <link rel="stylesheet" href="styles.css" type="text/css" />
        <link rel="stylesheet" type="text/css" href="firebug-lite/skin/light/firebug-lite.css">
        <title>Collaborative Webpage Editor</title>
    </head>
    <body>
        <header>
        </header>
        <input type="hidden" id="project" value="<?php echo $project;?>" />
        <input type="hidden" id="parent" value="<?php echo $parent;?>" />
        <div id="main">
            <div id="left">
                <div id="html-wrap">
                    <h1>Write HTML Here (index.html)</h1>
                    <code id="html-editor"><?php 
                        echo htmlentities($html); 
                  ?></code>
                </div>
                <div id="js-wrap">
                    <h1>Write JavaScript Code Here (main.js)</h1>
                    <code id="js-editor"><?php
                        echo htmlentities($js); 
                    ?></code>
                </div>
                <div id="css-wrap">
                    <h1>Write CSS Here (style.css)</h1>
                    <code id="css-editor"><?php
                        echo htmlentities($css);
                    ?></code>
                </div>
            </div>
            <div id="right">
                <div id="share">
                    <label>Share this project:</label>
                    <input disabled value="<?php echo "{$url}?project={$project}"; ?>" />
                </div>
                <div id="actions">
                    <button id="save">Save (push)</button>
                    <button id="sync">Sync (pull)</button>
                    <div id="height-control">
                        <div id="height-control-left">
                            <div>
                                <label for="width">Width:</label>
                                <input type="text" id="width">
                            </div>
                            <div>
                                <label for="height">Height:</label>
                                <input type="text" id="height">
                            </div>
                        </div>
                        <button id="resize">Resize</button>
                    </div>
                </div>
                <div id="result">
                    <div id="mock-browser">
                        <div id="titlebar"></div>
                        <div id="iframe-container">
                            <iframe src="about:blank"></iframe>
                        </div>
                    </div>
                </div>
                <div id="console">
                    <div id="console-inner">
                    </div>
                </div>
            </div>
        </div>
        <footer>
        </footer>
    </body>
</html>
