<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script
    src="https://www.paypal.com/sdk/js?client-id=AfoxScMJDXiiDwoZYjgdd_kEna-R0UnsvL9hxZBthCVM_G4Vnw9Vj91MZ2Q5YTAgeFqY9llbCnRXUlqY&currency=USD">
</script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}" />
<!------ Include the above in your HEAD tag ---------->
<div id="overlay" style="display:none">
    <div class="spinner"></div>
    <br />
    Dont refresh or reload the page as payment is getting done...
</div>
<div class="wrapper">
  <div class="l"></div>
  <div class="l"></div>
</div>
<!-- <p>Pay using your Paypal Id</p> -->
<div class="col-md-6 offset-md-3" style="margin-top: 15%;">
    <span class="anchor" id="formPayment"></span>
    <hr class="my-5">

    <!-- form card cc payment -->
    <div class="card card-outline-secondary">
        <div class="card-body">
            <h3 class="text-center">Signup final Process</h3>
            <hr>
            <div id="paypal-button-container"></div>
        </div>
    </div>
    <!-- /form card cc payment -->
    <style>
    #overlay {
        background: #ffffff;
        color: #666666;
        position: fixed;
        height: 100%;
        width: 100%;
        z-index: 5000;
        top: 0;
        left: 0;
        float: left;
        text-align: center;
        padding-top: 25%;
        opacity: .80;
    }

    .spinner {
        margin: 0 auto;
        height: 80px;
        width: 80px;
        animation: rotate 0.8s infinite linear;
        border: 12px solid #de5400;
        border-right-color: transparent;
        border-radius: 50%;
    }

    @keyframes rotate {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

.wrapper {
  position: absolute;
  left: 50%;
  margin: -25px 0 0 -25px;
}

.l {
  position: absolute;
  border: 4px solid #a72693;
  border-radius: 50%;
  animation: animate 1s ease-out infinite;
}
p{
    font-family: 'Overpass', sans-serif;
    text-align: center;
    font-size: 20px;
    color: #d2883c;
    position: fixed;
    top: 14%;
    left: 41.5%;
    margin: -25px 0 0 -25px;
  
}
.l:nth-child(2) {
  animation-delay: -0.5s;
}

@keyframes animate {
  0% {
    top: 25px;
    left: 25px;
    width: 0px;
    height: 0px;
    opacity: 1;
  }
  
  100% {
    top: 0px;
    left: 0px;
    width: 50px;
    height: 50px;
    opacity: 0;
  }
}
    </style>

    <script>
    $.ajaxSetup({

        headers: {

            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

        }

    });
    // Render the PayPal button into #paypal-button-container
    paypal.Buttons({

        // Set up the transaction
        createOrder: function(data, actions) {
            $('#overlay').show()
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '1'
                    }
                }]
            });
        },

        // Finalize the transaction
        onApprove: function(data, actions) {
            let userId = <?php echo $userId; ?>;
            data.userId = userId;
            return $.ajax({
                /* the route pointing to the post function */
                url: '/payment/success/' + data.orderID,
                type: 'POST',
                /* send the csrf-token and the input to the controller */
                data: data,
                dataType: 'JSON',
                /* remind that 'data' is the response of the AjaxController */
                success: function(data) {
                    $('#overlay').hide().delay(3000).fadeIn(1000);
                    window.location.href='/login?payment=success';
                },
                error: function (request, status, error) {
                    swal({
                    text: "Something went wrong as payment is not successfull!. Please contact to Administrator",
                    icon: "error",
                    buttons: true,
                    dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            window.location.href='/login';
                        } else {
                            window.location.href='/login';
                        }
                    });
                }
            });
        }


    }).render('#paypal-button-container');
    </script>