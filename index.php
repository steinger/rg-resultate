<?php
/**
 * Index Page
 *
 * @author  Marcel Steinger <github@steinger.ch>
 */
require('htmlheader.html');
include('rg.class.php');
$rg=new RgResultate("config/config.php");
$anzahlCat = count($rg->GetCategories());
$location = file_get_contents ('config/rg_location.txt');
$gf = $rg->getGf();
?>
<body>
    <div data-role="page">
        <?php $rg->MobileHeader(0); ?>
        <div data-role="content" role="main">
            <p>Live Resultate der Rhythmische Gymnastik des Wettkampfs, <b> <?php echo $location; ?> </b>
            <ul data-role="listview" data-inset="true">
            <li><a href="#start">Startliste</a></li>
            <li><a href="#rang">Ranglisten <?php if ($anzahlCat > 1) { ?> <span class="ui-li-count"> <?php echo $anzahlCat; ?> </span><?php } ?> </a></li>
            <li data-role="list-divider" role="heading">Rangliste nach &Uuml;bungen</li>
            <?php $rg->GuiListElements(); ?>
            <?php if ($gf == 1) { ?> 
            <li data-role="list-divider" role="heading">Handger&auml;te Final</li> 
            <?php $rg->GuiListElements(1); } ?>
            </ul>
        </div>
        <?php $rg->MobileFooter(); ?>
    </div>
    <div data-role="page" id="start">
        <?php include('panel.php'); $rg->GuiListElements(); ?>
        </div>
            <?php $rg->MobileHeader(1); ?>
            <div data-role="content" role="main">
                <h1>Startliste</h1>
                <?php  $rg->Startliste(); ?>
            </div>
            <?php $rg->MobileFooter(); ?>
    </div>
    <div data-role="page" id="rang">
        <?php include('panel.php'); $rg->GuiListElements(); ?>
        </div>
        <?php $rg->MobileHeader(1,"rang"); ?>
        <div data-role="content" role="main">
            <h1>Ranglisten</h1>
            <?php $rg->GuiRanglisten(); ?>
        </div>
        <?php $rg->MobileFooter(); ?>
    </div>
    <?php $rg->GuiKatRanglistenPages(); ?>
    <?php $rg->GuiElementRanglistenPages(); ?>
    <?php $rg->GuiElementRanglistenPages(2); ?>
    <?php $rg->GuiRanglisteKategorieElementPages(); ?>
    <?php $rg->GuiRanglisteKategorieElementPages(2); ?>
</body>
</html>