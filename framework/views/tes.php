<table cellspacing="0" cellpadding="1" border="1">
    <thead>
        <tr>
            <th>No.</th>
            <th>No. PO</th>
            <th>Produk</th>
            <th>Harga</th>
            <th>Disk 1</th>
            <th>Disk 2</th>
            <th>Disk 3</th>
            <th>Potongan</th>
            <th>Harga Beli Satuan</th>
            <th>PPN</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; ?>
        <?php foreach ($trx_det as $det) { ?>
        <?php
                $sql_cek_prd = $this->db->select('tbl_trans_beli.no_nota, tbl_trans_beli.status_ppn, tbl_trans_beli_det.harga, tbl_trans_beli_det.jml, tbl_trans_beli_det.jml_satuan, tbl_trans_beli_det.disk1, tbl_trans_beli_det.disk2, tbl_trans_beli_det.disk3, tbl_trans_beli_det.potongan')->join('tbl_trans_beli', 'tbl_trans_beli.id=tbl_trans_beli_det.id_pembelian')->where('tbl_trans_beli_det.id_produk', $det->id)->get('tbl_trans_beli_det')->row();
                $disk1       = $sql_cek_prd->harga - (($sql_cek_prd->disk1 / 100) * $sql_cek_prd->harga);
                $disk2       = $disk1 - (($sql_cek_prd->disk2 / 100) * $disk1);
                $disk3       = $disk2 - (($sql_cek_prd->disk3 / 100) * $disk2);
                
                $harga       = ((($disk3 * $sql_cek_prd->jml) - $sql_cek_prd->jml_potongan) / ($sql_cek_prd->jml * $sql_cek_prd->jml_satuan));
                $ppn         = ($sql_cek_prd->status_ppn == '1' ? ((10 / 100) * $harga) : 0);
                
//                if(empty($sql_cek_prd->harga_beli_ppn)){
//                    crud::update('tbl_m_produk', 'id', $sql_cek_prd->id, array('harga_beli_ppn'=>$ppn));
//                }
                if(!empty($sql_cek_prd->no_nota) && $sql_cek_prd->harga !='1'){
                    crud::update('tbl_m_produk', 'id', $det->id, array('harga_beli'=>$harga,'harga_beli_ppn'=>$ppn));
        ?>
            <tr>
                <td style="text-align: center"><?php echo $no++; ?></td>
                <td><?php echo anchor(base_url('transaksi/trans_beli_det.php?id='.general::enkrip($sql_cek_prd->id_pembelian)), $sql_cek_prd->no_nota, 'target="_blank"'); ?></td>
                <td><?php echo anchor(base_url('master/data_barang_tambah.php?id='.general::enkrip($det->id)),$det->produk, 'target="_blank"'); ?></td>
                <td style="text-align: right"><?php echo general::format_angka($sql_cek_prd->harga); ?></td>
                <td style="text-align: right"><?php echo (float)$sql_cek_prd->disk1; ?></td>
                <td style="text-align: right"><?php echo (float)$sql_cek_prd->disk2; ?></td>
                <td style="text-align: right"><?php echo (float)$sql_cek_prd->disk3; ?></td>
                <td style="text-align: right"><?php echo general::format_angka($sql_cek_prd->potongan); ?></td>
                <td style="text-align: right"><?php echo general::format_angka($harga); ?></td>
                <td style="text-align: right"><?php echo general::format_angka($ppn); ?></td>
                <td style="text-align: right"><?php echo (empty($sql_cek_prd->harga_beli_ppn) ? 'P' : '')?></td>
            </tr>
        <?php } ?>
        <?php } ?>
    </tbody>
</table>