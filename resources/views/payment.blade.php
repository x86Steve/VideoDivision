@extends ('layouts.app')

@section('content')

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">

    {{ csrf_field() }}

    <?php
    $subbed=0;
    foreach($subscription as $subscription)
    {
        if ($subscription->isPaid=='1')
        {
            $subbed=1;
        }
    }
    ?>


    <form role="form" method="POST">
        @if($subbed==0)
    <div class="container">
        <hr>
        <div class="row">
            <div class="col-sm-8">
                <article class="card">
                    <div class="card-body p-5">
                        <ul class="nav bg-light nav-pills rounded nav-fill mb-3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="pill" href="#nav-tab-card">
                                    <i class="fa fa-credit-card"></i> Credit Card</a></li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="pill" href="#nav-tab-paypal">
                                    <i class="fab fa-paypal"></i>  Paypal</a></li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="pill" href="#nav-tab-bank">
                                    <i class="fa fa-university"></i>  Bank Transfer</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="nav-tab-card">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        {{csrf_field()}}
                                        <label for="username">Full Name</label>
                                        <input type="text" class="form-control" name="username" placeholder="" required maxlength="50">
                                    </div> <!-- form-group.// -->

                                    <div class="form-group">
                                        <label for="cardNumber">Credit Card Number</label>
                                        <div class="input-group">
                                            <input oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type = "number" maxlength = "16" class="form-control" name="cardNumber" placeholder="" required>
                                            <div class="input-group-append">
				<span class="input-group-text text-muted">
					<i class="fab fa-cc-visa"></i>   <i class="fab fa-cc-amex"></i>  
					<i class="fab fa-cc-mastercard"></i>
				</span>
                                            </div>
                                        </div>
                                    </div> <!-- form-group.// -->

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><span class="hidden-xs">Expiration</span> </label>
                                                <div class="input-group">
                                                    <select class="custom-select-med" id="month" required name="month">
                                                        <option value="" selected disabled> Month</option>
                                                        <option value="1">Jan</option>
                                                        <option value="2">Feb</option>
                                                        <option value="3">Mar</option>
                                                        <option value="4">Apr</option>
                                                        <option value="5">May</option>
                                                        <option value="6">Jun</option>
                                                        <option value="7">Jul</option>
                                                        <option value="8">Aug</option>
                                                        <option value="9">Sept</option>
                                                        <option value="10">Oct</option>
                                                        <option value="11">Nov</option>
                                                        <option value="12">Dec</option>
                                                    </select>
                                                    <input oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type = "number" maxlength = "2" class="form-control" required="" placeholder="YY" name="year" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label  title="" data-original-title="3 digits code on back side of the card">CVC</label>
                                                <input oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type = "number" maxlength = "3" class="form-control" name="cvv" required>
                                            </div> <!-- form-group.// -->
                                        </div>
                                    </div> <!-- row.// -->



                                    <select class="custom-select custom-select-lg mb-1" id="selection" required="" name="selection">
                                        <option selected>Select your Plan</option>
                                        <option value="1">Basic $10.00</option>
                                    </select>
                                    <?php echo "<br>";?>
                                    <?php echo "<br>";?>
                                    <button type="submit" class="btn btn-primary btn-block">Submit</button>

                            </div> <!-- tab-pane.// -->
                            <div class="tab-pane fade" id="nav-tab-paypal">
                                <p>Paypal is easiest way to pay online</p>
                                <p>
                                    <button type="button" class="btn btn-primary"> <i class="fab fa-paypal"></i> Log in my Paypal </button>
                                </p>
                                <p><strong>Note:</strong>This is not real please do not sue. </p>
                            </div>
                            <div class="tab-pane fade" id="nav-tab-bank">
                                <p>Bank accaunt details</p>
                                <dl class="param">
                                    <dt>BANK: </dt>
                                    <dd>Wayne Banking</dd>
                                </dl>
                                <dl class="param">
                                    <dt>Accaunt number: </dt>
                                    <dd> 12345678912345</dd>
                                </dl>
                                <dl class="param">
                                    <dt>IBAN: </dt>
                                    <dd> 123456789</dd>
                                </dl>
                                <p><strong>Note:</strong> This is not real please do not sue.</p>
                            </div> <!-- tab-pane.// -->
                        </div> <!-- tab-content .// -->
                    </div> <!-- card-body.// -->
                </article> <!-- card.// -->
            </div> <!-- col.// -->
        </div> <!-- row.// -->

@endif

@if($subbed==1)


<hr>

                {{csrf_field()}}
        <div class="row">
            {{csrf_field()}}
            <div class="col-sm-8 align-content-center">
                <article class="card align-content-center">
                    <div class="card-body p-5 align-content-center">
                        <ul class="nav bg-light nav-pills rounded nav-fill mb-2" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="pill" href="#nav-tab-basic">Subscription Status:  Subscribed to Basic Plan</a></li></ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="nav-tab-basic">
                                <p><Strong>Our Records Indicate that you are already subscribed to the Basic Streaming Service</Strong></p>
                                <dl class="param">

                                    <dd>You may cancel the subscription by checking the box below and submitting the cancellation request
                                        </dd>
                                </dl>
                                {{csrf_field()}}
                                <div class="form-group center">
                                    <input class="form-check-input" type="checkbox" value="0" required id="defaultCheck1">
                                    <label class="form-check-label" for="defaultCheck1">
                                        Cancel Subscription
                                    </label>
                                </div>
                                <?php echo "<br>";?>
                                    <?php echo "<br>";?>
                                <button type="submit" class="btn btn-primary btn-block">Submit</button>
                            <!-- tab-pane.// -->

                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>
    </form>

@endif
@endsection

<?php
/**
 * Created by PhpStorm.
 * User: MuscleNerd
 * Date: 3/3/2019
 * Time: 4:53 PM
 */
?>