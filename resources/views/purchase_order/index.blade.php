@extends('layouts.app')

@section('content')

@auth
{{-- ========================================== ADMIN ========================================== --}}
@if (Auth::user()->hasAccess())
<div class="container">
    <div class="row justify-content-center">
        {{-- ORDER --}}
        <div class="col-md-8">

        </div>
    </div>
</div>
@endif
{{-- ========================================== ADMIN ========================================== --}}
{{-- ========================================== USER ========================================== --}}
@if (!Auth::user()->hasAccess())
<div class="container">
    <div class="row justify-content-center">
        {{-- ORDER --}}
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    {{__('text.Orders')}} ({{$total}})
                </div>
                <div class="card-body">
                    <?php $lastID = ''; ?>
                    @foreach ($po as $purchase_item)
                    @if ($purchase_item->purchase_id == $lastID)
                        @continue
                    @endif
                        <div class="mein-collapsible">
                            <div class="row">
                                <div class="col-6">
                                    <span class="mr-auto">{{$purchase_item->created_at}}</span>
                                </div>
                                <div class="col-6 text-right">
                                    @if ($purchase_item->processed_status == 1)
                                        <span class="ml-auto outline-success text-success">-[ {{__('text.Processed')}} ]-</span>
                                    @elseif ($purchase_item->processed_status == 0)
                                        <span class="ml-auto outline-warning text-warning">-[ {{__('text.Processing')}} ]-</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="mein-content">
                            <div class="row">
                                <div class="col-md-6 col-xs-12 px-3">
                                    <br>
                                    <?php $purchase_product = App\Purchase_Order::leftJoin('product','product.id','=','purchase_order.product_id')->where('purchase_id',"$purchase_item->purchase_id")->get(); ?>
                                    @foreach ($purchase_product as $p_product)
                                        <p class="m-0">{{$p_product->name}} {{$p_product->amount}} {{__('text.Unit')}}</p>
                                    @endforeach
                                    <br>
                                </div>
                                <div class="col-md-6 col-xs-12 px-3">
                                    <br>
                                    <p class="m-0">{{ __('text.TotalPrice') }}: {{$purchase_item->final_price}}</p>
                                    <p class="m-0">{{ __('text.Receipt') }}: <a href="{{route('po.export2pdf', ['po_id' => $purchase_item->purchase_id])}}">{{ $purchase_item->purchase_id }}</a></p>
                                    <br>
                                </div>
                            </div>
                        </div>
                    <?php $lastID = $purchase_item->purchase_id; ?>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif
{{-- ========================================== USER ========================================== --}}
@endauth

@endsection

@section('script')
<script>
    var coll = document.getElementsByClassName("mein-collapsible");
    var i;

    for (i = 0; i < coll.length; i++) {
        coll[i].addEventListener("click", function() {
            this.classList.toggle("mein-active");
            var content = this.nextElementSibling;
            if (content.style.maxHeight) {
                content.style.maxHeight = null;
            } else {
                content.style.maxHeight = content.scrollHeight + "px";
            }
        });
    }
</script>
@endsection
