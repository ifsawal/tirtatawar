@extends('layouts.app')

@push('script_atas')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="row" style="display: block;">

        <div class="col-md-6 col-sm-12  ">
            <div class="x_panel">
                <div class="x_title">
                    <h2>PDAM <small>Form Daftar User / Pengguna</small></h2>
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



                    <form method=post action="{{ isset($user_e) ? route('update_user') : route('tambah_user') }}"
                        class="form-horizontal form-label-left">
                        @csrf
                        @if (isset($user_e))
                            <input type=hidden name=id value="{{ encrypt($user_e->id) }}">
                        @endif
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3 ">Nama pengguna</label>
                            <div class="col-md-9 col-sm-9 ">
                                <input name=user type="text" class="form-control" required
                                    value="{{ isset($user_e->nama) ? $user_e->nama : '' }}" placeholder="User">
                                {!! $errors->first('nama') !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3 ">Email</label>
                            <div class="col-md-9 col-sm-9 ">
                                <input name=email type="email" class="form-control has-feedback-left  "
                                    value="{{ isset($user_e->email) ? $user_e->email : '' }}" placeholder="Email" required>
                                {!! $errors->first('email') !!}
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3 ">Email</label>
                            <div class="col-md-9 col-sm-9 ">
                                <select class="select2_single form-control" tabindex="-1" name=pdam>
                                    @if (isset($pdam_terpilih))
                                        <option value="{{ encrypt($pdam_terpilih[0]->id) }}">{{ $pdam_terpilih[0]->pdam }}
                                        </option>
                                    @endif
                                    @foreach ($pdam as $p)
                                        <option value="{{ encrypt($p->id) }}">{{ $p->pdam }}</option>
                                    @endforeach
                                </select>
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

        <!-- daftar user -->
        @if (isset($user))
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
                                        <td>Nama</td>
                                        <td>Email</td>
                                        <td>Aksi</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user as $u)
                                        <tr>
                                            <td></td>
                                            <td>{{ $u->nama }}</td>
                                            <td>{{ $u->email }}</td>

                                            <td>
                                                <a href="{{ route('edit_user', encrypt($u->id)) }}"><i
                                                        class="fa fa-pencil"></i></a>
                                                &nbsp&nbsp
                                                <a href="{{ route('hapus_user', encrypt($u->id)) }}"><i
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
