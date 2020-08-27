@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header row">
                    <div class="col-5">
                        {{__('text.UserManagement')}} ({{$total}})
                    </div>
                    <div class="col-7">
                        <form action="/user" method="POST">
                            @csrf
                            <div class="input-group input-group-sm">
                            <input name="SearchUser" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7 ml-auto" id="SearchUser" placeholder="{{__('text.SearchUser')}}" aria-describedby="SearchButton" value="{{ $query ?? '' }}">
                                <div class="input-group-append" id="SearchButton">
                                    <button type="submit" class="btn btn-outline-secondary"><i class="fa fa-search" aria-hidden="true"></i></button></div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-striped tm-table-striped-even">
                            <colgroup>
                                <col span="1" style="width: 40%;"><!-- NAME -->
                                <col span="1" style="width: 40%;"><!-- Email -->
                                <col span="1" style="width: 10%;"><!-- UPDATE BUTTON -->
                                <col span="1" style="width: 10%;"><!-- DELETE BUTTON -->
                             </colgroup>
                            <thead>
                                <tr class="tm-bg-gray">
                                    <th scope="col">{{__('text.Name')}}</th>
                                    <th scope="col" class="text-center">{{__('text.Email')}}</th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                <tr>
                                    <td class="tm-product-name">{{$user->name}}</td>
                                    <td class="text-center">{{$user->email}}</td>
                                    <td><a href="{{$user->id}}"><i class="fas fa-pencil-alt text-dark"></i></a></td>
                                    <td><a href="{{$user->id}}"><i class="fas fa-trash-alt text-dark"></i></a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{$users->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection