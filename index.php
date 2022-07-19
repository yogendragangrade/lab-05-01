<html>
<head>
        <title>Ahmad</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css">
</head>
<body style="background-color: #cc66ff">
        <div class="ui container">
                <div class="ui segment" style="max-width: 800px; margin: 0 auto;">

                                <form class="ui form" style="width: 400px; margin: 0 auto">
                                <h3 class="ui header"></h3>
                                <div class="field">
                                        <label>Product Name</label>
                                        <input type="text" name="product_name" placeholder="Product Name" id="new_product_name" required>
                                </div>
                                <div class="field">
                                        <label>Product Qty.</label>
                                        <input type="number" name="first-name" placeholder="Product Qty." id="new_product_quantity" required>
                                </div>
                                <div class="field">
                                        <label>Product Price</label>
                                        <input type="number" name="first-name" placeholder="Product Price" id="new_product_price" required>
                                </div>
                                <div class="field">
                                        <div class="ui grid">
                                                <div class="eight wide column">
                                                        <button class="ui button" id="product_add">Add</button>
                                                </div>
                                                <div class="eight wide column" style="text-align: right;">
                                                        <button class="ui button" id="product_fetch">Read</button>
                                                </div>
                                        </div>
                                </div>

</form>
                        <table class="ui celled table">
                                <thead>
                                        <tr>
                                                <th class="center">Product Name</th>
                                                <th class="center">Product Qty.</th>
                                                <th class="center">Product Price</th>
                                                <th></th>
                                                <th></th>
                                        </tr>
                                </thead>
                                <tbody></tbody>
                        </table>
                </div>
        </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js" integrity="sha256-t8GepnyPmw9t+foMh3mKNvcorqNHamSKtKRxxpUEgFI=" crossorigin="anonymous"></script>
<script type="text/javascript">
        let db_url = undefined,
                db_user = undefined,
                db_pass = undefined;

        $('.setting').click(function() {
                //get_data();

                let connection_string = prompt('connection string');
                while (connection_string.length === 0 || connection_string.split(';').length != 3)
                        connection_string = prompt('connecton string');

                let db_arr = connection_string.split(';');

                db_url = db_arr[0].split('=')[1];
                db_user = db_arr[1].split('=')[1];
                db_pass = db_arr[2].split('=')[1];

                console.log(db_url, db_user, db_pass);

                get_data();
        });

        $('#product_fetch').click(() => {
                 get_data();
        });

        function get_data() {
                db = `db_url=${db_url}&db_user=${db_user}&db_pass=${db_pass}`;

                $.ajax({
                        url: `data.php?operation=get&${db}`,
                        success: function(result) {
                                if (result.error) {
                                        alert(result.message);
                                        return;
                                }

                                $('tbody').empty();
                                result.products.forEach(function(product) {
                                        $('tbody').append(`
                                                <tr data-id="${product.id}" data-name="${product.name}" data-quantity="${product.quantity}" data-price="${product.price}">
                                                        <td class="center">${product.name}</td>
                                                        <td class="center">${product.quantity}</td>
                                                        <td class="center">${product.price}</td>
                                                        <td class="center"><i class="edit green icon"></i></td>
							<td class="center"><i class="trash alternate red icon"></i></td>
                                                </tr>
                                                `)
                                });
                        },
                        error: function(error) {
                                console.log(error);
                                alert(error.message);
                        }
                });

        }

        // add
        $('form').submit(function(event) {
                event.preventDefault();
                db = `db_url=${db_url}&db_user=${db_user}&db_pass=${db_pass}`;
                $.ajax({
                        url: `data.php?operation=add&product_name=${$('#new_product_name').val()}&product_quantity=${$('#new_product_quantity').val()}&product_price=${$('#new_product_price').val()}&${db}`,
                        success: function(result) {
                                if (result.error) {
                                        alert(result.message);
                                        return;
                                }
                                get_data();
                        },
                        error: function(error) {
                                console.log(error);
                                alert(error.message);
                        }
                });
        });

        //update
        $(document).on('click', '.edit.icon', function() {
                let tr = $(this).closest('tr')[0];
                let product_id = $(tr).data('id'),
                        product_name = undefined,
                        product_quantity = undefined,
                        product_price = undefined;

                while (!product_name || product_name.length === 0)
                        product_name = prompt('Product name');

                while (!product_quantity || isNaN(product_quantity))
                        product_quantity = prompt('Product quantity');

                while (!product_price || isNaN(product_price))
                        product_price = prompt('Product price');

                db = `db_url=${db_url}&db_user=${db_user}&db_pass=${db_pass}`;

                $.ajax({
                        url: `data.php?operation=update&product_id=${product_id}&product_name=${product_name}&product_quantity=${product_quantity}&product_price=${product_price}&${db}`,
                        success: function(result) {
                                if (result.error) {
                                        alert(result.message);
                                        return;
                                }
                                get_data();
                        },
                        error: function(error) {
                                console.log(error);
                                alert(error.message);
                        }
                });
        });

        // delete
        $(document).on('click', '.trash.icon', function() {
                let tr = $(this).closest('tr')[0]
                let product_id = $(tr).data('id')
                db = `db_url=${db_url}&db_user=${db_user}&db_pass=${db_pass}`;

                $.ajax({
                        url: `data.php?operation=delete&product_id=${product_id}&${db}`,
                        success: function(result) {
                                if (result.error) {
                                        alert(result.message);
                                        return;
                                }

                                $(tr).remove();
                        },
                        error: function(error) {
                                console.log(error);
                                alert(error.message);
                        }
                });
        });
</script>

</body>

</html>