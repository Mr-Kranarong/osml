@extends('layouts.app')

@section('content')

@auth
{{-- ========================================== USER ========================================== --}}
<div class="container">
    <div class="row justify-content-center">
        {{-- ORDER --}}
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    {{__('text.Wishlist')}} ({{$total}})
                </div>
                <div class="card-body">
                    @foreach ($favorite as $f)
                    <div class="container border rounded shadow">
                        <div class="row">
                            <div class="col-md-2">
                                @if ($f->image_img)
                                <div id="product-{{$f->id}}" class="carousel slide" data-ride="carousel">
                                    <ol class="carousel-indicators">
                                        {{-- loop to image number
                                        --}}
                                        <?php $images = explode('|', $f->image_img);$x=0; ?>
                                            @foreach ($images as $image)
                                            <li data-target="#product-{{$f->id}}" data-slide-to="{{$x}}"></li>
                                            <?php $x++ ?>
                                            @endforeach
                                    </ol>
                                    <div class="carousel-inner">
                                        {{-- loop to image number
                                        --}}
                                        <?php $y = 0; ?>
                                        @foreach ($images as $image)
                                        <div class="carousel-item @if($y==0) active @endif"> <?php $y = 1; ?>
                                            <a href="{{ url('images/'.$image) }}" class="venobox"
                                                data-gall="product-{{$f->id}}-images">
                                                <img class="d-block cart-gallery-image rounded" src="{{ url('images/'.$image) }}">
                                            </a>
                                        </div>
                                        @endforeach
                                    </div>
                                    <a class="carousel-control-prev" href="#product-{{$f->id}}" role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#product-{{$f->id}}" role="button" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <p class="m-0"><b>{{__('text.ProductName')}}:</b> <a href="{{route('product.view',$f->id)}}" class="text-nowrap">{{$f->name}}</a></p>
                                <p class="m-0"><b>{{__('text.Price')}}:</b> {{$f->price}}</p>
                                <p class="m-0"><b>{{__('text.InStocks')}}:</b> {{$f->stock_amount}}</p>
                            </div>
                            <div class="col-md-4 text-right">
                                <p class="m-0"><b>{{__('text.Date')}}:</b> <span class="text-nowrap">{{date('d-m-Y', strtotime($f->fdate))}}</span></p>
                                <form id="form-delete-{{$f->fid}}" class="p-0" action="{{ route('favorite.delete', ['fid' => $f->fid]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-primary mein-width-100" form="form-delete-{{$f->fid}}">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <br>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
{{-- ========================================== USER ========================================== --}}
@endauth

@endsection

@section('script')
<script>
    $(document).ready(function() {
            $('.venobox').venobox({
                infinigall: true,
            });
        });
</script>
@endsection
