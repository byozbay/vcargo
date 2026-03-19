<main class="main-content">

    <!-- ── Page Header ── -->
    <div class="page-header">
        <div>
            <div class="page-title">Yeni Cari Hesap</div>
            <div class="breadcrumb">
                <a href="?page=dashboard">Ana Sayfa</a>
                <span class="sep">›</span>
                <a href="?page=accounts">Cari Hesaplar</a>
                <span class="sep">›</span>
                <span>Yeni Kayıt</span>
            </div>
        </div>
        <a href="?page=accounts" class="btn-outline-secondary-sm d-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i> Geri Dön
        </a>
    </div>

    <div style="padding:18px 26px 40px;">
        <form id="accountForm" onsubmit="submitAccount(event)">
            <div class="row g-3">

                <!-- ══ SOL KOLON ══ -->
                <div class="col-12 col-lg-8">

                    <!-- ── Hesap Türü ── -->
                    <div class="card mb-3">
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <div
                                style="width:32px;height:32px;background:#e8f1ff;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-person-vcard" style="color:#1b84ff;"></i>
                            </div>
                            <div class="section-label">Hesap Türü</div>
                        </div>
                        <div class="row g-2">
                            <div class="col-12 col-md-6">
                                <label class="type-card" id="tc-kurumsal" onclick="selectType('kurumsal')">
                                    <input type="radio" name="account_type" value="kurumsal" style="display:none;"
                                        checked />
                                    <div class="d-flex align-items-center gap-3">
                                        <div
                                            style="width:44px;height:44px;background:#e8f1ff;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                            <i class="bi bi-building" style="color:#1b84ff;font-size:1.1rem;"></i>
                                        </div>
                                        <div>
                                            <div style="font-size:.86rem;font-weight:700;">Kurumsal</div>
                                            <div style="font-size:.73rem;color:var(--text-muted);">Şirket, işletme,
                                                kooperatif</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="type-card" id="tc-bireysel" onclick="selectType('bireysel')">
                                    <input type="radio" name="account_type" value="bireysel" style="display:none;" />
                                    <div class="d-flex align-items-center gap-3">
                                        <div
                                            style="width:44px;height:44px;background:#f3e5f5;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                            <i class="bi bi-person" style="color:#8e24aa;font-size:1.1rem;"></i>
                                        </div>
                                        <div>
                                            <div style="font-size:.86rem;font-weight:700;">Bireysel</div>
                                            <div style="font-size:.73rem;color:var(--text-muted);">Kişisel cari takip
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- ── Firma / Kimlik Bilgileri ── -->
                    <div class="card mb-3">
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <div
                                style="width:32px;height:32px;background:#e7f9f0;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-info-circle" style="color:#0e8045;"></i>
                            </div>
                            <div class="section-label" id="infoSectionTitle">Firma Bilgileri</div>
                        </div>

                        <div class="row g-3">
                            <!-- KURUMSAL alanlar -->
                            <div class="col-12 col-md-8" id="f-companyName">
                                <label class="form-label-sm">Firma Adı <span style="color:#f31260;">*</span></label>
                                <input type="text" id="companyName" class="form-input"
                                    placeholder="ABC Lojistik Ltd. Şti." required />
                            </div>
                            <div class="col-12 col-md-4" id="f-taxNo">
                                <label class="form-label-sm">Vergi No</label>
                                <input type="text" id="taxNo" class="form-input" placeholder="1234567890"
                                    maxlength="10" />
                            </div>
                            <div class="col-12 col-md-6" id="f-taxOffice">
                                <label class="form-label-sm">Vergi Dairesi</label>
                                <input type="text" id="taxOffice" class="form-input" placeholder="Kadıköy VD" />
                            </div>

                            <!-- BİREYSEL alanlar -->
                            <div class="col-12 col-md-6" id="f-fullName" style="display:none;">
                                <label class="form-label-sm">Ad Soyad <span style="color:#f31260;">*</span></label>
                                <input type="text" id="fullName" class="form-input" placeholder="Ad Soyad" />
                            </div>
                            <div class="col-12 col-md-4" id="f-tcNo" style="display:none;">
                                <label class="form-label-sm">T.C. Kimlik No</label>
                                <input type="text" id="tcNo" class="form-input" placeholder="12345678901"
                                    maxlength="11" />
                            </div>

                            <!-- Ortak -->
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Yetkili Kişi</label>
                                <input type="text" id="contactPerson" class="form-input" placeholder="Mehmet Kara" />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">Telefon <span style="color:#f31260;">*</span></label>
                                <input type="tel" id="phone" class="form-input" placeholder="0 5xx xxx xx xx"
                                    required />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-sm">E-posta</label>
                                <input type="email" id="email" class="form-input" placeholder="bilgi@firma.com" />
                            </div>
                            <div class="col-12">
                                <label class="form-label-sm">Adres</label>
                                <input type="text" id="address" class="form-input"
                                    placeholder="Mahalle, Cadde/Sokak, Bina No, İl/İlçe" />
                            </div>
                        </div>
                    </div>

                    <!-- ── Cari Limit & Ödeme Koşulları ── -->
                    <div class="card mb-3">
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <div
                                style="width:32px;height:32px;background:#fff8ec;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-credit-card-2-front" style="color:#e08b00;"></i>
                            </div>
                            <div class="section-label">Cari Limit & Ödeme Koşulları</div>
                        </div>
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label class="form-label-sm">Kredi Limiti (₺) <span
                                        style="color:#f31260;">*</span></label>
                                <input type="number" id="creditLimit" class="form-input" placeholder="5000" min="0"
                                    step="100" oninput="updateSummary()" required />
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label-sm">Vade Günü</label>
                                <input type="number" id="paymentDays" class="form-input" value="30" min="0" max="365"
                                    oninput="updateSummary()" />
                                <div style="font-size:.71rem;color:var(--text-muted);margin-top:3px;">Fatura
                                    kesilmesinden itibaren ödeme günü</div>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label-sm">Aylık Faiz Oranı (%)</label>
                                <div style="position:relative;">
                                    <input type="number" id="interestRate" class="form-input" value="0" min="0"
                                        max="100" step="0.5" style="padding-right:30px;" />
                                    <span
                                        style="position:absolute;right:11px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:.8rem;">%</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label-sm mb-2">İzin Verilen Ödeme Yöntemleri</label>
                                <div class="d-flex flex-wrap gap-2">
                                    <label class="check-chip active" id="pm-cari" onclick="toggleChip(this)"><i
                                            class="bi bi-journal-text me-1"></i>Cari Hesap</label>
                                    <label class="check-chip" id="pm-nakit" onclick="toggleChip(this)"><i
                                            class="bi bi-cash me-1"></i>Nakit</label>
                                    <label class="check-chip" id="pm-kart" onclick="toggleChip(this)"><i
                                            class="bi bi-credit-card me-1"></i>Kredi Kartı</label>
                                    <label class="check-chip" id="pm-havale" onclick="toggleChip(this)"><i
                                            class="bi bi-bank me-1"></i>Havale/EFT</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── Notlar ── -->
                    <div class="card">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div
                                style="width:32px;height:32px;background:#f3e5f5;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-sticky" style="color:#8e24aa;"></i>
                            </div>
                            <div class="section-label">Notlar</div>
                        </div>
                        <textarea id="notes" class="form-input" rows="3"
                            placeholder="Müşteri hakkında özel notlar, ödeme alışkanlıkları, iletişim tercihleri..."
                            style="resize:vertical;"></textarea>
                    </div>

                </div><!-- /col-8 -->

                <!-- ══ SAĞ KOLON ══ -->
                <div class="col-12 col-lg-4">

                    <!-- Özet Kartı -->
                    <div class="card mb-3" style="background:var(--body-bg);">
                        <div class="section-label mb-3">Hesap Özeti</div>
                        <div class="d-flex flex-column gap-2" style="font-size:.83rem;">
                            <div class="d-flex justify-content-between">
                                <span style="color:var(--text-muted);">Tür</span>
                                <span id="prev-type" style="font-weight:600;">Kurumsal</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span style="color:var(--text-muted);">Kredi Limiti</span>
                                <span id="prev-limit" style="font-weight:600;color:var(--accent-blue);">—</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span style="color:var(--text-muted);">Vade</span>
                                <span id="prev-days" style="font-weight:600;">30 gün</span>
                            </div>
                            <hr style="border-color:var(--border-color);margin:4px 0;" />
                            <div class="d-flex justify-content-between">
                                <span style="color:var(--text-muted);">Mevcut Bakiye</span>
                                <span style="font-weight:700;color:#0e8045;">₺0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bilgi Notu -->
                    <div
                        style="padding:12px 14px;background:#e8f1ff;border-radius:8px;font-size:.78rem;color:#145dc0;margin-bottom:12px;border:1px solid #bfdbfe;">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        Cari hesap oluşturulduktan sonra ilgili müşterinin kargo ödemelerinde <strong>"Cari
                            Hesap"</strong> seçeneği otomatik aktif olur.
                    </div>

                    <!-- Aksiyon -->
                    <div class="d-flex flex-column gap-2">
                        <button type="submit" class="btn-primary-sm w-100"
                            style="height:44px;font-size:.9rem;border-radius:8px;">
                            <i class="bi bi-check2-circle me-1"></i> Cari Hesabı Oluştur
                        </button>
                        <button type="reset" onclick="resetForm()" class="btn-outline-secondary-sm w-100"
                            style="height:36px;font-size:.82rem;border-radius:8px;color:var(--text-muted);">
                            <i class="bi bi-x-lg me-1"></i> Temizle
                        </button>
                    </div>

                </div><!-- /col-4 -->

            </div>
        </form>
    </div><!-- /body -->

