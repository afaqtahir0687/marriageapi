@extends('admin.layouts.app')
@section('title')
<title>Dashboard | Marriage</title>
@endsection
@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                    <h5 class="card-title">Change Password</h5>
                    @if($errors->any())
                        {!! implode('', $errors->all('<div style="color:red" class="mb-2">:message</div>')) !!}
                    @endif
                    @if(Session::get('error') && Session::get('error') != null)
                        <div style="color:red" class="mb-3">{{ Session::get('error') }}</div>
                        @php
                            Session::put('error', null)
                        @endphp
                    @endif
                    @if(Session::get('success') && Session::get('success') != null)
                        <div style="color:green" class="mb-3">{{ Session::get('success') }}</div>
                        @php
                            Session::put('success', null)
                        @endphp
                    @endif
                    </div>
                </div>
                <form class="form" action="{{ url('profile/update/password') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            
                            <div class="mb-3">
                                <label for="" class="form-label">Email</label>
                                <input type="text" class="form-control" disabled value="{{ Session::get('user')->email ?? '' }} ">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Current Password">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Current Password">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" placeholder="Current Password">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6"> <button type="submit" class="btn btn-primary text-center">Update</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
