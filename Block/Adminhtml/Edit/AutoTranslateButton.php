<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
declare(strict_types=1);

namespace Magefan\AdminUserGuide\Block\Adminhtml\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magefan\Community\Block\Adminhtml\Edit\GenericButton;
use Magento\Framework\App\RequestInterface;
use Magefan\AdminUserGuide\Model\Config;

class AutoTranslateButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @var RequestInterface
     */
    private $request;


    /**
     * @var
     */
    private $config;

    /**
     * @param Context $context
     * @param RequestInterface $request
     * @param Config $config
     * @param $authorization
     */
    public function __construct(
        Context $context,
        RequestInterface $request,
        Config $config,
        $authorization = null
    ) {
        parent::__construct($context, $authorization);
        $this->request = $request;
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getButtonData()
    {
        $storeId = (int)$this->request->getParam('store') ?: 0;
        $excluded = !$this->config->isEnabled($storeId);

        $data = [
            'label' => __('Auto Translate'),
            'class' => 'mf_auto_translate',
            'on_click' => !$excluded ? 'confirmSetLocation(\'' . __(
                    'To activate this functional you need to install the module.'
                ) . '\', \'' . $this->getControllerUrl() . '\')' : '',
            'sort_order' => 20,
            'disabled' => $excluded,
        ];


        return $data;
    }

    /**
     * Return url of controller
     *
     * @return string
     */
    private function getControllerUrl()
    {
        return 'https://magefan.com/magento-2-translation-extension?utm_source=adminhtml_widget_instance_index&utm_medium=link&utm_campaign=admin-user-guide';
    }
}
