<div class="modal-view quick-product">
	<button class="close-modal">x</button>
    @foreach($data as $key=>$value)
    <div class="main__data__popup">
    	<div class="quick-product-img">
    		<img style="width: 75px; height:75px;" src="{{asset($value->image)}}" alt="">
    	</div>
        @if($value->size)
        <div class="sizes__section">
             <p class="name">{{$value->size}}</p>
        </div>
        @else
         <div class="sizes__section">
             <p class="name">N/A</p>
        </div>
        @endif
    <div class="quick-product-content">
        <div class="product-details-cart popup__cart">
            <div class="qty-cart popup_cart">
                <div class="quantity popup_qty">
                    <span class="minus minus_quick">-</span>
                    <input type="text" name="qty" value="1"/>
                    <span class="plus plus_quick">+</span>
                </div>
                <div class="addcartbuttonQuick add-to-cart cart_store" 
                    data-size="{{$value->size}}" 
                    data-color="{{$value->color}}" 
                    data-id="{{$value->product_id}}">
                    add to cart
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
</div>
<script src="https://websolutionit.com/rajbonik/public/frontEnd/js/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function () {
    function mini_cart() {
            $.ajax({
                type: "GET",
                url: "{{ route('mini.cart') }}",
                dataType: "html",
                success: function(data) {
                    $(".mini-cart-wrapper").html(data);
                },
            });
        }
    function cart_count() {
        $.ajax({
            type: "GET",
            url: "{{route('cart.count')}}",
            success: function (data) {
                if (data) {
                    $("#cart-qty").html(data);
                } else {
                    $("#cart-qty").empty();
                }
            },
        });
    }
    
    function mobile_cart() {
        $.ajax({
            type: "GET",
            url: "{{route('mobile.cart.count')}}",
            success: function (data) {
                if (data) {
                    $(".mobilecart-qty").html(data);
                } else {
                    $(".mobilecart-qty").empty();
                }
            },
        });
    }

    $('.addcartbuttonQuick').on('click',function(e){
        console.log('check',e);
    var id = $(this).data("id");
    var size = $(this).data("size");
    var qty = $(this).closest(".popup_cart").find('input[name="qty"]').val();

    if (id) {
        $.ajax({
            type: "GET",
            url: "{{url('add-to-cart-quick')}}",
            data: {id,size,qty},
            success: function (response) {
                if (response) {
                    toastr.success("Success", "Product added to cart successfully");
                    cart_count();
                    mobile_cart() + mini_cart();
                     $("#custom-modal").hide();
                     $("#loading").hide();
                     $("#page-overlay").hide();
                }
            },
            error: function (xhr) {
                toastr.error("Error", "Failed to add product to cart.");
            }
        });
    }
});

});
</script>
<script>
	$('.close-modal').on('click',function(){
        $("#custom-modal").hide();
        $("#page-overlay").hide();
     });
</script>
<script>
    $(document).ready(function() {
        $('.minus').click(function () {
            var $input = $(this).parent().find('input');
            var count = parseInt($input.val()) - 1;
            count = count < 1 ? 1 : count;
            $input.val(count);
            $input.change();
            return false;
        });
        $('.plus').click(function () {
            var $input = $(this).parent().find('input');
            $input.val(parseInt($input.val()) + 1);
            $input.change();
            return false;
        });
    });
</script>
<!-- cart js start -->