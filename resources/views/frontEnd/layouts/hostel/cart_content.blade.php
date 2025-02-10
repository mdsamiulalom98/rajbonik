@foreach($cartinfo as $key=>$value)
  <tr>
    <td>{{$loop->iteration}}</td>
    <td>{{$value->name}}</td>
    <td>{{$value->qty}}</td>
    <td>{{$value->subtotal}}</td>
    <td><span class="btn btn-xs btn-info edit_hostel_order mb-1" data-id="{{$value->id}}"><i class="fa-regular fa-pen-to-square"></i></span> <span class="btn btn-xs btn-danger cart__remove" data-id="{{$value->id}}" ><i class="fa-solid fa-trash-can"></i></span></td>
  </tr>
@endforeach
<script src="{{asset('public/frontEnd/')}}/js/jquery-3.7.1.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/libs/select2/js/select2.min.js"></script>
<script type="text/javascript">
 $(document).ready(function () {
  $(".select2").select2();
 });
</script>
<script>
    $('.close-modal-hostel').on('click',function(){
        $("#custom-modal").hide();
        $("#page-overlay").hide();
     });
</script>
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
      
      $(".cart__remove").on("click", function (e) {
          var id = $(this).data('id');
          if (id) {
           $.ajax({
            cache: "false",
            type: "GET",
            data: { id: id},
            url: "{{route('hostel.order.cart_remove')}}",
            dataType: "json",
            success: function (remove) {   
            console.log(remove);         
                return cart_content();
            },
           });
          }
         });

</script>
