<?php
function clean_tree_name($name) {
    // Tableau de remplacement des accents
    $accents = [
        'À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Å'=>'A','à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a',
        'Ç'=>'C','ç'=>'c',
        'È'=>'E','É'=>'E','Ê'=>'E','Ë'=>'E','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e',
        'Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i',
        'Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','Ø'=>'O','ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o','ö'=>'o','ø'=>'o',
        'Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U','ù'=>'u','ú'=>'u','û'=>'u','ü'=>'u',
        'Ñ'=>'N','ñ'=>'n',
        'Ý'=>'Y','ý'=>'y','ÿ'=>'y',
        'Œ'=>'OE','œ'=>'oe',
        'Æ'=>'AE','æ'=>'ae'
    ];
    $name = strtr($name, $accents); // Supprime accents

    $name = str_replace([' ', '-'], '_', $name);

    // Capitalize
    return ucfirst(strtolower(trim($name)));
}

function process_csv_file($tmp_file, $target_file) {
    if (($handle = fopen($tmp_file, "r")) !== FALSE) {
        $output = fopen($target_file, "w");

        // Écrit l'en-tête
        fputcsv($output, ["genre_francais"]);

        while (($data = fgetcsv($handle)) !== FALSE) {
            if (!empty($data[0])) {
                $clean_name = clean_tree_name($data[0]); // Nettoie
                fputcsv($output, [$clean_name]); // Remplace
            }
        }

        fclose($handle);
        fclose($output);
        return true;
    }
    return false;
}
?>
