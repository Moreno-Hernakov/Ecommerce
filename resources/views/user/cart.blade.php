@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row px-xl-5">
        <div class="col-lg-8 table-responsive mb-5">
            <table class="table table-light table-borderless table-hover text-center mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th colspan="2">Products</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @foreach ($data as $cart)
                        <tr id="">
                            <input type="hidden" name="id" value="{{$cart->id}}" id="cartId">
                            <td><img class="rounded-circle" src="/produk-image/{{$cart->produk->image}}" alt="" style="width: 50px;"></td>
                            <td class="align-middle">{{$cart->produk->nama}}</td>
                            <td class="align-middle" id="harga">Rp. {{$cart->produk->harga}}</td>
                            <td class="align-middle">
                                <div class="input-group quantity mx-auto" style="width: 100px;">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-primary btn-minus" onclick="qtyact({{$cart->produk->harga}}, {{$cart->produk->id}}, 'min')">
                                        <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <input id="jumlah{{$cart->produk->id}}" type="text" class="qty form-control form-control-sm bg-secondary border-0 text-center" value="{{$cart->jumlah}}">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-primary btn-plus" onclick="qtyact({{$cart->produk->harga}}, {{$cart->produk->id}}, 'plus')">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle totalcart" id="totalperitem{{$cart->produk->id}}">Rp. {{$cart->produk->harga * $cart->jumlah}}</td>
                            <td class="align-middle">
                                <button class="btn btn-sm btn-danger" onclick="rmvRow(this, {{$cart->id}})">
                                    <i class="fa fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-lg-4">
            <form class="mb-30" action="">
                <div class="input-group">
                    <input type="text" class="form-control border-0 p-4" placeholder="Coupon Code">
                    <div class="input-group-append">
                        <button class="btn btn-primary">Apply Coupon</button>
                    </div>
                </div>
            </form>
            <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Cart Summary</span></h5>
            <div class="bg-light p-30 mb-5">
                {{-- <div class="border-bottom pb-2">
                    <div class="d-flex justify-content-between mb-3">
                        <h6>Subtotal</h6>
                        <h6>$150</h6>
                    </div>
                    <div class="d-flex justify-content-between">
                        <h6 class="font-weight-medium">Shipping</h6>
                        <h6 class="font-weight-medium">$10</h6>
                    </div>
                </div> --}}
                <div>
                    <div class="d-flex justify-content-between ">
                        <h5>Total</h5>
                        <h5 id="totalcart"></h5>
                    </div>
                    <button onclick="addTrans()" class="btn btn-block btn-primary font-weight-bold my-3 py-3">Proceed To Checkout</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script> 
    $(document).ready(function() {
            totalCount()
    });
        
    
    function addTrans(){
        let inputId = $('input[name=id]')
        let inputQty = $('input.qty')
        let total = parseInt( $('#totalcart').text().replace('Rp. ','') )
        // console.log(total)
        let cartId = []
        let qty = []
        $.each( inputId, function( key, value ) {
            cartId.push(value.value)
        });
        $.each( inputQty, function( key, value ) {
            qty.push(value.value)
        });

        $.ajax({
            url: "/addtransaction",
            type:"POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{ qty, cartId, total },
            success: function(res){
                // if(res.success){
                //     alert(res.message)
                // }
                console.log(res)
            },
            error: function(error) {
                console.log(error)
            }
        });
        // console.log(cartId)
    }

    function rmvRow(btn, id){
        var row = btn.parentNode.parentNode
        row.parentNode.removeChild(row);
        $.ajax({
            url: "/deletecart/"+id,
            type:"DELETE",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res){
                // if(res.success){
                //     alert(res.message)
                // }
                console.log(res)
            },
            error: function(error) {
                console.log(error)
            }
        });
        totalCount()
    }

    function totalCount(){
        var arr = 0;
        $("table tr .totalcart").map(function() {
             arr += parseInt($(this).text().replace('Rp. ',''));
        });
        $("#totalcart").text('Rp. '+ arr)
    }

    function qtyact(harga, id, act) {
        
        let jumlah = $('#jumlah'+id).val() 
        // let harga = $('#harga').val()
        if(act == 'plus'){
            const qty = parseInt(jumlah)+1
            $('#totalperitem'+id).text("Rp. " + harga * qty)
            $.ajax({
                url: "/updateqty",
                type:"PUT",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{ qty, id },
                success: function(res){
                    // if(res.success){
                    //     alert(res.message)
                    // }
                    console.log(res)
                },
                error: function(error) {
                    console.log(error)
                }
            });
        }else{
            const qty = parseInt(jumlah)-1;
            $('#totalperitem'+id).text("Rp. "+(harga* qty))
            $.ajax({
                url: "/updateqty",
                type:"PUT",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{ qty, id },
                success: function(res){
                    // if(res.success){
                    //     alert(res.message)
                    // }
                    console.log(res)
                },
                error: function(error) {
                    console.log(error)
                }
            });
        }
        totalCount()
    }
</script>
@endsection

