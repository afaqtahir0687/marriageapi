@extends('admin.layouts.app')
@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title text-center">Payment Plan</h1>
                    <form action="{{ url('paymentplan/update',$paymentPlan->id) }}" method="POST" enctype="multipart/form-data">
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
                                    <label for="contact">Contact Text</label>
                                    <input type="number" name="contacts_text" class="form-control" required value="{{ $paymentPlan->contacts_text ?? '' }}"/>
                                </div>
                                <div>
                                    <label for="cal">Video Cal</label>
                                    <input type="number" name="video_call" class="form-control" required value="{{ $paymentPlan->video_call ?? '' }}"/>
                                </div>
                                <div>
                                    <label for="photo">Upload Photo</label>
                                    <input type="number" name="upload_photo" class="form-control" required value="{{ $paymentPlan->upload_photo ?? '' }}"/>
                                </div>
                                <div>
                                    <label for="bio">Short Bio</label>
                                    <input type="number" name="short_bio" class="form-control" required value="{{ $paymentPlan->short_bio ?? '' }}"/>
                                </div>
                                <div>
                                    <label for="profile">Basic Profile</label>
                                    <input type="number" name="basic_profile" class="form-control" required value="{{ $paymentPlan->basic_profile ?? '' }}"/>
                                </div>
                                <div>
                                    <label for="discount">Month Discount</label>
                                    <input type="number" name="month_discount" class="form-control" required value="{{ $paymentPlan->month_discount ?? '' }}"/>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 mb-4">
                            <div class="col-md-4"></div>
                            <div class="col-md-4 text-center">
                                <button type="submit" class="btn btn-success btn-lg">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop