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
    echo json_encode($row[0]);
} else {
    $js = '<script type="text/js">' . $row[0]["javascript"] . '</script>';
    $css = '<style>' . $row[0]["css"] . '</style>';
    $html = $row[0]["html"];
    // A slightly terrible way of doing this, but it should work fine.
    //$html = preg_replace('/' . preg_quote('</head>') . '/', preg_quote($css) . preg_quote('</head>'), $html);
    $html = preg_replace('/\<\/head\>/', "\n{$css}\n</head>", $html);
    $html = preg_replace('/\<\/body\>/', "\n{$js} \n</body>", $html);
    echo $html;
}
?>
