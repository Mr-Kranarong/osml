<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PO-{{$po_id}}</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    {{-- <link rel="stylesheet" href="{{ url('css/custom.css') }}"> --}}
    <style>
    @font-face {
        font-family: 'THSarabunNew';
        font-style: normal;
        font-weight: normal;
        src: url("{{ public_path('fonts/THSarabunNew.ttf') }}") format('truetype');
    }
    @font-face {
        font-family: 'THSarabunNew';
        font-style: normal;
        font-weight: bold;
        src: url("{{ public_path('fonts/THSarabunNew Bold.ttf') }}") format('truetype');
    }
    @font-face {
        font-family: 'THSarabunNew';
        font-style: italic;
        font-weight: bold;
        src: url("{{ public_path('fonts/THSarabunNew BoldItalic.ttf') }}") format('truetype');
    }
    @font-face {
        font-family: 'THSarabunNew';
        font-style: italic;
        font-weight: normal;
        src: url("{{ public_path('fonts/THSarabunNew Italic.ttf') }}") format('truetype');
    }

    body{
        font-family: "THSarabunNew";
    }
    </style>
</head>
<body>
    <div class="container border">
        <div class="row">
            <div class="col">
                <h1 class="">{{$store_name->value}}</h1>
            </div>
            <div class="col text-right">
                <p class="m-0"><b>{{__('text.POID')}}</b> {{$po_id}}</p>
                <p class="m-0"><b>{{__('text.DateIssue')}}</b> <span class="text-nowrap">{{date('d-m-Y', strtotime($po_items[0]->created_at))}}</span></p>
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
                            <p class="m-0">{{$shipping_address}}</p>
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
                    <?php $x=0;$real=0; ?>
                    @foreach ($po_items as $item)
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
                   @if ($real > $po_items[0]->final_price)
                    <tr>
                        <td class="mein-font-14"></td>
                        <td class="mein-font-14" colspan="2">{{__('text.Promotion')}} & {{__('text.Coupon')}}</td>
                        <td class="mein-font-14 text-right">-{{ number_format(round($real-$po_items[0]->final_price,2), 2, '.', '') }}</td>
                        <td class="mein-font-14 text-right"><u>{{ $po_items[0]->final_price }}</u></td>
                    </tr>
                   @endif
                </tbody>
            </table>
        </div>
        <div class="row border">
            <div class="col text-right">
                <b>{{__('text.Contact')}}</b>
                <p class="m-0">{{$store_email->value}}</p>
                <p class="m-0">{{$store_telephone->value}}</p>
            </div>
        </div>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>