<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\AdminUserGuide\Ui\Component\MassAction;

use Magento\Framework\UrlInterface;
use Magento\Cms\Ui\Component\Listing\Column\Cms\Options as StoreOptions;
use Magento\Framework\Phrase;

class Stores implements \JsonSerializable
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var StoreOptions
     */
    protected $storeOptions;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var
     */
    protected $options;

    /**
     * @var
     */
    protected $urlPath;

    /**
     * @var
     */
    protected $paramName;

    /**
     * @var array
     */
    protected $additionalData = [];

    /**
     * @param UrlInterface $urlBuilder
     * @param StoreOptions $storeOptions
     * @param array $data
     */
    public function __construct(
        UrlInterface $urlBuilder,
        StoreOptions $storeOptions,
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->storeOptions = $storeOptions;
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        if ($this->options === null) {
            $this->prepareData();
            $array = $this->storeOptions->toOptionArray();

            $options = $this->flattenStores($array);

            foreach ($options as $optionCode) {
                $this->options[$optionCode['value']] = [
                    'type' => 'storeId_' . $optionCode['value'],
                    'label' => $optionCode['label'],
                ];

                $this->options[$optionCode['value']]['url'] = $this->urlBuilder->getUrl(
                    $this->urlPath
                );
                /*if ($this->urlPath && $this->paramName) {
                    $this->options[$optionCode['value']]['url'] = $this->urlBuilder->getUrl(
                        $this->urlPath,
                        [$this->paramName => $optionCode['value']]
                    );
                }*/

                $this->options[$optionCode['value']] = array_merge_recursive(
                    $this->options[$optionCode['value']],
                    $this->additionalData
                );
            }

            $this->options = array_values($this->options);
        }

        return $this->options;
    }

    /**
     * @param array $stores
     * @param string $prefix
     * @return array
     */
    private function flattenStores(array $stores, string $prefix = ''): array
    {
        $result = [];

        foreach ($stores as $store) {
            // Convert Magento Phrase objects to string if needed
            $label = (string)($store['label'] instanceof \Magento\Framework\Phrase
                ? $store['label']->getText()
                : $store['label']);
            $label = trim($label);

            $value = $store['value'];

            if (is_array($value)) {
                // Recurse into nested structures
                $result = array_merge(
                    $result,
                    $this->flattenStores($value, $prefix ? $prefix . ' → ' . $label : $label)
                );
            } else {
                // Leaf node — add final [value, label] pair
                $result[] = [
                    'value' => $value,
                    'label' => $prefix ? $prefix . ' → ' . $label : $label,
                ];
            }
        }

        return $result;
    }

    /**
     * @return void
     */
    protected function prepareData()
    {
        foreach ($this->data as $key => $value) {
            switch ($key) {
                case "urlPath":
                    $this->urlPath = $value;
                    break;
                case "paramName":
                    $this->paramName = $value;
                    break;
                case "confirm":
                    foreach ($value as $messageName => $message) {
                        $this->additionalData[$key][
                        $messageName
                        ] = (string) new Phrase($message);
                    }
                    break;
                default:
                    $this->additionalData[$key] = $value;
                    break;
            }
        }
    }
}
