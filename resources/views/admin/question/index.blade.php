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
                    {{__('text.Inquiries')}} ({{$total}})
                </div>
                <div class="card-body">
                    @foreach ($questions as $pq)
                    <div class="container border rounded shadow">
                        <div class="row">
                            <div class="col">
                                <p class="m-0"><b>{{__('text.ProductName')}}:</b> <a href="{{route('product.view',$pq->product_id)}}" class="text-nowrap">{{App\Product::where('id',$pq->product_id)->value('name')}}</a></p>
                                <p class="m-0">"{{$pq->question}}"</p>
                            </div>
                            <div class="col text-right">
                                <p class="m-0"><b>{{__('text.Date')}}:</b> <span class="text-nowrap">{{date('d-m-Y', strtotime($pq->created_at))}}</span></p>
                                <p class="m-0"><b>-</b> <span class="text-nowrap">{{App\User::where('id',$pq->user_id)->value('name')}}</span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <form id="form-answer-{{$pq->id}}" class="p-0" action="{{ route('question.answer', ['pq_id' => $pq->id]) }}" method="POST">
                                    @csrf
                                <textarea name="content" id="content" rows="2" class="form-control p-2 m-1"></textarea>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <button class="btn btn-primary col-6" form="form-answer-{{$pq->id}}">
                                {{__('text.SubmitAnswer')}} <i class="fa fa-check"></i>
                            </button>
                            <form id="form-delete-{{$pq->id}}" class="p-0 col-6" action="{{ route('question.delete', ['pq_id' => $pq->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-primary mein-width-100" form="form-delete-{{$pq->id}}">
                                    {{__('text.DeleteQuestion')}} <i class="fa fa-times"></i>
                                </button>
                            </form>
                        </div>
                    </div>
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

@endsection
