<?php
function call_flask_api($endpoint) {
    $response = @file_get_contents($endpoint);
    if ($response !== false) {
        $data = json_decode($response, true);
        if (isset($data["error"])) {
            return ['error' => $data["error"]];
        }
        return ['data' => $data];
    } else {
        return ['error' => "Erreur lors de la communication avec le serveur Flask."];
    }
}

function handle_form_submission() {
    require_once 'includes/validate.php';
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
        $upload_dir = __DIR__ . "/../../data/";
        $original_filename = $_FILES["csv_file"]["name"];
        $file_extension = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));
        $allowed_extensions = ['csv', 'ods', 'xlsx'];
        if (!in_array($file_extension, $allowed_extensions)) {
            $result['error'] = "Format de fichier non supporté. Seuls les fichiers CSV, ODS ou XLSX sont acceptés.";
        } else {
            $upload_name = "arbres_" . strtolower($ville) . "." . $file_extension;
            $upload_path = $upload_dir . $upload_name;
            if (move_uploaded_file($_FILES["csv_file"]["tmp_name"], $upload_path)) {
                $result['success'] = "Le fichier a bien été envoyé ! Vous pouvez relancer l'analyse.";
            } else {
                $result['error'] = "Erreur lors de l'envoi du fichier.";
            }
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
