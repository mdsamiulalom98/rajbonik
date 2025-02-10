<form action="" method="POST">
@csrf
<label for="product_id" class="form-label">Select Product</label>
<select name="product_id" id="product_id" class="form-select form-control">
    @foreach($products as $value)
        @if($value->type == 1)
            <option value="{{ $value->id }}" data-type="{{ $value->type }}" >
                {{ $value->name }} - ৳{{ $value->new_price }}
                @if($value->old_price)
                    <del>৳{{ $value->old_price }}</del>
                @endif
                
            </option>
        @else
            @foreach($value->variables as $variable)
                <option value="{{ $value->id }}" 
                        data-size="{{ $variable->size }}" 
                        data-type="{{ $value->type }}" 
                        data-color="{{ $variable->color }}" 
                        data-stock="{{ $variable->stock }}">
                    {{ $value->name }} 
                    @if($variable->size) - {{ $variable->size }} @endif
                    @if($variable->color) - {{ $variable->color }} @endif
                    - ৳{{ $variable->new_price }}
                    @if($variable->old_price)
                        <del>৳{{ $variable->old_price }}</del>
                    @endif
                   
            @endforeach
        @endif
    @endforeach
</select>

<label for="qty" class="form-label">Qty</label>
<input type="number" name="qty" class="form-control" id="qty" required>

<label for="comment" class="form-label">Comment</label>
<input type="text" name="comment" class="form-control" id="comment">

<button class="mt-2 order_hostel" type="submit">Submit</button>
 <button class="mt-2 clear__btn" type="reset">Clear</button>
</form>
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
     function cart_content() {
      $.ajax({
       type: "GET",
       url: "{{route('hostel.order.cart_content')}}",
       dataType: "html",
       success: function (hostel_cart) {
        $("#cartTable").html(hostel_cart);
       },
      });
     }

     //order create code
    $(".order_hostel").on("click", function (e) {
    e.preventDefault();

        var selectedOption = $("#product_id").find(":selected");
        var id = selectedOption.val();
        var color = selectedOption.data("color");
        var size = selectedOption.data("size");
        var type = selectedOption.data("type");
        var qty = $("#qty").val();
        var comment = $("#comment").val();

        if (!id || !qty) {
            alert("Please select a product and enter quantity.");
            return;
        }
          if (id) {
           $.ajax({
            cache: "false",
            type: "GET",
            data: { id: id,color:color, size:size, type:type, qty:qty, comment:comment },
            url: "{{route('hostel.order.cart_add')}}",
            dataType: "json",
            success: function (hostel_cart) {
             return cart_content();
            },
           });
          }
         });
</script>
