<style>
@media only screen and (min-width: 320px) and (max-width: 767px) {
    .modal-view-hostel {
        padding: 10px 8px;
        height: 85vh !important;
        overflow-y: auto !important;
    }
}
</style>
<div class="modal-view-hostel quick-product">
   <section>
       <div class="container">
           <div class="row">
               <div class="col-sm-12">
                   <div class="invoice__modal">
                       <div class="invoice_modal_header">
                           <div class="modal__name"><h2>Order Details</h2></div>
                           <div class="close-modals"><h2><i class="fa-solid fa-xmark close-modal-hostel"></i></h2></div>
                       </div>
                   </div>
               </div>
               <div class="col-sm-5">
                   <div class="order__information">
                       <div class="order__title">
                           <h3>Order Information</h3>
                           @foreach($data as $key=>$value)
                           <table class="table table-bordered">
                            <tbody>
                               <tr>
                                   <th>Order Number</th>
                                   <td>{{$value->invoice_id}}</td>
                               </tr>
                               <tr>
                                   <th>Customer</th>
                                   <td>{{$value->customer->name??''}}</td>
                               </tr>
                               <tr>
                                   <th>Shipping Address</th>
                                   <td>{{$value->shipping->address}}</td>
                               </tr>
                               <tr>
                                   <th>Contact</th>
                                   <td>{{$value->shipping->phone}}</td>
                               </tr>
                                <tr>
                                   <th>Created At</th>
                                   <td>{{$value->created_at}}</td>
                               </tr>
                                <tr>
                                   <th>Updated At</th>
                                   <td>{{$value->updated_at}}</td>
                               </tr>
                               
                            </tbody>
                           </table>
                           @endforeach
                       </div>
                   </div>
               </div>
               <div class="col-sm-7">
                   <div class="order__information">
                       <div class="order__title">
                           <h3>Product Details</h3>
                         
                           <table class="table table-bordered">
                            <tbody>
                               <tr>
                                   <th>SL</th>
                                   <th>Item</th>
                                   <th>Qty</th>
                                   <th>Total</th>
                               </tr>
                                @foreach($data as $key => $details)
                                    @foreach($details->orderdetails as $key => $value)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $value->product_name }} <br>
                                                @if ($value->product_size)
                                                    <small>Size: {{ $value->product_size }}</small>
                                                    @endif @if ($value->product_color)
                                                        <small>Color: {{ $value->product_color }}</small>
                                                    @endif
                                            </td>
                                            <td>{{ $value->qty }}</td>
                                            <td>{{ $value->sale_price }}</td>
                                        </tr>
                                    @endforeach
                                     <tr>
                                        <th colspan="3" class="text-end px-4">Net Amount</th>
                                        <td>{{$details->amount}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                           </table>
                         
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </section>
</div>
<script src="{{asset('public/frontEnd/')}}/js/jquery-3.7.1.min.js"></script>
<script>
	$('.close-modal-hostel').on('click',function(){
        $("#custom-modal").hide();
        $("#page-overlay").hide();
     });
</script>

<!-- cart js start -->