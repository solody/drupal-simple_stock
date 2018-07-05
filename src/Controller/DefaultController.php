<?php

namespace Drupal\simple_stock\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\simple_stock\Plugin\StockTypeManager;

/**
 * Class DefaultController.
 */
class DefaultController extends ControllerBase {

  /**
   * Drupal\simple_stock\Plugin\StockTypeManager definition.
   *
   * @var \Drupal\simple_stock\Plugin\StockTypeManager
   */
  protected $pluginManagerStockType;

  /**
   * Constructs a new DefaultController object.
   */
  public function __construct(StockTypeManager $plugin_manager_stock_type) {
    $this->pluginManagerStockType = $plugin_manager_stock_type;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.stock_type')
    );
  }

  /**
   * Hello.
   *
   * @return string
   *   Return Hello string.
   */
  public function hello($name) {
    $entity_type = \Drupal::entityTypeManager()->getDefinition('commerce_product_variation');

    $fields = [];
    /** @var \Drupal\simple_stock\Plugin\StockTypeManager $stock_type_manager */
    $stock_type_manager = \Drupal::getContainer()->get('plugin.manager.stock_type');
    foreach ($stock_type_manager->getDefinitions() as $plugin_id => $plugin_definition) {
      print_r($plugin_id);
      print_r($plugin_definition);
      /** @var \Drupal\simple_stock\Plugin\StockTypeInterface $stock_type */
      $stock_type = $stock_type_manager->createInstance($plugin_id);
      if ($stock_type->supportEntityType($entity_type)) {
        $fields = $stock_type->stockBaseFieldDefinitions() + $fields;
      }
    }

    print_r($fields);
    exit();

    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: hello with parameter(s): $name'),
    ];
  }

}
