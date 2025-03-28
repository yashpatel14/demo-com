<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Payment</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body onload="startPayment()">

    <h3 style="text-align: center">Processing Payment...</h3>

    <form name="razorpayForm" action="{{ route('razorpay.success') }}" method="POST">
        @csrf
        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
        <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
        <input type="hidden" name="razorpay_signature" id="razorpay_signature">
    </form>

    <script>
        function startPayment() {
            var options = {
                "key": "{{ $razorpayKey }}",
                "amount": "{{ $amount * 100 }}",
                "currency": "INR",
                "order_id": "{{ $orderId }}",
                "handler": function (response){
                    document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                    document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                    document.forms['razorpayForm'].submit();
                },
                "prefill": {
                    "name": "yash patel",
                    "email": "yash@gmail.com",
                    "contact": "1234567890"
                },
                "theme": {
                    "color": "#3399cc"
                },
                "modal": {
            "ondismiss": function() {
                console.log("Payment popup closed by user.");
                window.location.href = "{{ url('/') }}"; // Redirect on close
            }
        }
            };

            var rzp = new Razorpay(options);
            rzp.on('modal.close', function () {
        window.location.href = "{{ url('/') }}"; // Redirect to your failure page
    });
            rzp.open();
        }
    </script>

</body>
</html>
