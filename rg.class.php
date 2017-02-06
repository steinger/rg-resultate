<?php
/**
 * PHP Class for Rhythmische Gymnastik Resultate
 *
 * @author  Marcel Steinger <github@steinger.ch>
 */

class RgResultate
{
    public  $db;
    private $table;
    private $tableNote;
    private $dez;
    private $gf;
    private $noten;

    public function __construct($configPath) 
    {
    /**
     * Class Konstrukter, vordefinieren der Class variablen
     *
     * @param   string  $configPath     Pfad zur Configurationsfile  
     */
        include($configPath);
        $this->db = $this->DBconnection($sql_username,$sql_passwrd,$sql_database);
        $this->table = $table;
        $this->tableNote = $tableNote;
        $this->dez = $dez;
        $this->noten = $noten;
        $this->gf = $gf;
    }
    
    function getGf()
    {
    /**
     * Holt aus der Config die Gerätefinal ID ja oder Nein
     * 
     * @return  int  $gf 
     */
        return $this->gf;
    }
    
    function getNoten()
    {
    /**
     * Holt die Config die Noten Anzeige ja oder nein.
     * 
     * @return  int  $gf 
     */
        return $this->noten;
    }

    function getUpdate ()
    {
    /**
     * Anzeige bei Rangliste wann zuletzt aktualisiert.
     * 
     * @return  string  printout jQuery Mobile in HTML5 
     */
        
        echo '<p>Letzte Aktualisierung : '.$this->LastEnties().'<br>';
        echo '(Angaben ohne Gewähr) </p>';  
    }    
    
    function MobileHeader($home, $pageid = "",  $titel = "RG Resultate")
    {
    /**
     * Mobile Header
     *
     * @param   string   $home      Auf Hauptseite
     * @param   string   $pageid    SeitenID
     * @param   string   $titel     Seiten Titel
     * @return  string   printout jQuery Mobile in HTML5      
     */

        echo '<div data-role="header" role="banner" data-theme="b"><h1>'.$titel.'</h1>';  
        if ($home == 1)
        {
            echo '<a href="#mypanel" data-role="button" data-icon="bars" data-iconpos="notext" data-theme="b" data-iconshadow="false" data-inline="true"></a>';
            if ($pageid != "")
            {
                echo '<a href="'.$_SERVER['PHP_SELF'].'" data-role="button" data-icon="refresh" data-iconpos="notext" data-theme="b" data-inline="true" ></a>';
            }
        }
        if ($home == 2)
        {
            echo '<a href="#start" data-icon="home" data-role="button" data-direction="reverse" data-iconpos="notext" data-corners="true" data-shadow="true" data-iconshadow="true" data-inline="false" data-wrapperels="span" title="Home" id="#index"></a>';
        }
        echo '</div>';

    }

    function MobileFooter()
    {
    /**
     * Mobile Footer
     *
     * @return string  printout jQuery Mobile in HTML5          
     */
        echo '<div data-role="footer" data-theme="c">';
        echo '<h3>&copy; 2013 steinger.ch</h3></div> ';
    }

    function Startliste ($select = 0)
    {
    /**
     * Startliste
     *
     * @param   string  $select     Wenn 1 wird die Startliste aus Drop-Down dargestellt, ist für die Input Seite vorgesehen.
     * @return printout jQuery Mobile in HTML5              
     */
        $sql = "SELECT * FROM $this->table ORDER BY startnr";
        if ($select) echo '<select name="startnr" id="select-choice-startnr">';
        else echo '<ul data-role="listview" data-inset="true" data-filter="true">';
        foreach ($this->db->query($sql) as $row)
        {
            if ($select) echo '<option value="'.$row['startnr'].'" '.$this->GetFormSelected ($row['startnr'],$select).'>'.$row['startnr'].' - '.$row['name'].'</option>';
            else echo '<li data-icon="false><a href="#">'.$row['startnr'].' '.$row['name'].'</a> <span class="ui-li-aside">'.$row['kat'].'<span></li>';
        }
        if ($select) echo '</select>';
        else echo "</ul>";
    }

