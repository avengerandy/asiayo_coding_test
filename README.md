# AsiaYo - Mid Backend Engineer

## 資料庫測驗

### 題⽬⼀

```SQL
SELECT orders.bnb_id AS bnb_id, bnbs.name AS bnb_name, SUM(orders.amouont) AS may_amount
FROM bnbs, orders
WHERE
    orders.bnb_id = bnbs.id AND
    orders.currency = 'TWD' AND (
        orders.created_at
        BETWEEN '2023-05-01 00:00:00'
        AND '2023-05-31 23:59:59'
    )
GROUP BY orders.bnb_id
ORDER BY may_amount DESC
LIMIT 10
```

### 題⽬二

#### 是否有 index

orders table 的 currency 以及 created_at 都在 WHERE 篩選條件中，會先檢查這兩個欄位是否有 index。

沒有 index 的話就要考慮建立，但此表通常是已經很大才會慢，直接新增可能會卡住、影響線上服務，需往不停機新增 index、online index update 等方向思考。

有的 index 話使用 EXPLAIN 語法確認該 SQL 有使用到 index，若沒有使用就需要根據情況看為什麼不使用，必要時使用 FORCE INDEX 等語法強制使用。

bnbs.id、orders.bnb_id 也需要檢查，但我想他們應該會是主鍵跟外鍵，已有 index 的機率非常高。

#### 過濾量

過濾量大條件放在前方讓 WHERE 先篩選，我預想 currency 應該會過濾比較多資料所以放在前方，但實際上還要看表內的資料而定。

#### JOIN 改 subquery

orders 數量應該是遠超 bnbs，且 WHERE 與 GROUP BY 的條件都在 orders 身上就可以完成。

先對 orders 做含 WHERE GROUP BY 的 subquery 生成較小的佔存表，再在這張表上與 bnbs JOIN，量大的時候這個做法有機會比直接 JOIN 兩張表的原 query 更快。

#### 系統架構層面優化

根據情況可以讀寫分離、叢集、partitioning 來加速，不過這個部分超出 SQL tuning 的範圍了。

#### 業務層面優化

確認此查詢的應用場景，若對更新率的要求不高，或是主要運作在後台報表查詢舊資料，也許有機會做快取、crontab 等其他操作，不過這個部分超出 SQL tuning 的範圍了。

## API 實作測驗

### docker

```
docker-compose up
```

### testing

#### Unit

```
php artisan test --coverage --testsuite Unit
```

```
   PASS  Tests\Unit\OrderControllerTest
  ✓ index call order request and order service than return response json                   0.11s
  ✓ index call order request and order service than return error message when order servi… 0.01s

   PASS  Tests\Unit\OrderRequestTest
  ✓ authorize method returns true                                                          0.02s
  ✓ rules method returns rules                                                             0.01s
  ✓ validator return validator make                                                        0.01s

   PASS  Tests\Unit\CheckerTest
  ✓ error message is empty                                                                 0.02s
  ✓ check call next when handle return true and next is not null                           0.01s
  ✓ check not call next when handle return true and next is null                           0.01s
  ✓ check throw exception when handle return false                                         0.01s

   PASS  Tests\Unit\NonEngilshTest
  ✓ error message is non english                                                           0.02s
  ✓ check pass when name is english only                                                   0.01s
  ✓ check fail when name is contains non english                                           0.01s

   PASS  Tests\Unit\NotCapitalizedTest
  ✓ error message is not capitalized                                                       0.02s
  ✓ check pass when name is capitalized                                                    0.01s
  ✓ check fail when name is not capitalized                                                0.01s

   PASS  Tests\Unit\OverPriceTest
  ✓ error message is over price                                                            0.02s
  ✓ check pass when price not greater than 2000                                            0.01s
  ✓ check fail when price greater than 2000                                                0.01s

   PASS  Tests\Unit\WrongCurrencyFormatTest
  ✓ error message is wrong currency format                                                 0.02s
  ✓ check pass when currency format correct                                                0.02s
  ✓ check fail when currency format wrong                                                  0.01s

   PASS  Tests\Unit\OrderCheckerChainFactoryTest
  ✓ create order checker chain                                                             0.02s

   PASS  Tests\Unit\OrderTransformerTest
  ✓ not transform data when currency is not usd                                            0.02s
  ✓ transform data when currency is usd                                                    0.01s

   PASS  Tests\Unit\OrderServiceTest
  ✓ transform call order checker chain and order transformer                               0.02s

  Tests:    25 passed (40 assertions)
  Duration: 0.55s

  Exceptions/OrderCheckerException ...................................................... 100.0%
  Http/Controllers/Controller ........................................................... 100.0%
  Http/Controllers/OrderController ...................................................... 100.0%
  Http/Requests/OrderRequest ............................................................ 100.0%
  Order/OrderChecker/Checker ............................................................ 100.0%
  Order/OrderChecker/NonEngilsh ......................................................... 100.0%
  Order/OrderChecker/NotCapitalized ..................................................... 100.0%
  Order/OrderChecker/OverPrice .......................................................... 100.0%
  Order/OrderChecker/WrongCurrencyFormat ................................................ 100.0%
  Order/OrderCheckerChainFactory ........................................................ 100.0%
  Order/OrderCurrency ................................................................... 100.0%
  Order/OrderTransformer ................................................................ 100.0%
  Providers/AppServiceProvider .......................................................... 100.0%
  Services/OrderService ................................................................. 100.0%
  ──────────────────────────────────────────────────────────────────────────────────────────────
                                                                                  Total: 100.0 %
```

