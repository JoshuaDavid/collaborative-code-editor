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
?>
<html>
    <head>
        <script src="jquery.js"></script>
        <script src="ace/src-noconflict/ace.js"></script>
        <script src="main.js"></script>
        <link rel="stylesheet" href="styles.css" type="text/css" />
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
                    <code id="html-editor">
                    </code>
                </div>
                <div id="js-wrap">
                    <h1>Write JavaScript Code Here (main.js)</h1>
                    <code id="js-editor">function helloWorld() {
    console.log("Hello world");
}</code>
                </div>
                <div id="css-wrap">
                    <h1>Write CSS Here (style.css)</h1>
                    <code id="css-editor">body {
    margin: 0px;
}</code>
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
                </div>
                <div id="result">
                    <iframe src="about:blank"></iframe>
                </div>
                <div id="console">
                </div>
            </div>
        </div>
        <footer>
        </footer>
    </body>
</html>
