<form action="{{route('hostel.order.save')}}">
    <div class="table__datas">
       <table class="table table-bordered">
            <thead>
               <tr>
                   <th>SL</th>
                   <th>Item</th>
                   <th>Qty</th>
                   <th>Total</th>
                   <th>Action</th>
               </tr>
            </thead>
            <tbody id="cartTable" class="">
                @foreach (Cart::instance('shopping')->content() as $value)
                  <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$value->name}}</td>
                    <td>{{$value->qty}}</td>
                    <td>{{$value->subtotal}}</td>
                     <td><span  class="btn btn-xs btn-info edit_hostel_order mb-1" data-id="{{$value->id}}"><i class="fa-regular fa-pen-to-square "></i></span> <span class="btn btn-xs btn-danger cart__remove" data-id="{{$value->id}}" ><i class="fa-solid fa-trash-can"></i></span></td>
                  </tr>
                @endforeach
            </tbody>
        </table>
      </div>
    
      <div class="date__comment">
          <div class="col-sm-6">
              <label for="deliveryTime">Delivery Time *</label>
              <select id="deliveryTime" name="deliveryTime" class="form-select form-control" required>
                    <option value="">-Please select-</option>
              </select>
          </div>
          <div class="col-sm-6">
              <label for="comment">Comment</label>
              <input type="text" name="comment" class="form-control" id="comment" placeholder="Enter comment">
          </div>
      </div>
      <div class="btn__section">
          <a class="order__clear">Clear All</a>
          <button type="submit" onclick="order_hostel_place()">Order Now</button>
      </div>

    {{--<p class="no__data__found">No data found !</p>--}}
</form>
<script>
  function cart_content_edit(id) {
      $.ajax({
           type: "GET",
           url: "{{route('hostel.order.cart_content_edit')}}",
           dataType: "html",
           data: {id:id},
            success: function (cartinfo) {
            $(".cartForm").html(cartinfo);
           },
        });
     }

      $(".edit_hostel_order").on("click", function (e) {
          var id = $(this).data('id');
          if (id) {
           $.ajax({
            cache: "false",
            type: "GET",
            data: { id: id},
            url: "{{route('hostel.order.cart_edit')}}",
            dataType: "json",
            success: function (cartinfo) {   
            console.log(cartinfo);         
                return cart_content_edit(id);
            },
           });
          }
         });
</script>