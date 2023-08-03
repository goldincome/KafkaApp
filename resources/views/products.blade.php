@extends('layouts.app')

@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                
                <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                  @foreach($products as $product)
                  <form method="POST" action="{{route('cart.add')}}">
                         @csrf
                         
                          <input type="hidden" name="product_id" value="{{$product->id}}"/>
                          <div class="group relative">
                            <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80">
                              <img src="https://tailwindui.com/img/ecommerce-images/product-page-01-related-product-01.jpg" alt="Front of men&#039;s Basic Tee in black." class="h-full w-full object-cover object-center lg:h-full lg:w-full">
                            </div>
                            <div class="mt-4 flex justify-between">
                              <div>
                                <h3 class="text-sm text-gray-700">

                                    {{$product->name}}
                                  
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">${{$product->price}} <br/>
                                  <input type="text" name="qty" value="1"/> <br/>
                                  <button class="py-2 px-4 bg-blue-500 hover:bg-blue-600 text-white rounded-lg">
                                    Add To Cart
                                  </button>
                                </p>
                              </div>
                              
                            </div>
                          </div>
                        
                      </form>
                    @endforeach

                   

                     

                     
                    <!-- More products... -->
                  
                </div>
            </div>
        </div>
    </div>
@endsection
