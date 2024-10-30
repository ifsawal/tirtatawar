<div>


    <div class="section">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-10">
              <div class="mb-5">
                <h2 class="mb-4" style="line-height:1.5">{{$artikel->judul}}</h2>
                <span> <span class="mx-2">{{date('d-m-Y',strtotime($artikel->created_at))}} /</span></span>
                <p class="list-inline-item">Category : <a href="#!" class="ml-1"> {{$artikel->nama}} </a>
                </p>
                {{-- <p class="list-inline-item">Tags : <a href="#!" class="ml-1">Photo </a> ,<a href="#!"
                    class="ml-1">Image </a>
                </p> --}}
                <p class="list-inline-item">Sumber : {{$artikel->nama_admin}} 
                </p>
              </div>
              <div class="mb-5 text-center">
                <div class="post-slider rounded overflow-hidden">
                  <img loading="lazy" decoding="async" src="{{ asset('storage/' . $artikel->image) }}" alt="Post Thumbnail">
                  
                </div>
              </div>
              <div class="content">
                             
                {!! $artikel->konten !!}
              </div>
            </div>
          </div>
        </div>
      </div>

</div>
