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
                    {{__('text.PendingOrder')}} ({{$total}})
                </div>
                <div class="card-body">
                    <?php $lastID = ''; ?>
                    @foreach ($po as $purchase_item)
                    @if ($purchase_item->purchase_id == $lastID)
                        @continue
                    @endif
                    <div class="container border rounded shadow">
                        <div class="row">
                            <div class="col">
                                <h1 class="">{{$store_name->value}}</h1>
                            </div>
                            <div class="col text-right">
                                <p class="m-0"><b>{{__('text.POID')}}</b><a href="{{route('po.export2pdf', ['po_id' => $purchase_item->purchase_id])}}"> {{ $purchase_item->purchase_id }}</a></p>
                                <p class="m-0"><b>{{__('text.DateIssue')}}</b> <span class="text-nowrap">{{date('d-m-Y', strtotime($purchase_item->created_at))}}</span></p>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <colgroup>
                                    <col span="1" style="width: 50%;"><!-- VENDOR -->
                                    <col span="1" style="width: 50%;"><!-- SHIPPING -->
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <td class="mein-font-14 border">
                                            <b>{{__('text.VendorAddress')}}</b>
                                            <p class="m-0">{{$store_address->value}}</p>
                                        </td>
                                        <td class="mein-font-14 border">
                                            <b>{{__('text.ShippingAddress')}}</b>
                                            <p class="m-0">
                                                <?php
                                                    if ($purchase_item->user_id != null) {
                                                        $address = App\User::where('id',$purchase_item->user_id)->value('address');
                                                    }else {
                                                        $address = $purchase_item->guest_address;
                                                    }
                                                ?>
                                                {{$address}}
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <colgroup>
                                    <col span="1" style="width: 10%;"><!-- NO -->
                                    <col span="1" style="width: 35%;"><!-- PRODUCT NAME -->
                                    <col span="1" style="width: 15%;"><!-- AMOUNT -->
                                    <col span="1" style="width: 20%;"><!-- PRICE -->
                                    <col span="1" style="width: 20%;"><!-- TOTAL -->
                                </colgroup>
                                <thead>
                                    <tr class="tm-bg-gray">
                                        <th scope="col">#</th>
                                        <th scope="col">{{ __('text.ProductName') }}</th>
                                        <th scope="col" class="text-center">{{ __('text.Amount') }}</th>
                                        <th scope="col" class="text-right">{{ __('text.PriceEach') }}</th>
                                        <th scope="col" class="text-right">{{ __('text.TotalPrice') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $purchase_product = App\Purchase_Order::leftJoin('product','product.id','=','purchase_order.product_id')->where('purchase_id',"$purchase_item->purchase_id")->get(); ?>
                                    <?php $x=0;$real=0; ?>
                                    @foreach ($purchase_product as $item)
                                    <?php $x++; ?>
                                    <tr>
                                        <td class="mein-font-14">{{ $x }}</td>
                                        <td class="mein-font-14">{{ $item->name }}</td>
                                        <td class="mein-font-14 text-center">{{ $item->amount }}</td>
                                        <td class="mein-font-14 text-right">{{ $item->price }}</td>
                                        <td class="mein-font-14 text-right">{{ number_format(round($item->amount*$item->price,2), 2, '.', '') }}</td>
                                    </tr>
                                    <?php $real+=$item->amount*$item->price; ?>
                                    @endforeach
                                    <tr>
                                        <td class="mein-font-14"></td>
                                        <td class="mein-font-14" colspan="2">{{__('text.Promotion')}} & {{__('text.Coupon')}}</td>
                                        <td class="mein-font-14 text-right">-{{ number_format(round($real-$purchase_item->final_price,2), 2, '.', '') }}</td>
                                        <td class="mein-font-14 text-right"><u>{{ $purchase_item->final_price }}</u></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row border">
                            <div class="col text-left">
                                <div class="row mein-height-100">
                                    <form class="col p-0" action="{{ route('po.processed', ['po_id' => $purchase_item->purchase_id]) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-primary mein-width-100 mein-height-100">
                                            <i class="fa fa-check"></i>
                                        </button>
                                    </form>
                                    <form class="col p-0" action="{{ route('po.refunded', ['po_id' => $purchase_item->purchase_id]) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-primary mein-width-100 mein-height-100">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="col text-right">
                                <b>{{__('text.Contact')}}</b>
                                <p class="m-0">{{$store_email->value}}</p>
                                <p class="m-0">{{$store_telephone->value}}</p>
                            </div>
                        </div>
                    </div>
                    <?php $lastID = $purchase_item->purchase_id; ?>
                    <br>
                    @endforeach
                </div>
            </div>
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
                                        <span class="ml-auto bg-light outline-success text-success">-[ {{__('text.Processed')}} ]-</span>
                                    @elseif ($purchase_item->processed_status == 0)
                                        <span class="ml-auto bg-light outline-warning text-warning">-[ {{__('text.Processing')}} ]-</span>
                                    @elseif ($purchase_item->processed_status == 2)
                                    <span class="ml-auto bg-light outline-danger text-danger">-[ {{__('text.Refunded')}} ]-</span>
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
