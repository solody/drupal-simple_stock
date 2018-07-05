# simple_stock

一个简单的库存系统，支持两种模式：
- normal 一般模式

  包含 stock stock_order 两个字段。
  stock 是即时有效库存。
  stock_order 是订单已销售的记数。

- fixed 固定库存模式

  包含 stock stock_reduce stock_order，3个字段。
  stock 是固定的产品总库存，一般不变。
  stock_reduce 是外部系统已销售的库存。
  stock_order 是本系统订单已销售的记数。
  
  有效库存为 3者之差，即： stock - stock_reduce - stock_order