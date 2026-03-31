<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\TranslationExtra\Plugin\Backend\Magento\Catalog\Block\Adminhtml\Product\Attribute;

use Magefan\TranslationExtra\Model\Config;

class Edit
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    public function beforeSetLayout(
        \Magento\Catalog\Block\Adminhtml\Product\Attribute\Edit $subject,
        $layout
    ) {
        if ($this->config->isEnabled()) {
            $autoTranslationUrl = $subject->getUrl('translationextra/attribute/autoTranslate', ['id' => $subject->getRequest()->getParam('attribute_id')]);

            $subject->addButton(
                'mftranslate_button',
                [
                    'label' => __('Auto Translate'),
                    'on_click' => 'confirmSetLocation(\'' . __(
                            'Are you sure you want to perform Auto Translate?'
                        ) . '\', \'' . $autoTranslationUrl . '\')',
                    'class' => 'mf_auto_translate',
                ],
                10
            );
        }

        return [$layout];
    }
}
