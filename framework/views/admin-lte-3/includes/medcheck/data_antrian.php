<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Antrian</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">Data Antrian</h3>
                            <div class="card-tools">
                                <ul class="pagination pagination-sm float-right">
                                    <?php echo $pagination ?>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <?php echo $this->session->flashdata('medcheck'); ?>
                            <table class="table table-striped project">
                                <thead>
                                    <?php echo form_open_multipart(base_url('medcheck/set_cari_daftar.php'), 'autocomplete="off"') ?> 
                                    <tr>
                                        <td colspan="2">
                                            <?php echo form_input(array('id' => 'tgl', 'name' => 'tgl_daftar', 'class' => 'form-control rounded-0', 'placeholder' => 'Tgl Pendaftaran ...', 'value' => (!empty($_GET['filter_tgl']) ? $this->tanggalan->tgl_indo2($this->input->get('filter_tgl')) : ''))) ?>
                                        </td>
                                        <td colspan="3">
                                            <?php echo form_input(array('id' => '', 'name' => 'pasien', 'class' => 'form-control rounded-0', 'placeholder' => 'Pasien ...', 'value' => (!empty($_GET['filter_nama']) ? $_GET['filter_nama'] : ''))) ?>
                                            <input type="hidden" id="id_daftar" name="id_daftar" value="<?php echo (!empty($_GET['id']) ? $_GET['id'] : '') ?>">
                                        </td>
                                        <td>

                                        </td>
                                        <td class="text-left">
                                            <button class="btn btn-primary btn-flat">
                                                <i class="fa fa-search-plus"></i> Filter
                                            </button>
                                        </td>
                                    </tr>
                                    <?php echo form_close() ?> 
                                    <tr>
                                        <th style="width: 10px">No.</th>
                                        <th style="width: 50px;">Tgl</th>
                                        <th style="width: 50px;">Antrian</th>
                                        <th style="width: 100px;">Tipe</th>
                                        <th style="width: 100px;">Status</th>
                                        <th style="width: 100px;">CS</th>
                                        <th style="width: 100px;">Panggil</th>
                                        <th style="width: 100px;">Selesai</th>
                                        <th style="width: 250px;">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($penj)) {
                                        $no = (!empty($_GET['halaman']) ? $_GET['halaman'] + 1 : 1);
                                        foreach ($penj as $penj) {
                                            $sql_poli    = $this->db->where('kode', $penj->cnoro)->get('mpoli')->row();
                                            ?>
                                            <tr>
                                                <td class="text-center bg-<?php echo $sql_poli->style ?>"><?php echo $no++ ?>.</td>
                                                <td class="bg-<?php echo $sql_poli->style ?>" style="width: 150px; font-color: #fff;">
                                                    <!--<span class="mailbox-read-time float-left"><?php echo $this->tanggalan->tgl_indo2($penj->ddate) ?></span>-->
                                                    <?php echo $this->tanggalan->tgl_indo2($penj->ddate) ?>
                                                    <?php echo br(); ?>
                                                    <small><i><b><?php echo $poli->lokasi ?></b></i></small>
                                                </td>
                                                <td class="bg-<?php echo $sql_poli->style ?>" style="width: 50px;"><?php echo sprintf('%03d', $penj->ncount) ?></td>
                                                <td class="bg-<?php echo $sql_poli->style ?>" style="width: 150px;">
                                                    <span class="badge bg-<?php echo $sql_poli->style ?> float-left"><?php echo $sql_poli->poli; ?></span>
                                                </td>
                                                <td class="bg-<?php echo $sql_poli->style ?>">                                                    
                                                    <?php if ($penj->cnote == 'PRINT') { ?>
                                                        <span class="badge bg-primary float-center">BARU</span>
                                                    <?php } elseif ($penj->cnote == 'PANGGILAN') { ?>
                                                        <span class="badge bg-success float-center">PROSES</span>
                                                    <?php } ?>
                                                </td>
                                                <td class="bg-<?php echo $sql_poli->style ?>"><a href="<?php echo base_url('medcheck/data_antrian_det.php?id=' . $penj->id . '&antrian=' . $penj->ncount . '&type=' . $penj->cnoro . '&status=' . $penj->status.'&status_pgl='.$penj->status_pgl.'&id_view='.$penj->id_view.'&poli='.$sql_poli->poli) ?>" style="text-decoration-line: underline;"><b><?php echo $penj->cnote; ?></b> [<?php echo $penj->ccustsrv; ?>]</a></td>
                                                <td class="bg-<?php echo $sql_poli->style ?>"><?php echo substr($penj->ddatepross, 11, 8); ?></td>
                                                <td class="bg-<?php echo $sql_poli->style ?>"><?php echo substr($penj->ddateend, 11, 8); ?></td>
                                                <td class="bg-<?php echo $sql_poli->style ?>"><?php echo substr($penj->ddateend, 11, 8); ?></td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- /.content-wrapper -->
<!-- /.content-wrapper -->
<script src="<?php echo base_url('assets/theme/admin-lte-2/plugins/JAutoNumber/autonumeric.js') ?>"></script>
<script src="<?php echo base_url('assets/theme/admin-lte-3/plugins/jquery-ui/jquery-ui.js') ?>"></script>
<link href="<?php echo base_url('assets/theme/admin-lte-3/plugins/jquery-ui/jquery-ui.min.css') ?>" rel="stylesheet">

<!-- Page script -->
<script type="text/javascript">
    $(function () {
        $("#tgl").datepicker({
            format: 'dd/mm/yyyy',
            //defaultDate: "+1w",
//            SetDate: new Date(),
            changeMonth: true,
//            minDate: dateToday,
            autoclose: true
        });

//        // Data Pasien
//        $('#pasien').autocomplete({
//            source: function (request, response) {
//                $.ajax({
//                    url: "<?php // echo base_url('medcheck/json_medcheck_ant.php')  ?>",
//                    dataType: "json",
//                    data: {
//                        term: request.term
//                    },
//                    success: function (data) {
//                        response(data);
//                    }
//                });
//            },
//            minLength: 1,
//            select: function (event, ui) {
//                var $itemrow = $(this).closest('tr');
//                //Populate the input fields from the returned values
//                $itemrow.find('#id_daftar').val(ui.item.id);
//                $('#id_daftar').val(ui.item.id);
//                $('#no_antrian').val(ui.item.no_urut);
//                $('#pasien').val(ui.item.nama2);
//
//                // Give focus to the next input field to recieve input from user
//                $('#pasien').focus();
//                return false;
//            }
//
//            // Format the list menu output of the autocomplete
//        }).data("ui-autocomplete")._renderItem = function (ul, item) {
//            return $("<li></li>")
//                    .data("item.autocomplete", item)
//                    .append("<a>" + item.nama2 + "</a> <a>(" + item.jns_klm + ")</a></br><a>" + item.tgl_lahir + "</a></br><a>" + item.alamat + "</a><br/>-------------------------------------------------------------------------------------</a>")
//                    .appendTo(ul);
//        };
    });
</script>