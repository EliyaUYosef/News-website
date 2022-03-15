        
        var select = document.getElementById("select_input");
        select.addEventListener("change", displayDate);

        function displayDate() {
            document.getElementById("background_text").style.display = "none";
            loadDoc(this.value + "_data.php");
        }

        function ParseProducts(data_string) {
            let obj = JSON.parse(data_string);
            obj.forEach(o => createProductRow(o));
        }

        function ParseCategories(data_string) {
            let obj = JSON.parse(data_string);
            console.log(obj[1]);
            obj.forEach(o => createCategoryRow(o));
        }

        function createCategoryRow(element) {
            document.getElementById("products_table").style.display = "none";
            document.getElementById("categories_table").style.display = "block";

            var body_table = document.getElementById("categories_table_body");
            var row = body_table.insertRow(-1);

            var ID_cell = row.insertCell(0);
            ID_cell.outerHTML = "<th scope='row'>" + element.ID + "</th>";
            var title_cell = row.insertCell(1);
            var product_count_cell = row.insertCell(2);
            var attributes_cell = row.insertCell(3);

            attributes_cell.innerHTML = parseAttributeToCategoriesTable(element.attributes);
            attributes_cell.classList.add('attributes_cells');
            title_cell.innerHTML = element.title;
            product_count_cell.innerHTML = element.product_amount;
        }

        function createProductRow(element) {
            document.getElementById("categories_table").style.display = "none";
            document.getElementById("products_table").style.display = "block";

            var body_table = document.getElementById("products_table_body");

            var row = body_table.insertRow(-1);

            var ID_cell = row.insertCell(0);
            ID_cell.outerHTML = "<th scope='row'>" + element.ID + "</th>";

            var title_cell = row.insertCell(1);
            var price_cell = row.insertCell(2);
            var attributes_cell = row.insertCell(3);
            var categories_cell = row.insertCell(4);


            title_cell.innerHTML = element.title;
            price_cell.innerHTML = element.price;
            attributes_cell.innerHTML = element.attributes;
            categories_cell.innerHTML = element.categories;

            attributes_cell.classList.add('small_text');
            categories_cell.classList.add('small_text');
        }

        function parseAttributeToCategoriesTable(attributes) {
            var secondArr = [];
            for (var i = 0; i < attributes.length; i++) {
                if (attributes[i].attribute_name in secondArr) {
                    secondArr[attributes[i].attribute_name] += attributes[i].label_title + "(" +
                        attributes[i].label_count + ") ,";
                } else {
                    secondArr[attributes[i].attribute_name] = "<span class='font-weight-bold text-nowrap'>" + attributes[i].attribute_name + "</span> : " +
                        attributes[i].label_title + "(" + attributes[i].label_count + ") ,";
                }
            }

            attributes_string = '';
            for (const key of Object.keys(secondArr)) {
                // secondArr[key].slice(0, -2);
                secondArr[key] = secondArr[key].substring(0, secondArr[key].length - 1);
                attributes_string += secondArr[key] + ".<br/> ";
            }

            return attributes_string;
        }

        function loadDoc(type_data_string) {
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                if (type_data_string == 'products_data.php')
                    ParseProducts(this.responseText);
                if (type_data_string == 'categories_data.php')
                    ParseCategories(this.responseText);
            }
            xhttp.open("GET", type_data_string);
            xhttp.send();
        }