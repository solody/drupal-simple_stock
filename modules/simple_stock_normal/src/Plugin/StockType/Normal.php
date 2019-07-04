<?php

namespace Drupal\simple_stock_normal\Plugin\StockType;

use Drupal\commerce_product\Entity\ProductVariationInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\simple_stock\Plugin\StockTypeBase;

/**
 * @StockType(
 *   id = "normal",
 *   label = @Translation("Normal")
 * )
 */
class Normal extends StockTypeBase {

  public function supportEntity(ContentEntityInterface $entity) {
    return $entity instanceof ProductVariationInterface;
  }

  public function supportEntityType(EntityTypeInterface $entity_type) {
    return $entity_type->id() === 'commerce_product_variation';
  }

  public function stockBaseFieldDefinitions() {

    // stock 库存
    $fields['stock'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Stock'))
      ->setDescription(t('The effective stock that can be sells.'))
      ->setDefaultValue('0')
      ->addConstraint('simple_stock_normal_stock_number')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'number_integer',
        'weight' => 5,
      ])
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => 5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    // stock_order 订单已售
    $fields['stock_order'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Stock sold'))
      ->setDescription(t('Stock that consumed by orders.'))
      ->setDefaultValue('0')
      ->addConstraint('simple_stock_normal_stock_number')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'number_integer',
        'weight' => 5,
      ])
      ->setDisplayOptions('form', [
        'type' => 'readonly_field_widget',
        'weight' => 5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

  public function getEffectiveStock(ContentEntityInterface $entity) {
    $stock = $this->getFieldNumber($entity, 'stock');
    if ($stock < 0) $stock = 0;

    return $stock;
  }

  private function getFieldNumber(ContentEntityInterface $entity, $field_name) {
    return $entity->hasField($field_name) && (int)$entity->get($field_name)->value > 0 ? (int)$entity->get($field_name)->value : 0;
  }

  public function subtractStock(ContentEntityInterface $entity, $number) {
    if ($this->getEffectiveStock($entity) < $number) throw new \Exception('库存不足');

    if ($entity->hasField('stock_order')) {
      $entity->set('stock_order', $this->getFieldNumber($entity, 'stock_order') + $number);
    }

    if ($entity->hasField('stock')) {
      $entity->set('stock', $this->getFieldNumber($entity, 'stock') - $number);
    }

    $entity->save();
  }

  public function recoverStock(ContentEntityInterface $entity, $number) {
    if ($entity->hasField('stock_order')) {
      $stock = $this->getFieldNumber($entity, 'stock_order') - $number;
      if ($stock < 0) $stock = 0;
      $entity->set('stock_order', $stock);
    }

    if ($entity->hasField('stock')) {
      $entity->set('stock', $this->getFieldNumber($entity, 'stock') + $number);
    }

    $entity->save();
  }

  public function getSalesVolume(ContentEntityInterface $entity) {
    if ($entity->hasField('stock_order')) {
      return $entity->get('stock_order')->value;
    }
  }
}
