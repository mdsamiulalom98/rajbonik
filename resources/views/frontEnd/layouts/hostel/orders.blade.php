@extends('frontEnd.layouts.hostel.master')
@section('title', 'All Orders')
@section('content')
<div class="container-fluid margin-top-section">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Orders</h4>
                <div class="page-title-right">
                    <a class="btn btn-danger rounded-pill order_create"><i class="fe-shopping-cart"></i> Add New</a>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row order_page">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-8">
                            <!-- <ul class="action2-btn">
                                <li>
                                    <a data-bs-toggle="modal" data-bs-target="" class="btn rounded-pill btn-success"><i class="fe-plus"></i>Download</a>
                                </li>
                            </ul> -->
                        </div>
                        <div class="col-sm-4">
                            <form class="custom_form mb-3">
                                <div class="form-group">
                                    <input type="text" name="keyword" placeholder="Search" />
                                    <button class="btn rounded-pill btn-info">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-striped w-100">
                            <thead>
                                <tr>
                                    <th style="width:2%;">
                                        <div class="form-check">
                                            <label class="form-check-label"><input type="checkbox" class="form-check-input checkall" value="" /></label>
                                        </div>
                                    </th>
                                    <th style="width: 10%;">Order Number</th>
                                    <th style="width: 15%;">Invoice</th>
                                    <th style="width: 15%;">Delivary Time</th>
                                    <th style="width: 20%;">Name</th>
                                    <th style="width: 5%;">Total</th>
                                    <th style="width: 8%;">Status</th>
                                    <th style="width: 15%;">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($show_data as $key => $value)
                                <tr>
                                    <td><input type="checkbox" class="checkbox" value="{{ $value->id }}" /></td>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $value->invoice_id }}<br />
                                        {{ $value->customer_ip }} <br />
                                        @if ($value->order_type == 'digital')
                                        <i class="fa fa-gift"></i>
                                        @endif
                                    </td>
                                    <td>
                                        {{ date('d-m-Y', strtotime($value->updated_at)) }}<br />
                                        {{ date('h:i:s a', strtotime($value->updated_at)) }}
                                    </td>
                                    <td>
                                        <strong>{{ $value->shipping ? $value->shipping->name : '' }}</strong>
                                        <p>{{ $value->shipping ? $value->shipping->address : '' }}</p>
                                    </td>
                                    
                                    <td>৳{{ $value->amount }}</td>
                                    <td>{{ $value->status ? $value->status->name : '' }}</td>
                                     <td>
                                        <div class="button-list custom-btn-list">
                                            <a data-id="{{$value->id}}" class="invoice_data" title="Invoice"><i class="fe-eye"></i></a>

                                            <a class="order_edit_hostel" data-id="{{$value->id}}" title="Edit"><i class="fe-edit"></i></a>

                                            <form method="post" action="{{ route('hostel.order.destroy') }}" class="d-inline">
                                                @csrf
                                                <input type="hidden" value="{{ $value->id }}" name="id" />
                                                <button type="submit" title="Delete" class="delete-confirm"><i class="fe-trash-2"></i></button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="custom-paginate">
                        {{ $show_data->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
    </div>
</div>

<!-- pathao courier  End-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $(".checkall").on('change', function() {
            $(".checkbox").prop('checked', $(this).is(":checked"));
        });
</script>
@endsection
