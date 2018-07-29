<?php

namespace Drupal\simple_stock\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines an interface for Stock type plugins.
 */
interface StockTypeInterface extends PluginInspectionInterface {
  public function supportEntity(ContentEntityInterface $entity);
  public function supportEntityType(EntityTypeInterface $entity_type);
  public function stockBaseFieldDefinitions();
  public function getEffectiveStock(ContentEntityInterface $entity);
  public function getSalesVolume(ContentEntityInterface $entity);
  public function subtractStock(ContentEntityInterface $entity, $number);
  public function recoverStock(ContentEntityInterface $entity, $number);
}