    function Rangliste ($kat = "", $elemente = 0)
    {
    /**
     * Ranglist
     *
     * @param   string  $kat        Kategorie
     * @param   int     $element   
     * @return printout jQuery Mobile in HTML5        
     */
        if ($kat == "") 
        {   
            $sqlWhere  = "";
        }
        else
        {
            $sqlWhere = "WHERE kat = '".$kat."' ";
            $kat = substr($kat,0,1);
        }
        
        if ($elemente != 0)
        {
            $sql = implode(",", $elemente);
            $sqlcount = implode("+", $elemente);
        }
        else
        {
            if ($kat == 'G')
            {
                $sql = 'uebung1,uebung2';
                $sqlcount = 'uebung1+uebung2';
            }
            else
            {
                $sql =  'ohg,seil,ball,reif,keulen,band';
                $sqlcount = 'ohg+seil+ball+reif+keulen+band';
            }
        }
        $stmt = $this->db->prepare("SELECT startnr,name,$sql,($sqlcount) AS total FROM $this->table $sqlWhere ORDER BY total DESC");
        $stmt->execute();
        $dez = $this->dez;
        $i = 1;
        echo '<div data-role="collapsible-set" data-theme="c" data-content-theme="d">';
        while ($row = $stmt->fetch())
        {
            if ($row['total'] > 0)
            {
                echo '<div data-role="collapsible">';
                echo '<h3>'.$i.'. '.$row['name'].'<span class="ui-li-aside">'.number_format($row['total'], $dez).'</span></h3>';
                echo "<table data-role=\"table\" id=\"table-column-toggle\" class=\"ui-responsive\">";
                echo "<tr><tbody><th data-priority=\"1\">Startnr.</th><td data-priority=\"2\">".$row['startnr']."</td></tr></tbody>";
                if ( isset($row['ohg']) && $row['ohg'] > 0)  echo "<tr><tbody><th>o.Hg.</th><td>".number_format($row['ohg'],$dez)."</td><td>".$this->SqlGetNote($row['startnr'],"ohg",$dez)."</td></tr></tbody>";
                if ( isset($row['seil']) && $row['seil'] > 0)  echo "<tr><tbody><th>Seil</th><td>".number_format($row['seil'],$dez)."</td><td>".$this->SqlGetNote($row['startnr'],"seil",$dez)."</td></tr></tbody>";
                if ( isset($row['ball']) && $row['ball'] > 0)  echo "<tr><tbody><th>Ball</th><td>".number_format($row['ball'],$dez)."</td><td>".$this->SqlGetNote($row['startnr'],"ball",$dez)."</td></tr></tbody>";
                if ( isset($row['reif']) && $row['reif'] > 0)  echo "<tr><tbody><th>Reif</th><td>".number_format($row['reif'],$dez)."</td><td>".$this->SqlGetNote($row['startnr'],"reif",$dez)."</td></tr></tbody>";
                if ( isset($row['band'])  && $row['band'] > 0)  echo "<tr><tbody><th>Band</th><td>".number_format($row['band'],$dez)."</td></tr><td>".$this->SqlGetNote($row['startnr'],"band",$dez)."</td></tbody>";
                if ( isset($row['keulen']) && $row['keulen'] > 0) echo "<tr><tbody><th>Keulen</th><td>".number_format($row['keulen'],$dez)."</td><td>".$this->SqlGetNote($row['startnr'],"keulen",$dez)."</td></tr></tbody>";
                if ( isset($row['uebung1']) && $row['uebung1'] > 0)  echo "<tr><tbody><th>&Uuml;bung 1</th><td>".number_format($row['uebung1'],$dez)."</td></tr></tbody>";
                if ( isset($row['uebung2']) && $row['uebung2'] > 0)  echo "<tr><tbody><th>&Uuml;bung 2</th><td>".number_format($row['uebung2'],$dez)."</td></tr></tbody>";
                echo "<tr><tbody><th>Total</th><td>".number_format($row['total'],$dez)."</td></tr></tbody>";
                echo "</table>";
                echo '</div>';
                $i++;
            }
        }
        echo '</div>';
        if ($i>1)
        {
            $this->getUpdate();
        }
        else
        {
            echo "Noch keine Resultate vorhanden";
        }
    }

