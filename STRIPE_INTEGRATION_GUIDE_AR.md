# دليل تكامل بوابة الدفع Stripe - إمبابي كافيه

## 📋 المحتويات
1. [المتطلبات الأساسية](#المتطلبات-الأساسية)
2. [التثبيت](#التثبيت)
3. [الإعداد والتكوين](#الإعداد-والتكوين)
4. [تشغيل الهجرة](#تشغيل-الهجرة)
5. [الاختبار](#الاختبار)
6. [النشر على السيرفر](#النشر-على-السيرفر)
7. [الأسئلة الشائعة](#الأسئلة-الشائعة)

---

## 🎯 المتطلبات الأساسية

قبل البدء، تأكد من توفر التالي:

- ✅ حساب Stripe (تسجيل مجاني على [stripe.com](https://stripe.com))
- ✅ Laravel 11.x
- ✅ PHP 8.2+
- ✅ Composer

---

## 📦 التثبيت

### الخطوة 1: تثبيت Stripe PHP SDK

افتح Terminal واذهب إلى مجلد المشروع، ثم قم بتشغيل:

```bash
composer require stripe/stripe-php
```

### الخطوة 2: التحقق من الملفات الجديدة

يجب أن تجد الملفات التالية في مشروعك:

#### ملفات Backend:
- ✅ `config/stripe.php` - ملف التكوين
- ✅ `app/Services/StripePaymentService.php` - خدمة Stripe
- ✅ `app/Http/Controllers/PaymentController.php` - معالج الدفع
- ✅ `database/migrations/2026_01_22_000001_add_stripe_columns_to_orders_table.php` - Migration

#### ملفات Frontend:
- ✅ `resources/views/checkout/index.blade.php` - صفحة Checkout محدّثة
- ✅ `resources/views/checkout/success.blade.php` - صفحة النجاح محدّثة

#### Routes:
- ✅ تم إضافة routes في `routes/web.php`

---

## ⚙️ الإعداد والتكوين

### الخطوة 1: إنشاء حساب Stripe

1. اذهب إلى [stripe.com](https://stripe.com) وسجّل حساب جديد
2. أكمل عملية التسجيل

### الخطوة 2: الحصول على مفاتيح API

#### للاختبار (Test Mode):

1. اذهب إلى Stripe Dashboard
2. تأكد أنك في **Test Mode** (شاهد زر التبديل في الأعلى)
3. اذهب إلى **Developers → API Keys**
4. انسخ:
   - **Publishable key** (يبدأ بـ `pk_test_`)
   - **Secret key** (يبدأ بـ `sk_test_`) - احذر! هذا سري

### الخطوة 3: إضافة المفاتيح إلى .env

افتح ملف `.env` في مشروعك وأضف في النهاية:

```env
# Stripe API Keys (Test Mode)
STRIPE_KEY=pk_test_XXXXXXXXXXXXXXXXXXXXXX
STRIPE_SECRET=sk_test_XXXXXXXXXXXXXXXXXXXXXX
STRIPE_WEBHOOK_SECRET=
STRIPE_CURRENCY=egp
```

> **⚠️ مهم:** لا تشارك `STRIPE_SECRET` مع أحد ولا ترفعه على GitHub!

### الخطوة 4: إعداد Webhook (للاختبار المحلي)

#### أ) تثبيت Stripe CLI

##### Windows:
```bash
# باستخدام Scoop
scoop install stripe

# أو تحميل مباشر من
# https://github.com/stripe/stripe-cli/releases/latest
```

##### Linux/macOS:
```bash
brew install stripe/stripe-cli/stripe
```

#### ب) تسجيل الدخول
```bash
stripe login
```

#### ج) الاستماع للـ Webhooks محلياً
```bash
stripe listen --forward-to http://localhost:8000/stripe/webhook
```

**سيعطيك webhook signing secret** - انسخه وضعه في `.env`:
```env
STRIPE_WEBHOOK_SECRET=whsec_XXXXXXXXXXXXXXXXXXXXXX
```

---

## 🗄️ تشغيل الهجرة

بعد التأكد من إعداد `.env` بشكل صحيح، قم بتشغيل:

```bash
php artisan migrate
```

هذا سيضيف عمودين جديدين لجدول `orders`:
- `transaction_id` - معرف معاملة Stripe
- `currency` - العملة (افتراضياً EGP)

---

## 🧪 الاختبار

### 1. تشغيل التطبيق

```bash
php artisan serve
```

### 2. تشغيل Stripe Webhook Listener (في terminal منفصل)

```bash
stripe listen --forward-to http://localhost:8000/stripe/webhook
```

### 3. اختبار عملية الدفع الناجحة

1. افتح المتصفح واذهب إلى: `http://localhost:8000`
2. أضف منتجات إلى السلة
3. اذهب إلى صفحة Checkout
4. املأ البيانات المطلوبة
5. اختر **"الدفع بالبطاقة"**
6. أدخل بيانات بطاقة الاختبار:
   ```
   Card Number:    4242 4242 4242 4242
   Expiry Date:    12/34 (أي تاريخ مستقبلي)
   CVC:            123 (أي 3 أرقام)
   ZIP:            12345 (أي رقم)
   ```
7. اضغط "تأكيد الطلب"
8. انتظر loading spinner
9. **يجب أن ترى:**
   - ✅ تحويل لصفحة Success
   - ✅ احتفالية Confetti من الجانبين
   - ✅ SweetAlert2 popup جميل
   - ✅ badge "مدفوع" أخضر

### 4. اختبار عملية الدفع الفاشلة

استخدم بطاقة اختبار فاشلة:
```
Card Number: 4000 0000 0000 0002
```

**يجب أن ترى:**
- ❌ رسالة خطأ واضحة
- ❌ عدم تحويل لصفحة Success

### 5. بطاقات اختبار إضافية

| الغرض | رقم البطاقة |
|-------|-------------|
| ✅ نجاح | 4242 4242 4242 4242 |
| ❌ رفض البطاقة | 4000 0000 0000 0002 |
| ❌ أموال غير كافية | 4000 0000 0000 9995 |
| ⚠️ يتطلب تأكيد 3D Secure | 4000 0027 6000 3184 |

[المزيد من البطاقات](https://stripe.com/docs/testing#cards)

---

## 🚀 النشر على السيرفر

عند النشر على السيرفر الحقيقي (Production):

### 1. تفعيل Live Mode في Stripe

1. اذهب إلى Stripe Dashboard
2. **أكمل تفاصيل Business** (مطلوب للـ Live Mode)
3. بدّل إلى **Live Mode**
4. احصل على Live API Keys من **Developers → API Keys**

### 2. تحديث .env على السيرفر

```env
# Stripe API Keys (Live Mode)
STRIPE_KEY=pk_live_XXXXXXXXXXXXXXXXXXXXXX
STRIPE_SECRET=sk_live_XXXXXXXXXXXXXXXXXXXXXX
STRIPE_WEBHOOK_SECRET=whsec_XXXXXXXXXXXXXXXXXXXXXX
STRIPE_CURRENCY=egp
```

### 3. إعداد Webhook على Stripe Dashboard

> **⚠️ مهم جداً:** لن يعمل الـ Webhook بدون هذا الإعداد على السيرفر!

1. اذهب إلى Stripe Dashboard → **Developers → Webhooks**
2. اضغط **Add endpoint**
3. أدخل URL الخاص بك:
   ```
   https://your-domain.com/stripe/webhook
   ```
4. Select events to listen to:
   - ✅ `payment_intent.succeeded`
   - ✅ `payment_intent.payment_failed`
   - ✅ `payment_intent.canceled`
5. اضغط **Add endpoint**
6. انسخ **Signing secret** (whsec_XXX) وضعه في `.env`

### 4. تشغيل Migration على السيرفر

```bash
php artisan migrate --force
```

### 5. Clear Cache

```bash
php artisan config:cache
php artisan route:cache
```

---

## ❓ الأسئلة الشائعة

### س1: هل بيانات البطاقة تمر عبر سيرفري؟

**لا!** نحن نستخدم Stripe Elements، مما يعني:
- ✅ بيانات البطاقة تُرسل مباشرة من المتصفح إلى Stripe
- ✅ لا تلمس بيانات البطاقة سيرفرك أبداً
- ✅ Stripe-compliant و PCI-DSS معتمد

### س2: كيف أعرف أن الدفع نجح؟

هناك طريقتان:
1. **Webhook:** عند نجاح الدفع، Stripe يرسل `payment_intent.succeeded` إلى سيرفرك، نحن نحدّث الطلب تلقائياً
2. **في قاعدة البيانات:** تحقق من `payment_status = 'paid'` و `transaction_id` موجود

### س3: ماذا لو فشل الـ Webhook؟

- Stripe يعيد المحاولة تلقائياً عدة مرات
- يمكنك مراجعة Webhook logs في Stripe Dashboard
- يمكنك إعادة إرسال event يدوياً من Dashboard

### س4: هل يمكنني استخدام عملات أخرى غير EGP؟

نعم! عدّل `STRIPE_CURRENCY` في `.env`:
```env
STRIPE_CURRENCY=usd  # أو eur, sar, aed, etc.
```

**⚠️ ملاحظة:** [Stripe يدعم +135 عملة](https://stripe.com/docs/currencies)

### س5: كيف أختبر الـ Webhooks محلياً؟

استخدم Stripe CLI:
```bash
stripe listen --forward-to http://localhost:8000/stripe/webhook
```

أو اختبر event معين:
```bash
stripe trigger payment_intent.succeeded
```

### س6: هل رسوم Stripe مدعومة؟

نعم! Stripe يأخذ:
- **2.9% + 30¢** لكل معاملة ناجحة في الوطن العربي
- [تفاصيل التسعير الكاملة](https://stripe.com/pricing)

### س7: كيف أسترجع الأموال (Refund)؟

من Stripe Dashboard:
1. اذهب إلى **Payments**
2. ابحث عن المعاملة
3. اضغط **Refund**

أو برمجياً:
```php
$stripe = new \Stripe\StripeClient(config('stripe.secret'));
$stripe->refunds->create([
    'payment_intent' => 'pi_XXXX',
]);
```

### س8: لا يظهر confetti في صفحة النجاح؟

تأكد من:
- ✅ `payment_status = 'paid'` في قاعدة البيانات
- ✅ فتح Developer Console وشاهد الأخطاء
- ✅ تحميل `canvas-confetti` من CDN

### س9: error: "No API key provided"

تأكد من:
- ✅ `STRIPE_KEY` و `STRIPE_SECRET` موجودان في `.env`
- ✅ تشغيل `php artisan config:cache` بعد تعديل `.env`

### س10: كيف أعرف أن Transaction تمت بنجاح؟

تحقق من:
1. **في Laravel:** `orders` table → `payment_status = 'paid'` و `transaction_id` موجود
2. **في Stripe Dashboard:** اذهب إلى Payments → شاهد Success

---

## 📞 الدعم

إذا واجهت أي مشكلة:

1. **Stripe Documentation:** [stripe.com/docs](https://stripe.com/docs)
2. **Stripe Test Cards:** [stripe.com/docs/testing](https://stripe.com/docs/testing)
3. **Laravel Logs:** `storage/logs/laravel.log`

---

## 🎉 مبروك!

الآن لديك نظام دفع Stripe متكامل وآمن! 🚀

**لا تنسى:**
- 🔒 احفظ `STRIPE_SECRET` بأمان
- 🧪 اختبر جيداً في Test Mode قبل Live Mode
- 📊 راجع Stripe Dashboard بانتظام
- 🎨 استمتع بتجربة المستخدم الرائعة مع Confetti & SweetAlert2!

---

**تم إنشاء هذا الدليل بواسطة Antigravity AI ♥**
