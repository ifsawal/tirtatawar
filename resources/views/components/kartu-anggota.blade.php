<div class="col-xl-3 col-lg-4 col-md-6 mt-4">
    <div class="card bg-transparent border-0 text-center">
        <div class="card-img">

            @if ($anggota->image != '')
                <img loading="lazy" decoding="async" src="{{ asset('storage/' . $anggota->image) }}" alt="Scarlet Pena"
                    class="rounded w-100" width="300" height="332">
            @endif

            <ul class="card-social list-inline">
                @if ($anggota->fb !='')
                <li class="list-inline-item"><a class="facebook" target="_blank" href="{{$anggota->fb}}"><i class="fab fa-facebook"></i></a>
                </li>
                @endif
                @if ($anggota->ig !='')
                <li class="list-inline-item"><a class="twitter" target="_blank" href="{{$anggota->ig}}"><i class="fab fa-twitter"></i></a>
                </li>
                @endif
                @if ($anggota->tiktok !='')
                <li class="list-inline-item"><a class="instagram" target="_blank" href="{{$anggota->tiktok}}"><i class="fab fa-instagram"></i></a>
                </li>
                @endif
            </ul>
        </div>
        <div class="card-body">
            <h3>{{ $anggota->nama }}</h3>
            <p>{{ $anggota->jabatan }}</p>
        </div>
    </div>
</div>