    function RanglisteElement($element, $kat = "")
    {
    /**
     * Ranglist Elemente/Geräte
     *
     * @param   string  $element    Element oder Gerät
     * @param   string  $kat        Kategorie  
     * @return printout jQuery Mobile in HTML5         
     */
        if ($kat == "") 
        {   
            $sqlWhere = "";
        }
        else
        {
            $sqlWhere = "WHERE kat = '".$kat."' ";
        }
        $stmt = $this->db->prepare("SELECT name,$element FROM $this->table $sqlWhere ORDER BY $element DESC");
        $stmt->execute();
        $i = 1;
        echo "<table data-role=\"table\" id=\"table-custom-2\" data-mode=\"columntoggle\" class=\"ui-body-d ui-shadow table-stripe ui-responsive\" data-column-btn-theme=\"a\" data-column-btn-text=\"Spalten Auswahl...\" data-column-popup-theme=\"a\">";
        echo "<thead><tr class=\"ui-bar-a\"><th data-priority=\"2\">Rang</th><th data-priority=\"1\">Name</th><th data-priority=\"3\">Punkte</th></tr></thead><tbody>";
        while ($row = $stmt->fetch())
        {
            
            if ($row[$element] > 0)
            {
                echo "<tr><td>".$i."</td><td>".$row['name']."</td><td>".number_format($row[$element],$this->dez)."</td></tr>";
                $i++;
            }
        }
        echo "</tbody>";
        echo "</table>";
        $this->getUpdate();
    }

    function GuiRanglisten()
    {
    /**
     * GUI Ranglisten, auflistung mehrern Ranglisten
     *
     * @return printout jQuery Mobile in HTML5         
     */
        $katArray = $this->GetCategories();
        $anzahl = count($katArray);
        
        if ($anzahl > 1)
        {
            echo "<ul data-role=\"listview\" data-inset=\"true\">";
            foreach ($katArray as $kat) 
            {
                $longName = $this->KatLangName($kat);
                echo "<li><a href=\"#rang$kat\">$longName</a></li>";
            }
            echo "</ul>";
        }
        else
        {
            foreach ($katArray as $kat) 
            {
                $longName = $this->KatLangName($kat);
                echo "<h3>$longName<h3>";
            }
            echo '<div data-role="content" role="main">';
            
            $this->Rangliste();
            echo '</div>';
        }
    }

    function GuiListElements($final = 0)
    {
    /**
     * Liste der Elementen / Geräte
     *
     * @param   int     $final      Gerätefinal
     * @return printout jQuery Mobile in HTML5           
     */
        if ($final) $elArray = $this->GetElementsFinal();
        else $elArray = $this->GetElements();
        $anzahl = count($elArray);
        if ($anzahl > 0)
        {
            foreach ($elArray as $el) 
            {
                $anzahlCat = count($this->GetCategories($el));
                $longName = $this->ElementLangName($el);
                if ($anzahlCat > 1)  
                {
                    if ($final) echo '<li><a href="#'.$el.'">'.$longName.'<span class="ui-li-count">'.$anzahlCat.'</span></a></li>';
                    else echo '<li><a href="#'.$el.'">&Uuml;bung '.$longName.' <span class="ui-li-count">'.$anzahlCat.'</span></a></li>';
                }
                else
                {
                    if ($final) echo '<li><a href="#'.$el.'">'.$longName.'</a></li>';
                    else echo '<li><a href="#'.$el.'">&Uuml;bung '.$longName.'</a></li>';
                }
            }
        }
        else
        {
            echo '<li>Noch keine Resultate vorhanden</li>';
        }
    }
        
