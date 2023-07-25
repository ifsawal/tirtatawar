@extends('layouts.app')

@push('script_atas')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="col-md-6 col-sm-6  ">
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa fa-bars"></i> Master <small>Alamat</small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                            aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">Settings 1</a>
                            <a class="dropdown-item" href="#">Settings 2</a>
                        </div>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">

                <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="provinsi-tab" data-toggle="tab" href="#provinsi" role="tab"
                            aria-controls="provinsi" aria-selected="true">Provinsi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="kabupaten-tab" data-toggle="tab" href="#kabupaten" role="tab"
                            aria-controls="kabupaten" aria-selected="false">Kabupaten</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="kecamatan-tab" data-toggle="tab" href="#kecamatan" role="tab"
                            aria-controls="kecamatan" aria-selected="false">Kecamatan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="desa-tab" data-toggle="tab" href="#desa" role="tab"
                            aria-controls="desa" aria-selected="false">desa</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="provinsi" role="tabpanel" aria-labelledby="provinsi-tab">

                        <!--provinsi-->
                        <div class="form-group row">
                            <label class="col-form-label col-md-3 col-sm-3 ">Provinsi baru</label>
                            <div class="col-md-9 col-sm-9 ">
                                <input type="text" class="form-control" id="name_prov" placeholder="Nama provinsi">
                                <button type="button" class="btn btn-info btn-sm mt-2" id=sim_prov>Simpan</button>
                            </div>
                        </div>

                        <select class="select2_single form-control" tabindex="-1" id="sel_provinsi">
                            @foreach ($provinsi as $p)
                                <option value="{{ encrypt($p->id) }}">{{ $p->provinsi }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-info btn-sm mt-2 w-100" id=hapus_prov>Hapus</button>


                    </div>
                    <div class="tab-pane fade" id="kabupaten" role="tabpanel" aria-labelledby="kabupaten-tab">
                        <!--kabupaten-->
                        <div class="form-group row">
                            <label class="col-form-label col-md-3 col-sm-3 ">Kabupaten baru</label>
                            <div class="col-md-9 col-sm-9 ">
                                <input type="text" class="form-control" id="name_kab" placeholder="Kabupaten">
                                <button type="button" class="btn btn-info btn-sm mt-2" id=sim_kab>Simpan</button>
                            </div>
                        </div>

                        <select class="select2_single form-control" tabindex="-1" id="sel_kab">
                        </select>
                        <button type="button" class="btn btn-info btn-sm mt-2 w-100" id=hapus_kab>Hapus</button>
                    </div>

                    <!--kecamatan-->
                    <div class="tab-pane fade" id="kecamatan" role="tabpanel" aria-labelledby="kecamatan-tab">
                        <div class="form-group row">
                            <label class="col-form-label col-md-3 col-sm-3 ">Kecamatan baru</label>
                            <div class="col-md-9 col-sm-9 ">
                                <input type="text" class="form-control" id="name_kec" placeholder="Kecamatan">
                                <button type="button" class="btn btn-info btn-sm mt-2" id=sim_kec>Simpan</button>
                            </div>
                        </div>

                        <select class="select2_single form-control" tabindex="-1" id="sel_kec">
                        </select>
                        <button type="button" class="btn btn-info btn-sm mt-2 w-100" id=hapus_kec>Hapus</button>
                    </div>

                    <!--desa-->
                    <div class="tab-pane fade" id="desa" role="tabpanel" aria-labelledby="desa-tab">
                        <div class="form-group row">
                            <label class="col-form-label col-md-3 col-sm-3 ">Desa baru</label>
                            <div class="col-md-9 col-sm-9 ">
                                <input type="text" class="form-control" id="name_desa" placeholder="Desa">
                                <button type="button" class="btn btn-info btn-sm mt-2" id=sim_desa>Simpan</button>
                            </div>
                        </div>

                        <select class="select2_single form-control" tabindex="-1" id="sel_desa">
                        </select>
                        <button type="button" class="btn btn-info btn-sm mt-2 w-100" id=hapus_desa>Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script_bawah')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // desa
        $("#hapus_desa").click(function() {
            var data = $('#sel_desa').val()
            $.ajax({
                type: 'delete',
                url: "{{ route('hapus_desa') }}",
                data: {
                    id: data
                },
                cache: false,
                success: function(data) {
                    if (data == "Sukses") {
                        $("#sel_desa option").remove();
                    }
                }
            });
        });

        $("#sim_desa").click(function() {
            var data = $('#name_desa').val();
            var id_kec = $('#sel_kec').val();

            $.ajax({
                type: 'POST',
                url: "{{ route('simpan_desa') }}",
                data: {
                    nama: data,
                    id_kec: id_kec
                },
                cache: false,
                success: function(data) {
                    if (data == "Sukses") {
                        $("#sel_desa option").remove();
                    }
                }
            });
        });

        $("#sel_desa").click(function() {
            var opsi_saat_ini_desa = $('#sel_desa option').length;
            if (opsi_saat_ini_desa == 0) {
                AmbilDesa()
            }
        })

        function AmbilDesa() {
            var data = $('#sel_kec').val()
            $.ajax({
                type: 'post',
                url: "{{ route('get_desa') }}",
                data: {
                    id: data
                },
                cache: false,
                success: function(response) {
                    var len = 0;
                    if (response != null) {
                        len = response.length;
                    }
                    if (len > 0) {
                        // Read data and create <option >
                        for (var i = 0; i < len; i++) {
                            var id = response[i].id;
                            var desa = response[i].desa;
                            var option = "<option value='" + id + "'>" + desa +
                                "</option>";
                            $("#sel_desa").append(option);
                        }
                    }
                }
            });

        }
        //kecamatan

        $("#hapus_kec").click(function() {
            var data = $('#sel_kec').val()
            $.ajax({
                type: 'delete',
                url: "{{ route('hapus_kecamatan') }}",
                data: {
                    id: data
                },
                cache: false,
                success: function(data) {
                    if (data == "Sukses") {
                        $("#sel_kec option").remove();
                    }
                }
            });
        });


        $("#sim_kec").click(function() {
            var data = $('#name_kec').val();
            var id_kab = $('#sel_kab').val();

            $.ajax({
                type: 'POST',
                url: "{{ route('simpan_kecamatan') }}",
                data: {
                    nama: data,
                    id_kab: id_kab
                },
                cache: false,
                success: function(data) {
                    if (data == "Sukses") {
                        $("#sel_kec option").remove();
                    }
                }
            });
        });


        $("#sel_kec").click(function() {
            var opsi_saat_ini_kec = $('#sel_kec option').length;
            if (opsi_saat_ini_kec == 0) {
                AmbilKecamatan()
            }
        })

        function AmbilKecamatan() {
            var data = $('#sel_kab').val()
            $.ajax({
                type: 'post',
                url: "{{ route('get_kecamatan') }}",
                data: {
                    id: data
                },
                cache: false,
                success: function(response) {
                    var len = 0;
                    if (response != null) {
                        len = response.length;
                    }
                    if (len > 0) {
                        // Read data and create <option >
                        for (var i = 0; i < len; i++) {
                            var id = response[i].id;
                            var kecamatan = response[i].kecamatan;
                            var option = "<option value='" + id + "'>" + kecamatan +
                                "</option>";
                            $("#sel_kec").append(option);
                        }
                    }
                }
            });

        }

        //kabupaten
        $("#hapus_kab").click(function() {
            var data = $('#sel_kab').val()
            $.ajax({
                type: 'delete',
                url: "{{ route('hapus_kabupaten') }}",
                data: {
                    id: data
                },
                cache: false,
                success: function(data) {
                    if (data == "Sukses") {
                        $("#sel_kab option").remove();
                    }
                }
            });
        });

        $("#sim_kab").click(function() {
            var data = $('#name_kab').val();
            var id_pro = $('#sel_provinsi').val();

            $.ajax({
                type: 'POST',
                url: "{{ route('simpan_kabupaten') }}",
                data: {
                    nama: data,
                    id_pro: id_pro
                },
                cache: false,
                success: function(data) {
                    if (data == "Sukses") {
                        var opsi_saat_ini = $('#sel_kab option').length;
                        $("#sel_kab option").remove();
                    }
                }
            });
        });


        $("#sel_kab").click(function() {
            var opsi_saat_ini = $('#sel_kab option').length;
            if (opsi_saat_ini == 0) {
                AmbilKabupaten()
            }
        })

        function AmbilKabupaten() {
            var data = $('#sel_provinsi').val()
            $.ajax({
                type: 'post',
                url: "{{ route('get_kabupaten') }}",
                data: {
                    id: data
                },
                cache: false,
                success: function(response) {
                    var len = 0;
                    if (response != null) {
                        len = response.length;
                    }
                    if (len > 0) {
                        // Read data and create <option >
                        for (var i = 0; i < len; i++) {
                            var id = response[i].id;
                            var kabupaten = response[i].kabupaten;
                            var option = "<option value='" + id + "'>" + kabupaten +
                                "</option>";
                            $("#sel_kab").append(option);
                        }
                    }
                }
            });

        }

        //provinsi
        $("#sim_prov").click(function() {
            var data = $('#name_prov').val()
            $.ajax({
                type: 'POST',
                url: "{{ route('simpan_provinsi') }}",
                data: {
                    nama: data
                },
                cache: false,
                success: function(data) {
                    if (data == "Sukses") {
                        location.reload();
                    }
                }
            });
        });


        $("#hapus_prov").click(function() {
            var data = $('#sel_provinsi').val()
            $.ajax({
                type: 'delete',
                url: "{{ route('hapus_provinsi') }}",
                data: {
                    id: data
                },
                cache: false,
                success: function(data) {
                    if (data == "Sukses") {
                        location.reload();
                    }
                }
            });
        });
    </script>
@endpush
