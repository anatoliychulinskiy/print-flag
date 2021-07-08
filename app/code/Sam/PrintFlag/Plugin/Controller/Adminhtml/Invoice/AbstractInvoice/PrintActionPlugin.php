<?php

namespace Sam\PrintFlag\Plugin\Controller\Adminhtml\Invoice\AbstractInvoice;

use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Sales\Controller\Adminhtml\Invoice\AbstractInvoice\PrintAction;
use Psr\Log\LoggerInterface;

/**
 * Class PrintActionPlugin
 * @package Sam\PrintFlag\Plugin\Controller\Adminhtml\Invoice\AbstractInvoice
 */
class PrintActionPlugin
{
    /**
     * @var InvoiceRepositoryInterface
     */
    protected $invoiceRepository;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * PrintActionPlugin constructor.
     * @param InvoiceRepositoryInterface $invoiceRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        InvoiceRepositoryInterface $invoiceRepository,
        LoggerInterface $logger
    ) {
        $this->invoiceRepository = $invoiceRepository;
        $this->logger = $logger;
    }

    /**
     * @param PrintAction $subject
     * @param $result
     * @return mixed
     */
    public function afterExecute(PrintAction $subject, $result)
    {
        $invoiceId = $subject->getRequest()->getParam('invoice_id');
        if ($invoiceId) {
            try {
                $invoice = $this->invoiceRepository->get($invoiceId);
                if ($invoice && !$invoice->getIsPrinted()) {
                    $invoice->setIsPrinted(1);
                    $this->invoiceRepository->save($invoice);
                }
            } catch (\Exception $e) {
                $this->logger->critical('Error message', ['exception' => $e]);
            }
        }
        return $result;
    }
}