    function GuiKatRanglistenPages()
    {
    /**
     * Rangliste nach Kategorien
     *
     * @return printout jQuery Mobile in HTML5            
     */
        $katArray = $this->GetCategories();
        foreach ($katArray as $kat)
        {
            echo '<div data-role="page" id="rang'.$kat.'">';
            include('panel.php');
            $this->GuiListElements();
            echo "</div>";
            $this->MobileHeader(1,"rang".$kat);
            echo '<div data-role="content" role="main">';
            echo '<h3>Rangliste '.$this->KatLangName($kat).'</h3>';
            $this->Rangliste($kat);
            echo '</div>';
            $this->MobileFooter();
            echo '</div>';
        }
    }

    function GuiElementRanglistenPages ($final = 0)
    {
    /**
     * Rangliste nach Elemente/Geräte
     *
     * @param   int     $final      Gerätefinal
     * @return printout jQuery Mobile in HTML5           
     */   
        if ($final) $elArray = $this->GetElementsFinal();
        else $elArray = $this->GetElements();

        foreach ($elArray as $el)
        {
            $katArray = $this->GetCategories($el);
            $katAnzahl = count($katArray);
            echo '<div data-role="page" id="'.$el.'">';
            include('panel.php');
            $this->GuiListElements();
            echo "</div>";
            $this->MobileHeader(1,$el);
            echo '<div data-role="content" role="main">';
            echo '<h3>Rangliste &Uuml;bung '.$this->ElementLangName($el).'</h3>';
            if ($katAnzahl > 1)
            {
                echo "<ul data-role=\"listview\" data-inset=\"true\">";
                foreach ($katArray as $kat) 
                {
                    $longName = $this->KatLangName($kat);
                    echo "<li><a href=\"#rang$el$kat\">$longName</a></li>";
                }
                echo "</ul>";
            }
            else
            {
                foreach ($katArray as $kat) 
                {
                    $longName = $this->KatLangName($kat);
                    echo "<h3>$longName</h3>";
                }
                $this->RanglisteElement($el);
            }
            echo '</div>';
            $this->MobileFooter();
            echo '</div>';
        }
    }

    function GuiRanglisteKategorieElementPages ($final = 0)
    {
    /**
     * Rangliste nach Kategorien/Elemente
     *
     * @param   int     $final      Gerätefinal
     * @return printout jQuery Mobile in HTML5           
     */    
        if ($final) $elArray = $this->GetElementsFinal();
        else $elArray = $this->GetElements();
        foreach ($elArray as $el)
        {
            $katArray = $this->GetCategories($el);
            $katAnzahl = count($katArray);
            if ($katAnzahl > 1)
            {
                foreach ($katArray as $kat) 
                {
                    echo '<div data-role="page" id="rang'.$el.$kat.'">';
                    include('panel.php');
                    $this->GuiListElements();
                    echo "</div>";
                    $this->MobileHeader(1,"rang".$el.$kat);
                    echo '<div data-role="content" role="main">';
                    echo '<h3>Rangliste &Uuml;bung '.$this->ElementLangName($el).' von '.$this->KatLangName($kat).'</h3>';
                    $this->RanglisteElement($el,$kat);
                    echo '</div>';
                    $this->MobileFooter();
                    echo '</div>';
                }
            }
        }
    }

    function GetCategories ($el = "")
    {    
    /**
     * Holt die Liste der Kategorien
     *
     * @param   string  $el         Element/Gerät
     * @return  array   $katArray   Alle Kategorien die verwendet wurden.
     */
        if ($el == "") 
        {   
            $sqlWhere  = "ohg > 0 OR seil > 0 OR ball > 0 OR reif > 0 OR keulen > 0 OR band > 0 OR uebung1 > 0 OR uebung2 > 0";
        }
        else
        {
            $sqlWhere = "".$el." > 0 ";
        } 
        $katArray = array();
        $stmt = $this->db->prepare("SELECT DISTINCT kat FROM $this->table WHERE  $sqlWhere ORDER BY kat ASC");
        $stmt->execute();
        while ($row = $stmt->fetch())
        {
            if ($row['kat'] != "") $katArray[] = $row['kat'];
        }
        return $katArray;
    }

