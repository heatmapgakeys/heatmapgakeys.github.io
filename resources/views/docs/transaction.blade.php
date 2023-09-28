@extends('layouts.heatsketchdocs')
@section('title', 'Transaction')
@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <ul id="submenu">


            </ul>
            <div class="section-header">
                <h1 class="main-header" id="transaction">{{__("Transaction")}}</h1>
            </div>
            <hr class="main-hr"/>



            <div class="alert alert-primary" role="alert">
                {{__("In the transaction section, you can see your whole transaction in graphical presentation.")}}
            </div>


            <img
                src= {{asset("assets/docs/heatsketch_images/transaction/transactions.png")}}
                class="img-fluid"
            />

        </div>
    </section>
</div>

@endsection
