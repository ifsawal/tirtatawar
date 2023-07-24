@extends('layouts.app')

@push('script_atas')

@endpush


@Section('content')

<div class="row" style="display: block;">


    <div class="col-md-6 col-sm-12  ">
        <div class="x_panel">
            <div class="x_title">
                <h2>User <small>Daftar User</small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
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
                                <td><a href="{{ route('detil_hakakses',$u->id) }}"><i class="fa fa-bars"></i></a></td>
                            </tr>
                            @endforeach
                        </tbody>

                        <tbody>




                        </tbody>
                    </table>

                </div>


            </div>
        </div>
    </div>
    <!-- detil hak akses -->
    @if (isset($user_role[0]))
    <div class="col-md-6 col-sm-6  ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Hak Akses <small>Detil</small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
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
                    Hak Akses :
                    <button type="button" class="btn btn-round btn-secondary">{{ $user_role[0]->role->name }}</button>

                </div>
            </div>
        </div>
    </div>
    @endif


    <!-- jika hak akses belum di pilih user -->
    @if (!isset($user_role[0]) && isset($id))
    <div class="col-md-6 col-sm-6  ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Hak Akses <small>Detil</small></h2>
                <div class="clearfix"></div>
            </div>


            <div class="x_content">
                <p></p>
                <form method=post action="{{ route('simpan_hakakses') }}">
                    @csrf
                    <input type=hidden name=id_user value="{{ $id }}">
                    <div class="table-responsive">
                        <select class="select2_single form-control" name=role>
                            @if (isset($role) && !empty($role))
                            @foreach ($role as $r)
                            <option value="{{ $r->id }}">{{ $r->name }}</option>
                            @endforeach

                            @endif
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3 container-fluid">Simpan</button>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>

<div class="clearfix"></div>



@endsection





@push('script_bawah')
<script>
    // let table = new DataTable('#tabel');

    // $('#tabel').DataTable();

</script>
@endpush