    function GetElements ()
    {    
    /**
     * Holt die Liste der Elemente/Geräte
     *
     * @return  array   $elArray    Alle Elemente/Geräte die verwendet wurden    
     */
        $elArray = array();
        $sql = "SELECT SUM(ohg) as ohg,SUM(seil) as seil,SUM(ball) as ball,SUM(reif) as reif,SUM(keulen) as keulen,SUM(band) as band,SUM(uebung1) as uebung1,SUM(uebung2) as uebung2 FROM $this->table WHERE ohg > 0 OR seil > 0 OR ball > 0 OR reif > 0 OR keulen > 0 OR band > 0 OR uebung1 > 0 OR uebung2 > 0";
        foreach ($this->db->query($sql) as $row)
        {
            if ($row['ohg'] > 0) $elArray[] = "ohg";
            if ($row['seil'] > 0) $elArray[] = "seil";
            if ($row['ball'] > 0) $elArray[] = "ball";
            if ($row['reif'] > 0) $elArray[] = "reif";
            if ($row['keulen'] > 0) $elArray[] = "keulen";
            if ($row['band'] > 0) $elArray[] = "band";
            if ($row['uebung1'] > 0) $elArray[] = "uebung1";
            if ($row['uebung2'] > 0) $elArray[] = "uebung2";
        }
        return $elArray;
    }

    function GetElementsFinal ()
    {
    /**
     * Geräte Elemente nur bei SM / oder grosse Veranstalltungen
     *
     * @return  array   $elArray    Alle Gerätefinals Elemente die verwendet wurden          
     */
        $elArray = array();
        $sql = "SELECT SUM(gf_seil) as gf_seil,SUM(gf_ball) as gf_ball,SUM(gf_reif) as gf_reif,SUM(gf_keulen) as gf_keulen,SUM(gf_band) as gf_band FROM $this->table WHERE gf_seil > 0 OR gf_ball > 0 OR gf_reif > 0 OR gf_keulen > 0 OR gf_band > 0";
        foreach ($this->db->query($sql) as $row)
        {
            if ($row['gf_seil'] > 0) $elArray[] = "gf_seil";
            if ($row['gf_ball'] > 0) $elArray[] = "gf_ball";
            if ($row['gf_reif'] > 0) $elArray[] = "gf_reif";
            if ($row['gf_keulen'] > 0) $elArray[] = "gf_keulen";
            if ($row['gf_band'] > 0) $elArray[] = "gf_band";
        }
        return $elArray;
    }

    function LastEnties ()
    {
    /**
     * Letzter Eintrag
     *
     * @return date     $row[0]     Date        
     */
        $sql = "SELECT DATE_FORMAT(MAX(`update`), '%d.%m.%y - %H:%i') FROM $this->table";
        $stmt = $this->db->query($sql);
        $row = $stmt -> fetch(PDO::FETCH_BOTH);
        return $row[0];
        
    }

    function KatLangName ($kat)
    {
    /**
     * Name der Kategorien ausgeschrieben.
     *
     * @param   string  $kat    Kategorie kurz Bezeichnung.
     * @return  string  return  Kategorie ausgeschreiben.         
     */
        switch ($kat)
        {
            case 'P2':
            return 'Jugend P2';
            break;
            case 'P3':
            return 'Jugend P3';
            break;
            case 'P4':
            return 'Juniorinnen P4';
            break;
            case 'P5':
            return 'Juniorinnen P5';
            break;            
            case 'P6':
            return 'Seniorinnen P6';
            break;            
            case 'G1':
            return 'Gruppen Jugend G1';
            break; 
            case 'G2':
            return 'Gruppen Jugend G2';
            break;             
            case 'G3':
            return 'Gruppen Juniorinnen G3';
            break;
            case 'G4':
            return 'Gruppen Seniorinnen G4';
            default:
            return "unknown";
        }
    }

