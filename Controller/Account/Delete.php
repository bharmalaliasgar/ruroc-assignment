<?php

declare(strict_types=1);

namespace DevTech\AccountSection\Controller\Account;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Area;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use DevTech\AccountSection\Model\Config;

class Delete extends Action
{
    public function __construct(
        private readonly Context $context,
        private readonly Session $customerSession,
        private readonly TransportBuilder $transportBuilder,
        private readonly StoreManagerInterface $storeManager,
        private readonly Config $config
    ) {
        parent::__construct($this->context);
    }

    public function execute(): void
    {
        $customer = $this->customerSession->getCustomer();

        if ($this->getRequest()->isPost() && $this->getRequest()->getParam('delete_account') == 1) {
            $this->sendDeletionRequestEmail($customer);
        }
    }

    /**
     * @param $customer
     * @return void
     */
    private function sendDeletionRequestEmail($customer): void
    {
        $notificationEmail = $this->config->getAdminNotificationEmails();
        if ($notificationEmail === null) {
            return;
        }

        try {
            $transport = $this->transportBuilder
                ->setTemplateIdentifier('account_deletion_request_template')
                ->setTemplateOptions(
                    [
                        'area' => Area::AREA_FRONTEND,
                        'store' => $this->storeManager->getStore()->getId(),
                    ]
                )
                ->setTemplateVars(
                    [
                        'Name' => $customer->getName(),
                        'Id' => $customer->getId(),
                        'Email' => $customer->getEmail()
                    ]
                )
                ->setFromByScope('general')
                ->addTo($notificationEmail)
                ->getTransport();

            $transport->sendMessage();
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Failed to send email to %1', $notificationEmail));
        }
    }
}
