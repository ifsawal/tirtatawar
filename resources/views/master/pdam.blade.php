@extends('layouts.app')

@push('script_atas')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="row" style="display: block;">

        <div class="col-md-6 col-sm-12  ">
            <div class="x_panel">
                <div class="x_title">
                    <h2>PDAM <small>Form Daftar PDAM</small></h2>
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
                    <p></p>


                    <form method=post action="{{ isset($pdam_e) ? route('update_pdam') : route('tambah_pdam') }}"
                        class="form-horizontal form-label-left">
                        @csrf
                        @if (isset($pdam_e))
                            <input type=hidden name=id value="{{ $pdam_e->id }}">
                        @endif
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3 ">Role</label>
                            <div class="col-md-9 col-sm-9 ">
                                <input name=pdam type="text" class="form-control"
                                    value="{{ isset($pdam_e->pdam) ? $pdam_e->pdam : '' }}" placeholder="Nama PDAM">
                                {!! $errors->first('pdam') !!}
                            </div>
                        </div>

                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-9 col-sm-9  offset-md-3">

                                <button type="reset" class="btn btn-primary">Reset</button>
                                <button type="submit" class="btn btn-success">Simpan</button>
                            </div>
                        </div>

                    </form>


                    <div class="table-responsive">
                    </div>
                </div>
            </div>
        </div>

        <!-- daftar pdam -->
        @if (isset($pdam))
            <div class="col-md-6 col-sm-12  ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>PDAM <small>Daftar PDAM</small></h2>
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

                        <p></p>

                        <div class="table-responsive">
                            <table class="table table-striped jambo_table bulk_action" id="tabel">
                                <thead>
                                    <tr>
                                        <td>No</td>
                                        <td>Nama PDAM</td>
                                        <td>Aksi</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pdam as $p)
                                        <tr>
                                            <td></td>
                                            <td>{{ $p->pdam }}</td>

                                            <td>
                                                <a href="{{ route('edit_pdam', encrypt($p->id)) }}"><i
                                                        class="fa fa-pencil"></i></a>
                                                &nbsp&nbsp
                                                <a href="{{ route('hapus_pdam', encrypt($p->id)) }}"><i
                                                        class="fa fa-close"></i></a>



                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                                <tbody>

                            </table>

                        </div>
                    </div>
                </div>
            </div>
        @endif



        <!-- jika hak akses belum di pilih user -->




    </div>

    <div class="clearfix"></div>
@endsection


@push('script_bawah')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    @endpush
