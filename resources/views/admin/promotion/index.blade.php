@extends('layouts.app')

@section('content')

@auth
{{-- ========================================== ADMIN ========================================== --}}
@if (Auth::user()->hasAccess())
<div class="container">
    <div class="row justify-content-center">
        {{-- ORDER --}}
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    {{__('text.Promotion')}}
                </div>
                <div class="card-body">
                    <?php $lastID = ''; ?>
                    @foreach ($promotion as $item)
                    @if ($item->id == $lastID)
                        @continue
                    @endif
                    <div class="container border rounded shadow p-2">
                        <div class="row">
                            <div class="col">
                                <p class="h3">{{$item->name}}</p>
                            </div>
                            <div class="col text-right">
                                <p class="h6">{{__('text.ExpireDate')}}:</p>
                                <p class="h6">{{$item->expire_date}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col d-flex flex-row flex-wrap">
                                <?php $promotion_product = App\Product::where('promotion_id',"$item->id")->get(); ?>
                                <?php $fullprice = 0; ?>
                                @foreach ($promotion_product as $product)
                                <div class="mein-card text-center" style="background-color: black">
                                    @if ($product->image_img)
                                    <div id="product-{{$product->id}}" class="carousel slide" data-ride="carousel">
                                        <ol class="carousel-indicators">
                                            {{-- loop to image number
                                            --}}
                                            <?php $images = explode('|', $product->image_img);$x=0; ?>
                                                @foreach ($images as $image)
                                                <li data-target="#product-{{$product->id}}" data-slide-to="{{$x}}"></li>
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
                                                    data-gall="product-{{$product->id}}-images">
                                                    <img class="d-block cart-gallery-image rounded" src="{{ url('images/'.$image) }}">
                                                </a>
                                            </div>
                                            @endforeach
                                        </div>
                                        <a class="carousel-control-prev" href="#product-{{$product->id}}" role="button" data-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="carousel-control-next" href="#product-{{$product->id}}" role="button" data-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                    @endif
                                    @if (!$product->image_img) <img class="d-block home-product-gallery-image"> @endif
                                    <a href="{{route('product.view',$product)}}" style="color:white;font-size:14px;">{{$product->name}}</a>
                                </div>
                                <?php $fullprice = $fullprice+$product->price; ?>
                                @endforeach
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center">
                                <p class="product-details-text m-0"><u>{{__('text.TotalPrice')}}</u></p>
                                <p class="bold h2 m-0">{{$item->discounted_price}}</p>
                                <span>{{((round((100-(($item->discounted_price/$fullprice)*100)),2))*-1)}}% </span>
                                <s class="m-0 p-0">({{$fullprice}})</s>
                            </div>
                            <div class="col">
                                <form class="col p-0 mein-height-100" action="{{ route('product.promotion.delete', ['promotion_id' => $item->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-primary mein-width-100 mein-height-100">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <?php $lastID = $item->id; ?>
                    <br>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif
{{-- ========================================== ADMIN ========================================== --}}
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
