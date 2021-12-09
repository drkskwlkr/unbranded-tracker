# unbranded-tracker
One-page приложение за проследяване на доставките. Съвместимо със Спиди и Еконт (други куриери TBDL). За простота ще наричам приложението UT (от Unbranded Tracker; няма нищо общо с Unreal Tournament или uTorrent).

UT служи да показва проследяването на доставките на вашите пратки в самостоятелна страница в рамките на собствения домейн на магазина. По този начин, вместо да губите комуникация с клиентите, можете да запазите връзката с тях, след като обработите и изпратите всяка тяхна поръчка.

Освен проследяването на доставката, можете да направите от тази страница среда за допълнителна маркетингова комуникация: да добавите чат приложение, да показвате upsell/cross-sell предложения, да предлагате отстъпки за бъдещи поръчки, абонамент за email бюлетин и т.н.

Понеже страницата си е ваша, можете да поместите на нея и тракинг код, ad retargeting код, Facebook pixel код и т.н., чрез които допълнително да ангажирате клиента на момента или на по-късен етап. В такъв случай обаче не забравяйте, че трябва да добавите cookie notice и да поискате позволението на клиента!

## Съвместимост
Приложението е универсално и не зависи от конкретна платформа. Можете дори да не разполагате със собствен магазин, а да продавате в OLX или Facebook, и пак да пращате линкове за проследяване от ваш домейн =)

В първоначалното видео представяне написах, че UT не е съвместимо с облачни платформи. Това не е точно така; реално несъвместимост не може да съществува, доколкото UT функционира напълно самостоятелно от търговската платформа. Особеността на облачните платформи е, че те обикновено не позволяват на потребителя да хоства самостоятелно програмен код. Съответно, за да заработи проследяването, трябва да разполагате с отделен хостинг само за UT приложението. Понеже е малко тъпо да се купува цял самостоятелен хостинг пакет само за хостването на една PHP страница със 100КВ код, бих могъл да ви предложа хостване на приложението на мой сървър срещу символично заплащане, много по-евтино от нормален хостинг план. Свържете се с мен за подробности.

## Инсталация
От съображения за леснота, най-уместният начин е да се инсталира в самостоятелен под-домейн на вашия магазин (например tracking.example.com). Ако смятате да изпращате съобщения чрез SMS, препоръчвам да изберете възможно най-къс поддомейн, за да спестите символи (например t.example.com).

Възможно е UT да работи и в поддиректория на главния домейн (например example.com/t) паралелно със софтуера на магазина. Този режим на работа обаче изисква допълнителни настройки, които зависят от конкретната платформа и затова не се поддържа официално. Ако искате да настроите UT да работи по този начин, свържете се с мен.

Как се създава нов под-домейн е извън обхвата на настоящата инструкция, но ако не можете да се справите сами, свържете се с мен.

## Допълнителни изисквания
За да ползвате услугата със Спиди, е необходимо да притежавате потребителско име и парола, които са активирани за работа с API на Спиди. Ако използвате интеграция с модула на Спиди за OpenCart, WooCommerce, Prestashop, Magento и т.н., най-вероятно името и паролата, които въвеждате за оторизация там, ще работят и в UT. Ако видите грешка `bg.error.invalid.credentials`, това име и парола няма да ви свършат работа.

В такъв случай е необходимо да се свържете с вашия търговски представител в Спиди и да поискате от него да ви генерира нова двойка потребителско име и парола, които да използвате с UT. От Спиди най-вероятно ще ви попитат за каква платформа ще ползвате новата двойка име/парола, тъй като си водят вътрешна статистика за популярността на платформите. От потребителска гледна точка няма значение какво ще кажете. Можете да отговорите, че го искате за същата платформа, на която ви работи магазинът.

Потребителското име и парола за API-то на Спиди се вписват във файла [config.inc.php](https://github.com/drkskwlkr/unbranded-tracker/blob/main/config.inc.php):
```
define ('SPEEDY_USER', "") ;
define ('SPEEDY_PASS', "") ;
```

В същия файл е необходимо да зададете и следните детайли:
```
# Administrative
define ('SITE_TITLE',       "") ;   // Insert tracking website name, e.g. define ('SITE_TITLE', "Магазин Example.com: движение на доставките") ;
define ('SITE_URL',         "") ;   // Insert tracking website address including protocol, e.g. define ('SITE_URL', "https://tracking.example.com") ;
define ('SITE_CONTACT_URL', "") ;   // Insert contact website address including protocol, e.g. define ('SITE_CONTACT_URL', "https://tracking.example.com/contact") ;
```
**Внимание:** Следете стриктно за правилното вмъкване на данните между кавичките. Има разлика между единични и двойни кавички. Една липсваща кавичка може да счупи цялото приложение и да го накара да показва празна страница.

## Употреба
Линковете за проследяване се образуват, като към адреса на приложението се добави параметър p=номер_на_товарителница, т.е. tracking.example.com?p=1234567890. Това е връзката, която можете да изпращате на своите клиенти.

Ако UT не може да разчете номера на товарителницата, ще издаде съобщение `Не можем да разпознаем куриера по посочения номер на товарителница. Свържете се с нас за повече информация.` Самата фраза `Свържете се с нас` съдържа връзка към страницата за контакти в сайта, която сте посочили в `config.inc.php` (`SITE_CONTACT_URL`).

Ако UT се извика без параметър, ще издаде съобщение `Необходимо е да подадете заявка с номер на товарителница`. Това е малко тъпо поведение, доколкото пристигналият на тази страница посетител не би могъл да знае **как** да подаде заявка. Но от друга страна, няма нормална причина, поради която потребител да пристига на тази страница без да е проследил връзка, окомплектована с номер на товарителница. По-нататък ще променя това поведение. Ако някой много държи да си го модифицира отсега, трябва да пипне в подчертания участък на кода в `track.php`:
```
if ( isset ($_GET['p'] ) && !empty ($_GET['p'] ) )
{
  $parcel_id = htmlspecialchars($_GET['p']) ;
  $cid_len   = strlen($parcel_id) ;
  $cid_start = substr($parcel_id, 0, 1) ;
} else {

  /* Replace echo statement with something else if you want to modify */
  /* page behavior for people who land on page without a tracking no. */
  echo '<h2>Необходимо е да подадете заявка с номер на товарителница</h2>' ;

  die() ;
}
```
Промяната на това поведение не ми е приоритет на момента, но е относително просто да се направи така, че вместо да издава грешка при идването на такъв трафик, приложението да препраща към друга страница (например, заглавната страница на сайта на търговеца). Дайте сигнал, ако считате, че това трябва да бъде направено.

## За въпроси, мнения, препоръки и т.н. контакти
Facebook група: www.facebook.com/groups/unbranded.tracker/
FB Messenger: https://m.me/ivan.webstage

За предпочитане е да задавате въпроси и да изразявате мнения в групата. Използвайте личниия контакт в Messenger само по теми, които не искате да бъдат публично достъпни (например, ако искате да ви настроя сайта да работи с UT, няма нужда да ми изпращате паролата за хостинг акаунта в групата...) Моля, когато контактувате с мен във Facebook Messenger, да започвате първоначалната комуникация с цялостния въпрос, който искате да зададете. Не отговарям на заявки за чат тип "Здравей" и "Ko pr". Благодаря за разбирането!
