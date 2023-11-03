@extends('admin.layouts.app')
@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center">Payment Plan</h5>
                    <form action="{{ url('payment_plans/store') }}" method="POST"  enctype="multipart/form-data">
                        @csrf
                        <div class="row mt-3 mb-4">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                @if(Session::has('success'))
                                    <div class="alert alert-success text-center">
                                        {{Session::get('success')}}
                                    </div>
                                @endif    
                                <div>
                                <lablel for="title">Contacts Text</label>
                                <input type="number" name="contacts_text" class="form-control" required style="border-radius: 40px; height: 50px;">
                                </div>
                                <div class="mt-4">
                                <lablel for="title">Video Cal</label>
                                <input type="number" name="video_cal" class="form-control" required style="border-radius: 40px; height: 50px;">
                                </div>
                                <div class="mt-4">
                                <lablel for="title">Upload Photos</label>
                                <input type="number" name="upload_photo" class="form-control" required style="border-radius: 40px; height: 50px;">
                                </div>
                                <div class="mt-4">
                                <lablel for="title">Short Bio</label>
                                <input type="number" name="short_bio" class="form-control" required style="border-radius: 40px; height: 50px;">
                                </div>
                                <div class="mt-4">
                                <lablel for="title">Basic Profile</label>
                                <input type="number" name="basic_profile" class="form-control" required style="border-radius: 40px; height: 50px;">
                                </div>
                                <div class="mt-4">
                                <lablel for="title">Month Discount</label>
                                <input type="number" name="month_discount" class="form-control" required style="border-radius: 40px; height: 50px;">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3  mb-4">
                            <div class="col-md-4"></div>
                            <div class="col-md-4 text-center">
                            <button type="submit" class="btn btn-success btn-lg">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop