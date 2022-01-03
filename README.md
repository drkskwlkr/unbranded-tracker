# unbranded-tracker
One-page приложение за проследяване на доставките. За простота ще наричам приложението **UT** (съкратено от **Unbranded Tracker**; няма нищо общо с Unreal Tournament, uTorrent или Ути Бъчваров).

UT разпознава пратките на :
- Спиди
- Еконт
- A1 Post
- Лео Експрес
- CVC
- Elta (Гърция) — функционалността е разработена, но още не е добавена

За всеки от изброените куриери UT предоставя хронология на проследяването, чрез която вашите потребители могат да се информират за движението на своите пратки. Поддръжка за други куриери може да бъде реализирана според техническите възможности и интереса. Ако имате интерес към конкретен куриер, питайте.

## Какви са ползите от UT за търговеца
UT служи да показва проследяването на доставките на вашите пратки в самостоятелна страница в рамките на собствения домейн на магазина. По този начин, вместо да губите комуникация с клиентите, можете да запазите връзката с тях, след като обработите и изпратите всяка тяхна поръчка.

Освен проследяването на доставката, можете да направите от тази страница среда за допълнителна маркетингова комуникация: да добавите чат приложение, да показвате upsell/cross-sell предложения, да предлагате отстъпки за бъдещи поръчки, абонамент за email бюлетин и т.н.

Понеже страницата си е ваша, можете да поместите на нея и тракинг код, ad retargeting код, Facebook pixel код и т.н., чрез които допълнително да ангажирате клиента на момента или на по-късен етап. В такъв случай обаче не забравяйте, че трябва да добавите cookie notice и да поискате позволението на клиента!

## Съвместимост
Приложението е универсално и не зависи от конкретна платформа. Можете дори да не разполагате със собствен магазин, а да продавате в OLX или Facebook, и пак да пращате линкове за проследяване от ваш домейн =)

В първоначалното видео представяне написах, че UT не е съвместимо с облачни платформи. Това не е точно така; реално несъвместимост не може да съществува, доколкото UT функционира напълно самостоятелно от търговската платформа. Особеността на облачните платформи е, че те обикновено не позволяват на потребителя да хоства самостоятелно програмен код. Съответно, за да заработи проследяването, трябва да разполагате с отделен хостинг само за UT приложението.

Понеже е малко тъпо да се купува цял самостоятелен хостинг пакет само за хостването на една PHP страница със 100КВ код, бих могъл да ви предложа хостване на приложението на мой сървър срещу символично заплащане, много по-евтино от нормален хостинг план. Свържете се с мен за подробности.

## Инсталация
Трябва да свалите пет файла от GitHub:
```
access.inc.php
config.inc.php
index.php
style.css
track.php
feedback.php
```
[Как се свалят файлове от GitHib (YouTube видео)](https://www.youtube.com/watch?v=GIJdfuAoqFI)

От съображения за леснота, най-уместният начин е да се инсталира в самостоятелен под-домейн на вашия магазин (например tracking.example.com). Ако смятате да изпращате съобщения чрез SMS, препоръчвам да изберете възможно най-къс поддомейн, за да спестите символи (например t.example.com). Файловете, които свалихте, трябва да се поместят в кореновата папка (public_html) на поддомейна.

Възможно е UT да работи и в поддиректория на главния домейн (например example.com/t) паралелно със софтуера на магазина. Този режим на работа обаче изисква допълнителни настройки, които зависят от конкретната платформа и затова не се поддържа официално. Ако искате да настроите UT да работи по този начин, свържете се с мен.

Как се създава нов под-домейн е извън обхвата на настоящата инструкция, но ако не можете да се справите сами, свържете се с мен.

## Актуализиране на инсталацията
Възможно е периодично публикуване на актуализирани версии на приложението. Всяко обновление ще съдържа инструкция кои файлове да свалите повторно, но като правило, обичайно тези обновления ще засягат файловете `access.inc.php`, `style.css` и `track.php`. Обновления по `index.php` са възможни, но би трябвало да са значително по-редки. След промените във версия 0.4, конфигурационият файл `config.inc.php` би трябвало да се променя изключително рядко.

## Допълнителни изисквания
За да ползвате услугата със Спиди, е необходимо да притежавате потребителско име и парола, които са активирани за работа с API на Спиди. Ако използвате интеграция с модула на Спиди за OpenCart, WooCommerce, Prestashop, Magento и т.н., най-вероятно името и паролата, които въвеждате за оторизация там, ще работят и в UT. Ако видите грешка `bg.error.invalid.credentials`, това име и парола няма да ви свършат работа.

В такъв случай е необходимо да се свържете с вашия търговски представител в Спиди и да поискате от него да ви генерира нова двойка потребителско име и парола, които да използвате с UT. От Спиди най-вероятно ще ви попитат за каква платформа ще ползвате новата двойка име/парола, тъй като си водят вътрешна статистика за популярността на платформите. От потребителска гледна точка няма значение какво ще кажете. Можете да отговорите, че го искате за същата платформа, на която ви работи магазинът.

## Настройки
Потребителските настройки се правят през файла [config.inc.php](https://github.com/drkskwlkr/unbranded-tracker/blob/main/config.inc.php). Какво по-конкретно се въвежда, е описано надолу в документа.

Потребителското име и парола за API-то на Спиди се вписват в config.inc.php:
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

**Ново [2021-12-11]**: На същото място, ако желаете да промените настройката по подразбиране, можете да промените този параметър (допустимите стойности са **`bg`** и **`en`**; ако сложите нещо друго тук, ще счупите скрипта):
```
define ('LANGUAGE_DEFAULT',	"bg") ; // Determine default language for return queries. Specify either bg or en (small caps)
```

**Внимание:** Следете стриктно за правилното вмъкване на данните между кавичките. Има разлика между единични и двойни кавички. Една липсваща кавичка може да счупи цялото приложение и да го накара да показва празна страница.

## Употреба
Линковете за проследяване се образуват, като към адреса на приложението се добави параметър p=номер_на_товарителница, т.е. tracking.example.com?p=1234567890. Това е връзката, която можете да изпращате на своите клиенти.

За куриерските услуги, които го поддържат (Спиди, Еконт, A1 Post, CVC) е възможно езикът на статусите да се превключи на английски или за постоянно (като промените `define ('LANGUAGE_DEFAULT', "bg")` на `define ('LANGUAGE_DEFAULT', "en")` в `config.inc.php` или оперативно, като добавите към URL-а параметър lang=en, т.е. tracking.example.com?p=1234567890&lang=en. Първият начин на превключване е за случаите, в които аудиторията ви е предимно чуждестранна; тогава tracking.example.com?p=1234567890 ще връща резултат по подразбиране на английски, който ще може да бъде обърнат на български с добавяне на параметър `lang=bg`.

Ако UT не може да разчете номера на товарителницата, ще издаде съобщение `Не можем да разпознаем куриера по посочения номер на товарителница. Свържете се с нас за повече информация.` Самата фраза `Свържете се с нас` съдържа връзка към страницата за контакти в сайта, която сте посочили в `config.inc.php` (`SITE_CONTACT_URL`).

## Алтернативен метод на употреба
Възможно е да съкратите допълнително дължината на тракинг адреса чрез скриване на GET параметъра, който предава номера на товарителницата. Тоест, вместо сегашното track.example.com/?p=123456789, URL-ът може да изглежда значително по-спретнато: track.example.com/123456789.

Това решение има съществени ограничения спрямо оригиналното поведение на скрипта; прочетете ги внимателно:

1. Файловете .htaccess се разпознават само от Apache и OpenLiteSpeed/LSWE. За да работи с Ngnix, трябва да конвертирате инструкциите за пренаписване в техния формат (има безплатни инструменти онлайн за това).
2. Ползвайте го задължително, ама задължително и само ако имате настроен под-домейн за тракинг. Ако инсталирате скрипта като под-директория на главния домейн (което по принцип не препоръчвам!), със сигурност няма да работи и има голям шанс да счупи сайта.
3. Губи се обратна съвместимост: заявките в стария формат на URI-то с видим GET параметър няма да се разпознават. Мисля, че този проблем може да бъде решен с малко повече мислене и четене на документацията, но засега нямам планове в тази посока.
4. Губи се възможността да се управлява езикът на статусите чрез GET параметър. Все още имате възможност да зададете език по подразбиране (български или английски) през конфигурационния файл и всички статуси ще бъдат на него.

Ако сте окей с всичко това, свалете файла `.htaccess.DISABLED` и го преименувайте на `.htaccess` (не пропускайте точката отпред). Тествайте тракера. Ако забелязвате проблем или виждате грешка HTTP 500, изтрийте този файл и тракерът ще възстанови първоначалното си поведение.

## Демонстрация
Ако искате да видите как изглежда приложението без да сваляте и хоствате файлове, можете да кликнете върху някой от следните линкове:
- [Примерен резултат с Еконт](https://utdemo.webstage.dev/1054219036007) 
- [Примерен резултат с A1 Post](https://utdemo.webstage.dev/RS082678975DE)
- [Примерен резултат с Leo Expres](https://utdemo.webstage.dev/69904612)
- [Примерен резултат със CVC](https://utdemo.webstage.dev/00090798)
- [Примерен резултат с Български пощи](https://utdemo.webstage.dev/CP112509907BG)
- [Примерен резултат с Hellenic Post](https://utdemo.webstage.dev/HB740041740GR)

Примерен резултат за Спиди не мога да предоставя, понеже методът за извличане на данни чрез API при тях изисква оторизация и резултатът е ограничен до товарителници, създадени със същия API потребител. Но страницата изглежда по абсолютно същия начин.

## Недостатъци тип "Тука е така" (т.е. неща, които нямам планове да променям)
Ако UT се извика без параметър, ще издаде съобщение `Необходимо е да подадете заявка с номер на товарителница`. Това е малко тъпо поведение, доколкото пристигналият на тази страница посетител не би могъл да знае **как** да подаде заявка. Но от друга страна, няма нормална причина, поради която потребител да пристига на тази страница без да е проследил връзка, окомплектована с номер на товарителница. Ако някой много държи да модифицира това поведение, трябва да пипне съдържанието на посочената else клауза в `track.php`:
```
/* Determine parcel sender */
if ( isset ($_GET['p'] ) && !empty ($_GET['p'] ) )
{
( ... )
} else {
	/* Replace echo statement with something else if you want to modify */
	/* page behavior for people who land on page without a tracking no. */
	echo '<h2>Необходимо е да подадете заявка с номер на товарителница</h2>' ;
	die() ;
}
```
Промяната на това поведение не ми е приоритет на момента, но е относително просто да се направи така, че вместо да издава грешка при идването на такъв трафик, приложението да препраща към друга страница (например, заглавната страница на сайта на търговеца).

Друг недостатък е свързан с локализацията. Дори да превключите езика на статусите от български на английски, текстовете в началото на страницата ("Информация за движението на Вашата пратка" и "Доставката се изпълнява чрез (Еконт|Спиди|whatever). Хронология на събитията:" няма да се променят. Ако работите предимно на английски, можете да си ги преведете сами. Ако работите поравно на български и английски, можете да ги махнете.

## За въпроси, мнения, препоръки и т.н. контакти
Facebook група: www.facebook.com/groups/unbranded.tracker/
FB Messenger: https://m.me/ivan.webstage

За предпочитане е да задавате въпроси и да изразявате мнения в групата. Използвайте личниия контакт в Messenger само по теми, които не искате да бъдат публично достъпни (например, ако искате да ви настроя сайта да работи с UT, няма нужда да ми изпращате паролата за хостинг акаунта в групата...) Моля, когато контактувате с мен във Facebook Messenger, да започвате първоначалната комуникация с цялостния въпрос, който искате да зададете. Не отговарям на заявки за чат тип "Здравей" и "Ko pr". Благодаря за разбирането!
