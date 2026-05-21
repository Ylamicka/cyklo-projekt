<?=$this->extend("layout/master");?>

<?=$this->section("content");?>
    <h1>Ky$ :3</h1>
<?php
$table = new \CodeIgniter\View\Table();

    $pole = array('Roky', 'Počet Závodů');
$table->setHeading($pole);
foreach($raceYear as $row){
    $table->addRow($row->year,$row->pocet);
}
    $template = array(
'table_open'=> '<table class="table table-bordered">',
'thead_open'=> '<thead>',
'thead_close'=> '</thead>',
'heading_row_start'=> '<tr>',
'heading_row_end'=>' </tr>',
'heading_cell_start'=> '<th>',
'heading_cell_end' => '</th>',
'tbody_open' => '<tbody>',
'tbody_close' => '</tbody>',
'row_start' => '<tr>',
'row_end'  => '</tr>',
'cell_start' => '<td>',
'cell_end' => '</td>',
'row_alt_start' => '<tr>',
'row_alt_end' => '</tr>',
'cell_alt_start' => '<td>',
'cell_alt_end' => '</td>',
'table_close' => '</table>'
);

$table->setTemplate($template);
echo $table->generate();
?>

<?=$this->endSection();?>