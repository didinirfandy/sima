<?php
	$data['tittle'] = "Pemeliharaan Aset";
	$this->load->view('template/head', $data);
?>
<body>
    <div id="wrapper">
        <?php $this->load->view('template/navbar'); ?>
        <!--/. NAV TOP  -->
        <?php $this->load->view('template/menu'); ?>
        <!-- /. NAV SIDE  -->

		<div id="page-wrapper">
            <div class="header"> 
                <h1 class="page-header">Pemeliharaan Aset</h1>
                <?= $this->session->flashdata('pesan'); ?>
                <ol class="breadcrumb">
                    <li><a href="#"><?php $str = $this->session->userdata('nama_pegawai');
                    echo wordwrap($str, 15, "<br>\n"); ?></a></li>
                    <li><a href="<?=base_url()?>Aset/home">Home</a></li>
                    <li class="active">Pemeliharaan Aset</li>
                </ol> 
            </div>
            
            <div id="page-inner">
                <!-- /. ROW  -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 style="font-weight:bold;">Pemeliharaan Aset</h3>
                                <hr align="right" color="black">
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover table-sm" id="plihara">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Peminjam</th>
                                                <th>Kode Aset</th>
                                                <th>Nama Aset</th>
                                                <th>Merek/Type</th>
                                                <th>Kondisi</th>
                                                <th>Harga</th>
                                                <th>Umur Ekonomis</th>
                                                <th>Nilai Sisa</th>
                                                <th>Nilai Buku</th>
                                                <th>Jenis Pemeliharaan</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tmpl_data">
                                        </tbody>
                                    </table>
                                </div>
                            </div>						
                        </div>   
                    </div>
                </div>	
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 style="font-weight:bold;">Pemeliharaan Aset Internal</h3>
                                <hr align="right" color="black">
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="internal">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Peminjam</th>
                                                <th>Kode Aset</th>
                                                <th>Nama Aset</th>
                                                <th>Merek/Type</th>
                                                <th>Kondisi</th>
                                                <th>Umur Ekonomis</th>
                                                <th>Nilai Sisa</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="dt_internal">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 style="font-weight:bold;">Pemeliharaan Aset External</h3>
                                <hr align="right" color="black">
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="external">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Peminjam</th>
                                                <th>Kode Aset</th>
                                                <th>Nama Aset</th>
                                                <th>Merek/Type</th>
                                                <th>Kondisi</th>
                                                <th>Umur Ekonomis</th>
                                                <th>Nilai Sisa</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="dt_external">
                                        </tbody>
                                    </table>
                                </div>
                            </div>	
                        </div>
                    </div>
                </div>
                <!-- /. ROW  -->
				<?php $this->load->view('template/copyright') ?>
            </div>
            <!-- /. PAGE INNER  -->
            <!-- Modal -->
            <div class="modal fade" id="insert_keterangan" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title" id="exampleModalLongTitle">Keterangan Pemeliharaan</h4>
                        </div>
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea class="form-control" rows="2" name="ket" id="ket" required></textarea>
                                    <input type="hidden" name="id_p_lmbr" value="">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <!-- /. WRAPPER  -->
    <?php $this->load->view('template/script') ?>
    <script type="text/javascript">
        $(document).ready(function() {
            tampil_pengadaan();
            tampil_internal();
            tampil_external();

            $('#plihara').dataTable();
            $('#internal').dataTable();
            $('#external').dataTable();
        })

        function tampil_pengadaan() {
            $.ajax({
                type: "GET",
                url: "<?= base_url('Pemeliharaan_aset/get_data_aset') ?>",
                async: false,
                dataType: "JSON",
                success: function(c) {
                    var pgdn = "";
                    var id_rg = "";
                    for (h = 0; h < c.length; h++) {
                        var bilangan = c[h].nli_sisa;
                            
                        var	reverse  = bilangan.toString().split('').reverse().join(''),
                            ribuan 	 = reverse.match(/\d{1,3}/g);
                            nli_sisa = ribuan.join('.').split('').reverse().join('');

                        var bil = c[h].harga;
                        
                        var	reverse = bil.toString().split('').reverse().join(''),
                            ribuan 	= reverse.match(/\d{1,3}/g);
                            harga	= ribuan.join('.').split('').reverse().join('');

                        if (c[h].umr_ekonomis != "0" && c[h].nli_sisa != "0") {
                            var buku_n          = (c[h].harga - c[h].nli_sisa) / c[h].umr_ekonomis;
                            var dateMonth = new Date();
                            var buku_bulat_bln  = Math.ceil(buku_n / 12 * dateMonth.getMonth() + 1); 
                            var	reverse         = buku_bulat_bln.toString().split('').reverse().join(''),
                                ribuan_bln      = reverse.match(/\d{1,3}/g);
                                nilai_buku_bln  = ribuan_bln.join('.').split('').reverse().join('');
                            // var buku_bulat_thn  = Math.ceil(buku_n); 
                            // var	reverse         = buku_bulat_thn.toString().split('').reverse().join(''),
                            //     ribuan_thn      = reverse.match(/\d{1,3}/g);
                            //     nilai_buku_thn  = ribuan_thn.join('.').split('').reverse().join('');
                            // console.log(buku_bulat_bln);
                        } else {
                            nilai_buku_bln = '0';
                            nilai_buku_thn = '0';
                        }

                        if (c[h].kondisi == 1) {
                            tmbh = "";
                            hps = "";
                            aksi1 = "display: none;";
                            aksi2 = "display: none;";
                            aksi3 = "display: none;";
                            aksi4 = "display: none;";
                        } else if (c[h].kondisi == 2) {
                            tmbh = "";
                            hps = "disabled";
                            aksi1 = "";
                            aksi2 = "display: none;";
                            aksi3 = "display: none;";
                            aksi4 = "display: none;";
                        } else if (c[h].kondisi == 3) {
                            tmbh = "";
                            hps = "disabled";
                            aksi1 = "";
                            aksi2 = "display: none;";
                            aksi3 = "display: none;";
                            aksi4 = "display: none;";
                        } else {
                            aksi1 = "display: none;";
                            aksi2 = "display: none;";
                            aksi3 = "display: none;";
                            aksi4 = "display: none;";
                        }
                        
                        if (c[h].kondisi == 1) {
                            tmbh = "";
                            hps = "";
                            aksi1 = "display: none;";
                            aksi2 = "display: none;";
                            aksi3 = "display: none;";
                            aksi4 = "display: none;";
                        } else if (c[h].stts_pemeliharaan == 1) {
                            tmbh = "";
                            hps = "disabled";
                            aksi1 = "";
                            aksi2 = "display: none;";
                            aksi3 = "display: none;";
                            aksi4 = "";
                        } else if (c[h].stts_pemeliharaan == 2 && c[h].stts_approval == 1) {
                            tmbh = "disabled";
                            hps = "";
                            aksi1 = "display: none;";
                            aksi2 = "";
                            aksi3 = "display: none;";
                            aksi4 = "";
                        } else if (c[h].stts_pemeliharaan == 2 && c[h].stts_approval == 2 || c[h].stts_approval == 3) {
                            tmbh = "disabled";
                            hps = "";
                            aksi1 = "display: none;";
                            aksi2 = "";
                            aksi3 = "display: none;";
                            aksi4 = "display: none;";
                        } else if (c[h].stts_pemeliharaan == 3) {
                            aksi1 = "display: none;";
                            aksi2 = "display: none;";
                            aksi3 = "";
                            aksi4 = "display: none;";
                        } else {
                            tmbh = "";
                            hps = "";
                            aksi1 = "";
                            aksi2 = "display: none;";
                            aksi3 = "display: none;";
                            aksi4 = "display: none;";
                        }


                        if (c[h].kondisi == 1) {
                            kondisi = "Baik";
                        } else if (c[h].kondisi == 2) {
                            kondisi = "Rusak Ringan";
                        } else if(c[h].kondisi == 3) {
                            kondisi = "Rusak Berat";
                        } else {
                            kondisi = "-";
                        }

                        pgdn +=
                            '<tr>' + 
                                '<td>' + (h + 1) + '</td>' +
                                '<td>' + c[h].kd_brg + '</td>' +
                                '<td style="text-align: center;">' + c[h].no_reg + '</td>' +
                                '<td>' + c[h].nm_brg + '</td>' +
                                '<td>' + c[h].merk_type + '</td>' +
                                '<td>' + kondisi + '</td>' +
                                '<td style="text-align: right;">' + harga + '</td>' +
                                '<td style="text-align: right;">' + c[h].umr_ekonomis + '</td>' +
                                '<td style="text-align: right;">' + nli_sisa + '</td>' +
                                '<td style="text-align: right;">' + nilai_buku_bln + '</td>' +
                                '<td style="text-align: center;">' +
                                '<button style="'+ aksi3 +'" type="button" class="btn btn-xs btn-success" disabled><i class="fa fa-check"></i> Selesai</button>' +
                                '<button style="'+ aksi2 +'" type="button" class="btn btn-xs btn-warning" disabled><i class="fa fa-circle-o"></i> Prosses</button> &nbsp;' +
                                '<button style="'+ aksi1 +'" type="submit" '+ tmbh +' title="Pemeliharaan" onclick="tmbh_pelihara(\'' + c[h].id_brg + '\')" class="btn btn-xs btn-info"><i class="fa fa-cog"></i> Pemeliharaan</button> &nbsp;' +
                                '<button style="'+ aksi4 +'" type="submit" '+ hps +' title="Batalkan" onclick="hps_pelihara(\'' + c[h].id_brg + '\')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Batalkan</button></td>' +
                            '</tr>';
                            // id_brg = c[h].id_brg;
                            // $('#insert_keterangan').html(id_brg);
                    }
                    $('#tmpl_data').html(pgdn);
                    
                }
            });
        }

        function tmbh_pelihara(id_brg) {
            swal({
                text: "Masukan Keterangan Pemeliharaan",
                content: "input",
                button: {
                    text: "Simpan",
                    closeModal: true,
                },
            })
            .then((ket) => {
                if (!ket) {
                    return swal("Harus Menginputkan Keterangan!");
                } else {
                    $.ajax({
                        type: "POST",
                        data: {id_brg:id_brg, ket:ket},
                        url: "<?= base_url('Pemeliharaan_aset/int_pelihara') ?>",
                        async: false,
                        dataType: "JSON",
                        success: function(tmbh) {
                            // console.log(tmbh);
                            if (tmbh == 2) {
                                swal({
                                    text: "Aset masih dalam kondisi baik",
                                    type: "warning",
                                    timer: 5000,
                                    showConfirmButton: false
                                });
                            } else {
                                swal({
                                    text: "Data Berhasil diajukan!",
                                    type: "success",
                                    timer: 5000,
                                    showConfirmButton: false
                                });
                                location.reload();
                            }
                        }
                    });
                }
            });
        }

        function hps_pelihara(id_brg) {
            $.ajax({
                type: "POST",
                data: "id_brg=" + id_brg,
                url: "<?= base_url('Pemeliharaan_aset/hps_pelihara') ?>",
                async: false,
                dataType: "JSON",
                success: function(a) {
                    location.reload();
                }
            });
        }

        function tampil_internal() {
            $.ajax({
                type: "GET",
                url: "<?= base_url('Pemeliharaan_aset/get_pelihara_aset_internal') ?>",
                async: false,
                dataType: "JSON",
                success: function(c) {
                    var pgdn = "";
                    for (h = 0; h < c.length; h++) {
                        var bilangan = c[h].nli_sisa;
                            
                        var	reverse = bilangan.toString().split('').reverse().join(''),
                            ribuan 	= reverse.match(/\d{1,3}/g);
                            ribuan	= ribuan.join('.').split('').reverse().join('');

                        if (c[h].stts_approval == 4) {
                            tmbh = "disabled";
                            stts = "success";
                        } else {
                            tmbh = "";
                            stts = "info";
                        }

                        if (c[h].kondisi_brg == 2) {
                            kondisi_brg = "Rusak Ringan";
                        } else if(c[h].kondisi_brg == 3){
                            kondisi_brg = "Rusak Berat";
                        } else {
                            kondisi_brg = "baik";
                        }

                        pgdn +=
                            '<tr>' + 
                                '<td>' + (h + 1) + '</td>' +
                                '<td>' + c[h].kd_brg + '</td>' +
                                '<td style="text-align: center;">' + c[h].no_reg + '</td>' +
                                '<td>' + c[h].nm_brg + '</td>' +
                                '<td>' + c[h].merk_type + '</td>' +
                                '<td>' + kondisi_brg + '</td>' +
                                '<td style="text-align: right;">' + c[h].umr_ekonomis + '</td>' +
                                '<td style="text-align: right;">' + ribuan + '</td>' +
                                '<td style="text-align: center;"><button type="submit" '+ tmbh +' title="Selesai" onclick="pelihara_selesai_in(\'' + c[h].id_pemeliharaan + '\', \'' + c[h].id_brg + '\')" class="btn btn-xs btn-'+stts+'"><i class="fa fa-cog"></i> Selesai</button></td>' +
                            '</tr>';
                    }
                    $('#dt_internal').html(pgdn);
                    
                }
            });
        }

        function tampil_external() {
            $.ajax({
                type: "GET",
                url: "<?= base_url('Pemeliharaan_aset/get_pelihara_aset_external') ?>",
                async: false,
                dataType: "JSON",
                success: function(c) {
                    var pgdn = "";
                    for (h = 0; h < c.length; h++) {
                        var bilangan = c[h].nli_sisa;
                            
                        var	reverse = bilangan.toString().split('').reverse().join(''),
                            ribuan 	= reverse.match(/\d{1,3}/g);
                            ribuan	= ribuan.join('.').split('').reverse().join('');

                        if (c[h].stts_approval == 5) {
                            tmbh = "disabled";
                            stts = "success";
                        } else {
                            tmbh = "";
                            stts = "info";
                        }

                        if (c[h].kondisi_brg == 2) {
                            kondisi_brg = "Rusak Ringan";
                        } else if(c[h].kondisi_brg == 3){
                            kondisi_brg = "Rusak Berat";
                        } else {
                            kondisi_brg = "baik";
                        }

                        pgdn +=
                            '<tr>' + 
                                '<td>' + (h + 1) + '</td>' +
                                '<td>' + c[h].kd_brg + '</td>' +
                                '<td style="text-align: center;">' + c[h].no_reg + '</td>' +
                                '<td>' + c[h].nm_brg + '</td>' +
                                '<td>' + c[h].merk_type + '</td>' +
                                '<td>' + kondisi_brg + '</td>' +
                                '<td style="text-align: right;">' + c[h].umr_ekonomis + '</td>' +
                                '<td style="text-align: right;">' + ribuan + '</td>' +
                                '<td style="text-align: right;"><button type="submit" '+ tmbh +' title="Selesai" onclick="pelihara_selesai_ex(\'' + c[h].id_pemeliharaan + '\', \'' + c[h].id_brg + '\')" class="btn btn-sm btn-'+stts+'"><i class="fa fa-cog"></i> Selesai</button></td>' +
                            '</tr>';
                    }
                    $('#dt_external').html(pgdn);
                    
                }
            });
        }

        function pelihara_selesai_in(id_pemeliharaan, id_brg) {
            $.ajax({
                type: "POST",
                data: { id_pemeliharaan:id_pemeliharaan, id_brg:id_brg },
                url: "<?= base_url('Pemeliharaan_aset/pelihara_selesai_in') ?>",
                async: false,
                dataType: "JSON",
                success: function(a) {
                    location.reload();
                }
            });
        }

        function pelihara_selesai_ex(id_pemeliharaan, id_brg) {
            $.ajax({
                type: "POST",
                data: { id_pemeliharaan:id_pemeliharaan, id_brg:id_brg },
                url: "<?= base_url('Pemeliharaan_aset/pelihara_selesai_ex') ?>",
                async: false,
                dataType: "JSON",
                success: function(a) {
                    location.reload();
                }
            });
        }
    </script>

</body>

</html>