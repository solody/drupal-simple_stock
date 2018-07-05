<?php

namespace Drupal\simple_stock_fixed\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Ensures product variation SKU uniqueness.
 *
 * @Constraint(
 *   id = "simple_stock_fixed_stock_number",
 *   label = @Translation("Fixed Stock Number.", context = "Validation")
 * )
 */
class FixedStockNumberConstraint extends Constraint {

  public $stockNumber = '库存数值不能小于0';
  public $reduceGreaterThanStock = '预设已售不能大于总库存';

}