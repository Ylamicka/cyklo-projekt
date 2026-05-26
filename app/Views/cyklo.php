<?=$this->extend("layout/master");?>

<?=$this->section("content");?>

<h1 class="display-4 text-center my-4">Roky Závodů</h1>

<div class="mx-3 d-grid gap-2 col-md-6 mx-auto">
    <?php foreach($raceYear as $row): ?>
        <a href="<?= site_url('zavody/rok/' . $row->year); ?>" class="btn btn-outline-dark btn-lg d-flex justify-content-between align-items-center">
            <span>Rok: <strong><?= esc($row->year); ?></strong></span>
            <span class="badge bg-dark rounded-pill"><?= esc($row->pocet); ?> závodů</span>
        </a>
    <?php endforeach; ?>
</div>

<?=$this->endSection();?>