</main>

<style>
    .form-label-sm {
        display: block;
        font-size: .72rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: .03em;
        margin-bottom: 4px;
    }

    .form-input {
        width: 100%;
        border: 1px solid var(--border-color);
        border-radius: 7px;
        padding: 8px 12px;
        font-size: .84rem;
        background: var(--body-bg);
        color: var(--text-dark);
        outline: none;
        transition: border-color .15s, box-shadow .15s;
        font-family: 'Inter', sans-serif;
    }

    .form-input:focus {
        border-color: var(--accent-blue);
        box-shadow: 0 0 0 3px rgba(27, 132, 255, .12);
    }

    /* Tür Kartları */
    .type-card {
        display: block;
        cursor: pointer;
        border: 2px solid var(--border-color);
        border-radius: 9px;
        padding: 12px 14px;
        transition: border-color .15s, background .15s;
        height: 100%;
    }

    .type-card:hover {
        background: var(--body-bg);
    }

    .type-card.active {
        border-color: var(--accent-blue);
        background: #f0f6ff;
    }

    body.dark .type-card.active {
        background: rgba(27, 132, 255, .12);
    }

    /* Ödeme Yöntemi Chip */
    .check-chip {
        display: inline-flex;
        align-items: center;
        cursor: pointer;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: .78rem;
        font-weight: 600;
        border: 1.5px solid var(--border-color);
        background: var(--body-bg);
        color: var(--text-muted);
        transition: all .15s;
        user-select: none;
    }

    .check-chip.active {
        border-color: var(--accent-blue);
        background: #e8f1ff;
        color: var(--accent-blue);
    }

    body.dark .check-chip.active {
        background: rgba(27, 132, 255, .15);
    }
