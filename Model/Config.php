<?php
declare(strict_types=1);

namespace DevTech\AccountSection\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    private const string PATH = 'customer/gdpr_settings/admin_email_csv';

    public function __construct(private readonly  ScopeConfigInterface $scopeConfig) {}

    public function getAdminNotificationEmails(): array|null
    {
        $notificationEmail =  $this->scopeConfig->getValue(self::PATH, ScopeInterface::SCOPE_STORE);
        if($notificationEmail !== null) {
            return explode(',', $notificationEmail);
        }
        return null;
    }
}
