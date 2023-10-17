<?php

namespace RltSquare\ProductPopup\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Catalog\Helper\Image;


class ProductDetails extends Action
{
    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;
    /**
     * @var SessionManagerInterface
     */
    protected $session;
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    private ProductRepositoryInterface $productRepository;
    private RequestInterface $request;
    private Image $helperImage;

    public function __construct(
        Context                    $context,
        JsonFactory                $resultJsonFactory,
        SessionManagerInterface    $session,
        PageFactory                $resultPageFactory,
        ProductRepositoryInterface $productRepository,
        RequestInterface           $request,
        Image                      $helperImage
    )
    {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->session = $session;
        $this->resultPageFactory = $resultPageFactory;
        $this->productRepository = $productRepository;
        $this->request = $request;
        $this->helperImage = $helperImage;
    }

    public function execute()
    {
        $productSku = $this->request->getParam('sku');
        $products = $this->productRepository->get($productSku);
        $relatedProducts = $products->getRelatedProductCollection();
        $prodDetails = [];
        foreach ($relatedProducts as $relatedProduct) {
            $relatedProduct = $this->productRepository->get($relatedProduct->getSku());
            $productImage = $this->helperImage->init($relatedProduct, 'product_base_image')->getUrl();
            $prodDetails[] = "
        <div style='display: inline-block; margin-right: 20px;'>
            <img src='$productImage' alt='{$relatedProduct->getName()}'><br>
            <strong>Name:</strong> {$relatedProduct->getName()}<br>
            <strong>SKU:</strong> {$relatedProduct->getSku()}<br>
            <strong>Price:</strong> {$relatedProduct->getPrice()}
        </div>";
        }
        $result = $this->resultJsonFactory->create();
        return $result->setData(['details' => $prodDetails]);

    }
}
