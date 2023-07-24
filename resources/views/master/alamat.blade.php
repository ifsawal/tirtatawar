@extends('layouts.app')

@section('content')


<div class="col-md-6 col-sm-6  ">
    <div class="x_panel">
        <div class="x_title">
            <h2><i class="fa fa-bars"></i> Master <small>Alamat</small></h2>
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

            <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="provinsi-tab" data-toggle="tab" href="#provinsi" role="tab" aria-controls="provinsi" aria-selected="true">Provinsi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="kabupaten-tab" data-toggle="tab" href="#kabupaten" role="tab" aria-controls="kabupaten" aria-selected="false">Kabupaten</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="kecamatan-tab" data-toggle="tab" href="#kecamatan" role="tab" aria-controls="kecamatan" aria-selected="false">Kecamatan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="desa-tab" data-toggle="tab" href="#desa" role="tab" aria-controls="desa" aria-selected="false">desa</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="provinsi" role="tabpanel" aria-labelledby="provinsi-tab">
                    Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher
                    synth. Cosby sweater eu banh mi, qui irure terr.
                </div>
                <div class="tab-pane fade" id="kabupaten" role="tabpanel" aria-labelledby="kabupaten-tab">
                    Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo
                    booth letterpress, commodo enim craft beer mlkshk aliquip
                </div>
                <div class="tab-pane fade" id="kecamatan" role="tabpanel" aria-labelledby="kecamatan-tab">
                    xxFood truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo
                    booth letterpress, commodo enim craft beer mlkshk
                </div>
                <div class="tab-pane fade" id="desa" role="tabpanel" aria-labelledby="desa-tab">
                    xxFood truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo
                    booth letterpress, commodo enim craft beer mlkshk
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
