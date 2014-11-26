<?php
$pdo = new PDO("sqlite:db/revisions.sqlite");

$project = $_GET["project"];

$stmt = $pdo->prepare("select project, html, css, javascript from revisions " .
    "where project=:project " . 
    "order by id desc limit 1;");

$stmt->bindParam(":project", $project);

$stmt->execute();

$row = $stmt->fetchAll();

if(!empty($_GET["json"])) {
    $row[0]["project"] = $project;
    echo json_encode($row[0]);
} else if(!empty($_GET["part"])) {
    if($_GET["part"] == "javascript") {
        header("Content-type: application/javascript");
        echo $row[0]["javascript"];
    } else if($_GET["part"] == "css") {
        header("Content-type: text/css");
        echo $row[0]["css"];
    } else if($_GET["part"] == "html") {
        echo $row[0]["html"];
    } else if($_GET["part"] == "zip") {
        $tmpdname = tempnam(sys_get_temp_dir(), "PAGE");
        mkdir($tmpdname);
        $htmlfilename = $tmpdname . DIRECTORY_SEPARATOR . 'index.html';
        file_put_contents($htmlfilename, $row[0]["html"]);
        $jsfilename = $tmpdname . DIRECTORY_SEPARATOR . 'main.js';
        file_put_contents($jsfilename, $row[0]["javascript"]);
        $cssfilename = $tmpdname . DIRECTORY_SEPARATOR . 'style.css';
        file_put_contents($cssfilename, $row[0]["css"]);
        $zip = new ZipArchive();
        $zipfilename = $tmpdname . DIRECTORY_SEPARATOR . 'project.zip';
        $zip->open($zipfilename, ZIPARCHIVE::OVERWRITE);
        echo $htmlfilename . '<br />';
        $zip->addFile($htmlfilename, $htmlfilename);
        $zip->addFile($cssfilename, $cssfilename);
        $zip->addFile($jsfilename, $jsfilename);
        $zip->close();
        var_dump($zip);
        die();
        // http headers for zip downloads
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"project.zip\"");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".filesize($zipfilename));
        @readfile($zipfilename);
    }
} else {
    $html = $row[0]["html"];
    // A slightly terrible way of doing this, but it should work fine.
    $html = preg_replace('/"main.js"/', "\"get_page.php?project={$project}&part=javascript\"", $html);
    $html = preg_replace('/"style.css"/', "\"get_page.php?project={$project}&part=css\"", $html);
    echo $html;
}
?>
