@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header row">
                        <div class="col-5">
                            {{ __('text.UserManagement') }} ({{ $total }})
                        </div>
                        <div class="col-7">
                            <form action="{{ route('user.search') }}" method="POST">
                                @csrf
                                <div class="input-group input-group-sm">
                                    <input name="SearchUser" type="text"
                                        class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7 ml-auto"
                                        id="SearchUser" placeholder="{{ __('text.SearchUser') }}"
                                        aria-describedby="SearchButton" value="{{ $query ?? '' }}">
                                    <div class="input-group-append" id="SearchButton">
                                        <button type="submit" class="btn btn-outline-secondary"><i class="fa fa-search"
                                                aria-hidden="true"></i></button></div>
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
                                        <th scope="col">{{ __('text.Name') }}</th>
                                        <th scope="col" class="text-center">{{ __('text.Email') }}</th>
                                        <th scope="col"></th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td class="tm-product-name">{{ $user->name }}</td>
                                            <td class="text-center">{{ $user->email }}</td>

                                            <td><a data-backdrop="static" data-keyboard="false" data-toggle="modal"
                                                    data-target="#update-modal-{{ $user->id }}"><i
                                                        class="fas fa-pencil-alt text-dark"></i></a></td>

                                            <td><a href="{{ route('user.delete', $user) }}"
                                                    onclick="event.preventDefault();document.getElementById('user-delete-{{ $user->id }}').submit()"><i
                                                        class="fas fa-trash-alt text-dark"></i></a></td>

                                            {{-- HIDDEN FORM FOR DELETE BUTTON
                                            --}}
                                            <form id="user-delete-{{ $user->id }}"
                                                action="{{ route('user.delete', $user) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('delete')
                                            </form>

                                            {{-- HIDDEN MODAL FOR UPDATE BUTTON
                                            --}}
                                            <div class="modal fade" id="update-modal-{{ $user->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="update-modal-{{ $user->id }}-label"
                                                aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="update-modal-{{ $user->id }}-label">
                                                                {{ __('text.EditingUser') }}: {{ $user->id }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close"
                                                                onclick="document.getElementById('form-{{ $user->id }}').reset()">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('user.update', $user) }}" method="POST"
                                                            id="form-{{ $user->id }}">
                                                            @csrf
                                                            @method('put')

                                                            <div class="modal-body">

                                                                <div class="form-group row">
                                                                    <label for="name"
                                                                        class="col-md-4 col-form-label text-md-right">{{ __('text.Name') }}</label>

                                                                    <div class="col-md-6">
                                                                        <input id="name" type="text"
                                                                            class="form-control @error('name') is-invalid @enderror"
                                                                            name="name" value="{{ $user->name }}" required
                                                                            autocomplete="name" autofocus>

                                                                        @error('name')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label for="address"
                                                                        class="col-md-4 col-form-label text-md-right">{{ __('text.Address') }}</label>

                                                                    <div class="col-md-6">
                                                                        <input id="address" type="text"
                                                                            class="form-control @error('address') is-invalid @enderror"
                                                                            name="address" value="{{ $user->address }}"
                                                                            autocomplete="address" autofocus>

                                                                        @error('address')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label for="phone"
                                                                        class="col-md-4 col-form-label text-md-right">{{ __('text.Phone') }}</label>

                                                                    <div class="col-md-6">
                                                                        <input id="phone" type="text"
                                                                            class="form-control @error('phone') is-invalid @enderror"
                                                                            name="phone" value="{{ $user->phone }}"
                                                                            autocomplete="phone" autofocus>

                                                                        @error('phone')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label for="email"
                                                                        class="col-md-4 col-form-label text-md-right">{{ __('text.Email') }}</label>

                                                                    <div class="col-md-6">
                                                                        <input id="email" type="email"
                                                                            class="form-control @error('email') is-invalid @enderror"
                                                                            name="email" value="{{ $user->email }}" required
                                                                            autocomplete="email" autofocus>

                                                                        @error('email')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label for="access"
                                                                        class="col-md-4 col-form-label text-md-right">{{ __('text.AdminAccess') }}</label>

                                                                    <div class="col-md-6">
                                                                        <input type="hidden" name="access" value="0">
                                                                        <input id="access" type="checkbox"
                                                                            class="form-control @error('access') is-invalid @enderror"
                                                                            name="access" value="1"
                                                                            {{ $user->access == 1 ? 'checked=\'\'' : '' }}"
                                                                            autofocus>

                                                                        @error('access')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal"
                                                                    onclick="reset()">{{ __('text.CancelChanges') }}</button>
                                                                <button type="submit"
                                                                    class="btn btn-primary">{{ __('text.SaveChanges') }}</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
