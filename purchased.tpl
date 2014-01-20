<!-- KISSmetrics Purchased -->
<script type="text/javascript">
  _kmq.push(['record', 'Purchased', {ldelim}'Order ID':{$trans.id}, 'Order Total':{$trans.total}{rdelim}]);
  _kmq.push(function() {ldelim}
    {foreach from=$purchased_products item='product' name='purchasedProducts'}
      KM.set(
        {ldelim}
          'ProductID':'{$product.product_id}',
          '_t':KM.ts() + {$smarty.foreach.purchasedProducts.index},
          '_d':1,
          'Purchased Product Name': '{$product.product_name}',
          'Color': '{$product.category}',
          'Category': '{$product.manufacturer_name}',
          'Price': '{$product.product_price_wt}',
          'Quantity': '{$product.product_quantity}'
        {rdelim}
      );
    {/foreach}
  {rdelim});
</script>
