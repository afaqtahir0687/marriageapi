@extends('admin.layouts.app')
@section('title')
<title>Payment Plan List | Marriage</title>
@endsection
@section('css')
    <link href="{{ url('assets/plugins/DataTables/datatables.min.css') }}" rel="stylesheet">
    <style>
        table
        {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
        
    </style>
@endsection
@section('content')
<div class="row">
    <div class="col">
        @if(count($errors) > 0)
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
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Payment Plan List</h5>
                <table id="zero-conf" class="display table table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Contacts text</th>
                            <th>Video Cal</th>
                            <th>upload photos</th>
                            <th>Short Bio</th>
                            <th>Basic Profile</th>
                            <th>Month Discount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paymentPlan as $plan )
                        <tr>
                            <td>
                                {{ $plan->contacts_text ?? '' }}
                            </td>
                            <td>
                                {{ $plan->video_call ?? '' }}
                            </td>
                            <td>
                                {{ $plan->upload_photo ?? '' }}
                            </td>
                            <td>
                                {{ $plan->short_bio ?? '' }}
                            </td>
                            <td>
                                {{ $plan->basic_profile ?? '' }}
                            </td>
                            <td>
                                {{ $plan->month_discount ?? '' }}
                            </td>
                            <td>
                                <a href="{{url('payment_plans/add')}}" class="btn btn-success btn-lg">Add</a>
                                <a href="{{url('payment_plans/edit', $plan->id)}}" class="btn btn-warning btn-lg">Edit</a>
                                <a href="{{url('payment_plans/delete', $plan->id)}}" class="btn btn-info btn-lg">Delete</a>
                            </td>
                            
                           
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
    <script src="{{ url('assets/plugins/DataTables/datatables.min.js') }}"></script>
    <script src="{{ url('assets/js/pages/datatables.js') }}"></script>
@endsection
