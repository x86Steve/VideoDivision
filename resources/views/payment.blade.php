@extends ('layouts.app')

@section('content')

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">



    {{ csrf_field() }}
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
                                <form role="form" method="POST">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        {{csrf_field()}}
                                        <label for="username">Full name</label>
                                        <input type="text" class="form-control" name="username" placeholder="" required="">
                                    </div> <!-- form-group.// -->

                                    <div class="form-group">
                                        <label for="cardNumber">Card number</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="cardNumber" placeholder="">
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
                                                    <input type="number" class="form-control" placeholder="MM" name="month">
                                                    <input type="number" class="form-control" placeholder="YY" name="year">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label  title="" data-original-title="3 digits code on back side of the card">CVV</label>
                                                <input type="number" class="form-control" name="cvv" required="">
                                            </div> <!-- form-group.// -->
                                        </div>
                                    </div> <!-- row.// -->



                                    <select class="custom-select custom-select-lg mb-1" id="selection" name="selection">
                                        <option selected>Select your Plan</option>
                                        <option value="1">Basic $10.00</option>
                                        <option value="1">Premium $20.00</option>
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




<hr>
        <div class="row">
            <div class="col-sm-8">
                <article class="card">
                    <div class="card-body p-5">
                        <ul class="nav bg-light nav-pills rounded nav-fill mb-3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="pill" href="#nav-tab-basic">Basic</a></li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="pill" href="#nav-tab-premium">Premium</a></li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="nav-tab-basic">
                                <p>Basic Video Streaming Service</p>
                                <dl class="param">

                                    <dd><strong>With this service you can only stream Shows.  Movies will be an extra charge
                                        </strong></dd>
                                </dl>

                            </div> <!-- tab-pane.// -->

                            <div class="tab-pane fade" id="nav-tab-premium">
                                <p>Premium Video Streaming Service</p>
                                <dl class="param">
                                    <dd><strong>With this service you are allowed to stream both Shows and Movies</strong></dd>
                                </dl>

                                </div>
                        </div> <!-- tab-content .// -->
                    </div> <!-- card-body.// -->

                    </form>
                </article> <!-- card.// -->
            </div> <!-- col.// -->
        </div> <!-- row.// -->

    </div>







@endsection

<?php
/**
 * Created by PhpStorm.
 * User: MuscleNerd
 * Date: 3/3/2019
 * Time: 4:53 PM
 */
?>