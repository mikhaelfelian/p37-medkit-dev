<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!--<h1 class="m-0">Master Data</h1>-->
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('master/index.php') ?>">Master Data</a></li>
                        <li class="breadcrumb-item active">Platform Penjamin</li>
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
                <div class="col-md-4">
                    <?php echo form_open(base_url('master/data_platform_pjm_' . (isset($_GET['id']) ? 'update' : 'simpan') . '.php'), 'autocomplete="off"') ?>
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">Form Data Platform Penjamin</h3>
                            <div class="card-tools">
                                <ul class="pagination pagination-sm float-right">
                                    <?php echo $pagination ?>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <?php echo $this->session->flashdata('master'); ?>
                            <?php $hasError = $this->session->flashdata('form_error'); ?>
                            <?php echo form_hidden('id', $this->input->get('id')) ?>
                            
                            <div class="form-group <?php echo (!empty($hasError['satKcl']) ? 'has-error' : '') ?>">
                                <label class="control-label">Kode</label>
                                <?php echo form_input(array('id' => 'kode', 'name' => 'kode', 'class' => 'form-control rounded-0', 'value' => $platform->kode)) ?>
                            </div>
                            <div class="form-group <?php echo (!empty($hasError['satKcl']) ? 'has-error' : '') ?>">
                                <label class="control-label">Penjamin</label>
                                <?php echo form_input(array('id' => 'platform', 'name' => 'platform', 'class' => 'form-control rounded-0', 'value' => $platform->platform)) ?>
                            </div>
                            <div class="form-group <?php echo (!empty($hasError['satKcl']) ? 'has-error' : '') ?>">
                                <label class="control-label">Biaya (%)</label>
                                <?php echo form_input(array('id' => 'biaya', 'name' => 'biaya', 'class' => 'form-control rounded-0', 'style' => 'width: 75px; text-align:center;', 'value' => (int) $platform->persen)) ?>
                            </div>
                        <div class="form-group">
                            <label class="control-label">Status</label>
                            <div class="col-sm-10">
                                <div class="custom-control custom-radio">
                                    <?php echo form_radio(array('id' => 'customRadio1', 'name' => 'status', 'class' => 'custom-control-input', 'value' => '0')) ?> <label for="customRadio1" class="custom-control-label">Non-Aktif</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <?php echo form_radio(array('id' => 'customRadio2', 'name' => 'status', 'class' => 'custom-control-input', 'value' => '1')) ?> <label for="customRadio2" class="custom-control-label">Aktif</label>
                                </div>                         
                            </div>
                        </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-6">
                                    <button type="button" onclick="window.location.href = '<?php echo base_url('master/data_platform_list.php') ?>'" class="btn btn-primary btn-flat">&laquo; Kembali</button>
                                </div>
                                <div class="col-lg-6 text-right">
                                    <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-save"></i> Simpan</button>
                                </div>
                            </div>                            
                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->