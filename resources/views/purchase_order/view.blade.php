@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header row">
                        <div class="col-6">
                            {{ __('text.PODetails') }}
                        </div>
                        <div class="col-6 text-right">
                            <a href="{{route('po.export2pdf', ['po_id' => $po_id])}}">{{ $po_id }}</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover table-striped tm-table-striped-even">
                                <colgroup>
                                    <col span="1" style="width: 10%;"><!-- NO -->
                                    <col span="1" style="width: 70%;"><!-- NAME -->
                                    <col span="1" style="width: 20%;"><!-- AMOUNT -->
                                 </colgroup>
                                <thead>
                                    <tr class="tm-bg-gray">
                                        <th scope="col"></th>
                                        <th scope="col">{{__('text.ProductName')}}</th>
                                        <th scope="col" class="text-center">{{__('text.Amount')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $x = 0; ?>
                                    @foreach ($po_items as $item)
                                    <?php $x++; ?>
                                    <tr>
                                        <td class="tm-product-name">{{$x}} </td>
                                        <td class="">{{$item->name}} </td>
                                        <td class="text-center">{{$item->amount}} </td>
                                    </tr>
                                    @endforeach

                                    <tr>
                                        <td></td>
                                        <td colspan="2" class="text-right mr-3">{{__('text.TotalFinal')}} {{$po_items[0]->final_price}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col">
                                @if ($po_items[0]->user_id != null)
                                    <?php $u = App\User::first('id',$po_items[0]->user_id); ?>
                                    <p>{{ $u->name }}</p>
                                @endif
                            </div>
                            <div class="col"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection
