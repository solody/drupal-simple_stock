<?php

namespace Drupal\simple_stock\EventSubscriber;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\state_machine\Event\WorkflowTransitionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class OrderSubscriber.
 */
class OrderSubscriber implements EventSubscriberInterface {
  /**
   * {@inheritdoc}
   */
  static function getSubscribedEvents() {
    $events['commerce_order.cancel.pre_transition'] = ['commerce_order_cancel_pre_transition'];
    return $events;
  }

  /**
   * This method is called whenever the commerce_order.cancel.pre_transition event is
   * dispatched.
   *
   * @param WorkflowTransitionEvent $event
   */
  public function commerce_order_cancel_pre_transition(WorkflowTransitionEvent $event) {
    // 订单取消，恢复库存
    $commerce_order = $event->getEntity();
    if ($commerce_order instanceof OrderInterface) {
      foreach ($commerce_order->getItems() as $order_item) {
        _simple_stock_adjust_stock($order_item->getPurchasedEntity(), 'recover', (int)$order_item->getQuantity());
      }
    }
  }

}
