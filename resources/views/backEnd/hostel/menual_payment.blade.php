@extends('backEnd.layouts.master')
@section('title', 'Rider Menual Payment')
@section('css')
    <link href="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-select-bs5/css/select.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                     <div class="page-title-right">
                        <a data-bs-toggle="modal" data-bs-target="#payment_modal" class="btn btn-primary">Payment</a>
                    </div>
                    <h4 class="page-title"> <a href="{{route('riders.profile',['id'=>request()->get('id')])}}"> <i class="fa fa-arrow-left"></i> Back</a> Rider Menual Payment</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive ">
                            <table class="table parcel-table">
                                <thead>
                                    <tr>
                                        <th>Parcel ID</th>
                                        <th>Invoice</th>
                                        <th>Recepient</th>
                                        <th>Delivery Status</th>
                                        <th>Amount</th>
                                        <th>Charge</th>
                                        <th>Commission</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($parcels as $key=>$value)
                                    <tr>
                                       <td>{{$value->parcel_id}}</td>
                                        <td>{{$value->merchant_invoice??'N/A'}}</td>
                                        <td>{{$value->name}}</td>
                                        <td><span class="@if($value->status == 1) warning @else success @endif"> {{$value->parcelstatus?$value->parcelstatus->name:''}}</span></td>
                                        <td>৳ {{$value->cod}}</td>
                                        <td>৳ {{$value->delivery_charge+$value->cod_charge}}</td>
                                        <td>৳ {{$value->rider_commission}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5"></td>
                                        <td><strong>Total</strong></td>
                                        <td><strong>৳ {{$parcels->sum('rider_commission')}}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="custom-paginate">
                            {{ $parcels->links('pagination::bootstrap-4') }}
                        </div>
                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>


<!-- Modal -->
    <div class="modal fade" id="payment_modal" tabindex="-1" aria-labelledby="payment_modalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="payment_modalLabel">Menual Payment</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{ route('riders.menual_payment.paid') }}" method="POST"
                enctype="multipart/form-data" data-parsley-validate="">
                @csrf
                <input type="hidden" value="{{$rider->id}}" name="rider_id">
                <div class="row">
                    <div class="col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="payment_method" class="mb-2">Payment Method</label>
                            <select class="form-control" name="payment_method" id="payment_method">
                                <option value="">Select..</option>
                                <option value="bank" data-method="{{$riderpay->account_number ?? ''}}">Bank</option>
                                <option value="bkash" data-method="{{$riderpay->bkash ?? ''}}">Bkash</option>
                                <option value="nagad" data-method="{{$riderpay->nagad ?? ''}}">Nagad</option>
                                <option value="rocket" data-method="{{$riderpay->rocket ?? ''}}">Rocket</option>
                                <option value="cash">Cash</option>
                            </select>
                        </div>
                        <p class="text-success mt-1" id="method_msg"></p>
                        <div class="form-group mt-2">
                            <label for="trx_id" class="mb-2">Trx Id</label>
                            <input type="text" name="trx_id" id="trx_id"  class="form-control">
                        </div>
                        <div class="form-group mt-2">
                            <label for="admin_note" class="mb-2">Sender Info & Note</label>
                            <textarea name="admin_note" class="form-control" id="admin_note" required></textarea>
                        </div>
                        <!-- form group -->
                        <div class="form-group mt-3">
                            <button class="btn btn-primary" id="submitBtn">Submit</button>
                        </div>
                    </div>
                    <!-- col end -->
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
@endsection
@section('script')
    <!-- third party js -->
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js">
    </script>
    <script
        src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js">
    </script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js">
    </script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js">
    </script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/pdfmake/build/pdfmake.min.js"></script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/pdfmake/build/vfs_fonts.js"></script>
    <script src="{{ asset('/public/backEnd/') }}/assets/js/pages/datatables.init.js"></script>
    <!-- third party js ends -->
    <script>
        $('#payment_method').on('change',function(){
            var method = $(this).val();
            var number = $(this).find('option:selected').data('method');
            if(number !=''){
                if(method == 'cash'){
                    $('#method_msg').text('You can collect your payment from office');
                }else{
                    $('#method_msg').text('Your '+ method + ' number is: ' + number);
                }
                $('#submitBtn').prop('disabled', false);
            }else{
                $('#method_msg').text('No '+ method + ' number is added to your acount!');
                $('#submitBtn').prop('disabled', true);
            }
        })
    </script>
@endsection
