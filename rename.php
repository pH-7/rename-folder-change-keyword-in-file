<?php
/**
 * This is useful to change the year in the copyright header for example.
 *
 * @author         Pierre-Henry Soria <pierrehenrysoria@gmail.com>
 * @copyright      (c) 2012-2017, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License 3 (http://www.gnu.org/licenses/gpl.html)
 * @package        pH7CMS
 */

function renameDIR($sDir) {
    $rHandle = opendir($sDir);
    while (false !== ($sFile = readdir($rHandle))) {
        if ($sFile !== '.' && $sFile !== '..') {
            if (is_dir($sDir.$sFile)) {
                renameDIR($sDir . $sFile . '/');
            } else {
                if (substr($sFile, -4) == '.tpl') {
                    if(filesize($sDir . '/' . $sFile) === 0 ) {
                        break;
                    }
                    $sNewFile = str_replace('_','-', $sDir . '/' . $sFile);
                    rename($sDir . '/' . $sFile, $sNewFile);
                }
            }
        }
    }
    closedir($rHandle);
}

renameDIR('/var/www/My-Projects/files/');
