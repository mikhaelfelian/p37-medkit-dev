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
                        <li class="breadcrumb-item active">MCU</li>
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
                    <?php
                    switch ($_GET['act']) {

                        default:
                            $data['sql_lab'] = $gtotal;
                            $this->load->view('admin-lte-3/includes/medcheck/med_trans_mcu_index', $data);
                            break;

                        case 'mcu_surat':
                            $data['sql_medc_mcu_rw'] = $sql_medc_mcu_rw;
                            $this->load->view('admin-lte-3/includes/medcheck/med_trans_mcu_surat', $data);
                            break;

                        case 'mcu_input':
                            $data['sql_medc_mcu_rw'] = $sql_medc_mcu_rw;
                            $data['sql_det'] = $sql_det;
                            $this->load->view('admin-lte-3/includes/medcheck/med_trans_mcu_input', $data);
                            break;

                        case 'mcu_hasil':
                            $data['sql_medc_mcu_rw'] = $sql_medc_rad_rw;
                            $data['sql_medc_mcu_hs'] = $this->db->where('id_medcheck', general::dekrip($this->input->get('id')))->where('id_mcu', general::dekrip($this->input->get('id_mcu')))->where('id_medcheck_det', general::dekrip($this->input->get('id_item')))->get('tbl_trans_medcheck_mcu_det')->result();
                            $this->load->view('admin-lte-3/includes/medcheck/med_trans_mcu_hasil', $data);
                            break;
                    }
                    ?>
                </div>
                <div class="col-lg-4">
                    <?php $data['gtotal'] = $gtotal ?>
                    <?php $this->load->view('admin-lte-3/includes/medcheck/med_tindakan_kanan', $data) ?>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script src="<?php echo base_url('assets/theme/admin-lte-2/plugins/JAutoNumber/autonumeric.js') ?>"></script>
<script src="<?php echo base_url('assets/theme/admin-lte-3/plugins/jquery-ui/jquery-ui.js') ?>"></script>
<script src="<?php echo base_url('assets/theme/admin-lte-3/plugins/moment/moment.min.js') ?>"></script>
<link href="<?php echo base_url('assets/theme/admin-lte-3/plugins/jquery-ui/jquery-ui.min.css') ?>" rel="stylesheet">

<!-- Select2 -->
<script src="<?php echo base_url('assets/theme/admin-lte-3/plugins/select2/js/select2.full.min.js') ?>"></script>
<link rel="stylesheet" href="<?php echo base_url('assets/theme/admin-lte-3/plugins/select2/css/select2.min.css') ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/theme/admin-lte-3/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">

<!-- Page script -->
<script type="text/javascript">
    $(function () {
        $("#harga").autoNumeric({aSep: '.', aDec: ',', aPad: false});

        // Select2   
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });
        
        <?php if (!empty($sql_produk)) { ?>
            // Cari Data Dokter
            $('#dokter').autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: "<?php echo base_url('medcheck/json_dokter.php') ?>",
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
                    //Populate the input fields from the returned values
                    $itemrow.find('#id_dokter').val(ui.item.id);
                    $('#id_dokter').val(ui.item.id);
                    $('#dokter').val(ui.item.nama);

                    // Give focus to the next input field to recieve input from user
                    $('#dokter').focus();
                    return false;
                }

                // Format the list menu output of the autocomplete
            }).data("ui-autocomplete")._renderItem = function (ul, item) {
                return $("<li></li>")
                        .data("item.autocomplete", item)
                        .append("<a>" + item.nama + "</a>")
                        .appendTo(ul);
            };
        <?php } ?>
    });
</script>