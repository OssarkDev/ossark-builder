<!--
Created by

 ██████╗ ███████╗███████╗ █████╗ ██████╗ ██╗  ██╗
██╔═══██╗██╔════╝██╔════╝██╔══██╗██╔══██╗██║ ██╔╝
██║   ██║███████╗███████╗███████║██████╔╝█████╔╝ 
██║   ██║╚════██║╚════██║██╔══██║██╔══██╗██╔═██╗ 
╚██████╔╝███████║███████║██║  ██║██║  ██║██║  ██╗

visits us at ossark.ie
 
-->

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php wp_head(); ?>
    <?php
        $header_scripts = get_field('header_scripts', 'option');
        if ($header_scripts) {
            echo $header_scripts;
        }
    ?>
</head>

<body>
<?php
    $body_scripts = get_field('body_scripts', 'option');
    if ($body_scripts) {
        echo $body_scripts;
    }
?>

<?php get_template_part('components/parts/header'); ?>

<main id="main">