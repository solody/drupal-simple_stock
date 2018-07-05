<?php

namespace Drupal\simple_stock_normal\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Constraint(
 *   id = "simple_stock_normal_stock_number",
 *   label = @Translation("Normal Stock Number.", context = "Validation")
 * )
 */
class NormalStockNumberConstraint extends Constraint {

  public $stockNumber = '库存数值不能小于0';

}