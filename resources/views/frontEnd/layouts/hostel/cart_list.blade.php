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
        <tbody >
            @foreach (Cart::instance('shopping')->content() as $value)
              <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$value->name}}</td>
                <td>{{$value->qty}}</td>
                <td>{{$value->subtotal}}</td>
                 <td><span  class="btn btn-xs btn-info edit_hostel_order mb-1" data-id="{{$value->id}}"><i class="fa-regular fa-pen-to-square "></i></span> <span class="btn btn-xs btn-danger cart__remove" data-id="{{$value->rowId}}" ><i class="fa-solid fa-trash-can"></i></span></td>
              </tr>
            @endforeach
        </tbody>
    </table>
  </div>


<script>
    $(".cart__remove").on("click", function (e) {
      var id = $(this).data('id');
      if (id) {
       $.ajax({
        cache: "false",
        type: "GET",
        data: { id: id},
        url: "{{route('hostel.order.cart_remove')}}",
        dataType: "json",
        success: function (response) {  
            return cart_content();
        },
       });
      }
     });

    function cart_content() {
      $.ajax({
       type: "GET",
       url: "{{route('hostel.order.cart_content')}}",
       dataType: "html",
       success: function (response) {
        $(".cartTable").html(response);
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
            success: function (response) {       
                $(".cartForm").html(response);
             },
           });
          }
        });

</script>