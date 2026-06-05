<?= $this->extend("layout/master"); ?>
<?= $this->section("content"); ?>

<div class="container py-4">
    <h1 class="display-4 text-center mb-5">Detaily závodů z roku <?= $vybranyRok ?></h1>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($detail as $row): ?>
            <?php 
                $display_meters = $row->vertical_meters;
                if ($display_meters == '0' || empty($display_meters)) {
                    $display_meters = 'Neznámé data';
                }
            ?>
            <div class="col">

                <div class="card h-100 shadow-sm overflow-hidden">
                    <div class="flag-box">
                        <span class="fi fi-<?php echo $row->country; ?> border border-dark"></span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title bold fs-4"><?= $row->real_name; ?></h5>
                        <hr>
                        
                        <div class="row align-items-center">
                            <div class="col-8">
                                <p class="card-text mb-2">
                                    <strong>Start:</strong> <?= date('j. n. Y', strtotime($row->start_date)); ?>
                                </p>
                                <p class="card-text mb-2">
                                    <strong>Konec:</strong> <?= date('j. n. Y', strtotime($row->end_date)); ?>
                                </p>
                                <p class="card-text mb-2">
                                    <strong>Délka etap:</strong> <?= $row->distance; ?> km
                                </p>
                                <p class="card-text mb-0">
                                    <strong>Převýšení:</strong> <?= $display_meters; ?>
                                </p>
                            </div>
                            
                            <div class="col-4 text-center">
                                <?php if (!empty($row->logo)): ?>
                                    <img src="<?= base_url('Images/' . $row->logo); ?>" 
                                         alt="Logo <?= $row->real_name; ?>" 
                                         class="img-fluid rounded" 
                                         style="max-height: 80px; width: auto; object-fit: contain;">
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="d-flex justify-content-center mt-4">
        <?= $pager->links() ?>
    </div>
</div>
<?= $this->endSection(); ?>