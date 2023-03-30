<?php

namespace Maxmize\LeftQty\Block;

use Magento\Catalog\Model\ProductFactory;
use Magento\InventorySalesApi\Api\GetProductSalableQtyInterface;
use Magento\InventorySalesApi\Api\StockResolverInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\InventorySalesApi\Api\Data\SalesChannelInterface;

/**
 * Class LeftQty
 * @package Maxmize\LeftQty\Block
 */
class LeftQty extends \Magento\Framework\View\Element\Template
{
    /**
     * @var GetProductSalableQtyInterface
     */
    protected $salebleqty;

    /**
     * @var StockResolverInterface
     */
    protected $stockresolver;

    /**
     * @var StoreManagerInterface
     */
    protected $storemanager;

    /**
     * @var Http
     */
    protected $request;

    /**
     * @var ProductFactory
     */
    protected $product;

    /**
     * LeftQty constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\App\Request\Http $request
     * @param ProductFactory $product
     * @param StoreManagerInterface $storemanager
     * @param GetProductSalableQtyInterface $salebleqty
     * @param StockResolverInterface $stockresolver
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\App\Request\Http $request,
        ProductFactory $product,
        StoreManagerInterface $storemanager,
        GetProductSalableQtyInterface $salebleqty,
        StockResolverInterface $stockresolver,
        array $data = [])
    {
        $this->request = $request;
        $this->product = $product;
        $this->storemanager = $storemanager;
        $this->salebleqty = $salebleqty;
        $this->stockresolver = $stockresolver;
        parent::__construct($context, $data);
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function saleble()
    {
        $productId = $this->request->getParam('id');
        $websiteCode = $this->storemanager->getWebsite()->getCode();
        $stockDetails = $this->stockresolver->execute(SalesChannelInterface::TYPE_WEBSITE, $websiteCode);
        $stockId = $stockDetails->getStockId();
        $productDetails = $this->product->create()->load($productId);
        $sku = $productDetails->getSku();
        $proType = $productDetails->getTypeId();

        if ($proType != 'configurable' && $proType != 'bundle' && $proType != 'grouped') {
            $stockQty = $this->salebleqty->execute($sku, $stockId);
            return $stockQty;
        } else {
            return '';
        }

    }
}