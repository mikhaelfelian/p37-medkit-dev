<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Medical Checkup</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('medcheck/index.php') ?>">Medical Checkup</a></li>
                        <li class="breadcrumb-item active">Tindakan</li>
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
                <div class="col-lg-8">
                    <?php $hasError = $this->session->flashdata('form_error'); ?>
                    <?php $jml = $this->session->flashdata('jml'); ?>
                    <?php echo form_open_multipart(base_url('medcheck/set_kwitansi_simpan.php'), 'autocomplete="off"') ?>                    
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">INPUT PEMBAYARAN DP - <?php echo $sql_pasien->nama_pgl; ?> <small><i>(<?php echo $this->tanggalan->usia($sql_pasien->tgl_lahir) ?>)</i></small></h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-12 col-lg-12 order-2 order-md-1">
                                    <?php echo $this->session->flashdata('medcheck'); ?>
                                    <?php echo form_hidden('id', general::enkrip($sql_medc->id)); ?>
                                    <?php echo form_hidden('status', $this->input->get('status')); ?>
                                    <?php echo form_hidden('status_kwi', '2'); ?>
                                    <?php echo form_hidden('keterangan', 'DP Pembayaran'); ?>
                                    
                                    <input type="hidden" id="id_dokter" name="id_dokter">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group row <?php echo (!empty($hasError['dari']) ? 'text-danger' : '') ?>">
                                                <label for="inputEmail3" class="col-sm-3 col-form-label">Telah diterima dari</label>
                                                <div class="col-sm-9">
                                                    <?php echo form_input(array('id' => 'dari', 'name' => 'dari', 'class' => 'form-control pull-right rounded-0' . (!empty($hasError['dari']) ? ' is-invalid' : ''), 'placeholder' => 'Isikan nama pasien / keluarga pasien ( yang membayar DP ) ...', 'value' => $sql_produk->kode)) ?>
                                                </div>
                                            </div>
                                            <div class="form-group row <?php echo (!empty($hasError['gtotal']) ? 'text-danger' : '') ?>">
                                                <label for="inputEmail3" class="col-sm-3 col-form-label">GRAND TOTAL</label>
                                                <div class="col-sm-9">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">Rp. </span>
                                                        </div>
                                                        <?php echo form_input(array('id' => 'nominal', 'name' => 'gtotal', 'class' => 'form-control pull-right rounded-0' . (!empty($hasError['gtotal']) ? ' is-invalid' : ''), 'placeholder' => 'Inputkan Nominal ...', 'value' => $jml_total, 'readonly' => 'TRUE')) ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row <?php echo (!empty($hasError['nominal']) ? 'text-danger' : '') ?>">
                                                <label for="inputEmail3" class="col-sm-3 col-form-label">Total DP</label>
                                                <div class="col-sm-9">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">Rp. </span>
                                                        </div>
                                                        <?php echo form_input(array('id' => 'nominal', 'name' => 'nominal', 'class' => 'form-control pull-right rounded-0' . (!empty($hasError['nominal']) ? ' is-invalid' : ''), 'placeholder' => 'Isikan Nominal DP...')) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-6">
                                    <button type="button" class="btn btn-primary btn-flat" onclick="window.location.href = '<?php echo base_url('medcheck/tindakan.php?id=' . general::enkrip($sql_medc->id)) ?>'"><i class="fas fa-arrow-left"></i> Kembali</button>
                                </div>
                                <div class="col-lg-6 text-right">
                                    <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-save"></i> Simpan</button>
                                </div>
                            </div>                            
                        </div>
                    </div>
                    <?php echo form_close() ?>
                    <!-- /.card -->

                    <!-- Default box -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">DATA DP PEMBAYARAN</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-left">Dari</th>
                                        <th class="text-right">Nominal</th>
                                        <th class="text-left">Ket</th>
                                        <th class="text-center">#</th>
                                    </tr>                                    
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $gtotal = 0
                                    ?>
                                    <?php foreach ($sql_kwitansi as $det) { ?>
                                        <tr>
                                            <td class="text-left" colspan="5">                            
                                                <small><?php echo $this->tanggalan->tgl_indo2($det->tgl_simpan) . ' ' . $this->tanggalan->wkt_indo($det->tgl_simpan) . ' - '; ?><i><?php echo $this->ion_auth->user($det->id_user)->row()->first_name ?></i></small>                            
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><i><?php echo $no++; ?></i></td>
                                            <td class="text-left"><i><?php echo strtoupper($det->dari); ?></i></td>
                                            <td class="text-right"><i><?php echo general::format_angka($det->nominal); ?></i></td>
                                            <td class="text-left">
                                                <i><?php echo ucfirst($det->ket); ?></i>
                                                <p><?php echo ucfirst($det->diagnosa); ?></p>
                                            </td>
                                            <td class="text-left">
                                                <?php echo anchor(base_url('medcheck/set_kwitansi_hps.php?id=' . general::enkrip($sql_medc->id) . '&id_kwi=' . general::enkrip($det->id) . '&status=' . $this->input->get('status')), 'Hapus &raquo;', 'class="btn btn-danger btn-flat btn-xs text-bold" style="width: 70px;" onclick="return confirm(\'Hapus ?\')"') ?>
                                                <?php echo br() ?>
                                                <?php echo anchor(base_url('medcheck/cetak_kwitansi_pdf.php?id=' . general::enkrip($sql_medc->id) . '&id_kwi=' . general::enkrip($det->id) . '&status=' . $this->input->get('status')), 'Cetak &raquo;', 'class="btn btn-warning btn-flat btn-xs text-bold" style="width: 70px;" target="_blank"') ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <div class="col-lg-4">
                    <?php $data['gtotal'] = $gtotal ?>
                    <?php $this->load->view('admin-lte-3/includes/medcheck/med_tindakan_kanan', $data) ?>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script src="<?php echo base_url('assets/theme/admin-lte-2/plugins/JAutoNumber/autonumeric.js') ?>"></script>
<script src="<?php echo base_url('assets/theme/admin-lte-3/plugins/jquery-ui/jquery-ui.js') ?>"></script>
<script src="<?php echo base_url('assets/theme/admin-lte-3/plugins/moment/moment.min.js') ?>"></script>
<script src="<?php echo base_url('assets/theme/admin-lte-3/plugins/jquery-maxlength/jquery.maxlength.min.js') ?>"></script>
<link href="<?php echo base_url('assets/theme/admin-lte-3/plugins/jquery-ui/jquery-ui.min.css') ?>" rel="stylesheet">

<!-- Toastr -->
<link rel="stylesheet" href="<?php echo base_url('assets/theme/admin-lte-3/plugins/toastr/toastr.min.css') ?>">
<script src="<?php echo base_url('assets/theme/admin-lte-3/plugins/toastr/toastr.min.js') ?>"></script>

<!-- Page script -->
<script type="text/javascript">
    $(function () {
        $("input[id=nominal]").autoNumeric({aSep: '.', aDec: ',', aPad: false});
        
        $("#keterangan").maxLength({
            maxChars: 50,
            clearChars:true,
            onLimitOver: function () {
                toastr.error("Maksimal karakter yang bisa diinput sebanyak 50 karakter termasuk spasi");
            }
        });
        
        <?php echo $this->session->flashdata('medcheck_toast'); ?>
    });
</script>