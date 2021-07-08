<?php

namespace Sam\PrintFlag\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class IsPrinted
 * @package Sam\PrintFlag\Ui\Component\Listing\Column
 */
class IsPrinted extends Column
{
    /**
     * @var InvoiceRepositoryInterface
     */
    protected $invoiceRepository;

    /**
     * Status constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param InvoiceRepositoryInterface $invoiceRepository
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        InvoiceRepositoryInterface $invoiceRepository,
        array $components = [],
        array $data = []
    ) {
        $this->invoiceRepository = $invoiceRepository;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $invoice  = $this->invoiceRepository->get($item['entity_id']);
                $item[$this->getData('name')] = $invoice->getData('is_printed');
            }
        }
        return $dataSource;
    }
}
