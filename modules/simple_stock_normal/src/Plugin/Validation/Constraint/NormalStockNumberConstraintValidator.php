<?php

namespace Drupal\simple_stock_normal\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * StockNumberConstraintValidator
 */
class NormalStockNumberConstraintValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($items, Constraint $constraint) {
    foreach ($items as $item) {
      // 库存数不可小于0
      if ($item->value < 0) {
        $this->context->addViolation($constraint->stockNumber);
      }
    }
  }
}