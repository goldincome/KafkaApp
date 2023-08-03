
@extends('layouts.app')

@section('content')
        
<div class="max-w-md mx-auto mt-16 bg-white rounded-lg overflow-hidden md:max-w-lg border border-gray-400">
    <div class="px-4 py-2 border-b border-gray-200">
        <h2 class="font-semibold text-gray-800">Shopping Cart</h2>
    </div>
    <div class="flex flex-col divide-y divide-gray-200">
        @foreach($cartItems as $item)
            <div class="flex items-center py-4 px-6">
                <img class="w-16 h-16 object-cover rounded" src="https://dummyimage.com/100x100/F3F4F7/000000.jpg" alt="Product Image">
                <div class="ml-3">
                    <h3 class="text-gray-900 font-semibold">{{$item->name}}</h3>
                    <p class="text-gray-700 mt-1">${{$item->price}}</p>
                    <p class="text-gray-700 mt-1">Quantity: {{$item->quantity}}</p>
                </div>
              
                <button class="ml-auto py-2 px-4 bg-blue-500 hover:bg-blue-600 text-white rounded-lg">
                    <a href="{{route('cart.remove', $item->id)}}">
                        Remove
                    </a>
                </button>
               
            </div>
        @endforeach
       
    </div>
    <div class="flex items-center justify-between px-6 py-3 bg-gray-100">
        <h3 class="text-gray-900 font-semibold">Total: ${{$total}}</h3>
        <button class="py-2 px-4 bg-blue-500 hover:bg-blue-600 text-white rounded-lg">
            <a href="{{route('cart.checkout')}}">
            Checkout
            </a>
        </button>
    </div>
</div>
@endsection