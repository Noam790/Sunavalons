<?php
function call_flask_api($endpoint) {
    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FAILONERROR, false); // Important pour récupérer les erreurs 400
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        return ['error' => "Erreur lors de la communication avec le serveur Flask."];
    }

    $data = json_decode($response, true);

    // Si Flask a renvoyé une erreur dans le JSON
    if (isset($data["error"])) {
        return ['error' => $data["error"]];
    }

    return ['data' => $data];
}

function get_csv_path($ville) {
    $upload_dir = __DIR__ . "/../../data/";
    $upload_name = "arbres_" . strtolower($ville) . ".csv";
    return $upload_dir . $upload_name;
}

function has_csv_file($ville) {
    if (!$ville) return false;
    return file_exists(get_csv_path($ville));
}

function handle_form_submission() {
    require_once 'includes/validate.php';
    require_once 'includes/clean_data.php';
    $result = [
        'error' => null,
        'success' => null,
        'best_trees' => null,
        'worst_trees' => null,
        'ville' => null,
    ];

    if ($_SERVER["REQUEST_METHOD"] !== "POST") return $result;

    $ville = $_POST["ville"] ?? null;
    $result['ville'] = $ville;

    if (!validate_ville($ville)) {
        $result['error'] = "Nom de ville invalide.";
        return $result;
    }

    // Upload CSV
    if (isset($_FILES["csv_file"]) && $ville) {
        $upload_path = get_csv_path($ville);
        $original_filename = $_FILES["csv_file"]["name"];
        $file_extension = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));

        if (!in_array($file_extension, ['csv', 'ods', 'xlsx'])) {
            $result['error'] = "Format de fichier non supporté. Seuls les fichiers CSV, ODS ou XLSX sont acceptés.";
            return $result;
        }

        // Suppression si existant
        if (has_csv_file($upload_path)) {
            unlink($upload_path);
        }

        // Traitement / Sauvegarde
        if (process_csv_file($_FILES["csv_file"]["tmp_name"], $upload_path)) {
            $result['success'] = "Le fichier a été mis à jour avec succès !";
            header('Location: ../comparator.php');
            exit;
        } else {
            $result['error'] = "Erreur lors de la mise à jour du fichier.";
        }
        return $result;
    }

    // Appel API
    $url = "http://127.0.0.1:5000/api/city_trees?ville=" . urlencode($ville);
    $api_result = call_flask_api($url);
    if (isset($api_result['error'])) {
        $result['error'] = $api_result['error'];
    } else {
        $data = $api_result['data'];
        $result['best_trees'] = $data["best_trees"];
        $result['worst_trees'] = $data["worst_trees"] ?? null;
    }
    return $result;
}

?>
