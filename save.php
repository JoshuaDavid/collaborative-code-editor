<?php
$pdo = new PDO("sqlite:db/revisions.sqlite");

$project = $_POST["project"];
$javascript = $_POST['javascript'];
$css = $_POST['css'];
$html = $_POST['html'];

$stmt = $pdo->prepare("insert into revisions (project, javascript, css, html) " . 
    " values  (:project, :javascript, :css, :html);");
$stmt->bindParam(':project', $project);
$stmt->bindParam(':javascript', $javascript);
$stmt->bindParam(':css', $css);
$stmt->bindParam(':html', $html);
$stmt->execute();
echo $pdo->lastInsertId();
?>
