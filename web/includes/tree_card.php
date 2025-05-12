<?php
$image_path = "assets/trees/";
function render_tree_card(array $tree_data, string $image_path) {
    $eco_score = $tree_data[1];
    $fill_ratio = max(0, min(1, 1 - ($eco_score / 8)));
    $fill_percent = intval($fill_ratio * 100);
    $tree_name = str_replace("_", " ", htmlspecialchars($tree_data[0]));
    $img = $image_path . htmlspecialchars($tree_data[0]) . ".jpg";
    ;?>
    <div class="tree-card">
        <span class="eco-badge">
            <span class="eco-fill" style="width: <?= $fill_percent ?>%;"></span>
            <span class="eco-text">Score éco-compatible</span>
        </span>
        <img src="<?= $img ?>" class="tree-image">
        <div class="tree-content">
            <h3 class="tree-name"><?= $tree_name ?></h3>
        </div>
    </div>
    <?php
}
?>
