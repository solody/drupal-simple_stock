<?php

namespace Drupal\simple_stock_fixed\Plugin\StockType;

use Drupal\aiqilv_product\Entity\BookingUnitInterface;
use Drupal\commerce_product\Entity\ProductVariationInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\simple_stock\Plugin\StockTypeBase;

/**
 * @StockType(
 *   id = "fixed",
 *   label = @Translation("Fixed")
 * )
 */
class Fixed extends StockTypeBase {

  public function supportEntity(ContentEntityInterface $entity) {
    return $entity instanceof ProductVariationInterface || $entity instanceof BookingUnitInterface;
  }

  public function supportEntityType(EntityTypeInterface $entity_type) {
    return $entity_type->id() === 'commerce_product_variation' || $entity_type->id() === 'booking_unit';
  }

  public function stockBaseFieldDefinitions() {

    // stock 库存
    $fields['stock'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('总库存'))
      ->setDescription(t('库存总量，商家拥用的总货量，系统会以此总量减去【预设已售】和【订单已售】而得出有效库存。'))
      ->setDefaultValue(0)
      ->addConstraint('simple_stock_fixed_stock_number')
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

    // stock_reduce 预设已售
    $fields['stock_reduce'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('预设已售'))
      ->setDescription(t('外部系统已经销售的库存，计算有效库存的变量之一。'))
      ->setDefaultValue(0)
      ->addConstraint('simple_stock_fixed_stock_number')
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
      ->setLabel(t('订单已售'))
      ->setDescription(t('本系统中的订单已销售掉的库存，计算有效库存的变量之一。'))
      ->setDefaultValue(0)
      ->addConstraint('simple_stock_fixed_stock_number')
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
    $stock_reduce = $this->getFieldNumber($entity, 'stock_reduce');
    $stock_order = $this->getFieldNumber($entity, 'stock_order');

    $effective_stock = $stock - $stock_reduce - $stock_order;
    if ($effective_stock < 0) $effective_stock = 0;

    return $effective_stock;
  }

  private function getFieldNumber(ContentEntityInterface $entity, $field_name) {
    return $entity->hasField($field_name) && (int)$entity->get($field_name)->value > 0 ? (int)$entity->get($field_name)->value : 0;
  }

  public function subtractStock(ContentEntityInterface $entity, $number) {
    if ($this->getEffectiveStock($entity) < $number) throw new \Exception('库存不足');

    if ($entity->hasField('stock_order')) {
      $entity->set('stock_order', $this->getFieldNumber($entity, 'stock_order') + $number);
      $entity->save();
    }
  }

  public function recoverStock(ContentEntityInterface $entity, $number) {
    if ($entity->hasField('stock_order')) {
      $stock = $this->getFieldNumber($entity, 'stock_order') - $number;
      if ($stock < 0) $stock = 0;
      $entity->set('stock_order', $stock);
      $entity->save();
    }
  }

  public function getSalesVolume(ContentEntityInterface $entity) {
    if ($entity->hasField('stock_order')) {
      return $entity->get('stock_order')->value;
    }
  }
}