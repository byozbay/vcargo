---
trigger: always_on
---

# vCargo - Otogar Lojistik Yönetim Sistemi (Project Rules)

## 1. Proje Vizyonu ve Amacı
vCargo (Otogar Lojistik Yönetim Sistemi); otobüs terminalleri üzerinden şehirler arası kargo taşımacılığı, emanet/depo yönetimi ve B2B kurumsal cari takibi yapan profesyonel bir ERP sistemidir. Sistem; merkez, bölge müdürlükleri, şubeler (bayi/merkez) ve otobüs firmaları arasındaki karmaşık finansal ve operasyonel süreçleri yönetir.

**Temel Hedef:** Karayolu Taşıma Yönetmeliği'ne uygun, finansal denetlenebilirliği yüksek, mobil uyumlu ve kullanıcı dostu bir lojistik platformu sağlamak.

## 2. Teknoloji Stack'i
- **Backend**: PHP 8.2+ (Typed Properties, Strict Types)
- **Mimari**: Custom MVC (Model-View-Controller)
- **Veritabanı**: MySQL / MariaDB (InnoDB Engine)
- **Frontend**:
  - **Framework**:materialize-html-admin-template/: https://demos.pixinvent.com/materialize-html-admin-template/html/horizontal-menu-template/app-logistics-dashboard.html
  - **Scripting**: jQuery, Vanilla JS
  - **CSS**: Custom CSS + Bootstrap Utilities
- **Tasarım Dili**:
-/template:
  - Horizontal Menu Layout
  - Kurumsal Minimal Tasarım
  - Keskin Kenarlar (`border-radius: 0`)
  - Dark/Light Mode Desteği
- **3. Parti Entegrasyonlar**:
  - Termal Yazıcı (ZPL/PDF)
  - SMS Gateway (Rutin bildirimler)

## 3. Klasör Yapısı (MVC Standards)
```
/
├── app/
│   ├── Controllers/    # PHP 8.2 Typed Controllers (PascalCase)
│   ├── Models/         # Database Entities & Repositories
│   ├── Services/       # Business Logic Layer (Optional)
│   └── Core/           # Database.php (PDO), Router.php, App.php, Auth.php
├── public/
│   ├── assets/         # CSS, JS, Images (Theme Assets)
│   └── index.php       # Entry Point
├── views/
│   ├── brunch/         # Feature specific views
│   ├── shared/         # Sidebar, Header, Footer partials
│   └── layout.php      # Master Layout
└── .agent/rules.md     # Project Context & Rules
```

## 4. Modüller ve Kapsam

### A. Organizasyon Yapısı (Multi-Hierarchy)
1.  **Merkez Yönetimi (Admin)**: Tüm sistemi, bölgeleri, şubeleri ve genel finansı yönetir.
2.  **Bölge Müdürlükleri**: Kendisine bağlı şehir ve şubelerin operasyonunu ve karlılığını denetler. Bölgesel fiyat politikaları belirleyebilir.
3.  **Şubeler**: Kargo kabul, sevk ve teslimat işlemlerini yapar.
    *   **Tipler**: CORPORATE (Merkez), FRANCHISE (Bayi).
4.  **Depo/Emanet**: Şube içindeki fiziksel alan yönetimi.

### B. Operasyon Yönetimi
*   **Kargo Kabul**:
    *   Gönderici/Alıcı T.C. veya Vergi No zorunluluğu (Yasal).
    *   Ödeme: Gönderici Öder, Alıcı Öder (C.O.D), Cari Hesap.
    *   Ek Hizmetler: Kurye Alım, SMS Bildirim, VIP Telefon Bildirim.
    *   Barkod: Termal çıktı.
*   **Sevk (Otobüs Entegrasyonu)**:
    *   Otobüs Firması/Plaka seçimi.
    *   **Komisyon**: (Toplam Tutar - Firma Komisyonu) = Otobüse Ödenecek Net Tutar.
    *   **Manifesto**: Otobüs teslim fişi basımı.
*   **Varış ve Emanet**:
    *   **Emanet Sayacı**: Kargo şubeye indiği an başlar. Opsiyonel "X saat ücretsiz" süresi (Şube/Bölge ayarlı).
    *   **Bağımsız Emanet**: Yolcu bavulu/çantası için kargo harici depolama. Saatlik/Günlük tarife.

### C. Finans ve Muhasebe
*   **Ödeme Yöntemleri**: Nakit, Kredi Kartı, Cari.
*   **Parçalı Ödeme (Split Payment)**: Bir işlem için kısmi Nakit, kısmi Kredi Kartı tahsilatı.
*   **Kasa Yönetimi**: Her şubenin kendi sanal kasası.
    *   Giriş: Kargo ücreti, Emanet ücreti.
    *   Çıkış: Otobüsçüye ödenen net tutar, genel giderler.
