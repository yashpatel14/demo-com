<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Product Insert & Listing</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 20px;
            background: #f4f4f4;
        }

        .container {
            width: 400px;
            padding: 20px;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
        }

        h2 {
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-group textarea {
            height: 80px;
            resize: none;
        }

        .form-group input[type="file"] {
            padding: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            font-size: 16px;
        }

        button:hover {
            background: #0056b3;
        }

        /* Product Listing Table */
        .product-list {
            width: 80%;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background: #007bff;
            color: white;
        }

        img {
            width: 50px;
            height: 50px;
            border-radius: 5px;
        }

        .btn {
            padding: 5px 10px;
            margin: 2px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .edit-btn {
            background-color: #ffc107;
            color: black;
        }

        .delete-btn {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>

    <!-- Product Insert Form -->
    <div class="container">
        <h2>Insert Product</h2>
        <form id="productForm" enctype="multipart/form-data">
            <div class="form-group">
                <label>Product Name</label>
                <input type="text" id="name" placeholder="Enter product name" required>
            </div>
            <div class="form-group">
                <label>Price ($)</label>
                <input type="number" id="price" placeholder="Enter price" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea id="desc" placeholder="Enter product description" required></textarea>
            </div>
            <div class="form-group">
                <label>Product Image</label>
                <input type="file" id="image"  accept="image/*" >
                <div id="showImage"></div>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select id="status">
                    <option value="0">Active</option>
                    <option value="1">Inactive</option>
                </select>
            </div>
        <input type="hidden" name="id" id="pId" value="">

            <button type="submit">Insert Product</button>
            <a href="{{url('logout')}}">Logout</a>
        </form>
    </div>

    <!-- Product Listing Table -->
    <div class="product-list">
        <h2>Product Listing</h2>
        <table id="productTable">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price ($)</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="productData">

            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        getProduct()
        $('#productForm').submit(function (e) {
            e.preventDefault();

            let formData = new FormData();
            formData.append('name',$('#name').val());
            formData.append('price',$('#price').val());
            formData.append('desc',$('#desc').val());
            formData.append('image',$("#image")[0].files[0]);
            formData.append('status',$("#status").val());
            formData.append('id',$("#pId").val());
            formData.append("_token", $('meta[name="csrf-token"]').attr("content"));

            $.ajax({
                type: "POST",
                url: "{{route('product.store')}}",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    console.log(response)
            $("#productForm")[0].reset();

                    getProduct()
                }
            });

        });

        function getProduct() {
            $.ajax({
                type: "GET",
                url: "{{route('product.get')}}",
                success: function (response) {
                    console.log(response);
                    const productList = response.data
                    $('#productData').html("");
                    productList.forEach((product,index) => {
                        const html = `
                        <tr>
                    <td>${index+1}</td>
                    <td><img src="${product.image ? '{{ asset('') }}' + product.image : 'https://via.placeholder.com/50'}" width="50"></td>
                    <td>${product.name}</td>
                    <td>$${product.price}</td>
                    <td>${product.desc}</td>
                    <td>
                        <button class="btn edit-btn" onclick="editProduct(${product.id})">Edit</button>
                        <button class="btn delete-btn" onclick="deleteProduct(${product.id})">Delete</button>
                    </td>
                </tr>
                        `
                    // console.log(html);

                    $('#productData').append(html);

                    });
                }
            });
         }

         function editProduct(id) {
            $.ajax({
                type: "GET",
                url: "{{route('product.edit')}}",
                data:{
                    id:id
                },
                success: function (response) {
                    console.log(response);

                    $('#name').val(response.data.name);
                    $('#price').val(response.data.price);
                    $('#desc').val(response.data.desc);
                    $("#showImage").append(`<img src="${response.data.image ? '{{ asset('') }}' + response.data.image : 'https://via.placeholder.com/50'}" width="50" id="viewImage">`)

                    $('#pId').val(response.data.id);

                    $('#status').val(response.data.status).change();




                }
            });
          }

          function deleteProduct(id) {
            $.ajax({
                type: "GET",
                url: "{{route('product.delete')}}",
                data:{
                    id:id
                },
                success: function (response) {
                    console.log(response);

                    getProduct();




                }
            });
          }

    </script>

</body>
</html>
