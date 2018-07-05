<?php

namespace Drupal\simple_stock_fixed\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * StockNumberConstraintValidator
 */
class FixedStockNumberConstraintValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($items, Constraint $constraint) {
    foreach ($items as $item) {
      // 库存数不可小于0
      if ($item->value < 0) {
        $this->context->addViolation($constraint->stockNumber);
      }
      // 预设已售 不能大于总库存
      if ($this->context->getPropertyName() === 'stock_reduce') {
        if ($item->value > $item->getParent()->getParent()->getValue()->get('stock')->value) {
          $this->context->addViolation($constraint->reduceGreaterThanStock);
        }
      }
    }
  }
}