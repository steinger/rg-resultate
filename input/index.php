<?php
/**
 * PHP Update Page
 *
 * @author  Marcel Steinger <github@steinger.ch>
 */
require('htmlheader.html');
include('../rg.class.php');
$rg=new RgResultate("../config/config.php");
$noten = $rg->getNoten();
if (isset($_REQUEST['submit'])) 
{
    $headertype = 1;
    $headerinfo = "Fehler ".$_REQUEST['startnr'];
    $theme = "f";
    if (isset($_SERVER['REMOTE_USER']))
    {
        $user = $_SERVER['REMOTE_USER'];
    }
    else
    {
         $user = $_SERVER['REMOTE_ADDR'];
    }
    if ($_REQUEST['wert'] > 0)
    {
        $rg->SqlUpdateData($_REQUEST['startnr'],$_REQUEST['element'],$_REQUEST['wert'],$user);
        $headertype = 2;
        $headerinfo = $_REQUEST['startnr']." saved";
        $theme = "g";
    }
    if ($_REQUEST['note_d'] > 0) 
    {
        $rg->SqlUpdateDataNote($_REQUEST['startnr'],$_REQUEST['element'],$_REQUEST['note_d'],$_REQUEST['note_e'],$user);
        $headertype = 2;
        $headerinfo = $_REQUEST['startnr']." saved";
        $theme = "g";
    }
}
else
{
    $headertype = 0;
}
if (isset($_REQUEST['startnr'])) 
{
    if ($headertype > 1 ) $startnummer = $_REQUEST['startnr']+1;
    else $startnummer = $_REQUEST['startnr'];
}
else 
{
    $startnummer = 1;
}
if (isset($_REQUEST['element']))  $element = $_REQUEST['element'];
else $element = "ohg";

?>
<body>
    <div data-role="page">

        <?php $rg->MobileHeader(0,"RG Live Input"); ?>
        
        <?php if ($headertype >= 1 ) {
        echo '<div data-role="header" data-position="fixed" data-theme="'.$theme.'" >
             <h1>'.$headerinfo.'</h1>
             </div>';
        } ?>
        
        
        <div data-role="content" role="main" data-theme="e">
            
            <form action=" <?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div data-role="fieldcontain">
                    <label for="select-choice-startnr" class="select">Startnummer:</label>
                    <?php  $rg->Startliste($startnummer); ?>
                </div>
                
                <div data-role="fieldcontain">
                    <label for="select-choice-element" class="select">Element:</label>
                    <?php  $rg->SelectElements($element); ?>
                </div>
                
                <div data-role="fieldcontain">
                    <label for="number-1">Punkte:</label>
                    <input data-clear-btn="true" name="wert" id="number-1" step="any" value="" type="number">
                </div>
                <?php if ($noten == 1) { ?>

                <div data-role="fieldcontain">
                    <label for="number-2">Note D (optional):</label>
                    <input data-clear-btn="true" name="note_d" id="number-2" step="any" value="" type="number">
                </div>
                
                <div data-role="fieldcontain">
                    <label for="number-3">Note E (optional):</label>
                    <input data-clear-btn="true" name="note_e" id="number-3" step="any" value="" type="number">
                </div>
                <?php  } ?>
                <button value="submit" name="submit" type="submit" aria-disabled="false">Senden</button>
            </form>
        </div>
        <?php $rg->MobileFooter(); ?>
    </div>
</body></html>