#### Feature

```
php artisan test --coverage --testsuite Feature
```

```
   PASS  Tests\Feature\OrderTest
  ✓ order success return same data when currency is not usd                                0.11s
  ✓ order format wrong than return error                                                   0.02s
  ✓ order name is contains non english than return error                                   0.02s
  ✓ order name is not capitalized than return error                                        0.01s
  ✓ order price greater than 2000 than return error                                        0.01s
  ✓ order currency format wrong than return error                                          0.01s
  ✓ order success return transform data when currency is usd                               0.02s

  Tests:    7 passed (14 assertions)
  Duration: 0.23s

  Exceptions/OrderCheckerException ...................................................... 100.0%
  Http/Controllers/Controller ........................................................... 100.0%
  Http/Controllers/OrderController ...................................................... 100.0%
  Http/Requests/OrderRequest ............................................................ 100.0%
  Order/OrderChecker/Checker ............................................................ 100.0%
  Order/OrderChecker/NonEngilsh ......................................................... 100.0%
  Order/OrderChecker/NotCapitalized ..................................................... 100.0%
  Order/OrderChecker/OverPrice .......................................................... 100.0%
  Order/OrderChecker/WrongCurrencyFormat ................................................ 100.0%
  Order/OrderCheckerChainFactory ........................................................ 100.0%
  Order/OrderCurrency ................................................................... 100.0%
  Order/OrderTransformer ................................................................ 100.0%
  Providers/AppServiceProvider .......................................................... 100.0%
  Services/OrderService ................................................................. 100.0%
  ──────────────────────────────────────────────────────────────────────────────────────────────
                                                                                  Total: 100.0 %
```

### 設計模式

#### Chain Of Responsibility

訂單有四個驗證規則，使用 Chain Of Responsibility 將此規則們集合成一個 Chain。

* client 不需要知道完整的驗證規則，呼叫 check method 即可。
* 如果有要新增或移除規則，只需要調整 Chain 的組合。
* 如果有要修改規則，只需要調整該規則對應的 Checker 即可。

```PHP
namespace App\Order\OrderChecker;

use App\Exceptions\OrderCheckerException;

abstract class Checker
{
    protected $nextChecker = null;
    public $errorMessage = '';

    abstract protected function handle(array $orderData): bool;

    public function check(array $orderData): void
    {
        $result = $this->handle($orderData);
        if (!$result) {
            $this->throwException();
        }
        if ($result && !is_null($this->nextChecker)) {
            $this->nextChecker->check($orderData);
        }
    }

    public function setNext(Checker $checker): void
    {
        $this->nextChecker = $checker;
    }

    protected function throwException(): void
    {
        throw new OrderCheckerException($this->errorMessage);
    }
}
```

