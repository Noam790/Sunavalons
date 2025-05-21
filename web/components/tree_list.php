<h2><?= $title ?></h2>
<div class="card-table-plantator">
    <?php foreach ($trees as $tree_data): ?>
        <?= render_tree_card($tree_data, $image_path); ?>
    <?php endforeach; ?>
</div>
