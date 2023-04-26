



$(document).ready(function(){

           /////add-product-btn
            $('.add-product-btn').on('click',function(e){
                        e.preventDefault();
                        var name = $(this).data('name');
                        var id = $(this).data('id');
                         // <input type="hidden" name="product_ids[]" value="${id}">

                        var price = $.number($(this).data('price'),2);
                        $(this).removeClass('btn-success').addClass('btn-default disabled')

                        var html =
                                    ` <tr>
                                        <td> ${name}</td>

                                        <td><input type="number"   name="products[${id}][quantity]" data-price="${price}" class="form-control input-sm product-quanitity" min="1" value="1"> </td>
                                        <td class="product-price">${price}</td>
                                        <td><button class="btn btn-danger btn-sm remove-product-btn" data-id="${id}"> <span class="fa fa-trash"></span></button></td>

                                        </tr>`;

                        $('.order-list').append(html);
                        calcualteTotla();

            });///add-product-btn

            ///disabled
            $('body').on('click','.disabled', function(e){
                e.preventDefault();
            });///disabled

            ///remove-product-btn
         $('body').on('click','.remove-product-btn',function(e){


         e.preventDefault();


            var id = $(this).data('id');
            $(this).closest('tr').remove();

             $('#product-'+ id).removeClass('btn-default disabled').addClass('btn-success');
               // alert('remove-product-btn')


               calcualteTotla();

         })///remove-product-btn

          /// product-quanitity keyup
         $('body').on('keyup change', '.product-quanitity',function(){
           var quanitity = Number($(this).val());
           var unitprice = parseFloat($(this).data('price').replace(/,/g, ''));
           $(this).closest('tr').find('.product-price').html($.number(quanitity * unitprice));
           calcualteTotla();
         });/// product-quanitity keyupp


         ///order-products

         $('.order-products').on('click',function(e){
            e.preventDefault();
      $('#loading').css('display','flex');

          var url = $(this).data('url');

          var method = $(this).data('method');

          $.ajax({
            url: url,
            method : method,
            success:function(data){

               $('#loading').css('display','none');

                $('#order-product-list').empty();
                $('#order-product-list').append(data);
            }
          })

         })


                    //print-area
            $(document).on('click','.print-btn',function(){
                $('#print-area').printThis();

            })


}); ///
        /// calcualteTotla
        function calcualteTotla(){
            var price = 0;

          $('.order-list .product-price').each(function(index){
            price += parseFloat($(this).html().replace(/,/g, ''));


          });

          $('.total-price').html($.number(price));

            // if check
          if(price > 0){
          $('#add-order-form-btn').removeClass('disabled');
          }else{
          $('#add-order-form-btn').addClass('disabled');

          } // edn if check

        }///calcualteTotla


