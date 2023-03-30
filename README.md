# magento-get-left-qty
Magento 2 get left Qty at product page

Display the available product Qty at the product page.

upload create folder Maxmize at /app/code/

upload the LeftQty inside this Dir


php bin/magento cache:clean


php bin/magento cache:flush


#########################

to call the Left Qty Value inside phtml page 


<?php

$blockObj= $block->getLayout()->createBlock('Maxmize\LeftQty\Block\LeftQty');

echo $blockObj->saleble();

?>


