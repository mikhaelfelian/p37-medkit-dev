<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Data Transaksi <small>Pembelian</small></h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('page=home') ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Transaksi</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-lg-12">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <?php echo $this->session->flashdata('transaksi') ?>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <?php echo nbs(3) ?>
                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>No. Purchase Order</th>
                                    <th>Tgl Transaksi</th>
                                    <th>Keterangan</th>
                                    <th>Supplier</th>
                                    <th colspan="3">#</th>
                                </tr>
                            </thead>
                            <?php echo form_open(base_url('transaksi/set_cari_po.php'), 'autocomplete="off"') ?>
                            <?php echo form_hidden('hal', $this->input->get('halaman')) ?>

                            <tbody>                                
                                <tr>
                                    <td></td>
                                    <td>
                                        <?php echo form_input(array('id' => 'no_nota', 'name' => 'no_nota', 'class' => 'form-control')) ?>
                                    </td>
                                    <td><?php echo form_input(array('id' => 'tgl', 'name' => 'tgl', 'class' => 'form-control')) ?></td>
                                    <td><?php // echo form_input(array('id' => 'tgl_tempo', 'name' => 'tgl_tempo', 'class' => 'form-control')) ?></td>
                                    <td>
                                        <?php echo form_input(array('id' => 'cari_supplier', 'name' => 'cari_supplier', 'class' => 'form-control')) ?>
                                        <input type="hidden" id="supplier" name="supplier">
                                    </td>
                                    <td></td>
                                    <td colspan="2"><button class="btn btn-primary">Filter</button></td>
                                </tr>
                            </tbody>
                            <?php echo form_close() ?>
                            <tbody>
                                <?php
                                if (!empty($penj)) {
                                    $no = (!empty($_GET['halaman']) ? $_GET['halaman'] + 1 : 1);
                                    foreach ($penj as $penj) {
                                        $tgl      = explode('-', $penj->tgl_masuk);
                                        $tgl_t    = explode('-', $penj->tgl_keluar);
                                        $sales    = $this->ion_auth->user($penj->id_user)->row();
                                        $supplier = $this->db->where('id', $penj->id_supplier)->get('tbl_m_supplier')->row();
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo $no++ ?>.</td>
                                            <td><?php echo anchor(base_url('transaksi/trans_beli_po_det.php?id='.general::enkrip($penj->id)), $penj->no_nota, 'class="text-default"') ?></td>
                                            <td><?php echo $tgl[1] . '/' . $tgl[2] . '/' . $tgl[0] ?></td>
                                            <td><?php echo $penj->keterangan ?></td>
                                            <td><?php echo $supplier->nama ?></td>
                                            <td>
                                                <?php if($penj->status_nota != 3){ ?>
                                                    <?php echo anchor(base_url('transaksi/trans_beli_edit.php?id=' . general::enkrip($penj->id)), '<i class="fa fa-edit"></i> Ubah', 'class="label label-default"') ?>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php echo general::status_nota_po($penj->status_nota) ?>
                                            </td>
                                            <td>
                                                <?php echo anchor(base_url('transaksi/cetak_nota_beli_po.php?id=' . general::enkrip($penj->id)), '<i class="fa fa-print"></i> Cetak PO', 'class="label '.($penj->cetak == '1' ? 'label-danger' : 'label-warning').'"') ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (!empty($pagination)) { ?>
                        <div class="box-footer">                        
                            <ul class="pagination pagination-sm no-margin pull-left">
                                <?php echo $pagination ?>
                            </ul>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<style>
    .clicked {
        background-color: #ffff00;
    }
</style>

<!-- Page script -->
<script src="<?php echo base_url('assets/theme/admin-lte-2/plugins/JAutoNumber/autonumeric.js') ?>"></script>
<script src="<?php echo base_url('assets/theme/admin-lte-2/plugins/jQueryUI/jquery-ui.js') ?>"></script>
<link href="<?php echo base_url('assets/theme/sb-admin') ?>/ui/jquery-ui.min.css" rel="stylesheet">

<!--Datepicker-->
<script src="<?php echo base_url('assets/theme/admin-lte-2/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<link rel="stylesheet" href="<?php echo base_url('assets/theme/admin-lte-2/plugins/datepicker/datepicker3.css'); ?>">

<script>
    $(function () {
//         Tanggale Nota
        $('#tgl').datepicker({
            autoclose: true,
        });

//        Autocomplete Nyari Kasir
        $('#cari_supplier').autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "<?php echo base_url('transaksi/json_supplier.php') ?>",
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            minLength: 1,
            select: function (event, ui) {
                var $itemrow = $(this).closest('tr');
                // Populate the input fields from the returned values
                $itemrow.find('#cari_supplier').val(ui.item.nama);
                $('#cari_supplier').val(ui.item.nama);
                $('#supplier').val(ui.item.id);

                // Give focus to the next input field to recieve input from user
                $('#cari_kasir').focus();
                return false;
            }
            // Format the list menu output of the autocomplete
        }).data("ui-autocomplete")._renderItem = function (ul, item) {
            return $("<li></li>")
                    .data("item.autocomplete", item)
                    .append("<a>" + item.nama + "</a>")
                    .appendTo(ul);
        };
    });
</script>