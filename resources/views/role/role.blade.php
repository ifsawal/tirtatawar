@extends('layouts.app')

@push('script_atas')

@endpush

@Section('content')

<div class="row" style="display: block;">
    <!--form roll-->
    @role(['admin','super_admin'])
    <div class="col-md-6 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Prizinan <small>Input Role</small></h2>
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
                <br>
                <form method=post action="{{ isset($role_e)?route('update_role'):route('tambah_role') }}" class="form-horizontal form-label-left">
                    @csrf
                    @if(isset($role_e))
                    <input type=hidden name=id value="{{ $role_e->id }}">
                    @endif
                    <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Role</label>
                        <div class="col-md-9 col-sm-9 ">
                            <input name=role type="text" class="form-control" value="{{ isset($role_e->name)?$role_e->name:'' }}" placeholder=Role>
                            {!! $errors->first('role') !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 ">Jenis </label>
                        <div class="col-md-9 col-sm-9 ">
                            <input name=jenis type="text" class="form-control" value="{{ isset($role_e->guard_name)?$role_e->guard_name:'' }}" placeholder=Jenis>
                            {!! $errors->first('jenis') !!}
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
            </div>
        </div>

        <!--daftar prizinan-->
        @if (isset($izin[0]->izin))
        <div class="x_panel">
            <div class="x_title">
                <h2>Prizinan <small>Daftar Izin</small></h2>
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

                    <!-- form penambahan izin-->
                    <form method=post action="{{ route('tambah_hakakses') }}" class="form-horizontal form-label-left">
                        @csrf
                        <input type=hidden name=id_role value="{{ $id }}">
                        <div class="form-group row ">
                            <div class="col-md-9 col-sm-9 ">
                                <label class="control-label ">Jenis izin</label>
                                <table class="table table-striped jambo_table bulk_action">
                                    <thead>
                                        <tr>
                                            <td>Checklist</td>
                                            <td>Jenis Perizinan</td>
                                            <td>Terpilih</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($permisi as $p)

                                        <tr>
                                            <td><input type="checkbox" name="permisi[]" value="{{ $p->id }}"></td>
                                            <td>{{ $p->name }}</td>
                                            @foreach ($izin[0]->izin as $i )
                                                @if ($p->id==$i->id)
                                                <td><i class="fa fa-check"></td>
                                                @endif
                                            
                                            @endforeach
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                                <button type="submit" class="btn btn-success">Simpan</button>
                            </div>
                        </div>



                    </form>
                    <table class="table table-striped jambo_table bulk_action" id="tabeldaftar">
                        <thead>
                            <tr>
                                <td>Izin terdaftar </td>
                                <td>Jenis Izin </td>
                                <td>Aksi </td>
                            </tr>

                        </thead>
                        <tbody>

                            @foreach ($izin[0]->izin as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->guard_name }}</td>
                                @if ($item->name<>"super_admin")
                                    <td>
                                        <a href="{{ route('hapus_hakakses',[base64_encode($item->id),base64_encode($item['pivot']->role_id)]) }}"><i class="fa fa-close"></i></a>
                                    </td>
                                    @endif
                            </tr>
                            @endforeach


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endrole


    <!--data roll-->
    <div class="col-md-6 col-sm-6  ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Perizinan <small>Role</small></h2>
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
                    <table class="table table-striped jambo_table bulk_action">
                        <tbody>
                            @if (isset($role))
                            @foreach ($role as $r)
                            <tr class="even pointer">
                                <td class="a-center ">
                                    <div class="icheckbox_flat-green" style="position: relative;"></ins></div>
                                </td>
                                <td class=" ">{{ $r->name }}</td>
                                <td class=" ">{{ $r->guard_name }} </td>
                                <td class="a-right a-right ">
                                    <a href="{{ route('hakakses_role',$r->id) }}" class="aksi_role" data-id="{{ $r->id }}"><i class="fa fa-reorder"></i></a> &nbsp&nbsp
                                    @if ($r->name<>"super_admin")
                                        <a href="{{ route('edit_role',$r->id) }}"><i class="fa fa-pencil"></i></a>
                                        &nbsp&nbsp
                                        @endif

                                        @if ($r->name<>"super_admin")
                                            <a href="{{ route('hapus_role',$r->id) }}"><i class="fa fa-close"></i></a>
                                            @endif
                                </td>
                            </tr>
                            @endforeach
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>


<div class="clearfix"></div>

@endsection





@push('script_bawah')

<script>





</script>

@endpush
