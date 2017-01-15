<div id="product-prices" class="panel product-tab">
    <input type="hidden" name="submitted_tabs[]" value="Prices">

    <h3>CodesWholesale Product Settings</h3>

    <div class="form-group">
        <div class="col-lg-1"><span class="pull-right"></span></div>
        <label class="control-label col-lg-2" for="codeswholesale_product">
			<span class="label-tooltip" data-toggle="tooltip" data-original-title="Choose CodesWholesale Product.">
				CodesWholesale Product
			</span>
        </label>

        <div class="col-lg-5">

            <select name="codeswholesale_product"  id="codeswholesale_product_id" class="form-control">

                <option value="">-- choose one --</option>

                        {foreach $codeswholesale_products as $product}
                        <option data-stock-quantity="{$product->getStockQuantity()}" data-product-price="{$product->getLowestPrice()}" value="{$product->getProductId()}"
                        {if !empty($codeswholesale_product) && $codeswholesale_product == $product->getProductId()} selected="selected"{/if}>

                          {$product->getName()} - {$product->getPlatform()} - {$product->getStockQuantity()} - € {$product->getLowestPrice()}

                          </option>
                        {/foreach}
            </select>

            <i>When you will choose CodesWholesale product we will automatically add our stock quantity, and price with your chosen spread.</i>

        </div>
    </div>

    <div class="form-group">
        <div class="col-lg-1"><span class="pull-right"></span></div>
        <label class="control-label col-lg-2" for="codeswholesale_calculate_type">
			<span class="label-tooltip" data-toggle="tooltip" title="" data-original-title="Choose Calculating Type.">
				Calculate Type
			</span>
        </label>

        <div class="col-lg-5">
            <select name="cw_calculate"  id="calculate-type" class="form-control" >
                <option value="0" {if $codeswholesale_calculate && $codeswholesale_calculate == 0}selected="selected"{/if} {if !$codeswholesale_spread}disabled{/if}>Codeswholesale calculate with spread and stock</option>
                <option value="1" {if $codeswholesale_calculate && $codeswholesale_calculate == 1}selected="selected"{/if}>Custom price and stock</option>
            </select>
            <i>Choose your type of calculating price. If you want to use "CodesWholesale price with spread" submit form in CodesWholesale module settings.</i>
        </div>
    </div>

    <div class="panel-footer">
        <a href="index.php?controller=AdminProducts&amp;token=35a39ce0b81fbd39e728e4814e671bcc" class="btn btn-default"><i
                    class="process-icon-cancel"></i> Cancel</a>
        <button type="submit" name="submitAddproduct" class="btn btn-default pull-right"><i
                    class="process-icon-save"></i> Save
        </button>
        <button type="submit" name="submitAddproductAndStay" class="btn btn-default pull-right"><i
                    class="process-icon-save"></i> Save and stay
        </button>
    </div>
</div>



<!--<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>-->
<script type="text/javascript">

    $(document).ready(function () {

        function setProductPrice() {

            var spread_type = {$codeswholesale_spread_type}
            var spread = {$codeswholesale_spread}
            var price = $("option:selected", "#codeswholesale_product_id").attr("data-product-price");
            var stock = $("option:selected", "#codeswholesale_product_id").attr("data-stock-quantity");
            var qty = $('#qty_0 input');

            if ($("option:selected", this).length == 0 && spread_type == 0) {

                $("#priceTEReal").val(parseFloat(price) + parseFloat(spread));
                $("#priceTE").val(parseFloat(price) + parseFloat(spread));
                qty.val(stock);
                $('.available_quantity span').html(parseInt(stock));
                //qty.blur();
                qty.change();

            } else if ($("option:selected", this).length == 0 && spread_type == 1) {

                var calculate = (price / 100 * spread) + parseFloat(price);

                $("#priceTEReal").val(calculate.toFixed(2));
                $("#priceTE").val(calculate.toFixed(2));

                qty.val(stock);
                $('.available_quantity span').html(parseInt(stock));
                //qty.blur();
                qty.change();

            }
        }

        $("#calculate-type").on('change', 'select', function(){
            var qty = $('#qty_0 input');
            var value = $("#calculate-type").val();

            if(value == 1) {

                $("#priceTEReal").val("");
                $("#priceTE").val("");
                qty.attr('value', '1');
            }
            else {

                setProductPrice();
            }

        });

        $("#codeswholesale_product_id").change(function () {

            setProductPrice();
        })
    });
</script>

