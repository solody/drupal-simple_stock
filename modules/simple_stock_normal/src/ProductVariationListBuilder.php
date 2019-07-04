<?php

namespace Drupal\simple_stock_normal;

use Drupal\Core\Entity\EntityInterface;

/**
 * Defines the list builder for product variations.
 */
class ProductVariationListBuilder extends \Drupal\commerce_product\ProductVariationListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header = [];
    foreach (parent::buildHeader() as $key => $item) {
      $header[$key] = $item;
      if ($key === 'price') {
        $header['stock'] = $this->t('Stock');
        $header['stock_order'] = $this->t('Stock sold');
      }
    }

    return $header;
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row = [];
    foreach (parent::buildRow($entity) as $key => $item) {
      $row[$key] = $item;
      if ($key === 'price') {
        $row['stock'] = $entity->stock->view(['label' => 'hidden']);
        $row['stock_order'] = $entity->stock_order->view(['label' => 'hidden']);
      }
    }

    return $row;
  }

}