    function ElementLangName ($el)
    {
    /**
     * Name der Element/Gerät ausgeschrieben.
     *
     * @param   string  $el     Geräte kurz Bezeichnung.
     * @return  string  return  Gerät ausgeschreiben.         
     */
        switch ($el)
        {
            case 'seil':
            return 'Seil';
            break;
            case 'ball':
            return 'Ball';
            break;
            case 'reif':
            return 'Reif';
            break;
            case 'keulen':
            return 'Keulen';
            break;            
            case 'band':
            return 'Band';
            break;
            case 'uebung1':
            return '1 (Gruppe)';
            break;
            case 'uebung2':
            return '2 (Gruppe)';
            break;
            case 'gf_seil':
            return 'Ger&auml;tefinal Seil';
            break;
            case 'gf_ball':
            return 'Ger&auml;tefinal Ball';            
            break;
            case 'gf_reif':
            return 'Ger&auml;tefinal Reif';
            break;
            case 'gf_keulen':
            return 'Ger&auml;tefinal Keulen';              
            break;
            case 'gf_band':
            return 'Ger&auml;tefinal Band';  
            default:
            return "ohne Handger&auml;te";
        }
    }

    function SqlUpdateData($startnr,$element,$wert,$user = "WEB")
    {
    /**
     * Die Inputdaten werden in die MySQL geschrieben, 
     *
     * @param   string  $startnr    Startnummer
     * @param   string  $element    Element / Gerät
     * @param   string  $wert       Kampfrichterwertung
     * @param   string  $user       Woher stammt die Eingabe, wie input.php = WEB
     */
        try {
            $stmt = $this->db->prepare ("UPDATE $this->table SET
                                ".$element." = :".$element.",
                                `update` = NOW()
                                WHERE startnr = :startnr
                                ");
            $stmt -> bindParam(':'.$element.'', $wert);
            $stmt -> bindParam(':startnr',$startnr);
            $stmt -> execute();
        } catch (PDOException $e) {
            $this->WriteLog ("log_db-error", date('d.m.y H:i:s')." - Fehler beim Update ".$this->table.": ".$e->getMessage(). "\n");
        }
        $this->WriteLog ("log", date('d.m.y H:i:s').";$user;$startnr;$element;$wert\n");
    }

    function SqlGetNote($startnr,$element,$dez)
    {
    /**
     * SQL Abfrage der Note
     *
     * @param   string  $startnr    Startnummer
     * @param   string  $element    Element / Gerät
     * @param   int     $dez        Länge der Dezimalzahl nach Komma
     * @return  string  $noteString Die Note im String/float       
     */
        $noteString = "";
        $noteArray = array ();
        $stmt = $this->db->prepare("SELECT note_d, note_e FROM $this->tableNote WHERE startnr = :startnr AND element = :element");
        $stmt -> bindParam(':startnr', $startnr);
        $stmt -> bindParam(':element', $element);
        $stmt->execute();
        while ($row = $stmt->fetch())
        {
            if ($row['note_d'] > 0) $noteString = "D:".number_format($row['note_d'],$dez)." E:".number_format($row['note_e'],$dez)."";
        }
        return $noteString;
    }

    function SqlUpdateDataNote($startnr,$element,$note_d,$note_e,$user = "WEB")
    {
    /**
     * Die Update der Daten in die MySQL geschrieben, 
     *
     * @param   string  $startnr    Startnummer
     * @param   string  $element    Element / Gerät
     * @param   string  $note_d     Kampfrichterwertung D-Note
     * @param   string  $note_e     Kampfrichterwertung E-Note
     * @param   string  $user       Woher stammt die Eingabe, wie input.php = WEB
     */
        $stmt1 = $this->db->prepare("SELECT count(*) FROM $this->tableNote WHERE startnr = :startnr AND element LIKE :element");
        $stmt1 -> bindParam(':startnr', $startnr);
        $stmt1 -> bindParam(':element', $element);
        $stmt1->execute();
        $rowCount = $stmt1->fetch(PDO::FETCH_NUM);
        
        if ($rowCount[0] > 0)
        {
            try {
                $stmt = $this->db->prepare ("UPDATE $this->tableNote SET
                                    note_d = :note_d,
                                    note_e = :note_e
                                    WHERE 
                                    startnr = :startnr
                                    AND
                                    element = :element
                                    ");
                $stmt -> bindParam(':note_d',$note_d);
                $stmt -> bindParam(':note_e',$note_e);
                $stmt -> bindParam(':startnr',$startnr);
                $stmt -> bindParam(':element',$element);
                $stmt -> execute();
            } catch (PDOException $e) {
                $this->WriteLog ("log_db-error", date('d.m.y H:i:s')." - Fehler beim Update($note_d,$note_e) ".$this->table.": ".$e->getMessage(). "\n");
            }
        }
        else
        {
            try {
                $stmt = $this->db->prepare ("INSERT INTO $this->tableNote (startnr, element, note_d, note_e ) VALUES (:startnr, :element, :note_d, :note_e)");                   
                $stmt -> bindParam(':note_d',$note_d);
                $stmt -> bindParam(':note_e',$note_e);
                $stmt -> bindParam(':startnr',$startnr);
                $stmt -> bindParam(':element',$element);
                $stmt -> execute();
            } catch (PDOException $e) {
                $this->WriteLog ("log_db-error", date('d.m.y H:i:s')." - Fehler beim Insert ($note_d,$note_e) ".$this->table.": ".$e->getMessage(). "\n");
            }
        }
        $this->WriteLog ("log", date('d.m.y H:i:s').";$user;$startnr;$element;D:$note_d - E:$note_e\n");
    }

    function SelectElements($fixEle)
    {
    /**
     * Drop-Down für Elemente/Gerät
     *
     * @param   string  $fixEl  Fix Wert    
     * @return  string  HTML oder leer
     */
        $arraySmall =  array ("ohg","seil","ball","reif","keulen","band","uebung1","uebung2");
        $arrayBig   =  array ("oHg","Seil","Ball","Reif","Keulen","Band","Gruppe Uebung1","Gruppe Uebung2");
        if ($this->gf == 1)
        {
            $arraySmallGf =  array ("gf_seil","gf_ball","gf_reif","gf_keulen","gf_band");
            $arrayBigGf   =  array ("GF Seil","GF Ball","GF Reif","GF Keulen","GF Band");
            $arraySmall = array_merge($arraySmall, $arraySmallGf);
            $arrayBig = array_merge($arrayBig, $arrayBigGf );
        }
        echo '<select name="element" id="select-choice-element">';
        $x=0;
        foreach ( $arraySmall as $element)
        {
            echo '<option value="'.$element.'" '.$this->GetFormSelected ($element,$fixEle).'>'.$arrayBig[$x].'</option>';
            $x++;
        }
        echo '</select>';
    }

    function GetFormSelected($variable, $fix)
    {
    /**
     * Gibt den Namen selected für HTML Form SELECT zurück wenn Variable und fix gleich sind.
     *
     * @param   string  $variable   Variable Wert
     * @param   string  $fix        Fix Wert    
     * @return  string  HTML oder leer
     */
        
        if ($variable == $fix)
        {
            return "selected=\"selected\"";
        }
        else
        {
            return "";
        }
    }
    
    function DBconnection ($username,$passwrd,$database)
    {
    /** 
     * Connection to MySQL DB over PDO 
     *
     * @param   string  $username       DB Username
     * @param   string  $passwrd        DB Password
     * @param   string  $db_database    DB Database
     * @return  string  $db Connection String 
     */
        global $db;
        try {
        $db = new PDO('mysql:host=localhost;dbname='.$database, $username, $passwrd, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            //echo 'Verbindung fehlgeschlagen: ' . $e->getMessage();
            $this->WriteLog ("log_db-error", date('d.m.y H:i:s')." - Verbindung fehlgeschlagen ".$this->table.": ".$e->getMessage(). "\n");
        }
        return $db;
    }
    
    function WriteLog($file, $inhalt) 
    {
    /**
     * Schreibe Logfile
     *
     * @param   string  $file       Name der Datei
     * @param   string  $inhalt     Textinhalt
     * @return  write File        
     */    
        $handle = fopen($file.".txt","a+");
        fwrite($handle,str_replace("\r\n"," ",utf8_decode($inhalt)));
        fclose($handle);
    }
} // End Class
?>