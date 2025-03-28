<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Home Page</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f4f4;
        }

        /* Header Section */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #007bff;
            padding: 15px 20px;
            color: white;
        }

        .profile-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            color: #007bff;
            cursor: pointer;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 16px;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }

        /* Product List */
        .product-section {
            padding: 20px;
            text-align: center;
        }

        .product-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .product {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .product img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
        }

        .product h3 {
            margin: 10px 0;
        }

        .product p {
            color: #555;
            font-size: 14px;
        }

        .product button {
            margin-top: 10px;
            padding: 8px 12px;
            border: none;
            background: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .product button:hover {
            background: #0056b3;
        }

        /* Footer Section */
        .footer {
            background: #007bff;
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: 30px;
        }

    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <div class="profile-icon"><a href="{{route('login')}}">👤</a></div>
        <div class="nav-links">
            <a href="#">Home</a>
            <a href="#">cart</a>
            <a href="#">Contact</a>
        </div>
    </div>

    <!-- Product Section -->
    <div class="product-section">
        <h2>Our Products</h2>
        <div class="product-list" id="pList">


        </div>
    </div>


    <form action="{{ route('razorpay.payment') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" name="product_id" value="1"  class="form-control" required>
        </div>

        

        <div class="mb-3">
            <label for="amount" class="form-label">Amount (INR)</label>
            <input type="number" name="amount" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Proceed to Pay</button>
    </form>


    <!-- Footer -->
    <div class="footer">
        <p>© 2025 Your Company | All Rights Reserved</p>
    </div>


    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        viewProduct()

        function viewProduct() {
            $.ajax({
                type: "GET",
                url: "{{route('product.list')}}",
                success: function (response) {
                    console.log(response);
                    const productList = response.data
                    $('#productData').html("");
                    productList.forEach((product,index) => {
                        const html = `

                        <div class="product">
                            <img src="${product.image ? '{{ asset('') }}' + product.image : 'https://via.placeholder.com/50'}" width="50">
                <h3>${product.name}</h3>
                <p>$${product.price}</p>
                <button onclick="addPayment(${product.id})">Add to Cart</button>
            </div>

                        `
                    // console.log(html);

                    $('#pList').append(html);

                    });
                }
            });
         }

         function addPayment(id) {

            $.ajax({
                type: "POST",
                url: "{{route('razorpay.payment')}}",
                data:{
                    id:id,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    console.log(response);

                     // Create a hidden form to submit the data via POST
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('razorpay.payment') }}"; // Ensure it matches the Laravel POST route

            // Add CSRF token
            let csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = "{{ csrf_token() }}";
            form.appendChild(csrfInput);

            // Add orderId input
            let orderInput = document.createElement('input');
            orderInput.type = 'hidden';
            orderInput.name = 'orderId';
            orderInput.value = response.orderId;
            form.appendChild(orderInput);

            // Add product_id input
            let productInput = document.createElement('input');
            productInput.type = 'hidden';
            productInput.name = 'product_id';
            productInput.value = response.product_id;
            form.appendChild(productInput);

            // Add amount input
            let amountInput = document.createElement('input');
            amountInput.type = 'hidden';
            amountInput.name = 'amount';
            amountInput.value = response.amount;
            form.appendChild(amountInput);

            document.body.appendChild(form);
            form.submit(); // Submit the form

                }
            });
          }

    </script>

</body>
</html>
