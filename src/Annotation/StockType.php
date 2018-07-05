<?php

namespace Drupal\simple_stock\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Stock type item annotation object.
 *
 * @see \Drupal\simple_stock\Plugin\StockTypeManager
 * @see plugin_api
 *
 * @Annotation
 */
class StockType extends Plugin {


  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The label of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

}
