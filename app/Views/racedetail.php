<?= $this->extend("layout/master"); ?>
<?= $this->section("content"); ?>
 
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="display-4 mb-0">Detaily závodů z roku <?= $vybranyRok ?></h1>
        <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="bi bi-plus-circle"></i> Přidat ročník
        </button>
    </div>
 
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
 
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($detail as $row): ?>
            <?php
                $display_meters = $row->vertical_meters;
                if ($display_meters == '0' || empty($display_meters)) {
                    $display_meters = 'Neznámé data';
                }
               
                $raceYearId = $row->race_year_id ?? null;
            ?>
            <div class="col">
 
                <div class="card h-100 shadow-sm overflow-hidden d-flex flex-column justify-content-between">
                    <div>
                        <div class="flag-box">
                            <span class="fi fi-<?php echo $row->country; ?> border border-dark"></span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title bold fs-4"><?= $row->real_name; ?></h5>
                            <?php if(!empty($row->uci_tour)): ?>
                                <span class="badge bg-secondary mb-2"><?= $row->uci_tour; ?></span>
                            <?php endif; ?>
                            <hr>
                           
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <p class="card-text mb-2">
                                        <strong>Start:</strong> <?= !empty($row->start_date) ? date('j. n. Y', strtotime($row->start_date)) : 'Nezadáno'; ?>
                                    </p>
                                    <p class="card-text mb-2">
                                        <strong>Konec:</strong> <?= !empty($row->end_date) ? date('j. n. Y', strtotime($row->end_date)) : 'Nezadáno'; ?>
                                    </p>
                                    <p class="card-text mb-2">
                                        <strong>Délka etap:</strong> <?= $row->distance ?? 0; ?> km
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
 
                    <?php if ($raceYearId): ?>
                        <div class="card-footer bg-transparent border-0 d-flex gap-2 pb-3 px-3">
                            <button class="btn btn-outline-primary btn-sm flex-grow-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal<?= $raceYearId ?>">
                                Editovat
                            </button>
                            <a href="<?= base_url('racedetail/delete/' . $raceYearId) ?>"
                               class="btn btn-outline-danger btn-sm flex-grow-1"
                               onclick="return confirm('Opravdu chcete smazat tento ročník závodu?')">
                                Smazat
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
 
            <?php if ($raceYearId): ?>
                <div class="modal fade" id="editModal<?= $raceYearId ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="<?= base_url('racedetail/edit/' . $raceYearId) ?>" method="post" enctype="multipart/form-data">
                                <?= csrf_field() ?>
                                <div class="modal-header">
                                    <h5 class="modal-title">Upravit ročník: <?= $row->real_name ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Rok závodu</label>
                                        <select name="year" class="form-select" required>
                                            <?php for($y = date('Y')+1; $y >= 2000; $y--): ?>
                                                <option value="<?= $y ?>" <?= $y == $row->year ? 'selected' : '' ?>><?= $y ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Název závodu</label>
                                        <input type="text" name="real_name" class="form-control" value="<?= esc($row->real_name) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Start</label>
                                        <input type="date" name="start_date" class="form-control" value="<?= $row->start_date ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Konec</label>
                                        <input type="date" name="end_date" class="form-control" value="<?= $row->end_date ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Délka etap (km)</label>
                                        <input type="number" step="0.1" name="distance" class="form-control" value="<?= $row->distance ?? 0 ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Převýšení (m)</label>
                                        <input type="number" name="vertical_meters" class="form-control" value="<?= $row->vertical_meters ?? 0 ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Změnit Logo (Obrázek)</label>
                                        <input type="file" name="logo" class="form-control" accept="image/*">
                                        <?php if(!empty($row->logo)): ?>
                                            <small class="text-muted">Aktuální soubor: <?= $row->logo ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zavřít</button>
                                    <button type="submit" class="btn btn-primary">Uložit změny</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
 
        <?php endforeach; ?>
    </div>
   
    <div class="d-flex justify-content-center mt-4">
        <?= $pager->links() ?>
    </div>
</div>
 
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('racedetail/add') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Přidat nový ročník závodu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Rok závodu</label>
                        <select name="year" class="form-select" required>
                            <?php for($y = date('Y')+1; $y >= 2000; $y--): ?>
                                <option value="<?= $y ?>" <?= $y == $vybranyRok ? 'selected' : '' ?>><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Název závodu</label>
                        <input type="text" name="real_name" class="form-control" placeholder="Obecný název závodu">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Start</label>
                        <input type="date" name="start_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konec</label>
                        <input type="date" name="end_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Délka etap (km)</label>
                        <input type="number" step="0.1" name="distance" class="form-control" placeholder="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Převýšení (m)</label>
                        <input type="number" name="vertical_meters" class="form-control" placeholder="0">
                    </div>
                   
                    <input type="hidden" name="country" value="cz">
                   
                    <div class="mb-3">
                        <label class="form-label">Logo závodu</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zavřít</button>
                    <button type="submit" class="btn btn-success">Přidat ročník</button>
                </div>
            </form>
        </div>
    </div>
</div>
 
<?= $this->endSection(); ?>