</style>

<script>
    var currentType = 'kurumsal';

    /* ── Hesap Türü ── */
    function selectType(type) {
        currentType = type;
        document.getElementById('tc-kurumsal').classList.toggle('active', type === 'kurumsal');
        document.getElementById('tc-bireysel').classList.toggle('active', type === 'bireysel');

        var kurumsalFields = ['f-companyName', 'f-taxNo', 'f-taxOffice'];
        var bireyselFields = ['f-fullName', 'f-tcNo'];

        kurumsalFields.forEach(function (id) { document.getElementById(id).style.display = type === 'kurumsal' ? 'block' : 'none'; });
        bireyselFields.forEach(function (id) { document.getElementById(id).style.display = type === 'bireysel' ? 'block' : 'none'; });

        document.getElementById('infoSectionTitle').textContent = type === 'kurumsal' ? 'Firma Bilgileri' : 'Bireysel Bilgiler';
        document.getElementById('prev-type').textContent = type === 'kurumsal' ? 'Kurumsal' : 'Bireysel';

        /* Zorunluluk */
        document.getElementById('companyName').required = (type === 'kurumsal');
        document.getElementById('fullName').required = (type === 'bireysel');
    }

    /* ── Ödeme Chip ── */
    function toggleChip(el) { el.classList.toggle('active'); }

    /* ── Özet Güncelle ── */
    function updateSummary() {
        var limit = parseFloat(document.getElementById('creditLimit').value) || 0;
        var days = parseInt(document.getElementById('paymentDays').value) || 0;
        document.getElementById('prev-limit').textContent = limit > 0 ? '₺' + limit.toLocaleString('tr-TR') : '—';
        document.getElementById('prev-days').textContent = days + ' gün';
    }

    ['creditLimit', 'paymentDays'].forEach(function (id) {
        document.getElementById(id).addEventListener('input', updateSummary);
    });

    /* ── Submit ── */
    function submitAccount(e) {
        e.preventDefault();
        showToast('✓ Cari hesap başarıyla oluşturuldu!', 'success');
        /* TODO: AJAX / form POST */
    }

    function resetForm() {
        selectType('kurumsal');
        document.getElementById('prev-limit').textContent = '—';
        document.getElementById('prev-days').textContent = '30 gün';
        document.querySelectorAll('.check-chip').forEach(function (c, i) { c.classList.toggle('active', i === 0); });
    }

    /* ── Toast ── */
    function showToast(msg, type) {
        var colors = { success: '#0e8045', error: '#c03060', info: '#1b84ff' };
        var t = document.createElement('div');
        t.style.cssText = 'position:fixed;bottom:24px;right:24px;padding:11px 18px;border-radius:8px;font-size:.82rem;font-weight:600;z-index:9999;box-shadow:0 4px 14px rgba(0,0,0,.18);color:#fff;background:' + (colors[type] || '#333') + ';transition:opacity .3s;';
        t.textContent = msg;
        document.body.appendChild(t);
        setTimeout(function () { t.style.opacity = '0'; setTimeout(function () { t.remove(); }, 300); }, 3000);
    }

    /* İlk yükleme */
    selectType('kurumsal');
</script>