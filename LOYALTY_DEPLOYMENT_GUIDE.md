# 🚀 Production Deployment Guide - Loyalty System

## خطوات التطبيق على Production

### 1️⃣ تشغيل الـ Migrations (إزالة الحقول القديمة)

```bash
# إذا كنت تستخدم Docker
docker-compose exec app php artisan migrate

# أو مباشرة
php artisan migrate
```

**ماذا يفعل هذا؟**
- يحذف حقول `name_ar` و `description_ar` من جداول نظام الولاء
- آمن 100% - لن يحذف أي بيانات موجودة

---

### 2️⃣ تشغيل الـ Seeder (إضافة البيانات الافتراضية)

```bash
# الطريقة الآمنة - seeder محدد فقط
docker-compose exec app php artisan db:seed --class=LoyaltySystemSeeder

# أو بدون Docker
php artisan db:seed --class=LoyaltySystemSeeder
```

**ماذا يفعل هذا؟**
- يضيف 6 قواعد نقاط افتراضية (Point Rules)
- يضيف 4 مستويات ولاء (Loyalty Tiers): Bronze, Silver, Gold, Platinum
- يضيف 6 مكافآت افتراضية (Loyalty Rewards)
- **آمن 100%** - يستخدم `firstOrCreate` لتجنب التكرار

---

### 3️⃣ التحقق من النتائج

```bash
# دخول لـ Laravel Tinker للتحقق
docker-compose exec app php artisan tinker

# ثم شغّل هذه الأوامر
>>> \App\Models\PointRule::count()
>>> \App\Models\LoyaltyTier::count()
>>> \App\Models\LoyaltyReward::count()
>>> exit
```

---

## 📋 البيانات التي سيتم إضافتها

### Point Rules (6 قواعد)
1. **نقاط الطلب** - 1 نقطة لكل جنيه
2. **مكافأة التسجيل** - 50 نقطة
3. **مكافأة أول طلب** - 100 نقطة
4. **نقاط التقييم** - 10 نقاط
5. **مكافأة الإحالة** - 200 نقطة (للمُحيل)
6. **مكافأة المُحال** - 50 نقطة (للمستخدم الجديد)

### Loyalty Tiers (4 مستويات)
1. **Bronze** - 0 نقطة
2. **Silver** - 500 نقطة
3. **Gold** - 2000 نقطة  
4. **Platinum** - 5000 نقطة

### Loyalty Rewards (6 مكافآت)
1. **خصم 5 ج.م** - 100 نقطة
2. **خصم 30 ج.م** - 500 نقطة
3. **خصم 70 ج.م** - 1000 نقطة (مميزة)
4. **شحن مجاني** - 300 نقطة
5. **خصم 15%** - 800 نقطة (Silver فقط)
6. **خصم 25%** - 1500 نقطة (Gold فقط)

---

## ⚠️ ملاحظات مهمة

### ✅ آمن للتشغيل على Production
- الـ Seeder يستخدم `firstOrCreate()` - لن يكرر البيانات
- إذا شغلته مرتين، لن يحدث شيء سيء
- لن يحذف أو يعدل بيانات موجودة

### ✅ لن يؤثر على المستخدمين الحاليين
- المستخدمون القدامى يحتفظون بنقاطهم
- سيتم تطبيق القواعد الجديدة على الطلبات الجديدة فقط

### ⚠️ تحديث البيانات يدوياً (اختياري)
إذا أردت تحديث قاعدة أو مكافأة موجودة:
```bash
# دخول Tinker
php artisan tinker

# مثال: تحديث قيمة قاعدة
>>> $rule = \App\Models\PointRule::where('slug', 'signup_bonus')->first()
>>> $rule->value = 100  // بدلاً من 50
>>> $rule->save()
```

---

## 🔄 إعادة التشغيل (Fresh Start)

⚠️ **خطر! هذا سيحذف كل البيانات**

إذا أردت البدء من الصفر (تطوير فقط):
```bash
php artisan migrate:fresh --seed
```

**للـ Production، استخدم الطريقة الآمنة أعلاه ⬆️**

---

## 🧹 مسح الـ Cache بعد التحديث

```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan view:clear
```

---

## ✅ Checklist

- [ ] تشغيل `php artisan migrate`
- [ ] تشغيل `php artisan db:seed --class=LoyaltySystemSeeder`  
- [ ] التحقق من البيانات عبر Tinker أو Admin Panel
- [ ] مسح الـ Cache
- [ ] اختبار إنشاء قاعدة/مكافأة جديدة
- [ ] التأكد من عمل نظام النقاط

---

## 🆘 استكشاف الأخطاء

### خطأ: "Class LoyaltySystemSeeder not found"
```bash
composer dump-autoload
php artisan db:seed --class=LoyaltySystemSeeder
```

### خطأ: "SQLSTATE[HY000]: Field 'name_ar' doesn't have a default value"
- تأكد من تشغيل الـ migration أولاً: `php artisan migrate`

### البيانات لم تُضاف
- تحقق من الـ output - قد تكون موجودة بالفعل
- استخدم `--force` في production إذا لزم الأمر:
  ```bash
  php artisan db:seed --class=LoyaltySystemSeeder --force
  ```
