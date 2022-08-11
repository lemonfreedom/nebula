<div class="container">
    <span>Time：<?= $time ?> 秒</span>
    <span>SQL：<?= count($sqls) ?> 条</span>
    <?php if ('1' === $data['showSQL']) : ?>
        <?php foreach ($sqls as $i => $sql) : ?>
            <div><b>SQL<?= $i + 1 ?>：</b><?= $sql ?></div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
