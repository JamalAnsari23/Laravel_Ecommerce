
   @extends('master')
@section("content")
<div class="container">

  <div class="row">
    <div class="col-sm-6">
      <img class="detail-img" src="{{$product['gallery']}}" alt="">
    </div>
    <div class="col-sm-6">
      <a href="/">Go Back</a>

      <h2>{{$product['name']}}</h2>
      <h3><mark>Price :</mark>  {{$product['price']}}</h3>
      <h4><mark>Details : </mark> {{$product['description']}}</h4>  
      <h4><mark>Category : </mark>  {{$product['category']}}</h4>
      <br><br>
      <form action="/add_to_cart" method="POST">
        @csrf
        <input type="text" name="product_id" value="{{$product['id']}}">
      <button class="btn btn-primary">Add to cart</button> 
        
      </form>
      <button class="btn btn-success">By Now</button> 


    </div>
  </div>
    
</div>
@endsection
