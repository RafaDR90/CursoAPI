<?php
use utils\Utils,
    lib\Pages;
if (!Utils::isLogued() or !isset($token)){
    $pages=new Pages();
    $pages->render('landingPage/LandingPageView');
    exit();
}
?>
<div>
    <p>Usa este token desde tu proyecto para poder hacer peticiones a la API:</p>
    <p><b><?=$token?></b></p>
</div>