*   **Cari Hesap**: Kurumsal firmalar ve sık gönderim yapan esnaflar için borç/alacak takibi.
*   **Dinamik Fiyatlandırma**: Bölge bazlı emanet birim fiyatları.

## 5. Kullanıcı Rolleri ve Yetki (RBAC)

| Rol | Yetki Özeti |
| :--- | :--- |
| **Super Admin** | Tam yetki. Sistem ayarları, tüm data erişimi. |
| **Muhasebe** | Para akışı, fatura, cari mutabakat, kar/zarar raporları. |
| **Bölge Müdürü** | Bölge raporları, bölgesel fiyat belirleme (override), şube denetimi. |
| **Şube Müdürü** | Şube personeli yönetimi, kasa kontrolü, emanet inisiyatifi (indirim/sıfırlama). |
| **Personel** | Kargo kabul/teslim, barkod basımı. |
| **Kurye** | Mobil ekran, alım/dağıtım onayı. |

## 6. Veritabanı ve Kod Standartları

### Naming Conventions
*   **Database Tables & Columns**: `snake_case` (e.g., `shipments`, `tracking_no`, `total_price`).
*   **PHP Variables & Functions**: `camelCase` (e.g., `$totalPrice`, `calculateCommission()`).
*   **Classes**: `PascalCase` (e.g., `ShipmentController`).
*   **UI Text**: **Türkçe** (Kullanıcı arayüzü).
*   **Code Comments/Names**: **İngilizce**.

### Kritik Tablolar
*   `accounts`: Müşteriler (Bireysel/Kurumsal).
*   `shipments`: Kargo kayıtları (status, tracking_no, payment_status).
*   `transactions`: Finansal hareketler (type: IN/OUT, method: CASH/CARD, amount).
*   `vault_transactions`: Kasa hareketleri.
*   `branches`: Şube tanımları.
*   `trips`: Sefer/Otobüs bilgileri (Plaka, Şoför, Firma).
*   `storage_records`: Emanet kayıtları.
*   `audit_logs`: Güvenlik ve işlem logları (Kim, ne zaman, ne yaptı?).

### Güvenlik Kuralları
*   **Soft Delete**: Finansal kayıtlar asla silinmez (`is_active = 0` veya `deleted_at`).
*   **SQL Injection**: PDO Prepared Statements zorunlu.
*   **XSS**: Output escaping.

## 7. Frontend Tasarım Kuralları
*   **Theme**: Sneat Bootstrap 5 Horizontal Menu.
*   **Renk Paleti**:
    *   Dark Mode: `#0f172a` (Primary BG), `#1e293b` (Secondary BG).
    *   Light Mode: `#ffffff` (Primary BG), `#f8f9fa` (Secondary BG).
    *   Accent: `#64748b` (Gray), `#2563eb` (Blue - Active/Hover).
*   **Stil**:
    *   `border-radius: 0` (Keskin kenarlar).
    *   Responsive (Mobile-first).
    *   Kullanıcı dostu formlar (Validation mesajları Türkçe).

## 8. İş Akışları (Workflows)

### 8.1. Kargo Kabul & Sevk
1.  **Giriş**: Gönderici/Alıcı bilgileri girilir. Ödeme tipi seçilir.
2.  **Etiket**: Barkod basılır, kargoya yapıştırılır.
3.  **Sevk**: Otobüs firması/plaka seçilir. Komisyon düşülerek şoföre ödenecek tutar hesaplanır.
4.  **Çıktı**: Otobüs Teslim Fişi (Manifesto) basılır.
5.  **Finans**: Kargo ücreti kasaya girer (Peşin ise), Otobüs ödemesi kasadan çıkar.

### 8.2. Varış & Emanet
1.  **Varış**: Şube barkodu okutur -> "Emanette" statüsü.
2.  **Sayaç**: Emanet süresi başlar.
3.  **Teslimat**: Alıcı gelir.
    *   Varsa Emanet Ücreti hesaplanır.
    *   Varsa Kapıda Ödeme (C.O.D) tahsil edilir.
    *   Emanet ücretinde indirim yapılacaksa yönetici onayı/log kaydı alınır.
4.  **Kapanış**: Kargo "Teslim Edildi" statüsüne geçer.

### 8.3. Bağımsız Emanet (Yolcu Bagajı)
1.  **Hızlı Kayıt**: Ad, Telefon, Eşya Tipi (Bavul/Koli).
2.  **Konum**: Raf/Dolap No girilir.
3.  **Fiş**: QR kodlu fiş müşteriye verilir.
4.  **Teslim**: Fiş okutulur, süreye göre ücret tahsil edilir.