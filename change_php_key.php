<?php
/**
 * This is useful to change the year in the copyright header for example.
 *
 * @author         Pierre-Henry Soria <pierrehenrysoria@gmail.com>
 * @copyright      (c) 2012-2017, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License 3 (http://www.gnu.org/licenses/gpl.html)
 * @package        pH7CMS
 */
?>
<!DOCTYPE html>
<head>
<meta charset="utf-8" />
<title>Change KeyWord in Files and recurrent files</title>
<meta name="author" content="Pierre-Henry Soria" />
<style type="text/css">
label {
    display:block;
}
.success, .error, .warning {
    font-weight:bold;
    text-align:center;
}
.success {
    color:green;
}
.error {
    color:red;
}
.warning {
    color: #9F6000;
}
form {
    text-align:center;
}
legend {
    text-align:center;
}
input {
    width:92%;
    height:40px;
    font-size:18px;
}
</style>
<body>

<?php
$aSuccess = array();
$aError = array();
function searchDir($sDir) {
    global $sFile, $sOldKey, $sNewKey;

    $rHandle = opendir($sDir);
    while (false !== ($sFile = readdir($rHandle))) {
        if ($sFile != '.' && $sFile != '..') {
            if (is_dir($sDir . $sFile)) {
                searchDir($sDir . $sFile . '/');
            } else {
                // Change the extensions below if needed. For example, if you want to replace a keyword only in PHP files, remove the following conditions.
                if (
                        substr($sFile, -4) === '.php' || substr($sFile, -3) === '.js' ||
                        substr($sFile, -4) === '.tpl' || substr($sFile, -4) === '.sql' ||
                        substr($sFile, -4) === '.css' ||  substr($sFile, -4) === '.ini' ||
                        substr($sFile, -3) === '.sh'
                    ) {
                    if(filesize($sDir . '/' . $sFile) === 0) {
                        // Skip for empty files
                        continue;
                    }
                    $sContentFile = file_get_contents($sDir . '/' . $sFile);
                    $sContentFile = str_ireplace($sOldKey, $sNewKey, $sContentFile);
                    file_put_contents($sDir . '/' . $sFile, $sContentFile) or print('<p class="warning">Was unable to wrtite file: ' . htmlspecialchars($sDir . '/' . $sFile) . '</p>');
                }
            }
        }
    }
    closedir($rHandle);
    return true;
}

if (!empty($_POST['submit'])) {
    extract($_POST);
    if (!empty($sDir) && !empty($sOldKey) && !empty($sNewKey)) {
        if (!is_dir($sDir)) {
            $aError[] = 'The directory you specified does not exist: ' . htmlspecialchars($sDir);
        } elseif (searchDir($sDir)) {
            $aSuccess[] = 'Traitement terminé avec succès !';
        } else {
            $aError[] = 'Erreur !';
        }
    } else {
        $aError[] = 'Veuillez remplir tous les champs du formulaire';
    }
}
if (count($aError) > 0) {
    foreach ($aError as $sErr)
        echo '<p class="error">' . $sErr . '</p>';
} elseif (count($aSuccess) > 1) {
    foreach ($aSuccess as $sSucc)
        echo '<p class="sucess">' . $sSucc . '</p>';
}
?>
<fieldset>
<legend>Change PHP Key</legend>
<form method="post" action="">
<label for="dir">Directory:</label>
<input type="text" id="dir" name="sDir" value="<?php echo __DIR__ ?>/" />
<label for="old_key">Old Key:</label>
<input type="text" id="old_key" name="sOldKey" value="" />
<label for="new_key">New Key:</label>
<input type="text" id="new_key" name="sNewKey" value="" /><br />
<input type="submit" name="submit" value="Envoyer" /><br />
</form>
</fieldset>

</body>
</html>
