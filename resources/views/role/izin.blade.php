@extends('layouts.app')


@Section('content')

<div class="row" style="display: block;">
    @role('super_admin')
    <div class="col-md-6 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Prizinan <small> Input izin </small> </h2>
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
                <form method=post action="{{ isset($izin_e)?route('update_izin'):route('tambah_izin') }}" class="form-horizontal form-label-left">
                    @csrf
                    @if(isset($izin_e))
                    <input type=hidden name=id value="{{ $izin_e->id }}">
                    @endif
                    <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Izin</label>
                        <div class="col-md-9 col-sm-9 ">
                            <input name=izin type="text" class="form-control" value="{{ isset($izin_e->name)?$izin_e->name:'' }}" placeholder=Izin>
                            {!! $errors->first('izin') !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 ">Jenis </label>
                        <div class="col-md-9 col-sm-9 ">
                            <input name=jenis type="text" class="form-control" value="{{ isset($izin_e->guard_name)?$izin_e->guard_name:'' }}" placeholder=Jenis>
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
    </div>
    @endrole

    <div class="col-md-6 col-sm-12  ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Prizinan <small>Izin</small></h2>
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
                            @if (isset($izin))

                            @foreach ($izin as $r)


                            <tr class="even pointer">
                                <td class="a-center ">
                                    <div class="icheckbox_flat-green" style="position: relative;"><input type="checkbox" class="flat" name="table_records" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px none; opacity: 0;"></ins></div>
                                </td>
                                <td class=" ">{{ $r->name }}</td>
                                <td class=" ">{{ $r->guard_name }} </td>
                                <td class="a-right a-right "></td>
                                <td class=" last">
                                    @role('super_admin')
                                    <a href="{{ route('edit_izin',$r->id) }}"><i class="fa fa-pencil"></i></a> &nbsp&nbsp
                                    <a href="{{ route('hapus_izin',$r->id) }}"><i class="fa fa-close"></i></a>
                                    @endrole
                                </td>
                            </tr>

                            @endforeach
                            @endif

                        </tbody>
                    </table>
                    {{ $izin->links() }}
                </div>


            </div>
        </div>
    </div>
</div>

<div class="clearfix"></div>

@endsection





@push('script')
<script>

</script>
@endpush
