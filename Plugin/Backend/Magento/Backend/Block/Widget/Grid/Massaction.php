<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\AdminUserGuide\Plugin\Backend\Magento\Backend\Block\Widget\Grid;

use Magefan\AdminUserGuide\Model\Config;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Module\Manager as ModuleManager;

class Massaction
{

    /**
     * @var Config
     */
    private $config;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ModuleManager
     */
    private $moduleManager;

    /**
     * @var string[]
     */
    private $adminControllerPathMap = [
        'blog_post_index',
        'blog_category_index',
        'blog_tag_index',
        'blogauthor_author_index',

        'secondblog_post_index',
        'secondblog_category_index',
        'secondblog_tag_index',
        'secondblogauthor_author_index',

    ];

    /**
     * Massaction constructor.
     * @param Config $config
     * @param StoreManagerInterface $storeManager
     * @param ModuleManager $moduleManager
     */
    public function __construct(
        Config $config,
        StoreManagerInterface $storeManager,
        ModuleManager $moduleManager
    ) {
        $this->config = $config;
        $this->storeManager = $storeManager;
        $this->moduleManager = $moduleManager;
    }

    /**
     * @param $subject
     * @return void
     */
    public function beforeGetItems($subject)
    {
        if (!$this->moduleManager->isEnabled('Magefan_Translation')) {
            $fan = $subject->getRequest()->getFullActionName();

            if ($this->config->isEnabled() && in_array($fan, $this->adminControllerPathMap)) {

                $websites = $this->storeManager->getWebsites(true);
                $groups = $this->storeManager->getGroups();
                $stores = $this->storeManager->getStores();

                $subject->addItem(
                    'mass_translate_store_0',
                    [
                        'label' => __('Auto Translate - "%1"', __('All Store Views')),
                    ]
                );

                foreach ($stores as $store) {
                    $storeId = (int)$store->getId();

                    $websiteName = $websites[$store->getWebsiteId()]->getName() ?? '';
                    $groupName = $groups[$store->getStoreGroupId()]->getName() ?? '';
                    $storeViewName = $store->getName();

                    $label = __('%1 → %2 → %3', $websiteName, $groupName, $storeViewName);

                    $subject->addItem(
                        'mass_translate_store_' . $storeId,
                        [
                            'label' => __('Auto Translate - "%1"', $label),
                        ]
                    );
                }
            }
        }
    }
}
