# vCargo — Proje Kuralları ve İş Akışı Gerçekleri

## 1. Proje Vizyonu
vCargo; şehirlerarası otobüs terminallerinde faaliyet gösteren emanet ve kargo transfer dükkanları için geliştirilmiş bir ERP sistemidir.

**Temel iş modeli:**
- Gönderici kargosunu dükana bırakır
- Dükkan uygun otobüs firmasıyla anlaşır ve kargoya yer sağlar
- Kargo varış terminalinde indirilir, alıcı gelene kadar emanette bekler
- Alıcı geldiğinde emanet + kargo ücreti (C.O.D ise) tahsil edilerek teslim edilir

---

## 2. Yazıcı ve Belge Standartları

### Kargo Etiketi (Label)
- **Yazıcı tipi:** Termal yazıcı (ESC/POS veya ZPL uyumlu)
- **Format:** Barkodlu, 80mm genişlik termal şerit
- İçerik: Takip no, gönderici, alıcı, varış şehri, ücret, tarih

### Sevk İrsaliyesi (Manifesto)
- **Yazıcı tipi:** Normal/lazer yazıcı (A4)
- **Format:** Tarayıcı print diyaloğu ile PDF/print
- İçerik: Sefer bilgisi (plaka, firma, kalkış), kargo listesi, toplam tutar, şoföre ödenecek net tutar
- Şoför imzalayıp alır, dükkan kopyası dosyalanır

### Fatura / Faturalama
- **HAYIR — fatura kesilmez**
- Yalnızca kargo fişi ve sevk irsaliyesi vardır

---

## 3. Fiyatlandırma Kuralları

### Emanet Tarifeleri
- Her yıl ilgili şehrin **Trafik ve Ulaşım Komisyonu** tarafından belirlenir
- Admin panelden girilir: `pricing_rules` tablosu
- **Şube override:** Bazı şubeler bağımsız (özerk) çalışabilir, kendi tarifelerini belirleyebilir
- Öncelik sırası: Şube tarifesi > Bölge tarifesi > Sistem varsayılanı

### Kargo Taşıma Ücretleri
- Şu an manuel girilir (formda `cargo_fee` alanı)
- Gelecekte opsiyonel tarife tablosu eklenebilir

---

## 4. C.O.D (Alıcı Öder) Muhasebe Mantığı

```
Kargo kabul → payment_type = 'RECEIVER_PAYS'
  ↓
Kargo yolda → KASA KAYDI YOK (sadece alacak bekliyor)
  ↓
Teslim anında → tahsilat yapılır → KASA GİRİŞİ oluşur
  ↓
payment_status = 'paid'
```

**Kritik:** C.O.D kargoların `transactions` kaydı yalnızca teslimatta oluşturulmalıdır. Otobüşe yüklendiğinde değil.

---

## 5. Kasa Akış Kuralları

| İşlem | Kasa Etkisi |
|-------|-------------|
| Gönderici öder (Nakit/Kart) | Kasa GİRİŞİ — kabul anında |
| Alıcı öder (C.O.D) | Kasa GİRİŞİ — **teslim anında** |
| Cari hesap | Kasa kaydı yok, hesap bakiyesi güncellenir |
| Şoföre ödeme (net tutar) | Kasa ÇIKIŞ — sevk irsaliyesi onayında |
| Emanet ücreti | Kasa GİRİŞİ — teslim anında |
| Şube giderleri | Kasa ÇIKIŞ — manuel giriş |

---

## 6. Barkod / Takip No Girişi

- **Barkod okuyucu** (USB/Bluetooth) desteklenir — input alanına focus yeterli
- **Manuel giriş** de her zaman mümkün olmalı
- Takip no formatı: `TRK-YYMMDD-XXX`

---

## 7. Şube Yapısı Gerçeği

- Her terminalde **tek dükkan** vardır — iki dükkan aynı terminali paylaşmaz
- Multi-branch-per-terminal senaryosu geçersiz
- Franchise: Şubeler merkeze bağlı veya özerk olabilir ama tek terminal = tek şube

---

## 8. Kritik İş Akışları

### 8.1 Kargo Kabul → Sevk Zinciri
```
1. Kargo Kabul (shipment_create) → tracking_no atanır, etiket basılır
2. Sevk (dispatch) → sefere atanır, manifesto basılır, şoföre net ödemr kasa çıkışı
3. Kargo yolda → status: in_transit
4. Varış (arrival) → status: at_branch → emanet sayacı başlar
5. Teslim (delivery) → emanet + C.O.D tahsil → kasa girişi → status: delivered
```

### 8.2 Bağımsız Emanet (Yolcu Bagajı)
```
1. Hızlı kayıt → ad, telefon, eşya tipi, raf no
2. Fiş basılır (QR kodlu)
3. Teslim → fiş okutulur → süre hesaplanır → ücret tahsil → kasa girişi
```

---

## 9. Modül Öncelikleri (Konuşma Sonrası Güncellenmiş)

| Öncelik | Modül | Durum |
|---------|-------|-------|
| 🔴 1 | Sevk (Dispatch) + Manifesto | Eksik |
| 🔴 2 | Teslim (Delivery) + Kasa Entegrasyonu | Eksik |
| 🟡 3 | Tarife Yönetimi (Pricing Admin) | Kısmi |
| 🟡 4 | Kasa Kapanışı | Eksik |
| 🟢 5 | Kargo Takip (Public) | Eksik |
| 🟢 6 | Raporlama | Eksik |

---

## 10. Teknoloji Notları

- **Backend:** PHP 8.2, custom MVC, PDO
- **DB:** MySQL/MariaDB, snake_case
- **Frontend:** Bootstrap 5 + custom CSS, dark mode
- **Yazıcı:** Termal → ESC/POS JS kütüphanesi veya PHP ile ZPL/raw print
- **Barkod:** Browser `input` focus yöntemi + isteğe bağlı QuaggaJS
