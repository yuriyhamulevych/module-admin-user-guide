<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

namespace Magefan\TranslationExtra\Plugin\Magefan\TranslationPlus\Block\Adminhtml\Translate\Edit\Form;

use Magefan\TranslationExtra\Model\Config;
use Magento\Framework\View\Asset\Repository;

class Attributes
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Repository
     */
    protected $assetRepo;

    /**
     * @param Config $config
     * @param Repository $assetRepo
     */
    public function __construct(
        Config $config,
        Repository $assetRepo
    ) {
        $this->config = $config;
        $this->assetRepo = $assetRepo;
    }

    /**
     * @param $subject
     * @param $result
     * @return array|mixed|string|string[]
     */
    public function afterToHtml($subject, $result)
    {
        $storeId = $subject->getStore()->getId();

        if (!$this->config->isEnabled()
            || $this->config->getDefaultLanguageCodeByStoreId($storeId) == $this->config->getTranslateFromLanguageCode()
            || (int)$storeId === 0
        ) {
            return $result;
        }

        $langCode = $this->config->getLocaleByStoreId($storeId);

        $isExcluded = $this->config->isStoreExcludedFromAutoTranslation($storeId);
        $disabled = $isExcluded ? 'disabled' : '';
        $title  = $isExcluded ? __('This store is excluded from auto translation') : 'Auto Translate';

        $img = '<img src="' . $this->assetRepo->getUrl('Magefan_TranslationExtra::images/translate_icon.png') .
            '" class="mf-translate-icon"
            width="50" height="50"
            alt="' . $title . '"
            title="' . $title . '" >';

        $result = str_replace(
            '</fieldset>',
            '<input name="'. $storeId .'[locale_code]" class="hidden mf_locale_code" value="' . $langCode .'">
                     <div class="auto-translate ' . $disabled . '">' . $img . '</div>
                </fieldset>',
            $result
        );

        return $result;
    }
}