```PHP
namespace App\Order\OrderChecker;

class NonEngilsh extends Checker
{
    public $errorMessage = 'Name contains non-English characters';

    protected function handle(array $orderData): bool
    {
        $pattern = '/^[A-Za-z\s]+$/';
        $isMatch = preg_match($pattern, $orderData['name']);
        return $isMatch;
    }
}
```

#### Factory (or Builder)

Chain 的組合較複雜且需要操作多個 Checker 子類別，所以設計一個工廠來負責這件事。

* client 不需要如何組合 Chain，呼叫 check method 即可。
* 如果有要新增其他種類的 Chain 或調整 Chain，只需要調整工廠。

雖然我一開始想到的是工廠，但如果存在各種不同的 Chain 組合，它就更像 Builder pattern，可以根據需求組合不一樣的 Chain 提供給 Client。

```PHP
class OrderCheckerChainFactory
{
    private $nonEngilsh;
    private $notCapitalized;
    private $overPrice;
    private $wrongCurrencyFormat;

    public function __construct(NonEngilsh $nonEngilsh, NotCapitalized $notCapitalized, OverPrice $overPrice, WrongCurrencyFormat $wrongCurrencyFormat)
    {
        $this->nonEngilsh = $nonEngilsh;
        $this->notCapitalized = $notCapitalized;
        $this->overPrice = $overPrice;
        $this->wrongCurrencyFormat = $wrongCurrencyFormat;
    }

    public function create(): Checker
    {
        $this->nonEngilsh->setNext($this->notCapitalized);
        $this->notCapitalized->setNext($this->overPrice);
        $this->overPrice->setNext($this->wrongCurrencyFormat);
        return $this->nonEngilsh;
    }
}
```

資料轉換的 `App\Order\OrderTransformer` 也能仿造驗證功能的設計，不過需求上轉換只有一個動作，暫時只有將其獨立出來而已。

### SOLID

#### Single Responsibility Principle

在這份考題中，大部分 class 在功能上都是單一的，專注做一件事。

|class|職責|
|-|-|
|App\Http\Controllers\OrderController|Request、Response|
|App\Http\Requests\OrderRequest|驗證訂單 JSON 結構|
|App\Services\OrderService|使用 CheckerChain 與 OrderTransformer 驗證與轉換訂單|
|App\Order\OrderCurrency|訂單貨幣、匯率相關常數|
|App\Order\OrderTransformer|轉換訂單貨幣、匯率|
|App\Order\OrderCheckerChainFactory|組合 CheckerChain|
|App\Order\OrderChecker\Checker|父 Checker，負責讓 Chain 可以串接起來|
|App\Order\OrderChecker\NonEngilsh|驗證訂單名稱是否有非英文|
|App\Order\OrderChecker\NotCapitalized|驗證訂單名稱是否字首大寫|
|App\Order\OrderChecker\OverPrice|驗證訂單價格是否超過 2000|
|App\Order\OrderChecker\WrongCurrencyFormat|驗證訂單貨幣格式|
|App\Exceptions\OrderCheckerException|Checker 未通過時會丟出的錯誤|

#### The Open/Closed Principle

我認為訂單驗證規則使用的 Chain Of Responsibility，有符合這項原則，擴展新的驗證規則是不需要修改既有的其他規則的。

#### Liskov Substitution principle

訂單驗證中，所有繼承 Checker 的子 class 皆可以在程式中被當作 Checker 本身來使用。子 class 並沒有破壞 Checker 的原本行為。

#### Dependency inversion principle

Laravel 本身就有提供 IoC 容器，且整個框架的運作也幾乎都是採依賴注入的方式在運作。在這份考題中，除了測試程式碼外，所有的依賴 class 都是由 Laravel IoC 容器幫忙注入來達到依賴反轉。
