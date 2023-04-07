<?php declare(strict_types=1);

namespace DeveloperHub\OrderNote\Model;

use Magento\Quote\Model\ResourceModel\Quote\QuoteIdMask;
use DeveloperHub\OrderNote\Api\GuestOrderNoteManagementInterface;
use Magento\Quote\Model\QuoteIdMaskFactory;
use DeveloperHub\OrderNote\Api\OrderNoteManagementInterface;
use DeveloperHub\OrderNote\Api\Data\OrderNoteInterface;

class GuestOrderNoteManagement implements GuestOrderNoteManagementInterface
{
    /** @var QuoteIdMaskFactory */
    protected $quoteIdMaskFactory;

    /** @var OrderNoteManagementInterface */
    protected $orderNoteManagement;

    /** @var QuoteIdMask */
    private $quoteIdMaskResource;

    /**
     * GuestOrderNoteManagement constructor.
     * @param QuoteIdMaskFactory $quoteIdMaskFactory
     * @param QuoteIdMask $quoteIdMaskResource
     * @param OrderNoteManagementInterface $orderNoteManagement
     */
    public function __construct(
        QuoteIdMaskFactory $quoteIdMaskFactory,
        QuoteIdMask $quoteIdMaskResource,
        OrderNoteManagementInterface $orderNoteManagement
    ) {
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->orderNoteManagement = $orderNoteManagement;
        $this->quoteIdMaskResource = $quoteIdMaskResource;
    }

    /** {@inheritDoc} */
    public function saveOrderNote(
        string $cartId,
        OrderNoteInterface $orderNote
    ) {
        $quoteIdMask = $this->quoteIdMaskFactory->create();
        $this->quoteIdMaskResource->load($quoteIdMask, $cartId, 'masked_id');
        return $this->orderNoteManagement->saveOrderNote(
            (int)$quoteIdMask->getQuoteId(),
            $orderNote
        );
    }
}
